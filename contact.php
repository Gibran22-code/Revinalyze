<?php
// Menggunakan session untuk menampilkan pesan setelah pengiriman
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menangani data form yang dikirimkan
    $nama = htmlspecialchars($_POST['nama']); // Sanitasi data untuk menghindari XSS
    $email = htmlspecialchars($_POST['email']);
    $subjek = htmlspecialchars($_POST['subjek']);
    $pesan = htmlspecialchars($_POST['pesan']);

    // Email tujuan (Ganti dengan email Anda di sini)
    $to = ""; // Ganti dengan alamat email Anda
    $subject = "Pesan Kontak dari: $nama - $subjek"; // Subjek email
    $message = "Nama: $nama\nEmail: $email\n\nPesan:\n$pesan"; // Isi pesan
    $headers = "From: $email\r\n" . "Reply-To: $email\r\n" . "X-Mailer: PHP/" . phpversion();

    // Mengirim email
    if (mail($to, $subject, $message, $headers)) {
        $_SESSION['message'] = "Pesan Anda berhasil dikirim. Terima kasih telah menghubungi kami.";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan, pesan tidak terkirim.";
    }
    header("Location: contact.php"); // Mengarahkan kembali ke halaman contact.php setelah form disubmit
    exit();
}   
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <title>Kontak Kami</title>
    <style>
        * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Orbitron', sans-serif; /* Font Futuristik */
  background: radial-gradient(circle at 50% 50%, rgba(30, 30, 47, 1) 0%, rgba(60, 60, 90, 1) 50%, rgba(10, 10, 30, 1) 100%);
  color: #fff;
  line-height: 1.6;
  height: 100vh;
  animation: fadeIn 1.5s ease-in-out;
  background-size: cover;
  background-attachment: fixed;
  position: relative;
}

body::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle at 50% 50%, rgba(0, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.1) 50%, transparent 100%);
  pointer-events: none;
  z-index: 1;
  animation: lightPulse 3s ease-in-out infinite;
}

@keyframes lightPulse {
  0% {
      opacity: 0.15;
  }
  50% {
      opacity: 0.4;
  }
  100% {
      opacity: 0.15;
  }
}


        header {
            color: white;
            padding: 30px;
            text-align: center;
            display: flex;
            margin-top: 5px;
            background: rgba(0, 0, 0, 0,1);
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            transition: background 0.3s ease;
            border-radius: 20px;
        }
        header nav a:hover {

            transform: scale(0.95);
            box-shadow: 0 0 20px #00ffcc, 0 0 20px #00ffcc;
        }
        header nav a:hover {
            background-color: rgba(90, 211, 86, 0.8);
            transform: scale(0.95);
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        .contact-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .contact-form h2 {
            color: #1e4d5d;
            margin-bottom: 20px;
        }

        .contact-form label {
            display: block;
            margin-bottom: 5px;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form button {
            padding: 10px 20px;
            background-color: #1e4d5d;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            cursor: pointer;
        }

        .contact-form button:hover {
            background-color: #156078;
        }
        footer {
  background-color: #000000; /* Latar belakang footer */
  color: #fff; /* Warna teks putih */
  padding: 40px 20px;
  text-align: center;
  font-family: 'Poppins', sans-serif; /* Menggunakan font Poppins untuk footer */
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap;
  margin-bottom: 20px;
}
    </style>
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Dashboard</a></li>
            <li><a href="tentang.php">Tentang</a></li>
            <li><a href="contact.php">Kontak</a></li>
        </ul>
    </nav>
</header>

<section class="contact-form">
    <h2>Kontak Kami</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <form action="contact.php" method="post">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="subjek">Subjek:</label>
        <input type="text" name="subjek" id="subjek" required>

        <label for="pesan">Pesan:</label>
        <textarea name="pesan" id="pesan" rows="5" required></textarea>

        <button type="submit">Kirim Pesan</button>
    </form>
</section>
<br><br><br>

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
    </footer>
</html>
