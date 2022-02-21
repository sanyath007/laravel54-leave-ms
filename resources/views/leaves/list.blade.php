@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายการใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายการใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="getAll()">

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
                                <!-- <div class="form-group">
                                    <label>คำค้นหา</label>
                                    <input
                                        type="text"
                                        id="searchKey"
                                        name="searchKey"
                                        ng-model="searchKeyword"
                                        ng-keyup="getData($event)"
                                        class="form-control">
                                </div> -->

                            </div><!-- /.row -->
                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <a href="{{ url('/leaves/add') }}" class="btn btn-primary">
                                สร้างใบลา
                            </a>
                        </div>
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">รายการใบลา</h3>
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

                        <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 8%; text-align: center;">ปีงบประมาณ</th>
                                    <th style="width: 8%; text-align: center;">วันที่ลงทะเบียน</th>
                                    <th>ประเภทการลา</th>
                                    <!-- <th style="text-align: left;">ชื่อครุภัณฑ์</th> -->
                                    <th style="width: 20%; text-align: center;">วันที่ลา</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 15%; text-align: center;">สถานะ</th>
                                    <th style="width: 5%; text-align: center;">ไฟล์แนบ</th>
                                    <th style="width: 10%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, leave) in leaves">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ leave.year }}</td>
                                    <td style="text-align: center;">@{{ leave.leave_date | thdate }}</td>
                                    <td>@{{ leave.type.name }}</td>
                                    <td style="text-align: center;">
                                        <span>@{{ leave.start_date | thdate }} - </span>
                                        <span>@{{ leave.end_date | thdate }}</span>
                                        <p ng-show="leave.cancellation.length > 0" style="color: red;">
                                            ยกเลิกวันลา <span>@{{ leave.cancellation[0].days }} วัน</span>
                                        </p>
                                    </td>
                                    <td style="text-align: center;">
                                        @{{ leave.leave_days }}
                                        <p ng-show="leave.cancellation.length > 0" style="color: red;">
                                            (ลา @{{ leave.leave_days - leave.cancellation[0].days }})
                                        </p>
                                    </td>
                                    <td style="text-align: center;">
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
                                        <span class="label label-success" ng-show="leave.status == 8">
                                            ยกเลิกวันลา (บางส่วน)
                                        </span>
                                        <span class="label label-danger" ng-show="leave.status == 9">
                                            ยกเลิกวันลา (ทั้งหมด)
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
                                        <a  href="{{ url('/leaves/detail') }}/@{{ leave.id }}"
                                            class="btn btn-primary btn-xs" 
                                            title="รายละเอียด">
                                            <i class="fa fa-search"></i>
                                        </a>
                                        <a  ng-click="edit(leave.id)"
                                            ng-show="leave.status == 0"
                                            class="btn btn-warning btn-xs"
                                            title="แก้ไขรายการ">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a  ng-click="delete(leave.id)"
                                            ng-show="leave.status == 0"
                                            class="btn btn-danger btn-xs"
                                            title="ลบรายการ">
                                            <i class="fa fa-trash"></i>
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