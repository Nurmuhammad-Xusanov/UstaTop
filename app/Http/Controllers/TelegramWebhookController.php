<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $token = config('services.telegram.token');
        if (!$token) {
            return response()->json(['error' => 'Telegram token not configured'], 500);
        }

        $update = $request->all();
        $message = $update['message'] ?? null;

        if (!$message) {
            return response()->json(['status' => 'no_message']);
        }

        $chatId = $message['chat']['id'] ?? null;
        $text = trim($message['text'] ?? '');

        if (!$chatId) {
            return response()->json(['status' => 'no_chat_id']);
        }

        Log::info("Telegram Webhook message: {$text} from Chat ID: {$chatId}");

        // Command: /status
        if (str_starts_with($text, '/status')) {
            $user = User::where('telegram_id', $chatId)->first();
            if ($user) {
                $roleLabel = match($user->role) {
                    'admin' => '👑 Administrator',
                    'provider' => '🛠 Malakali Usta',
                    default => '👤 Mijoz',
                };

                $orderCount = ServiceRequests::where('user_id', $user->id)
                    ->orWhere('provider_id', $user->id)
                    ->count();

                $activeCount = ServiceRequests::where(function($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhere('provider_id', $user->id);
                })->whereIn('status', ['pending', 'accepted', 'provider_done'])->count();

                $msg = "📋 <b>Hisob Ma'lumotlari:</b>\n\n" .
                       "👤 <b>Ism:</b> {$user->name}\n" .
                       "📧 <b>Email:</b> {$user->email}\n" .
                       "🏷 <b>Rol:</b> {$roleLabel}\n" .
                       "📊 <b>Jami buyurtmalar:</b> {$orderCount}\n" .
                       "⚡️ <b>Faol buyurtmalar:</b> {$activeCount}\n\n" .
                       "🌐 Sayt: https://ustatop-lmze.onrender.com";

                $this->sendMessage($token, $chatId, $msg, 'HTML');
            } else {
                $msg = "ℹ️ Sizning Telegram hisobingiz hali UstaTop profilingizga ulanmagan.\n\n" .
                       "Ulash uchun saytga kiring va 'Telegramni ulash' tugmasini bosing:\n" .
                       "🌐 https://ustatop-lmze.onrender.com";
                $this->sendMessage($token, $chatId, $msg);
            }
            return response()->json(['status' => 'status_checked']);
        }

        // Command: /help
        if (str_starts_with($text, '/help')) {
            $msg = "🛠 <b>UstaTop Bot Buyruqlari:</b>\n\n" .
                   "/start — Botni ishga tushirish\n" .
                   "/status — Profilingiz va faol buyurtmalaringiz holati\n" .
                   "/help — Yordam ma'lumotlari\n\n" .
                   "🌐 <b>Veb-sayt:</b> https://ustatop-lmze.onrender.com";
            $this->sendMessage($token, $chatId, $msg, 'HTML');
            return response()->json(['status' => 'help_sent']);
        }

        // Command: /start
        if (str_starts_with($text, '/start')) {
            $parts = preg_split('/\s+/', $text);
            $userId = $parts[1] ?? null;

            $linkedUser = User::where('telegram_id', $chatId)->first();

            if ($linkedUser) {
                if ($userId && (int)$linkedUser->id === (int)$userId) {
                    $this->sendMessage($token, $chatId, '⚠️ Sizning Telegram hisobingiz ushbu profilga allaqachon ulangan.');
                } elseif ($userId) {
                    $this->sendMessage($token, $chatId, '❌ Bu Telegram hisobi allaqachon boshqa profilga ulangan. Iltimos, avval uni uzing.');
                } else {
                    $this->sendMessage($token, $chatId, "👋 Salom, {$linkedUser->name}!\nSiz UstaTop platformasiga ulangansiz. Faol buyurtmalarni ko'rish uchun /status buyrug'ini yuboring.");
                }
                return response()->json(['status' => 'already_linked']);
            }

            if (!$userId) {
                $welcome = "👋 <b>Assalomu alaykum! Xush kelibsiz UstaTop botiga!</b>\n\n" .
                           "UstaTop — O'zbekiston bo'ylab uyingiz va ofisingiz uchun malakali ustalarni topish platformasi.\n\n" .
                           "🔹 Santexnika, elektr montaj, mebel, konditsioner va boshqa 10+ xizmatlar\n" .
                           "🔹 Real vaqtda buyurtma xabarnomalari\n\n" .
                           "🔗 Profilingizni ulash uchun saytga kiring va Profilingizdan <b>'Telegramni ulash'</b> tugmasini bosing.\n\n" .
                           "🌐 <b>Saytga kirish:</b> https://ustatop-lmze.onrender.com";
                $this->sendMessage($token, $chatId, $welcome, 'HTML', [
                    'inline_keyboard' => [
                        [
                            ['text' => '🌐 UstaTop Saytiga o\'tish', 'url' => 'https://ustatop-lmze.onrender.com']
                        ]
                    ]
                ]);
                return response()->json(['status' => 'welcome_sent']);
            }

            $user = User::find($userId);

            if (!$user) {
                $this->sendMessage($token, $chatId, '❌ Foydalanuvchi topilmadi.');
                return response()->json(['status' => 'user_not_found']);
            }

            if ($user->telegram_id && $user->telegram_id != $chatId) {
                $this->sendMessage($token, $chatId, '❌ Bu profilga allaqachon boshqa Telegram hisobi ulangan.');
                return response()->json(['status' => 'profile_already_has_telegram']);
            }

            $user->update([
                'telegram_id' => $chatId,
            ]);

            $this->sendMessage($token, $chatId, "🎉 <b>Tabriklaymiz!</b>\n\nTelegram hisobingiz <b>{$user->name}</b> profiliga muvaffaqiyatli ulandi!\nEndi barcha yangi buyurtmalar va bildirishnomalar shu yerga yetkaziladi.", 'HTML');
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendMessage(string $token, $chatId, string $text, string $parseMode = '', array $replyMarkup = null)
    {
        try {
            $payload = [
                'chat_id' => $chatId,
                'text' => $text,
            ];
            if ($parseMode) {
                $payload['parse_mode'] = $parseMode;
            }
            if ($replyMarkup) {
                $payload['reply_markup'] = json_encode($replyMarkup);
            }
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", $payload);
        } catch (\Exception $e) {
            Log::error("Failed to send Telegram message: " . $e->getMessage());
        }
    }
}
