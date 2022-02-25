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

    // TODO: Duplicated method
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
            const { data, ...pager } = res.data.leaves;
            $scope.leaves = data;
            $scope.pager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    // TODO: Duplicated method
    $scope.getDataWithURL = function(URL, cb) {
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

        $http.get(`${CONFIG.baseUrl}/cancellations/${personId}/person?year=${$scope.cboYear}&type=${$scope.cboLeaveType}`)
        .then(function(res) {
            const { data, ...pager } = res.data.cancellations;

            $scope.cancellations = data;
            $scope.cancelPager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.showCancelForm = function(leave) {
        $scope.leave = leave;

        $scope.cancelReason = '';
        $scope.cboStartPeriod = leave.start_period.toString();
        $scope.cboEndPeriod = leave.end_period.toString();

        $('#from_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(leave.start_date).toDate());

        $('#to_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(leave.end_date).toDate());

        $('#add-form').modal('show');
    };

    $scope.onEdit = function(leave) {
        $scope.leave = leave;

        $scope.cancelReason = leave.cancellation[0].reason;
        $scope.cboStartPeriod = leave.cancellation[0].start_period.toString();
        $scope.cboEndPeriod = leave.cancellation[0].end_period.toString();

        $('#s_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(leave.cancellation[0].start_date).toDate());

        $('#e_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(leave.cancellation[0].end_date).toDate());

        $('#edit-form').modal('show');
    };

    $scope.onDelete = function(e, id) {
        e.preventDefault();

        if (window.confirm(`คุณต้องลบรายการขอยกเลิกวันลาเลขที่ ${id} ใช่หรือไม่?`)) {
            $('#frmDelete').submit();
        }
    };
});