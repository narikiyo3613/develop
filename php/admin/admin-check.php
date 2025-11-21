<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_login'])) {
    header("Location: login.php");
    exit;
}
