<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Person;
use App\Models\Leave;
use App\Models\History;
use App\Models\Vacation;

class HistoryController extends Controller
{
    public function summary($person, $year)
    {
        $searchKey = '0';
        if($searchKey == '0') {
            $leaves = Leave::where('leave_person', $person)->get();
        } else {
            $leaves = Leave::where('leave_person', $person)->get();
        }

        $histories  = History::where(['person_id' => $person, 'year' => 2565])->first();

        $vacation   = Vacation::where(['person_id' => $person,'year' => 2565])->first();

        return view('histories.summary', [
            'person'    => Person::where('person_id', $person)->first(),
            'leaves'    => $leaves,
            'histories' => $histories,
            'vacation' => $vacation
        ]);
    }
}
