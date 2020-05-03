<?php namespace RW\Utils\Models;

use Model;

/**
 * Model
 */
class DynamicSeo extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \RW\Utils\Classes\Traits\TranslatableRelation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rw_utils_dynamic_seo';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
        'description' => 'required',
    ];

    public $translatable = [
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'rwSeo[title]',
        'structured_data'
    ];

    public $attachOne = [
        'seoImage' => ['RW\Utils\Classes\Helpers\File', 'delete' => true]
    ];

    public $morphTo = [
        'seoable' => []
    ];
}
