<?php
    require_once("../config/variables.php");
    require_once("../partials/header.php");
    require_once("partials/login-verify.php");
    require_once('../config/db-connect.php');
?>

<h2>Change Password</h2>
<?php if (isset($_GET['message'])): ?>
    <p style="color:red;"><?php echo htmlspecialchars($_GET['message']); ?></p>
<?php endif; ?>

<form method="POST" action="change-password.php">
    <label>Old Password:</label><br>
    <input type="password" name="old_password" required><br><br>

    <label>New Password:</label><br>
    <input type="password" name="new_password" required><br><br>

    <label>Confirm New Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>

    <button class="b1" type="submit" name="change_password">Change Password</button>
</form>
