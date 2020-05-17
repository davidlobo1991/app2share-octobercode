<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppSubscriptionsFreePacks5 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->double('amount_paid', 10, 0)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->dropColumn('amount_paid');
        });
    }
}
