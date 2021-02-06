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
            $table->bigIncrements('id');
            $table->string("txn_type", 255)->nullable();
            $table->string("purpose", 255)->nullable();
            $table->decimal("amount", 20, 4)->nullable();
            $table->integer("account_id")->nullable();
            $table->string("reference")->nullable();
            $table->decimal("balance_before", 20, 4)->nullable();
            $table->decimal("balance_after", 20, 4)->nullable();
            $table->longText('metadata')->nullable()->default('text');
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
