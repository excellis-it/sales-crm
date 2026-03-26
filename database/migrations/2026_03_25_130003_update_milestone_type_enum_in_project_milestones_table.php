<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateMilestoneTypeEnumInProjectMilestonesTable extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `project_milestones` MODIFY `milestone_type` ENUM('upfront','milestone','upsale_upfront','upsale_milestone') NOT NULL DEFAULT 'milestone'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `project_milestones` MODIFY `milestone_type` ENUM('upfront','milestone') NOT NULL DEFAULT 'milestone'");
    }
}
