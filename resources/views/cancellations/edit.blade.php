@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขรายการ
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item">ยกเลิกวันลา</li>
            <li class="breadcrumb-item active">แก้ไขรายการ</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="cancelCtrl" ng-init="onEdit({{ $leave }})">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">แก้ไขรายการ</h3>
                    </div>

                        <form id="frmEditCancel" name="frmEditCancel" action="{{ url('/cancellations/update') }}" method="POST">
                            <input type="hidden" id="id" name="id" value="@{{ leave.cancellation[0].id }}" />
                            <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                            {{ csrf_field() }}

                            <div class="box-body">

                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <p>
                                            ช้าพเจ้า 
                                            <span style="font-weight: bold;; margin-right: 5px;">
                                                @{{ leave.person.person_firstname + ' ' + leave.person.person_lastname }}
                                            </span>
                                            ตำแหน่ง 
                                            <span style="font-weight: bold;">
                                                @{{ leave.person.position.position_name }}@{{ leave.person.academic ? leave.person.academic.ac_name : '' }}
                                            </span>
                                        </p>
                                        <p>
                                            สังกัด 
                                            <span style="font-weight: bold;; margin-right: 5px;">
                                                @{{ leave.person.member_of.depart.depart_name }}
                                            </span>
                                        </p>
                                        <p>
                                            ได้รับอนุญาตไห้
                                            <span style="font-weight: bold; margin-right: 5px;">@{{ leave.type.name }}</span>
                                            ตั้งแต่วันที่ 
                                            <span style="font-weight: bold;; margin-right: 5px;">@{{ leave.start_date | thdate }}</span>
                                            ถึงวันที่
                                            <span style="font-weight: bold;">@{{ leave.end_date | thdate }}</span>
                                            มีกำหนด
                                            <span style="font-weight: bold;">@{{ leave.leave_days | currency:'':1 }}</span> วัน
                                        </p>
                                    </div>
                                </div><!-- /.panel -->

                                <div class="row">
                                    <div
                                        class="form-group col-md-12"
                                        ng-class="{ 'has-error has-feedback': checkValidate(cancellation, 'reason') }"
                                    >
                                        <label for="">เนื่องจาก (ระบุเหตุผลการยกเลิก)</label>
                                        <textarea
                                            id="reason"
                                            name="reason"
                                            ng-model="cancellation.reason"
                                            cols="3"
                                            class="form-control"
                                        ></textarea>
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'reason')">
                                            @{{ formError.errors.end_period[0] }}
                                        </span>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="">จึงขอยกเลิกวันลา</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="@{{ leave.type.name }}"
                                            readonly="readonly"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div
                                        class="form-group col-md-6"
                                        ng-class="{'has-error has-feedback': checkValidate(cancellation, 'days')}"
                                    >
                                        <label for="">จำนวน (วัน)</label>
                                        <input
                                            type="text"
                                            id="days"
                                            name="days"
                                            ng-model="cancellation.days"
                                            class="form-control"
                                            value="@{{ cancellation.days }}"
                                            readonly="readonly"
                                        />
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'days')">
                                            @{{ formError.errors.days[0] }}
                                        </span>
                                    </div>
                                    <div
                                        class="form-group col-md-6"
                                        ng-class="{'has-error has-feedback': checkValidate(cancellation, 'working_days')}"
                                    >
                                        <label for="">จำนวน (วันทำการ)</label>
                                        <input
                                            type="text"
                                            id="working_days"
                                            name="working_days"
                                            ng-model="cancellation.working_days"
                                            class="form-control"
                                            value="@{{ cancellation.working_days }}"
                                            readonly="readonly"
                                        />
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'working_days')">
                                            @{{ formError.errors.working_days[0] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div
                                        class="form-group col-md-6"
                                        ng-class="{'has-error has-feedback': checkValidate(cancellation, 'start_date')}"
                                    >
                                        <label for="">ตั้งแต่วันที่</label>
                                        <input
                                            type="text"
                                            id="start_date"
                                            name="start_date"
                                            ng-model="cancellation.start_date"
                                            class="form-control"
                                        />
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'start_date')">
                                            @{{ formError.errors.start_date[0] }}
                                        </span>
                                    </div>
                                    <div
                                        class="form-group col-md-6"
                                        ng-class="{'has-error has-feedback': checkValidate(cancellation, 'start_period')}"
                                    >
                                        <label>ช่วงเวลา :</label>
                                        <select
                                            id="start_period"
                                            name="start_period"
                                            ng-model="cancellation.start_period"
                                            class="form-control"
                                        >
                                            <option value="">-- เลือกช่วงเวลา --</option>
                                            @foreach($periods as $key => $period)

                                                <option value="{{ $key }}">
                                                    {{ $period }}
                                                </option>

                                            @endforeach
                                        </select>
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'start_period')">
                                            @{{ formError.errors.start_period[0] }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div
                                        class="form-group col-md-6"
                                        ng-class="{'has-error has-feedback': checkValidate(cancellation, 'end_date')}"
                                    >
                                        <label for="">ถึงวันที่</label>
                                        <input
                                            type="text"
                                            id="end_date"
                                            name="end_date"
                                            ng-model="cancellation.end_date"
                                            class="form-control"
                                        />
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'end_date')">
                                            @{{ formError.errors.end_date[0] }}
                                        </span>
                                    </div>
                                    <div
                                        class="form-group col-md-6"
                                        ng-class="{ 'has-error has-feedback': checkValidate(cancellation, 'end_period') }"
                                    >
                                        <label>ช่วงเวลา :</label>
                                        <select
                                            id="end_period"
                                            name="end_period"
                                            ng-model="cancellation.end_period"
                                            ng-change="calculateLeaveDays('start_date', 'end_date', cancellation.end_period)"
                                            class="form-control"
                                        >
                                            <option value="">-- เลือกช่วงเวลา --</option>
                                            @foreach($periods as $key => $period)

                                                <option value="{{ $key }}">
                                                    {{ $period }}
                                                </option>

                                            @endforeach
                                        </select>
                                        <span class="help-block" ng-show="checkValidate(cancellation, 'end_period')">
                                            @{{ formError.errors.end_period[0] }}
                                        </span>
                                    </div>
                                </div>

                            </div><!-- /.box-body -->
                            <div class="box-footer clearfix">
                                <a href="{{ url('/cancellations/cancel') }}" class="btn btn-danger pull-right">
                                    ยกเลิก
                                </a>
                                <button
                                    type="submit"
                                    class="btn btn-warning pull-right"
                                    ng-click="formValidate($event, '/cancellations/validate', cancellation, 'frmEditCancel', update)"
                                    style="margin-right: 5px;"
                                >
                                    แก้ไข
                                </button>
                            </div><!-- /.box-footer -->
                        </form>
                
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
