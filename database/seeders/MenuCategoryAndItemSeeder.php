<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

/**
 * Seed data menu berdasarkan Menu Ramadhan 2026 Dapur Kawalli.
 * Menggunakan updateOrCreate agar aman dijalankan berulang (idempotent).
 */
class MenuCategoryAndItemSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedMenuItems();
    }

    // -------------------------------------------------------------------------
    // Categories
    // -------------------------------------------------------------------------

    private function seedCategories(): void
    {
        foreach ($this->categories() as $data) {
            MenuCategory::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name'         => $data['name'],
                    'cuisine_type' => $data['cuisine_type'],
                    'sort_order'   => $data['sort_order'],
                    'is_active'    => true,
                    'created_by'   => null,
                ]
            );
        }
    }

    // -------------------------------------------------------------------------
    // Menu Items
    // -------------------------------------------------------------------------

    private function seedMenuItems(): void
    {
        foreach ($this->menuItems() as $item) {
            $category = MenuCategory::query()
                ->where('slug', $item['category_slug'])
                ->first();

            if (! $category) {
                $this->command->warn("Category not found: {$item['category_slug']} — skipping {$item['name']}");
                continue;
            }

            MenuItem::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'menu_category_id' => $category->id,
                    'name'             => $item['name'],
                    'description'      => $item['description'] ?? null,
                    'price'            => $item['price'],
                    'image_url'        => null,
                    'is_featured'      => $item['is_featured'] ?? false,
                    'is_available'     => true,
                    'sort_order'       => $item['sort_order'],
                    'created_by'       => null,
                ]
            );
        }
    }

    // =========================================================================
    // DATA DEFINITIONS
    // =========================================================================

    private function categories(): array
    {
        return [
            // --- MAKANAN ---
            ['name' => 'Menu Nasi',      'slug' => 'menu-nasi',      'cuisine_type' => 'makanan', 'sort_order' => 1],
            ['name' => 'Menu Mie',       'slug' => 'menu-mie',       'cuisine_type' => 'makanan', 'sort_order' => 2],
            ['name' => 'Olahan Ayam',    'slug' => 'olahan-ayam',    'cuisine_type' => 'makanan', 'sort_order' => 3],
            ['name' => 'Olahan Gurame',  'slug' => 'olahan-gurame',  'cuisine_type' => 'makanan', 'sort_order' => 4],
            ['name' => 'Olahan Iga',     'slug' => 'olahan-iga',     'cuisine_type' => 'makanan', 'sort_order' => 5],
            ['name' => 'Olahan Buntut',  'slug' => 'olahan-buntut',  'cuisine_type' => 'makanan', 'sort_order' => 6],
            ['name' => 'Gepuk',          'slug' => 'gepuk',          'cuisine_type' => 'makanan', 'sort_order' => 7],
            ['name' => 'Ikan Nila',      'slug' => 'ikan-nila',      'cuisine_type' => 'makanan', 'sort_order' => 8],
            ['name' => 'Kuwe',           'slug' => 'kuwe',           'cuisine_type' => 'makanan', 'sort_order' => 9],
            ['name' => 'Bawal',          'slug' => 'bawal',          'cuisine_type' => 'makanan', 'sort_order' => 10],
            ['name' => 'Udang',          'slug' => 'udang',          'cuisine_type' => 'makanan', 'sort_order' => 11],
            ['name' => 'Cumi',           'slug' => 'cumi',           'cuisine_type' => 'makanan', 'sort_order' => 12],
            ['name' => 'Kerang',         'slug' => 'kerang',         'cuisine_type' => 'makanan', 'sort_order' => 13],
            ['name' => 'Olahan Tumis',   'slug' => 'olahan-tumis',   'cuisine_type' => 'makanan', 'sort_order' => 14],
            ['name' => 'Sambal',         'slug' => 'sambal',         'cuisine_type' => 'makanan', 'sort_order' => 15],
            ['name' => 'Menu Tambahan',  'slug' => 'menu-tambahan',  'cuisine_type' => 'makanan', 'sort_order' => 16],
            ['name' => 'Cemilan',        'slug' => 'cemilan',        'cuisine_type' => 'makanan', 'sort_order' => 17],

            // --- MINUMAN ---
            ['name' => 'Fresh Juice',    'slug' => 'fresh-juice',    'cuisine_type' => 'minuman', 'sort_order' => 18],
            ['name' => 'Ice Series',     'slug' => 'ice-series',     'cuisine_type' => 'minuman', 'sort_order' => 19],
            ['name' => 'Minuman Hangat', 'slug' => 'minuman-hangat', 'cuisine_type' => 'minuman', 'sort_order' => 20],

            // --- DESSERT ---
            ['name' => 'Dessert',        'slug' => 'dessert',        'cuisine_type' => 'dessert', 'sort_order' => 21],
        ];
    }

    private function menuItems(): array
    {
        return [
            // =================================================================
            // MENU NASI
            // =================================================================
            ['category_slug' => 'menu-nasi', 'name' => 'Nasi Putih',             'slug' => 'nasi-putih',             'price' => 8000,  'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'menu-nasi', 'name' => 'Nasi Goreng Spesial',    'slug' => 'nasi-goreng-spesial',    'price' => 25000, 'sort_order' => 2, 'is_featured' => true],
            ['category_slug' => 'menu-nasi', 'name' => 'Nasi Goreng Jambal Pete', 'slug' => 'nasi-goreng-jambal-pete', 'price' => 25000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'menu-nasi', 'name' => 'Nasi Goreng Biasa',      'slug' => 'nasi-goreng-biasa',      'price' => 20000, 'sort_order' => 4, 'is_featured' => false],
            ['category_slug' => 'menu-nasi', 'name' => 'Nasi Goreng Vietnam',    'slug' => 'nasi-goreng-vietnam',    'price' => 25000, 'sort_order' => 5, 'is_featured' => false],

            // =================================================================
            // MENU MIE
            // =================================================================
            ['category_slug' => 'menu-mie', 'name' => 'Mie Goreng Dapur Kawalli', 'slug' => 'mie-goreng-dapur-kawalli', 'price' => 25000, 'sort_order' => 1, 'is_featured' => false],

            // =================================================================
            // OLAHAN AYAM
            // =================================================================
            ['category_slug' => 'olahan-ayam', 'name' => 'Goreng Serudeng',     'slug' => 'ayam-goreng-serudeng',    'price' => 22000, 'sort_order' => 1,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Goreng Crispy',       'slug' => 'ayam-goreng-crispy',      'price' => 24000, 'sort_order' => 2,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Ayam Penyet',         'slug' => 'ayam-penyet',             'price' => 26000, 'sort_order' => 3,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Ayam Teriyaki',       'slug' => 'ayam-teriyaki',           'price' => 24000, 'sort_order' => 4,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Ayam Mentega',        'slug' => 'ayam-mentega',            'price' => 24000, 'sort_order' => 5,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Ayam Sambel Matah',   'slug' => 'ayam-sambel-matah',       'price' => 24000, 'sort_order' => 6,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Ayam Bakar',          'slug' => 'ayam-bakar',              'price' => 25000, 'sort_order' => 7,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Sop Ayam',            'slug' => 'sop-ayam',                'price' => 28000, 'sort_order' => 8,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Soto Ayam',           'slug' => 'soto-ayam',               'price' => 28000, 'sort_order' => 9,  'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Bakakak Goreng',      'slug' => 'bakakak-goreng',          'price' => 80000, 'sort_order' => 10, 'is_featured' => true],
            ['category_slug' => 'olahan-ayam', 'name' => 'Bakakak Bakar',       'slug' => 'bakakak-bakar',           'price' => 83000, 'sort_order' => 11, 'is_featured' => true],
            ['category_slug' => 'olahan-ayam', 'name' => 'Bakakak Penyet',      'slug' => 'bakakak-penyet',          'price' => 83000, 'sort_order' => 12, 'is_featured' => false],
            ['category_slug' => 'olahan-ayam', 'name' => 'Bakakak Crispy',      'slug' => 'bakakak-crispy',          'price' => 83000, 'sort_order' => 13, 'is_featured' => false],

            // =================================================================
            // OLAHAN GURAME
            // =================================================================
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Fillet Goreng',          'slug' => 'gurame-fillet-goreng',          'price' => 75000, 'sort_order' => 1,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Fillet Saos Padang',     'slug' => 'gurame-fillet-saos-padang',     'price' => 83000, 'sort_order' => 2,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Fillet Saos Mangga',     'slug' => 'gurame-fillet-saos-mangga',     'price' => 83000, 'sort_order' => 3,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Fillet Asam Manis',      'slug' => 'gurame-fillet-asam-manis',      'price' => 83000, 'sort_order' => 4,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Fillet Acar Kuning',     'slug' => 'gurame-fillet-acar-kuning',     'price' => 83000, 'sort_order' => 5,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Fillet Colo-colo',       'slug' => 'gurame-fillet-colo-colo',       'price' => 83000, 'sort_order' => 6,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Pecak',                  'slug' => 'gurame-pecak',                  'price' => 80000, 'sort_order' => 7,  'is_featured' => true],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Sop',                    'slug' => 'gurame-sop',                    'price' => 80000, 'sort_order' => 8,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Tim',                    'slug' => 'gurame-tim',                    'price' => 80000, 'sort_order' => 9,  'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Bakar',                  'slug' => 'gurame-bakar',                  'price' => 80000, 'sort_order' => 10, 'is_featured' => false],
            ['category_slug' => 'olahan-gurame', 'name' => 'Gurame Bakar Bumbu Cobek',      'slug' => 'gurame-bakar-bumbu-cobek',      'price' => 83000, 'sort_order' => 11, 'is_featured' => true],

            // =================================================================
            // OLAHAN IGA
            // =================================================================
            ['category_slug' => 'olahan-iga', 'name' => 'Iga Bakar',      'slug' => 'iga-bakar',      'price' => 75000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'olahan-iga', 'name' => 'Iga Penyet',     'slug' => 'iga-penyet',     'price' => 75000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'olahan-iga', 'name' => 'Iga Garam Asem', 'slug' => 'iga-garam-asem', 'price' => 75000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'olahan-iga', 'name' => 'Sop Iga',        'slug' => 'sop-iga',        'price' => 75000, 'sort_order' => 4, 'is_featured' => true],

            // =================================================================
            // OLAHAN BUNTUT
            // =================================================================
            ['category_slug' => 'olahan-buntut', 'name' => 'Buntut Goreng', 'slug' => 'buntut-goreng', 'price' => 85000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'olahan-buntut', 'name' => 'Sop Buntut',    'slug' => 'sop-buntut',    'price' => 85000, 'sort_order' => 2, 'is_featured' => true],

            // =================================================================
            // GEPUK
            // =================================================================
            ['category_slug' => 'gepuk', 'name' => 'Gepuk Goreng',        'slug' => 'gepuk-goreng',        'price' => 43000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'gepuk', 'name' => 'Gepuk Goreng Crispy', 'slug' => 'gepuk-goreng-crispy', 'price' => 45000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'gepuk', 'name' => 'Gepuk Cabe Hijau',    'slug' => 'gepuk-cabe-hijau',    'price' => 45000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'gepuk', 'name' => 'Gepuk Sambel Matah',  'slug' => 'gepuk-sambel-matah',  'price' => 45000, 'sort_order' => 4, 'is_featured' => false],

            // =================================================================
            // IKAN NILA
            // =================================================================
            ['category_slug' => 'ikan-nila', 'name' => 'Nila Goreng', 'slug' => 'nila-goreng', 'price' => 45000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'ikan-nila', 'name' => 'Nila Pecak',  'slug' => 'nila-pecak',  'price' => 50000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'ikan-nila', 'name' => 'Nila Bakar',  'slug' => 'nila-bakar',  'price' => 50000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'ikan-nila', 'name' => 'Nila Sop',    'slug' => 'nila-sop',    'price' => 50000, 'sort_order' => 4, 'is_featured' => false],
            ['category_slug' => 'ikan-nila', 'name' => 'Nila Tim',    'slug' => 'nila-tim',    'price' => 50000, 'sort_order' => 5, 'is_featured' => false],

            // =================================================================
            // KUWE
            // =================================================================
            ['category_slug' => 'kuwe', 'name' => 'Kuwe Goreng',    'slug' => 'kuwe-goreng',    'price' => 55000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'kuwe', 'name' => 'Kuwe Pecak',     'slug' => 'kuwe-pecak',     'price' => 60000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'kuwe', 'name' => 'Kuwe Bakar',     'slug' => 'kuwe-bakar',     'price' => 60000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'kuwe', 'name' => 'Kuwe Asam Manis', 'slug' => 'kuwe-asam-manis', 'price' => 60000, 'sort_order' => 4, 'is_featured' => false],

            // =================================================================
            // BAWAL
            // =================================================================
            ['category_slug' => 'bawal', 'name' => 'Bawal Goreng',    'slug' => 'bawal-goreng',    'price' => 55000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'bawal', 'name' => 'Bawal Pecak',     'slug' => 'bawal-pecak',     'price' => 60000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'bawal', 'name' => 'Bawal Bakar',     'slug' => 'bawal-bakar',     'price' => 60000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'bawal', 'name' => 'Bawal Asam Manis', 'slug' => 'bawal-asam-manis', 'price' => 60000, 'sort_order' => 4, 'is_featured' => false],

            // =================================================================
            // UDANG
            // =================================================================
            ['category_slug' => 'udang', 'name' => 'Udang Goreng Tepung', 'slug' => 'udang-goreng-tepung', 'price' => 75000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'udang', 'name' => 'Udang Saos Padang',   'slug' => 'udang-saos-padang',   'price' => 80000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'udang', 'name' => 'Udang Saos Tiram',    'slug' => 'udang-saos-tiram',    'price' => 80000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'udang', 'name' => 'Udang Asam Manis',    'slug' => 'udang-asam-manis',    'price' => 80000, 'sort_order' => 4, 'is_featured' => false],
            ['category_slug' => 'udang', 'name' => 'Udang Mentega',       'slug' => 'udang-mentega',       'price' => 80000, 'sort_order' => 5, 'is_featured' => false],

            // =================================================================
            // CUMI
            // =================================================================
            ['category_slug' => 'cumi', 'name' => 'Cumi Goreng Tepung', 'slug' => 'cumi-goreng-tepung', 'price' => 75000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'cumi', 'name' => 'Cumi Saos Padang',   'slug' => 'cumi-saos-padang',   'price' => 80000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'cumi', 'name' => 'Cumi Saos Tiram',    'slug' => 'cumi-saos-tiram',    'price' => 80000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'cumi', 'name' => 'Cumi Asam Manis',    'slug' => 'cumi-asam-manis',    'price' => 80000, 'sort_order' => 4, 'is_featured' => false],
            ['category_slug' => 'cumi', 'name' => 'Cumi Mentega',       'slug' => 'cumi-mentega',       'price' => 80000, 'sort_order' => 5, 'is_featured' => false],

            // =================================================================
            // KERANG
            // =================================================================
            ['category_slug' => 'kerang', 'name' => 'Kerang Ijo Rebus',  'slug' => 'kerang-ijo-rebus',  'price' => 22000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'kerang', 'name' => 'Kerang Saos Padang', 'slug' => 'kerang-saos-padang', 'price' => 25000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'kerang', 'name' => 'Kerang Saos Tiram', 'slug' => 'kerang-saos-tiram', 'price' => 25000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'kerang', 'name' => 'Kerang Asam Manis', 'slug' => 'kerang-asam-manis', 'price' => 25000, 'sort_order' => 4, 'is_featured' => false],

            // =================================================================
            // OLAHAN TUMIS
            // =================================================================
            ['category_slug' => 'olahan-tumis', 'name' => 'Kangkung Polos',    'slug' => 'kangkung-polos',    'price' => 20000, 'sort_order' => 1,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Kangkung Balacan',  'slug' => 'kangkung-balacan',  'price' => 25000, 'sort_order' => 2,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Kangkung Tauco',    'slug' => 'kangkung-tauco',    'price' => 25000, 'sort_order' => 3,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Kangkung Pedas',    'slug' => 'kangkung-pedas',    'price' => 25000, 'sort_order' => 4,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Toge Polos',        'slug' => 'toge-polos',        'price' => 22000, 'sort_order' => 5,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Toge Asin Jambal',  'slug' => 'toge-asin-jambal',  'price' => 23000, 'sort_order' => 6,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Genjer Polos',      'slug' => 'genjer-polos',      'price' => 20000, 'sort_order' => 7,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Genjer Tauco',      'slug' => 'genjer-tauco',      'price' => 25000, 'sort_order' => 8,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Genjer Pedas',      'slug' => 'genjer-pedas',      'price' => 25000, 'sort_order' => 9,  'is_featured' => false],
            ['category_slug' => 'olahan-tumis', 'name' => 'Peda Asin',         'slug' => 'peda-asin',         'price' => 22000, 'sort_order' => 10, 'is_featured' => false],

            // =================================================================
            // SAMBAL
            // =================================================================
            ['category_slug' => 'sambal', 'name' => 'Sambal Dadak',  'slug' => 'sambal-dadak',  'price' => 12000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'sambal', 'name' => 'Sambal Petir',  'slug' => 'sambal-petir',  'price' => 12000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'sambal', 'name' => 'Sambal Mangga', 'slug' => 'sambal-mangga', 'price' => 12000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'sambal', 'name' => 'Sambal Pete',   'slug' => 'sambal-pete',   'price' => 17000, 'sort_order' => 4, 'is_featured' => false],

            // =================================================================
            // MENU TAMBAHAN
            // =================================================================
            ['category_slug' => 'menu-tambahan', 'name' => 'Karedok',               'slug' => 'karedok',               'price' => 15000, 'sort_order' => 1,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Lotek',                 'slug' => 'lotek',                 'price' => 15000, 'sort_order' => 2,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Sayur Asem',            'slug' => 'sayur-asem',            'price' => 15000, 'sort_order' => 3,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Lalapan',               'slug' => 'lalapan',               'price' => 11000, 'sort_order' => 4,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Pete (Rebus/Goreng/Bakar)', 'slug' => 'pete',              'price' => 12000, 'sort_order' => 5,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Telur Dadar',           'slug' => 'telur-dadar',           'price' => 12000, 'sort_order' => 6,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Telur Mata Sapi',       'slug' => 'telur-mata-sapi',       'price' => 8500,  'sort_order' => 7,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Tahu Goreng',           'slug' => 'tahu-goreng',           'price' => 4000,  'sort_order' => 8,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Tahu Crispy',           'slug' => 'tahu-crispy',           'price' => 5000,  'sort_order' => 9,  'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Tempe Goreng',          'slug' => 'tempe-goreng',          'price' => 5000,  'sort_order' => 10, 'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Tempe Crispy',          'slug' => 'tempe-crispy',          'price' => 6000,  'sort_order' => 11, 'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Jengkol Goreng',        'slug' => 'jengkol-goreng',        'price' => 22000, 'sort_order' => 12, 'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Jengkol Balado',        'slug' => 'jengkol-balado',        'price' => 25000, 'sort_order' => 13, 'is_featured' => false],
            ['category_slug' => 'menu-tambahan', 'name' => 'Jengkol Pecak',         'slug' => 'jengkol-pecak',         'price' => 25000, 'sort_order' => 14, 'is_featured' => false],

            // =================================================================
            // CEMILAN
            // =================================================================
            ['category_slug' => 'cemilan', 'name' => 'Bakwan Jagung',  'slug' => 'bakwan-jagung',  'price' => 20000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'cemilan', 'name' => 'Kentang Goreng', 'slug' => 'kentang-goreng', 'price' => 20000, 'sort_order' => 2, 'is_featured' => false],

            // =================================================================
            // FRESH JUICE
            // =================================================================
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Kurma DK',  'slug' => 'juice-kurma-dk',  'price' => 20000, 'sort_order' => 1, 'is_featured' => true],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Alpukat',   'slug' => 'juice-alpukat',   'price' => 25000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Sirsak',    'slug' => 'juice-sirsak',    'price' => 23000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Mangga',    'slug' => 'juice-mangga',    'price' => 25000, 'sort_order' => 4, 'is_featured' => false],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Jambu',     'slug' => 'juice-jambu',     'price' => 23000, 'sort_order' => 5, 'is_featured' => false],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Strawberry', 'slug' => 'juice-strawberry', 'price' => 23000, 'sort_order' => 6, 'is_featured' => false],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Melon',     'slug' => 'juice-melon',     'price' => 20000, 'sort_order' => 7, 'is_featured' => false],
            ['category_slug' => 'fresh-juice', 'name' => 'Juice Jeruk',     'slug' => 'juice-jeruk',     'price' => 20000, 'sort_order' => 8, 'is_featured' => false],

            // =================================================================
            // ICE SERIES
            // =================================================================
            ['category_slug' => 'ice-series', 'name' => 'Sop Buah DK',         'slug' => 'sop-buah-dk',         'price' => 28000, 'sort_order' => 1,  'is_featured' => true],
            ['category_slug' => 'ice-series', 'name' => 'Es Kuwut',             'slug' => 'es-kuwut',            'price' => 23000, 'sort_order' => 2,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Kabayan',           'slug' => 'es-kabayan',          'price' => 25000, 'sort_order' => 3,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Red Dragon',        'slug' => 'es-red-dragon',       'price' => 25000, 'sort_order' => 4,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Oyen DK',           'slug' => 'es-oyen-dk',          'price' => 27000, 'sort_order' => 5,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Oyen Durian',       'slug' => 'es-oyen-durian',      'price' => 30000, 'sort_order' => 6,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Dawegan',           'slug' => 'es-dawegan',          'price' => 27000, 'sort_order' => 7,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Milk Strawberry',   'slug' => 'es-milk-strawberry',  'price' => 25000, 'sort_order' => 8,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Jeruk Kelapa',      'slug' => 'es-jeruk-kelapa',     'price' => 23000, 'sort_order' => 9,  'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Sirsak Kelapa',     'slug' => 'es-sirsak-kelapa',    'price' => 22000, 'sort_order' => 10, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Kelapa Cincau',     'slug' => 'es-kelapa-cincau',    'price' => 20000, 'sort_order' => 11, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Cappucino Cincau',  'slug' => 'es-cappucino-cincau', 'price' => 25000, 'sort_order' => 12, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Pana Cotta Durian', 'slug' => 'es-pana-cotta-durian', 'price' => 30000, 'sort_order' => 13, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Kelapa Muda',       'slug' => 'es-kelapa-muda',      'price' => 23000, 'sort_order' => 14, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Jeruk',             'slug' => 'es-jeruk',            'price' => 17000, 'sort_order' => 15, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Cendol',            'slug' => 'es-cendol',           'price' => 17000, 'sort_order' => 16, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Cincau Hitam',      'slug' => 'es-cincau-hitam',     'price' => 17000, 'sort_order' => 17, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Lemon Tea',         'slug' => 'es-lemon-tea',        'price' => 15000, 'sort_order' => 18, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Teh Manis',         'slug' => 'es-teh-manis',        'price' => 8000,  'sort_order' => 19, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Teh Tawar',         'slug' => 'es-teh-tawar',        'price' => 6000,  'sort_order' => 20, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Ice Cream',            'slug' => 'ice-cream',           'price' => 15000, 'sort_order' => 21, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Le Minerale',          'slug' => 'le-minerale',         'price' => 8000,  'sort_order' => 22, 'is_featured' => false],
            ['category_slug' => 'ice-series', 'name' => 'Es Batu',              'slug' => 'es-batu',             'price' => 3000,  'sort_order' => 23, 'is_featured' => false],

            // =================================================================
            // MINUMAN HANGAT
            // =================================================================
            ['category_slug' => 'minuman-hangat', 'name' => 'Jeruk Panas',          'slug' => 'jeruk-panas',          'price' => 18000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Kopi Susu',            'slug' => 'kopi-susu',            'price' => 12000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Kopi',                 'slug' => 'kopi',                 'price' => 10000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Susu',                 'slug' => 'susu',                 'price' => 10000, 'sort_order' => 4, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Teh Manis Hangat',     'slug' => 'teh-manis-hangat',     'price' => 7500,  'sort_order' => 5, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Bandrek Kelapa',       'slug' => 'bandrek-kelapa',       'price' => 12000, 'sort_order' => 6, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Bandrek Kelapa Susu',  'slug' => 'bandrek-kelapa-susu',  'price' => 15000, 'sort_order' => 7, 'is_featured' => false],
            ['category_slug' => 'minuman-hangat', 'name' => 'Lemon Tea Panas',      'slug' => 'lemon-tea-panas',      'price' => 12000, 'sort_order' => 8, 'is_featured' => false],

            // =================================================================
            // DESSERT
            // =================================================================
            ['category_slug' => 'dessert', 'name' => 'Sop Sirsak',  'slug' => 'sop-sirsak',  'price' => 30000, 'sort_order' => 1, 'is_featured' => false],
            ['category_slug' => 'dessert', 'name' => 'Mango Sago',  'slug' => 'mango-sago',  'price' => 25000, 'sort_order' => 2, 'is_featured' => false],
            ['category_slug' => 'dessert', 'name' => 'Es Kabayan',  'slug' => 'dessert-es-kabayan', 'price' => 25000, 'sort_order' => 3, 'is_featured' => false],
            ['category_slug' => 'dessert', 'name' => 'Es Teler',    'slug' => 'es-teler',    'price' => 28000, 'sort_order' => 4, 'is_featured' => true],
        ];
    }
}
