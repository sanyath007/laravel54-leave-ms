app.controller('assetUnitCtrl', function($scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.units = [];
    $scope.pager = [];
    
    $scope.unit = {
        unit_id: '',
        unit_name: '',
    };

    $scope.getData = function(event) {
        console.log(event);
        $scope.units = [];
        $scope.loading = true;
        
        let searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        $http.get(CONFIG.baseUrl+ '/asset-unit/search/' +searchKey)
        .then(function(res) {
            console.log(res);
            $scope.units = res.data.units.data;
            $scope.pager = res.data.units;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        console.log(URL);
        $scope.units = [];
        $scope.loading = true;

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.units = res.data.units.data;
            $scope.pager = res.data.units;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.add = function(event, form) {
        event.preventDefault();
        console.log(form);
        console.log($scope.unit);

        if (form.$invalid) {
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {
            $http.post(CONFIG.baseUrl + '/asset-unit/store', $scope.unit)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });            
        }

        document.getElementById('frmNewAssetUnit').reset();
    }

    $scope.getAssetUnit = function(unitId) {
        $http.get(CONFIG.baseUrl + '/asset-cate/get-asset-cate/' +unitId)
        .then(function(res) {
            console.log(res);
            $scope.cate = res.data.cate;
        }, function(err) {
            console.log(err);
        });
    }

    $scope.edit = function(unitId) {
        console.log(unitId);

        window.location.href = CONFIG.baseUrl + '/asset-cate/edit/' + unitId;
    };

    $scope.update = function(event, form, unitId) {
        console.log(unitId);
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + unitId + " ใช่หรือไม่?")) {
            $http.put(CONFIG.baseUrl + '/asset-unit/update/', $scope.unit)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
    };

    $scope.delete = function(unitId) {
        console.log(unitId);

        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + unitId + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/asset-unit/delete/' +unitId)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');
                $scope.getData();
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
    };
});