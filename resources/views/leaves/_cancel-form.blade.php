<div class="modal fade" id="cancel-form" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="">
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
                                <span style="font-weight: bold;; margin-right: 5px;">@{{ leave.person.person_firstname + ' ' + leave.person.person_lastname }}</span>
                                ตำแหน่ง 
                                <span style="font-weight: bold;">@{{ leave.person.position }}</span>
                            </p>
                            <p>
                                ได้รับอนุญาตไห้ลา
                                <span style="font-weight: bold; margin-right: 5px;">@{{ leave.leave_type.name }}</span>
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
                            <textarea cols="3" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">จึงขอยกเลิกวันลา</label>
                            <input
                                type="text"
                                class="form-control"
                                value="@{{ leave.leave_type.name }}"
                                readonly="readonly"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">จำนวน (วัน)</label>
                            <input
                                type="text"
                                class="form-control"
                                value="@{{ leave.leave_days }}"
                                readonly="readonly"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">ตั้งแต่วันที่</label>
                            <input type="text" id="start_date" class="form-control" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">ถึงวันที่</label>
                            <input type="text" id="end_date" class="form-control" />
                        </div>
                    </div>

                </div><!-- /.modal-body -->
                <div class="modal-footer" style="padding-bottom: 8px;">
                    <button class="btn btn-primary" data-dismiss="modal" aria-label="Save">
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
