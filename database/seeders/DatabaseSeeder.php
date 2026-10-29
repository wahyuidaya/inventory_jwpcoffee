<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name'     => 'Admin JWP',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        // Akun Pimpinan
        User::create([
            'name'     => 'Pimpinan JWP',
            'email'    => 'pimpinan@gmail.com',
            'password' => Hash::make('pimpinan123'),
            'role'     => 'pimpinan',
        ]);

        // Kategori
        $kopi      = Category::create(['name' => 'Kopi',      'description' => 'Biji kopi, kopi bubuk, dan produk kopi']);
        $susu      = Category::create(['name' => 'Susu',      'description' => 'Susu segar, UHT, cream, dan produk dairy']);
        $sirup     = Category::create(['name' => 'Sirup',     'description' => 'Sirup perisa dan pemanis cair']);
        $nonKopi   = Category::create(['name' => 'Non-Kopi',  'description' => 'Matcha, coklat, dan minuman non kopi']);
        $packaging = Category::create(['name' => 'Packaging', 'description' => 'Cup, tutup, sedotan, dan perlengkapan saji']);

        // Produk — Kopi
        Product::create(['code' => 'KB-001', 'name' => 'Kopi Robusta', 'category_id' => $kopi->id, 'unit' => 'kg', 'stock' => 20, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-002', 'name' => 'Kopi Arabika', 'category_id' => $kopi->id, 'unit' => 'kg', 'stock' => 15, 'minimum_stock' => 10]);

        // Produk — Susu (5 merk)
        Product::create(['code' => 'KB-003', 'name' => 'Greenfields Full Cream',     'category_id' => $susu->id, 'unit' => 'liter', 'stock' => 20, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-004', 'name' => 'Indomilk UHT',               'category_id' => $susu->id, 'unit' => 'liter', 'stock' => 20, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-005', 'name' => 'Elle & Vire Whipping Cream', 'category_id' => $susu->id, 'unit' => 'liter', 'stock' => 12, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-006', 'name' => 'Oatside Oat Milk',           'category_id' => $susu->id, 'unit' => 'liter', 'stock' => 12, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-007', 'name' => 'Diamond Almond Milk',        'category_id' => $susu->id, 'unit' => 'liter', 'stock' => 12, 'minimum_stock' => 10]);

        // Produk — Sirup (5 varian)
        Product::create(['code' => 'KB-008', 'name' => 'Sirup Vanilla',     'category_id' => $sirup->id, 'unit' => 'botol', 'stock' => 15, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-009', 'name' => 'Sirup Caramel',     'category_id' => $sirup->id, 'unit' => 'botol', 'stock' => 15, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-010', 'name' => 'Sirup Hazelnut',    'category_id' => $sirup->id, 'unit' => 'botol', 'stock' => 12, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-011', 'name' => 'Brown Sugar Syrup', 'category_id' => $sirup->id, 'unit' => 'botol', 'stock' => 15, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-012', 'name' => 'Sirup Almond',      'category_id' => $sirup->id, 'unit' => 'botol', 'stock' => 12, 'minimum_stock' => 10]);

        // Produk — Non-Kopi (5 varian)
        Product::create(['code' => 'KB-013', 'name' => 'Matcha Powder',     'category_id' => $nonKopi->id, 'unit' => 'kg',  'stock' => 10, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-014', 'name' => 'Coklat Bubuk',      'category_id' => $nonKopi->id, 'unit' => 'kg',  'stock' => 10, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-015', 'name' => 'Red Velvet Powder', 'category_id' => $nonKopi->id, 'unit' => 'kg',  'stock' => 8,  'minimum_stock' => 10]);
        Product::create(['code' => 'KB-016', 'name' => 'Taro Powder',       'category_id' => $nonKopi->id, 'unit' => 'kg',  'stock' => 8,  'minimum_stock' => 10]);
        Product::create(['code' => 'KB-017', 'name' => 'Teh Celup',         'category_id' => $nonKopi->id, 'unit' => 'box', 'stock' => 15, 'minimum_stock' => 10]);

        // Produk — Packaging (5 item)
        Product::create(['code' => 'KB-018', 'name' => 'Cup Kertas',        'category_id' => $packaging->id, 'unit' => 'pack', 'stock' => 25, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-019', 'name' => 'Cup Plastik',       'category_id' => $packaging->id, 'unit' => 'pack', 'stock' => 25, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-020', 'name' => 'Tutup Cup Kertas',  'category_id' => $packaging->id, 'unit' => 'pack', 'stock' => 25, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-021', 'name' => 'Tutup Cup Plastik', 'category_id' => $packaging->id, 'unit' => 'pack', 'stock' => 25, 'minimum_stock' => 10]);
        Product::create(['code' => 'KB-022', 'name' => 'Sedotan',           'category_id' => $packaging->id, 'unit' => 'pack', 'stock' => 30, 'minimum_stock' => 10]);
    }
}