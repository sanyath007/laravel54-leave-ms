@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขครุภัณฑ์
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขครุภัณฑ์</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="assetCtrl" ng-init="getAsset('{{ $asset->asset_id }}')">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">แก้ไขครุภัณฑ์ : @{{ asset.asset_id }}</h3>
                    </div>

                    <form id="frmEditAsset" name="frmEditAsset" method="post" action="{{ url('/asset/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        <input type="hidden" id="asset_id" name="asset_id" ng-model="asset.asset_id">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                        <div class="row">
                                <div class="col-md-6">
                                    
                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'parcel_id')}">
                                        <label>พัสดุหลัก :</label>
                                        <select
                                            id="parcel_id"
                                            name="parcel_id"
                                            ng-model="asset.parcel_id"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                            tabindex="2"
                                            disabled
                                        >
                                            <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                            @foreach($parcels as $parcel)

                                                <option value="{{ $parcel->parcel_id }}">
                                                    {{ $parcel->parcel_no.'-'.$parcel->parcel_name }}
                                                </option>

                                            @endforeach

                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'parcel_id')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'parcel_id')">กรุณาเลือกชนิดครุภัณฑ์</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'asset_name')}">
                                        <label>ชื่อครุภัณฑ์ :</label>
                                        <input
                                            type="text" 
                                            id="asset_name" 
                                            name="asset_name" 
                                            ng-model="asset.asset_name" 
                                            class="form-control"
                                            tabindex="6"
                                        >
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'asset_name')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'asset_name')">กรุณาระบุชื่อครุภัณฑ์</span>
                                    </div>
                                    
                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'date_exp')}">
                                        <label>วันที่หมดอายุการใช้งาน :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="date_exp" 
                                                    name="date_exp" 
                                                    ng-model="asset.date_exp" 
                                                    class="form-control pull-right"
                                                    tabindex="1">
                                        </div>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'date_exp')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'date_exp')">กรุณาเลือกวันที่หมดอายุการใช้งาน</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'amount')}">
                                        <label>จำนวน :</label>
                                        <input  type="text" 
                                                id="amount" 
                                                name="amount" 
                                                ng-model="asset.amount" 
                                                class="form-control"
                                                tabindex="8">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'amount')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'amount')">กรุณาระบุจำนวน</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'budget_type')}">
                                        <label>ประเภทเงิน :</label>
                                        <select id="budget_type" 
                                                name="budget_type"
                                                ng-model="asset.budget_type"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                            @foreach($budgets as $budget)

                                                <option value="{{ $budget->budget_type_id }}">
                                                    {{ $budget->budget_type_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'budget_type')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'budget_type')">ประเภทเงิน</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'reg_no')}">
                                        <label>เลขทะเบียน :</label>
                                        <input  type="text" 
                                                id="reg_no" 
                                                name="reg_no" 
                                                ng-model="asset.reg_no"
                                                class="form-control"
                                                pattern="[0-9]{4}"
                                                tabindex="16">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'reg_no')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'reg_no')">กรุณาระบุเลขทะเบียน</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'depart')}">
                                        <label>หน่วยงาน :</label>
                                        <select id="depart" 
                                                name="depart"
                                                ng-model="asset.depart"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                            @foreach($departs as $depart)

                                                <option value="{{ $depart->depart_id }}">
                                                    {{ $depart->depart_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'depart')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'depart')">กรุณาเลือกหหน่วยงาน</span>
                                    </div>

                                    <div class="form-group">
                                        <label>รายละเอียดเพิ่มเติม :</label>
                                        <textarea
                                            id="description" 
                                            name="description" 
                                            ng-model="asset.description" 
                                            class="form-control"
                                            tabindex="17"
                                        ></textarea>
                                    </div>

                                </div><!-- /.col -->

                                <div class="col-md-6">

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'asset_no')}">
                                        <label>เลขพัสดุ :</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">@{{ asset.parcel_no }}</span>
                                            <input  type="text" 
                                                id="asset_no" .
                                                name="asset_no" 
                                                ng-model="asset.asset_no" 
                                                class="form-control"
                                                tabindex="4">
                                        </div>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'asset_no')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'asset_no')">กรุณาระบุเลขพัสดุ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'date_in')}">
                                        <label>วันที่รับเข้าระบบ :</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input
                                                type="text" 
                                                id="date_in" 
                                                name="date_in" 
                                                ng-model="asset.date_in"
                                                class="form-control pull-right"
                                                tabindex="1"
                                            >
                                        </div>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'date_in')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'date_in')">กรุณาเลือกวันที่รับเข้าระบบ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'unit_price')}">
                                        <label>ราคาหน่วยละ :</label>
                                        <input  type="text" 
                                                id="unit_price" 
                                                name="unit_price" 
                                                ng-model="asset.unit_price" 
                                                class="form-control"
                                                tabindex="3">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'unit_price')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'unit_price')">กรุณาระบุราคาหน่วยละ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'unit')}">
                                        <label>หน่วยนับ :</label>
                                        <select id="unit" 
                                                name="unit"
                                                ng-model="asset.unit"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                            @foreach($units as $unit)

                                                <option value="{{ $unit->unit_id }}">
                                                    {{ $unit->unit_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'unit')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'unit')">กรุณาเลือกหน่วยนับ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'purchased_method')}">
                                        <label>ประเภทการได้มา :</label>
                                        <select id="purchased_method" 
                                                name="purchased_method"
                                                ng-model="asset.purchased_method"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                            @foreach($methods as $method)

                                                <option value="{{ $method->method_id }}">
                                                    {{ $method->method_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'purchased_method')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'purchased_method')">กรุณาเลือกประเภทการได้มา</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'year')}">
                                        <label>ปีงบประมาณ (พ.ศ.) :</label>
                                        <input  type="text" 
                                                id="year" 
                                                name="year" 
                                                ng-model="asset.year"
                                                class="form-control"
                                                pattern="[0-9]{4}"
                                                tabindex="16">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'year')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'year')">กรุณาระบุปีงบประมาณ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'supplier')}">
                                        <label class="control-label">ผู้จัดจำหน่าย :</label>
                                        <select id="supplier" 
                                                name="supplier"
                                                ng-model="asset.supplier"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                            @foreach($suppliers as $supplier)

                                                <option value="{{ $supplier->supplier_id }}">
                                                    {{ $supplier->supplier_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'supplier')"></span>
                                        <span class="help-block" ng-show="checkValidate(asset, 'supplier')">กรุณาเลือกผู้จัดจำหน่าย</span>
                                    </div>

                                    <div class="form-group">
                                        <label>หมายเหตุ :</label>
                                        <textarea
                                            id="remark" 
                                            name="remark" 
                                            ng-model="asset.remark" 
                                            class="form-control"
                                            tabindex="17"
                                        ></textarea>
                                    </div>
                                    
                                </div><!-- /.col -->

                            </div><!-- /.row -->

                            <ul  class="nav nav-tabs">
                                <li class="active">
                                    <a  href="#1a" data-toggle="tab">หลักฐานการได้มา</a>
                                </li>
                            </ul>

                            <div class="tab-content clearfix">
                                <div class="tab-pane active" id="1a" style="padding: 10px;">
                                    <div class="col-md-12">       
                                        <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'doc_type')}">
                                            <label class="control-label">ประเภทหลักฐาน :</label>
                                            <select id="doc_type"
                                                    name="doc_type"
                                                    ng-model="asset.doc_type"
                                                    class="form-control select2"
                                                    style="width: 100%; font-size: 12px;"
                                                    tabindex="2">
                                                <!-- <option value="" selected="selected">-- กรุณาเลือก --</option> -->

                                                @foreach($docs as $doc)

                                                    <option value="{{ $doc->doc_type_id }}">
                                                        {{ $doc->doc_type_name }}
                                                    </option>

                                                @endforeach
                                                    
                                            </select>
                                            <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'doc_type')"></span>
                                            <span class="help-block" ng-show="checkValidate(asset, 'doc_type')">กรุณาเลือกประเภทหลักฐาน</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'doc_no')}">
                                            <label>เลขที่เอกสาร :</label>
                                            <input  type="text" 
                                                    id="doc_no" 
                                                    name="doc_no" 
                                                    ng-model="asset.doc_no"
                                                    class="form-control"
                                                    tabindex="12">
                                            <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'doc_no')"></span>
                                            <span class="help-block" ng-show="checkValidate(asset, 'doc_no')">กรุณาระบุเลขที่เอกสาร</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(asset, 'doc_date')}">
                                            <label>ลงวันที่ :</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input  type="text" 
                                                        id="doc_date" 
                                                        name="doc_date" 
                                                        ng-model="asset.doc_date" 
                                                        class="form-control pull-right"
                                                        tabindex="5">
                                            </div>
                                            <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(asset, 'doc_date')"></span>
                                            <span class="help-block" ng-show="checkValidate(asset, 'doc_date')">กรุณาเลือกวันที่หลักฐานการได้มา</span>
                                        </div>
                                    </div>

                                </div><!-- /.tab-pane -->
                            </div><!-- /.tab-content -->
                            
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/asset/validate', asset, 'frmEditAsset', update)"
                                class="btn btn-warning pull-right"
                            >
                                แก้ไข
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>

@endsection