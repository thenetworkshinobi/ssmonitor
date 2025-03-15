<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    require_once("partials/device-handler.php");
    require_once('../config/db-connect.php');
?>

<h1> Remove Hosts </h1>
<?php
    $deviceHandler = new DeviceHandler();
    $deviceHandler->renderRemoveForm();
?>

<?php require_once("../partials/footer.php"); ?> 