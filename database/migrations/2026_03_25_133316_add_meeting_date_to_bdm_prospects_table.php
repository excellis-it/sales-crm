<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMeetingDateToBdmProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bdm_prospects', function (Blueprint $table) {
            $table->date('meeting_date')->nullable()->after('followup_time')
                ->comment('Date when the meeting was held — used for meetings goal achievement count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bdm_prospects', function (Blueprint $table) {
            $table->dropColumn('meeting_date');
        });
    }
}
