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


class ParcelController extends Controller
{   
    protected $status = [
        '1' => 'ใช้งานอยู่',
        '2' => 'ยกเลิก',
        '3' => 'ไม่ทราบ'
    ];
    
    protected $parcelType = [
        '1' => 'วัสดุสิ้นเปลื้อง',
        '2' => 'วัสดุคงทน',
        '3' => 'ครุภัณฑ์',
        '4' => 'บริการ',
        '5' => 'อาคาร/สิ่งปลูกสร้าง',
        '6' => 'ที่ดิน',
    ];

    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'parcel_no' => 'required',
            'parcel_name' => 'required',
            'unit' => 'required',
            'unit_price' => 'required',
            'deprec_type' => 'required',
            'first_y_month' => 'required',
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
    	return view('parcels.list', [
            "suppliers"     => Supplier::all(),
            "cates"         => AssetCategory::orderBy('cate_no')->get(),
            "types"         => AssetType::orderBy('type_no')->get(),
            "parcel_types"  => $this->parcelType
    	]);
    }

    public function search($assetType, $parcelType, $searchKey)
    {
        $conditions = [];
        if($assetType != 0) array_push($conditions, ['asset_type', '=', $assetType]);
        if($parcelType != 0) array_push($conditions, ['parcel_type', '=', $parcelType]);
        if($searchKey !== '0') array_push($conditions, ['parcel_name', 'like', '%'.$searchKey.'%']);

        if($conditions == '0') {
            $parcels = Parcel::with('assetType')
                        ->with('deprecType')->toSql();
                        // ->paginate(20);
        } else {
            $parcels = Parcel::where($conditions)
                        ->with('assetType')
                        ->with('deprecType')
                        ->paginate(20);
        }
        
        return [
            'parcels' => $parcels,
            "parcel_types"  => $this->parcelType
        ];
    }

    public function getAll()
    {
        return [
            'parcels' => Parcel::orderBy('parcel_no')->get()
        ];
    }

    public function getById($parcelId)
    {
        return [
            'parcel' => Parcel::find($parcelId),
        ];
    }

    public function getByType($typeId)
    {
        return [
            'parcels' => Parcel::where('asset_type', '=', $typeId)->orderBy('parcel_no')->get()
        ];
    }
    
    public function getNo($assetType)
    {
        $parcel = Parcel::where('asset_type', '=', $assetType)
                        ->orderBy('parcel_no', 'DESC')
                        ->first();

        if($parcel) {
            $parcelNo = $parcel->parcel_no;
        } else {
            $assetType = AssetType::find($assetType);
            $parcelNo = $assetType->type_no.'-0000';
        }
        
        return [
            'parcelNo' => $parcelNo
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
    	return view('parcels.add', [
            "deprecTypes"   => DeprecType::all(),
            "units"         => AssetUnit::all(),
            "types"         => AssetType::orderBy('type_no')->get(),
            "parcelTypes"   => $this->parcelType,
            "statuses"      => $this->status
    	]);
    }

    public function store(Request $req)
    {
        $parcel = new Parcel();
        // $parcel->parcel_id = $this->generateAutoId();
        $parcel->parcel_no = $req['asset_type_no']. '-' .$req['parcel_no'];
        $parcel->parcel_name = $req['parcel_name'];
        $parcel->description = $req['description'];
        $parcel->parcel_type = $req['parcel_type'];
        $parcel->asset_type = $req['asset_type'];
        $parcel->unit = $req['unit'];
        $parcel->unit_price = $req['unit_price'];
        $parcel->deprec_type = $req['deprec_type'];
        $parcel->first_y_month = $req['first_y_month'];
        $parcel->remark = $req['remark'];
        $parcel->status = '1';

        if($parcel->save()) {
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

    public function edit($parcelId)
    {
        return view('parcels.edit', [
            "parcel"         => Parcel::find($parcelId),
            "types"         => AssetType::orderBy('type_no')->get(),     
            "deprecTypes"   => DeprecType::all(),
            "units"         => AssetUnit::all(),
            "parcelTypes"   => $this->parcelType,
            "statuses"      => $this->status
        ]);
    }

    public function update(Request $req)
    {
        $parcel = Parcel::find($req['parcel_id']);
        $parcel->parcel_no = $req['asset_type_no']. '-' .$req['parcel_no'];
        $parcel->parcel_name = $req['parcel_name'];
        $parcel->description = $req['description'];
        $parcel->parcel_type = $req['parcel_type'];
        $parcel->asset_type = $req['asset_type'];
        $parcel->unit = $req['unit'];
        $parcel->unit_price = $req['unit_price'];
        $parcel->deprec_type = $req['deprec_type'];
        $parcel->first_y_month = $req['first_y_month'];
        $parcel->remark = $req['remark'];
        $parcel->status = '1';

        if($parcel->save()) {
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
        return view('parcels.discharge-list', [
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
