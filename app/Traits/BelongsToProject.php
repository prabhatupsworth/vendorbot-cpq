<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToProject
{
    protected static function bootBelongsToProject()
    {
        static::creating(function ($model) {

            if (empty($model->project_id)) {

                $model->project_id = current_project_id();
            }
        });

        static::addGlobalScope('project', function (Builder $builder) {

            if (Auth::check()) {
                $user = Auth::user();
                $builder->where(
                    $builder->getModel()->getTable() . '.project_id',
                    current_project_id()
                );
            }
        });
    }
}
