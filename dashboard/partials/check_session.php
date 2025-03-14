<?php
$inactive = 60; // Example: 1 minute

if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        echo "logged_out";
        exit();
    }
}
echo "active";
?>
