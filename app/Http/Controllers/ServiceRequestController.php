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

        // client
        if ($user->role === 'client' || $user->role === 'user') {
            $requests = ServiceRequests::where('user_id', $user->id)->latest()->get();
            return view('service.user_index', compact('requests'));
        }

        // provider
        if ($user->role === 'provider') {
            $requests = ServiceRequests::where('status', 'pending')
                ->whereHas(
                    'category',
                    fn($q) =>
                    $q->whereIn(
                        'categories.id',
                        $user->categories->pluck('id')
                    )
                )
                ->latest()
                ->get();

            return view('service.provider_index', compact('requests'));
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
                    "🆔 Request ID: {$serviceRequest->id}".
                    "Qabul qilish uchun login qilib, so‘rovlar bo‘limiga o‘ting."
            );
        }


        return redirect()->route('service-requests.index')
            ->with('success', 'So‘rov muvaffaqiyatli yaratildi');
    }

    public function show(ServiceRequests $serviceRequest)
    {
        return view('service.show', compact('serviceRequest'));
    }


    private function sendTelegram($chatId, $text)
    {
        $token = config('services.telegram.token');

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }
}
