app.controller('historyCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, PaginateService) {
/** ################################################################################## */
    $scope.deprecTypes = [];
    $scope.assets = [];
    $scope.pager = [];
    $scope.loading = false;

    $scope.getData = function(event) {
        $scope.assets = [];
        $scope.loading = true;

        $http.get(CONFIG.baseUrl+ '/deprec/search')
        .then(function(res) { 
            console.log(res);           
            $scope.deprecTypes = res.data.deprecTypes;
            $scope.assets = res.data.assets.data;
            $scope.pager = res.data.assets;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.deprecCalulate = function () {
        $scope.loading = true;

        if($scope.assets) {
            $scope.assets.map(function(asset) {
                deprecType = $scope.deprecTypes.find(type => asset.parcel.deprec_type === type.deprec_type_id);
                asset['deprec_life_y'] = deprecType.deprec_life_y;

                asset['age_y'] = moment().diff(asset.date_in, 'years');
                asset['age_m'] = moment().diff(asset.date_in, 'months')-(asset.age_y*12);
                asset['deprec_year'] = (asset.unit_price/deprecType.deprec_life_y).toFixed(2);
                asset['deprec_collect'] = ((asset.deprec_year*asset.age_y)+(asset.deprec_year*asset.age_m/12)).toFixed(2);
                asset['deprec_net'] = (asset.unit_price-asset.deprec_collect).toFixed(2);
            });

            $scope.loading = false;
        }
    }

    $scope.store = function(URL) {
        // $scope.loading = true;
        console.log($scope.assets);

        // $http.post(CONFIG.baseUrl+ '/deprec/search', $scope.assets)
        // .then(function(res) {            
        //     $scope.assets = res.data.assets.data;
        //     $scope.pager = res.data.assets;

        //     $scope.loading = false;
        // }, function(err) {
        //     console.log(err);
        //     $scope.loading = false;
        // });
    }

    $scope.getArrearData = function(URL) {
        $scope.debts = [];
        $scope.pager = [];
        
        if($("#showall:checked").val() != 'on' && ($("#debtType").val() == '' && $("#creditor").val() == '')) {
            toaster.pop('warning', "", "กรุณาเลือกเจ้าหนี้หรือประเภทหนี้ก่อน !!!");
        } else {
            $scope.loading = true;

            var debtDate = ($("#debtDate").val()).split(",");
            var sDate = debtDate[0].trim();
            var eDate = debtDate[1].trim();
            var debtType = ($("#debtType").val() == '') ? '0' : $("#debtType").val();
            var creditor = ($("#creditor").val() == '') ? '0' : $("#creditor").val();
            var showAll = ($("#showall:checked").val() == 'on') ? 1 : 0;
            
            $http.get(CONFIG.BASE_URL +URL+ '/' +debtType+ '/' +creditor+ '/' +sDate+ '/' +eDate+ '/' + showAll)
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
        }
    };

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