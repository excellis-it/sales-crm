<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBdmFollowupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bdm_followups', function (Blueprint $table) {
            $table->id();
            $table->integer('bdm_prospect_id')->nullable();
            $table->integer('bdm_project_id')->nullable();
            $table->text('remark')->nullable();
            $table->date('next_followup_date')->nullable();
            $table->integer('user_id')->nullable(); // created by
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
        Schema::dropIfExists('bdm_followups');
    }
}
