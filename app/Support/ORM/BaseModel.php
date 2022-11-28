<?php

namespace App\Support\ORM;

use Exception;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Scope as GlobalScope;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseModel extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                        ->logExcept(['content', 'text', self::CREATED_AT, self::UPDATED_AT])
                        ->logAll();
    }

    /**
     * Exception that should be thrown whenever the model is not found.
     *
     * @param int|null $id
     *
     * @return \Exception
     */
    public function exceptionWhenModelNotFound(?int $id = null): Exception
    {
        return (new ModelNotFoundException)->setModel(
            get_class($this), $id
        );
    }

    /**
     * Apply a Query Scopes to the object.
     *
     * @param \Illuminate\Database\Eloquent\Scope $criterion
     *
     * @return void
     */
    public function addCriteria(GlobalScope $criterion): void
    {
        static::addGlobalScope($criterion);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array<\Illuminate\Database\Eloquent\Model> $models
     *
     * @return \App\Support\ORM\BaseCollection<\Illuminate\Database\Eloquent\Model>
     */
    public function newCollection(array $models = []): BaseCollection
    {
        return new BaseCollection($models);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query): BaseEloquentBuilder
    {
        return new BaseEloquentBuilder($query);
    }

    /**
     * Begin querying the model.
     *
     * @return \App\Support\ORM\BaseEloquentBuilder
     */
    public static function query(): BaseEloquentBuilder
    {
        return parent::query();
    }
}
