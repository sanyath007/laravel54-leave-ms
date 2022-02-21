<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\History;

class ApprovalController extends Controller
{
    public function getApprove()
    {
        return view('approvals.approve-list', [
            "leave_types" => LeaveType::all(),
        ]);
    }

    public function doApprove(Request $req)
    {
        $leave = Leave::find($req['leave_id']);
        $leave->approved_comment    = $req['comment'];
        $leave->approved_date       = date('Y-m-d');
        $leave->approved_by         = Auth::user()->person_id;
        $leave->status              = $req['approved'];

        if ($leave->save()) {
            /** Save leaves histories data */
            $count = History::where('person_id', $leave->leave_person)
                        ->where('year', $leave->year)
                        ->count();

            if ($count > 0) {
                $history = History::where('person_id', $leave->leave_person)->first();
            } else {
                $history = new History;
            }

            /** On insert new data */
            if (empty($history->person_id)) {
                $history->person_id = $leave->leave_person;
                $history->year      = $leave->year;
            }

            /** Increase coordinetd leave type */
            if ($leave->leave_type == '1') {
                $history->ill_days += (float)$leave->leave_days; // ลาป่วย
            } else if ($leave->leave_type == '2') {
                $history->per_days += (float)$leave->leave_days; // ลากิจส่วนตัว
            } else if ($leave->leave_type == '3') {
                $history->vac_days += (float)$leave->leave_days; // ลาพักผ่อน
            } else if ($leave->leave_type == '4') {
                $history->lab_days += (float)$leave->leave_days; // ลาคลอด
            } else if ($leave->leave_type == '5') {
                $history->hel_days += (float)$leave->leave_days; // ลาเพื่อดูแลบุตรและภรรยาหลังคลอด
            } else if ($leave->leave_type == '6') {
                $history->ord_days += (float)$leave->leave_days; // ลาอุปสมบท
            }

            $history->save();

            return redirect('/approvals/approve');
        }
    }

    public function getReceive()
    {
        return view('approvals.receive-list', [
            "leave_types"     => LeaveType::all(),
        ]);
    }

    public function doReceive(Request $req)
    {
        $leave = Leave::find($req['leave_id']);
        $leave->received_date       = date('Y-m-d');
        $leave->received_by         = Auth::user()->person_id;
        $leave->status              = '2';

        if ($leave->save()) {
            return redirect('/approvals/receive');
        }
    }

    public function getComment()
    {
        return view('approvals.comment-list', [
            "leave_types" => LeaveType::all(),
        ]);
    }

    public function doComment(Request $req)
    {
        $leave = Leave::find($req['leave_id']);
        $leave->commented_text  = $req['comment'];
        $leave->commented_date  = date('Y-m-d');
        $leave->commented_by    = Auth::user()->person_id;
        $leave->status          = $req['approved'];

        if ($leave->save()) {
            return redirect('/approvals/comment');
        }
    }
}
