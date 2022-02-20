app.controller('approvalCtrl', function($scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.leave = null;
    $scope.leaves = [];
    $scope.pager = null;
    $scope.cancellations = [];
    $scope.cancelPager = [];
    $scope.cboLeaveType = "";
    $scope.cboLeaveStatus = "";
    $scope.cboMenu = "";
    $scope.searchKeyword = "";
    $scope.cboQuery = "";
    $scope.showAllApproves = true;
    $scope.showAllCancels = true;

    // TODO: Duplicated method
    $scope.getAll = function(event) {
        $scope.leaves = [];
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
    }

    // TODO: Duplicated method
    const getCancellation = function(isApproval=false) {
        $scope.cancellations = [];
        $scope.cancelPager = null;
        $scope.loading = true;

        let year    = $scope.cboYear === '' ? 0 : $scope.cboYear;
        let type    = $scope.cboLeaveType === '' ? 0 : $scope.cboLeaveType;
        let status  = $scope.showAllCancels ? '5&8&9' : '5';
        let menu    = "1";

        $http.get(`${CONFIG.baseUrl}/leaves/search/${year}/${type}/${status}/${menu}`)
        .then(function(res) {
            const { data, ...pager } = res.data.leaves;

            if (isApproval) {
                $scope.cancellations = data;
            } else {
                $scope.cancellations = data.filter(cancel => {
                    return cancel.cancellation.every(c => c.received_date === null);
                });
            }

            $scope.cancelPager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    // TODO: Duplicated method
    $scope.setLeaves = function(res) {
        const { data, ...pager } = res.data.leaves;
        $scope.leaves = data;
        $scope.pager = pager;
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

    $scope.onCommentLoad = function(depart) {
        $scope.cboYear = '2565';
        $scope.cboLeaveStatus = $scope.showAllApproves ? '0&1&7' : '0';
        $scope.cboMenu = "1";
        $scope.cboQuery = `depart=${depart}`;

        $scope.getAll();

        getCancellation();
    };

    $scope.showCommentForm = function(leave, type) {
        $scope.leave = leave;

        if (type === 1) {
            $('#comment-form').modal('show');
        } else {
            $('#cancel-comment-form').modal('show');
        }
    };
});