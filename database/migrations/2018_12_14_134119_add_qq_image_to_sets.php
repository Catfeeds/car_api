<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQqImageToSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->string('qq_image')->after('no_loan_phone');
            $table->string('wx_image')->after('qq_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropColumn('qq_image');
            $table->dropColumn('wx_image');
        });
    }
}
