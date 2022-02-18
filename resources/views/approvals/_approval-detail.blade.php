<div class="modal fade" id="approval-detail" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดการอนุมัติ</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body" style="padding-bottom: 0;">
                <div class="row">
                    <div class="form-group col-md-12">
                        <h4>
                            หัวหน้ากลุ่มงาน
                            <input type="radio" name="approved" value="3" />
                            <span style="margin-right: 10px;">อนุญาต</span>
                            <input type="radio" name="approved" value="4" />
                            <span style="margin-right: 10px;">ไม่อนุญาต</span>
                            <span style="margin-right: 10px;">เมื่อวันที่</span>
                        </h4>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">ความเห็นในการลงนาม</label>
                        <textarea id="comment" name="comment" cols="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4>
                            รับเอกสารแล้ว
                            <span style="margin-right: 10px;">เมื่อวันที่</span>
                        </h4>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <h4>
                            ผู้มีอำนาจลงนาม
                            <input type="radio" name="approved" value="3" />
                            <span style="margin-right: 10px;">อนุญาต</span>
                            <input type="radio" name="approved" value="4" />
                            <span style="margin-right: 10px;">ไม่อนุญาต</span>
                            <span style="margin-right: 10px;">เมื่อวันที่</span>
                        </h4>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">ความเห็นในการลงนาม</label>
                        <textarea id="comment" name="comment" cols="3" class="form-control"></textarea>
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
