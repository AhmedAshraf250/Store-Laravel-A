<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete(); // عشان لو المتجر فيما بعد فترة لو قرر يحذف المنتج من عنده
            $table->string('product_name');
            $table->float('price');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->json('options')->nullable();

            $table->unique(['order_id', 'product_id']);
            // These columns can repeat individually, 
            // but the combination of both must be unique. If the same pair is inserted again, SQL will throw an error.
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
