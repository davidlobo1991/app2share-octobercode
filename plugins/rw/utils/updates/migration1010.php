<?php namespace RW\Utils\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1010 extends Migration
{
    public function up()
    {
        Schema::table('rw_utils_dynamic_seo', function($table)
        {
            $table->text('structured_data')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('rw_utils_dynamic_seo', function($table)
        {
            $table->dropColumn('structured_data');
        });
    }
}