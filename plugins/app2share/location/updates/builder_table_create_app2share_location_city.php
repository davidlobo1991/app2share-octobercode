<?php namespace App2share\Location\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateApp2shareLocationCity extends Migration
{
    public function up()
    {
        Schema::create('app2share_location_city', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('app2share_location_city');
    }
}
