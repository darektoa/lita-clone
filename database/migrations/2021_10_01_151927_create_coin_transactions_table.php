<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateCoinTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('sender_id')->nullable()->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->smallInteger('type');
            $table->bigInteger('coin');
            $table->bigInteger('balance');
            $table->string('description')->nullable();
            $table->string('status', 20)->nullable();
            $table->string('referral_code', 10)->nullable();
            $table->json('invoice')->nullable();
            $table->json('detail')->nullable();
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
        Schema::dropIfExists('coin_transactions');
    }
}
