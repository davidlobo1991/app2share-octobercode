<?php namespace RW\Utils\Classes;

use Illuminate\Database\Eloquent\Model as ModelBase;
use Illuminate\Database\Eloquent\Scope as ScopeInterface;
use Illuminate\Database\Eloquent\Builder as BuilderBase;
use Event;

class ActivatableScope implements ScopeInterface
{
    public function apply(BuilderBase $builder, ModelBase $model)
    {
        $builder->getQuery()->where(
            $model->getActiveColumn(),
            $model->getActiveValue()
        );
    }
}
