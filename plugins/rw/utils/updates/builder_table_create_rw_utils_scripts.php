<?php namespace RW\Utils\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRwUtilsScripts extends Migration
{
    public function up()
    {
        Schema::create('rw_utils_scripts', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->text('script')->nullable();
            $table->text('noscript')->nullable();
            $table->boolean('is_active')->default(1);
            $table->integer('sort_order')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rw_utils_scripts');
    }
}