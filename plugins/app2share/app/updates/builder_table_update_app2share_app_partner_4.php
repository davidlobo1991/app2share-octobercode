<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppPartner4 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->renameColumn('city_id', 'city');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->renameColumn('city', 'city_id');
        });
    }
}
