<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppOffer2 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_offer', function($table)
        {
            $table->integer('partner_id');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_offer', function($table)
        {
            $table->dropColumn('partner_id');
        });
    }
}
