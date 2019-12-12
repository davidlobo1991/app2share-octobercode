<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateApp2shareAppOfferValorations extends Migration
{
    public function up()
    {
        Schema::create('app2share_app_offer_valorations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('comment');
            $table->smallInteger('stars')->unsigned();
            $table->integer('offer_id')->nullable()->unsigned();
            $table->integer('user_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('app2share_app_offer_valorations');
    }
}
