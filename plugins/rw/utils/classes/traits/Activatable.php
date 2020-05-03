<?php namespace RW\Utils\Classes\Traits;

use RW\Utils\Classes\ActivatableScope;
use Event;
use App;

trait Activatable
{
    public static function bootActivatable()
    {
        if (!App::runningInBackend()) {
            static::addGlobalScope(new ActivatableScope);
        }
    }

    /**
     * Get the name of the "active" column.
     *
     * @return string
     */
    public function getActiveColumn()
    {
        return $this->isActive ?? 'is_active';
    }

    /**
     * Default value for "active" column
     *
     * @return string
     */
    public function getActiveValue()
    {
        return $this->activeValue ?? "1";
    }

    public function scopeIsActive($query)
    {
        return $query->where($this->getActiveColumn(), $this->getActiveValue());
    }
}
