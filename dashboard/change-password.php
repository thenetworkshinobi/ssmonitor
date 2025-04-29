<?php
include_once("../config/variables.php");
include_once("../config/db-connect.php");


$message = [];

if (!isset($_SESSION['username'])) {
    header("Location: " . $host . "/?action=signin&message=" . urlencode("Please sign in"));
    exit();
}

if (isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['username'];

    // Input validation
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $message[] = "All fields are required";
    } elseif (strlen($new_password) < 6) {
        $message[] = "New password must be at least 6 characters long";
    } elseif ($new_password !== $confirm_password) {
        $message[] = "New password and confirmation do not match";
    }

    if (!empty($message)) {
        header("Location: " . $host . "/index.php?action=changepassword&message=" . urlencode($message[0]));
        exit();
    }

    // Connect to DB and fetch hashed password
    $database = new dbConnect();
    $dbh = $database->connect();
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    $stmt = $dbh->prepare("SELECT adminID, password FROM adminuser WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user || !password_verify($old_password, $user->password)) {
        $message[] = "Old password is incorrect";
        header("Location: " . $host . "/index.php?action=changepassword&message=" . urlencode($message[0]));
        exit();
    }

    // Hash and update new password
    $hashedNewPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $dbh->prepare("UPDATE adminuser SET password = :new_password WHERE adminID = :id");
    $update_stmt->bindParam(':new_password', $hashedNewPassword);
    $update_stmt->bindParam(':id', $user->adminID);
    $update_stmt->execute();

    $message[] = "Password changed successfully.";
    header("Location: " . $host . "/index.php?action=changepassword&message=" . urlencode($message[0]));
    exit();
}
