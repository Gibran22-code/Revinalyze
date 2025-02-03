<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <link rel="stylesheet" href="css.css">
    <title>Profil Pengguna</title>
</head>
<body>

    <header>
        <h1>Profil Pengguna</h1>
        <nav>
            <ul>
                <a href="utama.php">Beranda</a></li>
                <a href="index.php">Dashboard</a></li>
                <a href="edit_profil.php">Edit Profil Anda</a>
            </ul>
        </nav>
    </header>

    <main>
        <section class="profile">
            <div class="profile-picture">
                <!-- Menampilkan gambar profil jika ada -->
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil Picture" width="150" height="150">
                <?php else: ?>
                    <img src="default-avatar.png" alt="Profil Picture" width="150" height="150"> <!-- Gambar default jika pengguna belum mengunggah gambar -->
                <?php endif; ?>
            </div>
            <table>
                <tr>
                    <th>Nama</th>
                    <td><?php echo htmlspecialchars($user['nama']); ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td><?php echo htmlspecialchars($user['alamat']); ?></td>
                </tr>
                <tr>
                    <th>Telepon</th>
                    <td><?php echo htmlspecialchars($user['telepon']); ?></td>
                </tr>
            </table>
        </section>
    </main>


</body>
</html>
