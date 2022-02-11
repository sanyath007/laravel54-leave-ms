<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faction;
use App\Models\Depart;
use App\Models\Leave;
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
                        \DB::raw("count(case when (leave_type='1') then leaves.id end) as ill_times"),
                        \DB::raw("sum(case when (leave_type='1') then histories.ill_days end) as ill_days"),
                        \DB::raw("count(case when (leave_type='2') then leaves.id end) as per_times"),
                        \DB::raw("sum(case when (leave_type='2') then histories.per_days end) as per_days"),
                        \DB::raw("count(case when (leave_type='3') then leaves.id end) as vac_times"),
                        \DB::raw("sum(case when (leave_type='3') then histories.vac_days end) as vac_days"),
                        \DB::raw("count(case when (leave_type='4') then leaves.id end) as lab_times"),
                        \DB::raw("sum(case when (leave_type='4') then histories.lab_days end) as lab_days"),
                        \DB::raw("count(case when (leave_type='5') then leaves.id end) as hel_times"),
                        \DB::raw("sum(case when (leave_type='5') then histories.hel_days end) as hel_days"),
                        \DB::raw("count(case when (leave_type='6') then leaves.id end) as ord_times"),
                        \DB::raw("sum(case when (leave_type='6') then histories.ord_days end) as ord_days")
                    )
                    ->leftJoin('histories', function($join) use ($year) {
                        $join->on('histories.person_id', '=', 'leaves.leave_person');
                        $join->where('histories.year', '=', $year);
                    })
                    ->whereIn('leaves.status', [3, 8])
                    ->where('leaves.year', $year)
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
                            ->paginate(10)
        ];
    }

    public function remain()
    {
        return view('reports.remain', [
            "factions"   => Faction::all(),
            "departs"    => Depart::orderBy('depart_name', 'ASC')->get(),
        ]);
    }

    public function getRemainData(Request $req)
    {
        $year   = $req->input('year');
        $depart = $req->input('depart');

        $conditions = [];
        if(!empty($year)) array_push($conditions, ['year', '=', $year]);

        if(count($conditions) == 0) {
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
                        ->groupBy('leave_person')->get();
        } else {
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
                        ->where($conditions)
                        ->groupBy('leave_person')
                        ->get();
        }

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
                            ->paginate(10)
        ];
    }

    public function getAll()
    {
        return [
            'reparations' => Reparation::orderBy('reparation')->get(),
        ];
    }

    public function getById($reparationId)
    {
        return [
            'reparation' => Reparation::find($reparationId),
        ];
    }

    private function generateAutoId()
    {
        $debt = \DB::table('nrhosp_acc_debt')
                    ->select('debt_id')
                    ->orderBy('debt_id', 'DESC')
                    ->first();

        $startId = 'DB'.substr((date('Y') + 543), 2);
        $tmpLastId =  ((int)(substr($debt->debt_id, 4))) + 1;
        $lastId = $startId.sprintf("%'.07d", $tmpLastId);

        return $lastId;
    }

    public function add()
    {
        return view('reparations.add', [
            "assets"        => Asset::all(),
            "types"         => $this->types,
            "statuses"      => $this->status
        ]);
    }

    public function store(Request $req)
    {
        $reparation = new Reparation();
        // $reparation->asset_id = $this->generateAutoId();
        $reparation->asset_id = $req['asset_id'];
        $reparation->reparation_doc_no = $req['reparation_doc_no'];
        $reparation->reparation_doc_date = $req['reparation_doc_date'];
        $reparation->reparation_date = $req['reparation_date'];
        $reparation->reparation_cause = $req['reparation_cause'];
        $reparation->reparation_price = $req['reparation_price'];
        $reparation->reparation_detail = $req['reparation_detail'];
        $reparation->reparation_handler = $req['reparation_handler'];
        $reparation->reparation_type = $req['reparation_type'];
        $reparation->reparation_status = $req['reparation_status'];

        if($reparation->save()) {
            return [
                "status" => "success",
                "message" => "Insert success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Insert failed.",
            ];
        }
    }

    public function edit($reparationId)
    {
        return view('assets.edit', [
            "asset"         => Reparation::find($reparationId),
            "asset"         => Asset::all(),
            "types"         => $this->types,
            "statuses"      => $this->status
        ]);
    }

    public function update(Request $req)
    {
        $reparation = Reparation::find($req['reparation_id']);
        $reparation->asset_id = $req['asset_id'];
        $reparation->reparation_doc_no = $req['reparation_doc_no'];
        $reparation->reparation_doc_date = $req['reparation_doc_date'];
        $reparation->reparation_date = $req['reparation_date'];
        $reparation->reparation_cause = $req['reparation_cause'];
        $reparation->reparation_price = $req['reparation_price'];
        $reparation->reparation_detail = $req['reparation_detail'];
        $reparation->reparation_handler = $req['reparation_handler'];
        $reparation->reparation_type = $req['reparation_type'];
        $reparation->reparation_status = $req['reparation_status'];

        if($reparation->save()) {
            return [
                "status" => "success",
                "message" => "Insert success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Insert failed.",
            ];
        }

    }

    public function delete($reparationId)
    {
        $reparation = Reparation::find($reparationId);

        if($reparation->delete()) {
            return [
                "status" => "success",
                "message" => "Delete success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Delete failed.",
            ];
        }   
    }

    public function discharge()
    {
        return view('assets.discharge-list', [
            "suppliers" => Supplier::all(),
            "cates"     => AssetCategory::all(),
            "types"     => AssetType::all(),
            "statuses"    => $this->status
        ]);
    }

    public function doDischarge(Request $req)
    {
        if(Asset::where('asset_id', '=', $req['asset_id'])
                ->update(['status' => '4']) <> 0) {
            return [
                'status' => 'success',
                'message' => 'Updated id ' .$req['asset_id']. 'is successed.',
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Updated id ' .$req['asset_id']. 'is failed.',
            ];
        }
    }
}
