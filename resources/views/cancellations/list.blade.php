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
    <section class="content" ng-controller="leaveCtrl" ng-init="onCancelLoad({{ Auth::user()->person_id }})">

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
                                        ng-change="onCancelLoad($event)"
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
                                        ng-change="onCancelLoad($event)"
                                    >
                                        <option value="">-- ทั้งหมด --</option>
                                        @foreach($leave_types as $type)

                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div><!-- /.form group -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#to-cancel-list" data-toggle="tab">
                                <i class="fa fa-check-square-o text-success" aria-hidden="true"></i>
                                รายการใบลาที่อนุมัติแล้ว
                                <span class="badge badge-light">@{{ leaves.length }}</span>
                            </a></li>
                            <li><a href="#cancelled-list" data-toggle="tab">
                                <i class="fa fa-window-close-o text-danger" aria-hidden="true"></i>
                                รายการขอยกเลิกวันลา
                                <span class="badge badge-light">@{{ cancellations.length }}</span>
                            </a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="to-cancel-list">

                                <div class="alert alert-warning alert-dismissible" style="margin: 10px 5px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-warning"></i>ท่านสามารถยกเลิกวันลาในรายการที่ผ่านการอนุมัติแล้วเท่านั้น !!
                                </div>

                                <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%; text-align: center;">#</th>
                                            <th>ประเภทการลา</th>
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
                                            <td>@{{ leave.type.name }}</td>
                                            <td style="text-align: center;">
                                                <span>@{{ leave.start_date | thdate }} - </span>
                                                <span>@{{ leave.end_date | thdate }}</span>
                                            </td>
                                            <td style="text-align: center;">@{{ leave.leave_days }}</td>
                                            <td style="text-align: center;">@{{ leave.leave_date | thdate }}</td>
                                            <td style="text-align: center;">@{{ leave.year }}</td>
                                            <td style="text-align: center;">
                                                <span class="label label-primary" ng-show="leave.status == 1">
                                                    อยู่ระหว่างดำเนินการ
                                                </span>
                                                <span class="label label-info" ng-show="leave.status == 2">
                                                    รับเอกสารแล้ว
                                                </span>
                                                <span class="label label-success" ng-show="leave.status == 3">
                                                    ผ่านการอนุมัติ
                                                </span>
                                                <span class="label label-default" ng-show="leave.status == 4">
                                                    ไม่ผ่านการอนุมัติ
                                                </span>
                                                <span class="label label-warning" ng-show="leave.status == 5">
                                                    อยู่ระหว่างการยกเลิก
                                                </span>
                                                <span class="label label-danger" ng-show="leave.status == 9">
                                                    ยกเลิก
                                                </span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                                                    class="btn btn-default btn-xs"
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

                                @include('cancellations._cancel-form')

                                <ul class="pagination pagination-sm no-margin pull-right">
                                    <li ng-if="pager.current_page !== 1">
                                        <a href="#" ng-click="getDataWithURL(pager.path+ '?page=1', setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (pager.current_page==1)}">
                                        <a href="#" ng-click="getDataWithURL(pager.prev_page_url, setLeaves)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>

                                    <!-- <li ng-repeat="i in debtPages" ng-class="{'active': pager.current_page==i}">
                                        <a href="#" ng-click="getDataWithURL(pager.path + '?page=' +i, setLeaves)">
                                            @{{ i }}
                                        </a>
                                    </li> -->

                                    <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                        <a href="#" ng-click="pager.path">
                                            ...
                                        </a>
                                    </li> -->

                                    <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                        <a href="#" ng-click="getDataWithURL(pager.next_page_url, setLeaves)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="pager.current_page !== pager.last_page">
                                        <a href="#" ng-click="getDataWithURL(pager.path+ '?page=' +pager.last_page, setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- /.tab-pane -->
                            <div class="tab-pane" id="cancelled-list">
                                <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%; text-align: center;">#</th>
                                            <th>รายละเอียดการลา</th>
                                            <th style="width: 35%;">ขอยกเลิกวันลา</th>
                                            <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
                                            <th style="width: 10%; text-align: center;">การอนุมัติ</th>
                                            <th style="width: 6%; text-align: center;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(index, cancel) in cancellations">
                                            <td style="text-align: center;">@{{ index+cancelPager.from }}</td>
                                            <td>
                                                <h4 style="margin: 2px auto;">
                                                    @{{ cancel.type.name }}
                                                </h4>
                                                <p style="color: grey; margin: 0px auto;">
                                                    ระหว่างวันที่ <span>@{{ cancel.start_date | thdate }} - </span>
                                                    ถึงวันที่ <span>@{{ cancel.end_date | thdate }}</span>
                                                    จำนวน <span>@{{ cancel.leave_days }}</span> วัน
                                                    <a  href="{{ url('/'). '/uploads/' }}@{{ cancel.attachment }}"
                                                        title="ไฟล์แนบ"
                                                        target="_blank"
                                                        ng-show="cancel.attachment"
                                                        class="btn btn-default btn-xs"
                                                    >
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </a>
                                                </p>
                                            </td>
                                            <td>
                                                <span style="font-weight: bold;">วันที่</span> 
                                                @{{ cancel.cancellation[0].start_date | thdate }} - @{{ cancel.cancellation[0].end_date | thdate }}
                                                <span style="font-weight: bold;">จำนวน</span> @{{ cancel.cancellation[0].days }} วัน
                                                <p style="color: grey; margin: 0px auto;">
                                                    <span style="font-weight: bold;">เนื่องจาก</span> @{{ cancel.cancellation[0].reason }}
                                                </p>
                                            </td>
                                            <td style="text-align: center;">@{{ cancel.year }}</td>
                                            <td style="text-align: center;">
                                                <p style="margin: 0px auto;">
                                                    <span class="label label-warning" ng-show="cancel.status == 5">
                                                        อยู่ระหว่างการยกเลิก
                                                    </span>
                                                    <span class="label label-success" ng-show="cancel.status == 8">
                                                        ผ่านการอนุมัติ (ยกเลิกบางส่วน)
                                                    </span>
                                                    <span class="label label-danger" ng-show="cancel.status == 9">
                                                        ผ่านการอนุมัติ (ยกเลิกทั้งหมด)
                                                    </span>
                                                    <p style="margin: 0px auto;">
                                                        @{{ cancel.cancel_date | thdate }}
                                                    </p>
                                                </p>
                                            </td>
                                            <td style="text-align: center;">
                                                <a  href="{{ url('/leaves/detail') }}/@{{ cancel.id }}"
                                                    class="btn btn-primary btn-xs" 
                                                    title="รายละเอียด">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                                <a  ng-click="edit(cancel.id)"
                                                    ng-show="cancel.status == 5"
                                                    class="btn btn-warning btn-xs"
                                                    title="แก้ไขรายการ">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a  ng-click="delete(cancel.id)"
                                                    ng-show="cancel.status == 5"
                                                    class="btn btn-danger btn-xs"
                                                    title="ลบรายการ">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <ul class="pagination pagination-sm no-margin pull-right">
                                    <li ng-if="cancelPager.current_page !== 1">
                                        <a href="#" ng-click="getDataWithURL(cancelPager.path+ '?page=1', setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (cancelPager.current_page==1)}">
                                        <a href="#" ng-click="getDataWithURL(cancelPager.prev_page_url, setLeaves)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>

                                    <!-- <li ng-repeat="i in debtPages" ng-class="{'active': cancelPager.current_page==i}">
                                        <a href="#" ng-click="getDataWithURL(cancelPager.path + '?page=' +i, setLeaves)">
                                            @{{ i }}
                                        </a>
                                    </li> -->

                                    <!-- <li ng-if="cancelPager.current_page < cancelPager.last_page && (cancelPager.last_page - cancelPager.current_page) > 10">
                                        <a href="#" ng-click="cancelPager.path">
                                            ...
                                        </a>
                                    </li> -->

                                    <li ng-class="{'disabled': (cancelPager.current_page==cancelPager.last_page)}">
                                        <a href="#" ng-click="getDataWithURL(cancelPager.next_page_url, setLeaves)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="cancelPager.current_page !== cancelPager.last_page">
                                        <a href="#" ng-click="getDataWithURL(cancelPager.path+ '?page=' +cancelPager.last_page, setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>

                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                        
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