<?php

namespace App\Repositories;

use Notification;
use App\Models\Order;
use App\Models\User;
use App\Models\Ingredient;
use App\Repositories\OrderProductRepository;
use App\Repositories\IngredientProductRepository;
use App\Repositories\IngredientRepository;
use App\Repositories\ProductRepository;
use App\Notifications\EmailNotification;

class OrderRepository extends AbstractRepository
{
    /**
     * @var Order
     */
    protected Order $model;
 
    /**
     * @var OrderProductRepository
     */
    private OrderProductRepository $order_product_repo;

    /**
     * @var ProductRepository
     */
    private ProductRepository $product_repo;
 
    /**
     * @var IngredientProductRepository
     */
    private IngredientProductRepository $product_ingredient_repo;
   
    /**
     * @var IngredientRepository
     */
    private IngredientRepository $ingredient_repo;

    public function __construct(Order $model,
                                OrderProductRepository $order_product_repo,
                                IngredientProductRepository $product_ingredient_repo,
                                IngredientRepository $ingredient_repo,
                                ProductRepository $product_repo
                                )
    {
        $this->model = $model;
        $this->order_product_repo = $order_product_repo;
        $this->product_ingredient_repo = $product_ingredient_repo;
        $this->ingredient_repo = $ingredient_repo;
        $this->product_repo = $product_repo;
    }

    public function create($email_data = [], $makeInstance = true)
    {
        \DB::beginTransaction();
        $low_stock_email_list = []; // list for emails to be sent for low stock ingredeients 

        try {
            // create order
            $order = parent::create(['user_id'=>$email_data['user_id']],$makeInstance);
            // insert each product in the order in order_product table
            foreach($email_data['products'] as $product){

                $this->order_product_repo->create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ]);    

                // fetch each product details e.g its ingredients
                $product_details = $this->product_repo->getById($product['product_id']);

                foreach($product_details->ingredients as $ingredient){

                        $quantiy_per_product = $ingredient->pivot->quantiy_per_product;
                        $quantiy_for_x_products = $quantiy_per_product * $product['quantity'];
                        $remaining_ingredient_quantity = $ingredient->quantity - $quantiy_for_x_products;
                        $low_stock = $ingredient->is_lowstock;

                        if($remaining_ingredient_quantity <0){
                            // insufficent qunatity of ingredents left
                            // needs rollback order
                            throw new \Exception('Insufficent Ingredients to Cater Order');
                        }

                        if(($remaining_ingredient_quantity <= $ingredient->opening_stock * Ingredient::LOW_STOCK_INDICATOR_PERCENTAGE) && !$low_stock ){

                            $low_stock = 1;
                             // add to email list
                            $low_stock_email_list[] = $ingredient; 
                        }

                        $this->ingredient_repo->update([
                            'id' =>  $ingredient->id,
                            'is_lowstock' => $low_stock,
                            'quantity' => $remaining_ingredient_quantity,
                        ]);
                        
                }    
            
            }

            \DB::commit();

            foreach($low_stock_email_list as $ingredient){
                // send email for ingredients
            $admin = User::where('email',User::ADMIN_EMAIL)->first();
                $email_data = [
                    'greeting' => 'Hi Admin,',
                    'body' => 'The ingredient '.$ingredient->name.' has now reached to '.Ingredient::LOW_STOCK_INDICATOR_PERCENTAGE*100 .'%' ,
                    'thanks' => 'Thank you',
                    'actionText' => 'View Sorck',
                    'actionURL' => url('/'),
                    'id' => $ingredient->id
    
                ];
          
                Notification::send($admin, new EmailNotification($email_data));
            }

            return $order;

        } catch (\Exception $e) {
            // something went wrong
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }

        
    }




}