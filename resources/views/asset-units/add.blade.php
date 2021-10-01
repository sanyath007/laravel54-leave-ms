@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            เพิ่มหน่วยครุภัณฑ์
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เพิ่มหน่วยครุภัณฑ์</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="assetUnitCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มเพิ่มหน่วยครุภัณฑ์</h3>
                    </div>

                    <form id="frmNewAssetUnit" name="frmNewAssetUnit" method="post" action="{{ url('/asset-unit/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">
                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(unit, 'unit_name')}">
                                    <label class="control-label">ชื่อหน่วยครุภัณฑ์ :</label>
                                    <input
                                        type="text"
                                        id="unit_name"
                                        name="unit_name"
                                        ng-model="unit.unit_name"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(unit, 'unit_name')"></span>
                                    <span class="help-block" ng-show="checkValidate(unit, 'unit_name')">กรุณากรอกชื่อหน่วยครุภัณฑ์ก่อน</span>
                                </div> 
                            </div>
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/asset-unit/validate', unit, '#frmNewAssetUnit')"
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