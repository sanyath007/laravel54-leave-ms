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

                                <div 
                                    class="form-group col-md-12"
                                    ng-show="leave.leave_type == '1' || leave.leave_type == '2' || leave.leave_type == '4' || leave.leave_type == '7'"
                                >
                                    <label>
                                        @{{ leave.leave_type == '7' ? 'เพื่อ' : 'เนื่องจาก' }}
                                    </label>
                                    <input  
                                        type="text" 
                                        id="leave_reason" 
                                        name="leave_reason" 
                                        ng-model="leave.leave_reason" 
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                </div>

                                <div class="form-group col-md-6" ng-show="leave.leave_type == '5'">
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
                                </div>

                                <div class="form-group col-md-6" ng-show="leave.leave_type == '5'">
                                    <label>คลอดบุตรเมื่อวันที่ :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="deliver_date"
                                            name="deliver_date"
                                            class="form-control pull-right"
                                            tabindex="5"
                                        />
                                    </div>
                                </div>

                                <div class="form-group col-md-6" ng-show="leave.leave_type == '6'">
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
                                        /> ยังไม่เคย
                                        <input
                                            type="radio"
                                            id="have_ordain"
                                            name="have_ordain"
                                            ng-value="1"
                                            ng-model="leave.have_ordain"
                                            style="margin: auto 10px auto 20px;"
                                            tabindex="5"
                                        /> เคย
                                    </div>
                                </div>

                                <div class="form-group col-md-6" ng-show="leave.leave_type == '6'">
                                    <label>จะอุปสมบท ณ วัด :</label>
                                    <input
                                        type="text" 
                                        id="ordain_temple" 
                                        name="ordain_temple" 
                                        ng-model="leave.ordain_temple" 
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                </div>

                                <div class="form-group col-md-12" ng-show="leave.leave_type == '6'">
                                    <label>ที่อยู่วัดที่จะอุปสมบท :</label>
                                    <input
                                        type="text"
                                        id="ordain_location"
                                        name="ordain_location"
                                        ng-model="leave.ordain_location"
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                </div>

                                <div class="form-group col-md-6" ng-show="leave.leave_type == '6'">
                                    <label>อุปสมบทวันที่ :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text"
                                            id="ordain_date"
                                            name="ordain_date"
                                            class="form-control pull-right"
                                            tabindex="5"
                                        />
                                    </div>
                                </div>

                                <div class="form-group col-md-6" ng-show="leave.leave_type == '6'">
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
                                    <input
                                        type="text" 
                                        id="hibernate_temple" 
                                        name="hibernate_temple" 
                                        ng-model="leave.hibernate_temple" 
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                </div>

                                <div class="form-group col-md-12" ng-show="leave.leave_type == '6'">
                                    <label>ที่อยู่วัดที่จะจำพรรษา :</label>
                                    <input
                                        type="text" 
                                        id="hibernate_location" 
                                        name="hibernate_location" 
                                        ng-model="leave.hibernate_location" 
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                </div>

                                <?php $contries = [
                                    '1' => 'เกาหลีใต้',
                                    '2' => 'ญี่ปุ่น',
                                    '3' => 'จีน',
                                    '4' => 'ไต้หวัน',
                                    '5' => 'ฮ่องกง',
                                    '6' => 'เวียดนาม',
                                    '7' => 'ลาว',
                                    '8' => 'พม่า',
                                    '9' => 'กัมพูชา',
                                    '10' => 'มาเลเซีย',
                                    '11' => 'สิงคโปร์',
                                ]; ?>
                                <div class="form-group col-md-12" ng-show="leave.leave_type == '7'">
                                    <label>ณ ประเทศ :</label>
                                    <select
                                        id="country"
                                        name="country"
                                        ng-model="leave.country"
                                        class="form-control" 
                                        style="width: 100%;"
                                        tabindex="2"
                                    >
                                        <option value="">-- เลือกประเทศ --</option>
                                        @foreach($contries as $key => $contry)

                                            <option value="{{ $key }}">
                                                {{ $contry }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
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

                                <div class="form-group col-md-6">
                                    <label>ถึงวันที่ :</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <input
                                            type="text" 
                                            id="end_date"
                                            name="end_date"
                                            value="@{{ leave.end_date }}"
                                            class="form-control pull-right"
                                            tabindex="5"
                                        />
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>ช่วงเวลา :</label>
                                    <select
                                        id="end_period"
                                        name="end_period"
                                        ng-model="leave.end_period"
                                        ng-change="calculateLeaveDays('start_date', 'end_date', leave.end_period)"
                                        class="form-control" 
                                        style="width: 100%;"
                                        tabindex="2"
                                    >
                                        <option value="">-- เลือกช่วงเวลา --</option>
                                        @foreach($periods as $key => $period)

                                            <option value="{{ $key }}">
                                                {{ $period }}
                                            </option>

                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>มีกำหนด (วัน) :</label>
                                    <input
                                        type="text" 
                                        id="leave_days" 
                                        name="leave_days" 
                                        ng-model="leave.leave_days" 
                                        class="form-control pull-right"
                                        tabindex="5"
                                    />
                                </div>

                                <div class="form-group col-md-12" ng-show="leave.leave_type != '6' && leave.leave_type != '7'">
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

                                <div class="form-group col-md-12">
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
                            <i class="fa fa-print"></i> พิมพ์ใบลา
                        </a>
                        <a
                            href="{{ url('/leaves/print-cancel') }}/{{ $leave->id }}"
                            class="btn btn-primary"
                            target="_blank"
                            ng-show="{{ $leave->status }}=='8'"
                        >
                            <i class="fa fa-print"></i> พิมพ์ยกเลิกใบลา
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