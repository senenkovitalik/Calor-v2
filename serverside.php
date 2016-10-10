<?php

$data = json_decode($_REQUEST["q"], true);

$conn = new mysqli("localhost", "root", "", "calories");

switch ($data["action"]) {
	case "load":
		loadProducts();
		break;
	case "save":
		saveProduct($data);
		break;
	case "update":
		updateProduct($data);
		break;
}

$conn->close();

function loadProducts() {

	header("Access-Control-Allow-Origin");
	header("Content-Type: application/json; charset=UTF-8");

	global $conn;

	$result = $conn->query("SELECT * FROM products");

	$outp = "";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "") {$outp .= ",";}
		$outp .= '{"name":"'			. $rs["name"] 			. '",';
		$outp .= '"calories":"' 		. $rs["calories"]		. '",';
		$outp .= '"proteins":"' 		. $rs["proteins"]		. '",';
		$outp .= '"fats":"'			 	. $rs["fats"]			. '",';
		$outp .= '"carbohydrates":"'	. $rs["carbohydrates"]	. '"}';
	}
	$outp = '{"products":['.$outp.']}';

	echo($outp);
}

function saveProduct($product) {
	$name 			= $product["name"];
	$calories 		= $product["calories"];
	$proteins 		= $product["proteins"];
	$fats 			= $product["fats"];
	$carbohydrates 	= $product["carbohydrates"];

	global $conn;

	$sql = "INSERT INTO products (name, calories, proteins, fats, carbohydrates) 
		 	VALUES ('$name', '$calories', '$proteins', '$fats', '$carbohydrates');";

	if ($conn->query($sql) === TRUE) {
		error_log("Product $name saved successfully");
		echo(TRUE);
	} else {
		error_log("Error: " . $sql);
		error_log($conn->error);
		echo("Error: " . $conn->error);
	}
}

function updateProduct($product) {
	$name 			= $product["name"];
	$column 		= $product["column"];
	$value 			= $product["value"];

	global $conn;

	$sql = "UPDATE products SET $column='$value' WHERE name='$name';";

	if ($conn->query($sql) === TRUE) {
		error_log("Product $name updated successfully");
		echo "Succes";
	} else {
		error_log("Error: " . $sql);
		error_log($conn->error);
		echo "Error";
	}
}
?>