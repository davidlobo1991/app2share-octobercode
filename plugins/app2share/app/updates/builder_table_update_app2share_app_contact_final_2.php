<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppContactFinal2 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_contact_final', function($table)
        {
            $table->renameColumn('contact_at', 'created_at');
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_contact_final', function($table)
        {
            $table->renameColumn('created_at', 'contact_at');
        });
    }
}
