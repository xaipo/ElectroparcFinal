<?php
/**
 * Created by PhpStorm.
 * User: xaipo
 * Date: 6/22/2017
 * Time: 11:45 AM
 */


require_once 'db2.php'; // The mysql database connection script
$data = json_decode(file_get_contents("php://input"));
$id = $data->id;

$query="select SUM(costo) as suma from abono_legacy as a where a.id_cuota=$id";
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