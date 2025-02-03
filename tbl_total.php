<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
// Cek koneksi  
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menangkap nilai pencarian
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : ''; 

// Query untuk mengambil data peserta berdasarkan pencarian
if (!empty($searchTerm)) {
    $sql = "SELECT id, nama, penilaian_selesai FROM data_utama 
            WHERE nama LIKE ? OR telepon LIKE ? OR alamat LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTermWildcard = "%" . $searchTerm . "%";
    $stmt->bind_param("sss", $searchTermWildcard, $searchTermWildcard, $searchTermWildcard);
} else {
    $sql = "SELECT id, nama, penilaian_selesai FROM data_utama";
    $stmt = $conn->prepare($sql);
}

// Menjalankan query untuk data peserta
$stmt->execute();
$result = $stmt->get_result();

// Menyimpan hasil ID peserta
$results = [];

// Menyimpan hasil data peserta
$participants = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];

    // Menyimpan data peserta untuk ditampilkan di tabel
    $participants[] = $row;

    // Inisialisasi total angka
    $total_angka = 0;

    // Query untuk menghitung total nilai per ID dari berbagai tabel
    $sqls = [
        "SELECT (Lpr_BukuHarian + Dokumentasi + Nilai_2 + Bobot_10) AS total_angka FROM kelengkapan_ojt WHERE id = ?",
        "SELECT (Lpr_Ketentuan + Sistematika + Refleksi + Nilai_1 + Bobot_10) AS total_angka FROM kerangka_laporan WHERE id = ?",
        "SELECT (Menjawab_Jelas + Argumentasi + Nilai_5 + Bobot_20) AS total_angka FROM pertanyaan WHERE id = ?",
        "SELECT (Presentasi_Jelas + Mudah_Dicerna + Nilai_4 + Bobot_20) AS total_angka FROM presentasi WHERE id = ?",
        "SELECT (Lpr_rencanaAksi + Lpr_Ditargetkan + Output_OJT + Output_Standar + Nilai_3 + Bobot_40) AS total_angka FROM rencana_aksi WHERE id = ?",
        "SELECT (Nsebelum_Perbaikan + Nsetelah_Perbaikan) AS total_angka FROM data_lainnya WHERE id = ?"
    ];

    // Menghitung total nilai untuk setiap ID
    foreach ($sqls as $sql) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // Mengikat ID
        $stmt->execute();
        $resultTotal = $stmt->get_result();
        if ($rowTotal = $resultTotal->fetch_assoc()) {
            $total_angka += $rowTotal['total_angka']; // Menambahkan total angka
        }
    }

    // Menyimpan hasil total angka untuk setiap ID
    $results[$id] = $total_angka;
}

$conn->close();

// Judul halaman
$title = "Halaman Penilaian";
$year = date("Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <link rel="stylesheet" href="css.css">
    <title><?php echo $title; ?></title>
</head>
<body>
<header>
    <h1><?php echo $title; ?></h1>
    <nav>
        <a href="index.php">Beranda</a>
    </nav>
</header>

<!-- Form untuk pencarian data peserta -->
<form method="GET" action="">
    <input type="text" id="search" name="search" placeholder="Cari data..." value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button class="back-button" type="submit">Cari</button>
</form>

<!-- Tabel untuk menampilkan data peserta dan total angka -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Total Angka</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($participants as $row): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo isset($results[$row['id']]) ? number_format($results[$row['id']], 2) : 'N/A'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
