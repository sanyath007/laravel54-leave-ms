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
    <section
        class="content"
        ng-controller="manageCtrl"
        ng-init="
            initForms({
                departs: {{ $departs }},
                divisions: {{ $divisions }}
            });
            getLeaves();
        "
    >

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <input type="hidden" name="user" id="user" value="{{ Auth::user()->person_id }}" />
                        <input type="hidden" name="faction_id" id="faction_id" value="{{ Auth::user()->memberOf->faction_id }}" />
                        <input type="hidden" name="depart_id" id="depart_id" value="{{ Auth::user()->memberOf->depart_id }}" />

                        <div class="box-body" style="padding-bottom: 0;">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label>ปีงบประมาณ</label>
                                    <select
                                        id="cboYear"
                                        name="cboYear"
                                        ng-model="cboYear"
                                        class="form-control"
                                        ng-change="getLeaves($event)"
                                    >
                                        <option value="">-- ทั้งหมด --</option>
                                        <option ng-repeat="y in budgetYearRange" value="@{{ y }}">
                                            @{{ y }}
                                        </option>
                                    </select>
                                </div><!-- /.form group -->
                                <div class="form-group col-md-3">
                                    <label>ประเภทการลา</label>
                                    <select
                                        id="cboLeaveType"
                                        name="cboLeaveType"
                                        ng-model="cboLeaveType"
                                        class="form-control"
                                        ng-change="getLeaves($event)"
                                    >
                                        <option value="">-- ทั้งหมด --</option>
                                        @foreach($leave_types as $type)

                                            <option value="{{ $type->id }}">
                                                {{ $type->name }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div><!-- /.form group -->
                                <div class="form-group col-md-3">
                                    <label>วันที่ลา</label>
                                    <div class="input-group">
                                        <input
                                            id="dtpSdate"
                                            name="dtpSdate"
                                            ng-model="dtpSdate"
                                            class="form-control"
                                        />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-danger" ng-click="clearDateValue($event, 'dtpSdate');">
                                                เคลียร์
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>ถึงวันที่</label>
                                    <div class="input-group">
                                        <input
                                            id="dtpEdate"
                                            name="dtpEdate"
                                            ng-model="dtpEdate"
                                            class="form-control"
                                        />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-danger" ng-click="clearDateValue($event, 'dtpEdate');">
                                                เคลียร์
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div><!-- /.row -->

                            <div class="row" ng-show="{{ Auth::user()->memberOf->duty_id }} == 1 || {{ Auth::user()->person_id }} == '1300200009261'">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>กลุ่มภารกิจ</label>
                                        <select
                                            id="faction"
                                            name="faction"
                                            ng-model="cboFaction"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                            ng-change="
                                                onFactionSelected(cboFaction);
                                                getLeaves();
                                            "
                                        >
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>
                                            @foreach($factions as $faction)
                                                <option value="{{ $faction->faction_id }}">
                                                    {{ $faction->faction_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div><!-- /.form group -->
                                </div><!-- /.col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>กลุ่มงาน</label>
                                        <select
                                            id="depart"
                                            name="depart"
                                            ng-model="cboDepart"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                            ng-change="
                                                onDepartSelected(cboDepart);
                                                getLeaves();
                                            "
                                        >
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>
                                            <option
                                                ng-repeat="depart in forms.departs"
                                                value="@{{ depart.depart_id }}"
                                            >
                                                @{{ depart.depart_name }}
                                            </option>
                                        </select>
                                    </div><!-- /.form group -->
                                </div><!-- /.col -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>งาน</label>
                                        <select
                                            id="division"
                                            name="division"
                                            ng-model="cboDivision"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                            ng-change="getLeaves()"
                                        >
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>
                                            <option
                                                ng-repeat="division in forms.divisions"
                                                value="@{{ division.ward_id }}"
                                            >
                                                @{{ division.ward_name }}
                                            </option>
                                        </select>
                                    </div><!-- /.form group -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>สถานะ</label>
                                        <select
                                            id="cboLeaveStatus"
                                            name="cboLeaveStatus"
                                            ng-model="cboLeaveStatus"
                                            class="form-control select2"
                                            style="width: 100%; font-size: 12px;"
                                            ng-change="getLeaves()"
                                        >
                                            <option value="" selected="selected">-- กรุณาเลือก --</option>
                                            <option value="0">อยู่ระหว่างดำเนินการ</option>
                                            <option value="1">หัวหน้าลงความเห็นแล้ว</option>
                                            <option value="2">รับเอกสารแล้ว</option>
                                            <option value="3">ผ่านการอนุมัติ</option>
                                            <option value="4">ไม่ผ่านการอนุมัติ</option>
                                            <option value="7">หัวหน้าไม่อนุญาต</option>
                                            <option value="5">อยู่ระหว่างการยกเลิก</option>
                                        </select>
                                    </div><!-- /.form group -->
                                </div><!-- /.col -->
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>ชื่อบุคลากร</label>
                                        <input
                                            type="text"
                                            id="keyword"
                                            name="keyword"
                                            ng-model="keyword"
                                            ng-keyup="getLeaves()"
                                            class="form-control"
                                        />
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">รายการใบลา</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="width: 5%; text-align: center;">ปีงบ</th>
                                    <th style="width: 8%; text-align: center;">วันที่ลงทะเบียน</th>
                                    <th style="text-align: left;">ชื่อ-สกุลผู้ลา</th>
                                    <th style="width: 20%;">ตำแหน่ง</th>
                                    <th style="width: 8%; text-align: center;">ประเภทการลา</th>
                                    <th style="width: 10%; text-align: center;">วันที่ลา</th>
                                    <th style="width: 4%; text-align: center;">จน.วัน</th>
                                    <th style="width: 12%; text-align: center;">สถานะ</th>
                                    <th style="width: 8%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, leave) in leaves">
                                    <td style="text-align: center;">@{{ index+pager.from }}</td>
                                    <td style="text-align: center;">@{{ leave.year }}</td>
                                    <td style="text-align: center;">@{{ leave.leave_date | thdate }}</td>
                                    <td>
                                        @{{ leave.person.prefix.prefix_name }}@{{ leave.person.person_firstname }} @{{ leave.person.person_lastname }}
                                        <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                                            class="btn btn-default btn-xs" 
                                            title="ไฟล์แนบ"
                                            target="_blank"
                                            ng-show="leave.attachment">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                    <td>@{{ leave.person.position.position_name }}@{{ leave.person.academic && leave.person.academic.ac_name }}</td>
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
                                            หัวหน้าลงความเห็นแล้ว
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
                                        <div style="display: flex; justify-content: center; gap: 2px;">
                                            <a  href="{{ url('/leaves/detail') }}/@{{ leave.id }}"
                                                class="btn btn-primary btn-xs" 
                                                title="รายละเอียด">
                                                <i class="fa fa-search"></i>
                                            </a>
                                            <a  ng-click="edit(leave.id)"
                                                ng-show="leave.status == 0 || (leave.status == 1 && {{ Auth::user()->memberOf->duty_id }} == 2)"
                                                class="btn btn-warning btn-xs"
                                                title="แก้ไขรายการ">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <!-- <form
                                                id="frmDelete"
                                                method="POST"
                                                action="{{ url('/leaves/delete') }}"
                                                ng-show="leave.status == 0 || (leave.status == 1 && {{ Auth::user()->memberOf->duty_id }} == 2)"
                                            >
                                                {{ csrf_field() }}
                                                <button
                                                    type="submit"
                                                    ng-click="delete($event, leave.id)"
                                                    class="btn btn-danger btn-xs"
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form> -->
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-4">
                                หน้า @{{ pager.current_page }} จาก @{{ pager.last_page }}
                            </div>
                            <div class="col-md-4" style="text-align: center;">
                                จำนวน @{{ pager.total }} รายการ
                            </div>
                            <div class="col-md-4">
                                <ul class="pagination pagination-sm no-margin pull-right" ng-show="pager.last_page > 1">
                                    <li ng-if="pager.current_page !== 1">
                                        <a href="#" ng-click="getLeavesWithUrl($event, pager.path+ '?page=1', setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (pager.current_page==1)}">
                                        <a href="#" ng-click="getLeavesWithUrl($event, pager.prev_page_url, setLeaves)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>

                                    <!-- <li ng-repeat="i in debtPages" ng-class="{'active': pager.current_page==i}">
                                        <a href="#" ng-click="getLeavesWithUrl(pager.path + '?page=' +i)">
                                            @{{ i }}
                                        </a>
                                    </li> -->

                                    <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                        <a href="#" ng-click="pager.path">
                                            ...
                                        </a>
                                    </li> -->

                                    <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                        <a href="#" ng-click="getLeavesWithUrl($event, pager.next_page_url, setLeaves)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="pager.current_page !== pager.last_page">
                                        <a href="#" ng-click="getLeavesWithUrl($event, pager.path+ '?page=' +pager.last_page, setLeaves)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.row -->
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