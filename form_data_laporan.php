<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

// Cek apakah form telah disubmit untuk update status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];  // ID Peserta
    $form_data_laporan = $_POST['Nama, Angkatan, Telepon, Alamat, Lpr_Ketentuan (lanjutkan)'];  // Status Penilaian (1 = selesai, 0 = belum)

    // Query untuk memperbarui status penilaian
    $sql = "UPDATE data_utama SET penilaian_selesai = ? WHERE id = ?";
    $sql = "UPDATE kerangka_laporan SET penilaian_selesai = ? WHERE id = ?";
    $sql = "UPDATE Lpr_rencanaAksi SET penilaian_selesai = ? WHERE id = ?";
    // Menyiapkan statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $penilaian_selesai, $id);
    
    if ($stmt->execute()) {
        // Tampilkan SweetAlert2 success alert dan refresh halaman agar data terbaru terlihat
        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Status penilaian peserta berhasil diperbarui.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = window.location.href;
                });
              </script>";
    } else {
        // Tampilkan SweetAlert2 error alert dan refresh halaman
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan: " . addslashes($stmt->error) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = window.location.href;
                });
              </script>";
    }

    $stmt->close();
    // Pastikan untuk menghentikan eksekusi selanjutnya agar tidak menjalankan kode pencarian dua kali
    exit();
}

// Simpan data ke tabel `data_utama`
if (isset($_POST['Nama'], $_POST['Angkatan'], $_POST['Telepon'], $_POST['Alamat'])) {
    $query = "INSERT INTO data_utama (nama, angkatan, telepon, alamat) VALUES (?, ?, ?, ?)";
    simpanData($conn, $query, [$_POST['Nama'], $_POST['Angkatan'], $_POST['Telepon'], $_POST['Alamat']], "ssss");
}

// Simpan data ke tabel `kerangka_laporan`
if (isset($_POST['Lpr_Ketentuan'], $_POST['Sistematika'], $_POST['Refleksi'], $_POST['Nilai_1'], $_POST['Bobot_10'])) {
    $query = "INSERT INTO kerangka_laporan (Lpr_Ketentuan, Sistematika, Refleksi, Nilai_1, Bobot_10) VALUES (?, ?, ?, ?, ?)";
    simpanData($conn, $query, [$_POST['Lpr_Ketentuan'], $_POST['Sistematika'], $_POST['Refleksi'], $_POST['Nilai_1'], $_POST['Bobot_10']], "sssss");
}

// Simpan data ke tabel `buku_harian`
if (isset($_POST['Lpr_BukuHarian'], $_POST['Dokumentasi'], $_POST['Nilai_2'], $_POST['Bobot_10_buku'])) {
    $query = "INSERT INTO buku_harian (Lpr_BukuHarian, Dokumentasi, Nilai_2, Bobot_10) VALUES (?, ?, ?, ?)";
    simpanData($conn, $query, [$_POST['Lpr_BukuHarian'], $_POST['Dokumentasi'], $_POST['Nilai_2'], $_POST['Bobot_10_buku']], "ssss");
}

// Simpan data ke tabel `rencana_aksi`
if (isset($_POST['Lpr_rencanaAksi'], $_POST['Lpr_Ditargetkan'], $_POST['Output_OJT'], $_POST['Output_Standar'], $_POST['Nilai_3'], $_POST['Bobot_40'])) {
    $query = "INSERT INTO rencana_aksi (Lpr_rencanaAksi, Lpr_Ditargetkan, Output_OJT, Output_Standar, Nilai_3, Bobot_40) VALUES (?, ?, ?, ?, ?, ?)";
    simpanData($conn, $query, [
        $_POST['Lpr_rencanaAksi'], 
        $_POST['Lpr_Ditargetkan'], 
        $_POST['Output_OJT'], 
        $_POST['Output_Standar'], 
        $_POST['Nilai_3'], 
        $_POST['Bobot_40']
    ], "ssssss");
}

// Simpan data ke tabel `presentasi`
if (isset($_POST['Presentasi_Jelas'], $_POST['Mudah_Dicerna'], $_POST['Nilai_4'], $_POST['Bobot_20'])) {
    $query = "INSERT INTO presentasi (Presentasi_Jelas, Mudah_Dicerna, Nilai_4, Bobot_20) VALUES (?, ?, ?, ?)";
    simpanData($conn, $query, [$_POST['Presentasi_Jelas'], $_POST['Mudah_Dicerna'], $_POST['Nilai_4'], $_POST['Bobot_20']], "ssss");
}

