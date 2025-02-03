<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
// Memastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Mengambil data pengguna dari database
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Memproses pembaruan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);

    // Proses upload gambar profil jika ada
    $profile_picture = $user['profile_picture']; // Default jika tidak ada gambar baru
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi tipe file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $file_name; // Simpan nama file gambar
            } else {
                echo "Terjadi kesalahan saat mengunggah gambar.";
            }
        } else {
            echo "Tipe file tidak diizinkan. Harap unggah file gambar (jpg, jpeg, png, gif).";
        }
    }

    // Query untuk memperbarui data pengguna
    $update_query = "UPDATE users SET nama = '$nama', email = '$email', alamat = '$alamat', telepon = '$telepon', profile_picture = '$profile_picture' WHERE username = '$username'";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location = 'profil.php';</script>";
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="css.css"> 
</head>
<body>

    <header>
        <h1>Edit Profil</h1>
        <nav>
            <ul>
                <a href="utama.php">Halaman Utama</a></li>
                <a href="index.php">Dashboard</a></li>
                <a href="profil.php">Profil</a></li>
            </ul>
        </nav>
    </header>
<div class="card-container">
    <div class="card">
    <main>
        <section class="profile-edit">
            <h2>Perbarui Informasi Anda</h2>
            <form method="POST" action="edit_profil.php" enctype="multipart/form-data">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                    <br>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <br>
                <label for="alamat">Alamat:</label>
                <textarea name="alamat" id="alamat" required><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                    <br>
                <label for="telepon">Telepon:</label>
                <input type="text" name="telepon" id="telepon" value="<?php echo htmlspecialchars($user['telepon']); ?>" required>
                    <br>
                <!-- Formulir untuk mengganti gambar profil -->
                <label for="profile_picture">Gambar Profil:</label>
                <input title="Untuk Tampilan Menarik Gunakan Format (png)" type="file" name="profile_picture" id="profile_picture" accept="image/*">
                <br><br>
                <button class="square-button" type="submit">Perbarui Profil</button>
            </form>
        </section>
    </main>
</div>
</div><br><br><br>
<footer>
    <div class="footer-content">
        <div class="contact-info">
            <h3>Kontak Kami</h3>
            <p>Email: m.gibranizar@gmail.com</p>
            <p>Telepon: +62 822-1002-8965</p>
            <p>+62 895-1217-9072</p>
        </div>

        <div class="footer-links">
            <ul>
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="data.php">Data Penilaian</a></li>
            </ul>
        </div>

        <div class="social-icons">
            <a href="#" target="_blank">&#xf09a;</a>
            <a href="#" target="_blank">&#xf099;</a>
            <a href="#" target="_blank">&#xf0d5;</a>
            <a href="#" target="_blank">&#xf16d;</a>
        </div>
    </div>
    <p>&copy; <?php echo date('Y'); ?> BADAN DIKLAT PKN BPK RI. Dibuat oleh M.Gibranizar Aufa. Semua Hak Dilindungi.</p>
    <a href="index.php" class="back-button">Kembali</a>

</body>
</html>
