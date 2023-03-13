<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use Carbon\Carbon;

class IngredientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::truncate();

        $data = [
          [
            'id' => 1,
            'name' => 'Beef', 
            'quantity' => '20000', 
            'opening_stock' => '20000',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'id' => 2,
            'name' => 'Cheese', 
            'quantity' => '5000', 
            'opening_stock' => '5000',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
          [
            'id' => 3,
            'name' => 'Onion', 
            'quantity' => '1000', 
            'opening_stock' => '1000',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
          ],
        ];

        Ingredient::insert($data);
    }
}
