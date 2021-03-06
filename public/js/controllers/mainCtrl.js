app.controller('mainCtrl', function($scope, $http, $location, $routeParams, CONFIG) {
/** ################################################################################## */
    console.log(CONFIG);
/** ################################################################################## */
    // $scope.selectText = function(form) {
    //     var input = form.$editables[0].inputEl;
    //     input.select();
    // }

    // $scope.updateQty = function(data) {
    //     $scope.orderList.qty = data;
    // }

    // $scope.updateDisc = function(data) {
    //     $scope.orderList.disc = data;
    // }

    // $scope.popover=null;
    // $scope.showEditable = function($event) {
    //   $scope.popover = $event.currentTarget;
    //   let target = $event.target;
    //   $($scope.popover).popover({
    //     html : true,
    //     title: function() {
    //       return $("#popover-head").html();
    //     },
    //     content: function() {
    //       return $("#popover-content").html();
    //     }
    //   });
    //
    //   $($scope.popover).popover("toggle");
    // }
    //
    // $scope.hideEditable = function($event) {
    //   let target = $event.target;
    //   $($scope.popover).popover("hide");
    //   $($scope.popover).on('hidden.bs.popover', function () {
    //     target.text('1');
    //   })
    //   $scope.popover=null;
    // }

/** ################################################################################## */
    // Calendar variables
    $scope.today = moment().format('YYYY-MM-DD');
    $scope.fdMonth = moment().format('YYYY-01-01');
    $scope.ldMonth = moment().format('YYYY-12-31');
    // $scope.ldMonth = moment($scope.fdMonth).endOf('month').format('YYYY-MM-DD');
    $scope.events = [];

    $scope.initCalendar = function ($scope) {
        console.log(this.today);
        var callback = this.showEvent;
        var getView = this.getView;

        $('#calendar').fullCalendar({
            locale: 'th',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: this.today,
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: this.events,
            eventClick: callback,
            dayClick: (date) => console.log(date),
            viewRender: (view, element) => getView(view, element) //Call getView function
        });

        //Test on click event of header button left (prev and next)
        // $('button.fc-prev-button').click(function(){
        //     alert('prev is clicked, do something');
        // });

        // $('button.fc-next-button').click(function(){
        //     alert('nextis clicked, do something');
        // });        
    }

    $scope.showEvent = function (event) {
        alert(event.title);
    }

    $scope.getView = function (view, element, $scope) {
        console.log(view);
        console.log(view.type + '  ' + view.title)
        let monthName = {
            "??????????????????":01, "??????????????????????????????":02, "??????????????????":03, "??????????????????":04, "?????????????????????":05, "????????????????????????":06,
            "?????????????????????":07, "?????????????????????":08, "?????????????????????":09, "??????????????????":10, "???????????????????????????":11, "?????????????????????":12
        };

        let monthShortName = {
            "???.???.":01, "???.???.":02, "??????.???.":03, "??????.???.":04, "???.???.":05, "??????.???.":06,
            "???.???.":07, "???.???.":08, "???.???.":09, "???.???.":10, "???.???.":11, "???.???.":12
        };

        let viewTitle = view.title.split(' ');
        console.log(viewTitle)

        if(view.type === "month") {
            let currentMonth = monthName[viewTitle[0]];
            console.log(currentMonth)

            this.fdMonth = moment().format(viewTitle[1] + '-' + currentMonth +'-01');
            this.ldMonth = moment().format(viewTitle[1] + '-' + currentMonth +'-28');
            console.log(this.fdMonth)
            console.log(this.ldMonth)
        } else if(view.type === "basicDay") {
            let currentMonth = monthName[viewTitle[1]];
            console.log(currentMonth)

            this.fdMonth = moment().format(viewTitle[2] + '-' + currentMonth +'-' + viewTitle[0]);
            this.ldMonth = moment().format(viewTitle[2] + '-' + currentMonth +'-' + viewTitle[0]);
            console.log(this.fdMonth)
            console.log(this.ldMonth)
        } else if(view.type === "basicWeek") {
            if(viewTitle.length === 5){
                let currentMonth = monthShortName[viewTitle[3]];
                console.log(currentMonth)

                this.fdMonth = moment().format(viewTitle[4] + '-' + currentMonth +'-' + viewTitle[0]);
                this.ldMonth = moment().format(viewTitle[4] + '-' + currentMonth +'-' + viewTitle[2]);
                console.log(this.fdMonth)
                console.log(this.ldMonth)
            } else {
                let fromMonth = monthShortName[viewTitle[1]];
                let toMonth = monthShortName[viewTitle[4]];
                console.log(fromMonth)
                console.log(toMonth)

                this.fdMonth = moment().format(viewTitle[5] + '-' + fromMonth +'-' + viewTitle[0]);
                this.ldMonth = moment().format(viewTitle[5] + '-' + toMonth +'-' + viewTitle[3]);
                console.log(this.fdMonth)
                console.log(this.ldMonth)
            }
        }

        //Clear events
        $('#calendar').fullCalendar( 'removeEventSource', this.events );
        //Load new events
        console.log(CONFIG.baseUrl + '/reserve/ajaxcalendar/' + this.fdMonth + '/' + this.ldMonth);
        $http.get(CONFIG.baseUrl + '/reserve/ajaxcalendar/' + this.fdMonth + '/' + this.ldMonth)
        .then((data) => {
            this.events = data.data;
            $('#calendar').fullCalendar('addEventSource', this.events);
        })
        .catch(error => console.log(error));
    }
/** ################################################################################## */
    //################## autocomplete ##################
    $scope.maintenanceList = [];
    $scope.fillinMaintenanceList = function(event) {
        console.log(event.keyCode);
        if (event.which === 13) {
            event.preventDefault();
            $scope.maintenanceList.push($(event.target).val());

            //???????????????????????????????????? text searchProduct
            $(event.target).val('');

            var maindetained_detail = "";
            var count = 0;
            angular.forEach($scope.maintenanceList, function(maintained) {
                if(count != $scope.maintenanceList.length - 1){
                    maindetained_detail += maintained + ",";
                } else {
                    maindetained_detail += maintained
                }

                count++;
            });

            $('#detail').val(maindetained_detail);
        }
    }

    // ????????????????????????
    $scope.removeMaintenanceList = function(m) {
        let index = $scope.maintenanceList.indexOf(m);
        $scope.maintenanceList.splice(index, 1);
    }

    $scope.calculateMaintainedVatnet = function (event) {
        var tmpVat = $(event.target).val();
        var tmpAmt = $('#amt').val();
        var tmpVatnet = parseFloat((tmpAmt * tmpVat) / 100);
        var tmpTotal = parseFloat(tmpAmt) + parseFloat(tmpVatnet);
        $('#total').val(tmpTotal);
        $('#vatnet').val(tmpVatnet);
        console.log(tmpTotal);
    }
/** ################################################################################## */
    $scope.passengers = [];
    $scope.showPassengers = function (event, reserveid) {
        $http.get(CONFIG.baseUrl + '/ajaxpassenger/' + reserveid + '/0')
        .then(function (data) {
            $scope.passengers = data.data[1];

            $('#dlgPassengers').modal('show')
        });
    }
/** ################################################################################## */
    /** MENU */
    $scope.menu = 'leaves';
    $scope.submenu = 'list';
    $scope.setActivedMenu = function() {
        let routePath = $location.$$absUrl.replace(`${CONFIG.baseUrl}/`, '');
        let [mnu, submnu, ...params] = routePath.split('/');

        $scope.menu = mnu; 
        $scope.submenu = submnu;
    }

    $scope.redirectTo = function(e, path) {
        e.preventDefault();
        window.location.href = `${CONFIG.baseUrl}/${path}`;
    };
/** ################################################################################## */
});
