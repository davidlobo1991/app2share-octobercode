<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class PartnerType extends Model
{
    use \October\Rain\Database\Traits\Validation;
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];
    public $translatable = ['name'];
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_parnter_type';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|max:255',
    ];


    public $fillable = [
        'name'
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
