<?php namespace RW\Utils\Models;

use Cms\Classes\Page;
use Cms\Classes\Theme;
use Model;

/**
 * Model
 */
class Seo extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
    /**
     * @var string The database table used by the model.
     */
    public $table = 'rw_utils_seo';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'page' => 'required|unique:rw_utils_seo',
        'title' => 'required',
        'description' => 'required',
    ];

    public $translatable = [
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'structured_data'
    ];

    public $attachOne = [
        'seoImage' => ['RW\Utils\Classes\Helpers\File', 'delete' => true]
    ];

    public function getPageOptions($keyValue = null)
    {
        $theme = Theme::getActiveTheme();
        $withSeo = self::lists('page');

        $pages = $theme->listPages()
            ->filter(function ($page, $key) use ($withSeo) {
                return ! in_array(strtolower($page->baseFileName), $withSeo);
            })
            ->pluck('title', 'baseFileName')
            ->toArray();

        if (!is_null($keyValue)) {
            $seoPage = $this->where('page', $keyValue)
                ->first();
            $pages[(string)$seoPage->page] = $seoPage->pageCms;
        }
        return $pages;
    }

    public function getPageCmsAttribute()
    {
        $theme = Theme::getActiveTheme();
        $page = Page::loadCached($theme, $this->page . '.htm');

        return $page->title;
    }
}
