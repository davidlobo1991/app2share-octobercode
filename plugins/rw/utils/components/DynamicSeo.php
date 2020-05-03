<?php namespace RW\Utils\Components;

use Cms\Classes\ComponentBase;
use RainLab\Translate\Classes\Translator;
use RW\Utils\Models\DynamicSeo as DynamicSeoModel;
use Log;
use Event;
use Exception;

class DynamicSeo extends ComponentBase
{
    const DEFAULT_URL_PARAM = 'slug';
    const DEFAULT_MODEL_FIELD = 'slug';

    public $canonicalUrl;
    public $dynamicSeo;

    public function componentDetails()
    {
        return [
            'name'        => 'DynamicSeo Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'model' => [
                'title' => 'models',
                'description' => 'nombre del modelo a buscar',
                'type' => 'dropdown'
            ],
            'urlParam' => [
                'title' => 'urlParam',
                'description' => 'Parametro de la url con el que buscar el modelo',
                'type' => 'string',
                'default' => self::DEFAULT_URL_PARAM
            ],
            'modelField' => [
                'title' => 'modelField',
                'description' => 'Nombre del campo de la base de datos para buscar el modelo',
                'type' => 'string',
                'default' => self::DEFAULT_MODEL_FIELD
            ]
        ];
    }

    public function onRun()
    {
        $modelName = $this->property('model');
        $slugName = $this->property('urlParam') ?? self::DEFAULT_URL_PARAM;
        $modelField = $this->property('modelField') ?? self::DEFAULT_MODEL_FIELD;

        if ($modelName) {
            try {
                $slug = $this->param($slugName);
                $seo = $this->getModel($modelName, $modelField, $slug);
                $this->dynamicSeo = $this->page['dynamicSeo'] = $seo;
                Event::fire('rw.dynamicSeo', $seo);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
                return;
            }
        }
    }

    private function getModel($modelName, $field, $slug)
    {
        try {
            $model = new $modelName;
            $query = $model->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
                ? $model->transWhere($field, $slug)
                : $model->where($field, $slug);
            $result = $query->firstOrFail();

            return $result->rwSeo;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            throw $exception;
        }
    }

    public function getModelOptions()
    {
        $models = DynamicSeoModel::groupby('seoable_type')
                    ->lists('seoable_type', 'seoable_type');
        return $models;
    }
}
