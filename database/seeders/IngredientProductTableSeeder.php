<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IngredientProduct;
use Carbon\Carbon;

class IngredientProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        IngredientProduct::truncate();

        $data = [
          [
            'product_id' => 1, 
            'ingredient_id' => 1, 
            'quantiy_per_product' => 150,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'product_id' => 1, 
            'ingredient_id' => 2, 
            'quantiy_per_product' => 30,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'product_id' => 1, 
            'ingredient_id' => 3, 
            'quantiy_per_product' => 20,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],

          [
            'product_id' => 2, 
            'ingredient_id' => 1, 
            'quantiy_per_product' => 250,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'product_id' => 2, 
            'ingredient_id' => 2, 
            'quantiy_per_product' => 300,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'product_id' => 2, 
            'ingredient_id' => 3, 
            'quantiy_per_product' => 50,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          
        ];

        IngredientProduct::insert($data);
    }
}
