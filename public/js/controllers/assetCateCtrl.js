app.controller('assetCateCtrl', function($scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.cboAssetGroup = "";
    $scope.searchKeyword = "";


    $scope.cates = [];
    $scope.pager = [];
    
    $scope.cate = {
        cate_id: '',
        cate_no: '',
        cate_name: '',
        group_id: '',
        group_no: '00'
    };

    $scope.getData = function(event) {
        $scope.cates = [];
        $scope.loading = true;
        
        let assetGroup = $scope.cboAssetGroup === '' ? 0 : $scope.cboAssetGroup;
        let searchKey = $scope.searchKeyword === '' ? 0 : $scope.searchKeyword;
        
        $http.get(`${CONFIG.baseUrl}/asset-cate/search/${assetGroup}/${searchKey}`)
        .then(function(res) {
            console.log(res);
            $scope.cates = res.data.cates.data;
            $scope.pager = res.data.cates;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        console.log(URL);
        $scope.cates = [];
        $scope.loading = true;

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.cates = res.data.cates.data;
            $scope.pager = res.data.cates;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.getAssetCate = function(cateId) {
        $http.get(CONFIG.baseUrl + '/asset-cate/get-ajax-byid/' +cateId)
        .then(function(res) {
            console.log(res);
            $scope.cate = res.data.cate;
            $scope.cate.group_id = $scope.cate.group_id.toString();
            $scope.cate.group_no = $scope.cate.cate_no.substring(0, 2);
            $scope.cate.cate_no = $scope.cate.cate_no.substring(2);
        }, function(err) {
            console.log(err);
        });
    }

    $scope.getCateNo = function(groupId) {
        $scope.loading = true;

        $http.get(CONFIG.baseUrl+ '/asset-cate/get-ajax-no/' +groupId)
        .then(function(res) {
            console.log(res);
            let groupNo = res.data.cateNo.substring(0, 2);
            let tmpNo = res.data.cateNo.substring(2);
            
            let newNo = (parseInt(tmpNo)+1).toString().padStart(2, "0");
            console.log(`${groupNo}${newNo}`);
            
            $scope.cate.group_no = `${groupNo}`;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.add = function(event, form) {
        event.preventDefault();

        $http.post(CONFIG.baseUrl + '/asset-cate/store', $scope.cate)
        .then(function(res) {
            console.log(res);
            toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
        }, function(err) {
            console.log(err);
            toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
        });

        document.getElementById(form).reset();
    }

    $scope.edit = function(cateId) {
        console.log(cateId);

        window.location.href = CONFIG.baseUrl+ 'asset-cate/edit/' +cateId;
    };

    $scope.update = function(event, form) {
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + $scope.cate.cate_id + " ใช่หรือไม่?")) {
            $http.put(CONFIG.baseUrl + '/asset-cate/update', $scope.cate)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }

        setTimeout(function (){
            window.location.href = CONFIG.baseUrl + '/asset-cate/list';
        }, 2000);
    };

    $scope.delete = function(cateId) {
        console.log(cateId);

        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + cateId + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/asset-cate/delete/' +cateId)
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