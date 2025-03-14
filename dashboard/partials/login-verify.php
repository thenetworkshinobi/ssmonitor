<?php

if (!isset($_SESSION['username'])){
    header("Location: " . $host . "/?action=signin");
    exit();
}else if (!isset($_SESSION['id_secret']) || $_SESSION['id_secret'] == false){
    header("Location: " . $host . "/?action=totp");
    exit();
}
$inactive = 600;

if (isset($_SESSION['timeout'])){
    $session_life = time() - $_SESSION['timeout'];
    if ($session_life > $inactive){
        session_unset();
        session_destroy();
        echo "<script type='text/javascript'>
            window.onload = function(){
                alert('You have been logged out due to inactivity.');
                window.location.href = '".$host . "/?action=signin';
            };
        </script>";
        exit();
    }
}
$_SESSION['timeout'] = time();
?>


<?php
/*
session_start();
$inactive = 60; // Example: 1 minute

if (isset($_SESSION['timeout'])) {
    $_SESSION['timeout'] = time();
}
?>

<?php

<script type="text/javascript">
function checkSession() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "check_session.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "logged_out") {
                alert('You have been logged out due to inactivity.');
                window.location.href = '<?php echo $host; ?>/?action=signin';
            }
        }
    };
    xhr.send();
}

// Call the checkSession function every minute
setInterval(checkSession, 60000); // 60000 milliseconds = 1 minute
</script>
*/
?>
