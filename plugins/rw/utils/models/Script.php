<?php namespace RW\Utils\Models;

use Model;

/**
 * Model
 */
class Script extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \RW\Utils\Classes\Traits\Activatable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rw_utils_scripts';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
