<?php
if (!isset($_SESSION['username'])) {
    header("Location: " . $host . "/?action=signin");
    exit();
} else if (!isset($_SESSION['totp']) || $_SESSION['totp'] == false) {
    header("Location: " . $host . "/?action=totp");
    exit();
}

// PHP session inactivity timeout
$inactive = 60; // in seconds

if (isset($_SESSION['timeout'])) {
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        echo "<script>
            window.location.href = '" . $host . "/?action=signin';
        </script>";
        exit();
    }
}

$_SESSION['timeout'] = time(); // reset timeout marker
?>

<script>
    let timeoutSeconds = 60; // same as $inactive
    setTimeout(function () {
        alert("You have been logged out due to inactivity.");
        window.location.href = "<?php echo $host; ?>/?action=signin";
    }, timeoutSeconds * 1000);
</script>


