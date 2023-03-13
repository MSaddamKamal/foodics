<?php

namespace App\Repositories;

use App\Models\OrderProduct;

class OrderProductRepository extends AbstractRepository
{
    protected $model;

    public function __construct(OrderProduct $model)
    {
        $this->model = $model;
    }




}