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
        Schema::create('role_user', function (Blueprint $table) {
            // $table ->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // $table ->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // 'autherizable_id' is the id of the user 
            // 'autherizable_type' is the type of the user (user , admin, etc)
            $table->morphs('authorizable');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');

            $table->primary(['authorizable_id', 'authorizable_type', 'role_id']);
            // primary key to prevent duplicate entries [together] [autherizable_id | autherizable_type | role_id] 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
};
