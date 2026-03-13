<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_projects', function (Blueprint $table) {
            $table->id();
            $table->string('tender_name');
            $table->string('tender_id_ref_no');
            $table->string('department_org');
            $table->enum('category', ['Hardware', 'AMC', 'Software']);
            $table->string('category_title')->nullable();
            $table->string('tender_value_lakhs')->nullable();
            $table->string('emd')->nullable();
            $table->date('delivery_date')->nullable();
            $table->unsignedBigInteger('status');
            $table->string('l1_quoted_value')->nullable();
            $table->string('excellis_it_quoted_price')->nullable();
            $table->string('contact_authority_name')->nullable();
            $table->string('contact_authority_phone')->nullable();
            $table->string('contact_authority_email')->nullable();
            $table->unsignedBigInteger('tender_user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tender_projects');
    }
}
