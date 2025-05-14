<?php
header('Content-Type: application/json'); // Set the content type to JSON
require_once "partials/json-handler.php";



if (isset($_GET['ip_address'])) {
    $jsonUrl = 'http://dragon-zord/ssmonitor/received_data.json'; // Replace with the actual JSON URL
    $searchIp = htmlspecialchars($_GET['ip_address']);

    // Fetch device data
    $deviceData = getDeviceDataFromWeb($jsonUrl, $searchIp) ?? [];

    // Return data as JSON
    echo json_encode([
        'cpu_usage' => $deviceData["cpu_usage"] ?? "unavailable",
        'ram_usage_percentage' => $deviceData["ram_usage_percentage"] ?? "unavailable",
        'network_in_throughput' => $deviceData["network_in_throughput"] ?? "unavailable",
        'network_out_throughput' => $deviceData["network_out_throughput"] ?? "unavailable"
    ]);
    exit;
} else {
    echo json_encode(['error' => 'No IP address provided']);
}

