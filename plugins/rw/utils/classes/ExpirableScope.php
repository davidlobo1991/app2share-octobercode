<?php namespace RW\Utils\Classes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as ModelBase;
use Illuminate\Database\Eloquent\Scope as ScopeInterface;
use Illuminate\Database\Eloquent\Builder as BuilderBase;
use Event;

class ExpirableScope implements ScopeInterface
{

    protected $extensions = ['IsExpired'];

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(BuilderBase $builder, ModelBase $model)
    {
        $builder->getQuery()->where(
            $model->getExpirableColumn(), '>=',
            Carbon::now()
        );
    }

    /**
     * Add the Expired scope to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    protected function addIsExpired(BuilderBase $builder)
    {
        $builder->macro('isExpired', function (BuilderBase $builder) {
            $model = $builder->getModel();
            $builder->withoutGlobalScope($this)->where(
                $model->getExpirableColumn(), '<=',
                Carbon::now()
            );

            return $builder;
        });

    }

    public function extend(Builderbase $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);

        }
    }
}
