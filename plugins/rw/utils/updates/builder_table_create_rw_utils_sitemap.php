<?php namespace RW\Utils\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateRwUtilsSitemap extends Migration
{
    public function up()
    {
        Schema::create('rw_utils_sitemap', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('theme');
            $table->string('page')->nullable();
            $table->string('type')->nullable();
            $table->string('changefreq')->nullable();
            $table->decimal('priority', 1, 1)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rw_utils_sitemap');
    }
}