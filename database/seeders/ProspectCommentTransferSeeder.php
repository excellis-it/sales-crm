<?php

namespace Database\Seeders;

use App\Models\BdmFollowup;
use App\Models\BdmProspect;
use App\Models\Followup;
use App\Models\Prospect;
use Illuminate\Database\Seeder;

class ProspectCommentTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Transfer for Sales Prospects
        $prospects = Prospect::whereNotNull('comments')->where('comments', '!=', '')->get();
        foreach ($prospects as $prospect) {
            // Check if a followup with this comment already exists to avoid duplication
            $exists = Followup::where('prospect_id', $prospect->id)
                ->where('followup_description', $prospect->comments)
                ->exists();

            if (!$exists) {
                Followup::create([
                    'prospect_id' => $prospect->id,
                    'user_id' => $prospect->user_id ?? $prospect->report_to,
                    'followup_type' => 'other',
                    'followup_description' => $prospect->comments,
                    'followup_date' => $prospect->updated_at ?? now(),
                    'status' => $prospect->status,
                    'created_at' => '2024-03-25 14:48:16'
                ]);
            }
        }

        // 2. Transfer for BDM Prospects
        $bdmProspects = BdmProspect::whereNotNull('comments')->where('comments', '!=', '')->get();
        foreach ($bdmProspects as $bdmProspect) {
            $exists = BdmFollowup::where('bdm_prospect_id', $bdmProspect->id)
                ->where('remark', $bdmProspect->comments)
                ->exists();

            if (!$exists) {
                BdmFollowup::create([
                    'bdm_prospect_id' => $bdmProspect->id,
                    'user_id' => $bdmProspect->user_id ?? $bdmProspect->report_to,
                    'remark' => $bdmProspect->comments,
                    'status' => $bdmProspect->status,
                    'next_followup_date' => $bdmProspect->followup_date,
                    'meeting_date' => $bdmProspect->meeting_date,
                   'created_at' => '2024-03-25 14:48:16'
                ]);
            }
        }
    }
}
