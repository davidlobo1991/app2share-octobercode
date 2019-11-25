<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class ContactPartner extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_contact_partner';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $fillable = [
        'name', 'email', 'phone', 'sector', 'company'
    ];
}
