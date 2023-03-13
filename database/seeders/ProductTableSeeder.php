<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();

        $data = [
          [
            'id' => 1,
            'name' => 'Burger', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'id' => 2,
            'name' => 'Pizza', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
        ];

        Product::insert($data);
    }
}
