<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid.");
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows !== 1) {
    die("Login pembeli gagal.");
}

$stmt->bind_result($id, $hash, $role);
$stmt->fetch();

if ($role !== 'buyer') {
    die("Akses ditolak. Anda bukan pembeli.");
}

if (!password_verify($password, $hash)) {
    die("Password salah.");
}

session_regenerate_id(true);
$_SESSION['user_id'] = $id;
$_SESSION['role'] = $role;

header("Location: views/buyer_dashboard.php");
exit();


<form action="../buyer_login.php" method="POST">
    <input type="text" name="username" placeholder="Username pembeli" required>
    <input type="password" name="password" placeholder="Password pembeli" required>
    <button type="submit">Login Pembeli</button>
</form>
?>
