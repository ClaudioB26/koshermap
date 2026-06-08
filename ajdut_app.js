var akApp = angular.module('akApp', ['ui.router', 'infinite-scroll']);

akApp.config(function ($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/');

    $stateProvider

        // HOME STATES AND NESTED VIEWS ========================================
        .state('home', {
            url: '/',
            templateUrl: 'views/home.html',
            controller: 'homeCtrl',
        })

        .state('exito', {
            url: '/exito',
            templateUrl: 'views/contacto-exito.html'
        })

        .state('quienes-somos', {
            url: '/quienes-somos',
            templateUrl: 'views/quienes-somos.html'
        })

        .state('empresas', {
            url: '/empresas',
            templateUrl: 'views/empresas.html',
            controller: 'empresasCtrl',
        })

        .state('consumidores', {
            url: '/consumidores',
            templateUrl: 'views/consumidores.html',
            controller: 'consumersCtrl',
            resolve: {
                alertas: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getAlertas();
                }]
            }

        })

        .state('pesaj', {
            url: '/pesaj',
            templateUrl: 'views/pesaj.html',
            controller: 'pesajCtrl'
        })

        .state('guia', {
            url: '/guia',
            templateUrl: 'views/guiakosher.html',
            controller: 'guiaKosherCtrl',
            resolve: {
                guiaKosher: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getGuiaKosher();
                }]
            }
        })

        .state('contacto', {
            url: '/contacto',
            templateUrl: 'views/contacto.html'
        })

        .state('listakosher', {
            url: '/listakosher',
            templateUrl: 'views/listakosher.html',
            controller: 'listaCtrl',
            resolve: {
                rubros: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getRubros();
                }]
                ,
                productos: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getProductos();
                }]
                ,
                query: [ function () {
                    return "";
                }]
            }
        })
        .state('listakosher2', {
            url: '/listakosher/:busqueda',
            templateUrl: 'views/listakosher.html',
            controller: 'listaCtrl',
            resolve: {
                rubros: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getRubros();
                }]
                ,
                productos: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getProductos();
                }]
                ,
                query: [ '$stateParams', function ($stateParams) {
                    return $stateParams.busqueda;
                }]
            }
        })
        
        .state('establecimientos', {
            url: '/establecimientos',
            templateUrl: 'views/establecimientos.html',
            controller: 'establecimientosCtrl'
            ,
            resolve: {
                establecimientos: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getEstablecimientos();
                }]
                ,
                tipoestablecimiento: ['apiService', '$stateParams', function (apiService, $stateParams) {
                    return apiService.getTipoEstablecimiento();
                }]
            }

        })

});

akApp.run(['$transitions', '$window', '$location', function ($transitions, $window, $location) {
    /* 
    $transitions.onSuccess({}, function () {
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        $window.ga('send', 'pageview', $location.path());
    });
    $window.ga('create', 'UA-147724679-1', 'auto');
    */
    
    $transitions.onSuccess({}, function () {
        document.body.scrollTop = document.documentElement.scrollTop = 0;
        $window.ga('send', 'pageview', $location.path());
    });
    $window.ga('create', 'UA-147724679-1', 'auto');
    $window.ga('create', 'AW-795803083', 'auto');
}]);

akApp.filter('toDate', function () {
    return function (dateString) {
        var dateObject = new Date(dateString);
        return dateObject.getDate() + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getFullYear();
    };
});

