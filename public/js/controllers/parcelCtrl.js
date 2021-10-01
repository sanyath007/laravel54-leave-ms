app.controller('parcelCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, ReportService, PaginateService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.cboParcelType = "";
    $scope.cboAssetType = "";
    $scope.searchKeyword = "";

    $scope.types = [];
    $scope.parcels = [];
    $scope.parcel_types = [];

    $scope.parcel = {
        parcel_id: '',
        parcel_no: '',
        parcel_name: '',
        description: '',
        parcel_type: '',
        asset_type: '',
        asset_type_no: '0000-000',
        unit: '',
        unit_price: '',
        deprec_type: '',
        first_y_month: '',
        remark: '',
        status: '',
    };

    $scope.barOptions = {};

    $scope.clearParcelObj = function() {
        $scope.parcel = {
            parcel_id: '',
            parcel_no: '',
            parcel_name: '',
            description: '',
            parcel_type: '',
            asset_type: '',
            asset_type_no: '0000-000',
            unit: '',
            unit_price: '',
            deprec_type: '',
            first_y_month: '',
            remark: '',
            status: '',
        };
    };

    $scope.getData = function(event) {
        $scope.parcels = [];
        $scope.loading = true;

        let assetType = $scope.cboAssetType === '' ? 0 : $scope.cboAssetType; 
        let parcelType = $scope.cboParcelType === '' ? 0 : $scope.cboParcelType; 
        let searchKey = $scope.searchKeyword === '' ? 0 : $scope.searchKeyword;

        $http.get(`${CONFIG.baseUrl}/parcel/search/${assetType}/${parcelType}/${searchKey}`)
        .then(function(res) {      
            console.log(res);
            $scope.parcel_types = res.data.parcel_types;
            $scope.parcels = res.data.parcels.data;
            $scope.pager = res.data.parcels;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getParcelWithURL = function(URL) {
        console.log(URL);
        $scope.parcels = [];
        $scope.pager = [];

        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.parcel_types = res.data.parcel_types;
            $scope.parcels = res.data.parcels.data;
            $scope.pager = res.data.parcels;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.getParcel = function(parcelId) {
        $http.get(CONFIG.baseUrl + '/parcel/get-ajax-byid/' +parcelId)
        .then(function(res) {
            $scope.parcel = res.data.parcel;

            let [group, type, no] = $scope.parcel.parcel_no.split('-');
            $scope.parcel.asset_type_no = `${group}-${type}`;
            $scope.parcel.parcel_no = `${no}`;

            $scope.parcel.asset_type = $scope.parcel.asset_type.toString();
            $scope.parcel.parcel_type = $scope.parcel.parcel_type.toString();
            $scope.parcel.unit = $scope.parcel.unit.toString();
            $scope.parcel.deprec_type = $scope.parcel.deprec_type.toString();
        }, function(err) {
            console.log(err);
        });
    };

    $scope.getAssetType = function (cateId) {
        $scope.loading = true;

        $http.get(CONFIG.baseUrl+ '/asset-type/get-ajax-all/' +cateId)
        .then(function(res) {
            console.log(res);
            $scope.types = res.data.types;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.getParcelNo = function (assetType) {
        $scope.loading = true;

        $http.get(CONFIG.baseUrl+ '/parcel/get-ajax-no/' +assetType)
        .then(function(res) {
            console.log(res);
            let tmpNo = res.data.parcelNo;
            let [group, type, no] = tmpNo.split('-');

            $scope.parcel.asset_type_no = `${group}-${type}`;
            $scope.parcel.parcel_no = (parseInt(no)+1).toString().padStart(4, "0");          

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.store = function(event, form) {
        event.preventDefault();

        /** Get user id */
        // $scope.parcel.created_by = $("#user").val();
        // $scope.parcel.updated_by = $("#user").val();
        console.log($scope.parcel);

        $http.post(CONFIG.baseUrl + '/parcel/store', $scope.parcel)
        .then(function(res) {
            console.log(res);
            toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
        }, function(err) {
            console.log(err);
            toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
        });

        /** Clear control value and model data */
        document.getElementById(form).reset();
        $scope.clearParcelObj();
    };

    $scope.edit = function(assetId) {
        console.log(assetId);

        /** Show edit form modal dialog */
        // $('#dlgEditForm').modal('show');
        
        window.location.href = CONFIG.baseUrl + '/parcel/edit/' + assetId;
    };

    $scope.update = function(event, form) {
        event.preventDefault();

        /** Get user id */
        // $scope.parcel.created_by = $("#user").val();
        // $scope.parcel.updated_by = $("#user").val();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + $scope.parcel.parcel_id + " ใช่หรือไม่?")) {
            $http.put(CONFIG.baseUrl + '/parcel/update', $scope.parcel)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }

        /** Redirect to debt list */
        setTimeout(() => {
            window.location.href = CONFIG.baseUrl + '/parcel/list';
        }, 2000);
    };

    $scope.delete = function(assettId) {
        console.log(assettId);

        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + assettId + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/asset/delete/' +assettId)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }

        /** Get debt list and re-render chart */
        // $scope.getDebtData('/asset/rpt');
        // $scope.getDebtChart($scope.cboDebtType);
    };
});