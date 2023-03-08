<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;
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
use App\Models\Faction;
use App\Models\Depart;
use App\Models\Division;
use PDF;

class ManagementController extends Controller
{
    protected $periods = [
        '1'  => 'เต็มวัน',
        '2'  => 'ช่วงเช้า (08.00-12.00น.)',
        '3'  => 'ช่วงบ่าย (13.00-16.00น.)',
    ];

    public function formValidate(Request $request)
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

        $messages = [
            'start_date.required'   => 'กรุณาเลือกจากวันที่',
            'start_date.not_in'     => 'คุณมีการลาในวันที่ระบุแล้ว',
            'end_date.required'     => 'กรุณาเลือกถึงวันที่',
            'end_date.not_in'       => 'คุณมีการลาในวันที่ระบุแล้ว',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();

            if (empty($request['leave_id']) && !$messageBag->has('start_date')) {
                if ($this->isDateExistsValidation(convThDateToDbDate($request['start_date']), 'start_date') > 0) {
                    $messageBag->add('start_date', 'คุณมีการลาในวันที่ระบุแล้ว');
                }
            }

            if (empty($request['leave_id']) && !$messageBag->has('end_date')) {
                if ($this->isDateExistsValidation(convThDateToDbDate($request['end_date']), 'end_date') > 0) {
                    $messageBag->add('end_date', 'คุณมีการลาในวันที่ระบุแล้ว');
                }
            }

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

    private function isDateExistsValidation($dbDate, $column)
    {
        list($year, $month, $day) = explode('-', $dbDate);
        $sdate = $year.'-'.$month.'-01';
        $edate = date('Y-m-t', strtotime($sdate));

        $leaves = Leave::where('leave_person', Auth::user()->person_id)
                    ->whereBetween($column, [$sdate, $edate])
                    ->get();

        $existed = 0;
        foreach($leaves as $leave) {
            if ($leave->start_date <= $dbDate && $leave->end_date >= $dbDate) {
                $existed++;
            }
        }

        return $existed > 0;
    }

    public function leaves()
    {
        return view('managements.list', [
            "leave_types"   => LeaveType::all(),
            "factions"      => Faction::whereNotIn('faction_id', [4, 6, 12])->get(),
            "departs"       => Depart::orderBy('depart_name', 'ASC')->get(),
            "divisions"     => Division::all()
        ]);
    }

    public function getLeaves(Request $req)
    {
        $matched = [];
        $arrStatus = [];
        $pattern = '/^\<|\>|\&|\-/i';

        /** Get params from query string */
        $user       = $req->get('user');
        $faction    = $user == '1300200009261' ? $req->get('faction') : '';
        $depart     = $user == '1300200009261' ? $req->get('depart') : '';
        $division   = $user == '1300200009261' ? $req->get('division') : '';
        $year       = $req->get('year');
        $type       = $req->get('type');
        $status     = $req->get('status');
        $menu       = $req->get('menu');
        $name       = $req->get('name');

        list($sdate, $edate) = array_key_exists('date', $req->all())
                                ? explode('-', $req->get('date'))
                                : explode('-', '-');

        $conditions = [];
        if($status != '-') {
            if (preg_match($pattern, $status, $matched) == 1) {
                $arrStatus = explode($matched[0], $status);

                if ($matched[0] != '-' && $matched[0] != '&') {
                    array_push($conditions, ['status', $matched[0], $arrStatus[1]]);
                }
            } else {
                array_push($conditions, ['status', '=', $status]);
            }
        }

        /** Generate list of person of depart from query params */
        $personList = Person::leftJoin('level', 'level.person_id', '=', 'personal.person_id')
                        ->where('person_state', '1')
                        ->when(!empty($faction), function($q) use ($faction) {
                            $q->where('level.faction_id', $faction);
                        })
                        ->when(!empty($depart), function($q) use ($depart) {
                            $q->where('level.depart_id', $depart);
                        })
                        ->when(!empty($division), function($q) use ($division) {
                            $wardLists = explode(",", $division);

                            $q->whereIn('level.ward_id', $wardLists);
                        })
                        ->when(!empty($name), function($q) use ($name) {
                            $q->where('person_firstname', 'like', $name.'%');
                        })
                        ->pluck('personal.person_id');

        $leaves = Leave::with('person','person.prefix','person.position','person.academic')
                    ->with('person.memberOf','person.memberOf.depart','person.memberOf.division')
                    ->with('type','cancellation')
                    ->when(!empty($year), function($q) use ($year) {
                        $q->where('year', $year);
                    })
                    ->when(!empty($type), function($q) use ($type) {
                        $q->where('leave_type', $type);
                    })
                    ->when($menu == '0', function($q) use ($user) {
                        $q->where('leave_person', $user);
                    })
                    ->when(count($conditions) > 0, function($q) use ($conditions) {
                        $q->where($conditions);
                    })
                    ->when(count($matched) > 0 && $matched[0] == '&', function($q) use ($arrStatus) {
                        $q->whereIn('status', $arrStatus);
                    })
                    ->when(count($matched) > 0 && $matched[0] == '-', function($q) use ($arrStatus) {
                        $q->whereBetween('status', $arrStatus);
                    })
                    ->when(array_key_exists('date', $req->all()) && $req->get('date') != '-', function($q) use ($sdate, $edate) {
                        if ($sdate != '' && $edate != '') {
                            $q->whereBetween('start_date', [convThDateToDbDate($sdate), convThDateToDbDate($edate)]);
                        } else if ($edate == '') {
                            $q->where('end_date', convThDateToDbDate($sdate));
                        }
                    })
                    ->where(function($sq) use ($personList) {
                        $sq->whereIn('leave_person', $personList);
                    })
                    ->orderBy('leave_date', 'desc')
                    ->orderBy('start_date', 'desc')
                    ->paginate(20);

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

    public function vacations()
    {
        $depart = '';
        if (Auth::user()->memberOf->duty_id == 2) {
            $depart = Auth::user()->memberOf->depart_id;
        }

        return view('managements.vacations', [
            "factions"  => Faction::whereNotIn('faction_id', [4, 6, 12])->get(),
            "departs"   => Depart::orderBy('depart_name', 'ASC')->get(),
            "divisions" => Division::when(!empty($depart), function($q) use ($depart) {
                                $q->where('depart_id', $depart);
                            })->get()
        ]);
    }

    public function getVacations(Request $req)
    {
        $user       = $req->get('user');
        $faction    = $user == '1300200009261' ? $req->get('faction') : '';
        $depart     = $user == '1300200009261' ? $req->get('depart') : '';
        $division   = $user == '1300200009261' ? $req->get('division') : '';
        $year       = $req->get('year');

        $persons    = Person::join('level', 'personal.person_id', '=', 'level.person_id')
                        ->where('person_state', '1')
                        ->when(!empty($faction), function($q) use ($faction) {
                            $q->where('level.faction_id', $faction);
                        })
                        ->when(!empty($depart), function($q) use ($depart) {
                            $q->where('level.depart_id', $depart);
                        })
                        ->when(!empty($division), function($q) use ($division) {
                            $q->where('level.ward_id', $division);
                        })
                        ->with('prefix','position','academic')
                        ->with('memberOf', 'memberOf.depart');

        $personsList = $persons->pluck('personal.person_id');

        $leaves = \DB::table('leaves')
                    ->select(
                        \DB::raw("leave_person as person_id"),
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
                    ->whereIn('status', [3,5,8,9])
                    ->where('year', $year)
                    ->whereIn('leave_person', $personsList)
                    ->groupBy('leave_person')->get();

        return [
            "leaves"    => $leaves,
            "persons"   => $persons->paginate(20),
            "histories" => History::where('year', $year)->get(),
            "vacations" => Vacation::where('year', $year)->get()
        ];
    }

    public function storeVacation(Request $req)
    {
        try {
            $vacation = new Vacation();
            $vacation->year         = $req['year'];
            $vacation->person_id    = $req['person_id'];
            $vacation->old_days     = $req['old_days'];
            $vacation->new_days     = $req['new_days'];
            $vacation->all_days     = $req['all_days'];
            $vacation->created_user = $req['user'];
            $vacation->updated_user = $req['user'];

            if($vacation->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Insertion successfully!!',
                    'vacation'  => $vacation,
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function updateVacation(Request $req, $id)
    {
        try {
            $vacation = Vacation::find($id);
            // $vacation->year         = $req['year'];
            // $vacation->person_id    = $req['person_id'];
            $vacation->old_days     = $req['old_days'];
            $vacation->new_days     = $req['new_days'];
            $vacation->all_days     = $req['all_days'];
            $vacation->updated_user = $req['user'];

            if($vacation->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Updating successfully!!',
                    'vacation'  => $vacation,
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function storeHistory(Request $req)
    {
        try {
            $history = new History();
            $history->year         = $req['year'];
            $history->person_id    = $req['person_id'];
            $history->ill_days     = $req['ill_days'];
            $history->per_days     = $req['per_days'];
            $history->lab_days     = $req['lab_days'];
            $history->vac_days     = $req['vac_days'];
            $history->hel_days     = $req['hel_days'];
            $history->ord_days     = $req['ord_days'];
            $history->created_user = $req['user'];
            $history->updated_user = $req['user'];

            if($history->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Insertion successfully!!',
                    'history'  => $history,
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
        }
    }

    public function updateHistory(Request $req, $id)
    {
        try {
            $history = History::find($id);
            // $history->year         = $req['year'];
            // $history->person_id    = $req['person_id'];
            $history->ill_days     = $req['ill_days'];
            $history->per_days     = $req['per_days'];
            $history->lab_days     = $req['lab_days'];
            $history->vac_days     = $req['vac_days'];
            $history->hel_days     = $req['hel_days'];
            $history->ord_days     = $req['ord_days'];
            $history->updated_user = $req['user'];

            if($history->save()) {
                return [
                    'status'    => 1,
                    'message'   => 'Updating successfully!!',
                    'history'  => $history,
                ];
            } else {
                return [
                    'status'    => 0,
                    'message'   => 'Something went wrong!!'
                ];
            }
        } catch (\Exception $ex) {
            return [
                'status'    => 0,
                'message'   => $ex->getMessage()
            ];
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

        /** Invoke helper function to return view of pdf instead of laravel's view to client */
        return renderPdf($pdfView, $data);
    }
}
