<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class PartnerType extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_parnter_type';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $fillable = [

    ];

    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

    public $hasMany = [
        'partner' => 'App2share\App\Models\Partner'
    ];

    public function getThumbnailAttribute()
    {
        $project = $this->find($this->id);
        $image = null !== $project->logo ? '<img src="'.$project->logo->getThumb(100, 100, 'crop').'" />' : null;
        return $image;
    }

}
