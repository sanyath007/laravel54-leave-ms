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
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">#</th>
                                <th style="text-align: center;">ชื่อ-สกุล</th>
                                <th style="width: 20%; text-align: center;">ตำแหน่ง</th>
                                <th style="width: 20%; text-align: center;">สังกัด</th>
                                <th style="width: 10%; text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, person) in persons">
                                <td style="text-align: center;">@{{index+1}}</td>
                                <td></td>
                                <td style="text-align: center;"></td>
                                <td style="text-align: center;"></td>
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
                    <button class="btn btn-danger" data-dismiss="modal" aria-label="Close">ปิด</button>
                </div><!-- /.modal-footer -->
            </form>
        </div>
    </div>
</div>

<!-- <script type="text/javascript">
	$(function() {
		$('#leave_date').datepicker({
			autoclose: true,
			language: 'th',
			format: 'dd/mm/yyyy',
			thaiyear: true
		}).datepicker('update', new Date());

        $('#leave_doc_date').datepicker({
			autoclose: true,
			language: 'th',
			format: 'dd/mm/yyyy',
			thaiyear: true
		}).datepicker('update', new Date());
	});
</script> -->