app.controller('mainCtrl', function($scope, $http, $location, $routeParams, CONFIG) {
/** ################################################################################## */
    console.log(CONFIG);

    $scope.forms = {
        depart: [],
        division: [],
        categories: [],
        groups: [],
        expenses: [],
    };

    $scope.temps = {
        departs: [],
        divisions: [],
        categories: [],
        groups: [],
        expenses: [],
    }

    $scope.initForms = (data) => {
        if (data) {
            $scope.temps.departs = data.departs ? data.departs : [];
            $scope.temps.divisions = data.divisions ? data.divisions : [];
        }
    };

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

    $scope.onFactionSelected = function(faction) {
        $scope.forms.departs = $scope.temps.departs.filter(dep => dep.faction_id == faction);
    };

    $scope.onDepartSelected = function(depart) {
        $scope.forms.divisions = $scope.temps.divisions.filter(div => div.depart_id == depart);
    };
/** ################################################################################## */
});
