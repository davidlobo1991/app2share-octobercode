<?php namespace RW\Utils\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateRwUtilsSeo extends Migration
{
    public function up()
    {
        Schema::table('rw_utils_seo', function($table)
        {
            $table->string('robots')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('rw_utils_seo', function($table)
        {
            $table->dropColumn('robots');
        });
    }
}