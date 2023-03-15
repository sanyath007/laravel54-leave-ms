<div class="modal fade" id="vacation-form" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frmVacation" name="frmVacation" novalidate ng-submit="onSubmitVacation($event, frmVacation)">
                <div class="modal-header">
                    <h5 class="modal-title">บันทึกวันลาพักผ่อนสะสม ประจำปีงบ @{{ cboYear }}</h5>
                </div>
                <div class="modal-body" style="padding: 0 50px;">
                    <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}" />

                    <div class="row">
                        <div class="alert alert-info" style="margin-top: 10px;">
                            <span style="font-size: 18px; font-weight: bold;">
                                ชื่อ-สกุล @{{ vacation.person.prefix.prefix_name + vacation.person.person_firstname + ' ' + vacation.person.person_lastname }}
                            </span>
                            <p style="font-size: 18px; font-weight: bold;">
                                ตำแหน่ง @{{ vacation.person.position.position_name + vacation.person.academic.ac_name }}
                            </p>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmVacation.$submitted && frmVacation.old_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาคงเหลือ</label>
                            <input
                                type="text"
                                id="old_days"
                                name="old_days"
                                class="form-control"
                                ng-model="vacation.old_days"
                                ng-keyup="calculateVacation(vacation.old_days, vacation.new_days)"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmVacation.$submitted && frmVacation.old_days.$error.required">
                                กรุณาระบุจำนวนวันลาคงเหลือ
                            </span>
                            <span class="help-block" ng-show="frmVacation.$submitted && frmVacation.old_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาคงเหลือเป็นตัวเลข
                            </span>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmVacation.$submitted && frmVacation.new_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาประจำปี</label>
                            <input
                                type="text"
                                id="new_days"
                                name="new_days"
                                class="form-control"
                                ng-model="vacation.new_days"
                                ng-keyup="calculateVacation(vacation.old_days, vacation.new_days)"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmVacation.$submitted && frmVacation.new_days.$error.required">
                                กรุณาระบุจำนวนวันลาประจำปี
                            </span>
                            <span class="help-block" ng-show="frmVacation.$submitted && frmVacation.new_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาประจำปีเป็นตัวเลข
                            </span>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmVacation.$submitted && frmVacation.all_days.$invalid}"
                        >
                            <label for="">จำนวนวันลารวม</label>
                            <input
                                type="text"
                                id="all_days"
                                name="all_days"
                                class="form-control"
                                ng-model="vacation.all_days"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                                readonly
                            />
                            <span class="help-block" ng-show="frmVacation.$submitted && frmVacation.all_days.$error.required">
                                กรุณาระบุจำนวนวันลารวม
                            </span>
                            <span class="help-block" ng-show="frmVacation.$submitted && frmVacation.all_days.$error.pattern">
                                กรุณาระบุจำนวนวันลารวมเป็นตัวเลข
                            </span>
                        </div>
                    </div>
                </div><!-- /.modal-body -->
                <div class="modal-footer" style="padding-bottom: 8px;">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        aria-label="Save"
                    >
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
