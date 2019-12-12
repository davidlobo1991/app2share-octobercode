<?php namespace App2share\App\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateApp2shareAppOfferValoration2 extends Migration
{
    public function up()
    {
        Schema::table('app2share_app_offer_valoration', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('app2share_app_offer_valoration', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
