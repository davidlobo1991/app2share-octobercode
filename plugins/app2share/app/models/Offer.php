<?php
namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class Offer extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_offer';

    /**
     * @var array Validation rules
     */
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];
    public $translatable = ['name', 'description'];


    public $belongsTo = [
        'partner' => 'App2share\App\Models\Partner'
    ];

    public $hasMany = [
        'offerRating' => 'App2share\App\Models\OfferRating'
    ];

    public $rules = [
        'name' => 'required|max:255',
    ];
}
