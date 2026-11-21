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
            'phone' => 'required|string|max:13',
            'city' => 'required|string|max:100',
        ]);

        if ($user->role !== 'client') {
            abort(403); // faqat oddiy foydalanuvchilar uchun sybauuuuu
        }

        if (ServiceRequests::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists()
        ) {
            return back()->withErrors('Sizda allaqachon ochiq so‘rov bor');
        }

        $serviceRequest = ServiceRequests::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'phone' => $request->phone,
            'city' => $request->city,
            'email' => $user->email,
            'status' => 'pending',
        ]);

        $providers = User::where('role', 'provider')
            ->whereNotNull('telegram_id')
            ->whereHas('categories', function ($q) use ($serviceRequest) {
                $q->where('categories.id', $serviceRequest->category_id);
            })
            ->get();

        foreach ($providers as $provider) {
            $this->sendTelegram(
                $provider->telegram_id,
                "🛠 Yangi ish bor!\n\n" .
                    "📍 Shahar: {$serviceRequest->city}\n" .
                    "📞 Telefon: {$serviceRequest->phone}\n" .
                    "📩 Email: {$serviceRequest->email}\n" .
                    "🆔 Request ID: {$serviceRequest->id}" .
                    "Qabul qilish uchun login qilib, so‘rovlar bo‘limiga o‘ting."
            );
        }


        return redirect()->route('service-requests.index')
            ->with('success', 'So‘rov muvaffaqiyatli yaratildi');
    }

    // public function show(ServiceRequests $serviceRequest)
    // {
    //     return view('service.show', compact('serviceRequest'));
    // }

    public function destroy(ServiceRequests $serviceRequest)
    {
        $user = auth()->user();

        abort_if(
            $serviceRequest->user_id !== $user->id ||
                !in_array($serviceRequest->status, ['completed','cancelled']),
            403
        );

        $serviceRequest->delete();

        return back()->with('success', 'So‘rov o‘chirildi');
    }



    private function sendTelegram($chatId, $text)
    {
        $token = config('services.telegram.token');

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
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
                "✅ Sizning #{$serviceRequest->id} so'rovingiz usta {$provider->name} tomonidan qabul qilindi!\n\nTez orada usta siz bilan bog'lanadi."
            );
        }

        return back()->with('success', 'Ish qabul qilindi');
    }

    public function cancel(ServiceRequests $serviceRequest)
    {
        $provider = auth()->user();

        abort_if(
            $serviceRequest->status !== 'accepted' ||
                $serviceRequest->provider_id !== $provider->id,
            403
        );

        $serviceRequest->update([
            'status' => 'cancelled',
            'provider_id' => null,
        ]);

        if ($serviceRequest->user && $serviceRequest->user->telegram_id) {
            $this->sendTelegram(
                $serviceRequest->user->telegram_id,
                "⚠️ Sizning #{$serviceRequest->id} buyurtmangiz usta tomonidan bekor qilindi. Boshqa usta topishingiz mumkin."
            );
        }

        return back()->with('success', 'Ish bekor qilindi');
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
                "🛠 Usta {$provider->name} #{$serviceRequest->id} buyurtmani bajarganini bildirdi.\n\nIltimos, saytga kirib natijani tasdiqlang: https://ustatop-lmze.onrender.com"
            );
        }

        return back()->with('success', 'Ish tugatilgani belgilandi');
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

        return back()->with('success', 'Ish muvaffaqiyatli yakunlandi');
    }
}
