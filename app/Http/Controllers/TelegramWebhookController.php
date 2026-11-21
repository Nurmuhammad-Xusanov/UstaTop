<?php

namespace App\Http\Controllers;

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
                    $this->sendMessage($token, $chatId, 'ℹ️ Siz allaqachon UstaTop tizimiga ulangansiz.');
                }
                return response()->json(['status' => 'already_linked']);
            }

            if (!$userId) {
                $this->sendMessage($token, $chatId, '❌ Iltimos, saytdagi "Telegramni ulash" tugmasi orqali kiring.');
                return response()->json(['status' => 'no_user_id']);
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

            $this->sendMessage($token, $chatId, '✅ Telegram hisobingiz UstaTop profiliga muvaffaqiyatli ulandi!');
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'ok']);
    }

    private function sendMessage(string $token, $chatId, string $text)
    {
        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send Telegram message: " . $e->getMessage());
        }
    }
}
