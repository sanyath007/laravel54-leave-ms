<div class="modal fade" id="cancel-form" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ url('/cancellations/cancel') }}" method="POST">
                <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                {{ csrf_field() }}

                <div class="modal-header">
                    <h5 class="modal-title">ยกเลิกใบลา</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body" style="padding-bottom: 0;">
                    <div class="box">
                        <div class="box-body">
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
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="">เนื่องจาก (ระบุเหตุผลการยกเลิก)</label>
                            <textarea name="reason" cols="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">จึงขอยกเลิกวันลา</label>
                            <input
                                type="text"
                                class="form-control"
                                value="@{{ leave.type.name }}"
                                readonly="readonly"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">จำนวน (วัน)</label>
                            <input
                                type="text"
                                id="leave_days"
                                name="leave_days"
                                class="form-control"
                                value="@{{ leave.leave_days }}"
                                readonly="readonly"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">ตั้งแต่วันที่</label>
                            <input type="text" id="from_date" name="from_date" class="form-control" />
                        </div>
                        <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'end_period')}">
                            <label>ช่วงเวลา :</label>
                            <select id="start_period"
                                    name="start_period"
                                    ng-model="cboStartPeriod"
                                    class="form-control">
                                <option value="">-- เลือกช่วงเวลา --</option>
                                @foreach($periods as $key => $period)

                                    <option value="{{ $key }}">
                                        {{ $period }}
                                    </option>

                                @endforeach
                            </select>
                            <span class="help-block" ng-show="checkValidate(leave, 'end_period')">เลือกช่วงเวลา</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">ถึงวันที่</label>
                            <input type="text" id="to_date" name="to_date" class="form-control" />
                        </div>
                        <div class="form-group col-md-6" ng-class="{'has-error has-feedback': checkValidate(leave, 'end_period')}">
                            <label>ช่วงเวลา :</label>
                            <select id="end_period"
                                    name="end_period"
                                    ng-model="cboEndPeriod"
                                    ng-change="calculateLeaveDays('from_date', 'to_date', cboEndPeriod)"
                                    class="form-control">
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

                </div><!-- /.modal-body -->
                <div class="modal-footer" style="padding-bottom: 8px;">
                    <button type="submit" class="btn btn-primary">
                        บันทึก
                    </button>
                    <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                        ปิด
                    </button>
                </div><!-- /.modal-footer -->
            </form>
        </div>
    </div>
</div>
