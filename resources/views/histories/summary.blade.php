@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            ประวัติการลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">ประวัติการลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="historyCtrl" ng-init="getData()">

        <div class="row">
            <div class="col-md-12">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body" style="background-color: #F5F7FA;">

                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                <div class="row">
                    <div class="col-md-4">
                        <div class="box box-success">
                            <div class="box-header">
                                <h3 class="box-title">สถิติการลา ปีงบประมาณ</h3>
                            </div>
                            <div class="box-body">
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="box box-danger">
                            <div class="box-header">
                                <h3 class="box-title">รายละเอียดข้อมูลการลา</h3>
                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label> ประเภทการลา :</label>
                                        <select
                                            id="cboLeaveType"
                                            name="cboLeaveType"
                                            ng-model="cboLeaveType"
                                            ng-change="getData();"
                                            class="form-control"
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

                                <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 1rem;">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%; text-align: center;">#</th>
                                            <th>ประเภทการลา</th>
                                            <th style="width: 30%; text-align: center;">วันที่ลา</th>
                                            <th style="width: 10%; text-align: center;">จน.วัน</th>
                                            <th style="width: 10%; text-align: center;">สถานะ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(index, leave) in leaves" ng-show="leaves.length > 0">
                                            <td style="text-align: center;">
                                                @{{ index+pager.from }}
                                            </td>
                                            <td style="text-align: left;">
                                                @{{ leave.type.name }}
                                                <p ng-show="leave.cancellation.length > 0" style="color: red;">
                                                    ยกเลิกวันลา <span>@{{ leave.cancellation[0].days }} วัน</span>
                                                </p>
                                            </td>
                                            <td style="text-align: center;">
                                                <span>@{{ leave.start_date | thdate }} - </span>
                                                <span>@{{ leave.end_date | thdate }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                @{{ leave.leave_days | currency:"":0 }}
                                                <p ng-show="leave.cancellation.length > 0" style="color: red;">
                                                    (ลา @{{ leave.leave_days - leave.cancellation[0].days }})
                                                </p>
                                            </td>
                                            <td style="text-align: left;">
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
                                                <span class="label label-danger" ng-show="leave.status == 9 && leave.leave_days - leave.cancellation[0].days == 0">
                                                    ยกเลิก
                                                </span>
                                                <span class="label label-success" ng-show="leave.status == 9 && leave.leave_days - leave.cancellation[0].days > 0">
                                                    ผ่านการอนุมัติ
                                                </span>
                                            </td>
                                        </tr>
                                        <tr ng-show="leaves.length == 0">
                                            <td style="text-align: center;" colspan="5">
                                                -- ไม่มีข้อมูลการลา --
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <ul class="pagination pagination-sm no-margin pull-right">
                                    <li ng-if="pager.current_page !== 1">
                                        <a href="#" ng-click="getDataWithURL(pager.path+ '?page=1')" aria-label="Previous">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (pager.current_page==1)}">
                                        <a href="#" ng-click="getDataWithURL(pager.prev_page_url)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>

                                    <!-- <li ng-repeat="i in debtPages" ng-class="{'active': pager.current_page==i}">
                                        <a href="#" ng-click="getDataWithURL(pager.path + '?page=' +i)">
                                            @{{ i }}
                                        </a>
                                    </li> -->

                                    <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                        <a href="#" ng-click="pager.path">
                                            ...
                                        </a>
                                    </li> -->

                                    <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                        <a href="#" ng-click="getDataWithURL(pager.next_page_url)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="pager.current_page !== pager.last_page">
                                        <a href="#" ng-click="getDataWithURL(pager.last_page_url)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!-- /.row -->

                <!-- Loading (remove the following to stop the loading)-->
                <div ng-show="loading" class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <!-- end loading -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('#cal_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true
            });
        });
    </script>

@endsection