<?php
session_start();
if ($_POST['username'] === 'admin' && $_POST['password'] === 'kp123') {
    $_SESSION['admin'] = true;
    header("Location: dashboard.php");
}
?>
<form method="POST">
    <input type="text" name="username" placeholder="Admin Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
