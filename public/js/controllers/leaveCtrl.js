app.controller('leaveCtrl', function(CONFIG, $scope, $http, toaster, ModalService, StringFormatService, ReportService, PaginateService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.cboYear = (parseInt(moment().format('YYYY'))+543).toString();
    $scope.cboMonth = moment().format('MM');
    $scope.cboLeaveType = "";
    $scope.cboLeaveStatus = "";
    $scope.searchKeyword = "";
    $scope.budgetYearRange = [2560,2561,2562,2563,2564,2565,2566,2567];
    $scope.monthLists = [
        { id: '01', name: 'มกราคม' },
        { id: '02', name: 'กุมภาพันธ์' },
        { id: '03', name: 'มีนาคม' },
        { id: '04', name: 'เมษายน' },
        { id: '05', name: 'พฤษภาคม' },
        { id: '06', name: 'มิถุนายน' },
        { id: '07', name: 'กรกฎาคม' },
        { id: '08', name: 'สิงหาคม' },
        { id: '09', name: 'กันยายน' },
        { id: '10', name: 'ตุลาคม' },
        { id: '11', name: 'พฤศจิกายน' },
        { id: '12', name: 'ธันวาคม' },
    ];

    $scope.leaves = [];
    $scope.persons = [];
    $scope.pager = [];

    $scope.leave = {
        leave_id: '',
        leave_no: '',
        leave_place: '1',
        leave_topic: '',
        leave_to: 'ผู้อำนวยการโรงพยาบาลเทพรัตน์นครราชสีมา',
        leave_person: '',
        leave_type: '',
        leave_reason: '',
        leave_contact: '',
        leave_delegate: '',
        start_date: '',
        start_period: '1',
        end_date: '',
        end_period: '',
        leave_days: 0
    };
    
    $scope.barOptions = {};

    /** ============================== Init Form elements ============================== */
    /** ให้เลือกช่วงได้เฉพาะวันสุดท้าย */
    $('#start_period').prop("disabled", true);

    $('#leave_date').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    }).datepicker('update', new Date());

    $('#start_date').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    }).on('changeDate', function(event) {
        let selectedDate = moment(event.date).format('YYYY-MM-DD');
        let [ year, month, day ] = selectedDate.split('-');

        $scope.leave.start_date = day+ '/' +month+ '/' +(parseInt(year)+543);
    });

    $scope.speriodSelected = '';
    $('#end_date').datepicker({
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true
    }).on('changeDate', function(event) {
        let selectedDate = moment(event.date).format('YYYY-MM-DD');
        let [ year, month, day ] = selectedDate.split('-');

        $scope.leave.end_date = day+ '/' +month+ '/' +(parseInt(year)+543);

        /** Clear value of .select2 */
        $('#end_period').val(null).trigger('change');
    });

    $scope.clearLeaveObj = function() {
        $scope.leave = {
            leave_no: '',
            leave_place: '1',
            leave_topic: '',
            leave_to: 'ผู้อำนวยการโรงพยาบาลเทพรัตน์นครราชสีมา',
            leave_person: '',
            leave_type: '',
            leave_reason: '',
            leave_contact: '',
            leave_delegate: '',
            start_date: '',
            start_period: '1',
            end_date: '',
            end_period: '',
            leave_days: 0
        };
    };

    $scope.calculateLeaveDays = function(endPeriod) {
        let sdate = StringFormatService.convToDbDate($('#start_date').val());
        let edate = StringFormatService.convToDbDate($('#end_date').val());
        let days = moment(edate).diff(moment(sdate), 'days');
        
        if (endPeriod !== '1') {
            days += 0.5;
        } else {
            days += 1;
        }

        $('#leave_days').val(days);
    };

    $scope.onSelectedType = function() {
        $scope.leave.leave_topic = $('#leave_type').children("option:selected").text().trim();
        $('#leave_topic').val($('#leave_type').children("option:selected").text().trim());
    };

    $scope.onShowPersonLists = function(e) {
        e.preventDefault();

        $scope.getPersons('0', '0', togglePersonLists);
    };

    const togglePersonLists = function() {
        console.log('callback is invoked!!');
        $('#person-list').modal('show');
    };

    $scope.getPersons = function(depart, searchKey, cb) {
        $http.get(`${CONFIG.baseUrl}/persons/search/${depart}/${searchKey}`)
        .then(function(res) {
            let { data, ...pager } = res.data.persons;
            $scope.persons = data;
            $scope.pager = pager;

            if (cb) cb();

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.onSelectedDelegatePerson = function(e, person) {
        e.preventDefault();

        $scope.leave.leave_delegate = person.person_id;
        $('#leave_delegate').val(person.person_id);

        const academic = person.academic !== null ? person.academic.ac_name : '';
        $('#leave_delegate_detail').val(person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname + ' ตำแหน่ง' + person.position.position_name + academic)

        $('#person-list').modal('hide');
    };

    $scope.cboDepart = '';
    $scope.searchKey = '';
    $scope.onFilterPerson = function() {
        console.log($scope.cboDepart);
        let depart = $scope.cboDepart === '' ? '0' : $scope.cboDepart; 
        let searchKey = $scope.searchKey === '' ? '0' : $scope.searchKey; 

        $scope.getPersons(depart, searchKey, null);
    };

    $scope.onApproveLoad = function(e) {
        $scope.cboYear = '2565';
        $scope.cboLeaveStatus = '3';

        $scope.getAll(e);
    };

    $scope.getAll = function(event) {
        $scope.leaves = [];
        $scope.loading = true;

        let year = $scope.cboYear === '' ? 0 : $scope.cboYear;
        let type = $scope.cboLeaveType === '' ? 0 : $scope.cboLeaveType;
        let status = $scope.cboLeaveStatus === '' ? 0 : $scope.cboLeaveStatus;

        $http.get(`${CONFIG.baseUrl}/leaves/search/${year}/${type}/${status}`)
        .then(function(res) {
            const { data, ...pager } = res.data.leaves
            $scope.leaves = data;
            $scope.pager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getDataWithURL = function(URL) {
        $scope.persons = [];
        $scope.pager = null;
        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            let { data, ...pager } = res.data.persons;
            $scope.persons  = data;
            $scope.pager    = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.getById = function(id, cb) {
        $http.get(`${CONFIG.baseUrl}/leaves/get-ajax-byid/${id}`)
        .then(function(res) {
            console.log(res);

            cb(res.data);
        }, function(err) {
            console.log(err);
        });
    }

    $scope.setEditControls = function(data) {
        $scope.leave.leave_id       = data.leave.id;
        $scope.leave.leave_no       = data.leave.leave_no;
        $scope.leave.leave_topic    = data.leave.leave_topic;
        $scope.leave.leave_to       = data.leave.leave_to;
        $scope.leave.leave_person   = data.leave.leave_person;
        $scope.leave.leave_reason   = data.leave.leave_reason;
        $scope.leave.leave_contact  = data.leave.leave_contact;
        $scope.leave.leave_delegate = data.leave.leave_delegate;
        $scope.leave.leave_days     = data.leave.leave_days;
        $scope.leave.attachment     = data.leave.attachment;
        /** Convert int value to string */
        $scope.leave.leave_place    = data.leave.leave_place.toString();
        $scope.leave.leave_type     = data.leave.leave_type.toString();
        $scope.leave.start_period   = data.leave.start_period.toString();
        $scope.leave.end_period     = data.leave.end_period.toString();
        /** Convert db date to thai date. */            
        $scope.leave.leave_date     = StringFormatService.convFromDbDate(data.leave.leave_date);
        $scope.leave.start_date     = StringFormatService.convFromDbDate(data.leave.start_date);
        $scope.leave.end_date       = StringFormatService.convFromDbDate(data.leave.end_date);

        /** Set delegate detail to input */
        let academic = data.leave.delegate.academic !== null ? data.leave.delegate.academic.ac_name : '';
        let delegate = data.leave.delegate.prefix.prefix_name;
        delegate += data.leave.delegate.person_firstname + ' ' + data.leave.delegate.person_lastname;
        delegate += '  ตำแหน่ง' + data.leave.delegate.position.position_name + academic;
        $('#leave_delegate_detail').val(delegate);

        $('#leave_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(data.leave.leave_date).toDate());

        $('#start_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(data.leave.start_date).toDate());

        $('#end_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true
        }).datepicker('update', moment(data.leave.end_date).toDate());
    };

    $scope.store = function(event, form) {
        event.preventDefault();

        $('#frmNewLeave').submit();
        /** Clear control value and model data */
        // document.getElementById(form).reset();
        // $scope.clearLeaveObj();

        // TODO: clear date controls to current date
    }

    $scope.edit = function(id) {
        window.location.href = `${CONFIG.baseUrl}/leaves/edit/${id}`;
    };

    $scope.update = function(event) {
        event.preventDefault();
    
        if(confirm("คุณต้องแก้ไขใบลาเลขที่ " + $scope.leave.leave_id + " ใช่หรือไม่?")) {
            $('#frmEditLeave').submit();
        }
    };

    $scope.delete = function(id) {
        console.log(id);

        if(confirm("คุณต้องลบใบลาเลขที่ " + id + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/leaves/delete/' +id)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'ลบข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }
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