<div class="modal fade" id="approval-detail" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดการอนุมัติ</h5>
            </div>
            <div class="modal-body" style="padding: 20px 40px;">
                @{{ approval.cancellation }}
                <div class="row">
                    <div class="col-md-12">
                        <h4>หัวหน้ากลุ่มงาน</h4>
                        <span
                            style="margin-right: 10px; color: red; font-size: 12px;"
                            ng-show="approval.commented_date == null"
                        >
                            <i class="fa fa-window-close" aria-hidden="true"></i>
                            ยังไม่ได้ลงความเห็น
                        </span>

                        <div ng-show="approval.commented_date != null">
                            <div class="form-group">
                                <input type="checkbox" ng-checked="[1,2,3,4].includes(approval.status)" />
                                <span style="margin-right: 10px;">อนุญาต</span>
                                <input type="checkbox" ng-checked="approval.status == 7" />
                                <span style="margin-right: 10px;">ไม่อนุญาต</span>
                                <span style="margin-right: 10px;">
                                    เมื่อวันที่ @{{ approval.commented_date | thdate }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="">ความเห็นของหัวหน้ากลุ่มงาน</label>
                                @{{ approval.commented_text }}
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4>การรับเอกสาร</h4>
                        <span
                            style="margin-right: 10px; color: red; font-size: 12px;"
                            ng-show="approval.received_date == null"
                        >
                            <i class="fa fa-window-close" aria-hidden="true"></i>
                            ยังไม่ได้ลงรับ
                        </span>
                        <span style="margin-right: 10px;" ng-show="approval.received_date != null">
                            เมื่อวันที่ @{{ approval.received_date | thdate }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4>ผู้มีอำนาจลงนาม</h4>
                        <span
                            style="margin-right: 10px; color: red; font-size: 12px;"
                            ng-show="approval.approved_date == null"
                        >
                            <i class="fa fa-window-close" aria-hidden="true"></i>
                            ยังไม่ได้ลงนาม
                        </span>

                        <div ng-show="approval.approved_date != null">
                            <div class="form-group">
                                <input type="checkbox" ng-checked="approval.status == 3" />
                                <span style="margin-right: 10px;">อนุญาต</span>
                                <input type="checkbox" ng-checked="approval.status == 4" />
                                <span style="margin-right: 10px;">ไม่อนุญาต</span>
                                <span style="margin-right: 10px;">
                                    เมื่อวันที่ @{{ approval.approved_date | thdate }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="">ความเห็นในการลงนาม</label>
                                @{{ approval.approved_comment }}
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-body -->
            <div class="modal-footer" style="padding-bottom: 8px;">
                <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                    ปิด
                </button>
            </div><!-- /.modal-footer -->
        </div>
    </div>
</div>
