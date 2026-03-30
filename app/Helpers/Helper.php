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
                                                  ->where('milestone_type', '!=', 'upfront')
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
            $gross_amount = Project::where('user_id', $userId)
                                  ->whereBetween('sale_date', [$startDate, $endDate])
                                  ->sum('project_value');

            $projectIds = Project::where('assigned_to', $userId)->pluck('id');

            // Regular milestones (milestone + upsale_milestone + upsale_upfront — anything that isn't the base 'upfront')
            $net_milestones = ProjectMilestone::whereIn('project_id', $projectIds)
                                              ->where('payment_status', 'Paid')
                                              ->where('milestone_type', '!=', 'upfront')
                                              ->whereBetween('payment_date', [$startDate, $endDate])
                                              ->sum('milestone_value');


            // Base project upfront (when opener = account manager)
            $net_upfront = Project::where('assigned_to', $userId)
                                  ->where('project_opener', $userId)
                                  ->whereBetween('sale_date', [$startDate, $endDate])
                                  ->sum('project_upfront');


            $net_amount = $net_milestones  + $net_upfront ;
        }

        return [
            'gross_amount' => $gross_amount,
            'net_amount'   => $net_amount,
        ];
    }

    /**
     * Get live meetings and onboard achievement counts for a BDM or BDE user.
     *
     * BDM — counts prospects where report_to = userId (team prospects)
     * BDE — counts prospects where user_id  = userId (own prospects)
     *
     * @param int    $userId
     * @param string $startDate  Y-m-d
     * @param string $endDate    Y-m-d
     * @return array ['meetings' => int, 'onboard' => int]
     */
    public static function getUserMeetingsAndOnboardAchievement($userId, $startDate, $endDate)
    {
        $user = User::find($userId);

        if (!$user) {
            return ['meetings' => 0, 'onboard' => 0];
        }

        if ($user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
            $query = BdmProspect::where('report_to', $userId)
                ->whereNotNull('meeting_date')
                ->whereBetween('meeting_date', [$startDate, $endDate]);
        } elseif ($user->hasRole('BUSINESS_DEVELOPMENT_EXCECUTIVE')) {
            $query = BdmProspect::where('user_id', $userId)
                ->whereNotNull('meeting_date')
                ->whereBetween('meeting_date', [$startDate, $endDate]);
        } else {
            return ['meetings' => 0, 'onboard' => 0];
        }

        return [
            'meetings' => (clone $query)->count(),
            'onboard'  => (clone $query)->where('status', 'Win')->whereBetween('sale_date', [$startDate, $endDate])->count(),
        ];
    }

    public static function getDateRangeByDuration($duration)
    {
        $startDate = null;
        $endDate = null;

        if ($duration == 'today') {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        } elseif ($duration == 'this_month') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        } elseif ($duration == 'this_year' || $duration == 'yearEarn') {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        } elseif ($duration == 'this_week' || $duration == 'WeekEarn') {
            $startDate = date('Y-m-d', strtotime('last sunday'));
            $endDate = date('Y-m-d', strtotime('next saturday'));
        }

        return [$startDate, $endDate];
    }
}

