<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AssetUnit;

class AssetUnitController extends Controller
{
    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'unit_name' => 'required'
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
    	return view('asset-units.list');
    }

    public function search($searchKey)
    {
        if($searchKey == '0') {
            $units = AssetUnit::paginate(20);
        } else {
            $units = AssetUnit::where('unit_name', 'like', '%'.$searchKey.'%')->paginate(20);
        }

        return [
            'units' => $units,
        ];
    }

    private function generateAutoId()
    {
        $unit = \DB::table('asset_units')
                        ->select('unit_id')
                        ->orderBy('unit_id', 'DESC')
                        ->first();

        $tmpLastNo =  ((int)($unit->unit_id)) + 1;
        $lastNo = sprintf("%'.05d", $tmpLastNo);

        return $lastId;
    }

    public function add()
    {
    	return view('asset-units.add');
    }

    public function store(Request $req)
    {
        $unit = new AssetUnit();
        // $unit->unit_id = $this->generateAutoId();
        $unit->unit_name = $req['unit_name'];

        if($unit->save()) {
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

    public function getById($unitId)
    {
        return [
            'unit' => AssetUnit::find($unitId),
        ];
    }

    public function edit($unitId)
    {
        return view('asset-units.edit', [
            'unit' => AssetUnit::find($unitId),
        ]);
    }

    public function update(Request $req)
    {
        $unit = AssetUnit::find($req['unit_id']);
        $unit->unit_name = $req['unit_name'];

        if($unit->save()) {
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

    public function delete($unitId)
    {
        $unit = AssetUnit::find($unitId);

        if($unit->delete()) {
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
