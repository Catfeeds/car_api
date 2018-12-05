<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToProductSkus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->boolean('is_discount')->default(0)->after('is_sale')->comment('是否折扣价');
            $table->decimal('discount_price', 10, 2)->nullable()->after('is_discount')->comment('折扣价');
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
            $table->dropColumn('is_discount');
            $table->dropColumn('discount_price');
        });
    }
}
