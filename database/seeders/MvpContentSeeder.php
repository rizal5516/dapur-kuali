<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\RestaurantProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MvpContentSeeder extends Seeder
{
    public function run(): void
    {
        RestaurantProfile::query()->updateOrCreate(
            ['id' => 1],
            [
                'brand_name' => 'Dapur Kawalli',
                'tagline' => 'Sajian Autentik Sunda & Betawi untuk Keluarga dan Acara Spesial',
                'about' => "Restoran dengan cita rasa khas Sunda dan Betawi. Cocok untuk makan bersama keluarga, gathering, hingga reservasi wedding.",
                'address' => 'Alamat restoran (isi sesuai lokasi).',
                'phone' => '08xxxxxxxxxx',
                'whatsapp_number' => '08xxxxxxxxxx',
                'email' => 'info@dapur-kawalli.test',
                'instagram_url' => 'https://www.instagram.com/dapur.kawalli/',
                'google_maps_embed' => null,
                'opening_hours_text' => 'Setiap hari 10.00 - 22.00 WIB',
                'updated_by' => null,
            ]
        );

        $categories = [
            ['name' => 'Menu Sunda', 'slug' => 'menu-sunda', 'cuisine_type' => 'sunda', 'sort_order' => 1],
            ['name' => 'Menu Betawi', 'slug' => 'menu-betawi', 'cuisine_type' => 'betawi', 'sort_order' => 2],
            ['name' => 'Minuman', 'slug' => 'minuman', 'cuisine_type' => 'minuman', 'sort_order' => 3],
            ['name' => 'Lainnya', 'slug' => 'lainnya', 'cuisine_type' => 'lainnya', 'sort_order' => 99],
        ];

        $categoryMap = [];
        foreach ($categories as $c) {
            $cat = MenuCategory::query()->updateOrCreate(
                ['slug' => $c['slug']],
                [
                    'name' => $c['name'],
                    'cuisine_type' => $c['cuisine_type'],
                    'sort_order' => $c['sort_order'],
                    'is_active' => true,
                    'created_by' => null,
                ]
            );
            $categoryMap[$c['slug']] = $cat->id;
        }

        $menuItems = [
            // Sunda
            [
                'category_slug' => 'menu-sunda',
                'name' => 'Nasi Liwet Komplit',
                'slug' => 'nasi-liwet-komplit',
                'description' => 'Nasi liwet khas Sunda dengan lauk pilihan dan sambal.',
                'price' => 45000,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'menu-sunda',
                'name' => 'Ayam Goreng Lengkuas',
                'slug' => 'ayam-goreng-lengkuas',
                'description' => 'Ayam goreng gurih dengan taburan lengkuas renyah.',
                'price' => 38000,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category_slug' => 'menu-sunda',
                'name' => 'Karedok',
                'slug' => 'karedok',
                'description' => 'Sayuran segar dengan bumbu kacang khas.',
                'price' => 25000,
                'is_featured' => false,
                'sort_order' => 3,
            ],

            // Betawi
            [
                'category_slug' => 'menu-betawi',
                'name' => 'Soto Betawi',
                'slug' => 'soto-betawi',
                'description' => 'Soto Betawi kuah santan/gurih dengan daging pilihan.',
                'price' => 42000,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'menu-betawi',
                'name' => 'Ketoprak Betawi',
                'slug' => 'ketoprak-betawi',
                'description' => 'Ketoprak dengan bumbu kacang, bihun, dan kerupuk.',
                'price' => 28000,
                'is_featured' => false,
                'sort_order' => 2,
            ],

            // Minuman
            [
                'category_slug' => 'minuman',
                'name' => 'Es Teh Manis',
                'slug' => 'es-teh-manis',
                'description' => 'Teh manis segar.',
                'price' => 8000,
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'category_slug' => 'minuman',
                'name' => 'Es Jeruk',
                'slug' => 'es-jeruk',
                'description' => 'Jeruk peras segar.',
                'price' => 12000,
                'is_featured' => false,
                'sort_order' => 2,
            ],
        ];

        foreach ($menuItems as $item) {
            $categoryId = $categoryMap[$item['category_slug']] ?? null;
            if (!$categoryId) {
                continue;
            }

            MenuItem::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'menu_category_id' => $categoryId,
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'image_url' => null,
                    'is_featured' => $item['is_featured'],
                    'is_available' => true,
                    'sort_order' => $item['sort_order'],
                    'created_by' => null,
                ]
            );
        }

        $galleries = [
            ['category' => 'food', 'title' => 'Menu Unggulan', 'image_url' => '/storage/gallery/food-1.jpg', 'alt_text' => 'Foto menu unggulan', 'sort_order' => 1],
            ['category' => 'interior', 'title' => 'Suasana Restoran', 'image_url' => '/storage/gallery/interior-1.jpg', 'alt_text' => 'Interior restoran', 'sort_order' => 1],
            ['category' => 'wedding', 'title' => 'Wedding Setup', 'image_url' => '/storage/gallery/wedding-1.jpg', 'alt_text' => 'Setup wedding', 'sort_order' => 1],
        ];

        foreach ($galleries as $g) {
            Gallery::query()->updateOrCreate(
                ['category' => $g['category'], 'image_url' => $g['image_url']],
                [
                    'title' => $g['title'],
                    'alt_text' => $g['alt_text'],
                    'sort_order' => $g['sort_order'],
                    'is_active' => true,
                    'created_by' => null,
                ]
            );
        }
    }
}
