<?php

namespace App\Http\Controllers;


use App\DailyLogOffDay;
use App\Http\Requests\StoreDailyLogOffDayRequest;
use App\ProjectDiscipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DailyLogOffDaysController extends Controller
{
    public function store(StoreDailyLogOffDayRequest $request, ProjectDiscipline $projectDiscipline)
    {
        $projectDiscipline->dailyLogOffDays()->create([
            'off_date' => $request->validated()['off_date']
        ]);

        return Redirect::back()->with('success', 'Date marked as waived.');
    }

    public function destroy(Request $request, ProjectDiscipline $projectDiscipline, DailyLogOffDay $dailyLogOffDay)
    {
        abort_unless($projectDiscipline->isLeadBy($request->user()), 403);

        $dailyLogOffDay->delete();

        return Redirect::back()->with('success', 'Waived day removed.');
    }
}
