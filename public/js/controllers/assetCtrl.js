app.controller('assetCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, ReportService, PaginateService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.cboAssetType = "";
    $scope.cboParcel = "";
    $scope.cboAssetStatus = "";
    $scope.searchKeyword = "";

    $scope.parcels = [];
    $scope.assets = [];

    $scope.asset = {
        asset_id: '',
        asset_no: '',
        asset_name: '',
        description: '',
        parcel_id: '',
        parcel_no: '0000-000-0000',
        amount: 1,
        unit: '',
        unit_price: '',
        method: '',
        image: '',
        reg_no: '',
        budget_type: '',
        purchased_method: '',
        year: '',
        doc_type: '',
        doc_no: '',
        doc_date: '',
        date_in: '',
        date_exp: '',
        depart: '',
        supplier: '',
        remark: '',
        status: '',
    };
    
    $scope.barOptions = {};
    $scope.lifeYear = 0;

    /** Init Form elements */
    // $('.select2').select2();

    $('#date_in').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    }).on('changeDate', function(event){
        let addDate = moment(event.date).add(parseInt($scope.lifeYear), 'years').format('YYYY-MM-DD');
        let [ year, month, day ] = addDate.split('-');
        
        $scope.asset.date_exp = day+ '/' +month+ '/' +(parseInt(year)+543);
    });

    $('#date_exp').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    });

    $('#doc_date').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    });

    $scope.clearAssetObj = function() {
        $scope.asset = {
            asset_id: '',
            asset_no: '',
            asset_name: '',
            description: '',
            parcel_id: '',
            parcel_no: '0000-000-0000',
            amount: '',
            unit: '',
            unit_price: '',
            method: '',
            image: '',
            reg_no: '',
            budget_type: '',
            purchased_method: '',
            year: '',
            supplier: '',
            doc_type: '',
            doc_no: '',
            doc_date: '',
            date_in: '',
            date_exp: '',
            remark: '',
            status: '',
        };
    };

    $scope.getData = function(event) {
        $scope.assets = [];
        $scope.loading = true;

        let parcelId = $scope.cboParcel === '' ? 0 : $scope.cboParcel;
        let assetStatus = $scope.cboAssetStatus === '' ? 0 : $scope.cboAssetStatus; 
        let searchKey = $scope.searchKeyword === '' ? 0 : $scope.searchKeyword;

        $http.get(`${CONFIG.baseUrl}/asset/search/${parcelId}/${assetStatus}/${searchKey}`)
        .then(function(res) {            
            $scope.assets = res.data.assets.data;
            $scope.pager = res.data.assets;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDebtWithURL = function(URL) {
        console.log(URL);
        $scope.debts = [];
        $scope.debtPager = [];
        $scope.debtPages = [];

        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            console.log(res);
            $scope.debts = res.data.debts.data;
            $scope.debtPager = res.data.debts;
            $scope.debtPages = PaginateService.createPagerNo($scope.debtPager);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getParcel = function (typeId) {
        $scope.loading = true;

        $http.get(CONFIG.baseUrl+ '/parcel/get-ajax-bytype/' +typeId)
        .then(function(res) {
            console.log(res);
            $scope.parcels = res.data.parcels;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.setAssetNo = function (parcelId) {
        $http.get(CONFIG.baseUrl+ '/parcel/get-ajax-byid/' +parcelId)
        .then(function(res) {
            console.log(res);

            $scope.asset.parcel_no = res.data.parcel.parcel_no + '/';
            $scope.asset.asset_name = res.data.parcel.parcel_name;
            $scope.lifeYear = res.data.parcel.deprec_type.deprec_life_y;
        }, function(err) {
            console.log(err);
        });
    }

    $scope.getAsset = function(assetId) {
        $http.get(CONFIG.baseUrl + '/asset/get-ajax-byid/' +assetId)
        .then(function(res) {
            $scope.asset = res.data.asset;
            console.log($scope.asset);
            /** Separate asset_no and parcel_no */
            let [tmpParcelNo, tmpAssetNo] = $scope.asset.asset_no.split('/');
            $scope.asset.parcel_no = tmpParcelNo + '/';
            $scope.asset.asset_no = tmpAssetNo;
            /** Convert int value to string */
            $scope.asset.parcel_id = $scope.asset.parcel_id.toString();
            $scope.asset.unit = $scope.asset.unit.toString();
            $scope.asset.budget_type = $scope.asset.budget_type.toString();
            $scope.asset.purchased_method = $scope.asset.purchased_method.toString();
            $scope.asset.depart = $scope.asset.depart.toString();
            $scope.asset.supplier = $scope.asset.supplier.toString();
            $scope.asset.doc_type = $scope.asset.doc_type.toString();
            /** Convert db date to thai date. */
            $scope.asset.date_in = StringFormatService.convFromDbDate($scope.asset.date_in);
            $scope.asset.date_exp = StringFormatService.convFromDbDate($scope.asset.date_exp);            
            $scope.asset.doc_date = StringFormatService.convFromDbDate($scope.asset.doc_date);
        }, function(err) {
            console.log(err);
        });
    }

    $scope.store = function(event, form) {
        event.preventDefault();
        /** Convert thai date to db date. */
        $scope.asset.date_in = StringFormatService.convToDbDate($scope.asset.date_in);
        $scope.asset.date_exp = StringFormatService.convToDbDate($scope.asset.date_exp);
        $scope.asset.doc_date = StringFormatService.convToDbDate($scope.asset.doc_date);
        /** Get user id */
        // $scope.asset.created_by = $("#user").val();
        // $scope.asset.updated_by = $("#user").val();
        console.log($scope.asset);

        $http.post(CONFIG.baseUrl + '/asset/store', $scope.asset)
        .then(function(res) {
            console.log(res);
            toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
        }, function(err) {
            console.log(err);
            toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
        });

        /** Clear control value and model data */
        document.getElementById(form).reset();
        $scope.clearAssetObj();
    }

    $scope.edit = function(assetId) {
        console.log(assetId);

        /** Show edit form modal dialog */
        // $('#dlgEditForm').modal('show');BASE_URL
        window.location.href = CONFIG.baseUrl + '/asset/edit/' + assetId;
    };

    $scope.update = function(event, form) {
        event.preventDefault();

        /** Convert thai date to db date. */
        $scope.asset.date_in = StringFormatService.convToDbDate($scope.asset.date_in);
        $scope.asset.date_exp = StringFormatService.convToDbDate($scope.asset.date_exp);
        $scope.asset.doc_date = StringFormatService.convToDbDate($scope.asset.doc_date);
        /** Get user id */
        // $scope.asset.created_by = $("#user").val();
        // $scope.asset.updated_by = $("#user").val();
        console.log($scope.asset);

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + $scope.asset.asset_id + " ใช่หรือไม่?")) {
            // $http.put(CONFIG.baseUrl + '/asset/update/', $scope.asset)
            // .then(function(res) {
            //     console.log(res);
            // }, function(err) {
            //     console.log(err);
            // });
            
            /** Redirect to debt list */
            // window.location.href = CONFIG.baseUrl + '/asset/list';
        }
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

    $scope.discharge = function(assetId) {
        console.log(assetId);

        if(confirm("คุณต้องลดหนี้เป็นศูนย์รายการหนี้เลขที่ " + assetId + " ใช่หรือไม่?")) {
            $http.post(CONFIG.baseUrl + '/asset/discharge', { asset_id: assetId })
            .then(function(res) {
                console.log(res);
                if(res.data.status == 'success') {
                    toaster.pop('success', "ระบบทำการงลดหนี้เป็นศูนย์สำเร็จแล้ว", "");
                } else { 
                    toaster.pop('error', "พบข้อผิดพลาดในระหว่างการดำเนินการ !!!", "");
                }
            }, function(err) {
                console.log(err);

                toaster.pop('error', "พบข้อผิดพลาดในระหว่างการดำเนินการ !!!", "");
            });
        }
    };

    $scope.getAssetChart = function (creditorId) {
        ReportService.getSeriesData('/report/debt-chart/', creditorId)
        .then(function(res) {
            console.log(res);

            var debtSeries = [];
            var paidSeries = [];
            var setzeroSeries = [];

            angular.forEach(res.data, function(value, key) {
                let debt = (value.debt) ? parseFloat(value.debt.toFixed(2)) : 0;
                let paid = (value.paid) ? parseFloat(value.paid.toFixed(2)) : 0;
                let setzero = (value.setzero) ? parseFloat(value.setzero.toFixed(2)) : 0;
                
                debtSeries.push(debt);
                paidSeries.push(paid);
                setzeroSeries.push(setzero);
            });

            var categories = ['ยอดหนี้']
            $scope.barOptions = ReportService.initBarChart("barContainer", "", categories, 'จำนวน');
            $scope.barOptions.series.push({
                name: 'หนี้คงเหลือ',
                data: debtSeries
            }, {
                name: 'ตัดจ่ายแล้ว',
                data: paidSeries
            }, {
                name: 'ลดหนี้ศูนย์',
                data: setzeroSeries
            });

            var chart = new Highcharts.Chart($scope.barOptions);
        }, function(err) {
            console.log(err);
        });
    };
});