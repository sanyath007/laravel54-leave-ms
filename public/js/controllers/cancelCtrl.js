app.controller('cancelCtrl', function(CONFIG, $scope, $http, toaster, StringFormatService, PaginateService) {
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
    
    $scope.cancellation = {
        leave_id: '',
        reason: '',
        start_date: '',
        end_date: '',
        start_period: '',
        end_period: '',
        days: 0,
        working_days: 0,
    };

    /** ============================== Init Form elements ============================== */
    let dtpOptions = {
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true,
        todayBtn: true,
        todayHighlight: true
    };

    /** ==================== Add form ==================== */
    $('#start_period').prop("disabled", true);

    $('#start_date')
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
            if (
                moment(event.date).isBefore(moment($scope.leave.start_date)) ||
                moment(event.date).isAfter(moment($scope.leave.end_date))
            ) {
                alert('ไม่สามารถเลือกวันที่ไม่อยู่ระหว่างวันที่ลาได้!!');

                $('#start_date').datepicker('update', moment($scope.leave.start_date).toDate());
            }

            /** Clear value of .select2 */
            $('#end_period').val(null).trigger('change');
        });

    $('#end_date')
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
            if (
                moment(event.date).isBefore(moment($scope.leave.start_date)) ||
                moment(event.date).isAfter(moment($scope.leave.end_date))
            ) {
                alert('ไม่สามารถเลือกวันที่ไม่อยู่ระหว่างวันที่ลาได้!!');

                $('#end_date').datepicker('update', moment($scope.leave.end_date).toDate());
            }

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

        $scope.cancellation.leave_days = days;
        $('#days').val(days);

        /** ตรวจสอบวันทำการ */
        working_days = await calculateWorkingDays(sdate, edate, parseInt(endPeriod));
        $scope.cancellation.working_days = working_days;
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

        $scope.cancellation.leave_id = leave.id;
        $scope.cancellation.reason = '';
        $scope.cancellation.start_date = leave.start_date;
        $scope.cancellation.end_date = leave.end_date;
        $scope.cancellation.start_period = leave.start_period.toString();
        $scope.cancellation.end_period = leave.end_period.toString();
        $scope.cancellation.days = leave.leave_days;
        $scope.cancellation.working_days = leave.working_days;

        $('#start_date').datepicker(dtpOptions).datepicker('update', moment(leave.start_date).toDate());

        $('#end_date').datepicker(dtpOptions).datepicker('update', moment(leave.end_date).toDate());

        $('#add-form').modal('show');
    };

    $scope.store = function(event, form) {
        event.preventDefault();

        $(`#${form}`).submit();
    }

    $scope.onEdit = function(leave) {
        $scope.leave = leave;

        $scope.cancellation.leave_id = leave.id;
        $scope.cancellation.reason = leave.cancellation[0].reason;;
        $scope.cancellation.start_date = StringFormatService.convFromDbDate(leave.cancellation[0].start_date);
        $scope.cancellation.end_date = StringFormatService.convFromDbDate(leave.cancellation[0].end_date);
        $scope.cancellation.start_period = leave.cancellation[0].start_period.toString();
        $scope.cancellation.end_period = leave.cancellation[0].end_period.toString();
        $scope.cancellation.days = leave.cancellation[0].days;
        $scope.cancellation.working_days = leave.cancellation[0].working_days;

        $('#start_date')
            .datepicker(dtpOptions)
            .datepicker('update', moment(leave.cancellation[0].start_date).toDate());

        $('#end_date')
            .datepicker(dtpOptions)
            .datepicker('update', moment(leave.cancellation[0].end_date).toDate());
    };

    $scope.update = function(event, form) {
        event.preventDefault();

        if(confirm(`คุณต้องแก้ไขรายการขอยกเลิกวันลาเลขที่ ${$scope.cancellation.leave_id} ใช่หรือไม่?`)) {
            $(`#${form}`).submit();
        }
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
