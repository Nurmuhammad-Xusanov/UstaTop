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
        $offset = 0;

        while (true) {
            try {

            $response = Http::timeout(10)->get("https://api.telegram.org/bot{$token}/getUpdates", [
                'offset' => $offset,

            ]);

            $updates = $response->json()['result'] ?? [];

            foreach ($updates as $update) {
                $offset = $update['update_id'] + 1;

                $message = $update['message'] ?? null;
                if (!$message) continue;

                $chatId = $message['chat']['id'];
                $text = $message['text'] ?? '';

                if (str_starts_with($text, '/start')) {
                    $parts = explode(' ', $text);
                    $userId = $parts[1] ?? null;

                    if ($userId) {
                        User::where('id', $userId)->update([
                            'telegram_id' => $chatId
                        ]);
                    }
                }
            }
            } catch (\Exception $e) {
                logger()->error('Telegram Polling Error: ' . $e->getMessage());
                sleep(5);
            }

            sleep(2);
        }
    }
}
