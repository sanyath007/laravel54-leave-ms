app.controller('personCtrl', function($scope, $http, toaster, CONFIG, ModalService) {
/** ################################################################################## */
    $scope.loading = false;

    /** Input control model */
    $scope.cboFaction = '';
    $scope.cboDepart = '';
    $scope.cboDivision = '';
    $scope.keyword = '';

    /** Data models */
    $scope.person = null;
    $scope.persons = [];
    $scope.pager = null;

    $scope.movings = [];

    $scope.moving = {
        person_id: '',
        move_doc_no: '',
        move_doc_date: '',
        move_date: '',
        move_duty: '',
        move_faction: '',
        move_depart: '',
        move_division: '',
        move_reason: '',
        in_out: 'O',
        remark: ''
    };

    $scope.transferring = {
        person_id: '',
        transfer_date: '',
        transfer_doc_no: '',
        transfer_doc_date: '',
        transfer_to: '',
        transfer_reason: '',
        in_out: 'O',
        remark: ''
    };

    $scope.leaving = {
        person_id: '',
        leave_doc_no: '',
        leave_doc_date: '',
        leave_date: '',
        leave_type: '',
        leave_reason: '',
        remark: ''
    };

    $scope.renaming = {
        person_id: '',
        doc_no: '',
        doc_date: '',
        old_fullname: '',
        new_prefix: '',
        new_firstname: '',
        new_lastname: '',
        remark: ''
    };

    $scope.typepositions = [];
    $scope.typeacademics = [];
    $scope.positions = [];
    $scope.academics = [];

    let dtpDateOptions = {
        autoclose: true,
        language: 'th',
        format: 'dd/mm/yyyy',
        thaiyear: true,
        todayBtn: true,
        todayHighlight: true
    };

    /** ==================== Add form ==================== */
    $('#person_singin')
        .datepicker(dtpDateOptions)
        .datepicker('update', new Date())
        .on('show', function (e) {
            console.log(e);
        })
        .on('changeDate', function(event) {
            console.log(event.date);
        });

    $scope.setControlsData = function(data) {
        $scope.typepositions = data ? data.typepositions : [];
        $scope.typeacademics = data ? data.typeacademics : [];
        $scope.academics = data ? data.academics : [];
    };

    $scope.getPersons = function() {
        $scope.loading = true;
        $scope.persons = [];
        $scope.pager = null;

        let faction = $scope.cboFaction ? $scope.cboFaction : '';
        let depart = $scope.cboDepart ? $scope.cboDepart : '';
        let division = $scope.cboDivision ? $scope.cboDivision : '';
        let keyword = $scope.keyword ? $scope.keyword : '';
        let status = $scope.cboStatus ? $scope.cboStatus : '';

        $http.get(`${CONFIG.baseUrl}/persons/search?faction=${faction}&depart=${depart}&division=${division}&name=${keyword}&status=${status}`)
        .then(function(res) {
            $scope.setPersons(res);

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

        let faction = $scope.cboFaction ? $scope.cboFaction : '';
        let depart = $scope.cboDepart ? $scope.cboDepart : '';
        let division = $scope.cboDivision ? $scope.cboDivision : '';
        let keyword = $scope.keyword ? $scope.keyword : '';
        let status = $scope.cboStatus ? $scope.cboStatus : '';

        $http.get(`${url}&faction=${faction}&depart=${depart}&division=${division}&name=${keyword}&status=${status}`)
        .then(function(res) {
            cb(res);

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.setPersons = function(res) {
        const { data, ...pager } = res.data.persons;

        $scope.persons = data;
        $scope.pager = pager;
    };

    $scope.getHeadOfDeparts = function() {
        $scope.loading = true;

        $scope.persons = [];
        $scope.pager = null;

        let faction = $scope.cboFaction === '' ? 0 : $scope.cboFaction;
        let searchKey = $scope.keyword === '' ? 0 : $scope.keyword;
        let queryStr = $scope.queryStr === '' ? '' : $scope.queryStr;

        $http.get(`${CONFIG.baseUrl}/persons/departs/head?faction=${faction}&searchKey=${searchKey}${queryStr}`)
        .then(function(res) {
            const { data, ...pager } = res.data.persons;

            $scope.persons = data;
            $scope.pager = pager;

            $scope.loading = false;
        }, function(err) {
            console.log(err);
            $scope.loading = false;
        });
    }

    $scope.edit = function(typeId) {
        console.log(typeId);

        window.location.href = CONFIG.baseUrl + '/asset-type/edit/' + typeId;
    };


    $scope.add = function(event, form) {
        event.preventDefault();

        $http.post(CONFIG.baseUrl + '/asset-type/store', $scope.type)
        .then(function(res) {
            console.log(res);
            toaster.pop('success', "", 'บันทึกข้อมูลเรียบร้อยแล้ว !!!');
        }, function(err) {
            console.log(err);
            toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
        });

        document.getElementById(form).reset();
    }

    $scope.update = function(event, form) {
        event.preventDefault();

        if(confirm("คุณต้องแก้ไขรายการหนี้เลขที่ " + $scope.type.type_id + " ใช่หรือไม่?")) {
            $scope.type.cate_id = $('#cate_id option:selected').val();

            $http.put(CONFIG.baseUrl + '/asset-type/update', $scope.type)
            .then(function(res) {
                console.log(res);
                toaster.pop('success', "", 'แก้ไขข้อมูลเรียบร้อยแล้ว !!!');
            }, function(err) {
                console.log(err);
                toaster.pop('error', "", 'พบข้อผิดพลาด !!!');
            });
        }

        setTimeout(function (){
            window.location.href = CONFIG.baseUrl + '/asset-type/list';
        }, 2000);        
    };

    $scope.delete = function(typeId) {
        console.log(typeId);

        if(confirm("คุณต้องลบรายการหนี้เลขที่ " + typeId + " ใช่หรือไม่?")) {
            $http.delete(CONFIG.baseUrl + '/asset-type/delete/' +typeId)
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

    $scope.getById = function(id) {
        $scope.loading = true;

        $http.get(`${CONFIG.apiUrl}/persons/${id}`)
        .then(res => {
            $scope.positions = res.data.positions;

            $scope.person = res.data.person;
            $scope.person.fullname = res.data.person.prefix.prefix_name+res.data.person.person_firstname+ ' ' +res.data.person.person_lastname;
            $scope.person.typeposition_id = res.data.person.typeposition_id.toString();
            $scope.person.position_id = res.data.person.position_id.toString();
            $scope.person.ac_id = res.data.person.ac_id.toString();
            $scope.person.typeac_id = res.data.person.typeac_id.toString();

            $('#person_singin')
                .datepicker(dtpDateOptions)
                .datepicker('update', moment(res.data.person.person_singin).toDate())

            $scope.loading = false;
        }, err => {
            console.log(err);
            $scope.loading = false;

            toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
        });
    };

    $scope.calcAge = function(birthdate, type) {
        return moment().diff(moment(birthdate), type);
    };

    $scope.getMovings = (id) => {
        $scope.loading = true;

        $http.get(`${CONFIG.apiUrl}/persons/${id}/movings`)
        .then(res => {
            $scope.movings = res.data.movings;

            $scope.loading = false;
        }, (err) => {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.showMoveForm = function(e, type, faction, person_id) {
        e.preventDefault();
        $scope.moving.person_id = person_id;

        if (type == 'S') {
            $scope.onFactionSelected(faction);
            $scope.moving.move_faction = faction;

            $('#shiftForm').modal('show');
        } else if (type == 'M') {
            $('#moveForm').modal('show');
        }
    };

    $scope.move = (e) => {
        if(e) e.preventDefault();
        $scope.loading = true;

        $http.put(`${CONFIG.apiUrl}/persons/${$scope.moving.person_id}/move`, $scope.moving)
        .then(res => {
            /** Clear values */
            $scope.moving = {
                person_id: '',
                move_doc_no: '',
                move_doc_date: '',
                move_date: '',
                move_duty: '',
                move_faction: '',
                move_depart: '',
                move_division: '',
                move_reason: '',
                in_out: 'O',
                remark: '',
            };

            $('#moveForm').modal('hide');
            $('#shiftForm').modal('hide');

            $scope.loading = false;

            if (res.data.status == 1) {
                toaster.pop('success', "ผลการทำงาน", "บันทึกข้อมูลเรียบร้อย !!!");

                window.location.href = `${CONFIG.baseUrl}/persons/detail/${res.data.person.person_id}`;
            } else {
                toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
            }
        }, err => {
            console.log(err)
            $scope.loading = false;
        });
    };

    $scope.showTransferForm = function(e, person_id) {
        e.preventDefault();

        $scope.transferring.person_id = person_id;

        $('#transferForm').modal('show');
    };

    $scope.transfer = (e) => {
        if(e) e.preventDefault();

        $http.put(`${CONFIG.apiUrl}/persons/${$scope.transferring.person_id}/transfer`, $scope.transferring)
        .then(res => {
            /** Clear values */
            $scope.transferring = {
                person_id: '',
                transfer_date: '',
                transfer_doc_no: '',
                transfer_doc_date: '',
                transfer_to: '',
                transfer_reason: '',
                in_out: 'O',
                remark: '',
            };

            $('#transferForm').modal('hide');
            $scope.loading = false;

            if (res.data.status == 1) {
                toaster.pop('success', "ผลการทำงาน", "บันทึกข้อมูลเรียบร้อย !!!");

                window.location.href = `${CONFIG.baseUrl}/persons/detail/${res.data.person.person_id}`;
            } else {
                toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
            }
        }, err => {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.showLeaveForm = function(e, id) {
        e.preventDefault();

        $scope.leaving.person_id = id;

        $('#leaveForm').modal('show');
    };

    $scope.leave = (e) => {
        if(e) e.preventDefault();
        console.log($scope.leaving);

        $http.put(`${CONFIG.apiUrl}/persons/${$scope.leaving.person_id}/leave`, $scope.leaving)
        .then(res => {
            /** Clear values */
            $scope.leaving = {
                person_id: '',
                leave_doc_no: '',
                leave_doc_date: '',
                leave_date: '',
                leave_type: '',
                leave_reason: '',
                remark: ''
            };

            $('#leaveForm').modal('hide');

            $scope.loading = false;

            if (res.data.status == 1) {
                toaster.pop('success', "ผลการทำงาน", "บันทึกข้อมูลเรียบร้อย !!!");

                window.location.href = `${CONFIG.baseUrl}/persons/detail/${res.data.person.person_id}`;
            } else {
                toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
            }
        }, err => {
            console.log(err);
            $scope.loading = false;
        });
    };

    $scope.status = (e, id, status) => {
        if(e) e.preventDefault();
        $scope.loading = true;

        $http.put(`${CONFIG.apiUrl}/persons/${id}/status`, { status })
        .then(res => {
            $scope.loading = false;

            if (res.data.status == 1) {
                toaster.pop('success', "ผลการทำงาน", "บันทึกข้อมูลเรียบร้อย !!!");

                window.location.href = `${CONFIG.baseUrl}/persons/detail/${res.data.person.person_id}`;
            } else {
                toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
            }
        }, err => {
            console.log(err);
            $scope.loading = false;

            toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
        });
    };

    $scope.showRenameForm = function(person) {
        if (person) {
            $scope.renaming.person_id = person.person_id;
            $scope.renaming.old_fullname = person.prefix.prefix_name+person.person_firstname+ ' ' +person.person_lastname;
            $scope.renaming.new_prefix = person.person_prefix.toString();

            $('#renameForm').modal('show');
        }
    };

    $scope.rename = (e, id) => {
        if(e) e.preventDefault();
        $scope.loading = true;

        $http.put(`${CONFIG.apiUrl}/persons/${id}/rename`, $scope.renaming)
        .then(res => {
            $scope.person.person_prefix = res.data.person.person_prefix;
            $scope.person.person_firstname = res.data.person.person_firstname;
            $scope.person.person_lastname = res.data.person.person_lastname;
            $scope.person.fullname = res.data.person.prefix.prefix_name+res.data.person.person_firstname+ ' ' +res.data.person.person_lastname;

            /** Clear values */
            $scope.renaming = {
                person_id: '',
                doc_no: '',
                doc_date: '',
                old_fullname: '',
                new_prefix: '',
                new_firstname: '',
                new_lastname: '',
                remark: ''
            };

            $('#renameForm').modal('hide');
            $scope.loading = false;

            if (res.data.status == 1) {
                toaster.pop('success', "ผลการทำงาน", "บันทึกข้อมูลเรียบร้อย !!!");

                window.location.href = `${CONFIG.baseUrl}/persons/detail/${res.data.person.person_id}`;
            } else {
                toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
            }
        }, err => {
            console.log(err);
            $scope.loading = false;

            toaster.pop('error', "ผลการตรวจสอบ", "ไม่สามารถบันทึกข้อมูลเได้ !!!");
        });
    };
});