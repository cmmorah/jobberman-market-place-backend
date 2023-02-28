<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('user_id');
            $table->string('location');
            $table->text('pictures');
            $table->text('youtube_link');
            $table->string('title');
            $table->string('brand');
            $table->string('condition');
            $table->string('description');
            $table->string('price');
            $table->string('seller_phone');
            $table->string('seller_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
