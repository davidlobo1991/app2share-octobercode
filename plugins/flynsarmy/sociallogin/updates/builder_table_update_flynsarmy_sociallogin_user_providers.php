<?php namespace Flynsarmy\SocialLogin\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateFlynsarmySocialloginUserProviders extends Migration
{
    public function up()
    {
        Schema::table('flynsarmy_sociallogin_user_providers', function($table)
        {
            $table->string('provider_id', 191)->default(null)->change();
            $table->text('provider_token')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('flynsarmy_sociallogin_user_providers', function($table)
        {
            $table->string('provider_id', 191)->default(null)->change();
            $table->string('provider_token', 191)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
