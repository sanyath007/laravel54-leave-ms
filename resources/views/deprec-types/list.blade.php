@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการประเภทการคิดค่าเสื่อม
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการประเภทการคิดค่าเสื่อม</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="deprecTypeCtrl" ng-init="getData()">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="col-md-12">                                
                                <div class="form-group">
                                    <label>ค้นหาชื่อประเภทการคิดค่าเสื่อม</label>
                                    <input type="text" id="searchKey" ng-keyup="getData($event)" class="form-control">
                                </div><!-- /.form group -->
                            </div>

                        </div><!-- /.box-body -->
                  
                        <div class="box-footer">
                            <a href="{{ url('/deprec-type/add') }}" class="btn btn-primary"> เพิ่มประเภทการคิดค่าเสื่อม</a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">

                    <div class="box-header with-border">
                      <h3 class="box-title">รายการประเภทการคิดค่าเสื่อม</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                      <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 10%; text-align: center;">เลขรหัส</th>
                                    <th style="text-align: left;">ชื่อประเภทการคิดค่าเสื่อม</th>
                                    <th style="width: 15%; text-align: left;">อายุการใช้งาน (ปี)</th>
                                    <th style="width: 15%; text-align: left;">อัตรค่าเสื่อม/ปี (%)</th>
                                    <th style="width: 8%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, deprecType) in deprecTypes">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ deprecType.deprec_type_no }}</td>
                                    <td style="text-align: left;">@{{ deprecType.deprec_type_name }}</td>
                                    <td style="text-align: center;">@{{ deprecType.deprec_life_y }}</td>
                                    <td style="text-align: center;">@{{ deprecType.deprec_rate_y }}</td>
                                    <td style="text-align: center;">
                                        <a ng-click="edit(deprecType.deprec_type_id)" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        @if(Auth::user()->person_id == '1300200009261')

                                            <a ng-click="delete(deprecType.deprec_type_id)" class="btn btn-danger btn-sm">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        
                                        @endif

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">

                            <li ng-if="pager.current_page !== 1">
                                <a ng-click="getDataWithURL(pager.first_page_url)" aria-label="Previous">
                                    <span aria-hidden="true">First</span>
                                </a>
                            </li>
                        
                            <li ng-class="{'disabled': (pager.current_page==1)}">
                                <a ng-click="getDataWithURL(pager.first_page_url)" aria-label="Prev">
                                    <span aria-hidden="true">Prev</span>
                                </a>
                            </li>
                           
                            <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                <a href="@{{ pager.url(pager.current_page + 10) }}">
                                    ...
                                </a>
                            </li>
                        
                            <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                <a ng-click="getDataWithURL(pager.next_page_url)" aria-label="Next">
                                    <span aria-hidden="true">Next</span>
                                </a>
                            </li>

                            <li ng-if="pager.current_page !== pager.last_page">
                                <a ng-click="getDataWithURL(pager.last_page_url)" aria-label="Previous">
                                    <span aria-hidden="true">Last</span>
                                </a>
                            </li>

                        </ul>
                    </div><!-- /.box-footer -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

@endsection