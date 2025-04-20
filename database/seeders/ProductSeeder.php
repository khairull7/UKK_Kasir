<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create(); 

        $images = [
            'template/img/produk1.png',
            'template/img/produk2.png',
            'template/img/produk3.png',
            'template/img/produk4.png',
            'template/img/produk5.png',
        ];

        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'nama_produk' => ucfirst($faker->word()), 
                'img' => $faker->randomElement($images), 
                'harga' => $faker->numberBetween(10000,  99999),
                'stok' => $faker->numberBetween(1, 50), 
            ]);
        }
    }
}
