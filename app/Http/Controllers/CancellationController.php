<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
use App\Models\Person;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\History;
use App\Models\Cancellation;

class CancellationController extends Controller
{
    protected $periods = [
        '1'  => 'เต็มวัน',
        '2'  => 'ช่วงเช้า (08.00-12.00น.)',
        '3'  => 'ช่วงบ่าย (13.00-16.00น.)',
    ];

    public function formValidate(Request $request)
    {
        $rules = [
            'reason'        => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'end_period'    => 'required',
            'end_period'    => 'required',
            'days'          => 'required',
            'working_days'  => 'required',
        ];

        $messages = [
            'reason.required'       => 'กรุณาระบุเหตุผลการยกเลิก',
            'start_date.required'    => 'กรุณาเลือกจากวันที่',
            'start_date.not_in'      => 'คุณมีการลาในวันที่ระบุแล้ว',
            'end_date.required'      => 'กรุณาเลือกถึงวันที่',
            'end_date.not_in'        => 'คุณมีการลาในวันที่ระบุแล้ว',
            'end_period.required'    => 'กรุณาเลือกช่วงเวลา',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();

            return [
                'success' => 0,
                'errors' => $messageBag->toArray(),
            ];
        } else {
            return [
                'success' => 1,
                'errors' => $validator->getMessageBag()->toArray(),
            ];
        }
    }

    public function getCancel()
    {
        return view('cancellations.list', [
            "leave_types"   => LeaveType::all(),
            "periods"       => $this->periods,
        ]);
    }

    public function store(Request $req)
    {
        $cancel = new Cancellation;
        $cancel->leave_id       = $req['leave_id'];
        $cancel->cancel_date    = date('Y-m-d');
        $cancel->reason         = $req['reason'];
        $cancel->start_date     = convThDateToDbDate($req['start_date']);
        $cancel->start_period   = '1';
        $cancel->end_date       = convThDateToDbDate($req['end_date']);
        $cancel->end_period     = $req['end_period'];
        $cancel->days           = $req['days'];
        $cancel->working_days   = $req['working_days'];

        if ($cancel->save()) {
            /** Update status of leave data */
            $leave = Leave::find($req['leave_id']);
            $leave->status  = '5';
            $leave->save();

            return redirect('/cancellations/cancel');
        }
    }

    public function update(Request $req)
    {
        $cancel = Cancellation::find($req['id']);
        $cancel->reason         = $req['reason'];
        $cancel->start_date     = convThDateToDbDate($req['start_date']);
        $cancel->start_period   = '1';
        $cancel->end_date       = convThDateToDbDate($req['end_date']);
        $cancel->end_period     = $req['end_period'];
        $cancel->days           = $req['days'];
        $cancel->working_days   = $req['working_days'];
        dd($cancel);

        if ($cancel->save()) {
            return redirect('/cancellations/cancel');
        }
    }

    public function delete(Request $req, $id)
    {
        $cancel = Cancellation::find($id);
        $leaveId = $cancel->leave_id;

        if ($cancel->delete()) {
            $leave = Leave::find($cancel->leave_id);
            $leave->status = 3;
            $leave->save();

            return redirect('/cancellations/cancel')->with('status', 'ลบรายการขอยกเลิกวันลา ID: ' .$id. ' เรียบร้อยแล้ว !!');;
        }
    }

    public function printCancelForm($id)
    {
        $leave      = Leave::where('id', $id)
                        ->with('person', 'person.prefix', 'person.position', 'person.academic')
                        ->with('person.memberOf', 'person.memberOf.depart', 'type')
                        ->with('delegate', 'delegate.prefix', 'delegate.position', 'delegate.academic')
                        ->first();

        $cancel     = Cancellation::where('leave_id', $leave->id)->first();

        $places     = ['1' => 'โรงพยาบาลเทพรัตน์นครราชสีมา'];

        $histories  = History::where([
                            'person_id' => $leave->leave_person,
                            'year'      => $leave->year
                        ])->first();

        $data = [
            'leave'     => $leave,
            'cancel'    => $cancel,
            'places'    => $places,
            'histories' => $histories
        ];

        /** Invoke helper function to return view of pdf instead of laravel's view to client */
        return renderPdf('forms.form03', $data);
    }

    public function getByPerson(Request $req, $personId)
    {
        $year = $req->get('year');
        $type = $req->get('type');

        return [
            'cancellations' => Leave::where('leave_person', $personId)
                                ->whereIn('status', [5,8,9])
                                ->when(!empty($year), function($q) use($year) {
                                    $q->where('year', $year);
                                })
                                ->when(!empty($type), function($q) use($type) {
                                    $q->where('leave_type', $type);
                                })
                                ->with('person', 'person.prefix', 'person.position', 'person.academic')
                                ->with('person.memberOf', 'person.memberOf.depart')
                                ->with('delegate','delegate.prefix','delegate.position','delegate.academic')
                                ->with('type','cancellation')
                                ->orderBy('leave_date', 'DESC')
                                ->paginate(10),
        ];
    }

    public function doApprove(Request $req)
    {
        try {
            $cancel = Cancellation::find($req['_id']);
            $cancel->approved_comment   = $req['comment'];
            $cancel->approved_date      = date('Y-m-d');
            $cancel->approved_by        = Auth::user()->person_id;

            if ($cancel->save()) {
                /** Update status of cancelled leave data */
                $leave = Leave::find($req['leave_id']);
                $leave->status = $leave->leave_days == $cancel->days ? '9' : '8';
                $leave->save();

                /** Update cancelled leave histories data */
                $history = History::where('person_id', $leave->leave_person)->first();

                /** Decrease leave days coordineted leave type */
                if ($leave->leave_type == '1') {
                    $history->ill_days -= (float)$cancel->days;     // ลาป่วย
                } else if ($leave->leave_type == '2') {
                    $history->per_days -= (float)$cancel->days;     // ลากิจส่วนตัว
                } else if ($leave->leave_type == '3') {
                    $history->vac_days -= (float)$cancel->days;     // ลาพักผ่อน
                } else if ($leave->leave_type == '4') {
                    $history->lab_days -= (float)$leave->leave_days; // ลาคลอด
                } else if ($leave->leave_type == '5') {
                    $history->hel_days -= (float)$leave->leave_days; // ลาเพื่อดูแลบุตรและภรรยาหลังคลอด
                } else if ($leave->leave_type == '6') {
                    $history->ord_days -= (float)$cancel->days;     // ลาอุปสมบท
                }

                $history->save();

                return redirect('/leaves/approve');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function doComment(Request $req)
    {
        try {
            $cancel = Cancellation::find($req['_id']);
            $cancel->commented_text   = $req['comment'];
            $cancel->commented_date   = date('Y-m-d');
            $cancel->commented_by     = Auth::user()->person_id;

            if ($cancel->save()) {
                /** Update status of cancelled leave data */
                $leave = Leave::find($req['leave_id']);
                $leave->status = $req['approved'];
                $leave->save();

                return redirect('/leaves/comment');
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
            $cancel->received_by    = Auth::user()->person_id;

            if ($cancel->save()) {
                return redirect('/leaves/receive');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
