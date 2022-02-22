<table class="table table-bordered table-striped" style="font-size: 14px; margin-bottom: 10px;">
    <thead>
        <tr>
            <th style="width: 5%; text-align: center;">#</th>
            <th>รายละเอียด</th>
            <th style="width: 10%; text-align: center;">ปีงบประมาณ</th>
            <th style="width: 10%; text-align: center;">วันที่ลงทะเบียน</th>
            <th style="width: 6%; text-align: center;">การอนุมัติ</th>
            <th style="width: 6%; text-align: center;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="(index, leave) in leaves">
            <td style="text-align: center;">@{{ index+pager.from }}</td>
            <td>
                <h4 style="margin: 2px auto;">
                    @{{ leave.type.name }}
                    @{{ leave.person.prefix.prefix_name + leave.person.person_firstname + ' ' + leave.person.person_lastname }}
                </h4>
                <p style="color: grey; margin: 0px auto;">
                    ระหว่างวันที่ <span>@{{ leave.start_date | thdate }} - </span>
                    ถึงวันที่ <span>@{{ leave.end_date | thdate }}</span>
                    จำนวน <span>@{{ leave.leave_days }}</span> วัน
                    <a  href="{{ url('/'). '/uploads/' }}@{{ leave.attachment }}"
                        title="ไฟล์แนบ"
                        target="_blank"
                        ng-show="leave.attachment"
                        class="btn btn-default btn-xs"
                    >
                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                    </a>
                </p>
            </td>
            <td style="text-align: center;">@{{ leave.year }}</td>
            <td style="text-align: center;">
                <p style="margin: 0px auto;">@{{ leave.leave_date | thdate }}</p>
                <p style="margin: 0px auto;">
                    <span class="label label-primary" ng-show="leave.status == 0">
                        อยู่ระหว่างดำเนินการ
                    </span>
                    <span class="label label-info" ng-show="leave.status == 1">
                        หัวหน้าลงความเห็นแล้ว
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
                    <span class="label label-default" ng-show="leave.status == 7">
                        หัวหน้าไม่อนุญาต
                    </span>
                    <span class="label label-warning" ng-show="leave.status == 5">
                        อยู่ระหว่างการยกเลิก
                    </span>
                    <span class="label label-danger" ng-show="leave.status == 9">
                        ยกเลิก
                    </span>
                    <span class="label label-success" ng-show="leave.status == 8">
                        ผ่านการอนุมัติ
                    </span>
                </p>
            </td>
            <td style="text-align: center;">
                <a  
                    ng-click="showApprovalDetail(leave)"
                    ng-show="leave.status != 0"
                    class="btn btn-default btn-sm" 
                    title="รายละเอียด"
                    target="_blank"
                >
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                </a>
            </td>
            <td style="text-align: center;">
                <a  
                    ng-click="showCommentForm(leave, 1)" 
                    ng-show="leave.status == 0" 
                    class="btn btn-success btn-sm"
                    title="ลงความเห็น"
                >
                    <i class="fa fa-check" aria-hidden="true"></i>
                    ลงความเห็น
                </a>
                <form action="{{ url('/approvals/status') }}" method="POST" ng-show="leave.status == 1 || leave.status == 7">
                    <input type="hidden" id="leave_id" name="leave_id" value="@{{ leave.id }}" />
                    <input type="hidden" id="status" name="status" value="0" />
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-remove" aria-hidden="true"></i>
                        ยกเลิก
                    </button>
                </form>
            </td>             
        </tr>
    </tbody>
</table>

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
        <a href="#" ng-click="getDebtWithURL(pager.path + '?page=' +i)">
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
        <a href="#" ng-click="getDataWithURL(pager.path+ '?page=1' +pager.last_page, setLeaves)" aria-label="Previous">
            <span aria-hidden="true">Last</span>
        </a>
    </li>
</ul>