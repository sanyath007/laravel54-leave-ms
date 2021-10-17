<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Person;
use App\Models\Leave;

class DashboardController extends Controller
{
    public function index()
    {
        return view('suppliers.list');
    }

    public function getHeadData($date)
    {
        $heads      = Person::join('level', 'level.person_id', '=', 'personal.person_id')
                        ->with('leaves')
                        ->whereNotIn('person_state', [6,7,8,9,99])
                        ->where('level.faction_id', '5')
                        ->whereIn('level.duty_id', [1,2,3])
                        ->pluck('personal.person_id');

        $persons    = Person::join('level', 'level.person_id', '=', 'personal.person_id')
                        ->with('leaves')
                        ->whereNotIn('person_state', [6,7,8,9,99])
                        ->where('level.faction_id', '5')
                        ->whereIn('level.duty_id', [1,2,3])
                        ->get();

        $leaves     = Leave::whereIn('leave_person', $heads)
                        ->with('type')
                        ->where('start_date', $date)
                        ->paginate(10);

        return [
            'leaves'   => $leaves,
            'persons'   => $persons,
        ];
    }
}
