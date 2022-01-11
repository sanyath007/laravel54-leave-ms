app.controller(
    "reportCtrl",
    function (CONFIG, $scope, $http, toaster, PaginateService) {
        /** ################################################################################## */
        $scope.leaves = [];
        $scope.data = [];
        $scope.pager = [];
        $scope.loading = false;

        $scope.onSelectedFaction = function () {
            $http
                .get(`${CONFIG.baseUrl}/reports/summary-data?year=${year}`)
                .then(function (res) {
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
                    }
                );
        };

        $scope.getSummary = function () {
            let year = 2565;

            $http
                .get(`${CONFIG.baseUrl}/reports/summary-data?year=${year}`)
                .then(function (res) {
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
                    }
                );
        };

        $scope.getDebtData = function (URL) {
            $scope.debts = [];
            $scope.pager = [];
            $scope.loading = true;

            var debtDate = $("#debtDate").val().split(",");
            var sDate = debtDate[0].trim();
            var eDate = debtDate[1].trim();
            var debtType =
                $("#debtType").val() != "" ? $("#debtType").val() : 0;
            var showAll = $("#showall:checked").val() == "on" ? 1 : 0;

            $http
                .get(`${CONFIG.baseUrl}${URL}/${debtType}/${sDate}/${eDate}/${showAll}`)
                .then(function (res) {
                        console.log(res);
                        $scope.debts = res.data.pager.data;
                        $scope.pager = res.data.pager;

                        $scope.pages = PaginateService.createPagerNo(
                            $scope.pager
                        );

                        console.log($scope.pages);
                        $scope.loading = false;
                    }, function (err) {
                        console.log(err);
                        $scope.loading = false;
                    }
                );
        };

        $scope.getDataWithURL = function (URL) {
            console.log(URL);
            $scope.debts = [];
            $scope.pager = [];
            $scope.loading = true;

            $http.get(URL).then(
                function (res) {
                    console.log(res);
                    $scope.debts = res.data.pager.data;
                    $scope.pager = res.data.pager;

                    $scope.pages = PaginateService.createPagerNo($scope.pager);

                    console.log($scope.pages);
                    $scope.loading = false;
                },
                function (err) {
                    console.log(err);
                    $scope.loading = false;
                }
            );
        };

        $scope.debtCreditorToExcel = function (URL) {
            console.log($scope.debts);

            if ($scope.debts.length == 0) {
                toaster.pop("warning", "", "ไม่พบข้อมูล !!!");
            } else {
                var debtDate = $("#debtDate").val().split(",");
                var sDate = debtDate[0].trim();
                var eDate = debtDate[1].trim();
                var creditor =
                    $("#debtType").val() == "" ? "0" : $("#debtType").val();
                var showAll = $("#showall:checked").val() == "on" ? 1 : 0;

                window.location.href = `${CONFIG.baseUrl}${URL}/${creditor}/${sDate}/${eDate}/${showAll}`;
            }
        };

        $scope.debttypeToExcel = function (URL) {
            console.log($scope.debts);

            if ($scope.debts.length == 0) {
                toaster.pop("warning", "", "ไม่พบข้อมูล !!!");
            } else {
                var debtDate = $("#debtDate").val().split(",");
                var sDate = debtDate[0].trim();
                var eDate = debtDate[1].trim();
                var debtType =
                    $("#debtType").val() == "" ? "0" : $("#debtType").val();
                var showAll = $("#showall:checked").val() == "on" ? 1 : 0;

                window.location.href = `${CONFIG.baseUrl}${URL}/${debtType}/${sDate}/${eDate}/${showAll}`;
            }
        };
    }
);
