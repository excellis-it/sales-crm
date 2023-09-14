<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDetailsToProjectMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_milestones', function (Blueprint $table) {
            //
            $table->enum('payment_status', ['Paid', 'Due'])->after('milestone_value')->nullable();
            $table->string('payment_date')->after('payment_status')->nullable();
            $table->longText('milestone_comment')->after('payment_date')->nullable();
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
            //
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_date');
            $table->dropColumn('milestone_comment');
        });
    }
}
