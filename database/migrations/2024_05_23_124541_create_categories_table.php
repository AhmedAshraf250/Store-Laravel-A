<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // $table->unsignedBigInteger('parent_id');
            // foreignId just make unsignedBigInteger column only
            $table->foreignId('parent_id')->nullable()->constrained('categories', 'id')
                ->nullOnDelete();
            // ->restrictOnDelete()
            // ->cascadeOnDelete()
            // ->cascadeOnUpdate()
            // if there an column foreign key to other column (relations) .. must these columns be same type

            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'archived']);
            $table->timestamps();


            // $table->unsignedInteger('depth')->default(0)->after('parent_id');
            /*
                    id ,name               ,parent_id  ,depth
                    ----------------------------------------
                    1,  clothes             ,NULL       ,0
                    2,  child_clothes       ,1          ,1
                    3,  child_clothes_boys  ,2          ,2
                    4,  child_clothes_girls ,2          ,2
            */
            // $table->check('id != parent_id'); // to prevent self reference




            // $table->unsignedBigInteger('right_id');
            // $table->unsignedBigInteger('left_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
