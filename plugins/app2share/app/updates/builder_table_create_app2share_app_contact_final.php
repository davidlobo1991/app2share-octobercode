<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateApp2shareAppContactFinal extends Migration
{
    public function up()
    {
        Schema::create('app2share_app_contact_final', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('email', 255);
            $table->string('name', 255);
            $table->string('phone', 255)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('app2share_app_contact_final');
    }
}
