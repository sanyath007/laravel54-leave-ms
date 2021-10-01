@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            เพิ่มกลุ่มครุภัณฑ์
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เพิ่มกลุ่มครุภัณฑ์</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="assetGroupCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มเพิ่มกลุ่มครุภัณฑ์</h3>
                    </div>

                    <form id="frmNewAssetGroup" name="frmNewAssetGroup" method="post" action="{{ url('/asset-group/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">
                                
                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(group, 'group_no')}">
                                    <label class="control-label">รหัสกลุ่มครุภัณฑ์ :</label>
                                    <input
                                        type="text"
                                        id="group_no"
                                        name="group_no"
                                        ng-model="group.group_no"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(group, 'group_no')"></span>
                                    <span class="help-block" ng-show="checkValidate(group, 'group_no')">กรุณากรอกรหัสกลุ่มครุภัณฑ์ก่อน</span>
                                </div> 

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(group, 'group_name')}">
                                    <label class="control-label">ชื่อกลุ่มครุภัณฑ์ :</label>
                                    <input
                                        type="text"
                                        id="group_name"
                                        name="group_name"
                                        ng-model="group.group_name"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(group, 'group_name')"></span>
                                    <span class="help-block" ng-show="checkValidate(group, 'group_name')">กรุณากรอกชื่อกลุ่มครุภัณฑ์ก่อน</span>
                                </div>

                            </div><!-- /.col-md-8 -->
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/asset-group/validate', group, 'frmNewAssetGroup', add)"
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
        });
    </script>

@endsection