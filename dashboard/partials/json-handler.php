<?php
function getDeviceDataFromWeb($url, $searchIp) {
    // Validate the URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ["error" => "Invalid URL provided."];
    }

    // Fetch the JSON data from the web server
    $jsonData = @file_get_contents($url);
    if ($jsonData === false) {
        return ["error" => "Failed to fetch data from the URL."];
    }

    // Decode the JSON data into an associative array
    $devices = json_decode($jsonData, true); // true means we decode as an array
    if ($devices === null) {
        return ["error" => "Error decoding JSON data."];
    }

    // Search for the device with the matching IP address
    foreach ($devices as $device) {
        if (isset($device['ip_address']) && $device['ip_address'] === $searchIp) {
            // Return the required data
            return [
                "cpu_usage" => $device['cpu_usage'] ?? "0",
                "ram_usage_percentage" => $device['ram_usage_percentage'] ?? "0",
                "network_throughput" => $device['network_throughput'] ?? "0"
            ];
        }
    }

    // If no matching IP address is found, return an error
    return ["error" => "No device found with IP address: $searchIp"];
}
// Example usage
$jsonUrl = "http://ssmonitor/data/linux_devices_data.json"; // Path to the JSON file
#$searchIp = "192.168.100.245";         // IP address to search for

?>
