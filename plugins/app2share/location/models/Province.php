<?php namespace App2share\Location\Models;

use Model;

/**
 * Model
 */
class Province extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];
    public $translatable = ['name', 'description'];

    public $rules = [
        'name' => 'required|max:255',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_location_province';

    public $fillable = ['name'];

    public $hasMany = [
      'cities' => 'App2share\Location\Models\City'
    ];
}
