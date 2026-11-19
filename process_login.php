<?php
session_start();
require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

// cắt khoảng trắng
$username = trim($_POST['username']);
$password = trim($_POST['password']);

$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($user) {
    // Đăng nhập thành công -> Lưu Session
    $_SESSION['adminlogin'] = true;
    $_SESSION['adminusername'] = $user['username'];
    
    // Chuyển hướng sang trang quản lý
    header("Location: manage.php");
    exit();
} else {
    // Sai tài khoản -> Về trang login kèm thông báo lỗi
    header("Location: login.php?error=1");
    exit();
}
?>