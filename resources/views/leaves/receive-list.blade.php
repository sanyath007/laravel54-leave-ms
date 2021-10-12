@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            ลงรับใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ลงรับใบลา</li>
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
                    <div class="box-header with-border">
                        <h3 class="box-title">ลงรับใบลา</h3>
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

                        <table class="table table-bordered table-striped" style="font-size: 14px; margin: 10px auto;">
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
                                            @{{ leave.leave_type.name }}
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
                                        </p>
                                    </td>
                                    <td style="text-align: center;">
                                        <form action="{{ url('/leaves/receive') }}" method="POST">
                                            <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                                            {{ csrf_field() }}

                                            <button type="submit" class="btn btn-primary btn-sm">รับใบลา</button>
                                        </form>
                                    </td>             
                                </tr>
                            </tbody>
                        </table>

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