<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppPartner extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->string('mobile', 255);
            $table->increments('id')->unsigned(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->dropColumn('mobile');
            $table->increments('id')->unsigned()->change();
        });
    }
}
