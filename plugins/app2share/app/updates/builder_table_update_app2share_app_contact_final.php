<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppContactFinal extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_contact_final', function($table)
        {
            $table->dateTime('contact_at');
            $table->timestamp('updated_at');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_contact_final', function($table)
        {
            $table->dropColumn('contact_at');
            $table->dropColumn('updated_at');
        });
    }
}
