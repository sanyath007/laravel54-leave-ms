<div class="box" ng-init="getHeadLeaves()">
    <div class="box-header">
        <h3 class="box-title">หัวหน้า</h3>
        <div class="pull-right box-tools">
            <div class="row">
                <div class="form-group col-md-12" style="margin-bottom: 0px;">
                    <input
                        type="text"
                        id="cboNow"
                        name="cboNow"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-triped">
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th>ชื่อ-สกุล</th>
                <th style="width: 30%; text-align: center;">ประเภท</th>
                <th style="width: 30%; text-align: center;">จำนวน (วัน)</th>
            </tr>
            <tr ng-repeat="(index, leave) in headLeaves">
                <td style="text-align: center;">@{{ index+1 }}</td>
                <td>@{{ leave.person.person_firstname + ' ' + leave.person.person_lastname }}</td>
                <td style="text-align: center;">@{{ leave.type.name }}</td>
                <td style="text-align: center;">@{{ leave.leave_days }}</td>
            </tr>
        </table>
    </div>
</div>