// Simpan data ke tabel `pertanyaan`
if (isset($_POST['Menjawab_Jelas'], $_POST['Argumentasi'], $_POST['Nilai_5'], $_POST['Bobot_20_Pertanyaan'])) {
    $query = "INSERT INTO pertanyaan (Menjawab_Jelas, Argumentasi, Nilai_5, Bobot_20) VALUES (?, ?, ?, ?)";
    simpanData($conn, $query, [$_POST['Menjawab_Jelas'], $_POST['Argumentasi'], $_POST['Nilai_5'], $_POST['Bobot_20_Pertanyaan']], "ssss");
}

// Simpan data ke tabel `data_lainnya`
if (isset($_POST['Nsebelum_Perbaikan'], $_POST['Perubahan_topik'], $_POST['Catatan_Penguji'], $_POST['TT_laporan_OJT'], $_POST['Nsetelah_Perbaikan'])) {
    $query = "INSERT INTO data_lainnya (Nsebelum_Perbaikan, Perubahan_topik, Catatan_Penguji, TT_laporan_OJT, Nsetelah_Perbaikan) VALUES (?, ?, ?, ?, ?)";
    simpanData($conn, $query, [$_POST['Nsebelum_Perbaikan'], $_POST['Perubahan_topik'], $_POST['Catatan_Penguji'], $_POST['TT_laporan_OJT'], $_POST['Nsetelah_Perbaikan']], "sssss");
}

