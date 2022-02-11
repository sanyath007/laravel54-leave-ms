<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faction;
use App\Models\Depart;
use App\Models\Leave;
use App\Models\History;
use App\Models\Person;

class ReportController extends Controller
{   
    protected $status = [
        '1' => 'อยู่ระหว่างการซ่อม',
        '2' => 'ซ่อมเสร็จแล้ว',
    ];

    protected $types = [
        '1' => 'ภายใน',
        '2' => 'ภายนอก',
    ];

    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'asset_id' => 'required',
            'reparation_date' => 'required',
            'reparation_doc_no' => 'required',
            'reparation_doc_date' => 'required',
            'reparation_cause' => 'required',
            'reparation_detail' => 'required',
            'reparation_price' => 'required',
            'reparation_handler' => 'required',
            'reparation_type' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'success' => 0,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        } else {
            return [
                'success' => 1,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        }
    }

    public function summary()
    {
        return view('reports.summary', [
            "factions"   => Faction::all(),
            "departs"    => Depart::orderBy('depart_name', 'ASC')->get(),
        ]);
    }

    public function getSummaryData(Request $req)
    {
        $year   = $req->input('year');
        $depart = $req->input('depart');

        $leaves = \DB::table('leaves')
                    ->select(
                        'leave_person',
                        \DB::raw("count(case when (leave_type='1') then id end) as ill_times"),
                        \DB::raw("sum(case when (leave_type='1') then leave_days end) as ill_days"),
                        \DB::raw("count(case when (leave_type='2') then id end) as per_times"),
                        \DB::raw("sum(case when (leave_type='2') then leave_days end) as per_days"),
                        \DB::raw("count(case when (leave_type='3') then id end) as vac_times"),
                        \DB::raw("sum(case when (leave_type='3') then leave_days end) as vac_days"),
                        \DB::raw("count(case when (leave_type='4') then id end) as lab_times"),
                        \DB::raw("sum(case when (leave_type='4') then leave_days end) as lab_days"),
                        \DB::raw("count(case when (leave_type='5') then id end) as hel_times"),
                        \DB::raw("sum(case when (leave_type='5') then leave_days end) as hel_days"),
                        \DB::raw("count(case when (leave_type='6') then id end) as ord_times"),
                        \DB::raw("sum(case when (leave_type='6') then leave_days end) as ord_days")
                    )
                    ->whereIn('status', [3, 8])
                    ->where('year', $year)
                    ->groupBy('leave_person')->get();

        return [
            'leaves'    => $leaves,
            'persons'   => Person::where('person_state', '1')
                            ->join('level', 'personal.person_id', '=', 'level.person_id')
                            ->where('level.faction_id', '5')
                            ->when(empty($depart), function($q) {
                                $q->where('level.depart_id', '65');
                            })
                            ->when(!empty($depart), function($q) use ($depart) {
                                $q->where('level.depart_id', $depart);
                            })
                            ->with('prefix','position','academic')
                            ->with('memberOf', 'memberOf.depart')
                            ->paginate(10),
            'histories' => History::where('year', $year)->get()
        ];
    }

    public function remain()
    {
        return view('reports.remain', [
            "factions"   => Faction::all(),
            "departs"    => Depart::orderBy('depart_name', 'ASC')->get(),
        ]);
    }
}
