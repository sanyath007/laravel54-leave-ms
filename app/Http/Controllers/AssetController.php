<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Parcel;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\AssetUnit;
use App\Models\BudgetType;
use App\Models\DeprecType;
use App\Models\PurchasedMethod;
use App\Models\DocumentType;
use App\Models\Supplier;
use App\Models\Department;


class AssetController extends Controller
{   
    protected $status = [
        '1' => 'รอเบิก',
        '2' => 'ใช้งานอยู่',
        '3' => 'ถูกยืม',
        '4' => 'จำหน่าย',
    ];

    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'asset_no' => 'required',
            'asset_name' => 'required',
            'parcel_id' => 'required',
            'amount' => 'required',
            'unit_price' => 'required',
            'unit' => 'required',
            'purchased_method' => 'required',
            'budget_type' => 'required',
            'year' => 'required',
            'doc_type' => 'required',
            'doc_no' => 'required',
            'doc_date' => 'required',
            'date_in' => 'required',
            'date_exp' => 'required',
            'depart' => 'required',
            'supplier' => 'required',
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
    	return view('assets.list', [
            "types"     => AssetType::orderBy('type_no')->get(),
            "parcels"     => Parcel::orderBy('parcel_no')->get(),
            "statuses"    => $this->status
    	]);
    }

    public function search($parcelId, $status, $searchKey)
    {
        $conditions = [];
        if($parcelId != 0) array_push($conditions, ['parcel_id', '=', $parcelId]);
        if($status != 0) array_push($conditions, ['status', '=', $status]);
        if($searchKey !== '0') array_push($conditions, ['asset_name', 'like', '%'.$searchKey.'%']);

        if($conditions == '0') {
            $assets = Asset::with('parcel')
                        ->with('budgetType')
                        ->with('docType')
                        ->with('purchasedMethod')
                        ->with('depart')
                        ->with('supplier')
                        ->paginate(20);
        } else {
            $assets = Asset::where($conditions)
                        ->with('parcel')
                        ->with('budgetType')
                        ->with('docType')
                        ->with('purchasedMethod')
                        ->with('depart')
                        ->with('supplier')
                        ->paginate(20);
        }

        return [
            'assets' => $assets,
        ];
    }

    public function getAll()
    {
        return [
            'assets' => Asset::orderBy('date_in')->get(),
        ];
    }

    public function getById($assetId)
    {
        return [
            'asset' => Asset::find($assetId),
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
    	return view('assets.add', [
            "parcels"     => Parcel::orderBy('parcel_no')->get(),
            "units"     => AssetUnit::all(),
            "budgets"   => BudgetType::all(),
            "docs"   => DocumentType::all(),
            "methods"     => PurchasedMethod::all(),
            "suppliers" => Supplier::all(),
            "departs" => Department::all(),
            "statuses"  => $this->status
    	]);
    }

    public function store(Request $req)
    {
        $asset = new Asset();
        // $asset->asset_id = $this->generateAutoId();
        $asset->asset_no = $req['parcel_no'].$req['asset_no'];
        $asset->asset_name = $req['asset_name'];
        $asset->description = $req['description'];
        $asset->parcel_id = $req['parcel_id'];
        $asset->amount = $req['amount'];
        $asset->unit_price = $req['unit_price'];
        $asset->unit = $req['unit'];
        $asset->purchased_method = $req['purchased_method'];
        $asset->budget_type = $req['budget_type'];
        $asset->reg_no = $req['reg_no'];
        $asset->year = $req['year'];
        $asset->doc_type = $req['doc_type'];
        $asset->doc_no = $req['doc_no'];
        $asset->doc_date = $req['doc_date'];
        $asset->date_in = $req['date_in'];
        $asset->date_exp = $req['date_exp'];
        $asset->depart = $req['depart'];
        $asset->supplier = $req['supplier'];
        $asset->remark = $req['remark'];
        $asset->status = '1';

        /** Upload image */
        $asset->image = '';

        if($asset->save()) {
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

    public function edit($assetId)
    {
        return view('assets.edit', [
            "asset"         => Asset::find($assetId),
            "parcels"     => Parcel::orderBy('parcel_no')->get(),
            "units"         => AssetUnit::all(),
            "budgets"       => BudgetType::all(),
            "docs"          => DocumentType::all(),
            "methods"       => PurchasedMethod::all(),
            "suppliers"     => Supplier::all(),
            "departs"       => Department::all(),
            "statuses"      => $this->status
        ]);
    }

    public function update(Request $req)
    {
        $asset = Asset::find($req['asset_id']);
        $asset->asset_no = $req['parcel_no'].$req['asset_no'];
        $asset->asset_name = $req['asset_name'];
        $asset->description = $req['description'];
        $asset->parcel_id = $req['parcel_id'];
        $asset->amount = $req['amount'];
        $asset->unit_price = $req['unit_price'];
        $asset->unit = $req['unit'];
        $asset->purchased_method = $req['method'];
        $asset->budget_type = $req['budget_type'];
        $asset->reg_no = $req['reg_no'];
        $asset->year = $req['year'];
        $asset->doc_type = $req['doc_type'];
        $asset->doc_no = $req['doc_no'];
        $asset->doc_date = $req['doc_date'];
        $asset->date_in = $req['date_in'];
        $asset->date_exp = $req['date_exp'];
        $asset->depart = $req['depart'];
        $asset->supplier = $req['supplier'];
        $asset->remark = $req['remark'];
        $asset->status = $req['status'];

        /** Upload image */
        $asset->image = '';

        if($asset->save()) {
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

    public function delete($assetId)
    {
        $asset = Asset::find($assetId);

        if($asset->delete()) {
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
