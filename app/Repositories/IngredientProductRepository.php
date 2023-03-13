<?php

namespace App\Repositories;

use App\Models\IngredientProduct;

class IngredientProductRepository extends AbstractRepository
{
    /**
     * @var IngredientProduct
     */
    protected IngredientProduct $model;

    public function __construct(IngredientProduct $model)
    {
        $this->model = $model;
    }




}