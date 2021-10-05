@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            สร้างใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">สร้างใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">สร้างใบลา</h3>
                    </div>

                    <form id="frmNewLeave" name="frmNewLeave" method="post" action="{{ url('/leaves/store') }}" role="form">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        {{ csrf_field() }}
                    
                        <div class="box-body">
                            <div class="row">

                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_place')}">
                                    <label>เขียนที่ :</label>
                                    <select id="leave_place" 
                                            name="leave_place"
                                            ng-model="leave.leave_place"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;"
                                            tabindex="2">
                                        <option value="">-- กรุณาเลือก --</option>
                                        <option value="1">โรงพยาบาลเทพรัตน์นครราชสีมา</option>
                                        <option value="2">บ้าน</option>
                                    </select>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_place')">กรุณาเลือกชนิดครุภัณฑ์</span>
                                </div>

                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_date')}">
                                    <label>วันที่ลงทะเบียน :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="leave_date"
                                            name="leave_date"
                                            class="form-control pull-right"
                                            tabindex="1">
                                    </div>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_date')">กรุณาระบุชื่อครุภัณฑ์</span>
                                </div>

                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_type')}">
                                    <label>เรื่อง :</label>
                                    <select id="leave_type" 
                                            name="leave_type"
                                            ng-model="leave.leave_type" 
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;"
                                            tabindex="2"
                                            ng-change="onSelectedType()">
                                        <option value="">-- เลือกเรื่อง --</option>

                                        @foreach($leave_types as $type)

                                            <option value="{{ $type->id }}">
                                                ขอ{{ $type->name }}
                                            </option>

                                        @endforeach

                                    </select>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_type')">กรุณาเลือกเรื่อง</span>
                                </div>

                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_to')}">
                                    <label>เรียน :</label>
                                    <input  type="text"
                                            id="leave_to"
                                            name="leave_to"
                                            ng-model="leave.leave_to"
                                            class="form-control"
                                            tabindex="6">
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_to')">กรุณาระบุข้อมูลในช่องเรียน</span>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>ผู้ลา :</label>
                                    <input  type="text"
                                            id="leave_person_name"
                                            name="leave_person_name"
                                            value="{{ Auth::user()->person_firstname }} {{ Auth::user()->person_lastname }}"
                                            class="form-control"
                                            readonly="readonly"
                                            tabindex="6">
                                    <input type="hidden"
                                            id="leave_person"
                                            name="leave_person"
                                            value="{{ Auth::user()->person_id }}">
                                </div>

                                <?php $user_position = ''; ?>
                                @foreach ($positions as $position)
                                    @if ($position->position_id == Auth::user()->position_id)
                                        <?php $user_position = $position->position_name; ?>
                                    @endif
                                @endforeach

                                <div class="form-group col-md-6">
                                    <label>ตำแหน่ง :</label>
                                    <input  type="text"
                                            id="leave_person_position"
                                            name="leave_person_position"
                                            value="{{ $user_position }}"
                                            class="form-control"
                                            readonly="readonly" />
                                </div>

                                <div class="form-group col-md-12" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_reason')}">
                                    <label>เนื่องจาก :</label>
                                    <input  type="text" 
                                            id="leave_reason" 
                                            name="leave_reason" 
                                            ng-model="leave.leave_reason" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_reason')">กรุณาระบุเหตุผลการลา</span>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(leave, 'start_date')}">
                                        <label>จากวันที่ :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text"
                                                    id="start_date"
                                                    name="start_date"
                                                    class="form-control pull-right"
                                                    tabindex="5">
                                        </div>
                                        <span class="help-block" ng-show="checkValidate(leave, 'start_date')">กรุณาเลือกจากวันที่</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'start_period')}">
                                    <label>ช่วงเวลา :</label>
                                    <select id="start_period"
                                            name="start_period"
                                            ng-model="leave.start_period"
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;"
                                            tabindex="2">
                                        <option value="" selected="selected">-- เลือกช่วงเวลา --</option>

                                        @foreach($periods as $key => $period)

                                            <option value="{{ $key }}">
                                                {{ $period }}
                                            </option>

                                        @endforeach

                                    </select>
                                    <span class="help-block" ng-show="checkValidate(leave, 'start_period')">เลือกช่วงเวลา</span>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(leave, 'end_date')}">
                                        <label>ถึงวันที่ :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="end_date"
                                                    name="end_date"
                                                    class="form-control pull-right"
                                                    tabindex="5">
                                        </div>
                                        <span class="help-block" ng-show="checkValidate(leave, 'end_date')">กรุณาเลือกถึงวันที่</span>
                                    </div>
                                </div>

                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'end_period')}">
                                    <label>ช่วงเวลา :</label>
                                    <select id="end_period" 
                                            name="end_period"
                                            ng-model="leave.end_period" 
                                            class="form-control select2" 
                                            style="width: 100%; font-size: 12px;"
                                            tabindex="2">
                                        <option value="" selected="selected">-- เลือกช่วงเวลา --</option>

                                        @foreach($periods as $key => $period)

                                            <option value="{{ $key }}">
                                                {{ $period }}
                                            </option>

                                        @endforeach

                                    </select>
                                    <span class="help-block" ng-show="checkValidate(leave, 'end_period')">เลือกช่วงเวลา</span>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>ระหว่างลาติดต่อข้าพเจ้าได้ที่ :</label>
                                    <textarea
                                        id="leave_contact" 
                                        name="leave_contact" 
                                        ng-model="leave.leave_contact" 
                                        class="form-control"
                                        tabindex="17"
                                    ></textarea>
                                </div>

                                <div class="form-group col-md-12" ng-class="{'has-error has-feedback': checkValidate(leave, 'depart')}">
                                    <label>แนบเอกสาร :</label>
                                    <input type="file"
                                            id="leave_delegate_detail" 
                                            name="leave_delegate_detail"
                                            class="form-control" />
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(leave, 'depart')"></span>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_delegate')">กรุณาแนบเอกสาร</span>
                                </div>

                                <div class="form-group col-md-12" ng-class="{'has-error has-feedback': checkValidate(leave, 'depart')}">
                                    <label>ผู้รับมอบหมายแทน :</label>
                                    <div class="input-group">
                                        <input type="text"
                                                id="leave_delegate_detail" 
                                                name="leave_delegate_detail"
                                                class="form-control" />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary">...</button>
                                        </span>
                                    </div>
                                    <input type="hidden"
                                                id="leave_delegate" 
                                                name="leave_delegate"
                                                ng-model="leave.leave_delegate" 
                                                class="form-control"
                                                tabindex="2" />
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="checkValidate(leave, 'depart')"></span>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_delegate')">กรุณาเลือกหหน่วยงาน</span>
                                </div>

                            </div><!-- /.row -->

                        </div><!-- /.box-body -->

                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, 'leaves/validate', leave, 'frmNewLeave', store)"
                                class="btn btn-success pull-right"
                            >
                                บันทึก
                            </button>
                        </div><!-- /.box-footer -->
                    </form>

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>

@endsection