<?php

$product = json_decode($_REQUEST["q"], true);

$name 			= $product["name"];
$calories 		= $product["calories"];
$proteins 		= $product["proteins"];
$fats 			= $product["fats"];
$carbohydrates 	= $product["carbohydrates"];

$conn = new mysqli("localhost", "root", "", "calories");

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

$conn->close();
?>