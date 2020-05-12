<?php namespace App2share\App\Models;

use Model;
use OFFLINE\Cashier\Models\User;

/**
 * Model
 */
class SubscriptionsFreePack extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_subscriptions_free_packs';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
      'user' => User::class
    ];
}
