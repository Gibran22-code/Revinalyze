<?php
session_start();
include('koneksi.php');

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Proses update data
if (isset($_POST['update'])) {
    // Mengambil data dari form edit
    $id = $_POST['edit-id'];
    $nama = $_POST['edit-nama'];
    $angkatan = $_POST['edit-angkatan'];
    $telepon = $_POST['edit-telepon'];
    $alamat = $_POST['edit-alamat'];

    // Query update untuk tabel data_utama
    $sqlUpdate = "UPDATE data_utama SET nama = ?, angkatan = ?, telepon = ?, alamat = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sqlUpdate)) {
        $stmt->bind_param("ssssi", $nama, $angkatan, $telepon, $alamat, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Proses hapus data (jika ada permintaan hapus)
if (isset($_POST['submit'])) {
    $id = $_POST['id'];

    // Array query hapus data dari berbagai tabel
    $deleteQueries = [
        "DELETE FROM data_utama WHERE id = ?",
        "DELETE FROM kelengkapan_ojt WHERE id = ?",
        "DELETE FROM kerangka_laporan WHERE id = ?",
        "DELETE FROM pertanyaan WHERE id = ?",
        "DELETE FROM presentasi WHERE id = ?",
        "DELETE FROM rencana_aksi WHERE id = ?",
        "DELETE FROM data_lainnya WHERE id = ?"
    ];

    foreach ($deleteQueries as $query) {
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Mengecek koneksi
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil data dari tabel data_utama dengan opsi pencarian
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT id, nama, angkatan, telepon, alamat, tanggal_terdaftar FROM data_utama";
if ($searchTerm) {
    $escapedTerm = $conn->real_escape_string($searchTerm);
    $sql .= " WHERE nama LIKE '%$escapedTerm%' OR telepon LIKE '%$escapedTerm%' OR alamat LIKE '%$escapedTerm%'";
}

// Eksekusi query pencarian
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <link rel="stylesheet" href="css.css">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fungsi untuk mencetak baris data tertentu
        function printRow(id) {
            var row = document.getElementById('row-' + id).outerHTML;
            var printWindow = window.open('', '', 'height=400,width=600');
            printWindow.document.write('<html><head><title>Print Data</title></head><body>');
            printWindow.document.write('<h1>Data untuk ID: ' + id + '</h1>');
            printWindow.document.write(row);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        // Menampilkan form edit dengan mengisi data yang akan diubah
        function showEditCard(id, nama, angkatan, telepon, alamat) {
            document.getElementById('edit-card').style.display = 'block';
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-angkatan').value = angkatan;
            document.getElementById('edit-telepon').value = telepon;
            document.getElementById('edit-alamat').value = alamat;
        }

        // Menutup form edit
        function closeEditCard() {
            document.getElementById('edit-card').style.display = 'none';
        }
    </script>
</head>
<body>
    <header>
        <h1>Tabel Data Penilaian</h1>
        <nav>
            <a href="index.php">Dashboard</a>
            <!-- Form Pencarian -->
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" id="search" name="search" placeholder="Cari data..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button class="back-button" type="submit">Cari</button>
                </form>
            </div>
        </nav>
    </header>

    <!-- Form Hapus Data -->
    <form action="" method="POST">
        <label for="id">Masukkan ID untuk dihapus:</label>
        <input type="text" name="id" id="id" required>
        <button type="submit" name="submit">Hapus Data</button>
    </form>

    <!-- Tabel Data -->
    <div class="table-container">
        <h2>Data Utama</h2>
        <table class="data-table" id="data-table">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Angkatan</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Tanggal Terdaftar</th>
                <th>Tombol</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='row-" . $row["id"] . "'>";
                    echo "<td>" . (isset($row["id"]) ? $row["id"] : 'Tidak ada data') . "</td>";
                    echo "<td>" . (isset($row["nama"]) ? $row["nama"] : 'Tidak ada data') . "</td>";
                    echo "<td>" . (isset($row["angkatan"]) ? $row["angkatan"] : 'Tidak ada data') . "</td>";
                    echo "<td>" . (isset($row["telepon"]) ? $row["telepon"] : 'Tidak ada data') . "</td>";
                    echo "<td>" . (isset($row["alamat"]) ? $row["alamat"] : 'Tidak ada data') . "</td>";
                    echo "<td>" . (isset($row["tanggal_terdaftar"]) ? $row["tanggal_terdaftar"] : 'Tidak ada data') . "</td>";
                    echo "<td>";
                    echo "<a href='#' onclick=\"showEditCard('" . $row["id"] . "', '" . addslashes($row["nama"]) . "', '" . addslashes($row["angkatan"]) . "', '" . addslashes($row["telepon"]) . "', '" . addslashes($row["alamat"]) . "')\"
                            style=\"
                                display: inline-block;
                                background-color: #28a745;
                                color: #fff;
                                padding: 6px 12px;
                                border-radius: 4px;
                                text-decoration: none;
                                margin-right: 5px;
                                transition: background-color 0.3s ease;
                            \" onmouseover=\"this.style.backgroundColor='#218838'\" onmouseout=\"this.style.backgroundColor='#28a745'\">Edit</a>";

                    echo "<a href='detail.php?id=" . $row["id"] . "'
                            style=\"
                                display: inline-block;
                                background-color: #007bff;
                                color: #fff;
                                padding: 6px 12px;
                                border-radius: 4px;
                                text-decoration: none;
                                margin-right: 5px;
                                transition: background-color 0.3s ease;
                            \" onmouseover=\"this.style.backgroundColor='#0069d9'\" onmouseout=\"this.style.backgroundColor='#007bff'\">Detail</a>";

                    echo "<a href='#' onclick='printRow(" . $row["id"] . ")'
                            style=\"
                                display: inline-block;
                                background-color: #ffc107;
                                color: #212529;
                                padding: 6px 12px;
                                border-radius: 4px;
                                text-decoration: none;
                                transition: background-color 0.3s ease;
                            \" onmouseover=\"this.style.backgroundColor='#e0a800'\" onmouseout=\"this.style.backgroundColor='#ffc107'\">Print</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada data</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Form Edit Data -->
    <div id="edit-card" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; border: 1px solid #ccc; border-radius: 5px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 1000; width: 320px;">
        <h2 style="margin-top: 0; text-align: center; color: black;">Edit Data</h2>
        <form method="POST" action="">
            <input type="hidden" name="edit-id" id="edit-id">
            
            <div style="margin-bottom: 15px;">
                <label for="edit-nama" style="display: block; margin-bottom: 5px;">Nama:</label>
                <input type="text" name="edit-nama" id="edit-nama" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="edit-angkatan" style="display: block; margin-bottom: 5px;">Angkatan:</label>
                <input type="text" name="edit-angkatan" id="edit-angkatan" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label for="edit-telepon" style="display: block; margin-bottom: 5px;">Telepon:</label>
                <input type="text" name="edit-telepon" id="edit-telepon" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">
            </div>
            
            <div style="margin-bottom: 20px;">
                <label for="edit-alamat" style="display: block; margin-bottom: 5px;">Alamat:</label>
                <input type="text" name="edit-alamat" id="edit-alamat" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 3px;">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" name="update" style="flex: 1; background-color: #28a745; color: #fff; border: none; padding: 10px; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="closeEditCard()" style="flex: 1; background-color: #dc3545; color: #fff; border: none; padding: 10px; border-radius: 4px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#c82333'" onmouseout="this.style.backgroundColor='#dc3545'">
                    Batal
                </button>
            </div>
        </form>
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
</body>
</html>

<?php
$conn->close();
?>
