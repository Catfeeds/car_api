<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('all_customer_phone')->comment('总客服电话');
            $table->string('email')->comment('邮箱');
            $table->string('account')->comment('打款账户');
            $table->string('loan_phone')->comment('贷款联系电话');
            $table->string('no_loan_phone')->comment('无贷款联系电话');
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
        Schema::dropIfExists('sets');
    }
}
