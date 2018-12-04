<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorToProductSkus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->string('color')->nullable()->after('id');
            $table->string('configuration')->nullable()->after('color');
            $table->string('style')->nullable()->after('configuration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->string('title');
            $table->dropColumn('color');
            $table->dropColumn('configuration');
            $table->dropColumn('style');
        });
    }
}
