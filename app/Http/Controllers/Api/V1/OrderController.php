<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Repositories\OrderProductRepository;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\ErrorResource;

/**
 * @group Orders
 *
 * APIs for managing Orders
 */

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    public OrderRepository $order_repo;
    /**
     * @var OrderProductRepository
     */
    public OrderProductRepository $order_product_repo;
  
    /**
     * @param OrderRepository $order_repo
     * @param OrderProductRepository $order_product_repo
     */
    public function __construct(OrderRepository $order_repo,OrderProductRepository $order_product_repo)
    {
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  StoreOrderRequest $request
    * @return ErrorResource|OrderResource
    */


    /**
     * Store
     *
     * This endpoint is used to store an order to the system.
     *
     * @bodyParam products object[] required needs the product_id and quanity for each array item. Example: [{"product_id": 1,"quantity": 1},{"product_id": 2,"quantity": 1}]
     * @bodyParam products.product_id int required The id of the product Example: 1
     * @bodyParam products.quantity int required The quantity of the product Example: 3
     * @bodyParam user_id int required Note: The user_id is required for just sample scenerio, in real scenrio get id from auth user so no need to pass it.  Example: 2
     *
     * @response scenario="success" {
     * "success": true,
     *   "data": {
     *       "id": 16,
     *       "status": 0,
     *       "products": [
     *           {
     *               "id": 1,
     *               "name": "Burger",
     *               "created_at": "2023-03-12T22:38:03.000000Z",
     *               "updated_at": "2023-03-12T22:38:03.000000Z",
     *               "pivot": {
     *                   "order_id": 16,
     *                   "product_id": 1,
     *                   "quantity": 1
     *              }
     *           },
     *           {
     *               "id": 2,
     *               "name": "Pizza",
     *               "created_at": "2023-03-12T22:38:03.000000Z",
     *               "updated_at": "2023-03-12T22:38:03.000000Z",
     *               "pivot": {
     *                   "order_id": 16,
     *                   "product_id": 2,
     *                   "quantity": 1
     *               }
     *           }
     *       ]
     *   },
     *   "message": "Success"
     * }
     *
     * @response scenario="Some Validation Error || insuficient Ingredeints Left || Other Exception"{
     *   "success": false,
     *   "data": null,
     *   "message": "some error msg"
     * }
     *
     */
    public function store(StoreOrderRequest $request){

        try{
            // create order
            $order = $this->order_repo->create($request->validated());
            return (new OrderResource($order))
                                ->response()
                                ->setStatusCode(201);
            
        } catch (\Exception $e) {
            // some thing went wrong
          return new ErrorResource($e->getMessage());
        }
    
    }
}
