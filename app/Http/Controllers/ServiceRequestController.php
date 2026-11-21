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

        foreach ($providers as $provider) {
            $locale = $this->getTelegramLocaleForUser($provider);
            $catName = $category ? $category->getLocalizedName($locale) : ($locale === 'ru' ? 'Услуга' : ($locale === 'en' ? 'Service' : 'Xizmat'));

            if ($locale === 'ru') {
                $phoneText = $serviceRequest->phone ? "\n📞 Телефон: {$serviceRequest->phone}" : "";
                $telegramText = $user->telegram_id ? "\n📱 Telegram: подключен через бота" : "";
                $commentText = $serviceRequest->comment ? "\n💬 Комментарий: {$serviceRequest->comment}" : "";
                $msg = "🛠 Новый заказ!\n\n" .
                    "📂 Категория: {$catName}\n" .
                    "👤 Клиент: {$user->name}\n" .
                    "✉️ Email: {$serviceRequest->email}" .
                    $phoneText . $telegramText . "\n" .
                    "📍 Город: {$serviceRequest->city}" .
                    $commentText . "\n\n" .
                    "🆔 ID заказа: #{$serviceRequest->id}\n" .
                    "👉 Принять заказ: https://ustatop-lmze.onrender.com/service-requests";
            } elseif ($locale === 'en') {
                $phoneText = $serviceRequest->phone ? "\n📞 Phone: {$serviceRequest->phone}" : "";
                $telegramText = $user->telegram_id ? "\n📱 Telegram: connected via bot" : "";
                $commentText = $serviceRequest->comment ? "\n💬 Comment: {$serviceRequest->comment}" : "";
                $msg = "🛠 New service request received!\n\n" .
                    "📂 Category: {$catName}\n" .
                    "👤 Client: {$user->name}\n" .
                    "✉️ Email: {$serviceRequest->email}" .
                    $phoneText . $telegramText . "\n" .
                    "📍 City: {$serviceRequest->city}" .
                    $commentText . "\n\n" .
                    "🆔 Order ID: #{$serviceRequest->id}\n" .
                    "👉 Accept order: https://ustatop-lmze.onrender.com/service-requests";
            } else {
                $phoneText = $serviceRequest->phone ? "\n📞 Telefon: {$serviceRequest->phone}" : "";
                $telegramText = $user->telegram_id ? "\n📱 Telegram: @ustatpuzbot orqali ulangan" : "";
                $commentText = $serviceRequest->comment ? "\n💬 Izoh: {$serviceRequest->comment}" : "";
                $msg = "🛠 Yangi buyurtma keldi!\n\n" .
                    "📂 Soha: {$catName}\n" .
                    "👤 Mijoz: {$user->name}\n" .
                    "✉️ Email: {$serviceRequest->email}" .
                    $phoneText . $telegramText . "\n" .
                    "📍 Shahar: {$serviceRequest->city}" .
                    $commentText . "\n\n" .
                    "🆔 Buyurtma ID: #{$serviceRequest->id}\n" .
                    "👉 Ishni qabul qilish uchun: https://ustatop-lmze.onrender.com/service-requests";
            }

            $this->sendTelegram($provider->telegram_id, $msg);
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

    private function getTelegramLocaleForUser(?User $user): string
    {
        if (!$user) {
            return 'uz';
        }
        if ($user->sync_telegram_lang) {
            return $user->locale ?: 'uz';
        }
        return $user->telegram_locale ?: $user->locale ?: 'uz';
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
            $clientLocale = $this->getTelegramLocaleForUser($serviceRequest->user);
            if ($clientLocale === 'ru') {
                $msg = "✅ Ваш заказ #{$serviceRequest->id} принят мастером {$provider->name} и перешел в работу!\n\nКонтакты: {$provider->email}\nМастер скоро свяжется с вами.";
            } elseif ($clientLocale === 'en') {
                $msg = "✅ Your order #{$serviceRequest->id} has been accepted by craftsman {$provider->name} and is now in progress!\n\nContact: {$provider->email}\nThe craftsman will contact you shortly.";
            } else {
                $msg = "✅ Sizning #{$serviceRequest->id} buyurtmangiz usta {$provider->name} tomonidan qabul qilindi va jarayonga o'tdi!\n\nBog'lanish: {$provider->email}\nTez orada usta siz bilan bog'lanadi.";
            }
            $this->sendTelegram($serviceRequest->user->telegram_id, $msg);
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
            $clientLocale = $this->getTelegramLocaleForUser($serviceRequest->user);
            if ($clientLocale === 'ru') {
                $msg = "ℹ️ По вашему заказу #{$serviceRequest->id} мастер не смог завершить работу. Ваш заказ снова открыт для других мастеров.";
            } elseif ($clientLocale === 'en') {
                $msg = "ℹ️ The craftsman could not complete the work for order #{$serviceRequest->id}. Your request has been reopened for other craftsmen.";
            } else {
                $msg = "ℹ️ Sizning #{$serviceRequest->id} buyurtmangiz bo'yicha usta ishni bajara olmadi. So'rovingiz boshqa ustalar qabul qilishi uchun qayta ochildi.";
            }
            $this->sendTelegram($serviceRequest->user->telegram_id, $msg);
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
            $clientLocale = $this->getTelegramLocaleForUser($serviceRequest->user);
            if ($clientLocale === 'ru') {
                $msg = "🛠 Мастер {$provider->name} сообщил о завершении работы по заказу #{$serviceRequest->id}.\n\nПожалуйста, подтвердите результат на сайте: https://ustatop-lmze.onrender.com/service-requests";
            } elseif ($clientLocale === 'en') {
                $msg = "🛠 Craftsman {$provider->name} marked order #{$serviceRequest->id} as completed.\n\nPlease confirm the work on the website: https://ustatop-lmze.onrender.com/service-requests";
            } else {
                $msg = "🛠 Usta {$provider->name} #{$serviceRequest->id} buyurtma bo'yicha ishni tugatganini bildirdi.\n\nIltimos, saytga kirib natijani tasdiqlang: https://ustatop-lmze.onrender.com/service-requests";
            }
            $this->sendTelegram($serviceRequest->user->telegram_id, $msg);
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
            $providerLocale = $this->getTelegramLocaleForUser($serviceRequest->provider);
            if ($providerLocale === 'ru') {
                $msg = "🎉 Клиент подтвердил успешное завершение заказа #{$serviceRequest->id}! Спасибо за качественную работу.";
            } elseif ($providerLocale === 'en') {
                $msg = "🎉 Client confirmed successful completion of order #{$serviceRequest->id}! Thank you for your work.";
            } else {
                $msg = "🎉 Mijoz #{$serviceRequest->id} buyurtmani muvaffaqiyatli yakunlangan deb tasdiqladi! Mehnat faoliyatingiz uchun rahmat.";
            }
            $this->sendTelegram($serviceRequest->provider->telegram_id, $msg);
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
            $providerLocale = $this->getTelegramLocaleForUser($prevProvider);
            if ($providerLocale === 'ru') {
                $msg = "⚠️ Клиент сообщил, что работа по заказу #{$serviceRequest->id} не завершена. Заказ снова открыт для других мастеров.";
            } elseif ($providerLocale === 'en') {
                $msg = "⚠️ Client indicated that order #{$serviceRequest->id} was not fully completed. The order is reopened.";
            } else {
                $msg = "⚠️ Mijoz #{$serviceRequest->id} buyurtma bo'yicha ish to'liq yakunlanmaganini bildirdi. Buyurtma qayta ochiq holatga o'tkazildi.";
            }
            $this->sendTelegram($prevProvider->telegram_id, $msg);
        }

        return back()->with('success', __('Ish yakunlanmadi deb belgilandi va so\'rovingiz boshqa ustalar uchun qayta ochildi.'));
    }
}
