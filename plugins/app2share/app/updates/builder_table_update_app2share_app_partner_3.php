<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppPartner3 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->integer('city_id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->dropColumn('city_id');
        });
    }
}
