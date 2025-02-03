<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID dari parameter URL dan pastikan itu adalah angka yang valid
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Menggunakan (int) untuk memastikan ID adalah angka

// Cek apakah ID valid
if ($id == 0) {
    die("ID tidak valid.");
}

// Function untuk mengambil data berdasarkan ID dari tabel yang berbeda
function getData($conn, $table, $id) {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
    if ($stmt === false) {
        die("Query prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id); // "i" berarti integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Pastikan data ditemukan
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Ambil data dari berbagai tabel
$data_kelengkapan_ojt = getData($conn, 'kelengkapan_ojt', $id);
$data_kerangka_laporan = getData($conn, 'kerangka_laporan', $id);
$data_pertanyaan = getData($conn, 'pertanyaan', $id);
$data_presentasi = getData($conn, 'presentasi', $id);
$data_rencana_aksi = getData($conn, 'rencana_aksi', $id);
$data_data_lainnya = getData($conn, 'data_lainnya', $id);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Detail Data</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <script>
        function printPage() {
            var content = document.getElementById('detail-container').innerHTML;  // Ambil konten yang akan dicetak
            var printWindow = window.open('', '', 'height=400,width=600');
            printWindow.document.write('<html><head><title>Print Data</title></head><body>');
            printWindow.document.write(content);  // Tulis konten ke jendela print
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();  // Panggil fungsi print
        }
    </script>
</head>
<body>
    <header>
        <h1>Detail Data</h1>
        <a href="data.php" class="back-button">Kembali</a>
        <!-- Tombol Print -->
        <button class="button" onclick="printPage()">Print</button>
    </header>

    <div class="detail-container" id="detail-container">
        <!-- Kelengkapan OJT -->
        <table>
        <h2>Kelengkapan OJT</h2>
        <?php if ($data_kelengkapan_ojt): ?>
                <tr><th>LPR Buku Harian</th><td><?php echo htmlspecialchars($data_kelengkapan_ojt['Lpr_BukuHarian']); ?></td></tr>
                <tr><th>Dokumentasi</th><td><?php echo htmlspecialchars($data_kelengkapan_ojt['Dokumentasi']); ?></td></tr>
                <tr><th>Nilai 2</th><td><?php echo htmlspecialchars($data_kelengkapan_ojt['Nilai_2']); ?></td></tr>
                <tr><th>Bobot 10</th><td><?php echo htmlspecialchars($data_kelengkapan_ojt['Bobot_10']); ?></td></tr>
            </table>
        <?php else: ?>
            <p>Data tidak ditemukan di tabel Kelengkapan OJT.</p>
        <?php endif; ?>

        <!-- Kerangka Laporan -->
        <h2>Kerangka Laporan</h2>
        <?php if ($data_kerangka_laporan): ?>
            <table>
                <tr><th>LPR Ketentuan</th><td><?php echo htmlspecialchars($data_kerangka_laporan['Lpr_Ketentuan']); ?></td></tr>
                <tr><th>Sistematika</th><td><?php echo htmlspecialchars($data_kerangka_laporan['Sistematika']); ?></td></tr>
                <tr><th>Refleksi</th><td><?php echo htmlspecialchars($data_kerangka_laporan['Refleksi']); ?></td></tr>
                <tr><th>Nilai 1</th><td><?php echo htmlspecialchars($data_kerangka_laporan['Nilai_1']); ?></td></tr>
                <tr><th>Bobot 10</th><td><?php echo htmlspecialchars($data_kerangka_laporan['Bobot_10']); ?></td></tr>
            </table>
        <?php else: ?>
            <p>Data tidak ditemukan di tabel Kerangka Laporan.</p>
        <?php endif; ?>

        <!-- Pertanyaan -->
        <h2>Pertanyaan</h2>
        <?php if ($data_pertanyaan): ?>
            <table>
                <tr><th>Menjawab Jelas</th><td><?php echo htmlspecialchars($data_pertanyaan['Menjawab_Jelas']); ?></td></tr>
                <tr><th>Argumentasi</th><td><?php echo htmlspecialchars($data_pertanyaan['Argumentasi']); ?></td></tr>
                <tr><th>Nilai 5</th><td><?php echo htmlspecialchars($data_pertanyaan['Nilai_5']); ?></td></tr>
                <tr><th>Bobot 20</th><td><?php echo htmlspecialchars($data_pertanyaan['Bobot_20']); ?></td></tr>
            </table>
        <?php else: ?>
            <p>Data tidak ditemukan di tabel Pertanyaan.</p>
        <?php endif; ?>

        <!-- Presentasi -->
        <h2>Presentasi</h2>
        <?php if ($data_presentasi): ?>
            <table>
                <tr><th>Presentasi Jelas</th><td><?php echo htmlspecialchars($data_presentasi['Presentasi_Jelas']); ?></td></tr>
                <tr><th>Mudah Dicerna</th><td><?php echo htmlspecialchars($data_presentasi['Mudah_Dicerna']); ?></td></tr>
                <tr><th>Nilai 4</th><td><?php echo htmlspecialchars($data_presentasi['Nilai_4']); ?></td></tr>
                <tr><th>Bobot 20</th><td><?php echo htmlspecialchars($data_presentasi['Bobot_20']); ?></td></tr>
            </table>
        <?php else: ?>
            <p>Data tidak ditemukan di tabel Presentasi.</p>
        <?php endif; ?>

        <!-- Rencana Aksi -->
        <h2>Rencana Aksi</h2>
        <?php if ($data_rencana_aksi): ?>
            <table>
                <tr><th>LPR Rencana Aksi</th><td><?php echo htmlspecialchars($data_rencana_aksi['Lpr_rencanaAksi']); ?></td></tr>
                <tr><th>LPR Ditargetkan</th><td><?php echo htmlspecialchars($data_rencana_aksi['Lpr_Ditargetkan']); ?></td></tr>
                <tr><th>Output OJT</th><td><?php echo htmlspecialchars($data_rencana_aksi['Output_OJT']); ?></td></tr>
                <tr><th>Output Standar</th><td><?php echo htmlspecialchars($data_rencana_aksi['Output_Standar']); ?></td></tr>
                <tr><th>Nilai 3</th><td><?php echo htmlspecialchars($data_rencana_aksi['Nilai_3']); ?></td></tr>
                <tr><th>Bobot 40</th><td><?php echo htmlspecialchars($data_rencana_aksi['Bobot_40']); ?></td></tr>
            </table>
        <?php else: ?>
            <p>Data tidak ditemukan di tabel Rencana Aksi.</p>
        <?php endif; ?>

        <!-- Data Lainnya -->
        <h2>Data Lainnya</h2>
        <?php if ($data_data_lainnya): ?>
            <table>
                <tr><th>NSebelum Perbaikan</th><td><?php echo htmlspecialchars($data_data_lainnya['Nsebelum_Perbaikan']); ?></td></tr>
                <tr><th>Perubahan Topik</th><td><?php echo htmlspecialchars($data_data_lainnya['Perubahan_topik']); ?></td></tr>
                <tr><th>Catatan Penguji</th><td><?php echo htmlspecialchars($data_data_lainnya['Catatan_Penguji']); ?></td></tr>
                <tr><th>TT Laporan OJT</th><td><?php echo htmlspecialchars($data_data_lainnya['TT_laporan_OJT']); ?></td></tr>
                <tr><th>NS Setelah Perbaikan</th><td><?php echo htmlspecialchars($data_data_lainnya['Nsetelah_Perbaikan']); ?></td></tr>
            </table>
        <?php else: ?>
            <p>Data tidak ditemukan di tabel Data Lainnya.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
$conn->close();
?>
