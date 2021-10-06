<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Reparation;

class ReparationController extends Controller
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

    public function index()
    {
    	return view('reparations.list', [
            "statuses"    => $this->status
    	]);
    }

    public function search($parcelId, $status, $searchKey)
    {
        $conditions = [];
        if($parcelId != 0) array_push($conditions, ['parcel_id', '=', $parcelId]);
        if($status != 0) array_push($conditions, ['status', '=', $status]);
        if($searchKey !== '0') array_push($conditions, ['asset_name', 'like', '%'.$searchKey.'%']);

        // if($conditions == '0') {
            $reparations = Reparation::with('asset')
                        ->paginate(20);
        // } else {
        //     $reparations = Reparation::where($conditions)
        //                 ->with('asset')
        //                 ->paginate(20);
        // }

        return [
            'reparations' => $reparations,
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
