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
    /*
    $get_devices_sql = "SELECT hostname, ip_address, os, device_type FROM device_list WHERE ip_address=:ip_address, os=:os, device_type=:device_type";
    $get_devices_stmt = $dbh->prepare($get_devices_sql);
    $get_devices_stmt->bindParam("hostname", $hostname);
    $get_devices_stmt->bindParam("ip_address", $ip_address);
    $get_devices_stmt->bindParam("os", $os);
    $get_devices_stmt->bindParam("device_type", $device_type);
    $get_devices_stmt->execute();
    $get_devices_result = $get_devices_stmt->fetch();
    */
    // SQL query to fetch data
    $get_devices_sql = "SELECT hostname, ip_address, device_type, os FROM device_list";
    $get_devices_result = $database->query($get_devices_sql);

?>

    
    <div class="cards-container">
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
    <!--
    <div class="card-container">
        <div class="card-items" style:"grid-area: 1">
            <i class="fa-solid fa-server"></i>
        </div>
        <div class="card-items" style:"grid-area: 2">
            <i class="fa-solid fa-circle-info"></i>
        </div>
        <div class="card-items" style:"grid-area: 3">
            <ul class="card">
                <li>Hostname:</li>
                <li>IP Address:</li>
                <li>Online:</li>
                <li>Timestamp:</li>
            </ul>
        </div>
        <div class="card-items" style:"grid-area: 4">
            <ul class="card">
                <li>www.google.com</li>
                <li>251.25.14.68</li>
                <li>Online</li>
                <li></li>
            </ul>
        </div>
    </div>
    </div>
    -->
<?php
// Check if the query returns rows
if ($result->num_rows > 0) {
    // Loop through each row
    while ($row = $result->fetch_assoc()) {
        // Generate HTML block
        echo '
        <div class="card-container">
            <div class="card-items" style="grid-area: 1">
                <i class="fa-solid fa-server"></i>
            </div>
            <div class="card-items" style="grid-area: 2">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="card-items" style="grid-area: 3">
                <ul class="card">
                    <li>Hostname:</li>
                    <li>IP Address:</li>
                    <li>Device Type:</li>
                    <li>OS:</li>
                </ul>
            </div>
            <div class="card-items" style="grid-area: 4">
                <ul class="card">
                    <li>' . htmlspecialchars($row["hostname"]) . '</li>
                    <li>' . htmlspecialchars($row["ip_address"]) . '</li>
                    <li>' . htmlspecialchars($row["device_type"]) . '</li>
                    <li>' . htmlspecialchars($row["os"]) . '</li>
                </ul>
            </div>
        </div>';
    }
} else {
    echo "Please add hosts to monitor";
}
$conn->close();
?>


</div>

<?php
/*
// MySQL Database connection
$servername = "your_server";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data
$sql = "SELECT hostname, ip_address, status, timestamp FROM your_table";
$result = $conn->query($sql);

// Check if the query returns rows
if ($result->num_rows > 0) {
    // Loop through each row
    while ($row = $result->fetch_assoc()) {
        // Generate HTML block
        echo '
        <div class="card-container">
            <div class="card-items" style="grid-area: 1">
                <i class="fa-solid fa-server"></i>
            </div>
            <div class="card-items" style="grid-area: 2">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div class="card-items" style="grid-area: 3">
                <ul class="card">
                    <li>Hostname:</li>
                    <li>IP Address:</li>
                    <li>Online:</li>
                    <li>Timestamp:</li>
                </ul>
            </div>
            <div class="card-items" style="grid-area: 4">
                <ul class="card">
                    <li>' . htmlspecialchars($row["hostname"]) . '</li>
                    <li>' . htmlspecialchars($row["ip_address"]) . '</li>
                    <li>' . htmlspecialchars($row["status"]) . '</li>
                    <li>' . htmlspecialchars($row["timestamp"]) . '</li>
                </ul>
            </div>
        </div>';
    }
} else {
    echo "No data found!";
}

// Close connection
$conn->close();
*/
?>


<?php require_once("../partials/footer.php"); ?>

