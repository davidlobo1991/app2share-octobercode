<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class OfferValoration extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_offer_valoration';

    public $fillable = [
        'stars', 'comment'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
