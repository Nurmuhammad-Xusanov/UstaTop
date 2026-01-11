<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class TelegramPoll extends Command
{
    protected $signature = 'telegram:poll';
    protected $description = 'Telegram bot polling';

    public function handle()
    {
        $token = config('services.telegram.token');
        $offset = cache()->get('telegram_offset', 0);
        
        $this->info("Telegram Polling Started...");

        while (true) {
            try {
                $response = Http::timeout(60)->get("https://api.telegram.org/bot{$token}/getUpdates", [
                    'offset' => $offset,
                    'timeout' => 60,
                ]);

                if ($response->failed()) {
                    $this->error("Telegram API Error: " . $response->body());
                    sleep(5);
                    continue;
                }

                $updates = $response->json()['result'] ?? [];

                foreach ($updates as $update) {
                    $offset = $update['update_id'] + 1;
                    cache()->put('telegram_offset', $offset);

                    $message = $update['message'] ?? null;
                    if (!$message) continue;

                    $chatId = $message['chat']['id'];
                    $text = $message['text'] ?? '';
                    
                    $this->info("Received message: {$text} from Chat ID: {$chatId}");

                    if (str_starts_with($text, '/start')) {
                       
                        $parts = preg_split('/\s+/', trim($text));
                        $userId = $parts[1] ?? null;  
                        
                        $this->info("Processing /start command. UserId payload: " . ($userId ?? 'NULL'));

                       
                        $linkedUser = User::where('telegram_id', $chatId)->first();

                        if ($linkedUser) {
                            $this->info("Telegram ID {$chatId} is already linked to User ID: {$linkedUser->id}");
                            
                           
                            if ($userId) {
                                if ((int)$linkedUser->id === (int)$userId) {
                                     
                                     $this->info("Case: Already linked to the SAME user.");
                                     Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                        'chat_id' => $chatId,
                                        'text' => '⚠️ Sizning Telegram hisobingiz ushbu profilga allaqachon ulangan.'
                                    ]);
                                } else {
                                 
                                    $this->info("Case: Already linked to a DIFFERENT user (User ID: {$linkedUser->id}). Cannot link to New User ID: {$userId}");
                                    Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                        'chat_id' => $chatId,
                                        'text' => '❌ Bu Telegram hisobi allaqachon boshqa foydalanuvchiga ulangan. Iltimos, avval uni uzing.'
                                    ]);
                                }
                            } else {
                               
                                 $this->info("Case: Simple /start but already linked.");
                                 Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                    'chat_id' => $chatId,
                                    'text' => 'ℹ️ Siz allaqachon ro‘yxatdan o‘tgansiz.'
                                ]);
                            }
                            continue;
                        }

                       
                        if (!$userId) {
                             $this->warn("No User ID payload provided.");
                            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text' => '❌ Iltimos, saytdagi ""Telegramni ulash"" tugmasi orqali kiring.'
                            ]);
                            continue;
                        }

                 
                        $user = User::find($userId);

                        if (!$user) {
                            $this->error("Target User ID {$userId} not found in database.");
                            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text' => '❌ Foydalanuvchi topilmadi.'
                            ]);
                            continue;
                        }
                        
                       
                        if ($user->telegram_id && $user->telegram_id != $chatId) {
                             $this->warn("Target User ID {$userId} is already linked to another Telegram ID: {$user->telegram_id}");
                             Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                                'chat_id' => $chatId,
                                'text' => '❌ Bu profilga allaqachon boshqa Telegram hisobi ulangan.'
                            ]);
                            continue;
                        }

                      
                        $this->info("Linking Telegram ID {$chatId} to User ID {$userId}");
                        $user->update([
                            'telegram_id' => $chatId
                        ]);

                        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                            'chat_id' => $chatId,
                            'text' => '✅ Telegram hisobingiz muvaffaqiyatli ulandi!'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $this->error('Telegram Polling Error: ' . $e->getMessage());
                logger()->error('Telegram Polling Error: ' . $e->getMessage());
                sleep(5);
            }

            sleep(2);
        }
    }
}
