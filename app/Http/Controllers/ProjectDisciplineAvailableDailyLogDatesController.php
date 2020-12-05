<?php

namespace App\Http\Controllers;

use App\ProjectDiscipline;
use Illuminate\Http\Request;

class ProjectDisciplineAvailableDailyLogDatesController extends Controller
{
    public function __invoke(Request $request, ProjectDiscipline $projectDiscipline)
    {
        return response()->json(
            $projectDiscipline->daysWithoutDailyLog()->push(today())->map->toDateString()->values()
        );
    }
}
