<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
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
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Position;


class LeaveController extends Controller
{   
    protected $status = [
        '1' => 'รอเบิก',
        '2' => 'ใช้งานอยู่',
        '3' => 'ถูกยืม',
        '4' => 'จำหน่าย',
    ];

    protected $periods = [
        '1'  => 'เต็มวัน',
        '2'  => 'ช่วงเช้า (08.00-12.00น.)',
        '3'  => 'ช่วงบ่าย (13.00-16.00น.)',
    ];

    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'leave_place'   => 'required',
            'leave_type'   => 'required',
            'leave_to'      => 'required',
            'leave_reason'  => 'required',
            'start_date'    => 'required',
            'start_period'  => 'required',
            'end_date'      => 'required',
            'end_period'    => 'required',
            'leave_contact' => 'required',
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
        return view('leaves.list', [
            "leave_types"   => LeaveType::all(),
            "statuses"      => $this->status
        ]);
    }

    public function search($year, $month, $status)
    {
        $conditions = [];
        // if($parcelId != 0) array_push($conditions, ['parcel_id', '=', $parcelId]);
        // if($status != 0) array_push($conditions, ['status', '=', $status]);
        // if($searchKey !== '0') array_push($conditions, ['asset_name', 'like', '%'.$searchKey.'%']);

        if($conditions == '0') {
            $leaves = Leave::with('person')
                        ->with('leaveType')
                        ->orderBy('year', 'desc')
                        ->orderBy('leave_date', 'desc')
                        ->paginate(20);
        } else {
            $leaves = Leave::where($conditions)
                        ->with('person')
                        ->with('leaveType')
                        ->orderBy('year', 'desc')
                        ->orderBy('leave_date', 'desc')
                        ->paginate(20);
        }

        return [
            'leaves' => $leaves,
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
        return view('leaves.add', [
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "statuses"      => $this->status,
            "periods"       => $this->periods,
        ]);
    }

    public function store(Request $req)
    {
        $leave = new Leave();
        $leave->leave_date      = convThDateToDbDate($req['leave_date']);
        $leave->leave_place     = $req['leave_place'];
        $leave->leave_topic     = $req['leave_topic'];
        $leave->leave_person    = $req['leave_person'];
        $leave->leave_type      = $req['leave_type'];
        $leave->leave_reason    = $req['leave_reason'];
        $leave->leave_contact   = $req['leave_contact'];
        $leave->leave_delegate  = $req['leave_delegate'];
        $leave->start_date      = convThDateToDbDate($req['start_date']);
        $leave->start_period    = '1';
        $leave->end_date        = convThDateToDbDate($req['end_date']);
        $leave->end_period      = $req['end_period'];
        $leave->leave_days      = $req['leave_days'];
        $leave->year            = calcBudgetYear($req['start_date']);
        $leave->status          = '1';

        /** Upload attach file */
        $attachment = uploadFile($req->file('attachment'), 'uploads/');
        if (!empty($attachment)) {
            $leave->attachment = $attachment;
        }

        if($leave->save()) {
            return redirect('/leaves/list');
        }
    }

    public function edit($id)
    {
        return view('assets.edit', [
            "leave"         => Leave::find($id),
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "statuses"      => $this->status,
            "periods"       => $this->periods,
        ]);
    }

    public function update(Request $req)
    {
        $leave = Leave::find($req['id']);
        $leave->leave_date      = convThDateToDbDate($req['leave_date']);
        $leave->leave_place     = $req['leave_place'];
        $leave->leave_topic     = $req['leave_topic'];
        $leave->leave_person    = $req['leave_person'];
        $leave->leave_type      = $req['leave_type'];
        $leave->leave_reason    = $req['leave_reason'];
        $leave->leave_contact   = $req['leave_contact'];
        $leave->leave_delegate  = $req['leave_delegate'];
        $leave->start_date      = convThDateToDbDate($req['start_date']);
        $leave->start_period    = '1';
        $leave->end_date        = convThDateToDbDate($req['end_date']);
        $leave->end_period      = $req['end_period'];
        $leave->leave_days      = $req['leave_days'];
        $leave->year            = calcBudgetYear($req['start_date']);
        $leave->status          = '1';

        /** Upload image */
        $leave->image = '';

        if($leave->save()) {
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
