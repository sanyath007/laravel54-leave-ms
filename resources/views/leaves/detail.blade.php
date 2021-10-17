@extends('layouts.main')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            รายละเอียดใบลา : ID ({{ $leave->id }})
            <!-- <small>preview of simple tables</small> -->
        </h1>

        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">หน้าหลัก</a></li>
            <li class="breadcrumb-item active">รายละเอียดใบลา</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content" ng-controller="leaveCtrl" ng-init="getById({{ $leave->id }}, setEditControls);">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">รายละเอียดใบลา</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div style="border: 1px dotted grey; display: flex; min-height: 240px; padding: 10px;">
                                    <img src="{{ asset('img/user2-160x160.jpg') }}" alt="user_image" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group col-md-6">
                                    <label>เขียนที่ :</label>
                                    <select id="leave_place" 
                                            name="leave_place"
                                            ng-model="leave.leave_place"
                                            class="form-control"
                                            tabindex="2">
                                        <option value="">-- กรุณาเลือก --</option>
                                        <option value="1">โรงพยาบาลเทพรัตน์นครราชสีมา</option>
                                        <option value="2">บ้าน</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
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
                                </div>

                                <div class="form-group col-md-6">
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
                                </div>

                                <div class="form-group col-md-6">
                                    <label>เรียน :</label>
                                    <input  type="text"
                                            id="leave_to"
                                            name="leave_to"
                                            ng-model="leave.leave_to"
                                            class="form-control"
                                            tabindex="6">
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

                                <div class="form-group col-md-12">
                                    <label>เนื่องจาก :</label>
                                    <input  type="text" 
                                            id="leave_reason" 
                                            name="leave_reason" 
                                            ng-model="leave.leave_reason" 
                                            class="form-control pull-right"
                                            tabindex="5">
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>จากวันที่ :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text"
                                                    id="start_date"
                                                    name="start_date"
                                                    value="@{{ leave.start_date }}"
                                                    class="form-control pull-right"
                                                    tabindex="5">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
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
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ถึงวันที่ :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                            </div>
                                            <input  type="text" 
                                                    id="end_date"
                                                    name="end_date"
                                                    value="@{{ leave.end_date }}"
                                                    class="form-control pull-right"
                                                    tabindex="5">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
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
                                </div>

                                <div class="form-group col-md-12" ng-class="{'has-error has-feedback': checkValidate(leave, 'leave_contact')}">
                                    <label>ระหว่างลาติดต่อข้าพเจ้าได้ที่ :</label>
                                    <textarea
                                        id="leave_contact" 
                                        name="leave_contact" 
                                        ng-model="leave.leave_contact" 
                                        class="form-control"
                                        tabindex="17"
                                    ></textarea>
                                </div>
                                
                                
                                <div class="col-md-12" style="margin-bottom: 15px;" ng-show="leave.attachment">
                                    <label>เอกสารแนบ :</label>
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

                                <div class="form-group col-md-12" ng-class="{'has-error has-feedback': checkValidate(leave, 'depart')}">
                                    <label>ผู้รับมอบหมายแทน :</label>
                                    <input type="text"
                                            id="leave_delegate_detail" 
                                            name="leave_delegate_detail"
                                            class="form-control"
                                            readonly="readonly" />
                                </div>
                            </div>
                        </div><!-- /.row -->

                        @include('leaves._person-list')

                    </div><!-- /.box-body -->

                    <div class="box-footer clearfix" style="text-align: center;">
                        <a
                            href="{{ url('/leaves/print') }}/{{ $leave->id }}"
                            class="btn btn-success"
                            target="_blank"
                        >
                            <i class="fa fa-print"></i> พิมพ์
                        </a>
                        <a
                            ng-show="(leave.status!==4 || leave.status!==3)"
                            ng-click="edit(leave.id)"
                            class="btn btn-warning"
                        >
                            <i class="fa fa-edit"></i> แก้ไข
                        </a>
                        <a
                            href="#"
                            ng-click="edit(leave.id)"
                            class="btn btn-danger"
                        >
                            <i class="fa fa-trash"></i> ลบ
                        </a>
                    </div><!-- /.box-footer -->

                </div><!-- /.box -->

            </div><!-- /.col -->
        </div><!-- /.row -->

    </section>

@endsection