akApp.factory('apiService', function ($http) {

    //var apiUrl = "/jwt/api/";
    var apiUrl = "api/";



    function _getRubros() {
        console.log("getting rubros");
        return $http({
            url: apiUrl + "categorias.php",
            method: "GET"
        });
    }

    function _getProductos() {
        console.log("getting productos");
        return $http({
            url: apiUrl + "products.php",
            method: "GET"
        });
    }

    function _getAlertas() {
        console.log("getting alertas");
        return $http({
            url: apiUrl + "alertas.php",
            method: "GET"
        });
    }

    function _getGuiaKosher() {
        console.log("getting guia kosher");
        return $http({
            url: apiUrl + "textos.php?name=guiakosher",
            method: "GET"
        });
    }

    function _getEstablecimientos() {
        console.log("getting establecimientos");
        return $http({
            url: apiUrl + "establecimientos.php",
            method: "GET"
        });
    }

    function _getTipoEstablecimiento() {
        console.log("getting tipoestablecimiento");
        return $http({
            url: apiUrl + "tipoestablecimiento.php",
            method: "GET"
        });
    }

    function _getPesaj() {
        console.log("getting rubros");
        return $http({
            url: apiUrl + "pesaj.php",
            method: "GET"
        });
    }

    return {

        getRubros: _getRubros,
        getProductos: _getProductos,
        getAlertas: _getAlertas,
        getGuiaKosher: _getGuiaKosher,
        getEstablecimientos: _getEstablecimientos,
        getTipoEstablecimiento: _getTipoEstablecimiento,
        getPesaj: _getPesaj

    }

})


