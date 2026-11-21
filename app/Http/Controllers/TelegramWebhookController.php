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

        // 1. Handle Callback Queries (Inline Keyboard clicks)
        if (isset($update['callback_query'])) {
            $callback = $update['callback_query'];
            $callbackId = $callback['id'] ?? '';
            $chatId = $callback['message']['chat']['id'] ?? null;
            $messageId = $callback['message']['message_id'] ?? null;
            $data = $callback['data'] ?? '';

            $user = User::where('telegram_id', $chatId)->first();

            if ($user && $chatId) {
                if ($data === 'toggle_sync') {
                    $user->sync_telegram_lang = !$user->sync_telegram_lang;
                    if ($user->sync_telegram_lang) {
                        $user->telegram_locale = $user->locale ?: 'uz';
                    }
                    $user->save();

                    $this->answerCallbackQuery($token, $callbackId, $user->sync_telegram_lang ? "✅ Sayt bilan sinxronlash yoqildi!" : "❌ Sinxronlash o'chirildi.");
                    $this->sendOrEditSettingsMessage($token, $chatId, $user, $messageId);
                    return response()->json(['status' => 'sync_toggled']);
                }

                if (str_starts_with($data, 'set_lang_')) {
                    $chosenLang = str_replace('set_lang_', '', $data);
                    if (in_array($chosenLang, ['uz', 'ru', 'en'])) {
                        $user->telegram_locale = $chosenLang;
                        $user->sync_telegram_lang = false; // User manually picked language in bot
                        $user->save();

                        $langName = match($chosenLang) {
                            'ru' => 'Русский язык',
                            'en' => 'English language',
                            default => "O'zbek tili",
                        };

                        $this->answerCallbackQuery($token, $callbackId, "Til o'zgartirildi: {$langName}");
                        $this->sendOrEditSettingsMessage($token, $chatId, $user, $messageId);
                        return response()->json(['status' => 'language_set']);
                    }
                }
            }

            $this->answerCallbackQuery($token, $callbackId, 'OK');
            return response()->json(['status' => 'callback_handled']);
        }

        // 2. Handle Messages
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

        $user = User::where('telegram_id', $chatId)->first();
        $locale = $this->getUserLocale($user);

        // Command: Settings / Sozlamalar
        if (str_starts_with($text, '/settings') || str_contains(mb_strtolower($text), 'settings') || str_contains(mb_strtolower($text), 'sozlamalar') || str_contains(mb_strtolower($text), 'настройки')) {
            $this->sendOrEditSettingsMessage($token, $chatId, $user);
            return response()->json(['status' => 'settings_sent']);
        }

        // Command: /status or button "📋 Status"
        if (str_starts_with($text, '/status') || str_contains(mb_strtolower($text), 'status') || str_contains(mb_strtolower($text), 'holat') || str_contains(mb_strtolower($text), 'статус')) {
            if ($user) {
                $roleLabel = match($user->role) {
                    'admin' => ($locale === 'en' ? '👑 Administrator' : ($locale === 'ru' ? '👑 Администратор' : '👑 Administrator')),
                    'provider' => ($locale === 'en' ? '🛠 Verified Craftsman' : ($locale === 'ru' ? '🛠 Мастер' : '🛠 Malakali Usta')),
                    default => ($locale === 'en' ? '👤 Client' : ($locale === 'ru' ? '👤 Клиент' : '👤 Mijoz')),
                };

                $orderCount = ServiceRequests::where('user_id', $user->id)
                    ->orWhere('provider_id', $user->id)
                    ->count();

                $activeCount = ServiceRequests::where(function($q) use ($user) {
                    $q->where('user_id', $user->id)->orWhere('provider_id', $user->id);
                })->whereIn('status', ['pending', 'accepted', 'provider_done'])->count();

                if ($locale === 'en') {
                    $msg = "📋 <b>Account Details:</b>\n\n" .
                           "👤 <b>Name:</b> {$user->name}\n" .
                           "📧 <b>Email:</b> {$user->email}\n" .
                           "🏷 <b>Role:</b> {$roleLabel}\n" .
                           "📊 <b>Total Orders:</b> {$orderCount}\n" .
                           "⚡️ <b>Active Orders:</b> {$activeCount}\n\n" .
                           "🌐 <b>Website:</b> https://ustatop-lmze.onrender.com";
                } elseif ($locale === 'ru') {
                    $msg = "📋 <b>Данные аккаунта:</b>\n\n" .
                           "👤 <b>Имя:</b> {$user->name}\n" .
                           "📧 <b>Email:</b> {$user->email}\n" .
                           "🏷 <b>Роль:</b> {$roleLabel}\n" .
                           "📊 <b>Всего заказов:</b> {$orderCount}\n" .
                           "⚡️ <b>Активные заказы:</b> {$activeCount}\n\n" .
                           "🌐 <b>Сайт:</b> https://ustatop-lmze.onrender.com";
                } else {
                    $msg = "📋 <b>Hisob Ma'lumotlari:</b>\n\n" .
                           "👤 <b>Ism:</b> {$user->name}\n" .
                           "📧 <b>Email:</b> {$user->email}\n" .
                           "🏷 <b>Rol:</b> {$roleLabel}\n" .
                           "📊 <b>Jami buyurtmalar:</b> {$orderCount}\n" .
                           "⚡️ <b>Faol buyurtmalar:</b> {$activeCount}\n\n" .
                           "🌐 <b>Sayt:</b> https://ustatop-lmze.onrender.com";
                }

                $this->sendMessage($token, $chatId, $msg, 'HTML', $this->getMainReplyKeyboard($locale));
            } else {
                $msg = $locale === 'en'
                    ? "ℹ️ Your Telegram account is not yet connected to UstaTop.\n\nPlease log in and click 'Connect Telegram':\n🌐 https://ustatop-lmze.onrender.com"
                    : ($locale === 'ru'
                        ? "ℹ️ Ваш Telegram аккаунт еще не привязан к профилю UstaTop.\n\nВойдите на сайт и нажмите 'Привязать Telegram':\n🌐 https://ustatop-lmze.onrender.com"
                        : "ℹ️ Sizning Telegram hisobingiz hali UstaTop profilingizga ulanmagan.\n\nUlash uchun saytga kiring va 'Telegramni ulash' tugmasini bosing:\n🌐 https://ustatop-lmze.onrender.com");
                $this->sendMessage($token, $chatId, $msg);
            }
            return response()->json(['status' => 'status_checked']);
        }

        // Command: /help
        if (str_starts_with($text, '/help')) {
            $msg = $locale === 'en'
                ? "🛠 <b>UstaTop Bot Commands:</b>\n\n/start — Launch bot\n/status — Account info & active orders\n/settings — Change language & sync preferences\n/help — Show help\n\n🌐 https://ustatop-lmze.onrender.com"
                : ($locale === 'ru'
                    ? "🛠 <b>Команды бота UstaTop:</b>\n\n/start — Запустить бота\n/status — Данные аккаунта и заказы\n/settings — Языковые настройки и синхронизация\n/help — Помощь\n\n🌐 https://ustatop-lmze.onrender.com"
                    : "🛠 <b>UstaTop Bot Buyruqlari:</b>\n\n/start — Botni ishga tushirish\n/status — Profilingiz va faol buyurtmalaringiz holati\n/settings — Til va sinxronlash sozlamalari\n/help — Yordam ma'lumotlari\n\n🌐 https://ustatop-lmze.onrender.com");

            $this->sendMessage($token, $chatId, $msg, 'HTML', $this->getMainReplyKeyboard($locale));
            return response()->json(['status' => 'help_sent']);
        }

        // Command: /start
        if (str_starts_with($text, '/start')) {
            $parts = preg_split('/\s+/', $text);
            $userId = $parts[1] ?? null;

            if ($user) {
                if ($userId && (int)$user->id === (int)$userId) {
                    $this->sendMessage($token, $chatId, "⚠️ Sizning Telegram hisobingiz ushbu profilga allaqachon ulangan.", '', $this->getMainReplyKeyboard($locale));
                } elseif ($userId) {
                    $this->sendMessage($token, $chatId, "❌ Bu Telegram hisobi allaqachon boshqa profilga ulangan.", '', $this->getMainReplyKeyboard($locale));
                } else {
                    $greeting = $locale === 'en'
                        ? "👋 Hello, {$user->name}!\nYou are connected to UstaTop. Send /status to see your orders."
                        : ($locale === 'ru'
                            ? "👋 Здравствуйте, {$user->name}!\nВы подключены к UstaTop. Отправьте /status для просмотра заказов."
                            : "👋 Salom, {$user->name}!\nSiz UstaTop platformasiga ulangansiz. Faol buyurtmalarni ko'rish uchun /status yuboring.");
                    $this->sendMessage($token, $chatId, $greeting, '', $this->getMainReplyKeyboard($locale));
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

            $targetUser = User::find($userId);

            if (!$targetUser) {
                $this->sendMessage($token, $chatId, '❌ Foydalanuvchi topilmadi.');
                return response()->json(['status' => 'user_not_found']);
            }

            if ($targetUser->telegram_id && $targetUser->telegram_id != $chatId) {
                $this->sendMessage($token, $chatId, '❌ Bu profilga allaqachon boshqa Telegram hisobi ulangan.');
                return response()->json(['status' => 'profile_already_has_telegram']);
            }

            $targetUser->update([
                'telegram_id' => $chatId,
                'sync_telegram_lang' => true,
                'telegram_locale' => $targetUser->locale ?: 'uz',
            ]);

            $locale = $this->getUserLocale($targetUser);

            $congrats = $locale === 'en'
                ? "🎉 <b>Congratulations!</b>\n\nYour Telegram account has been linked to <b>{$targetUser->name}</b>!\nAll new service orders and status updates will be sent directly here."
                : ($locale === 'ru'
                    ? "🎉 <b>Поздравляем!</b>\n\nВаш Telegram аккаунт успешно привязан к профилю <b>{$targetUser->name}</b>!\nВсе новые заказы и уведомления будут поступать сюда."
                    : "🎉 <b>Tabriklaymiz!</b>\n\nTelegram hisobingiz <b>{$targetUser->name}</b> profiliga muvaffaqiyatli ulandi!\nEndi barcha yangi buyurtmalar va bildirishnomalar shu yerga yetkaziladi.");

            $this->sendMessage($token, $chatId, $congrats, 'HTML', $this->getMainReplyKeyboard($locale));
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'ok']);
    }

    private function getUserLocale(?User $user): string
    {
        if (!$user) {
            return 'uz';
        }
        if ($user->sync_telegram_lang) {
            return $user->locale ?: 'uz';
        }
        return $user->telegram_locale ?: $user->locale ?: 'uz';
    }

    private function getMainReplyKeyboard(string $locale): array
    {
        $statusBtn = $locale === 'en' ? '📋 Status' : ($locale === 'ru' ? '📋 Статус' : '📋 Holat');
        $settingsBtn = $locale === 'en' ? '⚙️ Settings' : ($locale === 'ru' ? '⚙️ Настройки' : '⚙️ Sozlamalar');
        $siteBtn = $locale === 'en' ? '🌐 Website' : ($locale === 'ru' ? '🌐 Сайт' : '🌐 Sayt');

        return [
            'keyboard' => [
                [
                    ['text' => $statusBtn],
                    ['text' => $settingsBtn],
                ],
                [
                    ['text' => $siteBtn],
                ]
            ],
            'resize_keyboard' => true,
            'persistent' => true
        ];
    }

    private function sendOrEditSettingsMessage(string $token, $chatId, ?User $user, ?int $messageId = null)
    {
        $locale = $this->getUserLocale($user);
        $sync = $user ? ($user->sync_telegram_lang ?? true) : true;

        $syncBadge = $sync ? '✅ Yoqilgan (ON)' : '❌ O\'chirilgan (OFF)';
        $activeLangName = match($locale) {
            'ru' => '🇷🇺 Русский',
            'en' => '🇬🇧 English',
            default => '🇺🇿 O\'zbekcha',
        };

        if ($locale === 'en') {
            $syncBadge = $sync ? '✅ ON (Synced with site)' : '❌ OFF (Independent)';
            $text = "⚙️ <b>Settings & Language</b>\n\n" .
                   "🌐 <b>Current Bot Language:</b> {$activeLangName}\n" .
                   "🔄 <b>Sync with Website Language:</b> {$syncBadge}\n\n" .
                   "<i>When sync is enabled, changing language on the website will automatically change the bot language.</i>";
            $syncBtnText = $sync ? "🔄 Sync with site: ✅ ON" : "🔄 Sync with site: ❌ OFF";
        } elseif ($locale === 'ru') {
            $syncBadge = $sync ? '✅ ВКЛ (Синхрон с сайтом)' : '❌ ВЫКЛ (Отдельно)';
            $text = "⚙️ <b>Настройки и Язык</b>\n\n" .
                   "🌐 <b>Текущий язык бота:</b> {$activeLangName}\n" .
                   "🔄 <b>Синхронизация с сайтом:</b> {$syncBadge}\n\n" .
                   "<i>При включенной синхронизации с сайтом язык бота автоматически меняется при смене языка на сайте.</i>";
            $syncBtnText = $sync ? "🔄 Синхронизация с сайтом: ✅" : "🔄 Синхронизация с сайтом: ❌";
        } else {
            $text = "⚙️ <b>Sozlamalar va Til</b>\n\n" .
                   "🌐 <b>Joriy bot tili:</b> {$activeLangName}\n" .
                   "🔄 <b>Sayt bilan sinxronlash:</b> {$syncBadge}\n\n" .
                   "<i>Sinxronlash yoqilgan bo'lsa, saytda til o'zgarganda bot ham avtomatik o'sha tilda gapiradi.</i>";
            $syncBtnText = $sync ? "🔄 Sayt bilan sinxronlash: ✅" : "🔄 Sayt bilan sinxronlash: ❌";
        }

        $replyMarkup = [
            'inline_keyboard' => [
                [
                    ['text' => $syncBtnText, 'callback_data' => 'toggle_sync']
                ],
                [
                    ['text' => '🇺🇿 O\'zbek', 'callback_data' => 'set_lang_uz'],
                    ['text' => '🇷🇺 Русский', 'callback_data' => 'set_lang_ru'],
                    ['text' => '🇬🇧 English', 'callback_data' => 'set_lang_en'],
                ]
            ]
        ];

        if ($messageId) {
            $this->editMessageText($token, $chatId, $messageId, $text, 'HTML', $replyMarkup);
        } else {
            $this->sendMessage($token, $chatId, $text, 'HTML', $replyMarkup);
        }
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

    private function editMessageText(string $token, $chatId, int $messageId, string $text, string $parseMode = '', array $replyMarkup = null)
    {
        try {
            $payload = [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'text' => $text,
            ];
            if ($parseMode) {
                $payload['parse_mode'] = $parseMode;
            }
            if ($replyMarkup) {
                $payload['reply_markup'] = json_encode($replyMarkup);
            }
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/editMessageText", $payload);
        } catch (\Exception $e) {
            Log::error("Failed to edit Telegram message: " . $e->getMessage());
        }
    }

    private function answerCallbackQuery(string $token, string $callbackId, string $text = '')
    {
        try {
            Http::timeout(5)->post("https://api.telegram.org/bot{$token}/answerCallbackQuery", [
                'callback_query_id' => $callbackId,
                'text' => $text,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to answer callback query: " . $e->getMessage());
        }
    }
}
