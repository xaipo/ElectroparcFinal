/**
 * Created by xaipo on 7/18/2017.
 */
app.controller('BuroControl', ['$scope', '$http', '$location', 'myProvider', '$localStorage', function ($scope, $http, $location, myProvider, $localStorage) {


    $scope.loadBuro = function () {



        $scope.date1= document.getElementById('datepicker').value;
        $scope.date2= document.getElementById('datepicker2').value;

        $scope.vec1=$scope.date1.split('/');
        $scope.vec2=$scope.date2.split('/');

        $scope.date1=$scope.vec1[2]+'-'+$scope.vec1[1]+'-'+$scope.vec1[0];
        $scope.date2=$scope.vec2[2]+'-'+$scope.vec2[1]+'-'+$scope.vec2[0];

        $http({
            method: 'POST',
            url: myProvider.buro(),
            headers: {
                'Content-Type': 'application/json'
            },
            data: {

                fecha1:$scope.date1,
                fecha2:$scope.date2
            }


             })
            .then(function (response) {

                $scope.personas=response.data;
                console.log($scope.personas)



                var doc = new jsPDF({

                    unit: "mm",
                    format: "letter"
                });

                var x = 20;
                var y = 35;

                var count =0;
                var n = $scope.personas.length;
                for(var i=0;i<n;i++) {// cambiar x el tamano de la lista
                    if (i == 0) {
                        doc.setFontSize(14);
                        doc.text(100, 10, 'ELECTROPARC CIA. LTDA.', 'center');
                        doc.setFontSize(10);
                        doc.text(100, 15, 'electroparc10@gmail.com', 'center');
                        doc.setFontSize(14);
                        doc.text(100, 30, 'Creditos pendientes', 'center');


                        doc.setFontSize(8);
                        doc.line(0, y + 230, 220, y + 230);
                        doc.text(100, y + 235, 'Electroparc Cia. Ltda. ', 'center');

                        doc.text(100, y + 238, 'Riobamba Ecuador ', 'center');

                        doc.text(100, y + 241, 'Direccion: Matriz Lavalle 30 56 y Olmedo Esquina', 'center');

                        doc.text(100, y + 244, 'Telefonos: 0994161053', 'center');

                    }

                    if (count <= 44) {
                        doc.setFontSize(10);
                        doc.text(x + 10, y + 5, $scope.personas[i].nombre);// nombre o campos a mostrar
                        y += 5;
                        count++;
                    } else {
                        count = 0;
                        doc.addPage();
                        var x = 20;
                        var y = 35;
                        doc.setFontSize(14);
                        doc.text(100, 10, 'ELECTROPARC CIA. LTDA.', 'center');
                        doc.setFontSize(10);
                        doc.text(100, 15, 'electroparc10@gmail.com', 'center');
                        doc.setFontSize(14);
                        doc.text(100, 30, 'Creditos pendientes', 'center');


                        doc.setFontSize(8);
                        doc.line(0, y + 230, 220, y + 230);
                        doc.text(100, y + 235, 'Electroparc Cia. Ltda. ', 'center');

                        doc.text(100, y + 238, 'Riobamba Ecuador ', 'center');

                        doc.text(100, y + 241, 'Direccion: Matriz Lavalle 30 56 y Olmedo Esquina', 'center');

                        doc.text(100, y + 244, 'Telefonos: 0994161053', 'center');

                    }
                }

                doc.save('Buro.pdf');














            }   , function errorCallback(response) {

                console.log(response);
            });






    }

}]);