@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายงานสรุปการลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายงานสรุปการลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="reportCtrl" ng-init="getSummary()">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <h3 class="box-title">ค้นหาข้อมูล</h3>
                    </div>

                    <form id="frmSearch" name="frmSearch" role="form">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>กลุ่มภารกิจ</label>
                                    <select
                                        id="faction"
                                        name="faction"
                                        ng-model="cboFaction"
                                        class="form-control select2"
                                        style="width: 100%; font-size: 12px;">

                                        <option value="" selected="selected">-- กรุณาเลือก --</option>

                                        @foreach($factions as $faction)

                                            <option value="{{ $faction->faction_id }}">
                                                {{ $faction->faction_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                </div><!-- /.form group -->
                            </div><!-- /.col -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>กลุ่มงาน</label>
                                    <select
                                        id="depart"
                                        name="depart"
                                        ng-model="cboDepart"
                                        class="form-control select2"
                                        style="width: 100%; font-size: 12px;">

                                        <option value="" selected="selected">-- กรุณาเลือก --</option>

                                        @foreach($departs as $depart)

                                            <option value="{{ $depart->depart_id }}">
                                                {{ $depart->depart_name }}
                                            </option>

                                        @endforeach
                                        
                                    </select>
                                </div><!-- /.form group -->
                            </div><!-- /.col -->

                            <div class="col-md-6">                            
                                <div class="form-group">
                                    <label>ปีงบประมาณ</label>
                                    <input
                                        type="text"
                                        id="year"
                                        name="year"
                                        ng-model="year"
                                        ng-keyup="getSummary($event)"
                                        class="form-control">
                                </div><!-- /.form group -->
                            </div>

                            <div class="col-md-6">                            
                                <div class="form-group">
                                    <label>ค้นหาชื่อบุคลากร</label>
                                    <input
                                        type="text"
                                        id="searchKeyword"
                                        name="searchKeyword"
                                        ng-model="searchKeyword"
                                        ng-keyup="getSummary($event)"
                                        class="form-control">
                                </div><!-- /.form group -->
                            </div>

                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->

                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">รายงานสรุปการลา</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 3%; text-align: center;" rowspan="2">#</th>
                                    <th style="text-align: left;" rowspan="2">ชื่อ-สกุล</th>
                                    <th style="width: 20%; text-align: center;" rowspan="2">ตำแหน่ง</th>
                                    <th style="text-align: center;" colspan="2">ลาป่วย</th>
                                    <th style="text-align: center;" colspan="2">ลากิจ</th>
                                    <th style="text-align: center;" colspan="2">ลาพักผ่อน</th>
                                    <th style="text-align: center;" colspan="2">ลาคลอด</th>
                                    <th style="text-align: center;" colspan="2">ลาบวช</th>
                                    <th style="text-align: center;" colspan="2">ลาไปต่างประเทศ</th>
                                </tr>
                                <tr>
                                    <th style="width: 5%; text-align: center;">ครั้ง</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 5%; text-align: center;">ครั้ง</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 5%; text-align: center;">ครั้ง</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 5%; text-align: center;">ครั้ง</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 5%; text-align: center;">ครั้ง</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                    <th style="width: 5%; text-align: center;">ครั้ง</th>
                                    <th style="width: 5%; text-align: center;">วัน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="(index, person) in data">
                                    <td style="text-align: center;">@{{ index+1 }}</td>
                                    <td>
                                        @{{ person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname }}
                                    </td>
                                    <td>
                                        @{{ person.position.position_name + person.academic.ac_name }}
                                    </td>
                                    <td style="text-align: center;">@{{ person.leave.ill_times }}</td>
                                    <td style="text-align: center;">@{{ person.leave.ill_days }}</td>
                                    <td style="text-align: center;">@{{ person.leave.per_times }}</td>
                                    <td style="text-align: center;">@{{ person.leave.per_days }}</td>
                                    <td style="text-align: center;">@{{ person.leave.vac_times }}</td>
                                    <td style="text-align: center;">@{{ person.leave.vac_days }}</td>
                                    <td style="text-align: center;">@{{ person.leave.lab_times }}</td>
                                    <td style="text-align: center;">@{{ person.leave.lab_days }}</td>
                                    <td style="text-align: center;">@{{ person.leave.ord_times }}</td>
                                    <td style="text-align: center;">@{{ person.leave.ord_days }}</td>
                                    <td style="text-align: center;">@{{ person.leave.abr_times }}</td>
                                    <td style="text-align: center;">@{{ person.leave.abr_days }}</td>
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

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        });
    </script>

@endsection