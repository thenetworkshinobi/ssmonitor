<?php
include_once '../config/variables.php';
include_once '../config/db-connect.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../partials/header.php';

// DB connection
$database = new dbConnect();
$dbh = $database->connect();
$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);


// Get AdminID from session data
$adminID = $_SESSION['id'];


// Fetch user info
$check_user_sql = "SELECT id_secret, enabled, username FROM adminuser WHERE adminID = ?";
$check_user_stmt = $dbh->prepare($check_user_sql);
$check_user_stmt->bindValue(1, $adminID);
$check_user_stmt->execute();
$user = $check_user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Admin user not found.");
}

$g = new PHPGangsta_GoogleAuthenticator();

// If no secret yet, generate and store one
if (empty($user['id_secret'])) {
    $id_secret = $g->createSecret();
    $add_secret_stmt = $dbh->prepare("UPDATE adminuser SET id_secret = ? WHERE adminID = ?");
    $add_secret_stmt->bindValue(1, $id_secret);
    $add_secret_stmt->bindValue(2, $adminID);
    $add_secret_stmt->execute();
} else {
    $id_secret = $user['id_secret'];
}

// Generate QR Code URL
$qrCodeUrl = $g->getQRCodeGoogleUrl('SSMonitor-' . $user['username'], $id_secret);

echo "<h1>Two-Factor Setup</h1>";
echo "<div><p>Scan this QR Code with your Authenticator app:</p></div>";
echo "<div><img src='$qrCodeUrl'></div>";

echo "<form method='POST'>
        <label>Enter code from Authenticator:</label><br>
        <input type='text' name='code' required>
        <button type='submit'>Verify</button>
    </form>";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userCode = $_POST['code'];

    if ($g->verifyCode($id_secret, $userCode, 2)) {
        // Mark 2FA as enabled in the database
        $set_user_stmt = $dbh->prepare("UPDATE adminuser SET enabled = TRUE WHERE adminID = ?");
        $set_user_stmt->bindValue(1, $adminID);
        $set_user_stmt->execute();

        echo "<p style='color:green;'>✅ Code is valid. 2FA is now enabled for your account.</p>";
        echo "<p style='color:green;'>Page will refresh automatically.</p>";
        session_unset();
        session_destroy();
        // Redirect after 30 seconds using Refresh header
        header("Refresh: 10; URL=" . $host . "/index.php?action=signin");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Invalid code. Please try again.</p>";
    }
}





