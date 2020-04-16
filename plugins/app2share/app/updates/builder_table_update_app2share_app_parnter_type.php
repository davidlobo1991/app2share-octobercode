<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppParnterType extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_parnter_type', function($table)
        {
            $table->string('slug', 255);
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_parnter_type', function($table)
        {
            $table->dropColumn('slug');
        });
    }
}
