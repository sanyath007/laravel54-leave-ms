app.controller('cancelCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, ReportService, PaginateService) {
    /** ################################################################################## */
    $scope.loading = false;
    $scope.cboYear = parseInt(moment().format('MM')) > 9
                        ? (moment().year() + 544).toString()
                        : (moment().year() + 543).toString();
    $scope.cboMonth = moment().format('MM');
    $scope.cboLeaveType = "";
    $scope.cboLeaveStatus = "";
    $scope.cboMenu = "";
    $scope.searchKeyword = "";
    $scope.cboQuery = "";
    $scope.budgetYearRange = [2560,2561,2562,2563,2564,2565,2566,2567];

    $scope.leaves = [];
    $scope.pager = [];

    $scope.cancellations = [];
    $scope.cancelPager = [];

    $scope.cboStartPeriod = '';
    $scope.cboEndPeriod = '';
    $scope.cancelReason = '';

    /** ============================== Init Form elements ============================== */
    let dtpOptions = {
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true,
        todayBtn: true,
        todayHighlight: true
    };

    $('#start_period').prop("disabled", true);

    $('#from_date')
        .datepicker(dtpOptions)
        .on('show', function (e) {
            /** If input is disabled user cannot select date  */
            const isDisabled = $(e.currentTarget).is('.disabled');

            $('.day').click(function(event) {
                if (isDisabled) {
                    alert('ไม่สามารถแก้ไชวันที่ได้');

                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        })
        .on('changeDate', function(event) {
            console.log(event.date);
        });

    $('#to_date')
        .datepicker(dtpOptions)
        .on('show', function (e) {
            /** If input is disabled user cannot select date  */
            const isDisabled = $(e.currentTarget).is('.disabled');

            $('.day').click(function(event) {
                if (isDisabled) {
                    alert('ไม่สามารถแก้ไชวันที่ได้');

                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        })
        .on('changeDate', function(event) {
            console.log(event.date);

            /** Clear value of .select2 */
            $('#end_period').val(null).trigger('change');
        });

    $scope.isOnlyOneDay = function (sDate, eDate){
        return moment(eDate).diff(moment(sDate), "day") === 0;
    };

    // Duplicated methods in leaveCtrl
    const getHolidays = async function () {
        const res = await $http.get(`${CONFIG.baseUrl}/holidays?year=${$scope.cboYear}`);

        return res.data;
    };

    // Duplicated methods in leaveCtrl
    $scope.holidays = null;
    const calculateWorkingDays = async function(sdate, edate, endPeriod) {
        let working_days = 0;
        const { holidays } = await getHolidays();

        $scope.holidays = holidays.map(holiday => holiday.holiday_date);

        if ($scope.holidays) {
            let startDate = moment(sdate);
            let endDate = moment(edate);
            let workDays = [];

            while(startDate <= endDate) {
                if (!$scope.holidays.includes(startDate.format('YYYY-MM-DD'))) {
                    workDays.push(startDate.format('YYYY-MM-DD'));

                    if (startDate.isSame(endDate)) {
                        if (endPeriod !== 1) {
                            working_days += 0.5;
                        } else {
                            working_days += 1;
                        }
                    } else {
                        working_days++;
                    }
                }

                startDate.add(1, "day");
            }
        }

        return working_days;
    };

    // Duplicated methods in leavelCtrl
    $scope.calculateLeaveDays = async function(sDateStr, eDateStr, endPeriod) {
        let sdate = StringFormatService.convToDbDate($(`#${sDateStr}`).val());
        let edate = StringFormatService.convToDbDate($(`#${eDateStr}`).val());
        let days = moment(edate).diff(moment(sdate), 'days');
        let working_days = 0;

        if (parseInt(endPeriod) !== 1) {
            days += 0.5;
        } else {
            days += 1;
        }

        console.log($scope.leave);

        $scope.leave.leave_days = days;
        $('#leave_days').val(days);

        /** ตรวจสอบวันทำการ */
        working_days = await calculateWorkingDays(sdate, edate, parseInt(endPeriod));
        $scope.leave.working_days = working_days;
        $('#working_days').val(working_days);
    };

    // TODO: Duplicated method in leavelCtrl
    $scope.getLeaves = function() {
        $scope.leaves = [];
        $scope.pager = null;
        
        $scope.loading = true;
        
        let year    = $scope.cboYear === '' ? 0 : $scope.cboYear;
        let type    = $scope.cboLeaveType === '' ? 0 : $scope.cboLeaveType;
        let status  = $scope.cboLeaveStatus === '' ? '-' : $scope.cboLeaveStatus;
        let menu    = $scope.cboMenu === '' ? 0 : $scope.cboMenu;
        let query   = $scope.cboQuery === '' ? '' : `?${$scope.cboQuery}`;
        
        $http.get(`${CONFIG.baseUrl}/leaves/search/${year}/${type}/${status}/${menu}${query}`)
        .then(function(res) {
            $scope.setLeaves(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.getCancellations = function(personId) {
        $http.get(`${CONFIG.baseUrl}/cancellations/${personId}/person?year=${$scope.cboYear}&type=${$scope.cboLeaveType}`)
        .then(function(res) {
            $scope.setCancellations(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.setLeaves = function (res) {
        console.log(res);
        const { data, ...pager } = res.data.leaves;
        $scope.leaves = data;
        $scope.pager = pager;
    };

    $scope.setCancellations = function(res) {
        console.log(res);
        const { data, ...pager } = res.data.cancellations;
        $scope.cancellations = data;
        $scope.cancelPager = pager;
    };

    // TODO: Duplicated method
    $scope.getDataWithURL = function(e, URL, cb) {
        /** Check whether parent of clicked a tag is .disabled just do nothing */
        if ($(e.currentTarget).parent().is('li.disabled')) return;

        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            cb(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.isUnCancel = function(endDate) {
        return moment(endDate).isBefore(moment());
    };

    $scope.onLoad = function(personId) {
        $scope.cboLeaveStatus = '3';
        $scope.cboMenu = "";

        $scope.getLeaves();
        $scope.getCancellations(personId);
    };

    $scope.showCancelForm = function(leave) {
        $scope.leave = leave;

        $scope.cancelReason = '';
        $scope.cboStartPeriod = leave.start_period.toString();
        $scope.cboEndPeriod = leave.end_period.toString();

        $('#from_date').datepicker(dtpOptions).datepicker('update', moment(leave.start_date).toDate());

        $('#to_date').datepicker(dtpOptions).datepicker('update', moment(leave.end_date).toDate());

        $('#add-form').modal('show');
    };

    $scope.onEdit = function(leave) {
        $scope.leave = leave;

        $scope.cancelReason = leave.cancellation[0].reason;
        $scope.cboStartPeriod = leave.cancellation[0].start_period.toString();
        $scope.cboEndPeriod = leave.cancellation[0].end_period.toString();

        $('#s_date').datepicker(dtpOptions).datepicker('update', moment(leave.cancellation[0].start_date).toDate());

        $('#e_date').datepicker(dtpOptions).datepicker('update', moment(leave.cancellation[0].end_date).toDate());

        $('#edit-form').modal('show');
    };

    $scope.onDelete = function(e, id) {
        e.preventDefault();

        const actionUrl = $('#frmDelete').attr('action');
        $('#frmDelete').attr('action', `${actionUrl}/${id}`);

        if (window.confirm(`คุณต้องลบรายการขอยกเลิกวันลาเลขที่ ${id} ใช่หรือไม่?`)) {
            $('#frmDelete').submit();
        }
    };
});
