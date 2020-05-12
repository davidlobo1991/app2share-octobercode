<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppSubscriptionsFreePacks2 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->boolean('is_paid')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->dropColumn('is_paid');
        });
    }
}
