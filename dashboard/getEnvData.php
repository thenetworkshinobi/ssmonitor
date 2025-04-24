<?php
require_once '../config/db-connect.php';

$database = new dbConnect();
$dbh = $database->connect();

// Ensure database connection was successful
if (!$dbh) {
    die(json_encode(["error" => "Database connection failed"]));
}

$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

// Fetch the most recent temperature and humidity
$get_env_data = "SELECT temperature, humidity, updateTime FROM environment ORDER BY updateTime DESC LIMIT 1";
$get_env_data_result = $dbh->query($get_env_data);

if ($get_env_data_result && $get_env_data_result->rowCount() > 0) {
    $row = $get_env_data_result->fetch();
    echo json_encode($row); // Send JSON response
} else {
    echo json_encode(["temperature" => "N/A", "humidity" => "N/A", "updateTime" => "N/A"]);
}
?>