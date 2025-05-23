<?php
include 'koneksi.php';
session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE name = '$name' AND password = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['user'] = $name;
        echo "<script>alert('Login berhasil!'); window.location.href = 'peminjaman.php';</script>";
    } else {
        echo "<script>alert('Username atau Password salah!'); window.location.href = 'index.php';</script>";
    }
}
?>