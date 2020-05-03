<?php namespace RW\Utils\Models;

use Model;
use Storage;
class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel',
        '@RainLab.Translate.Behaviors.TranslatableModel'
    ];
    public $translatable = [
        'structuredData'
    ];
    // A unique code
    public $settingsCode = 'rw_utils_config';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public $attachOne = [
        'robots' => ['RW\Utils\Classes\Helpers\File', 'delete' => true],
        'sitemap' => ['RW\Utils\Classes\Helpers\File', 'delete' => true],
        'broken_image' => ['RW\Utils\Classes\Helpers\File', 'delete' => true]
    ];

    public function afterSave()
    {
        $robots = $this->robots()->withDeferred(post('_session_key'))->first();
        if ($robots) {
            $this->copySeoFiles($robots, 'robots.txt');
        }

        $sitemap = $this->sitemap()->withDeferred(post('_session_key'))->first();
        if ($sitemap) {
            $this->copySeoFiles($sitemap, 'sitemap.xml');
        }

    }

    private function copySeoFiles($file, $fileName)
    {
        try {
            Storage::disk('local')->put('temp/'.$file->disk_name, $file->getContents());
            if (Storage::disk('local')->exists($fileName)) {
                Storage::delete($fileName);
            }
            Storage::copy('temp/'.$file->disk_name, $fileName);
            Storage::delete('temp/'.$file->disk_name);

        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
