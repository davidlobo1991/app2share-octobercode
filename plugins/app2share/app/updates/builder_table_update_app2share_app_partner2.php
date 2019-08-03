<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppPartner2 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->integer('partner_type_id');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_partner', function($table)
        {
            $table->dropColumn('partner_type_id');
        });
    }
}