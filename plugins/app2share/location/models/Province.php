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


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_location_province';

    public $fillable = ['name'];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasMany = [
      'cities' => 'App2share\Location\Models\City'
    ];
}
