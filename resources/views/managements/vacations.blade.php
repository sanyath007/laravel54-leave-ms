@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            สรุปวันลาสะสม
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">สรุปวันลาสะสม</li>
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
            getVacations();
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

                        <div class="box-body">
                            <div class="col-md-4" ng-show="{{ Auth::user()->memberOf->duty_id }} == 1 || {{ Auth::user()->person_id }} == '1300200009261'">
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
                                            getVacations();
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

                            <div class="col-md-4" ng-show="{{ Auth::user()->memberOf->duty_id }} == 1 || {{ Auth::user()->person_id }} == '1300200009261'">
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
                                            getVacations();
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>งาน</label>
                                    <select
                                        id="division"
                                        name="division"
                                        ng-model="cboDivision"
                                        class="form-control select2"
                                        style="width: 100%; font-size: 12px;"
                                        ng-change="getVacations()"
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

                            <!-- // TODO: should use datepicker instead -->
                            <div class="form-group col-md-6">
                                <label>ปีงบประมาณ</label>
                                <select
                                    id="cboYear"
                                    name="cboYear"
                                    ng-model="cboYear"
                                    class="form-control"
                                    ng-change="getVacations()"
                                >
                                    <option value="">-- ทั้งหมด --</option>
                                    <option ng-repeat="y in budgetYearRange" value="@{{ y }}">
                                        @{{ y }}
                                    </option>
                                </select>
                            </div><!-- /.form group -->

                            <div class="col-md-6">                            
                                <div class="form-group">
                                    <label>ค้นหาชื่อบุคลากร</label>
                                    <input
                                        type="text"
                                        id="searchKeyword"
                                        name="searchKeyword"
                                        ng-model="searchKeyword"
                                        ng-keyup="getVacations()"
                                        class="form-control">
                                </div><!-- /.form group -->
                            </div>

                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">สรุปวันลาสะสม ปีงบประมาณ @{{ cboYear }}</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;">#</th>
                                    <th style="text-align: left;">ชื่อ-สกุล</th>
                                    <th style="width: 20%; text-align: center;">ตำแหน่ง</th>
                                    <th style="width: 8%; text-align: center;">ลาป่วย</th>
                                    <th style="width: 8%; text-align: center;">ลากิจ</th>
                                    <th style="width: 8%; text-align: center;">ลาคลอด</th>
                                    <th style="width: 8%; text-align: center;">ลาพักผ่อน</th>
                                    <th style="width: 10%; text-align: center;">ลาเพื่อดูแลบุตร<br>และภริยาที่คลอดบุตร</th>
                                    <th style="width: 8%; text-align: center;">ลาอุปสมบท/<br>ประกอบพิธีฮัจย์</th>
                                    <th style="width: 8%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, person) in data">
                                    <td style="text-align: center;">@{{ pager.from + index }}</td>
                                    <td>
                                        @{{ person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname }}
                                    </td>
                                    <td>
                                        @{{ person.position.position_name + person.academic.ac_name }}
                                    </td>
                                    <td style="text-align: center;">@{{ 60 - person.leave_stats.ill_days }}</td>
                                    <td style="text-align: center;">@{{ 45 - person.leave_stats.per_days }}</td>
                                    <td style="text-align: center;">@{{ person.person_sex == '1' ? '-' : 90 - person.leave_stats.lab_days }}</td>
                                    <!-- TODO: vacation remaining days should be retrieved from vacations table -->
                                    <td style="text-align: center;">@{{ !person.vacation ? 10 : person.vacation.all_days - person.leave_stats.vac_days }}</td>
                                    <td style="text-align: center;">@{{ person.person_sex == '2' ? '-' : 15 - person.leave_stats.lab_days }}</td>
                                    <td style="text-align: center;">@{{ 120 - person.leave_stats.ord_days }}</td>
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
                                            <form
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
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-md-4">
                                หน้า @{{ pager.current_page }} จาก @{{ pager.last_page }}
                            </div>
                            <div class="col-md-4" style="text-align: center;">
                                จำนวน @{{ pager.total }} รายการ
                            </div>
                            <div class="col-md-4">
                                <ul class="pagination pagination-sm no-margin pull-right">

                                    <li ng-if="pager.current_page !== 1">
                                        <a ng-click="getVacationsWithURL(pager.path+ '?page=1', setVacations)" aria-label="Previous">
                                            <span aria-hidden="true">First</span>
                                        </a>
                                    </li>
                                
                                    <li ng-class="{'disabled': (pager.current_page==1)}">
                                        <a ng-click="getVacationsWithURL(pager.prev_page_url, setVacations)" aria-label="Prev">
                                            <span aria-hidden="true">Prev</span>
                                        </a>
                                    </li>

                                    <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                        <a href="@{{ pager.url(pager.current_page + 10) }}">
                                            ...
                                        </a>
                                    </li> -->
                                
                                    <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                        <a ng-click="getVacationsWithURL(pager.next_page_url, setVacations)" aria-label="Next">
                                            <span aria-hidden="true">Next</span>
                                        </a>
                                    </li>

                                    <li ng-if="pager.current_page !== pager.last_page">
                                        <a ng-click="getVacationsWithURL(pager.path+ '?page=' +pager.last_page, setVacations)" aria-label="Previous">
                                            <span aria-hidden="true">Last</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- /.box-footer -->

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