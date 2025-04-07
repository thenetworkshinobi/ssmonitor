<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    require_once('../config/db-connect.php');
?>
<?php
    $database = new dbConnect();
    $dbh = $database->connect();
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
 
    $snmpIP = [];

?>
<h1> Hosts</h1>
<div class="buttons">
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
                            <h1>'. htmlspecialchars($row->hostname) .'</h1>';
                            if ($row->os == "Linux"){
                                // Fetch real-time data using SNMP
                                
                                echo '
                                    <ul>
                                        <li>CPU Usage: %</li>
                                        <li>RAM Usage:  KB</li>
                                        <li>Network Throughput:  bps</li>
                                    </ul>';
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

