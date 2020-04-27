<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppOfferRating2 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_offer_rating', function($table)
        {
            $table->dateTime('valid_to')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_offer_rating', function($table)
        {
            $table->dropColumn('valid_to');
        });
    }
}
