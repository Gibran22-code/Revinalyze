<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
$averages = [
    'kelengkapanOjt' => 0,
    'kerangkaLaporan' => 0,
    'pertanyaan' => 0,
    'presentasi' => 0,
    'rencanaAksi' => 0,
];

// Fungsi untuk menghitung rata-rata
function getAverage($conn, $sql) {
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['avg_value'];
    }
    return 0;
}

// Ambil data ID dan nama peserta
$sql = "SELECT id, nama FROM data_utama";    
$result = $conn->query($sql);
$names = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $names[$row['id']] = $row['nama'];  // Menyimpan nama berdasarkan ID
    }
}

// Query untuk menghitung total nilai per ID dari berbagai tabel
$sqls = [
    "SELECT id, (Lpr_BukuHarian + Dokumentasi + Nilai_2 + Bobot_10) AS total_angka FROM kelengkapan_ojt",
    "SELECT id, (Lpr_Ketentuan + Sistematika + Refleksi + Nilai_1 + Bobot_10) AS total_angka FROM kerangka_laporan",
    "SELECT id, (Menjawab_Jelas + Argumentasi + Nilai_5 + Bobot_20) AS total_angka FROM pertanyaan",
    "SELECT id, (Presentasi_Jelas + Mudah_Dicerna + Nilai_4 + Bobot_20) AS total_angka FROM presentasi",
    "SELECT id, (Lpr_rencanaAksi + Lpr_Ditargetkan + Output_OJT + Output_Standar + Nilai_3 + Bobot_40) AS total_angka FROM rencana_aksi",
    "SELECT id, (Nsebelum_Perbaikan + Nsetelah_Perbaikan) AS total_angka FROM data_lainnya"
];

// Menjalankan query untuk setiap tabel
$results = [];
foreach ($sqls as $sql) {
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
            if (!isset($results[$id])) {
                $results[$id] = 0;  // Jika ID belum ada, inisialisasi
            }
            $results[$id] += $row['total_angka'];  // Menjumlahkan total angka per ID
        }
    }
}

// Ambil jumlah peserta
$sql_count = "SELECT COUNT(id) AS jumlah_id FROM data_utama";
$count_result = $conn->query($sql_count);
$jumlahId = 0;
if ($count_result && $count_result->num_rows > 0) {
    $row = $count_result->fetch_assoc();
    $jumlahId = $row['jumlah_id'];
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



// Query untuk menghitung rata-rata per field pada masing-masing tabel
$sql_kel_ojt = "SELECT 
                    AVG(CAST(Lpr_BukuHarian AS DECIMAL)) AS avg_Lpr_BukuHarian,
                    AVG(CAST(Dokumentasi AS DECIMAL)) AS avg_Dokumentasi,
                    AVG(CAST(Nilai_2 AS DECIMAL)) AS avg_Nilai_2,
                    AVG(CAST(Bobot_10 AS DECIMAL)) AS avg_Bobot_10
                FROM kelengkapan_ojt";

$sql_kerangka_laporan = "SELECT 
                            AVG(CAST(Lpr_Ketentuan AS DECIMAL)) AS avg_Lpr_Ketentuan,
                            AVG(CAST(Sistematika AS DECIMAL)) AS avg_Sistematika,
                            AVG(CAST(Refleksi AS DECIMAL)) AS avg_Refleksi,
                            AVG(CAST(Nilai_1 AS DECIMAL)) AS avg_Nilai_1,
                            AVG(CAST(Bobot_10 AS DECIMAL)) AS avg_Bobot_10
                         FROM kerangka_laporan";

$sql_pertanyaan = "SELECT 
                        AVG(CAST(Menjawab_Jelas AS DECIMAL)) AS avg_Menjawab_Jelas,
                        AVG(CAST(Argumentasi AS DECIMAL)) AS avg_Argumentasi,
                        AVG(CAST(Nilai_5 AS DECIMAL)) AS avg_Nilai_5,
                        AVG(CAST(Bobot_20 AS DECIMAL)) AS avg_Bobot_20
                    FROM pertanyaan";

$sql_presentasi = "SELECT 
                        AVG(CAST(Presentasi_Jelas AS DECIMAL)) AS avg_Presentasi_Jelas,
                        AVG(CAST(Mudah_Dicerna AS DECIMAL)) AS avg_Mudah_Dicerna,
                        AVG(CAST(Nilai_4 AS DECIMAL)) AS avg_Nilai_4,
                        AVG(CAST(Bobot_20 AS DECIMAL)) AS avg_Bobot_20
                    FROM presentasi";

$sql_rencana_aksi = "SELECT 
                        AVG(CAST(Lpr_rencanaAksi AS DECIMAL)) AS avg_Lpr_rencanaAksi,
                        AVG(CAST(Lpr_Ditargetkan AS DECIMAL)) AS avg_Lpr_Ditargetkan,
                        AVG(CAST(Output_OJT AS DECIMAL)) AS avg_Output_OJT,
                        AVG(CAST(Output_Standar AS DECIMAL)) AS avg_Output_Standar,
                        AVG(CAST(Nilai_3 AS DECIMAL)) AS avg_Nilai_3,
                        AVG(CAST(Bobot_40 AS DECIMAL)) AS avg_Bobot_40
                    FROM rencana_aksi";

$sql_data_lainnya = "SELECT 
                        AVG(CAST(Nsebelum_Perbaikan AS DECIMAL)) AS avg_Nsebelum_Perbaikan,
                        AVG(CAST(Nsetelah_Perbaikan AS DECIMAL)) AS avg_Nsetelah_Perbaikan
                    FROM data_lainnya";

// Fungsi untuk menjalankan query dan mendapatkan hasil rata-rata
function getAverageData($conn, $sql) {
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return array_values($row);  // Mengembalikan hasil rata-rata dalam bentuk array
    }
    return array_fill(0, count(explode(',', $sql)), 0); // Mengembalikan array 0 jika tidak ada data
}

