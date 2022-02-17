<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function getCancel()
    {
        return view('cancellations.list', [
            "leave_types"   => LeaveType::all(),
            "periods"       => $this->periods,
        ]);
    }

    public function doCancel(Request $req)
    {
        $cancel = new Cancellation;
        $cancel->leave_id       = $req['leave_id'];
        $cancel->cancel_date    = date('Y-m-d');
        $cancel->reason         = $req['reason'];
        $cancel->start_date     = convThDateToDbDate($req['from_date']);
        $cancel->start_period   = $req['start_period'];
        $cancel->end_date       = convThDateToDbDate($req['to_date']);
        $cancel->end_period     = $req['end_period'];
        $cancel->days           = $req['leave_days'];

        if ($cancel->save()) {
            /** Update status of leave data */
            $leave = Leave::find($req['leave_id']);
            $leave->status  = '5';
            $leave->save();

            return redirect('/cancellations/cancel');
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

        /** return view of pdf instead of laravel's view to client */
        return $this->renderPdf('forms.form03', $data);
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
                                ->with('delegate')
                                ->with('delegate.prefix','delegate.position','delegate.academic')
                                ->with('type','cancellation')
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
