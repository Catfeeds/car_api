<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('商品名称');
            $table->unsignedInteger('classify_id')->comment('分类');
            $table->foreign('classify_id')->references('id')->on('classifies')->onDelete('cascade');
            $table->string('image')->comment('封面');
            $table->text('banner')->comment('轮播图片');
            $table->longText('description')->comment('图文介绍');
            $table->string('keywords')->comment('关键词');
            $table->unsignedTinyInteger('sort')->default(50)->comment('排序');
            $table->boolean('is_sale')->default(0)->comment('是否展示');
            $table->boolean('is_hot')->default(0)->comment('是否为热门商品');
            $table->timestamps();
            $table->engine = 'InnoDB';
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
