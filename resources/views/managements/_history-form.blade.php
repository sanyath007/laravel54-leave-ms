<div class="modal fade" id="history-form" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frmHistory" name="frmHistory" novalidate ng-submit="onSubmitHistory($event, frmHistory)">
                <div class="modal-header">
                    <h5 class="modal-title">บันทึกจำนวนวันลา ประจำปีงบ @{{ cboYear }}</h5>
                </div>
                <div class="modal-body" style="padding: 0 50px;">
                    <input type="hidden" id="user" name="user" value="{{ Auth::user()->person_id }}" />

                    <div class="row">
                        <div class="alert alert-info" style="margin-top: 10px;">
                            <span style="font-size: 18px; font-weight: bold;">
                                ชื่อ-สกุล @{{ history.person.prefix.prefix_name + history.person.person_firstname + ' ' + history.person.person_lastname }}
                            </span>
                            <p style="font-size: 18px; font-weight: bold;">
                                ตำแหน่ง @{{ history.person.position.position_name + history.person.academic.ac_name }}
                            </p>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmHistory.$submitted && frmHistory.ill_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาป่วย</label>
                            <input
                                type="text"
                                id="ill_days"
                                name="ill_days"
                                class="form-control"
                                ng-model="history.ill_days"
                                ng-keyup="calculateVacation(history.ill_days, history.new_days)"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.ill_days.$error.required">
                                กรุณาระบุจำนวนวันลาป่วย
                            </span>
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.ill_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาป่วยเป็นตัวเลข
                            </span>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmHistory.$submitted && frmHistory.per_days.$invalid}"
                        >
                            <label for="">จำนวนวันลากิจ</label>
                            <input
                                type="text"
                                id="per_days"
                                name="per_days"
                                class="form-control"
                                ng-model="history.per_days"
                                ng-keyup="calculateVacation(history.old_days, history.per_days)"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.per_days.$error.required">
                                กรุณาระบุจำนวนวันลากิจ
                            </span>
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.per_days.$error.pattern">
                                กรุณาระบุจำนวนวันลากิจเป็นตัวเลข
                            </span>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmHistory.$submitted && frmHistory.lab_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาคลอด</label>
                            <input
                                type="text"
                                id="lab_days"
                                name="lab_days"
                                class="form-control"
                                ng-model="history.lab_days"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.lab_days.$error.required">
                                กรุณาระบุจำนวนวันลาคลอด
                            </span>
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.lab_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาคลอดเป็นตัวเลข
                            </span>
                        </div>
                        
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmHistory.$submitted && frmHistory.vac_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาพักผ่อน</label>
                            <input
                                type="text"
                                id="vac_days"
                                name="vac_days"
                                class="form-control"
                                ng-model="history.vac_days"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.vac_days.$error.required">
                                กรุณาระบุจำนวนวันลาพักผ่อน
                            </span>
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.vac_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาพักผ่อนเป็นตัวเลข
                            </span>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmHistory.$submitted && frmHistory.hel_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาลาเพื่อดูแลบุตรและภรรยาที่คลอดบุตร</label>
                            <input
                                type="text"
                                id="hel_days"
                                name="hel_days"
                                class="form-control"
                                ng-model="history.hel_days"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.hel_days.$error.required">
                                กรุณาระบุจำนวนวันลาลาเพื่อดูแลบุตรและภรรยาที่คลอดบุตร
                            </span>
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.hel_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาลาเพื่อดูแลบุตรและภรรยาที่คลอดบุตรเป็นตัวเลข
                            </span>
                        </div>
                        <div
                            class="col-md-12 form-group"
                            ng-class="{'has-error has-feedback': frmHistory.$submitted && frmHistory.ord_days.$invalid}"
                        >
                            <label for="">จำนวนวันลาอุปสมบท/ประกอบพิธีฮัจย์</label>
                            <input
                                type="text"
                                id="ord_days"
                                name="ord_days"
                                class="form-control"
                                ng-model="history.ord_days"
                                ng-pattern="/^-?\d+(\.\d{1,2})?$/"
                                required
                            />
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.ord_days.$error.required">
                                กรุณาระบุจำนวนวันลาอุปสมบท/ประกอบพิธีฮัจย์
                            </span>
                            <span class="help-block" ng-show="frmHistory.$submitted && frmHistory.ord_days.$error.pattern">
                                กรุณาระบุจำนวนวันลาอุปสมบท/ประกอบพิธีฮัจย์เป็นตัวเลข
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
