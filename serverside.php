<?php
header("Access-Control-Allow-Origin");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "calories");

$result = $conn->query("SELECT * FROM products");

$outp = "";
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
	if ($outp != "") {$outp .= ",";}
	$outp .= '{"name":"'	. $rs["name"] 			. '",';
	$outp .= '"calories":"' 		. $rs["calories"]		. '",';
	$outp .= '"proteins":"' 		. $rs["proteins"]		. '",';
	$outp .= '"fats":"'			 	. $rs["fats"]			. '",';
	$outp .= '"carbohydrates":"'	. $rs["carbohydrates"]	. '"}';
}
$outp = '{"products":['.$outp.']}';
$conn->close();

echo($outp);
?>