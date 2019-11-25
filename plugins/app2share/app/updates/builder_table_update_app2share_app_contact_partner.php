<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppContactPartner extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_contact_partner', function($table)
        {
            $table->date('created_at');
            $table->date('updated_at');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_contact_partner', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
