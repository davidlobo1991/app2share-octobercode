<?php namespace RW\Utils\Classes\Traits;

use Db;
use October\Rain\Exception\ApplicationException;
use RainLab\Translate\Models\Locale;

trait TranslateFixes
{

    public function beforeSave()
    {
        parent::beforeSave();

        $this->validateTranslatableFields();
    }

    private function validateTranslatableFields()
    {
        $translatableAttributes = $this->getTranslatableAttributesWithOptions();

        $locales = Locale::isEnabled()->get();
        foreach ($translatableAttributes as $key => $value) {
            if ($value['index']) {
                foreach ($locales as $locale) {
                    $indexValue = Db::table('rainlab_translate_indexes')
                        ->where('model_type', get_class($this))
                        ->where('locale', $locale->code)
                        ->where('model_id', '!=', $this->id)
                        ->where('item', $key)
                        ->where('value', (!empty($this->getTranslateAttributes($locale->code))) ? $this->getTranslateAttributes($locale->code)[$key] : '')
                        ->count();
                    if ($indexValue > 0) {
                        throw new ApplicationException('Slug already in use' . $locale->code);
                    }
                }
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        $locales = Locale::isEnabled()->get();
        foreach ($locales as $locale) {
            Db::table('rainlab_translate_indexes')
                ->where('model_type', get_class($this))
                ->where('locale', $locale->code)
                ->where('model_id', $this->id)->delete();

            Db::table('rainlab_translate_attributes')
                ->where('model_type', get_class($this))
                ->where('locale', $locale->code)
                ->where('model_id', $this->id)->delete();
        }
    }
}
