<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

$webName = "Sistem Penilaian Seminar";
$webDescription = "Sistem ini digunakan untuk menilai peserta seminar berdasarkan beberapa kriteria yang telah ditentukan, serta memberikan umpan balik untuk meningkatkan kualitas seminar di masa depan.";

// Menambahkan informasi fitur yang sesuai dengan tema seminar
$features = [
    [
        "name" => "Fitur 1: Tabel Data Penilaian",
        "details" => "Fitur ini memungkinkan panelis untuk memberikan penilaian terhadap presentasi peserta seminar berdasarkan kriteria yang telah disepakati.",
        "image" => "Penilaian/Pictures/image.jpg"  // Ganti dengan path gambar Anda
    ],
    [   
        "name" => "Fitur 2: Penilaian Interaksi Peserta",
        "details" => "Panelis dapat memberikan penilaian terhadap interaksi peserta selama seminar, termasuk pertanyaan dan diskusi yang diajukan.",
        "image" => "path/to/interaction-image.jpg"  // Ganti dengan path gambar Anda
    ],
    [
        "name" => "Fitur 3: Penilaian Materi Seminar",
        "details" => "Fitur ini digunakan untuk menilai kualitas materi seminar yang disajikan, termasuk kelengkapan dan relevansi materi dengan topik seminar.",
        "image" => "path/to/material-image.jpg"  // Ganti dengan path gambar Anda
    ],
    [
        "name" => "Fitur 4: Umpan Balik Peserta",
        "details" => "Peserta seminar dapat memberikan umpan balik mengenai seminar, yang akan digunakan untuk meningkatkan kualitas seminar di masa depan.",
        "image" => "path/to/feedback-image.jpg"  // Ganti dengan path gambar Anda
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <title>Tentang <?php echo $webName; ?></title>
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
        position: sticky;
        top: 0;
        z-index: 1000;
        background: rgba(0, 0, 0, 0,1);
        color: white;
        padding: 10px 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
        transition: background 0.3s ease;
        border-radius: 20px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        .about-container {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
            margin: 15px;
            padding: 20px;
            transition: transform 0.3s ease;
        }

        h1 {
            color:rgb(17, 228, 182);
        }

        h2 {
            color:rgb(17, 228, 182);
                }

        ul {
            list-style-type: disc;
            margin-left: 20px;
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
        .accordion-button {
            background-color:rgba(93, 109, 116, 0.49);
            color: white;
            padding: 10px 15px;
            width: 100%;
            text-align: left;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .accordion-content {
            padding: 20px;
            background-color:rgba(93, 109, 116, 0.49);
            display: none;
            margin-bottom:10px;
        }

        .accordion-button.active + .accordion-content {
            display: block;
        }

        .feature-image {
            width: 100%;
            max-width: 300px;
            margin-top: 10px;
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            display: none;
        }

        .feature-image.show {
            opacity: 1;
            display: block;
        }
        
        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #00ffff;
            color: #000;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #2a2a80;
        }

        @media (max-width: 768px) {
            .about-container {
                padding: 15px;
            }

            nav ul li {
                display: block;
                margin: 10px 0;
            }

            nav ul li a {
                font-size: 18px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const accordionButtons = document.querySelectorAll('.accordion-button');
            accordionButtons.forEach(button => {
                button.addEventListener('click', () => {
                    button.classList.toggle('active');
                    const content = button.nextElementSibling;
                    content.classList.toggle('show');  // Toggle the "show" class to show or hide content
                });
            });
        });
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="contact.php">Kontak</a></li>
            </ul>
        </nav>
    </header>
<br>
    <section class="about-container">
        <h1>Tentang <?php echo $webName; ?></h1>
        <p><?php echo $webDescription; ?></p>

        <h2>Fitur Utama</h2>
        <div class="accordion">
            <?php foreach ($features as $index => $feature) : ?>
                <div class="accordion-item">
                    <button class="accordion-button"><?php echo $feature['name']; ?></button>
                    <div class="accordion-content">
                        <img src="<?php echo $feature['image']; ?>" alt="Gambar <?php echo $feature['name']; ?>" class="feature-image">
                        <p><?php echo $feature['details']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Tujuan Pengembangan</h2>
        <p>Sistem penilaian seminar ini dikembangkan untuk memudahkan panitia dan penguji dalam memberikan penilaian objektif terhadap peserta seminar, serta memungkinkan pengumpulan umpan balik yang berharga untuk perbaikan acara seminar di masa depan.</p>

        <h2>Laporkan Masalah atau Bug</h2>
        <p>Jika Anda menemukan masalah atau bug di sistem ini, silakan laporkan melalui <a href="contact.php">halaman kontak</a> kami.</p>
    </section>
<br><br>
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
                <li><a href="tentang.php">Tentang Kami</a></li>
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
    <a href="index.php" class="back-button">Kembali ke Dashboard</a>
</body>
</html>
