<?php
session_start();
include('koneksi.php'); // Pastikan koneksi ke database sudah benar

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
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg"> 
    <title>Halaman Utama</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #0a0a0a;
            color: #fff;
            line-height: 1.6;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid #00ffff;
            margin-bottom: 15px;
            box-shadow: 0 0 10px #00ffff;
        }

        .profile-info h2 {
            font-size: 1.5rem;
            color: #00ffff;
            margin-bottom: 10px;
            text-shadow: 0 0 5px #00ffff;
        }

        .profile-info p {
            color: #bbb;
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .hero h1 {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #00ffff;
            text-shadow: 0 0 10px #00ffff;
        }

        .hero p {
            color: #bbb;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .cta {
            padding: 10px 20px;
            background-color: transparent;
            color: #00ffff;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 5px;
            border: 2px solid #00ffff;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px #00ffff;
        }

        .cta:hover {
            background-color: #00ffff;
            color: #0a0a0a;
            box-shadow: 0 0 20px #00ffff;
        }
    </style>
</head>
<body>

    <section class="hero">
        <div class="profile">
            <!-- Menampilkan gambar profil pengguna -->
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil Picture">
            <?php else: ?>
                <img src="default-avatar.png" alt="Profil Picture">
            <?php endif; ?>
            <div class="profile-info">
                <h2>Selamat Datang, <?php echo htmlspecialchars($user['nama']); ?>!</h2>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Telepon: <?php echo htmlspecialchars($user['telepon']); ?></p>
            </div>
        </div>
        <p>Kelola dan pantau nilai dengan mudah dan cepat.</p>
        <a href="index.php" class="cta">Mulai Sekarang</a>
    </section>

</body>
</html>