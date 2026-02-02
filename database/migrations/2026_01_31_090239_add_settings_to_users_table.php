<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_orders')->default(true)->after('remember_token');
            $table->boolean('email_promotions')->default(false)->after('email_orders');
            $table->boolean('email_newsletter')->default(false)->after('email_promotions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_orders', 'email_promotions', 'email_newsletter']);
        });
    }
};
