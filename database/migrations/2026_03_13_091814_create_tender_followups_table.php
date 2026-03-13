<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenderFollowupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tender_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tender_project_id');
            $table->text('comment');
            $table->unsignedBigInteger('user_id');
            $table->string('type')->nullable(); // Can be 'Remark' or 'Milestone Comment'
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
        Schema::dropIfExists('tender_followups');
    }
}