$field_averages_kel_ojt = getAverageData($conn, $sql_kel_ojt);
$field_averages_kerangka_laporan = getAverageData($conn, $sql_kerangka_laporan);
$field_averages_pertanyaan = getAverageData($conn, $sql_pertanyaan);
$field_averages_presentasi = getAverageData($conn, $sql_presentasi);
$field_averages_rencana_aksi = getAverageData($conn, $sql_rencana_aksi);
$field_averages_data_lainnya = getAverageData($conn, $sql_data_lainnya);
// Judul halaman
$title = "";
$year = date("Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title><?php echo $title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <link rel="stylesheet" href="index.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <nav style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
        <img title="Revinalyze" src="Pictures/RV2.png" alt="Logo" class="logo">
        <h1 class="neon-text">REVINALYZE</h1>                                                                         
        <div style="display: flex; justify-content: flex-end; align-items: center; flex-grow: 1;">
            <!-- Profile Dropdown -->
            <details>
                <summary title="Lihat & Edit"><a>PROFIL ANDA</summary></a>
                <div class="card-details">
                <h2 class="neon-text">Profil Anda</h2>
                <div class="profile-picture">
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil Picture">
                    <?php else: ?>
                        <img src="default-avatar.png" alt="Profil Picture">
                    <?php endif; ?>
                </div>
                <table class="profile-table">
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
                <button class="futuristic-button" onclick="window.location.href='edit_profil.php'">
                    EDIT PROFIL
                </button>
            </div>
        </div>
    </details>

    <a href="Utama.php">Halaman Utama</a>

    <a href="#" class="btn" id="toggleButton">☰ Open Navbar</a>
    <div id="navbar">
        <div class="profile-picture">
            <?php if (!empty($user['profile_picture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profil Picture" id="profilePic" title=" <?php echo htmlspecialchars($user['username']); ?>">
            <?php else: ?>
                <img src="default-avatar.png" alt="Profil Picture" id="profilePic" title="ID Pengguna: <?php echo htmlspecialchars($user['id']); ?>">
            <?php endif; ?>
        </div>

        <h1 class="neon-text"><i>SELAMAT DATANG <?php echo htmlspecialchars($user['username']); ?> !</i></h1><br><br>

        <!-- Link-menu navbar --><br>
        <a href="utama.php">Halaman utama</a><br><br><br>
        <a href="form_data_laporan.php">Tambah Data</a><br><br><br>
        <a href="data.php">Tabel Data </a><br><br><br>
        <a href="penilaian_selesai.php">Update Status</a><br><br><br>
        <a href="tbl_total.php">Nilai Perpeserta</a><br><br><br>
    </div>

    <script>
    const toggleButton = document.getElementById("toggleButton");
    const navbar = document.getElementById("navbar");

    // Function to open/close the navbar
    toggleButton.onclick = function() {
        if (navbar.classList.contains("active")) {
            navbar.classList.remove("active"); // Close the navbar
            toggleButton.innerHTML = "☰ Open Navbar"; // Change button text
        } else {
            navbar.classList.add("active"); // Open the navbar
            toggleButton.innerHTML = "✖ Close Navbar"; // Change button text
        }
    };

    // Close the navbar when clicking outside of it
    document.addEventListener("click", function(event) {
        if (!navbar.contains(event.target) && !toggleButton.contains(event.target)) {
            navbar.classList.remove("active");
            toggleButton.innerHTML = "☰ Open Navbar"; // Reset button text
        }
    });
</script>

<div class="settings-container">
    <a href="#" title="Tentang &amp; Pengaturan" id="settings-link">
      <span class="icon-nav-settings" id="nav-settings" style="font-size: 30px;">&#9881;</span>
    </a>

    <!-- Extra Buttons for Settings -->
    <div id="extra-buttons">
      <button class="button" id="button1" onclick="window.location.href='tentang.php';">Tentang</button>
      <button class="button" id="button2" onclick="window.location.href='logout.php';">Log Out</button>
    </div>
  </div>

  <script>
    // Mendapatkan elemen tombol pengaturan
    const settingsLink = document.getElementById('settings-link');
    const extraButtons = document.getElementById('extra-buttons');

    settingsLink.addEventListener('click', function(e) {
      e.preventDefault(); // Mencegah aksi default dari link

      // Toggle tampilan dropdown
      if (extraButtons.style.display === 'none' || extraButtons.style.display === '') {
        extraButtons.style.display = 'block';
      } else {
        extraButtons.style.display = 'none';
      }
    });

    // Menutup dropdown ketika klik di luar area settings-container
    document.addEventListener('click', function(e) {
      if (!e.target.closest('.settings-container')) {
        extraButtons.style.display = 'none';
      }
    });
  </script>
</nav>
</header>
<main>
<main>
    <br>
<div class="container">
   <!-- Card Tentang Penilaian (Kiri) -->
   <div class="tentang-penilaian">
    <h2 class="neon-text">Tentang Penilaian</h2>
    <div class="card-content">
      <p>
        Halaman ini menyediakan informasi tentang cara melakukan penilaian dan mendapatkan nilai terbaik berdasarkan kriteria yang ada. Penilaian yang tepat dan objektif merupakan kunci untuk mencapai hasil yang maksimal dalam setiap aspek yang diuji.
      </p><br>
      <p>
        Setiap kriteria penilaian memiliki bobot yang berbeda, dan penting untuk memahami prioritas yang diberikan. Oleh karena itu, alokasikan waktu dan usaha secara bijaksana untuk setiap bagian, sesuai dengan tingkat kepentingannya.
      </p><br>
      <p>
        Dengan mengikuti panduan yang ada di halaman ini, Anda dapat lebih siap dan percaya diri dalam menghadapi penilaian. Ingatlah bahwa penilaian bukan hanya tentang mencapai nilai tertinggi, tetapi juga tentang proses pembelajaran dan pengembangan diri yang terus berlanjut.
      </p><br>
    </div>
  </div>

   <!-- Card Informasi Jumlah ID (Kanan) -->
   <div class="jumlah-id-card">
   <br>
      <div class="jumlah-id">
         <div class="jumlah-id-container">
            <div class="jumlah-id-icon">
               <img src="Pictures/logo.png" alt="Logo" class="logo">
            </div>
            <div class="jumlah-id-details">
               <p class="neon-text">Jumlah ID: <?php echo htmlspecialchars($jumlahId); ?></p>
            </div>
         <button class="futuristic-button" onclick="location.href='data.php';">Lihat Detail</button>
      </div><br><br>
            <div class="jumlah-id-details">
            <p class="neon-text">Lihat Presentase Nilai Di Atas 70?</p>
            </div><br>
         <button class="futuristic-button" onclick="location.href='presentase.php';">Lihat!</button><br>
      </div>
</div>
   </div>
</div>


</main>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const reveals = document.querySelectorAll(".reveal");

        function checkReveals() {
            const windowHeight = window.innerHeight;

            reveals.forEach((reveal) => {
                const revealTop = reveal.getBoundingClientRect().top;

                if (revealTop < windowHeight - 50) {
                    // Tambahkan kelas active jika elemen terlihat
                    reveal.classList.add("active");
                } else {
                    // Hapus kelas active jika elemen tidak terlihat
                    reveal.classList.remove("active");
                }
            });
        }

        // Panggil fungsi saat scroll dan saat halaman dimuat
        window.addEventListener("scroll", checkReveals);
        checkReveals(); // Untuk memeriksa saat pertama kali dimuat
    });
</script>

<br>

</main>
<br>
<div class="chart-container">
    <div class="reveal">
        <a class="neon-text">Grafik Rata-Rata Nilai per Field Kelengkapan OJT</a>
        <canvas id="chart3"></canvas>
    </div>  

    <div class="reveal">
        <a class="neon-text">Grafik Rata-Rata Nilai per Field Kerangka Laporan</a>
        <canvas id="chart4"></canvas>
    </div>

    <div class="reveal">
        <a class="neon-text">Grafik Rata-Rata Nilai per Field Pertanyaan</a>
        <canvas id="chart5"></canvas>
    </div>

    <div class="reveal">
        <a class="neon-text">Grafik Rata-Rata Nilai per Field Presentasi</a>
        <canvas id="chart6"></canvas>
    </div>

    <div class="reveal">
        <a class="neon-text">Grafik Rata-Rata Nilai per Field Rencana Aksi</a>
        <canvas id="chart7"></canvas>
    </div>

    <div class="reveal">
        <a class="neon-text">Grafik Rata-Rata Data Lainnya</a>
        <canvas id="chart8"></canvas>
    </div>
</div>


<script>
    // Fungsi untuk membuat grafik
    function createChart(ctx, labels, data, label, bgColor, borderColor) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: bgColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            }
        });
    }

    // Data yang didapatkan dari PHP
    var fieldAveragesKelOjt = <?php echo json_encode($field_averages_kel_ojt); ?>;
    var fieldAveragesKerangkaLaporan = <?php echo json_encode($field_averages_kerangka_laporan); ?>;
    var fieldAveragesPertanyaan = <?php echo json_encode($field_averages_pertanyaan); ?>;
    var fieldAveragesPresentasi = <?php echo json_encode($field_averages_presentasi); ?>;
    var fieldAveragesRencanaAksi = <?php echo json_encode($field_averages_rencana_aksi); ?>;
    var fieldAveragesDataLainnya = <?php echo json_encode($field_averages_data_lainnya); ?>;

    // Membuat grafik untuk Kelengkapan OJT
    createChart(document.getElementById('chart3').getContext('2d'), 
                ['Lpr_BukuHarian', 'Dokumentasi', 'Nilai_2', 'Bobot_10'], 
                fieldAveragesKelOjt, 
                'Rata-rata Kelengkapan OJT', 
                'rgba(75, 192, 192, 0.2)', 
                'rgba(75, 192, 192, 1)');

    // Membuat grafik untuk Kerangka Laporan
    createChart(document.getElementById('chart4').getContext('2d'), 
                ['Lpr_Ketentuan', 'Sistematika', 'Refleksi', 'Nilai_1', 'Bobot_10'], 
                fieldAveragesKerangkaLaporan, 
                'Rata-rata Kerangka Laporan', 
                'rgba(255, 99, 132, 0.2)', 
                'rgba(255, 99, 132, 1)');

    // Membuat grafik untuk Pertanyaan
    createChart(document.getElementById('chart5').getContext('2d'), 
                ['Menjawab_Jelas', 'Argumentasi', 'Nilai_5', 'Bobot_20'], 
                fieldAveragesPertanyaan, 
                'Rata-rata Pertanyaan', 
                'rgba(153, 102, 255, 0.2)', 
                'rgba(153, 102, 255, 1)');

    // Membuat grafik untuk Presentasi
    createChart(document.getElementById('chart6').getContext('2d'), 
                ['Presentasi_Jelas', 'Mudah_Dicerna', 'Nilai_4', 'Bobot_20'], 
                fieldAveragesPresentasi, 
                'Rata-rata Presentasi', 
                'rgba(255, 159, 64, 0.2)', 
                'rgba(255, 159, 64, 1)');

    // Membuat grafik untuk Rencana Aksi
    createChart(document.getElementById('chart7').getContext('2d'), 
                ['Lpr_rencanaAksi', 'Lpr_Ditargetkan', 'Output_OJT', 'Output_Standar', 'Nilai_3', 'Bobot_40'], 
                fieldAveragesRencanaAksi, 
                'Rata-rata Rencana Aksi', 
                'rgba(110, 195, 252, 0.2)', 
                'rgba(54, 162, 235, 1)');

    // Membuat grafik untuk Data Lainnya
    createChart(document.getElementById('chart8').getContext('2d'), 
                ['Nsebelum_Perbaikan', 'Nsetelah_Perbaikan'], 
                fieldAveragesDataLainnya, 
                'Rata-rata Data Lainnya', 
                'rgba(57, 223, 79, 0.2)', 
                'rgb(3, 75, 12)');
</script>
</div>
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
    <a href="index.php" class="back-button">Kembali ke atas</a>
</body>
</html>