akApp.controller('consumersCtrl', ['$scope', '$http', '$sce', 'alertas', '$timeout', function ($scope, $http, $sce, alertas, $timeout) {
    console.log("consumersCtrl");

    console.log(alertas.data);
    $scope.alertas = alertas.data;

    $scope.renderHtml = function (html_code) {
        return $sce.trustAsHtml(html_code);
    };

    $scope.formatDate = function (date) {
        return new Date(date);
    }


    var a = 0;
    $scope.chechVisible = function () {
        console.log("checking");
        $(window).scroll(function () {

            var oTop = $('#counter').offset().top - window.innerHeight;
            if (a == 0 && $(window).scrollTop() > oTop) {
                $('.counter-value').each(function () {
                    var $this = $(this),
                        countTo = $this.attr('data-count');
                    $({
                        countNum: $this.text()
                    }).animate({
                        countNum: countTo
                    },

                        {

                            duration: 5000,
                            easing: 'swing',
                            step: function () {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function () {
                                $this.text(this.countNum);
                                //alert('finished');
                            }

                        });
                });
                a = 1;
            }
        });
    }

    $scope.chechVisible();
}]);

akApp.controller('empresasCtrl', ['$scope', '$http', '$sce', '$timeout', function ($scope, $http, $sce, $timeout) {
    console.log("empresasCtrl");

    var a = 0;
    $scope.chechVisible = function () {
        console.log("checking");
        $(window).scroll(function () {

            var oTop = $('#counter').offset().top - window.innerHeight;
            if (a == 0 && $(window).scrollTop() > oTop) {
                $('.counter-value').each(function () {
                    var $this = $(this),
                        countTo = $this.attr('data-count');
                    $({
                        countNum: $this.text()
                    }).animate({
                        countNum: countTo
                    },

                        {

                            duration: 5000,
                            easing: 'swing',
                            step: function () {
                                $this.text(Math.floor(this.countNum));
                            },
                            complete: function () {
                                $this.text(this.countNum);
                                //alert('finished');
                            }

                        });
                });
                a = 1;
            }
        });
    }

    $scope.chechVisible();

}]);


akApp.controller('masterCtrl', ['$scope', '$http', '$sce', '$timeout', 'apiService', function ($scope, $http, $sce, $timeout, apiService) {
    console.log("homeCtrl");
    $scope.pesaj = {};
    apiService.getPesaj()
    .success(function (data) {
        console.log(data[0]);
        $scope.pesaj = data[0];
    })

}]);




akApp.controller('homeCtrl', ['$scope', '$http', '$sce', '$timeout', function ($scope, $http, $sce, $timeout) {
    console.log("homeCtrl");
    var a = 0;
    $scope.startcarousel = function () {
        $('#carouselExampleIndicators').carousel('cycle');
    }

    $scope.startcarousel();

}]);

akApp.controller('listaCtrl', ['$scope', '$http', '$sce', 'productos', 'rubros','query', '$interval', '$window', function ($scope, $http, $sce, productos, rubros, query, $interval, $window) {
    console.log("listaCtrl");
    console.log(rubros.data);
    console.log(query);
    var mybutton = document.getElementById("myBtn");

    angular.element($window).bind('scroll', function () {  
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
          } else {
            mybutton.style.display = "none";
          }
      });


      $scope.topFunction = function() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
      }

    $scope.makeSuperLista = function (Rubros, Productos) {
        var superLista = angular.copy(Rubros);
        console.log(Rubros);
        for (var y = 0; y < superLista.length; y++) {
            superLista[y].productos = Productos.filter(function (producto) {
                return producto.rubroId == superLista[y].id;
            });
        }
        return superLista;
    }




    $scope.productos = productos.data;
    $scope.rubros = rubros.data;
    console.log(rubros.data);
    $scope.superLista = $scope.makeSuperLista($scope.rubros, $scope.productos);
    console.log($scope.superLista);

    $scope.query = {};
    $scope.query.text = query;
    $scope.query.sinTacc = false;
    $scope.trustAsHtml = $sce.trustAsHtml;



    $scope.numberToDisplay = 2;
    
    $scope.loadMore = function () {
        console.log("loadMore()");
        if ($scope.numberToDisplay + 5 < $scope.superLista.length) {
            $scope.numberToDisplay += 5;
            return true;
        } else {
            $scope.numberToDisplay = $scope.superLista.length;
            return false;
        }
    };


    function removeAccents(value) {
        return value
            .replace(/á/g, 'a')
            .replace(/é/g, 'e')
            .replace(/í/g, 'i')
            .replace(/ó/g, 'o')
            .replace(/ú/g, 'u')
            .replace(/Á/g, 'A')
            .replace(/É/g, 'E')
            .replace(/Í/g, 'I')
            .replace(/Ó/g, 'O')
            .replace(/Ú/g, 'U')
            .replace(/ñ/g, 'n')
            .replace(/Ñ/g, 'N');

    }

    $scope.ignoreAccentsProducto = function (item) {
        if ($scope.query.sinTacc == true && item.sintacc != "Si")
            return false;

        if (!$scope.query)
            return true;

        var fullItem = item.descripcion + ' ' + item.rubro + ' ' + item.marca + ' ' + item.codigoNombre +' ' + item.barcode;
        var text = removeAccents(fullItem.toLowerCase());
        var search = removeAccents($scope.query.text.toLowerCase());
        var searchTextSplit = search.split(' ');

        for (var y = 0; y < searchTextSplit.length; y++) {
            if (text.indexOf(searchTextSplit[y]) == -1) {
                return false;
            }
        }
        return true;
    };


    $scope.ignoreAccentsRubro = function (item) {

        if (!$scope.query)
            return true;

        if ($scope.rubFromProdSearch.has(item.id))
            return true;


        var fullItem = item.nombre;
        var text = removeAccents(fullItem.toLowerCase());
        var search = removeAccents($scope.query.text.toLowerCase());
        var searchTextSplit = search.split(' ');
        var count = 0;
        for (var y = 0; y < searchTextSplit.length; y++) {
            if (text.indexOf(searchTextSplit[y]) !== -1) {
                count++;
            }
        }
        if (count == searchTextSplit.length)
            return true;
        else
            return false;
    };

    $scope.foundRubro = function (rubro) {
        return $scope.rubSet.has(rubro.id);
    }

    $scope.foundProducto = function (producto) {
        return $scope.prodSet.has(producto.id);
    }

    $scope.renderHtml = function (html_code) {
        return $sce.trustAsHtml(html_code);
    };

    $scope.clickRubro = function (rubro) {
        console.log(rubro.nombre);
        //$scope.query = rubro.nombre;
    };


    $scope.$watch('query', function (newValue, oldValue) {
        console.log($scope.query.text);
        $scope.prodSearch = $scope.productos.filter($scope.ignoreAccentsProducto);
        $scope.rubFromProdSearch = new Set($scope.prodSearch.map(x => x.rubroId));
        $scope.prodSet = new Set($scope.prodSearch.map(x => x.id));
        $scope.rubSearch = $scope.rubros.filter($scope.ignoreAccentsRubro);
        $scope.rubSet = new Set($scope.rubSearch.map(x => x.id));
        if($scope.query.text =="") {
            $scope.numberToDisplay = 2;
        } else {
            $scope.$emit('list:filtered');
        }
    }, true);



    $scope.filtrarSinTacc = function () {
        console.log($scope.query.sinTacc);
        $scope.prodSearch = $scope.productos.filter($scope.ignoreAccentsProducto);
        $scope.rubFromProdSearch = new Set($scope.prodSearch.map(x => x.rubroId));
        $scope.prodSet = new Set($scope.prodSearch.map(x => x.id));
        $scope.rubSearch = $scope.rubros.filter($scope.ignoreAccentsRubro);
        $scope.rubSet = new Set($scope.rubSearch.map(x => x.id));
        console.log($scope.prodSearch);
        console.log($scope.rubSearch);
    }

}]);


