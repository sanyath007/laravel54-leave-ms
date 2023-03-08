app.controller(
    "reportCtrl",
    function (CONFIG, $scope, $http, toaster, StringFormatService, PaginateService) {
        /** ################################################################################## */
        $scope.leaves = [];
        $scope.data = [];
        $scope.pager = [];
        $scope.initFormValues = null;
        $scope.filteredDeparts = [];
        $scope.filteredDivisions = [];
        $scope.loading = false;

        $scope.cboFaction = '';
        $scope.cboDepart = '';
        $scope.cboDivision = '';
        $scope.dtpYear = parseInt(moment().format('MM')) > 9
                            ? (moment().year() + 544).toString()
                            : (moment().year() + 543).toString();
        $scope.dtpDate = StringFormatService.convFromDbDate(moment().format('YYYY-MM-DD'));
        $scope.dtpMonth = StringFormatService.dbDateToLongThMonth(moment().format('YYYY-MM-DD'));
        $scope.budgetYearRange = [2560,2561,2562,2563,2564,2565,2566,2567];

        let dtpDateOptions = {
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true,
            todayBtn: true,
            todayHighlight: true
        };

        let dtpMonthOptions = {
            autoclose: true,
			format: 'mm/yyyy',
			viewMode: "months", 
			minViewMode: "months",
			language: 'th',
			thaiyear: true,
            orientation: 'bottom'
        };

        $('#dtpDate')
            .datepicker(dtpDateOptions)
            .datepicker('update', new Date())
            .on('changeDate', function(event) {
                $('#dtpDate').datepicker('update', moment(event.date).toDate());

                $scope.getDaily();
            });

        $('#dtpMonth')
            .datepicker(dtpMonthOptions)
            .datepicker('update', new Date())
            .on('changeDate', function(event) {
                $('#dtpMonth').datepicker('update', moment(event.date).toDate());
                $scope.dtpMonth = StringFormatService.dbDateToShortThMonth(moment(event.date).format('YYYY-MM-DD'));
            });

        $scope.initForm = function (initValues) {
            $scope.initFormValues = initValues;

            $scope.filteredDeparts = initValues.departs;
            $scope.filteredDivisions = initValues.divisions;
        };

        $scope.onSelectedFaction = function (faction) {
            $scope.filteredDeparts = $scope.initFormValues.departs.filter(depart => {
                return depart.faction_id === parseInt(faction);
            });
        };

        $scope.onSelectedDepart = function (depart) {
            $scope.filteredDivisions = $scope.initFormValues.divisions.filter(division => {
                return division.depart_id === parseInt(depart);
            });
        };

        $scope.getDaily = function () {
            $scope.data = [];
            $scope.pager = null;
            $scope.loading = true;

            let faction     = !$scope.cboFaction ? '' : $scope.cboFaction;
            let depart      = !$scope.cboDepart ? '' : $scope.cboDepart;
            let division    = !$scope.cboDivision ? '' : $scope.cboDivision;
            let date        = $scope.dtpDate === ''
                                ? moment().format('YYYY-MM-DD')
                                : StringFormatService.convToDbDate($scope.dtpDate);
            let year        = $scope.dtpYear === ''
                                ? $scope.dtpYear = parseInt(moment().format('MM')) > 9
                                    ? moment().year() + 544
                                    : moment().year() + 543 
                                : $scope.dtpYear;

            $http.get(`${CONFIG.baseUrl}/reports/daily-data?year=${year}&faction=${faction}&depart=${depart}&division=${division}&date=${date}`)
            .then(function (res) {
                $scope.setDaily(res);

                $scope.loading = false;
            }, function (err) {
                console.log(err);
                $scope.loading = false;
            });
        };

        $scope.getDailyWithUrl = function (url) {
            $scope.data = [];
            $scope.pager = null;
            $scope.loading = true;

            let faction     = !$scope.cboFaction ? '' : $scope.cboFaction;
            let depart      = !$scope.cboDepart ? '' : $scope.cboDepart;
            let division    = !$scope.cboDivision ? '' : $scope.cboDivision;
            let date        = $scope.dtpDate === ''
                                ? moment().format('YYYY-MM-DD')
                                : StringFormatService.convToDbDate($scope.dtpDate);
            let year        = $scope.dtpYear === ''
                                ? $scope.dtpYear = parseInt(moment().format('MM')) > 9
                                    ? moment().year() + 544
                                    : moment().year() + 543 
                                : $scope.dtpYear;

            $http.get(`${url}&year=${year}&faction=${faction}&depart=${depart}&division=${division}&date=${date}`)
            .then(function (res) {
                    $scope.setDaily(res);

                    $scope.loading = false;
            }, function (err) {
                    console.log(err);
                    $scope.loading = false;
            });
        };

        $scope.setDaily = function (res) {
            const { data, ...pager } = res.data.leaves;

            $scope.data = data;
            $scope.pager = pager;
        };

        $scope.getMonthly = function () {
            $scope.data = [];
            $scope.pager = null;
            $scope.loading = true;

            let faction     = !$scope.cboFaction ? '' : $scope.cboFaction;
            let depart      = !$scope.cboDepart ? '' : $scope.cboDepart;
            let division    = !$scope.cboDivision ? '' : $scope.cboDivision;
            let month       = $('#dtpMonth').val() === ''
                                ? StringFormatService.shortMonthToDbMonth(moment().format('YYYY-MM'))
                                : StringFormatService.shortMonthToDbMonth($('#dtpMonth').val());
            let year        = $scope.dtpYear === ''
                                ? $scope.dtpYear = parseInt(moment().format('MM')) > 9
                                    ? moment().year() + 544
                                    : moment().year() + 543 
                                : $scope.dtpYear;

            $http.get(`${CONFIG.baseUrl}/reports/monthly-data?year=${year}&faction=${faction}&depart=${depart}&division=${division}&month=${month}`)
            .then(function (res) {
                $scope.setMonthly(res);

                $scope.loading = false;
            }, function (err) {
                console.log(err);
                $scope.loading = false;
            });
        };

        $scope.getMonthlyWithUrl = function (url) {
            $scope.data = [];
            $scope.pager = null;
            $scope.loading = true;

            let faction     = !$scope.cboFaction ? '' : $scope.cboFaction;
            let depart      = !$scope.cboDepart ? '' : $scope.cboDepart;
            let division    = !$scope.cboDivision ? '' : $scope.cboDivision;
            let month       = $('#dtpMonth').val() === ''
                                ? StringFormatService.shortMonthToDbMonth(moment().format('YYYY-MM'))
                                : StringFormatService.shortMonthToDbMonth($('#dtpMonth').val());
            let year        = $scope.dtpYear === ''
                                ? $scope.dtpYear = parseInt(moment().format('MM')) > 9
                                    ? moment().year() + 544
                                    : moment().year() + 543 
                                : $scope.dtpYear;

            $http.get(`${url}&year=${year}&faction=${faction}&depart=${depart}&division=${division}&month=${month}`)
            .then(function (res) {
                    $scope.setMonthly(res);

                    $scope.loading = false;
            }, function (err) {
                    console.log(err);
                    $scope.loading = false;
            });
        };

        $scope.setMonthly = function(res) {
            const { leaves, histories, persons } = res.data;
            const { data, ...pager } = persons;

            $scope.data = data;
            $scope.pager = pager;

            /** Append leave data to each person */
            $scope.data = data.map(person => {
                const leave = leaves.find((leave) =>
                    person.person_id === leave.leave_person
                );
                return {
                    ...person,
                    leave,
                };
            });
        };

        $scope.getSummary = function () {
            let faction     = !$scope.cboFaction ? '' : $scope.cboFaction;
            let depart      = !$scope.cboDepart ? '' : $scope.cboDepart;
            let division    = !$scope.cboDivision ? '' : $scope.cboDivision;
            let year        = $scope.dtpYear === ''
                                ? $scope.dtpYear = parseInt(moment().format('MM')) > 9
                                    ? moment().year() + 544
                                    : moment().year() + 543 
                        : $scope.dtpYear;

            $http.get(`${CONFIG.baseUrl}/reports/summary-data?faction=${faction}&depart=${depart}&division=${division}&year=${year}`)
            .then(function (res) {
                const { leaves, histories, vacations, persons } = res.data;
                const { data, ...pager } = persons;

                $scope.data = data;
                $scope.pager = pager;

                /** Set each history's days instead of leave_days value */
                leaves.map(leave => {
                    const leaveHistory = histories.find(history => history.person_id === leave.leave_person);

                    leave['ill_days'] = leaveHistory['ill_days'];
                    leave['per_days'] = leaveHistory['per_days'];
                    leave['vac_days'] = leaveHistory['vac_days'];
                    leave['lab_days'] = leaveHistory['lab_days'];
                    leave['hel_days'] = leaveHistory['hel_days'];
                    leave['ord_days'] = leaveHistory['ord_days'];

                    return leave;
                });

                /** Append leave data to each person */
                $scope.data = data.map(person => {
                    const leave = leaves.find((leave) => person.person_id === leave.leave_person);
                    const vacation = vacations.find((vacation) => person.person_id === vacation.person_id);

                    return {
                        ...person,
                        leave,
                        vacation,
                    };
                });

                $scope.loading = false;
            }, function (err) {
                console.log(err);
                $scope.loading = false;
            });
        };

        $scope.getDataWithURL = function (URL) {
            $scope.data = [];
            $scope.pager = [];
            $scope.loading = true;

            let depart = $scope.cboDepart === '' ? '' : $scope.cboDepart;
            let division = !$scope.cboDivision ? '' : $scope.cboDivision;
            let month       = $('#dtpMonth').val() === ''
                                ? StringFormatService.shortMonthToDbMonth(moment().format('YYYY-MM'))
                                : StringFormatService.shortMonthToDbMonth($('#dtpMonth').val());

            $http.get(`${URL}&depart=${depart}&division=${division}&year=${year}`)
            .then(function (res) {
                    console.log(res);
                    const { data, ...pager } = res.data.persons;
                    $scope.data = data;
                    $scope.pager = pager;

                    $scope.data = data.map((person) => {
                        const leave = res.data.leaves.find((leave) =>
                            person.person_id === leave.leave_person
                        );
                        return {
                            ...person,
                            leave: leave,
                        };
                    });

                    $scope.loading = false;
            }, function (err) {
                    console.log(err);
                    $scope.loading = false;
            });
        };
    }
);
