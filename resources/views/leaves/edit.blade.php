@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            แก้ไขใบลา
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">แก้ไขใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="getById({{ $leave->id }}, setEditControls);">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">แก้ไขใบลา</h3>
                    </div>

                    <form id="frmEditLeave" name="frmEditLeave" method="post" action="{{ url('/leaves/update') }}" role="form" enctype="multipart/form-data">
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}">
                        <input type="hidden" id="depart_id" name="depart_id" value="{{ Auth::user()->memberOf->depart_id }}">
                        <input type="hidden" id="leave_id" name="leave_id" value="{{ $leave->id }}" ng-model="leave.leave_id">
                        <input type="hidden" id="leave_topic" name="leave_topic" value="{{ $leave->leave_topic }}" ng-model="leave.leave_topic">
                        <input type="hidden" id="leave_delegate" name="leave_delegate" value="{{ $leave->leave_delegate }}" ng-model="leave.leave_delegate" />
                        {{ csrf_field() }}

                        @if ($leave->helpedWife)
                            <input type="hidden" id="hw_id" name="hw_id" value="{{ $leave->helpedWife->id }}" ng-model="leave.helped_wife.id">
                        @endif

                        @if ($leave->ordinate)
                            <input type="hidden" id="ord_id" name="ord_id" value="{{ $leave->ordinate->id }}" ng-model="leave.ordinate.id">
                        @endif

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
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_type')}">
                                    <label>เรื่อง :</label>
                                    <select id="leave_type"
                                            name="leave_type"
                                            ng-model="leave.leave_type"
                                            class="form-control"
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
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>ผู้ลา :</label>
                                    <div class="input-group">
                                        <input  type="text"
                                                id="leave_person_name"
                                                name="leave_person_name"
                                                value="@{{ leave.person.prefix.prefix_name+leave.person.person_firstname+' '+leave.person.person_lastname }}"
                                                class="form-control"
                                                readonly="readonly"
                                                tabindex="6">
                                        <input type="hidden"
                                                id="leave_person"
                                                name="leave_person"
                                                ng-model="leave.leave_person"
                                                value="@{{ leave.leave_person }}">
                                        <span class="input-group-btn">
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                ng-click="onShowLeavePersonsList($event, '{{ Auth::user()->memberOf->depart_id }}')"
                                            >
                                                ...
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ตำแหน่ง :</label>
                                    <input  type="text"
                                            id="leave_person_position"
                                            name="leave_person_position"
                                            value="@{{ leave.person.position.position_name }}@{{ leave.person.academic ? leave.person.academic.ac_name : '' }}"
                                            class="form-control"
                                            readonly="readonly" />
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-12"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_reason')}"
                                    ng-show="leave.leave_type == '1' || leave.leave_type == '2' || leave.leave_type == '4' || leave.leave_type == '7'"
                                >
                                    <label>
                                        @{{ leave.leave_type == '7' ? 'เพื่อ' : 'เนื่องจาก' }}
                                    </label>
                                    <input  type="text" 
                                            id="leave_reason" 
                                            name="leave_reason" 
                                            ng-model="leave.leave_reason" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_reason')">กรุณาระบุเหตุผลการลา</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-6" 
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'wife_name')}"
                                    ng-show="leave.leave_type == '5'"
                                >
                                    <div style="display: flex;">
                                        <label style="margin-right: 5px;">ภรรยาชื่อ :</label>
                                        (<input
                                            type="checkbox"
                                            id="wife_is_officer"
                                            name="wife_is_officer"
                                            ng-model="wife_is_officer"
                                            ng-change="onWifeIsOfficer(wife_is_officer)"
                                            style="margin: 5px;"
                                        /> เป็นบุคลากรของ รพ. )
                                    </div>
                                    <input 
                                        type="text" 
                                        id="wife_name" 
                                        name="wife_name" 
                                        ng-model="leave.wife_name" 
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                    <input type="hidden" id="wife_id" name="wife_id" ng-model="leave.wife_id" />
                                    <span class="help-block" ng-show="checkValidate(leave, 'wife_name')">
                                        กรุณาระบุเหตุผลการลา
                                    </span>
                                </div>

                                <div class="col-md-6" ng-show="leave.leave_type == '5'">
                                    <div class="form-group" ng-class="{'has-error has-feedback': checkValidate(leave, 'deliver_date')}">
                                        <label>คลอดบุตรเมื่อวันที่ :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text"
                                                    id="deliver_date"
                                                    name="deliver_date"
                                                    class="form-control pull-right"
                                                    tabindex="5">
                                        </div>
                                        <span class="help-block" ng-show="checkValidate(leave, 'deliver_date')">กรุณาเลือกวันที่คลอดบุตร</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-6"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'have_ordain')}"
                                    ng-show="leave.leave_type == '6'"
                                >
                                    <label>เคยอุปสมบท :</label>
                                    <div style="display: flex; margin-top: 5px; padding: 5px;">
                                        <input
                                            type="radio"
                                            id="have_ordain"
                                            name="have_ordain"
                                            ng-value="0"
                                            ng-model="leave.have_ordain"
                                            style="margin-right: 10px;"
                                            tabindex="5"
                                        > ยังไม่เคย
                                        <input
                                            type="radio"
                                            id="have_ordain"
                                            name="have_ordain"
                                            ng-value="1"
                                            ng-model="leave.have_ordain"
                                            style="margin: auto 10px auto 20px;"
                                            tabindex="5"
                                        > เคย
                                    </div>
                                    <span class="help-block" ng-show="checkValidate(leave, 'have_ordain')">
                                        กรุณาระบุการเคยอุปสมบท
                                    </span>
                                </div>

                                <div
                                    class="form-group col-md-6"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'ordain_temple')}"
                                    ng-show="leave.leave_type == '6'"
                                >
                                    <label>จะอุปสมบท ณ วัด :</label>
                                    <input  type="text" 
                                            id="ordain_temple" 
                                            name="ordain_temple" 
                                            ng-model="leave.ordain_temple" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'ordain_temple')">กรุณาระบุวัดที่จะอุปสมบท</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-12"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'ordain_location')}"
                                    ng-show="leave.leave_type == '6'"
                                >
                                    <label>ที่อยู่วัดที่จะอุปสมบท :</label>
                                    <input  type="text"
                                            id="ordain_location"
                                            name="ordain_location"
                                            ng-model="leave.ordain_location"
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'ordain_location')">กรุณาระบุที่อยู่วัดที่จะอุปสมบท</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-6"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'ordain_date')}"
                                    ng-show="leave.leave_type == '6'"
                                >
                                    <label>อุปสมบทวันที่ :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input  type="text"
                                                id="ordain_date"
                                                name="ordain_date"
                                                class="form-control pull-right"
                                                tabindex="5">
                                    </div>
                                    <span class="help-block" ng-show="checkValidate(leave, 'ordain_date')">กรุณาระบุวันที่อุปสมบท</span>
                                </div>

                                <div
                                    class="form-group col-md-6"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'hibernate_temple')}"
                                    ng-show="leave.leave_type == '6'"
                                >
                                    <div style="display: flex;">
                                        <label style="margin-right: 5px;">จำพรรษา ณ วัด :</label>
                                        (<input
                                            type="checkbox"
                                            id="same_ordain_temple"
                                            name="same_ordain_temple"
                                            ng-model="same_ordain_temple"
                                            ng-change="onSameOrdainTempleChecked(same_ordain_temple)"
                                            style="margin: 5px;"
                                        /> เป็นวัดเดียวกับวัดที่จะอุปสมบท )
                                    </div>
                                    <input  type="text" 
                                            id="hibernate_temple" 
                                            name="hibernate_temple" 
                                            ng-model="leave.hibernate_temple" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'hibernate_temple')">กรุณาระบุวัดที่จะจำพรรษา</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-12"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'hibernate_location')}"
                                    ng-show="leave.leave_type == '6'"
                                >
                                    <label>ที่อยู่วัดที่จะจำพรรษา :</label>
                                    <input  type="text" 
                                            id="hibernate_location" 
                                            name="hibernate_location" 
                                            ng-model="leave.hibernate_location" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'hibernate_location')">กรุณาระบุที่อยู่วัดที่จะจำพรรษา</span>
                                </div>
                            </div>

                            <div class="row">
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
                                            class="form-control"
                                            tabindex="2">
                                        <option value="">-- เลือกช่วงเวลา --</option>
                                        @foreach($periods as $key => $period)

                                            <option value="{{ $key }}">
                                                {{ $period }}
                                            </option>

                                        @endforeach
                                    </select>
                                    <span class="help-block" ng-show="checkValidate(leave, 'start_period')">เลือกช่วงเวลา</span>
                                </div>
                            </div>

                            <div class="row">
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
                                            ng-change="calculateLeaveDays('start_date', 'end_date', leave.end_period)"
                                            class="form-control" 
                                            style="width: 100%;"
                                            tabindex="2">
                                        <option value="">-- เลือกช่วงเวลา --</option>
                                        @foreach($periods as $key => $period)

                                            <option value="{{ $key }}">
                                                {{ $period }}
                                            </option>

                                        @endforeach
                                    </select>
                                    <span class="help-block" ng-show="checkValidate(leave, 'end_period')">เลือกช่วงเวลา</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-6"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_days')}"
                                >
                                    <label>มีกำหนด (วัน) :</label>
                                    <input  type="text" 
                                            id="leave_days" 
                                            name="leave_days" 
                                            ng-model="leave.leave_days" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_days')">กรุณาระบุจำนวนวันลา</span>
                                </div>
                                <div
                                    class="form-group col-md-6"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'working_days')}"
                                >
                                    <label>มีกำหนด (วันทำการ) :</label>
                                    <input  type="text" 
                                            id="working_days" 
                                            name="working_days" 
                                            ng-model="leave.working_days" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                    <span class="help-block" ng-show="checkValidate(leave, 'working_days')">กรุณาระบุจำนวนวันลา (วันทำการ)</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-12"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_contact')}"
                                    ng-show="leave.leave_type != '6' && leave.leave_type != '7'"
                                >
                                    <label>ระหว่างลาติดต่อข้าพเจ้าได้ที่ :</label>
                                    <textarea
                                        id="leave_contact" 
                                        name="leave_contact" 
                                        ng-model="leave.leave_contact" 
                                        class="form-control"
                                        tabindex="17"
                                    ></textarea>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_contact')">กรุณาระบุข้อมูลสำหรับติดต่อ</span>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    ng-style="leave.attachment && { 'margin-bottom': '5px' }"
                                    class="form-group col-md-12"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'attachment')}"
                                >
                                    <label>แนบเอกสาร :</label>
                                    <input type="file"
                                            id="attachment" 
                                            name="attachment"
                                            class="form-control" />
                                    <span class="help-block" ng-show="checkValidate(leave, 'attachment')">กรุณาแนบเอกสาร</span>
                                </div>

                                <div class="col-md-12" style="margin-bottom: 15px;" ng-show="leave.attachment">
                                    <div style="display: flex; flex-direction: row; justify-content: flex-start;">
                                        <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                                            title="ไฟล์แนบ"
                                            target="_blank">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                            @{{ leave.attachment }}
                                        </a>

                                        <span style="margin-left: 10px;">
                                            <a href="#">
                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    class="form-group col-md-12"
                                    ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_delegate')}"
                                    ng-show="leave.leave_type == '1' || leave.leave_type == '2' || leave.leave_type == '3' || leave.leave_type == '4'"
                                >
                                    <label>ผู้รับมอบหมายแทน :</label>
                                    <div class="input-group">
                                        <input type="text"
                                                id="leave_delegate_detail" 
                                                name="leave_delegate_detail"
                                                class="form-control"
                                                readonly="readonly" />
                                        <span class="input-group-btn">
                                            <button
                                                type="button"
                                                class="btn btn-primary"
                                                ng-click="onShowDelegatorsList($event, '{{ Auth::user()->memberOf->depart_id }}')"
                                            >
                                                ...
                                            </button>
                                        </span>
                                    </div>
                                    <span class="help-block" ng-show="checkValidate(leave, 'leave_delegate')">กรุณาเลือกผู้รับมอบหมายแทน</span>
                                </div>
                            </div><!-- /.row -->

                            @include('leaves._person-list')

                        </div><!-- /.box-body -->

                        <div class="box-footer clearfix">
                            <button
                                ng-click="formValidate($event, '/leaves/validate', leave, 'frmEditLeave', update)"
                                class="btn btn-warning pull-right"
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
            $('.select2').select2();
        });
    </script>

@endsection