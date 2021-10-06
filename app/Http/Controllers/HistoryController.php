<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Person;
use App\Models\Leave;

class HistoryController extends Controller
{
    public function index()
    {
        return view('deprec-types.list');
    }

    public function summary($person)
    {
        $searchKey = '0';
        if($searchKey == '0') {
            $histories = Leave::where('leave_person', $person)->get();
        } else {
            $histories = Leave::where('leave_person', $person)->get();
        }

        return view('histories.summary', [
            'person'    => Person::where('person_id', $person)->first(),
            'histories' => $histories,
        ]);
    }

    public function getAjexAll($cateId)
    {
        $types = DeprecType::where('cate_id', '=', $cateId)->get();

        return [
            'types' => $types,
        ];
    }

    public function getById($typeId)
    {
        return [
            'type' => DeprecType::find($typeId),
        ];
    }
}
