app.controller('deprecTypeCtrl', function($scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.pager = [];
    $scope.deprecTypes = [];
    $scope.deprecType = {
        deprec_type_id: '',
        deprec_type_no: '',
        deprec_type_name: '',
        deprec_life_y: '',
        deprec_rate_y: '',
    };

    $scope.getData = function(event) {
        console.log(event);
        $scope.types = [];
        $scope.loading = true;
        
        let searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        $http.get(CONFIG.baseUrl+ '/deprec-type/search/' +searchKey)
        .then(function(res) {
            console.log(res);
            $scope.deprecTypes = res.data.types.data;
            $scope.pager = res.data.types;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        console.log(URL);
        $scope.deprecTypes = [];
        $scope.loading = true;

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.deprecTypes = res.data.types.data;
            $scope.pager = res.data.types;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.add = function(event, form) {
        event.preventDefault();

        $http.post(CONFIG.baseUrl + '/deprec-type/store', $scope.deprecType)
        .then(function(res) {
            console.log(res);
            toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
        }, function(err) {
            console.log(err);
            toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
        });

        document.getElementById(form).reset();
    }

    $scope.getDeprecType = function(typeId) {
        $http.get(CONFIG.baseUrl + '/deprec-type/get-ajax-byid/' +typeId)
        .then(function(res) {
            console.log(res);
            $scope.deprecType = res.data.type;
        }, function(err) {
            console.log(err);
        });
    }

    $scope.edit = function(typeId) {
        console.log(typeId);

        window.location.href = CONFIG.baseUrl + '/deprec-type/edit/' + typeId;
    };

    $scope.update = function(event, form) {
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + $scope.deprecType.deprec_type_id + " ใช่หรือไม่?")) {            
            $http.put(CONFIG.baseUrl + '/deprec-type/update/', $scope.deprecType)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
    };

    $scope.delete = function(typeId) {
        console.log(typeId);

        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + typeId + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/deprec-type/delete/' +typeId)
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