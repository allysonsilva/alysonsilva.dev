<?php

namespace App\Support\ORM;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;

class BaseEloquentBuilder extends BaseBuilder
{
    /**
     * @inheritDoc
     */
    public function findOrFail($id, $columns = ['*']): BaseModel
    {
        if (! is_null($model = $this->find($id, $columns))) {
            return $model;
        }

        throw $this->model->exceptionWhenModelNotFound($id);
    }

    /**
     * @inheritDoc
     */
    public function firstOrFail($columns = ['*']): BaseModel
    {
        if (! is_null($model = $this->first($columns))) {
            return $model;
        }

        throw $this->model->exceptionWhenModelNotFound();
    }
}
