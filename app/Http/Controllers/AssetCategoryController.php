<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AssetGroup;
use App\Models\AssetCategory;

class AssetCategoryController extends Controller
{
    public function formValidate (Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'cate_no' => 'required',
            'cate_name' => 'required'
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
    	return view('asset-cates.list', [
            'groups' => AssetGroup::orderBy('group_no')->get()
        ]);
    }

    public function search($groupId, $searchKey)
    {
        $conditions = [];
        if($groupId != 0) array_push($conditions, ['group_id', '=', $groupId]);
        if($searchKey !== '0') array_push($conditions, ['cate_name', 'like', '%'.$searchKey.'%']);

        if($conditions == '0') {
            $cates = AssetCategory::with('group')
                        ->orderBy('cate_no')
                        ->paginate(20);
        } else {
            $cates = AssetCategory::where($conditions)
                        ->with('group')
                        ->orderBy('cate_no')
                        ->paginate(20);
        }

        return [
            'cates' => $cates,
        ];
    }

    public function getAll()
    {
        return [
            'cates' => AssetCategory::all(),
        ];
    }

    public function getById($cateId)
    {
        return [
            'cate' => AssetCategory::find($cateId),
        ];
    }

    public function getNo($groupId)
    {
        $cate = AssetCategory::where('group_id', '=', $groupId)
                        ->orderBy('cate_no', 'DESC')
                        ->first();

        if($cate) {
            $cateNo = $cate->cate_no;
        } else {
            $group = AssetGroup::find($groupId);
            $cateNo = $group->group_no.'00';
        }
        
        return [
            'cateNo' => $cateNo
        ];
    }

    private function generateAutoId()
    {
        $cate = \DB::table('asset_cates')
                        ->select('cate_no')
                        ->orderBy('cate_no', 'DESC')
                        ->first();

        $tmpLastNo =  ((int)($cate->cate_no)) + 1;
        $lastNo = sprintf("%'.05d", $tmpLastNo);

        return $lastId;
    }

    public function add()
    {
    	return view('asset-cates.add', [
            'groups' => AssetGroup::orderBy('group_no')->get(),
    	]);
    }

    public function store(Request $req)
    {
        $cate = new AssetCategory();
        // $cate->cate_id = $this->generateAutoId();
        $cate->cate_no = $req['group_no'].$req['cate_no'];
        $cate->cate_name = $req['cate_name'];
        $cate->group_id = $req['group_id'];

        if($cate->save()) {
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

    public function edit($cateId)
    {
        return view('asset-cates.edit', [
            'cate' => AssetCategory::find($cateId),
            'groups' => AssetGroup::orderBy('group_no')->get(),
        ]);
    }

    public function update(Request $req)
    {
        $cate = AssetCategory::find($req['cate_id']);

        $cate->cate_no = $req['group_no'].$req['cate_no'];
        $cate->cate_name = $req['cate_name'];
        $cate->group_id = $req['group_id'];

        if($cate->save()) {
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

    public function delete($cateId)
    {
        $cate = AssetCategory::find($cateId);

        if($cate->delete()) {
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
