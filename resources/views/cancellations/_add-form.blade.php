<div class="modal fade" id="add-form" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="frmAddCancel" name="frmAddCancel" action="{{ url('/cancellations/store') }}" method="POST">
                <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                {{ csrf_field() }}

                <div class="modal-header">
                    <h5 class="modal-title">ยกเลิกวันลา</h5>
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
                                มีกำหนด
                                <span style="font-weight: bold;">@{{ leave.leave_days | currency:'':1 }}</span> วัน
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div
                            class="form-group col-md-12"
                            ng-class="{'has-error has-feedback': checkValidate(cancellation, 'reason')}"
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
                                @{{ formError.errors.reason[0] }}
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
                                class="form-control"
                                ng-model="cancellation.start_date"
                                ng-class="{ disabled: isOnlyOneDay(leave.start_date, leave.end_date) }"
                                ng-readonly="isOnlyOneDay(leave.start_date, leave.end_date)"
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
                            <select id="start_period"
                                    name="start_period"
                                    ng-model="cancellation.start_period"
                                    class="form-control">
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
                                class="form-control"
                                ng-model="cancellation.end_date"
                                ng-class="{ disabled: isOnlyOneDay(leave.start_date, leave.end_date) }"
                                ng-readonly="isOnlyOneDay(leave.start_date, leave.end_date)"
                            />
                            <span class="help-block" ng-show="checkValidate(cancellation, 'end_date')">
                                @{{ formError.errors.end_date[0] }}
                            </span>
                        </div>
                        <div
                            class="form-group col-md-6"
                            ng-class="{'has-error has-feedback': checkValidate(cancellation, 'end_period')}"
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

                </div><!-- /.modal-body -->
                <div class="modal-footer" style="padding-bottom: 8px;">
                    <button class="btn btn-primary" ng-click="formValidate($event, '/cancellations/validate', cancellation, 'frmAddCancel', store)">
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
