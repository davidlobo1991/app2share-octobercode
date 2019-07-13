<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppOffer extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_offer', function($table)
        {
            $table->boolean('active')->default(1);
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_offer', function($table)
        {
            $table->dropColumn('active');
        });
    }
}
