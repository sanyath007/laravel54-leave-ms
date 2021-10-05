<div class="modal fade" id="person-list" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <h5 class="modal-title">รายชื่อบุคลากร</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 0;">
                    <!-- // TODO: Filtering controls -->
                    <div class="row">
                        <div class="col-md-12">
                            
                        </div>
                    </div>
                    <!-- // TODO: Filtering controls -->

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">#</th>
                                <th style="text-align: center;">ชื่อ-สกุล</th>
                                <th style="width: 25%; text-align: center;">ตำแหน่ง</th>
                                <th style="width: 30%;">สังกัด</th>
                                <th style="width: 10%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, person) in persons">
                                <td style="text-align: center;">
                                    @{{ pager.from + index }}
                                </td>
                                <td>
                                    @{{ person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname }}
                                </td>
                                <td style="text-align: center;">
                                    @{{ person.position.position_name + person.academic.ac_name }}
                                </td>
                                <td style="text-align: center;">
                                    @{{ person.member_of.depart.depart_name }}
                                </td>
                                <td style="text-align: center;">
                                    <a href="#" class="btn btn-primary" ng-click="onSelectedDelegatePerson($event, person)">
                                        เลือก
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /.modal-body -->
                <div class="modal-footer" style="padding-bottom: 8px;">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="pagination pagination-sm no-margin pull-left">
                                <li ng-if="pager.current_page !== 1">
                                    <a ng-click="getDataWithURL(pager.path + '?page=1')" aria-label="Previous">
                                        <span aria-hidden="true">First</span>
                                    </a>
                                </li>

                                <li ng-class="{'disabled': (pager.current_page==1)}">
                                    <a ng-click="getDataWithURL(pager.first_page_url)" aria-label="Prev">
                                        <span aria-hidden="true">Prev</span>
                                    </a>
                                </li>

                                <li ng-if="pager.current_page < pager.last_page && (pager.last_page - pager.current_page) > 10">
                                    <a href="@{{ pager.url(pager.current_page + 10) }}">
                                        ...
                                    </a>
                                </li>

                                <li ng-class="{'disabled': (pager.current_page==pager.last_page)}">
                                    <a ng-click="getDataWithURL(pager.next_page_url)" aria-label="Next">
                                        <span aria-hidden="true">Next</span>
                                    </a>
                                </li>

                                <li ng-if="pager.current_page !== pager.last_page">
                                    <a ng-click="getDataWithURL(pager.path + '?page=' + pager.last_page)" aria-label="Previous">
                                        <span aria-hidden="true">Last</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                ปิด
                            </button>
                        </div>
                    </div>
                </div><!-- /.modal-footer -->
            </form>
        </div>
    </div>
</div>
