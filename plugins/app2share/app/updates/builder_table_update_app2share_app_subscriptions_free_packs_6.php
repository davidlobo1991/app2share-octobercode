<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppSubscriptionsFreePacks6 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->decimal('amount_paid', 10, 0)->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_subscriptions_free_packs', function($table)
        {
            $table->double('amount_paid', 10, 0)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
