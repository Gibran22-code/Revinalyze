<?php
// Mulai session
session_start();

// Hapus semua session yang ada
session_unset();

// Hancurkan session
session_destroy();

// Arahkan pengguna ke halaman login atau halaman utama
header("Location: login.php"); // Ganti dengan URL halaman yang sesuai
exit();
?>
