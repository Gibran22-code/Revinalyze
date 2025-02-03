<?php
// Memulai sesi untuk memeriksa apakah ada notifikasi
session_start();

// Memeriksa apakah parameter "registration=success" ada di URL
$registration_success = isset($_GET['registration']) && $_GET['registration'] == 'success';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Form Registrasi</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">

</head>
<body>

<div class="card-container">
    <div class="card">
    <h2>Form Registrasi</h2>
    <form action="register_process.php" method="POST">
        <label for="username">Nama Pengguna:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Kata Sandi:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="confirm_password">Konfirmasi Kata Sandi:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    <button class="square-button">Click</button>
       
        <p>Sudah punya akun? <a href ="login.php">Login di sini</a></p>
</div>
 </form>

    <script>
        // Jika notifikasi ada, tampilkan dan sembunyikan setelah beberapa detik
        window.onload = function() {
            var notification = document.getElementById("notification");
            if (notification) {
                notification.style.display = "block";  // Tampilkan notifikasi
                setTimeout(function() {
                    notification.style.display = "none";  // Sembunyikan notifikasi setelah 5 detik
                }, 5000);
            }
        };
    </script>
</body>
</html>
