<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\Supplier;
use App\Models\SupplierPrefix;

class SupplierController extends Controller
{
    public function index()
    {
    	return view('suppliers.list');
    }

    public function search($searchKey)
    {
        if($searchKey == '0') {
            $suppliers = Supplier::paginate(20);
        } else {
            $suppliers = Supplier::where('supplier_name', 'like', '%'.$searchKey.'%')->paginate(20);
        }

        return [
            'suppliers' => $suppliers,
        ];
    }

    private function generateAutoId()
    {
        $supplier = \DB::table('stock_supplier')
                        ->select('supplier_id')
                        ->orderBy('supplier_id', 'DESC')
                        ->first();

        $tmpLastId =  ((int)($supplier->supplier_id)) + 1;
        $lastId = sprintf("%'.05d", $tmpLastId);

        return $lastId;
    }

    public function add()
    {
    	return view('suppliers.add', [
            'prefixes' => SupplierPrefix::all(),
    	]);
    }

    public function store(Request $req)
    {
        $lastId = $this->generateAutoId();

        $supplier = new Supplier();
        $supplier->supplier_id = $lastId;
        $supplier->prefix_id = $req['prefix_id'];
        $supplier->supplier_name = $req['supplier_name'];
        $supplier->supplier_payto = $req['supplier_name'];
        $supplier->supplier_address1 = $req['supplier_address1'];
        $supplier->supplier_address2 = $req['supplier_address2'];
        $supplier->supplier_address3 = $req['supplier_address3'];
        $supplier->supplier_zipcode = $req['supplier_zipcode'];
        $supplier->supplier_phone = $req['supplier_phone'];
        $supplier->supplier_fax = $req['supplier_fax'];
        $supplier->supplier_email = $req['supplier_email'];
        $supplier->supplier_taxid = $req['supplier_taxid'];
        $supplier->supplier_back_acc = $req['supplier_back_acc'];
        $supplier->supplier_note = $req['supplier_note'];
        $supplier->supplier_credit = $req['supplier_credit'];
        $supplier->supplier_taxrate = $req['supplier_taxrate'];
        $supplier->supplier_agent_name = $req['supplier_agent_name'];
        $supplier->supplier_agent_contact = $req['supplier_agent_contact'];
        $supplier->supplier_agent_email = $req['supplier_agent_email'];

        if($supplier->save()) {
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

    public function getById($supplierId)
    {
        return [
            'supplier' => Supplier::find($supplierId),
        ];
    }

    public function edit($supplierId)
    {
        return view('suppliers.edit', [
            'supplier' => Supplier::find($supplierId),
            'prefixs' => SupplierPrefix::all(),
        ]);
    }

    public function update(Request $req)
    {
        $supplier = Supplier::find($req['supplier_id']);
        $supplier->prename_id = $req['prename_id'];
        $supplier->supplier_name = $req['supplier_name'];
        $supplier->supplier_payto = $req['supplier_name'];
        $supplier->supplier_address1 = $req['supplier_address1'];
        $supplier->supplier_address2 = $req['supplier_address2'];
        $supplier->supplier_address3 = $req['supplier_address3'];
        $supplier->supplier_zipcode = $req['supplier_zipcode'];
        $supplier->supplier_phone = $req['supplier_phone'];
        $supplier->supplier_fax = $req['supplier_fax'];
        $supplier->supplier_email = $req['supplier_email'];
        $supplier->supplier_taxid = $req['supplier_taxid'];
        $supplier->supplier_back_acc = $req['supplier_back_acc'];
        $supplier->supplier_note = $req['supplier_note'];
        $supplier->supplier_credit = $req['supplier_credit'];
        $supplier->supplier_taxrate = $req['supplier_taxrate'];
        $supplier->supplier_agent_name = $req['supplier_agent_name'];
        $supplier->supplier_agent_contact = $req['supplier_agent_contact'];
        $supplier->supplier_agent_email = $req['supplier_agent_email'];

        if($supplier->save()) {
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

    public function delete($supplierId)
    {
        $supplier = Supplier::find($supplierId);

        if($supplier->delete()) {
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
