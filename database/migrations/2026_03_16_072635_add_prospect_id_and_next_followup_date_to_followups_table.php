<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProspectIdAndNextFollowupDateToFollowupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('followups', function (Blueprint $table) {
            $table->unsignedBigInteger('prospect_id')->nullable()->after('project_id');
            $table->date('next_followup_date')->nullable()->after('followup_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('followups', function (Blueprint $table) {
            $table->dropColumn(['prospect_id', 'next_followup_date']);
        });
    }
}
