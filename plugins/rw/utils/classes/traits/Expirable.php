<?php namespace RW\Utils\Classes\Traits;

use App;
use RW\Utils\Classes\ExpirableScope;

trait Expirable
{

    /**
     * Expirable constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->dates[] = 'expires_at';
    }

    public static function bootExpirable()
    {
        if (!App::runningInBackend()) {
            static::addGlobalScope(new ExpirableScope());
        }
    }


    public function getExpirableColumn()
    {
        return $this->expiresColumn ?? 'expires_at';
    }

    public function scopeExpired($query)
    {
        
    }


}
