<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateApp2shareAppContactPartner extends Migration
{
    public function up()
    {
        Schema::create('app2share_app_contact_partner', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 255)->nullable();
            $table->string('sector', 255)->nullable();
            $table->string('company', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('app2share_app_contact_partner');
    }
}
