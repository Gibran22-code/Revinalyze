<?php

$servername = "localhost"; 
$username = "root";        
$password = "";            
$database = "db_penilaian";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengecek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil input dari form dan membersihkan spasi di awal dan akhir
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi konfirmasi password
    if ($password !== $confirm_password) {
        echo "Kata sandi dan konfirmasi kata sandi tidak cocok! ";
        header("Location: regis.php");
        exit();
    }

    // Mengecek apakah username sudah terdaftar
    $sql_check = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->bind_param("s", $username); // Menggunakan parameter untuk mencegah SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username sudah terdaftar!";
    } else {
        // Meng-hash password sebelum menyimpannya ke database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Menyimpan data ke dalam database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password); // Binding parameter untuk keamanan
        if ($stmt->execute()) {
            header("Location: login.php"); // Redirect setelah sukses registrasi
            exit();
        } else {
            echo "Terjadi kesalahan dalam menyimpan data!";
        }
    }

    $stmt->close();
}

// Menutup koneksi
$conn->close();
?>
