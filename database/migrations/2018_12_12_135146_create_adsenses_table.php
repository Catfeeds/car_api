<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adsenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('广告位标题');
            $table->string('image')->comment('图片');
            $table->tinyInteger('sort')->default(50)->comment('排序');
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
        Schema::dropIfExists('adsenses');
    }
}
