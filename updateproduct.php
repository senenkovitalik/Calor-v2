<?php

$product = json_decode($_REQUEST["q"], true);

$name 			= $product["name"];
$column 		= $product["column"];
$value 			= $product["value"];

$conn = new mysqli("localhost", "root", "", "calories");

$sql = "UPDATE products SET $column='$value' WHERE name='$name';";

if ($conn->query($sql) === TRUE) {
	error_log("Product $name updated successfully");
	echo "Succes";
} else {
	error_log("Error: " . $sql);
	error_log($conn->error);
	echo "Error";
}

$conn->close();
?>