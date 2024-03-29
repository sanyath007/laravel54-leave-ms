app.controller('leaveCtrl', function(CONFIG, $scope, $http, toaster, StringFormatService) {
/** ################################################################################## */
    $scope.loading = false;
    $scope.cboYear = parseInt(moment().format('MM')) > 9
                        ? (moment().year() + 544).toString()
                        : (moment().year() + 543).toString();
    $scope.cboMonth = moment().format('MM');
    $scope.cboLeaveType = "";
    $scope.cboLeaveStatus = "";
    $scope.cboMenu = "";
    $scope.searchKeyword = "";
    $scope.cboQuery = "";
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

    $scope.cancellations = [];
    $scope.cancelPager = [];

    $scope.leave = {
        leave_id: '',
        leave_no: '',
        leave_place: '1',
        leave_topic: '',
        leave_to: 'ผู้อำนวยการโรงพยาบาลเทพรัตน์นครราชสีมา',
        leave_person: '',
        depart_id: '',
        leave_type: '',
        leave_reason: '',
        leave_contact: '',
        leave_delegate: '',
        start_date: '',
        start_period: '1',
        end_date: '',
        end_period: '',
        leave_days: 0,
        working_days: 0,
        wife_id: '',
        wife_name: '',
        wife_is_officer: false,
        deliver_date: '',
        have_ordain: 0,
        ordain_date: '',
        ordain_temple: '',
        ordain_location: '',
        hibernate_temple: '',
        hibernate_location: '',
    };

    $scope.barOptions = {};
    $scope.showAllApproves = true;
    $scope.showAllCancels = true;

    $scope.cboStartPeriod = '';
    $scope.cboEndPeriod = '';
    $scope.cancelReason = '';

    /** ============================== Init Form elements ============================== */
    /** ให้เลือกช่วงได้เฉพาะวันสุดท้าย */
    $('#start_period').prop("disabled", true);
    $('#cbo_start_period').prop("disabled", true);
    $('#cbo_end_period').prop("disabled", true);

    let dtpOptions = {
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true,
        todayBtn: true,
        todayHighlight: true
    };

    $('#leave_date').datepicker(dtpOptions).datepicker('update', new Date()).on('show', function (e) {
        /** ถ้าผู้ใช้งานเป็น admin หรือพี่เจง (เภสัช) ให้สามารถเปลี่ยนวันที่ได้ */
        if (!['3309900180137','1300200009261'].includes($('#user').val())) {
            $('.day').click(function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
        }
    });

    $('#deliver_date').datepicker(dtpOptions).on('changeDate', function(event) {
        $scope.leave.deliver_date = StringFormatService.convFromDbDate(moment(event.date).format('YYYY-MM-DD'));
    });

    $('#ordain_date').datepicker(dtpOptions).on('changeDate', function(event) {
        // if (!moment(event.date).isSameOrAfter(moment())) {
        //     alert('ไม่สามารถระบุวันที่ย้อนหลังได้!!');

        //     $('#ordain_date').datepicker('update', moment().toDate());

        //     $scope.leave.ordain_date = StringFormatService.convFromDbDate(moment().format('YYYY-MM-DD'));
        // } else {
            $scope.leave.ordain_date = StringFormatService.convFromDbDate(moment(event.date).format('YYYY-MM-DD'));
        // }
    });

    $('#start_date').datepicker(dtpOptions).on('changeDate', function(event) {
        const leaveType = $('#leave_type').val();

        // if (![1,2,5].includes(parseInt(leaveType)) && !moment(event.date).isSameOrAfter(moment())) {
        //     alert('ไม่สามารถระบุวันที่ย้อนหลังได้!!');

        //     $('#start_date').datepicker('update', moment().toDate());

        //     $scope.leave.start_date = StringFormatService.convFromDbDate(moment().format('YYYY-MM-DD'));
        // } else {
            $scope.leave.start_date = StringFormatService.convFromDbDate(moment(event.date).format('YYYY-MM-DD'));
        // }
        
        /** Clear value of .select2 */
        $('#end_period').val(null).trigger('change');
    });

    $scope.speriodSelected = '';
    $('#end_date').datepicker(dtpOptions).on('changeDate', function(event) {
        const startDate = StringFormatService.convToDbDate($('#start_date').val());

        // TODO: should check existance leave at selected data
        if (!moment(event.date).isSameOrAfter(moment(startDate))) {
            alert('ช่องถึงวันที่ต้องมากกว่าหรือเท่ากับช่องจากวันที่!!');

            $('#end_date').datepicker('update', moment(startDate).toDate());
            
            $scope.leave.end_date = StringFormatService.convFromDbDate(moment(startDate).format('YYYY-MM-DD'));
        } else {
            $scope.leave.end_date = StringFormatService.convFromDbDate(moment(event.date).format('YYYY-MM-DD'));
        }

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
            depart_id: '',
            leave_type: '',
            leave_reason: '',
            leave_contact: '',
            leave_delegate: '',
            start_date: '',
            start_period: '1',
            end_date: '',
            end_period: '',
            leave_days: 0,
            working_days: 0,
            wife_id: '',
            wife_name: '',
            wife_is_officer: false,
            deliver_date: '',
            have_ordain: 0,
            ordain_date: '',
            ordain_temple: '',
            ordain_location: '',
            hibernate_temple: '',
            hibernate_location: '',
        };
    };

    /** คำนวณจำนวนวันลาทั้งหมด (รวมวันหยุด) */
    $scope.calculateLeaveDays = async function(sDateStr, eDateStr, endPeriod) {
        let sdate = StringFormatService.convToDbDate($(`#${sDateStr}`).val());
        let edate = StringFormatService.convToDbDate($(`#${eDateStr}`).val());
        let days = moment(edate).diff(moment(sdate), 'days');
        let working_days = 0;

        if (parseInt(endPeriod) !== 1) {
            days += 0.5;
        } else {
            days += 1;
        }

        $scope.leave.leave_days = days;
        $('#leave_days').val(days);

        if (['4','5','6'].includes($scope.leave.leave_type) || ($('#faction_id').val() == '5' && $('#duty_id').val() == '5')) {
            $scope.leave.working_days = days;
            $('#working_days').val(days);
        } else {
            /** ตรวจสอบวันทำการ (ไม่รวมวันหยุด) */
            working_days = await calculateWorkingDays(sdate, edate, parseInt(endPeriod));

            $scope.leave.working_days = working_days;
            $('#working_days').val(working_days);
        }
    };

    /** คำนวณจำนวนวันลาทั้งหมด (ไม่รวมวันหยุด) */
    $scope.holidays = null;
    const calculateWorkingDays = async function(sdate, edate, endPeriod) {
        let working_days = 0;
        const { holidays } = await getHolidays();

        $scope.holidays = holidays.map(holiday => holiday.holiday_date);

        if ($scope.holidays) {
            let startDate = moment(sdate);
            let endDate = moment(edate);
            let workDays = [];

            while(startDate <= endDate) {
                if (!$scope.holidays.includes(startDate.format('YYYY-MM-DD'))) {
                    workDays.push(startDate.format('YYYY-MM-DD'));

                    if (startDate.isSame(endDate)) {
                        if (endPeriod !== 1) {
                            working_days += 0.5;
                        } else {
                            working_days += 1;
                        }
                    } else {
                        working_days++;
                    }
                }

                startDate.add(1, "day");
            }
        }

        return working_days;
    };

    const getHolidays = async function () {
        const res = await $http.get(`${CONFIG.baseUrl}/holidays?year=${$scope.cboYear}`);

        return res.data;
    };

    $scope.same_ordain_temple = false;
    $scope.onSameOrdainTempleChecked = function(value) {
        if (value) {
            if ($scope.leave.ordain_temple === '' || $scope.leave.ordain_location === '') {
                toaster.pop('error', "", 'คุณยังไม่ได้ระบุวัดและที่อยู่วัดที่จะอุปสมบท !!!');
                $scope.same_ordain_temple = false;
                return;
            }
    
            $scope.leave.hibernate_temple = $scope.leave.ordain_temple;
            $scope.leave.hibernate_location = $scope.leave.ordain_location;
        } else {
            $scope.leave.hibernate_temple = '';
            $scope.leave.hibernate_location = '';
        }
    };

    $scope.onSelectedType = function() {
        $scope.leave.leave_topic = $('#leave_type').children("option:selected").text().trim();
        $('#leave_topic').val($('#leave_type').children("option:selected").text().trim());
    };

    /*
    |-----------------------------------------------------------------------------
    | Methods สำหรับการเลือกผู้ปฏิบัติงานแทน
    |-----------------------------------------------------------------------------
    */
    $scope.personListsCallback = '';
    $scope.onShowLeavePersonsList = function(e, depart) {
        e.preventDefault();

        $scope.personListsCallback = 'onSelectedLeavePerson';
        $scope.getPersons(depart, '0', 'togglePersonsList');

        $scope.cboDepart = depart !== '' ? depart : '0';
    };

    $scope.onWifeIsOfficer = function(value) {
        if (value) {
            $scope.personListsCallback = 'onSelectedWifeInPersons';
            $scope.getPersons('0', '0', 'togglePersonsList');
        } else {
            $('#wife_name').val('');
            $('#wife_id').val('');

            $scope.leave.wife_id = '';
            $scope.leave.wife_name = '';
        }
    };

    $scope.onShowDelegatorsList = function(e, depart) {
        e.preventDefault();

        $scope.personListsCallback = 'onSelectedDelegator';
        $scope.getPersons(depart, '0', 'togglePersonsList');

        $scope.cboDepart = depart !== '' ? depart : '0';
    };

    $scope.getPersons = function(depart, searchKey, cbName) {
        $http.get(`${CONFIG.baseUrl}/persons/search?depart=${depart}&name=${searchKey}`)
        .then(function(res) {
            let { data, ...pager } = res.data.persons;
            $scope.persons = data;
            $scope.pager = pager;

            const cb = $scope[cbName];
            if (cb) cb();

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.getPersonWithURL = function(e, url, cb) {
        /** Check whether parent of clicked a tag is .disabled just do nothing */
        if ($(e.currentTarget).parent().is('li.disabled')) return;

        $scope.loading = true;
        $scope.persons = [];
        $scope.pager = null;

        let depart = $scope.cboDepart === '' ? '0' : $scope.cboDepart; 
        let name = $scope.searchKey === '' ? '0' : $scope.searchKey; 

        $http.get(`${url}&depart=${depart}&name=${name}`)
        .then(function(res) {
            cb(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.setPersons = function(res) {
        let { data, ...pager } = res.data.persons;

        $scope.persons  = data;
        $scope.pager    = pager;
    };

    $scope.togglePersonsList = function(isOpen=true) {
        if (isOpen) {
            $('#person-list').modal('show');
        } else {
            $('#person-list').modal('hide');
        }
    };

    $scope.onSelectedPerson = function(e, person, defaultPerson=null, cbName) {
        const cb = $scope[cbName];

        if (cb) cb(person, defaultPerson);
    };

    $scope.onSelectedLeavePerson = function(person, defaultPerson) {
        if (person) {
            const academic = person.academic !== null ? person.academic.ac_name : '';

            $scope.leave.leave_person = person.person_id;

            $('#leave_person').val(person.person_id);
            $('#leave_person_name').val(person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname)
            $('#leave_person_position').val(person.position.position_name + academic)
        } else {
            const academic = defaultPerson.academic !== null ? defaultPerson.academic.ac_name : '';

            $scope.leave.leave_person = defaultPerson.person_id;

            $('#leave_person').val(defaultPerson.person_id);
            $('#leave_person_name').val(defaultPerson.prefix.prefix_name + defaultPerson.person_firstname + ' ' + defaultPerson.person_lastname)
            $('#leave_person_position').val(defaultPerson.position.position_name + academic)
        }

        $scope.personListsCallback = '';
        $('#person-list').modal('hide');
    };

    $scope.onSelectedWifeInPerson = function(person) {
        if (person) {
            $scope.leave.wife_id = person.person_id;
            $scope.leave.wife_name = person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname;

            $('#wife_id').val(person.person_id);
            $('#wife_name').val(person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname)
        } else {
            $scope.leave.wife_is_officer = false;
        }

        $scope.personListsCallback = '';
        $('#person-list').modal('hide');
    };

    $scope.onSelectedDelegator = function(person) {
        if (person) {
            $scope.leave.leave_delegate = person.person_id;
            $('#leave_delegate').val(person.person_id);

            const academic = person.academic !== null ? person.academic.ac_name : '';
            $('#leave_delegate_detail').val(person.prefix.prefix_name + person.person_firstname + ' ' + person.person_lastname + ' ตำแหน่ง' + person.position.position_name + academic)
        } else {
            $scope.leave.leave_delegate = '';

            $('#leave_delegate').val('');
            $('#leave_delegate_detail').val('');
        }

        $scope.personListsCallback = '';
        $('#person-list').modal('hide');
    };

    $scope.cboDepart = '';
    $scope.searchKey = '';
    $scope.onFilterPerson = function() {
        let depart = $scope.cboDepart === '' ? '0' : $scope.cboDepart; 
        let searchKey = $scope.searchKey === '' ? '0' : $scope.searchKey; 

        $scope.getPersons(depart, searchKey, null);
    };

    /*
    |-----------------------------------------------------------------------------
    | ดึงข้อมูลการยกเลิกการลา
    |-----------------------------------------------------------------------------
    */
    const getCancellation = function(isApproval=false) {
        $scope.cancellations = [];
        $scope.cancelPager = [];

        $scope.loading = true;

        let year    = $scope.cboYear === '' ? 0 : $scope.cboYear;
        let type    = $scope.cboLeaveType === '' ? 0 : $scope.cboLeaveType;
        let status  = '5';
        let menu    = "1";

        $http.get(`${CONFIG.baseUrl}/leaves/search/${year}/${type}/${status}/${menu}`)
        .then(function(res) {
            const { data, ...pager } = res.data.leaves;

            if (isApproval) {
                $scope.cancellations = data;
            } else {
                $scope.cancellations = data.filter(cancel => {
                    return cancel.cancellation.every(c => c.received_date === null);
                });
            }

            $scope.cancelPager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    /*
    |-----------------------------------------------------------------------------
    | CRUD operations of การลา
    |-----------------------------------------------------------------------------
    */
    // TODO: Duplicated method
    $scope.getAll = function(event) {
        $scope.leaves = [];
        $scope.loading = true;

        let year    = $scope.cboYear === '' ? 0 : $scope.cboYear;
        let type    = $scope.cboLeaveType === '' ? 0 : $scope.cboLeaveType;
        let status  = $scope.cboLeaveStatus === '' ? '-' : $scope.cboLeaveStatus;
        let menu    = $scope.cboMenu === '' ? 0 : $scope.cboMenu;
        let query   = $scope.cboQuery === '' ? '' : `?${$scope.cboQuery}`;

        $http.get(`${CONFIG.baseUrl}/leaves/search/${year}/${type}/${status}/${menu}${query}`)
        .then(function(res) {
            $scope.setLeaves(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    // TODO: Duplicated method
    $scope.setLeaves = function(res) {
        const { data, ...pager } = res.data.leaves;
        $scope.leaves = data;
        $scope.pager = pager;
    };

    // TODO: Duplicated method
    $scope.getDataWithURL = function(e, URL, cb) {
        /** Check whether parent of clicked a tag is .disabled just do nothing */
        if ($(e.currentTarget).parent().is('li.disabled')) return;

        $scope.loading = true;

        $http.get(URL)
        .then(function(res) {
            cb(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.getById = function(id, cb) {
        $http.get(`${CONFIG.baseUrl}/leaves/get-ajax-byid/${id}`)
        .then(function(res) {
            cb(res.data);
        }, function(err) {
            console.log(err);
        });
    }

    $scope.setEditControls = function(data) {
        $scope.leave.leave_id           = data.leave.id;
        $scope.leave.leave_person       = data.leave.leave_person;
        $scope.leave.depart_id          = data.leave.depart_id;
        $scope.leave.leave_no           = data.leave.leave_no;
        $scope.leave.leave_topic        = data.leave.leave_topic;
        $scope.leave.leave_to           = data.leave.leave_to;
        $scope.leave.leave_reason       = data.leave.leave_reason;
        $scope.leave.leave_contact      = data.leave.leave_contact;
        $scope.leave.leave_delegate     = data.leave.leave_delegate;
        $scope.leave.leave_days         = data.leave.leave_days;
        $scope.leave.working_days       = data.leave.working_days;
        $scope.leave.attachment         = data.leave.attachment;
        $scope.leave.status             = data.leave.status;
        $scope.leave.cancellation       = data.leave.cancellation;
        $scope.leave.person             = data.leave.person;

        if (data.leave.leave_type == '5') {
            $scope.leave.helped_wife        = data.leave.helped_wife;
            $scope.leave.wife_name          = data.leave.helped_wife.wife_name;
            $scope.leave.wife_id            = data.leave.helped_wife.wife_id;
            $scope.leave.wife_is_officer    = data.leave.helped_wife.wife_is_officer == 1 ? true : false;
            $scope.leave.deliver_date       = StringFormatService.convFromDbDate(data.leave.helped_wife.deliver_date);
            
            $('#deliver_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true,
                todayBtn: true,
                todayHighlight: true
            }).datepicker('update', moment(data.leave.helped_wife.deliver_date).toDate());
        }

        if (data.leave.leave_type == '6') {
            $scope.leave.ordinate           = data.leave.ordinate;
            $scope.leave.ordain_temple      = data.leave.ordinate.ordain_temple;
            $scope.leave.ordain_location    = data.leave.ordinate.ordain_location;
            $scope.leave.hibernate_temple   = data.leave.ordinate.hibernate_temple;
            $scope.leave.hibernate_location = data.leave.ordinate.hibernate_location;
            $scope.leave.ordain_date        = StringFormatService.convFromDbDate(data.leave.ordinate.ordain_date);
            
            $('#ordain_date').datepicker({
                autoclose: true,
                language: 'th',
                format: 'dd/mm/yyyy',
                thaiyear: true,
                todayBtn: true,
                todayHighlight: true
            }).datepicker('update', moment(data.leave.ordinate.ordain_date).toDate());
        }

        if (data.leave.leave_type == '7') {
            $scope.leave.country          = data.leave.oversea.country.name;
        }

        /** Convert int value to string */
        $scope.leave.leave_place        = data.leave.leave_place.toString();
        $scope.leave.leave_type         = data.leave.leave_type.toString();
        $scope.leave.start_period       = data.leave.start_period.toString();
        $scope.leave.end_period         = data.leave.end_period.toString();
        /** Convert db date to thai date. */            
        $scope.leave.leave_date         = StringFormatService.convFromDbDate(data.leave.leave_date);
        $scope.leave.start_date         = StringFormatService.convFromDbDate(data.leave.start_date);
        $scope.leave.end_date           = StringFormatService.convFromDbDate(data.leave.end_date);

        /** Set delegate detail to input */
        let delegate = '';
        if (data.leave.delegate !== null) {
            let academic = data.leave.delegate.academic !== null ? data.leave.delegate.academic.ac_name : '';
            delegate = data.leave.delegate.prefix.prefix_name;
            delegate += data.leave.delegate.person_firstname + ' ' + data.leave.delegate.person_lastname;
            delegate += '  ตำแหน่ง' + data.leave.delegate.position.position_name + academic;
        }
        $('#leave_delegate_detail').val(delegate);

        $('#leave_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true,
            todayBtn: true,
            todayHighlight: true
        }).datepicker('update', moment(data.leave.leave_date).toDate());

        $('#start_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true,
            todayBtn: true,
            todayHighlight: true
        }).datepicker('update', moment(data.leave.start_date).toDate());

        $('#end_date').datepicker({
            autoclose: true,
            language: 'th',
            format: 'dd/mm/yyyy',
            thaiyear: true,
            todayBtn: true,
            todayHighlight: true
        }).datepicker('update', moment(data.leave.end_date).toDate());
    };

    $scope.store = function(event, form) {
        event.preventDefault();

        $('#frmNewLeave').submit();
    }

    $scope.edit = function(id) {
        window.location.href = `${CONFIG.baseUrl}/leaves/edit/${id}`;
    };

    $scope.update = function(event) {
        event.preventDefault();
    
        if(confirm(`คุณต้องแก้ไขใบลาเลขที่ ${$scope.leave.leave_id} ใช่หรือไม่?`)) {
            $('#frmEditLeave').submit();
        }
    };

    $scope.delete = function(e, id) {
        e.preventDefault();

        const actionUrl = $('#frmDelete').attr('action');
        $('#frmDelete').attr('action', `${actionUrl}/${id}`);

        if(confirm(`คุณต้องลบใบลาเลขที่ ${id} ใช่หรือไม่?`)) {
            $('#frmDelete').submit();
        }
    };

    $scope.approval = null;
    $scope.showApprovalDetail = function(id) {
        $scope.getById(id, function(data) {
            console.log(data);
            $scope.approval = data.leave;
        });

        $('#approval-detail').modal('show');
    };
});