<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBdmTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bdm_projects', function (Blueprint $table) {
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
            $table->string("delivery_tat")->nullable();
            $table->longText("comment")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bdm_prospects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('report_to')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('client_name')->nullable();
            $table->string('business_name')->nullable();
            $table->string('transfer_token_by')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('price_quote')->nullable();
            $table->string('upfront_value')->nullable();
            $table->boolean('is_project')->default(false);
            $table->string('offered_for')->nullable();
            $table->string('status')->nullable();
            $table->string('followup_date')->nullable();
            $table->string('followup_time')->nullable();
            $table->string('sale_date')->nullable();
            $table->text('comments')->nullable();
            $table->string('website')->nullable();
            $table->string('source')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('bdm_project_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_project_id')->nullable()->references('id')->on('bdm_projects')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });

        Schema::create('bdm_project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_project_id')->nullable()->references('id')->on('bdm_projects')->onDelete('cascade');
            $table->string('document_file');
            $table->timestamps();
        });

        Schema::table('project_milestones', function (Blueprint $table) {
            $table->foreignId('bdm_project_id')->nullable()->after('project_id')->references('id')->on('bdm_projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_milestones', function (Blueprint $table) {
            $table->dropForeign(['bdm_project_id']);
            $table->dropColumn('bdm_project_id');
        });
        Schema::dropIfExists('bdm_project_documents');
        Schema::dropIfExists('bdm_project_types');
        Schema::dropIfExists('bdm_prospects');
        Schema::dropIfExists('bdm_projects');
    }
}
