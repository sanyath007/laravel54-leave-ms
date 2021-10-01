@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขประเภทการคิดค่าเสื่อม : {{ $type->deprec_type_id }}
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขประเภทการคิดค่าเสื่อม</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="deprecTypeCtrl" ng-init="getDeprecType('{{ $type->deprec_type_id }}')">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มแก้ไขประเภทการคิดค่าเสื่อม</h3>
                    </div>

                    <form id="frmEditDeprecType" name="frmEditDeprecType" method="post" action="{{ url('/deprec-type/update') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">

                            <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(deprecType, 'deprec_type_no')}">
                                    <label class="control-label">รหัสประเภทการคิดค่าเสื่อม :</label>
                                    <input
                                        type="text"
                                        id="deprec_type_no"
                                        name="deprec_type_no"
                                        ng-model="deprecType.deprec_type_no"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(deprecType, 'deprec_type_no')"></span>
                                    <span class="help-block" ng-show="checkValidate(deprecType, 'deprec_type_no')">กรุณากรอกรหัสประเภทการคิดค่าเสื่อมก่อน</span>
                                </div>

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(deprecType, 'deprec_type_name')}">
                                    <label class="control-label">ชื่อประเภทการคิดค่าเสื่อม :</label>
                                    <input
                                        type="text"
                                        id="deprec_type_name"
                                        name="deprec_type_name"
                                        ng-model="deprecType.deprec_type_name"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(deprecType, 'deprec_type_name')"></span>
                                    <span class="help-block" ng-show="checkValidate(type, 'deprec_type_name')">กรุณากรอกชื่อประเภทการคิดค่าเสื่อมก่อน</span>
                                </div>

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(deprecType, 'life_y')}">
                                    <label class="control-label">อายุการใช้งาน (ปี) :</label>
                                    <input
                                        type="text"
                                        id="deprec_life_y"
                                        name="deprec_life_y"
                                        ng-model="deprecType.deprec_life_y"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(deprecType, 'deprec_life_y')"></span>
                                    <span class="help-block" ng-show="checkValidate(deprecType, 'deprec_life_y')">กรุณาระบุอายุการใช้งานก่อน</span>
                                </div>

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(deprecType, 'deprec_rate_y')}">
                                    <label class="control-label">อัตรค่าเสื่อม/ปี (ร้อยละ) :</label>
                                    <input
                                        type="text"
                                        id="deprec_rate_y"
                                        name="deprec_rate_y"
                                        ng-model="deprecType.deprec_rate_y"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(deprecType, 'deprec_rate_y')"></span>
                                    <span class="help-block" ng-show="checkValidate(deprecType, 'deprec_rate_y')">กรุณาระบุอัตรค่าเสื่อม/ปีก่อน</span>
                                </div>

                            </div><!-- /.col -->
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/deprec-type/validate', deprecType, 'frmEditDeprecType', update)"
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
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

@endsection