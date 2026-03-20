<?php
namespace App\Helpers;

use App\Models\User;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\BdmProject;
use App\Models\BdmProspect;
use App\Models\ProjectMilestone;

class Helper
{
    /**
     * Get date range wise goals achievement directly from Project/Prospect tables.
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public static function getUserAchievementDateRange($userId, $startDate, $endDate)
    {
        $user = User::find($userId);

        $gross_amount = 0;
        $net_amount = 0;

        if (!$user || !$startDate || !$endDate) {
            return [
                'gross_amount' => $gross_amount,
                'net_amount'   => $net_amount,
            ];
        }

        // --- SALES MANAGER ---
        if ($user->hasRole('SALES_MANAGER')) {
            $gross_amount = Project::where('user_id', $userId)
                                   ->whereBetween('sale_date', [$startDate, $endDate])
                                   ->sum('project_value');
            $net_amount   = Project::where('user_id', $userId)
                                   ->whereBetween('sale_date', [$startDate, $endDate])
                                   ->sum('project_upfront');
        }

        // --- SALES EXECUTIVE ---
        else if ($user->hasRole('SALES_EXCUETIVE')) {
            $gross_amount = Prospect::where('user_id', $userId)
                                    ->where('status', 'Win')
                                    ->whereBetween('sale_date', [$startDate, $endDate])
                                    ->sum('price_quote');
            $net_amount   = Prospect::where('user_id', $userId)
                                    ->where('status', 'Win')
                                    ->whereBetween('sale_date', [$startDate, $endDate])
                                    ->sum('upfront_value');
        }

        // --- BDM / BUSINESS DEVELOPMENT MANAGER ---
        else if ($user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
            $gross_amount = BdmProject::where('user_id', $userId)
                                      ->whereBetween('sale_date', [$startDate, $endDate])
                                      ->sum('project_value');
            $net_amount   = BdmProject::where('user_id', $userId)
                                      ->whereBetween('sale_date', [$startDate, $endDate])
                                      ->sum('project_upfront');

             // From BDM projects milestone
            $bdmProjectIds = BdmProject::where('user_id', $userId)->pluck('id');
            $net_bdm_milestones = ProjectMilestone::whereIn('bdm_project_id', $bdmProjectIds)
                                                  ->where('payment_status', 'Paid')
                                                  ->whereBetween('payment_date', [$startDate, $endDate])
                                                  ->sum('milestone_value');
            $net_amount = $net_amount + $net_bdm_milestones;
        }

        // --- BDE / BUSINESS DEVELOPMENT EXECUTIVE ---
        else if ($user->hasRole('BUSINESS_DEVELOPMENT_EXCECUTIVE')) {
            $gross_amount = BdmProspect::where('user_id', $userId)
                                       ->where('status', 'Win')
                                       ->whereBetween('sale_date', [$startDate, $endDate])
                                       ->sum('price_quote');
            $net_amount   = BdmProspect::where('user_id', $userId)
                                       ->where('status', 'Win')
                                       ->whereBetween('sale_date', [$startDate, $endDate])
                                       ->sum('upfront_value');
        }

        // --- ACCOUNT MANAGER ---
        else if ($user->hasRole('ACCOUNT_MANAGER')) {
            // Gross usually does not apply the same way to Account Manager (or it's zero),
            // but we'll sum Net from Milestones and Upfronts where they are openers.
            $gross_amount = 0;

            // From regular projects milestone
            $projectIds = Project::where('assigned_to', $userId)->pluck('id');
            $net_milestones = ProjectMilestone::whereIn('project_id', $projectIds)
                                              ->where('payment_status', 'Paid')
                                              ->whereBetween('payment_date', [$startDate, $endDate])
                                              ->sum('milestone_value');

            // From BDM projects milestone
            $bdmProjectIds = BdmProject::where('assigned_to', $userId)->pluck('id');
            $net_bdm_milestones = ProjectMilestone::whereIn('bdm_project_id', $bdmProjectIds)
                                                  ->where('payment_status', 'Paid')
                                                  ->whereBetween('payment_date', [$startDate, $endDate])
                                                  ->sum('milestone_value');

            // As account manager they also get net if they were the project opener
            $net_upfront = Project::where('assigned_to', $userId)
                                  ->where('project_opener', $userId)
                                  ->whereBetween('sale_date', [$startDate, $endDate])
                                  ->sum('project_upfront');

            $net_bdm_upfront = BdmProject::where('assigned_to', $userId)
                                         ->where('project_opener', $userId)
                                         ->whereBetween('sale_date', [$startDate, $endDate])
                                         ->sum('project_upfront');

            $net_amount = $net_milestones + $net_bdm_milestones + $net_upfront + $net_bdm_upfront;
        }

        return [
            'gross_amount' => $gross_amount,
            'net_amount'   => $net_amount,
        ];
    }
}
