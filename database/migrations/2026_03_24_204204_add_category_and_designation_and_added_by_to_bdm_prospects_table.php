<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryAndDesignationAndAddedByToBdmProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bdm_prospects', function (Blueprint $table) {
            $table->string('category')->nullable()->after('user_id');
            $table->string('designation')->nullable()->after('category');
            $table->string('added_by')->nullable()->after('designation');
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
            $table->dropColumn(['category', 'designation', 'added_by']);
        });
    }
}
