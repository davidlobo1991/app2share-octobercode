<?php namespace RW\Utils\Classes\Traits;

use App;
use Log;
use Event;
use Exception;
use Yaml;
use Backend\Widgets\Form;
use RW\Utils\Models\DynamicSeo;

trait SeoExtendable
{
    public static function bootSeoExtendable()
    {
        self::extend(function ($model) {
            $model->morphOne['rwSeo'] = [
                DynamicSeo::class,
                'name' => 'seoable'
            ];
        });

        Event::listen('backend.form.extendFields', function (Form $formWidget) {

            if ($formWidget->model instanceof DynamicSeo) {
                return;
            }

            $isRelation = request('_relation_field');
            if (!is_null($isRelation) && ($isRelation != 'rwSeo')) {
                return;
            }

            // NO AÑADE LOS CAMPOS DE SEO SI EL MODELO NO CONTIENE EL TRAIT
            if (!isset(class_uses($formWidget->model)[__TRAIT__])) {
                return;
            }

            try {
                $formWidget->addTabFields([
                   'rwSeo' => [
                       'type' => 'partial',
                       'path' => '$/rw/utils/partials/_rwSeo.htm',
                       'span' => 'full',
                       'context' => 'update',
                       'tab' => 'Seo'
                    ]
                ]);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return true;
        });
    }
}
