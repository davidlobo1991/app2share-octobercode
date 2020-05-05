<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class Partner extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];
    public $translatable = ['name', 'description'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_partner';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|max:255',
    ];


    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $belongsTo = [
        'partner_type' => 'App2share\App\Models\PartnerType',
        'city' => 'App2share\Location\Models\City'
    ];


    public $hasMany = [
        'offer' => 'App2share\App\Models\Offer'
    ];

    public function getThumbnailAttribute()
    {
        $project = $this->find($this->id);
        $image = null !== $project->logo ? '<img src="'.$project->logo->getThumb(100, 100, 'crop').'" />' : null;
        return $image;
    }

}
