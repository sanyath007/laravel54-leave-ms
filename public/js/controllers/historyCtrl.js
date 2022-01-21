app.controller('historyCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, PaginateService) {
/** ################################################################################## */
    $scope.leaves = [];
    $scope.pager = [];
    $scope.loading = false;

    $scope.getData = function() {
        $scope.loading = true;

        $http.get(CONFIG.baseUrl+ '/histories/1300200009261/2565/person')
        .then(function(res) {
            const { data, ...pager } = res.data.leaves;

            $scope.leaves = data;
            $scope.pager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getArrearWithURL = function(URL) {
        $scope.debts = [];
        $scope.pager = [];
        $scope.loading = true;
            
        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.debts.data;
            $scope.pager = res.data.debts;
            $scope.totalDebt = res.data.totalDebt;

            $scope.pages = PaginateService.createPagerNo($scope.pager);

            console.log($scope.pages);
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