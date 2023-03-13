<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository extends AbstractRepository
{
    /**
     * @var Product
     */
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }



}