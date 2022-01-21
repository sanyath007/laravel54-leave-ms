<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Person;
use App\Models\Leave;
use App\Models\History;
use App\Models\Vacation;
use App\Models\LeaveType;

class HistoryController extends Controller
{
    public function summary($person, $year)
    {
        $histories  = History::where(['person_id' => $person, 'year' => 2565])->first();

        $vacation   = Vacation::where(['person_id' => $person,'year' => 2565])->first();

        return view('histories.summary', [
            "person"        => Person::where('person_id', $person)->first(),
            "histories"     => $histories,
            "vacation"      => $vacation,
            "leave_types"   => LeaveType::all(),
        ]);
    }

    public function getHistoriesByPerson(Request $req, $person, $year)
    {
        $type = $req->input('type');

        $leaves = Leave::where('leave_person', $person)
                    ->where('year', $year)
                    ->when(!empty($type), function($q) use ($type) {
                        $q->where('leave_type', $type);
                    })
                    ->with('person', 'person.prefix', 'person.position', 'person.academic')
                    ->with('person.memberOf', 'person.memberOf.depart', 'type')
                    ->with('cancellation')
                    ->orderBy('year', 'desc')
                    ->orderBy('leave_date', 'desc')
                    ->paginate(20);

        return [
            "leaves" => $leaves,
        ];
    }
}
