<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Person;
use App\Models\Leave;
use App\Models\History;
use App\Models\Cancellation;

class CancellationController extends Controller
{
    public function doApprove(Request $req)
    {
        try {
            $cancel = Cancellation::find($req['_id']);
            $cancel->approved_comment    = $req['comment'];
            $cancel->approved_date       = date('Y-m-d');

            if ($cancel->save()) {
                /** Update status of cancelled leave data */
                $leave = Leave::find($req['leave_id']);
                $leave->status = '9';
                $leave->save();

                /** Update cancelled leave histories data */
                $history = History::where('person_id', $leave->leave_person)->first();

                /** Decrease leave days coordineted leave type */
                if ($leave->leave_type == '1') {
                    $history->ill_days -= (float)$cancel->days; // ลาป่วย
                } else if ($leave->leave_type == '2') {
                    $history->per_days -= (float)$cancel->days; // ลากิจส่วนตัว
                } else if ($leave->leave_type == '3') {
                    $history->vac_days -= (float)$cancel->days; // ลาพักผ่อน
                } else if ($leave->leave_type == '4') {
                    $history->abr_days -= (float)$cancel->days; // ลาไปต่างประเทศ
                } else if ($leave->leave_type == '5') {
                    $history->lab_days -= (float)$cancel->days; // ลาคลอด
                } else if ($leave->leave_type == '6') {
                    $history->ord_days -= (float)$cancel->days; // ลาอุปสมบท
                }

                $history->save();

                return redirect('/leaves/approve');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function doReceive(Request $req)
    {
        try {
            $cancel = Cancellation::find($req['_id']);
            $cancel->received_date  = date('Y-m-d H:i:s');
            $cancel->received_by    = '';

            if ($cancel->save()) {
                return redirect('/leaves/receive');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
