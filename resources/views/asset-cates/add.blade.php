@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            เพิ่มหมวดครุภัณฑ์
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">เพิ่มหมวดครุภัณฑ์</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="assetCateCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ฟอร์มเพิ่มหมวดครุภัณฑ์</h3>
                    </div>

                    <form id="frmNewAssetCate" name="frmNewAssetCate" method="post" action="{{ url('/debttype/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                        
                        <div class="box-body">
                            <div class="col-md-8">

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(cate, 'group_id')}">
                                    <label class="control-label">กลุ่มครุภัณฑ์ :</label>
                                    <select id="group_id"
                                            name="group_id"
                                            ng-model="cate.group_id"
                                            ng-change="getCateNo(cate.group_id)"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;">
                                            
                                        <option value="" selected="selected">-- กรุณาเลือก --</option>

                                        @foreach($groups as $group)

                                            <option value="{{ $group->group_id }}">
                                                {{ $group->group_no.'-'.$group->group_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(assetClass, 'group_id')"></span>
                                    <span class="help-block" ng-show="checkValidate(cate, 'group_id')">กรุณาเลือกกลุ่มครุภัณฑ์</span>
                                </div>

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(cate, 'cate_no')}">
                                    <label class="control-label">เลขหมวดครุภัณฑ์ :</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">@{{ cate.group_no }}</span>
                                        <input
                                            type="text"
                                            id="cate_no"
                                            name="cate_no"
                                            ng-model="cate.cate_no"
                                            class="form-control">
                                    </div>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(cate, 'cate_no')"></span>
                                    <span class="help-block" ng-show="checkValidate(cate, 'cate_no')">กรุณากรอกเลขหมวดครุภัณฑ์ก่อน</span>
                                </div> 

                                <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(cate, 'cate_name')}">
                                    <label class="control-label">ชื่อหมวดครุภัณฑ์ :</label>
                                    <input
                                        type="text"
                                        id="cate_name"
                                        name="cate_name"
                                        ng-model="cate.cate_name"
                                        class="form-control">
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(cate, 'cate_name')"></span>
                                    <span class="help-block" ng-show="checkValidate(cate, 'cate_name')">กรุณากรอกชื่อหมวดครุภัณฑ์ก่อน</span>
                                </div>

                            </div><!-- /.col-md-8 -->
                        </div><!-- /.box-body -->
                  
                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/asset-cate/validate', cate, 'frmNewAssetCate', add)"
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