<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Faction;
use App\Models\Depart;
use App\Models\Division;
use App\Models\Person;
use App\Models\Leave;
use App\Models\History;
use App\Models\Vacation;

class ReportController extends Controller
{
    public function daily()
    {
        $factions = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Faction::where('is_actived', 1)->get()
                        : Faction::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->get();
        $departs = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Depart::where('is_actived', 1)->orderBy('depart_name', 'ASC')->get()
                        : Depart::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->orderBy('depart_name', 'ASC')
                            ->get();

        return view('reports.daily', [
            "factions"  => $factions,
            "departs"   => $departs,
            "divisions" => Division::where('is_actived', 1)->get()
        ]);
    }

    public function getDailyData(Request $req)
    {
        /** Get params from query string */
        $user       = Person::with('memberOf')->where('person_id', $req->get('user'))->first();
        $faction    = $user->memberOf->person_id == '1300200009261' || $user->memberOf->depart_id == '40'
                        ? $req->get('faction')
                        : $user->memberOf->faction_id;
        $depart     = $user->memberOf->duty_id == 2
                        ? $user->memberOf->depart_id
                        : $req->get('depart');
        $division   = $req->get('division');
        $date       = $req->get('date');
        $name       = $req->get('name');

        /** Generate list of person of depart from query params */
        $personList = Person::leftJoin('level', 'level.person_id', '=', 'personal.person_id')
                        // ->where('person_state', '1')
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

        $leaves = Leave::with('person', 'person.prefix', 'person.position', 'person.academic')
                    ->with('person.memberOf', 'person.memberOf.depart', 'person.memberOf.division')
                    ->with('cancellation', 'type')
                    ->whereIn('status', [2,3,5,8,9])
                    ->when(count($personList) > 0, function($q) use ($personList) {
                        $q->whereIn('leave_person', $personList);
                    })
                    ->when(!empty($date), function($q) use ($date) {
                        $q->where(function($sq) use ($date) {
                            $sq->where('start_date', '<=', $date)->where('end_date', '>=', $date);
                        });
                    })
                    ->orderBy('leave_date', 'desc')
                    ->orderBy('start_date', 'desc')
                    ->paginate(300);

        return [
            'leaves' => $leaves,
        ];
    }

    public function monthly()
    {
        $factions = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Faction::where('is_actived', 1)->get()
                        : Faction::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->get();
        $departs = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Depart::where('is_actived', 1)->orderBy('depart_name', 'ASC')->get()
                        : Depart::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->orderBy('depart_name', 'ASC')
                            ->get();

        return view('reports.monthly', [
            "factions"  => $factions,
            "departs"   => $departs,
            "divisions" => Division::where('is_actived', 1)->get()
        ]);
    }

    public function getMonthlyData(Request $req)
    {
        /** Get params from query string */
        $user       = Person::with('memberOf')->where('person_id', $req->get('user'))->first();
        $faction    = $user->memberOf->person_id == '1300200009261' || $user->memberOf->depart_id == '40'
                        ? $req->get('faction')
                        : $user->memberOf->faction_id;
        $depart     = $user->memberOf->duty_id == 2
                        ? $user->memberOf->depart_id
                        : $req->get('depart');
        $division   = $req->get('division');
        $year       = $req->input('year');
        $month      = $req->get('month');

        $leaves = \DB::table('leaves')
                    ->select(
                        'leave_person',
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
                    ->when(!empty($month), function($q) use ($month) {
                        $sdate = $month. '-01';
                        $edate = date('Y-m-t', strtotime($sdate));

                        $q->where(function($sq) use ($sdate, $edate) {
                            $sq->whereBetween('start_date', [$sdate, $edate]);
                            $sq->orWhere(function($query) use ($sdate, $edate) {
                                $query->whereBetween('end_date', [$sdate, $edate]);
                            });
                        });

                    })
                    ->groupBy('leave_person')->get();

        return [
            'leaves'    => $leaves,
            'persons'   => Person::join('level', 'personal.person_id', '=', 'level.person_id')
                            ->whereIn('person_state', [1,2,4,5])
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
                            ->with('memberOf', 'memberOf.depart')
                            ->orderBy('person_singin')
                            ->paginate(300),
            'histories' => History::where('year', $year)->get()
        ];
    }

    public function summary()
    {
        $factions = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Faction::where('is_actived', 1)->get()
                        : Faction::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->get();
        $departs = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Depart::where('is_actived', 1)->orderBy('depart_name', 'ASC')->get()
                        : Depart::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->orderBy('depart_name', 'ASC')
                            ->get();

        return view('reports.summary', [
            "factions"  => $factions,
            "departs"   => $departs,
            "divisions" => Division::where('is_actived', 1)->get()
        ]);
    }

    public function getSummaryData(Request $req)
    {
        $user       = Person::with('memberOf')->where('person_id', $req->get('user'))->first();
        $faction    = $user->memberOf->person_id == '1300200009261' || $user->memberOf->depart_id == '40'
                        ? $req->get('faction')
                        : $user->memberOf->faction_id;
        $depart     = $user->memberOf->duty_id == 2
                        ? $user->memberOf->depart_id
                        : $req->get('depart');
        $division   = $req->input('division');
        $year       = $req->input('year');

        if (Auth::user()->memberOf->duty_id == 1 || Auth::user()->person_id == '1300200009261') {
            $depart = $req->input('depart');
        } else if (Auth::user()->memberOf->duty_id == 2) {
            $depart = Auth::user()->memberOf->depart_id;
        }

        $persons = Person::join('level', 'personal.person_id', '=', 'level.person_id')
                        ->with('prefix','position','academic')
                        ->with('memberOf', 'memberOf.depart')
                        ->whereIn('person_state', [1,2,4,5])
                        ->when(!empty($faction), function($q) use ($faction) {
                            $q->where('level.faction_id', $faction);
                        })
                        ->when(!empty($depart), function($q) use ($depart) {
                            $q->where('level.depart_id', $depart);
                        })
                        ->when(!empty($division), function($q) use ($division) {
                            $q->where('level.ward_id', $division);
                        })
                        ->orderBy('person_singin');

        $personsList = $persons->pluck('personal.person_id');

        $leaves = \DB::table('leaves')
                    ->select(
                        'leave_person',
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
            'leaves'    => $leaves,
            'persons'   => $persons->paginate(300),
            'histories' => History::where('year', $year)->whereIn('person_id', $personsList)->get(),
            "vacations" => Vacation::where('year', $year)->get()
        ];
    }

    public function remain()
    {
        $factions = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Faction::where('is_actived', 1)->get()
                        : Faction::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->get();
        $departs = Auth::user()->memberOf->person_id == '1300200009261' || Auth::user()->memberOf->depart_id == '40'
                        ? Depart::where('is_actived', 1)->orderBy('depart_name', 'ASC')->get()
                        : Depart::where('is_actived', 1)
                            ->where('faction_id', Auth::user()->memberOf->faction_id)
                            ->orderBy('depart_name', 'ASC')
                            ->get();

        return view('reports.remain', [
            "factions"  => $factions,
            "departs"   => $departs,
            "divisions" => Division::where('is_actived', 1)->get()
        ]);
    }
}
