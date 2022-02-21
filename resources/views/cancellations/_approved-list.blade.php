<table class="table table-bordered table-striped" style="font-size: 14px; margin: 10px auto;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th>ประเภทการลา</th>
            <th style="width: 20%; text-align: center;">วันที่ลา</th>
            <th style="width: 5%; text-align: center;">วัน</th>
            <th style="width: 15%; text-align: center;">วันที่ลงทะเบียน</th>
            <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
            <th style="width: 15%; text-align: center;">สถานะ</th>
            <th style="width: 5%; text-align: center;">ไฟล์แนบ</th>
            <th style="width: 6%; text-align: center;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="(index, leave) in leaves">
            <td style="text-align: center;">@{{ index+pager.from }}</td>
            <td>@{{ leave.type.name }}</td>
            <td style="text-align: center;">
                <span>@{{ leave.start_date | thdate }} - </span>
                <span>@{{ leave.end_date | thdate }}</span>
            </td>
            <td style="text-align: center;">@{{ leave.leave_days }}</td>
            <td style="text-align: center;">@{{ leave.leave_date | thdate }}</td>
            <td style="text-align: center;">@{{ leave.year }}</td>
            <td style="text-align: center;">
                <span class="label label-primary" ng-show="leave.status == 1">
                    อยู่ระหว่างดำเนินการ
                </span>
                <span class="label label-info" ng-show="leave.status == 2">
                    รับเอกสารแล้ว
                </span>
                <span class="label label-success" ng-show="leave.status == 3">
                    ผ่านการอนุมัติ
                </span>
                <span class="label label-default" ng-show="leave.status == 4">
                    ไม่ผ่านการอนุมัติ
                </span>
                <span class="label label-warning" ng-show="leave.status == 5">
                    อยู่ระหว่างการยกเลิก
                </span>
                <span class="label label-danger" ng-show="leave.status == 9">
                    ยกเลิก
                </span>
            </td>
            <td style="text-align: center;">
                <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                    class="btn btn-default btn-xs"
                    title="ไฟล์แนบ"
                    target="_blank"
                    ng-show="leave.attachment">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                </a>
            </td>
            <td style="text-align: center;">
                <a  ng-click="showCancelForm(leave)"
                    class="btn btn-danger btn-sm"
                    title="ยกเลิกวันลา">
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                    ยกเลิกวันลา
                </a>
            </td>             
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        หน้า @{{ pager.current_page }} จาก @{{ pager.last_page }}
    </div>
    <div class="col-md-4" style="text-align: center;">
        จำนวน @{{ pager.total }} รายการ
    </div>
    <div class="col-md-4">
        <ul class="pagination pagination-sm no-margin pull-right">
            <li ng-if="pager.current_page !== 1">
                <a href="#" ng-click="getDataWithURL(pager.path+ '?page=1', setLeaves)" aria-label="Previous">
                    <span aria-hidden="true">First</span>
                </a>
            </li>
        
            <li ng-class="{'disabled': (pager.current_page==1)}">
                <a href="#" ng-click="getDataWithURL(pager.prev_page_url, setLeaves)" aria-label="Prev">
                    <span aria-hidden="true">Prev</span>
                </a>
            </li>

            <!-- <li ng-repeat="i in debtPages" ng-class="{'active': pager.current_page==i}">
                <a href="#" ng-click="getDataWithURL(pager.path + '?page=' +i, setLeaves)">
                    @{{ i }}
                </a>
            </li> -->

            <!-- <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                <a href="#" ng-click="pager.path">
                    ...
                </a>
            </li> -->

            <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                <a href="#" ng-click="getDataWithURL(pager.next_page_url, setLeaves)" aria-label="Next">
                    <span aria-hidden="true">Next</span>
                </a>
            </li>

            <li ng-if="pager.current_page !== pager.last_page">
                <a href="#" ng-click="getDataWithURL(pager.path+ '?page=' +pager.last_page, setLeaves)" aria-label="Previous">
                    <span aria-hidden="true">Last</span>
                </a>
            </li>
        </ul>
    </div>
</div><!-- /.row -->