<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    include_once('../config/db-connect.php');
?>

<h1> Add Hosts </h1>
<?php
class DeviceHandler {
    public function addDevice() {
        $message = isset($_GET['message']) ? $_GET['message'] : '';
        if ($message) {
            $successMessage='
            <div style="padding:10px; color:black; background:white;">
                ' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '
            </div>';
        }else{
            $successMessage = "";
        }

        return  '
            <form action="add-device.php" method="POST">
                '. $successMessage . '
                <label for="hostname">Hostname or IP Address:</label>
                <input type="text" id="hostname" name="hostname" required><br><br>

                <label for="device_type">Device Type:</label>
                <select id="device_type" name="device_type" onchange="updateOSOptions()">
                    <option value="PC">PC</option>
                    <option value="Web Server">Web Server</option>
                    <option value="Switch">Switch</option>
                    <option value="Router">Router</option>
                </select><br><br>

                <label for="os">Operating System:</label>
                <select id="os" name="os">
                    <option value="N/A">N/A</option>
                </select><br><br>

                <button type="submit" name="add-device">Submit</button>
            </form>';
    }

    public function renderDeviceForm() {
        echo $this->addDevice();
    }
}

$deviceHandler = new DeviceHandler();
$deviceHandler->renderDeviceForm();
?>


<?php require_once("../partials/footer.php"); ?>