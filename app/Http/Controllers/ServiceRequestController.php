<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ServiceRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();


        if (in_array($user->role, ['client', 'user'])) {
            $requests = ServiceRequests::where('user_id', $user->id)
                ->latest()
                ->get();

            return view('service.user_index', compact('requests'));
        }


        if ($user->role === 'provider') {
            $requests = ServiceRequests::where(function ($q) use ($user) {
                $q->where('status', 'pending')
                    ->whereHas('category', function ($q2) use ($user) {
                        $q2->whereIn(
                            'categories.id',
                            $user->categories->pluck('id')
                        );
                    });
            })
                ->orWhere(function ($q) use ($user) {
                    $q->where('provider_id', $user->id)
                        ->whereIn('status', ['accepted', 'provider_done']);
                })
                ->latest()
                ->get();

            return view('service.provider_index', compact('requests'));
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.service-requests.index');
        }

        abort(403);
    }


    public function create()
    {
        $categories = Category::all();
        return view('service.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'phone' => 'nullable|string|max:25',
            'city' => 'required|string|max:100',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (!in_array($user->role, ['client', 'user', 'admin'])) {
            abort(403);
        }

        if (!$user->telegram_id && empty($request->phone)) {
            return back()->withInput()->withErrors(['phone' => __('Iltimos, telefon raqamingizni kiriting yoki Telegram profilingizni ulang.')]);
        }

        if (ServiceRequests::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists()
        ) {
            return back()->withInput()->withErrors(['general' => __('Sizda allaqachon ochiq so‘rov mavjud. Yangi so\'rov yuborishdan oldin amaldagisini bekor qiling yoki kuting.')]);
        }

        $category = Category::find($request->category_id);

        $serviceRequest = ServiceRequests::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'phone' => $request->phone ?: ($user->phone ?? 'Telegram orqali'),
            'city' => $request->city,
            'comment' => $request->comment,
            'email' => $user->email,
            'status' => 'pending',
        ]);

        // Send Telegram notification to all matching providers who offer this category
        $providers = User::where('role', 'provider')
            ->whereNotNull('telegram_id')
            ->whereHas('categories', function ($q) use ($serviceRequest) {
                $q->where('categories.id', $serviceRequest->category_id);
            })
            ->get();

        $categoryName = $category ? $category->name : 'Xizmat';
        $commentText = $serviceRequest->comment ? "\n💬 Izoh: {$serviceRequest->comment}" : "";
        $phoneText = $serviceRequest->phone ? "\n📞 Telefon: {$serviceRequest->phone}" : "";
        $telegramText = $user->telegram_id ? "\n📱 Telegram: @ustatpuzbot orqali ulangan" : "";

        $message = "🛠 Yangi buyurtma keldi!\n\n" .
            "📂 Soha: {$categoryName}\n" .
            "👤 Mijoz: {$user->name}\n" .
            "✉️ Email: {$serviceRequest->email}" .
            $phoneText .
            $telegramText . "\n" .
            "📍 Shahar: {$serviceRequest->city}" .
            $commentText . "\n\n" .
            "🆔 Buyurtma ID: #{$serviceRequest->id}\n" .
            "👉 Ishni qabul qilish uchun: https://ustatop-lmze.onrender.com/service-requests";

        foreach ($providers as $provider) {
            $this->sendTelegram($provider->telegram_id, $message);
        }

        return redirect()->route('service-requests.index')
            ->with('success', __('So‘rov muvaffaqiyatli yaratildi va sohangizdagi barcha ustalarga yuborildi!'));
    }

    public function destroy(ServiceRequests $serviceRequest)
    {
        $user = auth()->user();

        abort_if(
            $serviceRequest->user_id !== $user->id ||
                !in_array($serviceRequest->status, ['completed', 'cancelled', 'pending']),
            403
        );

        $serviceRequest->delete();

        return back()->with('success', __('So‘rov o‘chirildi'));
    }

    private function sendTelegram($chatId, $text)
    {
        $token = config('services.telegram.token');

        if (!$token || !$chatId) {
            return;
        }

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Telegram send failed: ' . $e->getMessage());
        }
    }

    public function accept(ServiceRequests $serviceRequest)
    {
        $provider = auth()->user();

        abort_if($serviceRequest->status !== 'pending', 403);

        abort_if(
            !$provider->categories->pluck('id')->contains($serviceRequest->category_id),
            403
        );

        $serviceRequest->update([
            'status' => 'accepted',
            'provider_id' => $provider->id,
        ]);

        if ($serviceRequest->user && $serviceRequest->user->telegram_id) {
            $this->sendTelegram(
                $serviceRequest->user->telegram_id,
                "✅ Sizning #{$serviceRequest->id} buyurtmangiz usta {$provider->name} tomonidan qabul qilindi va jarayonga o'tdi!\n\nBog'lanish: {$provider->email}\nTez orada usta siz bilan bog'lanadi."
            );
        }

        return back()->with('success', __('Ish muvaffaqiyatli qabul qilindi. Jarayon boshlandi!'));
    }

    public function cancel(ServiceRequests $serviceRequest)
    {
        $provider = auth()->user();

        abort_if(
            $serviceRequest->status !== 'accepted' ||
                $serviceRequest->provider_id !== $provider->id,
            403
        );

        // When provider says work could not be completed, reopen the order for others
        $serviceRequest->update([
            'status' => 'pending',
            'provider_id' => null,
        ]);

        if ($serviceRequest->user && $serviceRequest->user->telegram_id) {
            $this->sendTelegram(
                $serviceRequest->user->telegram_id,
                "ℹ️ Sizning #{$serviceRequest->id} buyurtmangiz bo'yicha usta ishni bajara olmadi. So'rovingiz boshqa ustalar qabul qilishi uchun qayta ochildi."
            );
        }

        return back()->with('success', __('Ish bekor qilindi va boshqa ustalar uchun qayta ochildi.'));
    }

    public function providerDone(ServiceRequests $serviceRequest)
    {
        $provider = auth()->user();

        abort_if(
            $serviceRequest->status !== 'accepted' ||
                $serviceRequest->provider_id !== $provider->id,
            403
        );

        $serviceRequest->update([
            'status' => 'provider_done',
        ]);

        if ($serviceRequest->user && $serviceRequest->user->telegram_id) {
            $this->sendTelegram(
                $serviceRequest->user->telegram_id,
                "🛠 Usta {$provider->name} #{$serviceRequest->id} buyurtma bo'yicha ishni tugatganini bildirdi.\n\nIltimos, saytga kirib natijani tasdiqlang: https://ustatop-lmze.onrender.com/service-requests"
            );
        }

        return back()->with('success', __('Ish tugatilgani belgilandi. Mijoz tasdiqlashi kutilmoqda.'));
    }

    public function clientConfirm(ServiceRequests $serviceRequest)
    {
        $user = auth()->user();

        abort_if(
            $serviceRequest->status !== 'provider_done' ||
                $serviceRequest->user_id !== $user->id,
            403
        );

        $serviceRequest->update([
            'status' => 'completed',
        ]);

        if ($serviceRequest->provider && $serviceRequest->provider->telegram_id) {
            $this->sendTelegram(
                $serviceRequest->provider->telegram_id,
                "🎉 Mijoz #{$serviceRequest->id} buyurtmani muvaffaqiyatli yakunlangan deb tasdiqladi! Mehnat faoliyatingiz uchun rahmat."
            );
        }

        return back()->with('success', __('Ish muvaffaqiyatli yakunlandi va tasdiqlandi!'));
    }

    public function clientReject(ServiceRequests $serviceRequest)
    {
        $user = auth()->user();

        abort_if(
            $serviceRequest->status !== 'provider_done' ||
                $serviceRequest->user_id !== $user->id,
            403
        );

        $prevProvider = $serviceRequest->provider;

        // Reopen for other providers
        $serviceRequest->update([
            'status' => 'pending',
            'provider_id' => null,
        ]);

        if ($prevProvider && $prevProvider->telegram_id) {
            $this->sendTelegram(
                $prevProvider->telegram_id,
                "⚠️ Mijoz #{$serviceRequest->id} buyurtma bo'yicha ish to'liq yakunlanmaganini bildirdi. Buyurtma qayta ochiq holatga o'tkazildi."
            );
        }

        return back()->with('success', __('Ish yakunlanmadi deb belgilandi va so\'rovingiz boshqa ustalar uchun qayta ochildi.'));
    }
}
