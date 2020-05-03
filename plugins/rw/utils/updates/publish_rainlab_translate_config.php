<?php namespace RW\Utils\Updates;

use File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use October\Rain\Database\Updates\Migration;
use October\Rain\Exception\ApplicationException;

class PublishRainlabTranslateConfig extends Migration
{
    /**
     * @throws ApplicationException
     */
    public function up()
    {
        $path = config_path('rainlab/translate/config.php');
        $fileDirectory = dirname($path);
        if (!File::isDirectory($fileDirectory)) {
            if (!File::makeDirectory($fileDirectory, 0777, true, true)) {
                throw new ApplicationException(Lang::get('rw.utils::lang.errors.create_dir', [
                    'name' => $fileDirectory
                ]));
            }
        }

        if (@File::put($path, File::get(plugins_path('rw/utils/classes/stubs/config/rainlab/translate/config.php'))) === false) {
            throw new ApplicationException(Lang::get('rw.utils::lang.errors.create_file', [
                'file' => basename($path)
            ]));
        }

        @File::chmod($path);
        $this->filesGenerated[] = $path;
    }

    /**
     * @throws ApplicationException
     */
    public function down()
    {
        if (!Storage::createLocalDriver(['root' => config_path()])->deleteDirectory('rainlab')) {
            throw new ApplicationException(Lang::get('rw.utils::lang.errors.delete_directory'));
        }
    }
}