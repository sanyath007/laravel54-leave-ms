@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            อนุมัติใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">อนุมัติใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="onApproveLoad($event)">

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

                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#approve" data-toggle="tab">รายการขออนุมัติใบลา</a></li>
                            <li><a href="#cancel" data-toggle="tab">รายการขอยกเลิกวันลา</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="approve">
                                <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%; text-align: center;">#</th>
                                            <th>รายละเอียด</th>
                                            <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
                                            <th style="width: 10%; text-align: center;">วันที่ลงทะเบียน</th>
                                            <th style="width: 6%; text-align: center;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(index, leave) in leaves">
                                            <td style="text-align: center;">@{{ index+pager.from }}</td>
                                            <td>
                                                <h4 style="margin: 2px auto;">
                                                    @{{ leave.type.name }}
                                                    @{{ leave.person.prefix.prefix_name + leave.person.person_firstname + ' ' + leave.person.person_lastname }}
                                                </h4>
                                                <p style="color: grey; margin: 0px auto;">
                                                    ระหว่างวันที่ <span>@{{ leave.start_date | thdate }} - </span>
                                                    ถึงวันที่ <span>@{{ leave.end_date | thdate }}</span>
                                                    จำนวน <span>@{{ leave.leave_days }}</span> วัน
                                                    <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                                                        title="ไฟล์แนบ"
                                                        target="_blank"
                                                        ng-show="leave.attachment"
                                                        class="btn btn-default btn-xs"
                                                    >
                                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    </a>
                                                </p>
                                            </td>
                                            <td style="text-align: center;">@{{ leave.year }}</td>
                                            <td style="text-align: center;">
                                                <p style="margin: 0px auto;">@{{ leave.leave_date | thdate }}</p>
                                                <p style="margin: 0px auto;">
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
                                                </p>
                                            </td>
                                            <td style="text-align: center;">
                                                <a  ng-click="showApproveForm(leave, 1)" 
                                                    ng-show="(leave.status!==4 || leave.status!==3)" 
                                                    class="btn btn-warning btn-sm"
                                                    title="ลงนามอนุมัติการลา">
                                                    ลงนาม
                                                </a>
                                            </td>             
                                        </tr>
                                    </tbody>
                                </table>

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
                            </div>
                            <div class="tab-pane" id="cancel">
                                <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%; text-align: center;">#</th>
                                            <th>รายละเอียดการลา</th>
                                            <th style="width: 35%;">ขอยกเลิกวันลา</th>
                                            <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
                                            <th style="width: 10%; text-align: center;">วันที่ลงทะเบียน</th>
                                            <th style="width: 6%; text-align: center;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(index, cancel) in cancellations">
                                            <td style="text-align: center;">@{{ index+cancelPager.from }}</td>
                                            <td>
                                                <h4 style="margin: 2px auto;">
                                                    ขอยกเลิกวัน@{{ cancel.type.name }}
                                                    @{{ cancel.person.prefix.prefix_name + cancel.person.person_firstname + ' ' + cancel.person.person_lastname }}
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
                                                <span style="font-weight: bold;">วันที่</span> @{{ cancel.cancellation[0].cancel_date | thdate }}
                                                <span style="font-weight: bold;">จำนวน</span> @{{ cancel.cancellation[0].days }} วัน
                                                <p style="color: grey; margin: 0px auto;">
                                                    <span style="font-weight: bold;">เนื่องจาก</span> @{{ cancel.cancellation[0].reason }}
                                                </p>
                                            </td>
                                            <td style="text-align: center;">@{{ cancel.year }}</td>
                                            <td style="text-align: center;">
                                                <p style="margin: 0px auto;">@{{ cancel.leave_date | thdate }}</p>
                                                <p style="margin: 0px auto;">
                                                    <span class="label label-warning" ng-show="cancel.status == 5">
                                                        อยู่ระหว่างการยกเลิก
                                                    </span>
                                                </p>
                                            </td>
                                            <td style="text-align: center;">
                                                <a  ng-click="showApproveForm(cancel, 2)" 
                                                    ng-show="(cancel.status!==4 || cancel.status!==3)" 
                                                    class="btn btn-success btn-sm"
                                                    title="ลงนามอนุมัติยกเลิกการลา">
                                                    ลงนาม
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

                        @include('leaves._approve-form')
                        @include('leaves._cancel-approval-form')

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
            $('.select2').select2()

            //Date range picker with time picker
            $('#debtDate').daterangepicker({
                timePickerIncrement: 30,
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: " , ",
                }
            }, function(e) {
                console.log(e);
            });
        });
    </script>

@endsection