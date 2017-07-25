<?php




require_once 'db.php'; // The mysql database connection script
$data = json_decode(file_get_contents("php://input"));

//$cont = $data->cont;
$fecha1 = $data->fecha1;
$fecha2 = $data->fecha2;


$query="select c.nombre from pagos_credito as a join contrato_credito as b on a.id_contrato=b.codigo join cliente as c on b.id_cliente=c.id_cliente where estado='1' and (fecha_maxima BETWEEN '$fecha1' AND '$fecha2 ')    group by c.nombre";

$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

$arr = array();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $arr[] = $row;
    }
}

# JSON-encode the response
echo $json_response = json_encode($arr);


?>