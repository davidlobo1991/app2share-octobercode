<?php namespace App2share\Location\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareLocationCity4 extends Migration
{
    public function up()
    {
        Schema::table('app2share_location_city', function($table)
        {
            $table->renameColumn('province', 'province_id');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_location_city', function($table)
        {
            $table->renameColumn('province_id', 'province');
        });
    }
}
