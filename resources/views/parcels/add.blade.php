@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            เพิ่มพัสดุหลัก
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เพิ่มพัสดุหลัก</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="parcelCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">เพิ่มพัสดุหลัก</h3>
                    </div>

                    <form id="frmNewParcel" name="frmNewParcel" method="post" action="{{ url('/parcel/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    
                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'asset_type')}">
                                        <label class="control-label">ชนิดครุภัณฑ์ :</label>
                                        <select id="asset_type"
                                                name="asset_type"
                                                ng-model="parcel.asset_type"
                                                ng-change="getParcelNo(parcel.asset_type)"
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($types as $type)

                                                <option value="{{ $type->type_id }}">
                                                    {{ $type->type_no.'-'.$type->type_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'asset_type')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'asset_type')">กรุณาเลือกชนิดครุภัณฑ์</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'parcel_name')}">
                                        <label>ชื่อพัสดุหลัก :</label>
                                        <input  type="text" 
                                                id="parcel_name" 
                                                name="parcel_name" 
                                                ng-model="parcel.parcel_name" 
                                                class="form-control"
                                                tabindex="6">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'parcel_name')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'parcel_name')">กรุณาระบุชื่อครุภัณฑ์</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'unit_price')}">
                                        <label>ราคาหน่วยละ :</label>
                                        <input  type="text" 
                                                id="unit_price" 
                                                name="unit_price" 
                                                ng-model="parcel.unit_price" 
                                                class="form-control"
                                                tabindex="3">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'unit_price')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'unit_price')">กรุณาระบุราคาหน่วยละ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'deprec_type')}">
                                        <label class="control-label">เกณฑ์การคิดค่าเสื่อม :</label>
                                        <select id="deprec_type" 
                                                name="deprec_type"
                                                ng-model="parcel.deprec_type" 
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($deprecTypes as $deprecType)

                                                <option value="{{ $deprecType->deprec_type_id }}">
                                                    {{ $deprecType->deprec_type_no.'-'.$deprecType->deprec_type_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'deprec_type')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'deprec_type')">กรุณาเลือกเกณฑ์การคิดค่าเสื่อม</span>
                                    </div>

                                    <div class="form-group">
                                        <label>รายละเอียดเพิ่มเติม :</label>
                                        <textarea
                                            id="description" 
                                            name="description" 
                                            ng-model="parcel.description" 
                                            class="form-control"
                                            tabindex="17"
                                        ></textarea>
                                    </div>

                                </div><!-- /.col -->

                                <div class="col-md-6">

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'parcel_no')}">
                                        <label>เลขพัสดุ :</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">@{{ parcel.asset_type_no }}</span>
                                            <input  type="text" 
                                                id="parcel_no" .
                                                name="parcel_no" 
                                                ng-model="parcel.parcel_no" 
                                                class="form-control"
                                                tabindex="4">
                                        </div>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'parcel_no')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'parcel_no')">กรุณาระบุเลขพัสดุ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'parcel_type')}">
                                        <label class="control-label">ประเภทพัสดุ :</label>
                                        <select id="parcel_type" 
                                                name="parcel_type"
                                                ng-model="parcel.parcel_type" 
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($parcelTypes as $key => $parcelType)

                                                <option value="{{ $key }}">
                                                    {{ $parcelType }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'parcel_type')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'parcel_type')">กรุณาเลือกชนิดครุภัณฑ์</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'unit')}">
                                        <label>หน่วยนับ :</label>
                                        <select id="unit" 
                                                name="unit"
                                                ng-model="parcel.unit" 
                                                class="form-control select2" 
                                                style="width: 100%; font-size: 12px;"
                                                tabindex="2">
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>

                                            @foreach($units as $unit)

                                                <option value="{{ $unit->unit_id }}">
                                                    {{ $unit->unit_name }}
                                                </option>

                                            @endforeach
                                                
                                        </select>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'unit')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'unit')">กรุณาเลือกหน่วยนับ</span>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(parcel, 'first_y_month')}">
                                        <label class="control-label">จน.เดือนที่คิดค่าเสื่อมปีแรก :</label>
                                        <input  type="text" 
                                                id="first_y_month" 
                                                name="first_y_month" 
                                                ng-model="parcel.first_y_month" 
                                                class="form-control"
                                                tabindex="1">
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(parcel, 'first_y_month')"></span>
                                        <span class="help-block" ng-show="checkValidate(parcel, 'first_y_month')">กรุณาระบุจน.เดือนที่คิดค่าเสื่อมปีแรก</span>
                                    </div>

                                    <div class="form-group">
                                        <label>หมายเหตุ :</label>
                                        <textarea
                                            id="remark" 
                                            name="remark" 
                                            ng-model="parcel.remark" 
                                            class="form-control"
                                            tabindex="17"
                                        ></textarea>
                                    </div>
                                    
                                </div><!-- /.col -->
                            </div><!-- /.row -->                            
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/parcel/validate', parcel, 'frmNewParcel', store)"
                                class="btn btn-success pull-right"
                            >
                                บันทึก
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            $('#date_in').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });

            $('#doc_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
        });
    </script>

@endsection