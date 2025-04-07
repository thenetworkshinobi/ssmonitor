<?php
// SNMP configuration
$ip_address = "192.168.100.245"; // Device IP address
$community = "public";           // SNMP community string
$cpu_oid = "1.3.6.1.4.1.2021.11.10.0"; // OID for CPU usage (replace with the correct one for your device)

try {
    // Fetch CPU usage via SNMP
    $cpu_usage = snmpget($ip_address, $community, $cpu_oid);

    // Check if SNMP returned a value
    if ($cpu_usage === false) {
        echo "Failed to fetch CPU usage. Please check the IP address, community string, or SNMP configuration.";
    } else {
        // Clean up the returned value (it may include extra text like 'INTEGER:')
        $cpu_usage = preg_replace('/[^0-9.]/', '', $cpu_usage);

        echo "CPU Usage of $ip_address: $cpu_usage%";
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo "An error occurred: " . $e->getMessage();
}
?>