@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            ยกเลิกใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ยกเลิกใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="onCancelLoad($event)">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label>ปีงบประมาณ</label>
                                    <select
                                        id="cboYear"
                                        name="cboYear"
                                        ng-model="cboYear"
                                        class="form-control"
                                        ng-change="getAll($event)"
                                    >
                                        <option value="">-- ทั้งหมด --</option>
                                        <option ng-repeat="y in budgetYearRange" value="@{{ y }}">
                                            @{{ y }}
                                        </option>
                                    </select>
                                </div><!-- /.form group -->
                                <div class="form-group col-md-6">
                                    <label>ประเภทการลา</label>
                                    <select
                                        id="cboLeaveType"
                                        name="cboLeaveType"
                                        ng-model="cboLeaveType"
                                        class="form-control"
                                        ng-change="getAll($event)"
                                    >
                                        <option value="">-- ทั้งหมด --</option>
                                        @foreach($leave_types as $type)

                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div><!-- /.form group -->
                                <div class="form-group col-md-6" ng-show="false">
                                    <label>สถานะ</label>

                                    <select
                                            id="cboLeaveStatus"
                                            name="cboLeaveStatus"
                                            ng-model="cboLeaveStatus"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;">

                                        <option value="" selected="selected">-- เลือกสถานะ --</option>
                                        @foreach($statuses as $key => $status)

                                            <option value="{{ $key }}">
                                                {{ $status }}
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">ยกเลิกใบลา</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <!-- <div class="form-group pull-right">
                            <input  type="text" 
                                    id="table_search" 
                                    name="table_search"
                                    ng-model="searchKeyword"
                                    class="form-control pull-right" 
                                    placeholder="ค้นหาเลขที่ใบส่งของ">                                       
                        </div> -->

                        <table class="table table-bordered table-striped" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th style="width: 5%; text-align: center;">#</th>
                                    <th>ประเภทการลา</th>
                                    <!-- <th style="text-align: left;">ชื่อครุภัณฑ์</th> -->
                                    <th style="width: 20%; text-align: center;">วันที่ลา</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 15%; text-align: center;">วันที่ลงทะเบียน</th>
                                    <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
                                    <th style="width: 15%; text-align: center;">สถานะ</th>
                                    <th style="width: 5%; text-align: center;">ไฟล์แนบ</th>
                                    <th style="width: 6%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, leave) in leaves">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td>@{{ leave.leave_type.name }}</td>
                                    <td style="text-align: center;">
                                        <span>@{{ leave.start_date | thdate }} - </span>
                                        <span>@{{ leave.end_date | thdate }}</span>
                                    </td>
                                    <td style="text-align: center;">@{{ leave.leave_days }}</td>
                                    <td style="text-align: center;">@{{ leave.leave_date | thdate }}</td>
                                    <td style="text-align: center;">@{{ leave.year }}</td>
                                    <td style="text-align: center;">
                                        <span class="label label-primary" ng-show="leave.status == 0">
                                            อยู่ระหว่างการสร้างเอกสาร
                                        </span>
                                        <span class="label label-primary" ng-show="leave.status == 1">
                                            อยู่ระหว่างดำเนินการ
                                        </span>
                                        <span class="label label-primary" ng-show="leave.status == 2">
                                            อยู่ระหว่างการแก้ไข
                                        </span>
                                        <span class="label label-info" ng-show="leave.status == 3">
                                            รับเอกสารแล้ว
                                        </span>
                                        <span class="label label-success" ng-show="leave.status == 4">
                                            ผ่านการอนุมัติ
                                        </span>
                                        <span class="label label-warning" ng-show="leave.status == 5">
                                            ไม่ผ่านการอนุมัติ
                                        </span>
                                        <span class="label label-danger" ng-show="leave.status == 9">
                                            ยกเลิก
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                                            class="btn btn-success btn-xs" 
                                            title="ไฟล์แนบ"
                                            target="_blank"
                                            ng-show="leave.attachment">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td style="text-align: center;">
                                        <a  ng-click="showCancelForm(leave)"
                                            class="btn btn-danger btn-xs"
                                            title="ยกเลิกใบลา">
                                            ยกเลิก
                                        </a>
                                    </td>             
                                </tr>
                            </tbody>
                        </table>

                        @include('leaves._cancel-form')

                        <ul class="pagination pagination-sm no-margin pull-right">
                            <li ng-if="debtPager.current_page !== 1">
                                <a href="#" ng-click="getDebtWithURL(debtPager.first_page_url)" aria-label="Previous">
                                    <span aria-hidden="true">First</span>
                                </a>
                            </li>
                        
                            <li ng-class="{'disabled': (debtPager.current_page==1)}">
                                <a href="#" ng-click="getDebtWithURL(debtPager.prev_page_url)" aria-label="Prev">
                                    <span aria-hidden="true">Prev</span>
                                </a>
                            </li>

                            <li ng-repeat="i in debtPages" ng-class="{'active': debtPager.current_page==i}">
                                <a href="#" ng-click="getDebtWithURL(debtPager.path + '?page=' +i)">
                                    @{{ i }}
                                </a>
                            </li>

                            <!-- <li ng-if="debtPager.current_page < debtPager.last_page && (debtPager.last_page - debtPager.current_page) > 10">
                                <a href="#" ng-click="debtPager.path">
                                    ...
                                </a>
                            </li> -->

                            <li ng-class="{'disabled': (debtPager.current_page==debtPager.last_page)}">
                                <a href="#" ng-click="getDebtWithURL(debtPager.next_page_url)" aria-label="Next">
                                    <span aria-hidden="true">Next</span>
                                </a>
                            </li>

                            <li ng-if="debtPager.current_page !== debtPager.last_page">
                                <a href="#" ng-click="getDebtWithURL(debtPager.last_page_url)" aria-label="Previous">
                                    <span aria-hidden="true">Last</span>
                                </a>
                            </li>
                        </ul>
                    </div><!-- /.box-body -->

                    <!-- Loading (remove the following to stop the loading)-->
                    <div ng-show="loading" class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <!-- end loading -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
        });
    </script>

@endsection