<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppOfferRating extends Migration
{
    public function up()
    {
        Schema::rename('app2share_app_offer_valoration', 'app2share_app_offer_rating');
    }
    
    public function down()
    {
        Schema::rename('app2share_app_offer_rating', 'app2share_app_offer_valoration');
    }
}