akApp.controller('guiaKosherCtrl', ['$scope', '$http', '$sce', 'guiaKosher', function ($scope, $http, $sce, guiaKosher) {
    console.log("guiaKosherCtrl");
    console.log(guiaKosher.data[0]);
    $scope.guiaKosher = guiaKosher.data[0];

    $scope.renderHtml = function (html_code) {
        return $sce.trustAsHtml(html_code);
    };
}]);

akApp.controller('establecimientosCtrl', ['$scope', '$http', '$sce', 'establecimientos', 'tipoestablecimiento', function ($scope, $http, $sce, establecimientos, tipoestablecimiento) {
    console.log("establecimientosCtrl");
    console.log(establecimientos.data);
    console.log(tipoestablecimiento.data);

    $scope.establecimientos = establecimientos.data;
    $scope.tipoestablecimiento = tipoestablecimiento.data;

    $scope.makeSuperListaEstablecimientos = function (tipoestablecimiento, establecimientos) {
        var superLista = angular.copy(tipoestablecimiento);
        console.log(tipoestablecimiento);
        for (var y = 0; y < superLista.length; y++) {
            superLista[y].establecimientos = establecimientos.filter(function (establecimiento) {
                return establecimiento.rubroId == superLista[y].id;
            });
        }
        return superLista;
    }

    $scope.superListaEstablecimientos = $scope.makeSuperListaEstablecimientos($scope.tipoestablecimiento, $scope.establecimientos);
    console.log($scope.superListaEstablecimientos);

    $scope.query = "";

    function removeAccents(value) {
        return value
            .replace(/á/g, 'a')
            .replace(/é/g, 'e')
            .replace(/í/g, 'i')
            .replace(/ó/g, 'o')
            .replace(/ú/g, 'u')
            .replace(/Á/g, 'A')
            .replace(/É/g, 'E')
            .replace(/Í/g, 'I')
            .replace(/Ó/g, 'O')
            .replace(/Ú/g, 'U')
            .replace(/ñ/g, 'n')
            .replace(/Ñ/g, 'N');

    }

    $scope.ignoreAccentsEstablecimiento = function (item) {
        if (!$scope.query)
            return true;

        var fullItem = item.nombre + ' ' + item.direccion + ' ' + item.tipoNombre;
        var text = removeAccents(fullItem.toLowerCase());
        var search = removeAccents($scope.query.toLowerCase());
        var searchTextSplit = search.split(' ');

        for (var y = 0; y < searchTextSplit.length; y++) {
            if (text.indexOf(searchTextSplit[y]) == -1) {
                return false;
            }
        }
        return true;
    };
}]);

akApp.controller('pesajCtrl', ['$scope', '$http', '$sce',  '$timeout', 'apiService', function ($scope, $http, $sce, $timeout, apiService) {
    console.log("pesajCtrl");

    apiService.getPesaj()
    .success(function (data) {
        console.log(data[0]);
        $scope.pesaj = data[0];
    })



    $scope.renderHtml = function (html_code) {
        return $sce.trustAsHtml(html_code);
    };

}]);;
