<?php

namespace App\Repositories;

use App\Models\Ingredient;

class IngredientRepository extends AbstractRepository
{
    /**
     * @var Ingredient
     */
    protected Ingredient $model;

    public function __construct(Ingredient $model)
    {
        $this->model = $model;
    }



}