<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\User;
use App\Models\IngredientProduct;
use Carbon\Carbon;
use Database\Seeders\UserTableSeeder;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    
    // Simple Payload
    public function getSimplePayload($quantity = 1): array
    {
        $random_user = User::inRandomOrder()->first();
        $random_product = Product::inRandomOrder()->first();

        return [
            'products' => [
               [
                'product_id' => $random_product->id,
                'quantity' => $quantity,
               ]
            ],
            'user_id' => $random_user->id

        ];
    }

    // custom data insertion in db for particular use cases
    public function prepareCustomIngredientsData()
    {
        $this->artisan('migrate:fresh');
        $this->seed(UserTableSeeder::class);
        
        $products = [
            [
              'id' => 1,
              'name' => 'Burger', 
              'created_at' => Carbon::now(),
              'updated_at' => Carbon::now()
            ]
          ];
  
        Product::insert($products);

        $ingredients = [
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

        Ingredient::insert($ingredients);

        
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
            ]
            
          ];
  
          IngredientProduct::insert($data);
    }

    // dummy calculations based on CustomIngredientsData function
    public function getIngredientRemainingQuantity($quantity)
    {
      $ingredient1_remaining_quantity = 20000 - ($quantity * 150) ; // old stock - (ordered_quantity * ingredient_quantity_consumed_per_product)
      $ingredient2_remaining_quantity = 5000 - ($quantity * 30) ;
      $ingredient3_remaining_quantity = 1000 - ($quantity * 20) ;

      return [
        'ingredient1_remaining_quantity' => $ingredient1_remaining_quantity,
        'ingredient2_remaining_quantity' => $ingredient2_remaining_quantity,
        'ingredient3_remaining_quantity' => $ingredient3_remaining_quantity,
      ];
    }

    /**
     * Order Can Be Correctly Stored.
     */
    public function test_order_can_be_correctly_stored(): void
    {
        // Run the DatabaseSeeder...
        $this->seed();
        $random_user = User::inRandomOrder()->first();
        $random_product = Product::inRandomOrder()->first();
        $order_id = 1;
        $quantity = 1;

        $payload = [
            'products' => [
               [
                'product_id' => $random_product->id,
                'quantity' => $quantity,
               ]
            ],
            'user_id' => $random_user->id

        ];
        
        $response = $this->postJson('api/place-order',$payload);
        $response
        ->assertStatus(201)
        ->assertJson(['success' => 'true'])
        ->assertJson(['message' => 'Success'])
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'status',
                'products',
            ]
          ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $random_user->id,
            'status' => 0
        ]);

        $this->assertDatabaseHas('order_product', [
            'order_id' => $order_id,
            'product_id' => $random_product->id,
            'quantity' => $quantity
        ]);
        
        
    }

    /**
     * Order Route is Accessible.
     */
    // public function test_order_route_is_accessible(): void
    // {
    //     $this->seed();

    //     $payload = $this->getSimplePayload();
        
    //     $response = $this->postJson('api/place-order',$payload);
    //     $response->assertStatus(201);
    // }

    /**
     * stock was correctly updated.
     */
    public function test_stock_was_correctly_updated(): void
    {
        
        $this->prepareCustomIngredientsData();

        $quantity = 1;
        $payload = $this->getSimplePayload($quantity);
        $remaining_quantities = $this->getIngredientRemainingQuantity($quantity);

        
        $response = $this->postJson('api/place-order',$payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('ingredients', [
            'id' => 1,
            'name' => 'Beef',
            'quantity' => $remaining_quantities['ingredient1_remaining_quantity']
        ]);

        $this->assertDatabaseHas('ingredients', [
            'id' => 2,
            'name' => 'Cheese',
            'quantity' => $remaining_quantities['ingredient2_remaining_quantity']
        ]);

        $this->assertDatabaseHas('ingredients', [
            'id' => 3,
            'name' => 'Onion',
            'quantity' => $remaining_quantities['ingredient3_remaining_quantity']
        ]);

        
        
        
    }

     /**
     * email sent on low stock.
     */
    public function test_email_sent_on_low_stock(): void
    {
        
        $this->prepareCustomIngredientsData();
        $quantity = 26;
        $payload = $this->getSimplePayload($quantity);

        $response = $this->postJson('api/place-order',$payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('ingredients', [
            'id' => 3,
            'name' => 'Onion',
            'quantity' => $this->getIngredientRemainingQuantity($quantity)['ingredient3_remaining_quantity'],
            'is_lowstock' => 1 // email identifer
        ]);

        $this->assertDatabaseHas('notifications', [
            'data' => '{"ingredient_id":3}',
            'type'=> 'App\Notifications\EmailNotification'
        ]);

        
        
        
    }
}
