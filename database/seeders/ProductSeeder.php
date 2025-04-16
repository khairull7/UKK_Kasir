<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); // Inisialisasi Faker

        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'name' => $faker->word(), // Nama produk
                'image' => 'https://via.placeholder.com/150', // Gambar produk, menggunakan placeholder
                'price' => $faker->randomNumber(5, true), // Harga acak
                'stock' => $faker->randomNumber(2), // Stok acak
            ]);
        }
    }
}
