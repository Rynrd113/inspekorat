<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EagerLoadingScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Hanya apply jika model memiliki method getDefaultEagerLoad
        if (method_exists($model, 'getDefaultEagerLoad')) {
            $defaultEagerLoad = $model->getDefaultEagerLoad();
            
            if (!empty($defaultEagerLoad)) {
                $builder->with($defaultEagerLoad);
            }
        }
    }
}
