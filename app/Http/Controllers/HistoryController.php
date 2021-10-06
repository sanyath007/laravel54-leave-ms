<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Person;
use App\Models\Leave;

class HistoryController extends Controller
{
    public function index()
    {
        return view('deprec-types.list');
    }

    public function search($searchKey)
    {
        if($searchKey == '0') {
            $types = DeprecType::paginate(20);
        } else {
            $types = DeprecType::where('deprec_type_name', 'like', '%'.$searchKey.'%')
                        ->paginate(20);
        }

        return [
            'types' => $types,
        ];
    }

    public function getAjexAll($cateId)
    {
        $types = DeprecType::where('cate_id', '=', $cateId)->get();

        return [
            'types' => $types,
        ];
    }

    private function generateAutoId()
    {
        $cate = \DB::table('deprec_types')
                        ->select('deprec_type_no')
                        ->orderBy('deprec_type_no', 'DESC')
                        ->first();

        $tmpLastNo =  ((int)($type->type_no)) + 1;
        $lastNo = sprintf("%'.05d", $tmpLastNo);

        return $lastId;
    }

    public function add()
    {
        return view('deprec-types.add');
    }

    public function store(Request $req)
    {
        $type = new DeprecType();
        // $type->type_id = $this->generateAutoId();
        $type->deprec_type_no = $req['deprec_type_no'];
        $type->deprec_type_name = $req['deprec_type_name'];
        $type->deprec_life_y = $req['deprec_life_y'];
        $type->deprec_rate_y = $req['deprec_rate_y'];

        if($type->save()) {
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

    public function getById($typeId)
    {
        return [
            'type' => DeprecType::find($typeId),
        ];
    }

    public function edit($typeId)
    {
        return view('deprec-types.edit', [
            'type' => DeprecType::find($typeId)
        ]);
    }

    public function update(Request $req)
    {
        $type = DeprecType::find($req['deprec_type_id']);
        $type->deprec_type_name = $req['deprec_type_name'];
        $type->deprec_life_y = $req['deprec_life_y'];
        $type->deprec_rate_y = $req['deprec_rate_y'];

        if($type->save()) {
            return [
                "status" => "success",
                "message" => "Update success.",
            ];
        } else {
            return [
                "status" => "error",
                "message" => "Update failed.",
            ];
        }
    }

    public function delete($typeId)
    {
        $type = DeprecType::find($typeId);

        if($type->delete()) {
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
}
