<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration107 extends Migration
{
    public function up()
{
    Schema::table('app2share_app_partner', function($table)
    {
        $table->text('description')->after('name')->nullable();
    });
}

public function down()
{
    Schema::table('app2share_app_partner', function($table)
    {
        $table->dropColumn('description');
    });
}
}