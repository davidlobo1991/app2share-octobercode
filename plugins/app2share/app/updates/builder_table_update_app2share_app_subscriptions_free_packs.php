<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppSubscriptionsFreePacks extends Migration
{
    public function up()
    {
        Schema::rename('app2share_app_suscriptions_free_packs', 'app2share_app_subscriptions_free_packs');
    }
    
    public function down()
    {
        Schema::rename('app2share_app_subscriptions_free_packs', 'app2share_app_suscriptions_free_packs');
    }
}
