<?php namespace RW\Utils\Classes\Traits;

use Illuminate\Support\Facades\DB;

/**
 * @see https://github.com/rainlab/translate-plugin/issues/209#issuecomment-362088300
 */
trait TranslatableRelation
{
    /**
     * This is a temporary fix until
     * https://github.com/rainlab/translate-plugin/issues/209
     * is resolved.
     */
    protected function setTranslatableFields()
    {
        if ( ! post('RLTranslate')) {
            return;
        }

        $translatableIndexes = [];
        $translatableAttributes = [];

        foreach (post('RLTranslate') as $key => $value) {
            foreach ($this->translatable as $translatableAttribute) {
                if (isset($translatableAttribute['index'])) {
                    $translatableIndexes[$translatableAttribute[0]] = '';
                    $translatableAttributes[$translatableAttribute[0]] = '';
                } else {
                    $translatableAttributes[$translatableAttribute] = '';
                }
            }

            $data = collect($value)->intersectByKeys($translatableAttributes);
            $indexes = collect($value)->intersectByKeys($translatableIndexes);

            $obj = DB::table('rainlab_translate_attributes')
                     ->where('locale', $key)
                     ->where('model_id', $this->id)
                     ->where('model_type', get_class($this));

            if ($obj->count() > 0) {
                $obj->update(['attribute_data' => $data->toJson()]);
            } else {
                DB::table('rainlab_translate_attributes')
                    ->insert([
                            'locale'         => $key,
                            'model_id'       => $this->id,
                            'model_type'     => get_class($this),
                            'attribute_data' => $data->toJson(),
                        ]
                    );
            }

            foreach ($indexes as $item => $value) {
                $obj = DB::table('rainlab_translate_indexes')
                         ->where('locale', $key)
                         ->where('model_id', $this->id)
                         ->where('model_type', get_class($this))
                         ->where('item', $item);

                if ($obj->count() > 0) {
                    $obj->update(['value' => $value]);
                } else {
                    DB::table('rainlab_translate_indexes')
                        ->insert([
                                'locale'         => $key,
                                'model_id'       => $this->id,
                                'model_type'     => get_class($this),
                                'item'           => $item,
                                'value'          => $value,
                            ]
                        );
                }
            }

        }
    }

    public function afterSave()
    {
        $this->setTranslatableFields();
    }
}