// Menutup koneksi
$conn->close();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Tambah Data</title>
    <link rel="stylesheet" href="css.css">
    <link rel="stylesheet" href="data.css">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
        <h1>Laporan Data</h1>
            <nav>
            <a href="index.php">Dashboard</a>
            </nav>
    </header>
    <div class="card-container">
    <div class="card">
        <h2>Masukkan Semua Data</h2>
        <form action="process_all_data.php" method="POST">
    <p class="mini-navbar">Data Pribadi</p>

    <label for="Nama">Nama:</label>
    <input type="text" id="Nama" name="Nama" required>
    <br>
    <label for="Angkatan">Angkatan:</label>
    <input type="text" id="Angkatan" name="Angkatan" required>
    <br>
    <label for="Telepon">No.Telepon:</label>
    <input type="text" id="Telepon" name="Telepon">
    <br>
    <label for="Alamat">Alamat:</label>
    <textarea id="Alamat" name="Alamat"></textarea>
    <br>    
    <label for="tanggal_terdaftar">Tanggal Terdaftar:</label>
    <input type="datetime-local" name="tanggal_terdaftar" id="tanngal_terdaftar" required><br>
    <!-- Kerangka Laporan -->
    <p class="mini-navbar">Kerangka Laporan</p>
    <label for="Lpr_Ketentuan">Laporan Ketentuan:</label>
    <input type="text" id="Lpr_Ketentuan" name="Lpr_Ketentuan" required>
    <br>
    <label for="Sistematika">Sistematika:</label>
    <input type="text" id="Sistematika" name="Sistematika" required>
    <br>
    <label for="Refleksi">Refleksi:</label>
    <input type="text" id="Refleksi" name="Refleksi" required>
    <br>
    <label for="Nilai_1">Nilai 1:</label>
    <input type="text" id="Nilai_1" name="Nilai_1" required>
    <br>
    <label for="Bobot_10">Bobot 10%:</label>
    <input type="text" id="Bobot_10" name="Bobot_10" required>
    <br>

    <!-- Buku Harian -->
    <p class="mini-navbar">Kelengkapan OJT</p>
    <label for="Lpr_BukuHarian">Laporan Berdasarkan Buku Harian:</label>
    <input type="text" id="Lpr_BukuHarian" name="Lpr_BukuHarian" required>
    <br>
    <label for="Dokumentasi">Dokumentasi:</label>
    <input type="text" id="Dokumentasi" name="Dokumentasi" required>
    <br>
    <label for="Nilai_2">Nilai 2:</label>
    <input type="text" id="Nilai_2" name="Nilai_2" required>
    <br>
    <label for="Bobot_10_buku">Bobot 10%:</label>
    <input type="text" id="Bobot_10_buku" name="Bobot_10_buku" required>
    <br>

    <!-- Rencana Aksi -->
    <p class="mini-navbar">Rencana Aksi</p>
    <label for="Lpr_rencanaAksi">Laporan Rencana Aksi:</label>
    <input type="text" id="Lpr_rencanaAksi" name="Lpr_rencanaAksi" required>
    <br>
    <label for="Lpr_Ditargetkan">Laporan Ditargetkan:</label>
    <input type="text" id="Lpr_Ditargetkan" name="Lpr_Ditargetkan" required>
    <br>
    <label for="Output_OJT">Output OJT:</label>
    <input type="text" id="Output_OJT" name="Output_OJT" required>
    <br>
    <label for="Output_Standar">Output Standar:</label>
    <input type="text" id="Output_Standar" name="Output_Standar" required>
    <br>
    <label for="Nilai_3">Nilai 3:</label>
    <input type="text" id="Nilai_3" name="Nilai_3" required>
    <br>
    <label for="Bobot_40">Bobot 40%:</label>
    <input type="text" id="Bobot_40" name="Bobot_40" required>
    <br>

    <!-- Presentasi -->
    <p class="mini-navbar">Presentasi</p>
    <label for="Presentasi_Jelas">Presentasi Jelas:</label>
    <input type="text" id="Presentasi_Jelas" name="Presentasi_Jelas" required>
    <br>
    <label for="Mudah_Dicerna">Materi Mudah Dipahami:</label>
    <input type="text" id="Mudah_Dicerna" name="Mudah_Dicerna" required>
    <br>
    <label for="Nilai_4">Nilai 4:</label>
    <input type="text" id="Nilai_4" name="Nilai_4" required>
    <br>
    <label for="Bobot_20_presentasi">Bobot 20%:</label>
    <input type="text" id="Bobot_20_presentasi" name="Bobot_20_presentasi" required>
    <br>

    <!-- Pertanyaan -->
    <p class="mini-navbar">Pertanyaan</p>
    <label for="Menjawab_Jelas">Menjawab Dengan Jelas:</label>
    <input type="text" id="Menjawab_Jelas" name="Menjawab_Jelas" required><br>
    <br>
    <label for="Argumentasi">Argumentasi:</label>
    <input type="text" id="Argumentasi" name="Argumentasi" required><br>
    <br>
    <label for="Nilai_5">Nilai 5:</label>
    <input type="text" id="Nilai_5" name="Nilai_5" required><br>
    <br>
    <label for="Bobot_20">Bobot 20%:</label>
    <input type="text" id="Bobot_20" name="Bobot_20" required><br>
    <br>
    <!-- Data Lainnya -->
    <p class="mini-navbar">Data Lainnya</p>
    <label for="Nsebelum_Perbaikan">Nilai Sebelum Perbaikan:</label>
    <input type="text" name="Nsebelum_Perbaikan" id="Nsebelum_Perbaikan" required><br>
    <br>
    <label for="Perubahan_topik">Perubahan Topik:</label>
    <input type="text" name="Perubahan_topik" id="Perubahan_topik" required><br>
    <br>
    <label for="Catatan_Penguji">Catatan Penguji:</label>
    <input type="text" name="Catatan_Penguji" id="Catatan_Penguji" required><br>
    <br>
    <label for="Laporan_OJT">Laporan OJT:</label>
    <input type="datetime-local" name="TT_laporan_OJT" id="TT_laporan_OJT" required><br>
    <br>
    <label for="Nsetelah_Perbaikan">Nilai Setelah Perbaikan:</label>
    <input type="text" name="Nsetelah_Perbaikan" id="Nsetelah_Perbaikan" required><br>
    <br>
   
    <button type="submit" id="submitButton">Kirim Data</button>
    </form>
</div>

<div id="notifikasi" class="notification">Data berhasil disimpan!</div>

<script>
    // Menangani form submit dengan AJAX
    document.getElementById('dataForm').addEventListener('submit', function (e) {
        e.preventDefault();  // Mencegah form submit secara default

        fetch('process_all_data.php', {
            method: 'POST',
            body: new FormData(this)
        })
        .then(response => response.text())
        .then(data => {
            Swal.fire({
                title: 'Success!',
                text: "Data berhasil disimpan!",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: "Terjadi kesalahan saat menyimpan data.",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>

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
                <li><a href="tentang.php">Tentang</a></li>
                <li><a href="index.php">Beranda</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>