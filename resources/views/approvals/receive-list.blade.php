@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รับเอกสาร
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รับเอกสาร</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="onReceiveLoad($event)">

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
                            <li class="active"><a href="#approve" data-toggle="tab">
                                <i class="fa fa-calendar-check-o" aria-hidden="true"></i>
                                รายการขออนุมัติใบลา
                                <span class="badge badge-light">@{{ leaves.length }}</span>
                            </a></li>
                            <li><a href="#cancel" data-toggle="tab">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                                รายการขอยกเลิกวันลา
                                <span class="badge badge-light">@{{ cancellations.length }}</span>
                            </a></li>
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
                                            <th style="width: 6%; text-align: center;">การอนุมัติ</th>
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
                                                    <span class="label label-primary" ng-show="leave.status == 0">
                                                        อยู่ระหว่างดำเนินการ
                                                    </span>
                                                    <span class="label label-info" ng-show="leave.status == 1">
                                                        หัวหน้าลงความเห็นแล้ว
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
                                                    <span class="label label-default" ng-show="leave.status == 7">
                                                        หัวหน้าไม่อนุญาต
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
                                                <a  ng-click="showApproval()"
                                                    class="btn btn-default btn-sm" 
                                                    title="รายละเอียด"
                                                    target="_blank">
                                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td style="text-align: center;">
                                                <form action="{{ url('/approvals/receive') }}" method="POST" ng-show="leave.status == 1">
                                                    <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                        รับเอกสาร
                                                    </button>
                                                </form>
                                                <form action="{{ url('/approvals/status') }}" method="POST" ng-show="leave.status == 2">
                                                    <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                                                    <input type="hidden" id="status" name="status" value="1" />
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-remove" aria-hidden="true"></i>
                                                        ยกเลิก
                                                    </button>
                                                </form>
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
                                        <a href="#" ng-click="getDebtWithURL(pager.path + '?page=' +i)">
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
                                        <a href="#" ng-click="getDataWithURL(.path+ '?page=1' +pager.last_page, setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- /.tab-pane -->
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
                                                <form action="{{ url('/cancellations/receive') }}" method="POST">
                                                    <input type="hidden" id="_id" name="_id" value="@{{ cancel.cancellation[0].id }}" />
                                                    <input type="hidden" id="leave_id" name="leave_id" value="@{{ cancel.id }}" />
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                        รับเอกสาร
                                                    </button>
                                                </form>
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