<?php
include_once("../config/variables.php");
include_once("../config/db-connect.php");

if (isset($_POST['signin'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Don't escape or hash yet

    $message = [];

    if (empty($username) || empty($password)) {
        $message[] = "Please fill all the fields";
        header("Location: " . $host . "/index.php?action=signin&message=" . urlencode($message[0]));
        exit();
    }

    $database = new dbConnect();
    $dbh = $database->connect();
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    // Fetch user
    $stmt = $dbh->prepare("SELECT adminID, fname, username, password, id_secret FROM adminuser WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user) {
        $message[] = "Incorrect username or password";
        header("Location: " . $host . "/index.php?action=signin&message=" . urlencode($message[0]));
        exit();
    }

    $dbPassword = $user->password;
    $loginSuccessful = false;

    // First try password_verify (secure)
    if (password_verify($password, $dbPassword)) {
        $loginSuccessful = true;
    }

    // If it fails, try old md5 check (legacy)
    elseif (md5($password) === $dbPassword) {
        $loginSuccessful = true;

        // Upgrade hash in DB
        $newHashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $update = $dbh->prepare("UPDATE adminuser SET password = :new_password WHERE adminID = :id");
        $update->bindParam(':new_password', $newHashedPassword);
        $update->bindParam(':id', $user->adminID);
        $update->execute();
    }

    if (!$loginSuccessful) {
        $message[] = "Incorrect username or password";
        header("Location: " . $host . "/index.php?action=signin&message=" . urlencode($message[0]));
        exit();
    }

    // ✅ Login OK, start session
    session_start();
    $_SESSION['id'] = $user->adminID;
    $_SESSION['fname'] = $user->fname;
    $_SESSION['username'] = $user->username;
    $_SESSION['id_secret'] =  false;

    
    if (!$_SESSION['id_secret']) {
        header("Location: " . $host . "/index.php?action=totp");
        exit();
    }
}
