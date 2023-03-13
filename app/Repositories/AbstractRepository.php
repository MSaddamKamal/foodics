<?php

namespace App\Repositories;
use Carbon\Carbon;

class AbstractRepository {

    public function getAll()
    {
        return $this->model->all();
    }
    
    public function create($data = [], $makeInstance = true)
    {
        $newInstance = $this->model;
        if($makeInstance) {
            $newInstance = $this->model->newInstance();
        }
        foreach ($data as $key => $value) {
            $newInstance->{$key} = $value;
        }

        if($newInstance->save()) {
            return $this->getById($newInstance->id);
        }
        return null;
    }

    public function update($data = [])
    {
        $record = $this->model->find($data['id']);

        if($record != NULL){
            foreach ($data as $key => $value) {
                $record->{$key} = $value;
            }
            $record->updated_at = Carbon::now();
            if($record->save()) {
                return $this->getById($data['id']);
            }
        }

        return null;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getByAttribute(array $values)
    {
        $queryBuilder = $this->model;


        foreach ($values as $column => $value) {

            $queryBuilder = $queryBuilder->where($column, $value);
        }

        return $queryBuilder->get();

    }
}