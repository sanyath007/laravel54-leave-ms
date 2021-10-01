app.controller('supplierCtrl', function($scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    let baseUrl = CONFIG.baseUrl;

    $scope.loading = false;
    $scope.pager = [];
    $scope.suppliers = [];
    $scope.supplier = {
        prefix_id: '',
        supplier_name: '',
        supplier_address1: '',
        supplier_address2: '',
        supplier_address3: '',
        supplier_zipcode: '',
        supplier_phone: '',
        supplier_fax: '',
        supplier_email: '',
        supplier_taxid: '',
        supplier_back_acc: '',
        supplier_note: '',
        supplier_credit: '',
        supplier_taxrate: '',
        supplier_agent_name: '',
        supplier_agent_email: '',
        supplier_agent_contact: ''
    };

    $scope.getData = function(event) {
        console.log(event);
        $scope.suppliers = [];
        $scope.loading = true;
        
        let searchKey = ($("#searchKey").val() == '') ? 0 : $("#searchKey").val();
        $http.get(CONFIG.baseUrl+ '/supplier/search/' +searchKey)
        .then(function(res) {
            console.log(res);
            $scope.suppliers = res.data.suppliers.data;
            $scope.pager = res.data.suppliers;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        console.log(URL);
        $scope.suppliers = [];
        $scope.loading = true;

    	$http.get(URL)
    	.then(function(res) {
    		console.log(res);
            $scope.suppliers = res.data.suppliers.data;
            $scope.pager = res.data.suppliers;

            $scope.loading = false;
    	}, function(err) {
    		console.log(err);
            $scope.loading = false;
    	});
    }

    $scope.add = function(event, form) {
        console.log(event);
        event.preventDefault();

        if (form.$invalid) {
            toaster.pop('warning', "", 'กรุณาข้อมูลให้ครบก่อน !!!');
            return;
        } else {
            $http.post(CONFIG.baseUrl + '/supplier/store', $scope.supplier)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });            
        }

        document.getElementById('frmNewsupplier').reset();
    }

    $scope.getSupplier = function(supplierId) {
        $http.get(CONFIG.baseUrl + '/supplier/get-supplier/' +supplierId)
        .then(function(res) {
            console.log(res);
            $scope.supplier = res.data.supplier;
        }, function(err) {
            console.log(err);
        });
    }

    $scope.edit = function(supplierId) {
        console.log(supplierId);

        window.location.href = CONFIG.baseUrl + '/supplier/edit/' + supplierId;
    };

    $scope.update = function(event, form, supplierId) {
        console.log(supplierId);
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขเจ้าหนี้เลขที่ " + supplierId + " ใช่หรือไม่?")) {
            $http.put(CONFIG.baseUrl + '/supplier/update/', $scope.supplier)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
    };

    $scope.delete = function(supplierId) {
        console.log(supplierId);

        if(confirm("คุณต้องลบเจ้าหนี้เลขที่ " + supplierId + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/supplier/delete/' +supplierId)
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