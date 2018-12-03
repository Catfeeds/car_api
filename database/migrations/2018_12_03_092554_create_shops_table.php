<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('门店名称');
            $table->string('image')->comment('展示图片');
            $table->text('banner')->comment('轮播图');
            $table->string('address')->comment('地址');
            $table->string('phone')->comment('联系电话');
            $table->longText('content')->comment('图文介绍');
            $table->unsignedTinyInteger('sort')->default(50)->comment('排序');
            $table->boolean('is_show')->default(0)->comment('是否展示');
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
        Schema::dropIfExists('shops');
    }
}
