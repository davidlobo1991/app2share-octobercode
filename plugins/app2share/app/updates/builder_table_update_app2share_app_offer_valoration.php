<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppOfferValoration extends Migration
{
    public function up()
    {
        Schema::rename('app2share_app_offer_valorations', 'app2share_app_offer_valoration');
    }
    
    public function down()
    {
        Schema::rename('app2share_app_offer_valoration', 'app2share_app_offer_valorations');
    }
}
