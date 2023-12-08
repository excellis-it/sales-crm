<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('customer_id')->nullable();
            $table->string('client_name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_address')->nullable();
            $table->string('project_name')->nullable();
            $table->text('project_description')->nullable();
            $table->string('project_value')->nullable();
            $table->string('project_upfront')->nullable();
            $table->string('currency')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('project_opener')->nullable();
            $table->string('project_closer')->nullable();
            $table->string('website')->nullable();
            $table->string('sale_date')->nullable();
            $table->string('assigned_date')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
