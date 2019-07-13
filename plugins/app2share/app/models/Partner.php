<?php namespace App2share\App\Models;

use Model;

/**
 * Model
 */
class Partner extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'app2share_app_partner';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];


    public $attachOne = [
        'logo' => 'System\Models\File'
    ];

    public $attachMany = [
        'images' => 'System\Models\File'
    ];

    public $belongsTo = [
        'partner_type' => 'App2share\App\Models\PartnerType'
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
