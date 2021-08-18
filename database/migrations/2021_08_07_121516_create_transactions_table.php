<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->enum('status', ['pending', 'success', 'cancel']);
            $table->enum('gateway', ['mellat', 'parsian', 'saman', 'pasargad', 'sadad', 'irankish', 'zarinpal', 'idpay', 'payping', 'payir', 'nextpay', 'zibal'])->default('mellat');
            $table->string('ref_id')->nullable();
            $table->integer('status_code')->nullable();
            $table->string('card')->nullable();
            $table->string('ip')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('payed_at')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
