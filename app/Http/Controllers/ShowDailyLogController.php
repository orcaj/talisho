<?php

namespace App\Http\Controllers;

use App\DailyLog;
use App\DailyLogOffDay;
use App\Enum\Permission;
use App\Organization;
use App\Project;
use App\ProjectDiscipline;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ShowDailyLogController extends Controller
{
    public function __invoke(Request $request, Organization $organization, Project $project)
    {
        $this->authorize('view', $project);

        $user = $request->user();
        $disciplines = $user->can(Permission::VIEW_ALL_LIABILITY)
            ? $project->disciplines
            : $project->disciplines->filter->isVisibleFor($user);

        $disciplines->each(function(ProjectDiscipline $discipline) {
            $discipline->load('discipline',
                'lead',
                'dailyLogs.reportedBy.organization',
                'dailyLogs.files',
                'dailyLogs.comments.files',
                'dailyLogs.comments.commentedBy.organization');

            foreach ($discipline->dateRange as $date) {
                // if team is empty, no daily logs required
                if ($discipline->team()->doesntExist()) {
                    break;
                }

                if ($discipline->dailyLogOffDays->contains(function (DailyLogOffDay $dailyLogOffDay) use ($date) {
                    return $dailyLogOffDay->off_date->isSameDay($date);
                })) {
                    $offDay = DailyLogOffDay::firstWhere('off_date', $date->toDateString());
                    $log = new DailyLog();
                    $log->setAttribute('off_day_id', $offDay->id);
                    $log->log_date = $date;
                    $log->setAttribute('status', 'OFF_DAY');
                    $discipline->dailyLogs->push($log);
                } elseif (!$discipline->dailyLogs->contains(function (DailyLog $dailyLog) use ($date) {
                    return $dailyLog->log_date->isSameDay($date);
                })) {
                    $log = new DailyLog();
                    $log->log_date = $date;
                    $log->setAttribute('status', 'MISSING');
                    $discipline->dailyLogs->push($log);
                }
            }

            $discipline->dailyLogs->sortBy('log_date')->each->append([
                'wereIssues',
                'wereDelays',
                'logDateWithDayOfWeek'
            ]);
        });

        return Inertia::render('Organization/Project/Liability/DailyLog/Show', [
            'organization' => $organization,
            'project' => $project,
            'disciplines' => $disciplines->load('discipline', 'lead')->values(),
        ]);
    }
}
