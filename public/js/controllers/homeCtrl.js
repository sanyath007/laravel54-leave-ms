app.controller('homeCtrl', function(CONFIG, $scope, $http, StringFormatService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.pieOptions = {};
    $scope.barOptions = {};
    $scope.headLeaves = [];
    $scope.pager = null;
    $scope.departs = [];
    $scope.departPager = null;

    $('#cboHeadDate').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        orientation: 'bottom',
        thaiyear: true
    })
    .datepicker('update', moment().toDate())
    .on('changeDate', function(event) {
        $scope.getHeadLeaves();
    });

    $('#cboDepartDate').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        orientation: 'bottom',
        thaiyear: true
    })
    .datepicker('update', moment().toDate())
    .on('changeDate', function(event) {
        $scope.getDepartLeaves();
    });

    $scope.getHeadLeaves = function() {
        $scope.loading = true;

        let date = $('#cboHeadDate').val() !== ''
                    ? StringFormatService.convToDbDate($('#cboHeadDate').val())
                    : moment().format('YYYY-MM-DD');

        $http.get(`${CONFIG.baseUrl}/dashboard/head/${date}`)
        .then(function(res) {
            $scope.setHeadLeaves(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.setHeadLeaves = function(res) {
        let { data, ...pager } = res.data.leaves;

        data.forEach(leave => {
            leave.person = res.data.persons.find(person => person.person_id === leave.leave_person);
        });

        $scope.headLeaves = data;
        $scope.pager = pager;
    };

    $scope.getDepartLeaves = function() {
        $scope.loading = true;

        let date = $('#cboDepartDate').val() !== ''
                    ? StringFormatService.convToDbDate($('#cboDepartDate').val())
                    : moment().format('YYYY-MM-DD');

        $http.get(`${CONFIG.baseUrl}/dashboard/depart/${date}`)
        .then(function(res) {
            $scope.setDepartLeaves(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.departTotal = 0;
    $scope.setDepartLeaves = function (res) {
        let { data, ...pager } = res.data.departs;

        data.forEach(depart => {
            depart.sum_leave = res.data.leaves.reduce((sum, leave) => {
                if (depart.depart_id == leave.person.member_of.depart_id) {
                    sum++;
                }

                return sum;
            }, 0);
        });

        $scope.departs = data;
        $scope.departPager = pager;

        $scope.departTotal = res.data.leaves.reduce((sum, leave) => {
            return sum = sum + 1;
        }, 0);
    };

    $scope.statCards = [];
    $scope.getStatYear = function () {
        $scope.loading = true;

        let year = '2565';

        $http.get(`${CONFIG.baseUrl}/dashboard/stat/${year}`)
        .then(function(res) {
            $scope.statCards = res.data.stats;

            $scope.loading = false;
        }, function(err) {
            console.log(err);

            $scope.loading = false;
        });
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

    $scope.getSumMonthData = function () {
        var month = '2018';
        console.log(month);

        ReportService.getSeriesData('/report/sum-month-chart/', month)
        .then(function(res) {
            console.log(res);
            var debtSeries = [];
            var paidSeries = [];
            var setzeroSeries = [];

            angular.forEach(res.data, function(value, key) {
                let debt = (value.debt) ? parseFloat(value.debt.toFixed(2)) : 0;
                let paid = (value.paid) ? parseFloat(value.paid.toFixed(2)) : 0;
                let setzero = (value.setzero) ? parseFloat(value.setzero.toFixed(2)) : 0;

                debtSeries.push(debt);
                paidSeries.push(paid);
                setzeroSeries.push(setzero);
            });

            var categories = ['??????', '??????', '??????', '??????', '??????', '?????????', '?????????', '??????', '?????????', '??????', '??????', '??????']
            $scope.barOptions = ReportService.initBarChart("barContainer1", "???????????????????????????????????????????????????????????? ???????????? 2561", categories, '???????????????');
            $scope.barOptions.series.push({
                name: '?????????????????????????????????',
                data: debtSeries
            }, {
                name: '????????????????????????',
                data: paidSeries
            }, {
                name: '?????????????????????????????????',
                data: setzeroSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getSumYearData = function () {       
        var month = '2018';
        console.log(month);

        ReportService.getSeriesData('/report/sum-year-chart/', month)
        .then(function(res) {
            console.log(res);
            var debtSeries = [];
            var paidSeries = [];
            var setzeroSeries = [];
            var categories = [];

            angular.forEach(res.data, function(value, key) {
                let debt = (value.debt) ? parseFloat(value.debt.toFixed(2)) : 0;
                let paid = (value.paid) ? parseFloat(value.paid.toFixed(2)) : 0;
                let setzero = (value.setzero) ? parseFloat(value.setzero.toFixed(2)) : 0;

                categories.push(parseInt(value.yyyy) + 543);
                debtSeries.push(debt);
                paidSeries.push(paid);
                setzeroSeries.push(setzero);
            });

            $scope.barOptions = ReportService.initBarChart("barContainer2", "??????????????????????????????????????????????????????", categories, '???????????????');
            $scope.barOptions.series.push({
                name: '?????????????????????????????????',
                data: debtSeries
            }, {
                name: '????????????????????????',
                data: paidSeries
            }, {
                name: '?????????????????????????????????',
                data: setzeroSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getPeriodData = function () {
        var selectMonth = document.getElementById('selectMonth').value;
        var month = (selectMonth == '') ? moment().format('YYYY-MM') : selectMonth;
        console.log(month);

        ReportService.getSeriesData('/report/period-chart/', month)
        .then(function(res) {
            console.log(res);
            
            var categories = [];
            var nSeries = [];
            var mSeries = [];
            var aSeries = [];
            var eSeries = [];

            angular.forEach(res.data, function(value, key) {
                categories.push(value.d);
                nSeries.push(value.n);
                mSeries.push(value.m);
                aSeries.push(value.a);
                eSeries.push(value.e);
            });

            $scope.barOptions = ReportService.initStackChart("barContainer", "?????????????????????????????????????????????????????? ?????????????????????????????????", categories, '???????????????????????????????????????????????????');
            $scope.barOptions.series.push({
                name: '00.00-08.00???.',
                data: nSeries
            }, {
                name: '08.00-12.00???.',
                data: mSeries
            }, {
                name: '12.00-16.00???.',
                data: aSeries
            }, {
                name: '16.00-00.00???.',
                data: eSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getDepartData = function () {
        var selectMonth = document.getElementById('selectMonth').value;
        var month = (selectMonth == '') ? moment().format('YYYY-MM') : selectMonth;
        console.log(month);

        ReportService.getSeriesData('/report/depart-chart/', month)
        .then(function(res) {
            console.log(res);
            var dataSeries = [];

            $scope.pieOptions = ReportService.initPieChart("pieContainer", "?????????????????????????????????????????????????????? ?????????????????????????????????");
            angular.forEach(res.data, function(value, key) {
                $scope.pieOptions.series[0].data.push({name: value.depart, y: value.request});
            });

            var chart = new Highcharts.Chart($scope.pieOptions);
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getReferData = function () {
        var selectMonth = document.getElementById('selectMonth').value;
        var month = (selectMonth == '') ? moment().format('YYYY-MM') : selectMonth;
        console.log(month);

        ReportService.getSeriesData('/report/refer-chart/', month)
        .then(function(res) {
            console.log(res);
            var nSeries = [];
            var mSeries = [];
            var aSeries = [];
            var eSeries = [];
            var categories = [];

            angular.forEach(res.data, function(value, key) {
                categories.push(value.d)
                nSeries.push(value.n);
                mSeries.push(value.m);
                aSeries.push(value.a);
            });

            $scope.barOptions = ReportService.initStackChart("barContainer", "??????????????????????????????????????????????????????????????????????????????????????????-???????????????????????????????????????", categories, '??????????????? Refer');
            $scope.barOptions.series.push({
                name: '??????????????????',
                data: nSeries
            }, {
                name: '?????????????????????',
                data: mSeries
            }, {
                name: '?????????????????????',
                data: aSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getFuelDayData = function () {
        var selectMonth = document.getElementById('selectMonth').value;
        var month = (selectMonth == '') ? moment().format('YYYY-MM') : selectMonth;
        console.log(month);

        ReportService.getSeriesData('/report/fuel-day-chart/', month)
        .then(function(res) {
            console.log(res);
            var nSeries = [];
            var mSeries = [];
            var categories = [];

            angular.forEach(res.data, function(value, key) {
                categories.push(value.bill_date)
                nSeries.push(value.qty);
                mSeries.push(value.net);
            });

            $scope.barOptions = ReportService.initBarChart("barContainer", "??????????????????????????????????????????????????????????????? ??????????????????", categories, '???????????????');
            $scope.barOptions.series.push({
                name: '??????????????????(????????????)',
                data: nSeries
            }, {
                name: '??????????????????(?????????)',
                data: mSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };
});