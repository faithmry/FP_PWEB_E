<?php
$host = 'sql304.infinityfree.com';
$username = 'if0_37868054';
$password = '81haP9pwLpvURfG'; // Ganti sesuai password database Anda
$dbname = 'if0_37868054_workflow';

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>