<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProviderRequest;
use App\Models\ServiceRequests;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create or update primary Admin User (nursherzil@gmail.com)
        $admin = User::updateOrCreate(
            ['email' => 'nursherzil@gmail.com'],
            [
                'name' => 'Nurmuhammad (Admin)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'theme' => 'system',
            ]
        );

        // 2. Initial 9 Categories with Multilingual Support (UZ, RU, EN)
        $categoriesData = [
            [
                'name' => 'Santexnika va suv quvurlari',
                'name_ru' => 'Сантехника и водопровод',
                'name_en' => 'Plumbing & Piping',
            ],
            [
                'name' => 'Elektr montaj va nosozliklar',
                'name_ru' => 'Электромонтаж и ремонт',
                'name_en' => 'Electrical Installation & Repair',
            ],
            [
                'name' => 'Mebel yasash va ta\'mirlash',
                'name_ru' => 'Изготовление и ремонт мебели',
                'name_en' => 'Furniture Making & Repair',
            ],
            [
                'name' => 'Maishiy texnika ta\'mirlash',
                'name_ru' => 'Ремонт бытовой техники',
                'name_en' => 'Appliance Repair',
            ],
            [
                'name' => 'Konditsioner va ventilyatsiya',
                'name_ru' => 'Кондиционеры и вентиляция',
                'name_en' => 'Air Conditioning & Ventilation',
            ],
            [
                'name' => 'Ichki pardozlash va kafel',
                'name_ru' => 'Внутренняя отделка и плитка',
                'name_en' => 'Interior Finishing & Tiling',
            ],
            [
                'name' => 'Duradgorlik va eshik-rom',
                'name_ru' => 'Столярные работы и двери/окна',
                'name_en' => 'Carpentry & Doors/Windows',
            ],
            [
                'name' => 'Tozalash va professional klining',
                'name_ru' => 'Клининг и профессиональная уборка',
                'name_en' => 'Cleaning & Professional Janitorial',
            ],
            [
                'name' => 'Payvandlash va metall ishlari',
                'name_ru' => 'Сварочные и металлические работы',
                'name_en' => 'Welding & Metalwork',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $cat = Category::where('name', $cData['name'])->first();
            if ($cat) {
                $cat->update([
                    'name_ru' => $cData['name_ru'],
                    'name_en' => $cData['name_en'],
                ]);
                $categories[] = $cat;
            } else {
                $categories[] = Category::create($cData);
            }
        }

        // 3. Initial 9 Masters (Providers)
        $mastersData = [
            [
                'name' => 'Jasur Qodirov',
                'email' => 'jasur.qodirov@ustatop.uz',
                'city' => 'Toshkent',
                'phone' => '+998901112233',
                'cat_index' => 0, // Santexnika
            ],
            [
                'name' => 'Alisher Karimov',
                'email' => 'alisher.karimov@ustatop.uz',
                'city' => 'Samarqand',
                'phone' => '+998912223344',
                'cat_index' => 1, // Elektr
            ],
            [
                'name' => 'Bobur Mirzayev',
                'email' => 'bobur.mebel@ustatop.uz',
                'city' => 'Toshkent',
                'phone' => '+998933334455',
                'cat_index' => 2, // Mebel
            ],
            [
                'name' => 'Rustam Bekmurodov',
                'email' => 'rustam.servis@ustatop.uz',
                'city' => 'Farg\'ona',
                'phone' => '+998944445566',
                'cat_index' => 3, // Maishiy texnika
            ],
            [
                'name' => 'Dilshod Xalilov',
                'email' => 'dilshod.kondi@ustatop.uz',
                'city' => 'Toshkent',
                'phone' => '+998955556677',
                'cat_index' => 4, // Konditsioner
            ],
            [
                'name' => 'Farrux Usmonov',
                'email' => 'farrux.remont@ustatop.uz',
                'city' => 'Buxoro',
                'phone' => '+998976667788',
                'cat_index' => 5, // Pardozlash
            ],
            [
                'name' => 'Sarvar To\'rayev',
                'email' => 'sarvar.duradgor@ustatop.uz',
                'city' => 'Andijon',
                'phone' => '+998987778899',
                'cat_index' => 6, // Duradgorlik
            ],
            [
                'name' => 'Nodir Zokirov',
                'email' => 'nodir.clean@ustatop.uz',
                'city' => 'Toshkent',
                'phone' => '+998998889900',
                'cat_index' => 7, // Klining
            ],
            [
                'name' => 'Sherzod Rahimov',
                'email' => 'sherzod.svarka@ustatop.uz',
                'city' => 'Namangan',
                'phone' => '+998909990011',
                'cat_index' => 8, // Payvandlash
            ],
        ];

        $providers = [];
        foreach ($mastersData as $m) {
            $cat = $categories[$m['cat_index']];
            $user = User::updateOrCreate(
                ['email' => $m['email']],
                [
                    'name' => $m['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'provider',
                    'email_verified_at' => now(),
                    'theme' => 'system',
                ]
            );

            $user->categories()->syncWithoutDetaching([$cat->id]);

            ProviderRequest::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $m['phone'],
                    'city' => $m['city'],
                    'service_ids' => [$cat->id],
                    'status' => 'approved',
                ]
            );

            $providers[] = $user;
        }

        // 3.1. Create Pending Master Applications (for Admin review demonstration)
        $pendingApplicants = [
            [
                'name' => 'Bobur Mirzayev',
                'email' => 'bobur.mirzayev@gmail.com',
                'phone' => '+998901112233',
                'city' => 'Toshkent',
                'cat_indices' => [0, 1], // Santexnika, Elektr montaj
            ],
            [
                'name' => 'Sanjar Qodirov',
                'email' => 'sanjar.qodirov@gmail.com',
                'phone' => '+998935554433',
                'city' => 'Samarqand',
                'cat_indices' => [4], // Konditsioner
            ],
        ];

        foreach ($pendingApplicants as $app) {
            $appUser = User::updateOrCreate(
                ['email' => $app['email']],
                [
                    'name' => $app['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'client',
                    'email_verified_at' => now(),
                    'theme' => 'system',
                ]
            );

            $serviceIds = array_map(fn($idx) => $categories[$idx]->id, $app['cat_indices']);

            ProviderRequest::updateOrCreate(
                ['user_id' => $appUser->id],
                [
                    'phone' => $app['phone'],
                    'city' => $app['city'],
                    'service_ids' => $serviceIds,
                    'status' => 'pending',
                ]
            );
        }

        // 4. Create Demo Clients
        $clientsData = [
            ['name' => 'Olimjon Vohidov', 'email' => 'olim.vohidov@gmail.com', 'city' => 'Toshkent', 'phone' => '+998901234567'],
            ['name' => 'Malika Rahimova', 'email' => 'malika.rahimova@mail.ru', 'city' => 'Samarqand', 'phone' => '+998912345678'],
            ['name' => 'Sardorbek Jo\'rayev', 'email' => 'sardor.jurayev@gmail.com', 'city' => 'Farg\'ona', 'phone' => '+998933456789'],
            ['name' => 'Zuhra Karimova', 'email' => 'zuhra.karimova@gmail.com', 'city' => 'Toshkent', 'phone' => '+998944567890'],
        ];

        $clients = [];
        foreach ($clientsData as $c) {
            $client = User::updateOrCreate(
                ['email' => $c['email']],
                [
                    'name' => $c['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'client',
                    'email_verified_at' => now(),
                    'theme' => 'system',
                ]
            );
            $clients[] = array_merge($c, ['user' => $client]);
        }

        // 5. Initial 9 Realistic Service Requests
        $requestsData = [
            // Completed requests
            [
                'client_idx' => 0,
                'cat_idx' => 0,
                'provider_idx' => 0,
                'city' => 'Toshkent, Yunusobod',
                'phone' => '+998901234567',
                'status' => 'completed',
            ],
            [
                'client_idx' => 1,
                'cat_idx' => 1,
                'provider_idx' => 1,
                'city' => 'Samarqand, Registon',
                'phone' => '+998912345678',
                'status' => 'completed',
            ],
            [
                'client_idx' => 2,
                'cat_idx' => 3,
                'provider_idx' => 3,
                'city' => 'Farg\'ona, Markaz',
                'phone' => '+998933456789',
                'status' => 'completed',
            ],
            // In-Progress / Accepted requests
            [
                'client_idx' => 3,
                'cat_idx' => 4,
                'provider_idx' => 4,
                'city' => 'Toshkent, Chilonzor',
                'phone' => '+998944567890',
                'status' => 'accepted',
            ],
            [
                'client_idx' => 0,
                'cat_idx' => 2,
                'provider_idx' => 2,
                'city' => 'Toshkent, Mirzo Ulug\'bek',
                'phone' => '+998901234567',
                'status' => 'provider_done',
            ],
            [
                'client_idx' => 1,
                'cat_idx' => 5,
                'provider_idx' => 5,
                'city' => 'Buxoro, Eski shahar',
                'phone' => '+998912345678',
                'status' => 'accepted',
            ],
            // Pending requests (awaiting master)
            [
                'client_idx' => 2,
                'cat_idx' => 7,
                'provider_idx' => null,
                'city' => 'Toshkent, Sergeli',
                'phone' => '+998933456789',
                'status' => 'pending',
            ],
            [
                'client_idx' => 3,
                'cat_idx' => 8,
                'provider_idx' => null,
                'city' => 'Namangan, Davlatobod',
                'phone' => '+998944567890',
                'status' => 'pending',
            ],
            [
                'client_idx' => 0,
                'cat_idx' => 6,
                'provider_idx' => null,
                'city' => 'Andijon, Bobur shoh',
                'phone' => '+998901234567',
                'status' => 'pending',
            ],
        ];

        foreach ($requestsData as $req) {
            $client = $clients[$req['client_idx']]['user'];
            $cat = $categories[$req['cat_idx']];
            $provider = isset($req['provider_idx']) && $req['provider_idx'] !== null ? $providers[$req['provider_idx']] : null;

            ServiceRequests::firstOrCreate(
                [
                    'user_id' => $client->id,
                    'category_id' => $cat->id,
                    'city' => $req['city'],
                ],
                [
                    'provider_id' => $provider ? $provider->id : null,
                    'phone' => $req['phone'],
                    'email' => $client->email,
                    'status' => $req['status'],
                ]
            );
        }
    }
}
