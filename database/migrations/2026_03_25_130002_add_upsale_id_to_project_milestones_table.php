<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpsaleIdToProjectMilestonesTable extends Migration
{
    public function up()
    {
        Schema::table('project_milestones', function (Blueprint $table) {
            $table->unsignedBigInteger('upsale_id')->nullable()->after('bdm_project_id');
            $table->foreign('upsale_id')->references('id')->on('upsales')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('project_milestones', function (Blueprint $table) {
            $table->dropForeign(['upsale_id']);
            $table->dropColumn('upsale_id');
        });
    }
}
