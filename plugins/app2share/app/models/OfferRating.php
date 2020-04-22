<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class OfferRating extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_offer_rating';

    public $guarded = [];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
