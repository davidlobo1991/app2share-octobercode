<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppSubscriptionsFreePacks4 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->string('id_paypal_order', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->dropColumn('id_paypal_order');
        });
    }
}
