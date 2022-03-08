<table class="table table-bordered table-striped" style="font-size: 14px; margin: 10px auto;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th>รายละเอียดการลา</th>
            <th style="width: 35%;">ขอยกเลิกวันลา</th>
            <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
            <th style="width: 10%; text-align: center;">การอนุมัติ</th>
            <th style="width: 6%; text-align: center;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="(index, cancel) in cancellations">
            <td style="text-align: center;">@{{ index+cancelPager.from }}</td>
            <td>
                <h4 style="margin: 2px auto;">
                    @{{ cancel.type.name }}
                </h4>
                <p style="color: grey; margin: 0px auto;">
                    ระหว่างวันที่ <span>@{{ cancel.start_date | thdate }} - </span>
                    ถึงวันที่ <span>@{{ cancel.end_date | thdate }}</span>
                    จำนวน <span>@{{ cancel.leave_days }}</span> วัน
                    <a  href="{{ url('/'). '/uploads/' }}@{{ cancel.attachment }}"
                        title="ไฟล์แนบ"
                        target="_blank"
                        ng-show="cancel.attachment"
                        class="btn btn-default btn-xs"
                    >
                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                    </a>
                </p>
            </td>
            <td>
                <span style="font-weight: bold;">วันที่</span> 
                @{{ cancel.cancellation[0].start_date | thdate }} - @{{ cancel.cancellation[0].end_date | thdate }}
                <span style="font-weight: bold;">จำนวน</span> @{{ cancel.cancellation[0].days }} วัน
                <p style="color: grey; margin: 0px auto;">
                    <span style="font-weight: bold;">เนื่องจาก</span> @{{ cancel.cancellation[0].reason }}
                </p>
            </td>
            <td style="text-align: center;">@{{ cancel.year }}</td>
            <td style="text-align: center;">
                <p style="margin: 0px auto;">
                    <span class="label label-warning" ng-show="cancel.status == 5">
                        อยู่ระหว่างการยกเลิก
                    </span>
                    <span class="label label-success" ng-show="cancel.status == 8">
                        ผ่านการอนุมัติ (ยกเลิกบางส่วน)
                    </span>
                    <span class="label label-danger" ng-show="cancel.status == 9">
                        ผ่านการอนุมัติ (ยกเลิกทั้งหมด)
                    </span>
                    <p style="margin: 0px auto;">
                        @{{ cancel.cancel_date | thdate }}
                    </p>
                </p>
            </td>
            <td style="text-align: center;">
                <a  href="{{ url('/leaves/detail') }}/@{{ cancel.id }}"
                    class="btn btn-primary btn-xs" 
                    title="รายละเอียด">
                    <i class="fa fa-search"></i>
                </a>
                <a  href="{{ url('/cancellations/edit') }}/@{{ cancel.id }}"
                    ng-show="cancel.status == 5"
                    class="btn btn-warning btn-xs"
                    title="แก้ไขรายการ">
                    <i class="fa fa-edit"></i>
                </a>
                <form
                    id="frmDelete"
                    method="POST"
                    action="{{ url('/cancellations/delete') }}"
                    ng-show="cancel.status == 5"
                >
                    {{ csrf_field() }}
                    <button
                        type="submit"
                        ng-click="onDelete($event, cancel.cancellation[0].id)"
                        class="btn btn-danger btn-xs"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        หน้า @{{ cancelPager.current_page }} จาก @{{ cancelPager.last_page }}
    </div>
    <div class="col-md-4" style="text-align: center;">
        จำนวน @{{ cancelPager.total }} รายการ
    </div>
    <div class="col-md-4">
        <ul class="pagination pagination-sm no-margin pull-right" ng-show="pager.last_page > 1">
            <li ng-if="cancelPager.current_page !== 1">
                <a href="#" ng-click="getDataWithURL($event, cancelPager.path+ '?page=1', setCancellations)" aria-label="Previous">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
        
            <li ng-class="{'disabled': (cancelPager.current_page==1)}">
                <a href="#" ng-click="getDataWithURL($event, cancelPager.prev_page_url, setCancellations)" aria-label="Prev">
                    <span aria-hidden="true">Prev</span>
                </a>
            </li>

            <!-- <li ng-repeat="i in debtPages" ng-class="{'active': cancelPager.current_page==i}">
                <a href="#" ng-click="getDataWithURL($event, cancelPager.path + '?page=' +i, setCancellations)">
                    @{{ i }}
                </a>
            </li> -->

            <!-- <li ng-if="cancelPager.current_page < cancelPager.last_page && (cancelPager.last_page - cancelPager.current_page) > 10">
                <a href="#" ng-click="cancelPager.path">
                    ...
                </a>
            </li> -->

            <li ng-class="{'disabled': (cancelPager.current_page==cancelPager.last_page)}">
                <a href="#" ng-click="getDataWithURL($event, cancelPager.next_page_url, setCancellations)" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>

            <li ng-if="cancelPager.current_page !== cancelPager.last_page">
                <a href="#" ng-click="getDataWithURL($event, cancelPager.path+ '?page=' +cancelPager.last_page, setCancellations)" aria-label="Previous">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        </ul>
    </div>
</div><!-- /.row -->