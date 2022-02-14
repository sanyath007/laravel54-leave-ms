<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Position;
use App\Models\History;
use App\Models\Cancellation;
use App\Models\Vacation;
use App\Models\HelpedWife;
use App\Models\Ordinate;
use App\Models\Oversea;
use App\Models\Country;
use App\Models\Person;
use PDF;


class LeaveController extends Controller
{
    protected $periods = [
        '1'  => 'เต็มวัน',
        '2'  => 'ช่วงเช้า (08.00-12.00น.)',
        '3'  => 'ช่วงบ่าย (13.00-16.00น.)',
    ];

    public function formValidate (Request $request)
    {
        $rules = [
            'leave_place'   => 'required',
            'leave_type'    => 'required',
            'leave_to'      => 'required',
            'start_date'    => 'required',
            'start_period'  => 'required',
            'end_date'      => 'required',
            'end_period'    => 'required',
        ];

        if ($request['leave_type'] == '1' || $request['leave_type'] == '2' || 
            $request['leave_type'] == '3' || $request['leave_type'] == '4' ||
            $request['leave_type'] == '5') {
            $rules['leave_contact'] = 'required';
        }
        
        if ($request['leave_type'] == '1' || $request['leave_type'] == '2' || 
            $request['leave_type'] == '4' || $request['leave_type'] == '7') {
            $rules['leave_reason'] = 'required';
        }

        if ($request['leave_type'] == '5') {
            $rules['wife_name'] = 'required';
            $rules['deliver_date'] = 'required';
        }

        if ($request['leave_type'] == '6') {
            $rules['ordain_date'] = 'required';
            $rules['ordain_temple'] = 'required';
            $rules['ordain_location'] = 'required';
            $rules['hibernate_temple'] = 'required';
            $rules['hibernate_location'] = 'required';
        }

        if ($request['leave_type'] == '7') {
            $rules['country'] = 'required';
        }

        $validator = \Validator::make($request->all(), $rules);

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
        ]);
    }

    public function search(Request $req, $year, $type, $status, $menu)
    {
        $matched = [];
        $pattern = '/^\<|\>/i';

        $conditions = [];
        if($year != '0') array_push($conditions, ['year', '=', $year]);
        if($type != '0') array_push($conditions, ['leave_type', $type]);
        if($status != '-') {
            if (preg_match($pattern, $status, $matched) == 1) {
                $arrStatus = explode($matched[0], $status);
                
                array_push($conditions, ['status', $matched[0], $arrStatus[1]]);
            } else {
                array_push($conditions, ['status', '=', $status]);
            }
        }
        if($menu == '0') array_push($conditions, ['leave_person', \Auth::user()->person_id]);
        if($menu == '0') array_push($conditions, ['leave_person', \Auth::user()->person_id]);

        /** Get depart from query params */
        $qsDepart = $req->get('depart');

        /** Generate list of person of depart from query params */
        $personList = Person::leftJoin('level', 'level.person_id', '=', 'personal.person_id')
                        ->when(!empty($qsDepart), function($q) use ($qsDepart) {
                            $q->where('level.depart_id', $qsDepart);
                        })
                        ->pluck('personal.person_id');

        if(count($conditions) == 0) {
            $leaves = Leave::with('person', 'person.prefix', 'person.position', 'person.academic')
                        ->with('person.memberOf', 'person.memberOf.depart', 'type')
                        ->with('cancellation')
                        ->when(!empty($qsDepart), function($q) use ($personList) {
                            $q->whereIn('leave_person', $personList);
                        })
                        ->orderBy('year', 'desc')
                        ->orderBy('leave_date', 'desc')
                        ->paginate(20);
        } else {
            $leaves = Leave::where($conditions)
                        ->with('person', 'person.prefix', 'person.position', 'person.academic')
                        ->with('person.memberOf', 'person.memberOf.depart', 'type')
                        ->with('cancellation')
                        ->when(!empty($qsDepart), function($q) use ($personList) {
                            $q->whereIn('leave_person', $personList);
                        })
                        ->orderBy('year', 'desc')
                        ->orderBy('leave_date', 'desc')
                        ->paginate(10);
        }

        return [
            'leaves' => $leaves,
        ];
    }

    public function getAll()
    {
        return [
            'leaves' => Leave::orderBy('leave_date')->get(),
        ];
    }

    public function getById($id)
    {
        return [
            'leave' => Leave::where('id', $id)
                        ->with('delegate')
                        ->with('delegate.prefix','delegate.position','delegate.academic')
                        ->with('helpedWife','ordinate')
                        ->with('cancellation')
                        ->first(),
        ];
    }

    public function detail($id)
    {
        return view('leaves.detail', [
            "leave"         => Leave::find($id),
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "periods"       => $this->periods,
        ]);
    }

    public function add()
    {
        return view('leaves.add', [
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "periods"       => $this->periods,
        ]);
    }

    public function store(Request $req)
    {
        $leave = new Leave();
        $leave->leave_date      = convThDateToDbDate($req['leave_date']);
        $leave->leave_place     = $req['leave_place'];
        $leave->leave_topic     = $req['leave_topic'];
        $leave->leave_to        = $req['leave_to'];
        $leave->leave_person    = $req['leave_person'];
        $leave->leave_type      = $req['leave_type'];
        
        /** leave_type detail
         *  1 = ลาป่วย
         *  2 = ลากิจ
         *  3 = ลาพักผ่อน
         *  4 = ลาคลอด
         *  5 = ลาเพื่อดูแลบุตรและภรรยาหลังคลอด
         *  6 = ลาอุปสมบท/ไปประกอบพิธีฮัจย์
         */
        if ($req['leave_type'] == '1' || $req['leave_type'] == '2' || 
            $req['leave_type'] == '3' || $req['leave_type'] == '4') {
            $leave->leave_contact   = $req['leave_contact'];
            $leave->leave_delegate  = $req['leave_delegate'];
        }

        if ($req['leave_type'] == '5') {
            $leave->leave_contact   = $req['leave_contact'];
        }

        if ($req['leave_type'] == '1' || $req['leave_type'] == '2' || 
            $req['leave_type'] == '4' || $req['leave_type'] == '7') {
            $leave->leave_reason    = $req['leave_reason'];
        }

        $leave->start_date      = convThDateToDbDate($req['start_date']);
        $leave->start_period    = '1';
        $leave->end_date        = convThDateToDbDate($req['end_date']);
        $leave->end_period      = $req['end_period'];
        $leave->leave_days      = $req['leave_days'];
        $leave->year            = calcBudgetYear($req['start_date']);

        /** 
         * TODO: 
         * If user duty is 2 (หน.กลุ่มงาน), status must be setted to 1 
         * with insert commented_ column with bypass data
         * or if user duty is 1 (หน.กลุ่มภารกิจ), status must be setted to 2
         * with insert commented_ and received_ column with bypass data
         * else, status must be setted to 0
         */
        if (Auth::user()->memberOf->duty_id == 1) {
            $leave->commented_text  = 'หัวหน้ากลุ่มภารกิจ';
            $leave->commented_date  = date('Y-m-d');
            $leave->commented_by    = Auth::user()->person_id;
            $leave->received_date   = date('Y-m-d');
            $leave->received_by     = Auth::user()->person_id;
            $leave->status          = '2';
        } else if (Auth::user()->memberOf->duty_id == 2) {
            $leave->commented_text  = 'หัวหน้ากลุ่มงาน';
            $leave->commented_date  = date('Y-m-d');
            $leave->commented_by    = Auth::user()->person_id;
            $leave->status          = '1';
        } else {
            $leave->status          = '0';
        }

        /** Upload attach file */
        $attachment = uploadFile($req->file('attachment'), 'uploads/');
        if (!empty($attachment)) {
            $leave->attachment = $attachment;
        }

        if($leave->save()) {
            /** Insert detail data of some leave type */
            if ($req['leave_type'] == '5') {
                $hw = new HelpedWife();
                $hw->leave_id           = $leave->id;
                $hw->wife_name          = $req['wife_name'];
                $hw->deliver_date       = convThDateToDbDate($req['deliver_date']);
                $hw->wife_is_officer    = $req['wife_is_officer'] == true ? 1 : 0;
                $hw->wife_id            = $req['wife_id'];
                $hw->save();
            }

            if ($req['leave_type'] == '6') {
                $ord = new Ordinate();
                $ord->leave_id              = $leave->id;
                $ord->have_ordain           = $req['have_ordain'];
                $ord->ordain_date           = convThDateToDbDate($req['ordain_date']);
                $ord->ordain_temple         = $req['ordain_temple'];
                $ord->ordain_location       = $req['ordain_location'];
                $ord->hibernate_temple      = $req['hibernate_temple'];
                $ord->hibernate_location    = $req['hibernate_location'];
                $ord->save();
            }

            if ($req['leave_type'] == '7') {
                $over = new Oversea();
                $over->leave_id     = $leave->id;
                $over->country_id   = $req['country'];
                $over->save();
            }

            return redirect('/leaves/list');
        }
    }

    public function edit($id)
    {
        return view('leaves.edit', [
            "leave"         => Leave::find($id),
            "leave_types"   => LeaveType::all(),
            "positions"     => Position::all(),
            "periods"       => $this->periods,
        ]);
    }

    public function update(Request $req)
    {
        $leave = Leave::find($req['id']);
        $leave->leave_date      = convThDateToDbDate($req['leave_date']);
        $leave->leave_place     = $req['leave_place'];
        $leave->leave_topic     = $req['leave_topic'];
        $leave->leave_to        = $req['leave_to'];
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
        $leave->status          = '0';

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

    public function getCancel()
    {
        return view('leaves.cancel-list', [
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

            return redirect('/leaves/cancel');
        }
    }

    public function printLeaveForm($id)
    {
        $pdfView = '';
        $leave      = Leave::where('id', $id)
                        ->with('person', 'person.prefix', 'person.position', 'person.academic')
                        ->with('person.memberOf', 'person.memberOf.depart', 'type')
                        ->with('delegate', 'delegate.prefix', 'delegate.position', 'delegate.academic')
                        ->with('cancellation')
                        ->with('helpedWife','ordinate','oversea','oversea.country')
                        ->first();

        $last       = Leave::whereIn('leave_type', [1,2,4,7])
                        ->where('leave_person', $leave->leave_person)
                        ->where('leave_type', $leave->leave_type)
                        ->where('start_date', '<', $leave->start_date)
                        ->with('type','cancellation')
                        ->with('oversea','oversea.country')
                        ->orderBy('start_date', 'desc')
                        ->first();

        $places     = ['1' => 'โรงพยาบาลเทพรัตน์นครราชสีมา'];

        $histories  = History::where([
                            'person_id' => $leave->leave_person,
                            'year'      => $leave->year
                        ])->first();

        $vacation   = Vacation::where([
                            'person_id' => $leave->leave_person,
                            'year'      => $leave->year
                        ])->first();

        $data = [
            'leave'     => $leave,
            'last'      => $last,
            'places'    => $places,
            'histories' => $histories,
            'vacations' => $vacation
        ];

        if (in_array($leave->leave_type, [1,2,4])) { // ลาป่วย กิจ คลอด
            $pdfView = 'forms.form01';
        } else if ($leave->leave_type == 5) {       // ลาเพื่อดูแลบุตรและภรรยาหลังคลอด
            $pdfView = 'forms.form05';
        } else if ($leave->leave_type == 6) {       // ลาอุปสมบท/ไปประกอบพิธีฮัจย์
            $pdfView = 'forms.form06';
        } else if ($leave->leave_type == 7) {       // ลาไปต่างประเทศ
            $pdfView = 'forms.form07';
        } else {                                    // ลาพักผ่อน
            $pdfView = 'forms.form02';
        }

        /** return view of pdf instead of laravel's view to client */
        return $this->renderPdf($pdfView, $data);
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

    /**
     * $renderType should be 'preview' | 'download'
     */
    public function renderPdf($view, $data, $renderType = 'preview')
    {
        $pdf = PDF::loadView($view, $data);

        /** แบบนี้จะ stream มา preview */
        if ($renderType == 'preview') {
            return $pdf->stream();
        }

        /** แบบนี้จะดาวโหลดเลย */
        return $pdf->download('test.pdf');
    }
}
