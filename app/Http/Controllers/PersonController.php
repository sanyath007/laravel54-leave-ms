<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Person;
use App\Models\Education;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::whereNotIn('person_state', [6,7,8,9,99])
                    ->join('level', 'personal.person_id', '=', 'level.person_id')
                    ->where('level.faction_id', '5')
                    ->with('prefix','typeposition','position','academic','office')
                    ->with('memberOf','memberOf.depart','memberOf.division')
                    ->get();

        return $persons;
    }
    
    public function search($depart, $searchKey)
    {
        $persons = Person::whereNotIn('person_state', [6,7,8,9,99])
                    ->join('level', 'personal.person_id', '=', 'level.person_id')
                    ->where('level.faction_id', '5')
                    ->when(!empty($depart), function($q) use ($depart) {
                        $q->where('level.depart_id', $depart);
                    })
                    // ->when(!empty($division), function($q) use ($division) {
                    //     $q->where('level.ward_id', $division);
                    // })
                    ->when(!empty($searchKey), function($q) use ($searchKey) {
                        $name = explode(' ', $searchKey);

                        if (!empty($name[0])) {
                            $q->where('person_firstname', 'like', '%' .$name[0]. '%');
                        }

                        if (count($name) > 1 && !empty($name[1])) {
                            $q->where('person_lastname', 'like', '%' .$name[1]. '%');
                        }
                    })
                    ->with('prefix','typeposition','position','academic','office')
                    ->with('memberOf','memberOf.depart','memberOf.division')
                    ->paginate(10);

        return [
            'persons' => $persons
        ];
    }

    public function getProfile($id)
    {
        $educationLevels = [
            '1' => "ประถมศึกษา", 
            '2' => "มัธยมศึกษาตอนต้น",
            '3' => "มัธยมศึกษาตอนปลาย - ปวช.",
            '4' => "ปวท. / อนุปริญญา - ปวส.",
            '5' => "ปริญญาตรี",
            '6' => "ปริญญาโท",
            '7' => "ปริญญาเอก",
        ];

        $educations = Education::where('person_id', $id)->orderBy('edu_year', 'DESC')->first();

        $personInfo = Person::where('person_id', $id)
                    ->with('prefix','typeposition','position','academic','office')
                    ->with('memberOf','memberOf.depart','memberOf.division','memberOf.duty')
                    ->first();

        return view('histories.profile', [
            'personInfo' => $personInfo,
            'educations' => $educations,
            'educationLevels' => $educationLevels,
        ]);
    }
}
