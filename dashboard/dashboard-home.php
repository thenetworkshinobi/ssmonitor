<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    require_once('../config/db-connect.php');
    require_once("partials/json-handler.php");
?>
<script>
    function fetchData() {
        fetch('getEnvData.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById("temperature").innerText = `Temperature: ${data.temperature}°C`;
                document.getElementById("humidity").innerText = `Humidity: ${data.humidity}%`;            })
            .catch(error => console.error("Error fetching data:", error));
    }

    // Refresh the data every second
    setInterval(fetchData, 1000);

    // Initial fetch when the page loads
    window.onload = fetchData;
</script>



<?php
    $database = new dbConnect();
    $dbh = $database->connect();
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
 
    $jsonUrl = "http://dragon-zord/ssmonitor/received_data.json";
    
?>
<h1> Hosts</h1>
<div class="buttons">
    <div class="data" id="temperature">Loading...</div>
    <div class="data" id="humidity">Loading...</div>
    <div class="addremove">
        <a href ='dashboard-add.php'>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM200 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>
        </a>
        <a href ='dashboard-remove.php'>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zm88 200l144 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-144 0c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>
        </a>              
    </div>
</div>
<div class="cards-container">    
    <?php
    try {
        // SQL query to fetch data
        $get_devices_sql = "SELECT * FROM recent_device_status";
        $get_devices_result = $dbh->query($get_devices_sql);

        // Check if the query returns rows
        if ($get_devices_result && $get_devices_result->rowCount() > 0) {
            // Loop through each row
            while ($row = $get_devices_result->fetch()) {

                
                

                // Generate HTML block
                echo '
                <div class="fb-cards">                    
                    <div class="card-container">
                        <div class="card-items" style="1">
                            <i class="fa-solid fa-server"></i>
                            <span class="host">' . htmlspecialchars($row->hostname) . '</span>
                        </div>
                        <div class="card-items" style="2">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div class="card-items" style="3">
                            <ul class="card">
                                <li>Hostname:</li>
                                <li>IP Address:</li>
                                <li>Device Type:</li>
                                <li>OS:</li>
                                <li>Status:</li>
                            </ul>
                        </div>
                        <div class="card-items" style="4">
                            <ul class="card">
                                <li>' . htmlspecialchars($row->hostname) . '</li>
                                <li>' . htmlspecialchars($row->ip_address) . '</li>
                                <li>' . htmlspecialchars($row->device_type) . '</li>
                                <li>' . htmlspecialchars($row->os) . '</li>
                                <li>' . htmlspecialchars($row->latest_status) . '</li>
                            </ul>
                        </div>
                    </div>                    
                    <div class="back">
                        <div class="back-container">
                            <h1>' . htmlspecialchars($row->hostname) ?? [] ;
                            echo '</h1>';
                            if ($row->rfc1918 == TRUE){ ?>
                                 <ul id="device-data">
                                    <li>CPU Usage: <span id="<?php echo htmlspecialchars($row->ip_address); ?>-cpu-usage">Loading...</span>%</li>
                                    <li>RAM Usage: <span id="<?php echo htmlspecialchars($row->ip_address); ?>-ram-usage">Loading...</span>%</li>
                                    <li>Network Throughput: <span id="<?php echo htmlspecialchars($row->ip_address); ?>-network-throughput">Loading...</span> MB/s</li>
                                </ul>

                                <?php $sanitizedIpAddress = htmlspecialchars($row->ip_address, ENT_QUOTES, 'UTF-8'); ?>
                                <script>
                                    function updateDeviceData() {
                                        const searchIp = "<?php echo $sanitizedIpAddress; ?>";

                                        fetch(`getdevicedata.php?ip_address=${encodeURIComponent(searchIp)}`)
                                            .then(response => {
                                                if (!response.ok) {
                                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                                }
                                                return response.json();
                                            })
                                            .then(data => {
                                                const cpuElement = document.getElementById('<?php echo $sanitizedIpAddress; ?>-cpu-usage');
                                                const ramElement = document.getElementById('<?php echo $sanitizedIpAddress; ?>-ram-usage');
                                                const networkElement = document.getElementById('<?php echo $sanitizedIpAddress; ?>-network-throughput');

                                                if (cpuElement) cpuElement.textContent = data.cpu_usage ?? "unavailable";
                                                if (ramElement) ramElement.textContent = data.ram_usage_percentage ?? "unavailable";
                                                if (networkElement) networkElement.textContent = data.network_throughput ?? "unavailable";
                                            })
                                            .catch(error => console.error('Error fetching device data:', error));
                                    }

                                    // Update device data every 1 seconds
                                    setInterval(updateDeviceData, 1000);

                                    // Initial load
                                    updateDeviceData();
                                </script>
                            <?php
                            }
                            
 echo '                 </div>
                    </div>
            </div>';
            }                
        }else {
            echo "Please add hosts to monitor";
            echo "</div>";
        }
    } catch (PDOException $e) {
        // Handle exceptions related to database operations
        echo "An error occurred: " . $e->getMessage();
    }
    //$conn->close();

    ?>
    







<?php require_once("../partials/footer.php"); ?>

