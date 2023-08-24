<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('repoert_to')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('transfer_token_by')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('price_quote')->nullable();
            $table->string('offered_for')->nullable();
            $table->string('status')->nullable();
            $table->string('followup_date')->nullable();
            $table->string('followup_time')->nullable();
            $table->string('next_followup_date')->nullable();
            $table->text('comments')->nullable();
            $table->string('website')->nullable();
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
        Schema::dropIfExists('prospects');
    }
}
