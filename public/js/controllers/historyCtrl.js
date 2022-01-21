app.controller('historyCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, PaginateService) {
/** ################################################################################## */
    $scope.leaves = [];
    $scope.pager = [];
    $scope.loading = false;

    $scope.cboLeaveType = '';

    $scope.getData = function() {
        $scope.loading = true;

        const type = $scope.cboLeaveType === '' ? '' : $scope.cboLeaveType;

        $http.get(`${CONFIG.baseUrl}/histories/1300200009261/2565/person?type=${type}`)
        .then(function(res) {
            console.log(res.data.leaves);
            const { data, ...pager } = res.data.leaves;

            $scope.leaves = data;
            $scope.pager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.leaves = [];
        $scope.pager = [];
        $scope.loading = true;
        
        const type = $scope.cboLeaveType === '' ? '' : $scope.cboLeaveType;
        console.log(`${URL}&type=${type}`);

        $http.get(`${URL}&type=${type}`)
        .then(function(res) {
            console.log(res);
            const { data, ...pager } = res.data.leaves;

            $scope.leaves = data;
            $scope.pager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.arrearToExcel = function(URL) {
        console.log($scope.debts);

        if($scope.debts.length == 0) {
            toaster.pop('warning', "", "ไม่พบข้อมูล !!!");
        } else {
            var debtDate = ($("#debtDate").val()).split(",");
            var sDate = debtDate[0].trim();
            var eDate = debtDate[1].trim();
            var debtType = ($("#debtType").val() == '') ? '0' : $("#debtType").val();
            var creditor = ($("#creditor").val() == '') ? '0' : $("#creditor").val();
            var showAll = ($("#showall:checked").val() == 'on') ? 1 : 0;

            window.location.href = CONFIG.BASE_URL +URL+ '/' +debtType+ '/' +creditor+ '/' +sDate+ '/' +eDate+ '/' + showAll;
        }
    };
});