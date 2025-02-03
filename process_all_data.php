<?php
session_start();  // Start session for user data
include('koneksi.php');

// Fungsi untuk menyimpan data ke dalam tabel
function simpanData($conn, $query, $params, $types) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query error: " . $conn->error);
    }
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Data berhasil disimpan!';
        header("Location: your-page.php");
        exit();
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
    $stmt->close();
}

// Ambil data dari session
$nama = isset($_SESSION['Nama']) ? $_SESSION['Nama'] : null;
$angkatan = isset($_SESSION['Angkatan']) ? $_SESSION['Angkatan'] : null;
$telepon = isset($_SESSION['Telepon']) ? $_SESSION['Telepon'] : null;
$alamat = isset($_SESSION['Alamat']) ? $_SESSION['Alamat'] : null;

// Function to handle data insertion
function insertData($conn, $query, $params, $types) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return $stmt->error;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect POST data and set defaults if empty
    $data = [
        'Nama' => $_POST['Nama'] ?? null,
        'Angkatan' => $_POST['Angkatan'] ?? null,
        'Telepon' => $_POST['Telepon'] ?? null,
        'Alamat' => $_POST['Alamat'] ?? null,
        'Lpr_Ketentuan' => $_POST['Lpr_Ketentuan'] ?? null,
        'Sistematika' => $_POST['Sistematika'] ?? null,
        'Refleksi' => $_POST['Refleksi'] ?? null,
        'Nilai_1' => $_POST['Nilai_1'] ?? null,
        'Bobot_10' => $_POST['Bobot_10'] ?? null,
        'Lpr_BukuHarian' => $_POST['Lpr_BukuHarian'] ?? null,
        'Dokumentasi' => $_POST['Dokumentasi'] ?? null,
        'Nilai_2' => $_POST['Nilai_2'] ?? null,
        'Menjawab_Jelas' => $_POST['Menjawab_Jelas'] ?? null,
        'Argumentasi' => $_POST['Argumentasi'] ?? null,
        'Nilai_5' => $_POST['Nilai_5'] ?? null,
        'Bobot_20' => $_POST['Bobot_20'] ?? null,
        'Presentasi_Jelas' => $_POST['Presentasi_Jelas'] ?? null,
        'Mudah_Dicerna' => $_POST['Mudah_Dicerna'] ?? null,
        'Nilai_4' => $_POST['Nilai_4'] ?? null,
        'Bobot_20_presentasi' => $_POST['Bobot_20_presentasi'] ?? null,
        'Lpr_rencanaAksi' => $_POST['Lpr_rencanaAksi'] ?? null,
        'Lpr_Ditargetkan' => $_POST['Lpr_Ditargetkan'] ?? null,
        'Output_OJT' => $_POST['Output_OJT'] ?? null,
        'Output_Standar' => $_POST['Output_Standar'] ?? null,
        'Nilai_3' => $_POST['Nilai_3'] ?? null,
        'Bobot_40' => $_POST['Bobot_40'] ?? null,
        'Nsebelum_Perbaikan' => $_POST['Nsebelum_Perbaikan'] ?? null,
        'Perubahan_topik' => $_POST['Perubahan_topik'] ?? null,
        'Catatan_Penguji' => $_POST['Catatan_Penguji'] ?? null,
        'Nsetelah_Perbaikan' => $_POST['Nsetelah_Perbaikan'] ?? null
    ]; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Show all POST data
    var_dump($_POST);

    // Collect POST data and set defaults if empty
    $Nama = $_POST['Nama'] ?? null;
    $Angkatan = $_POST['Angkatan'] ?? null;
    $Telepon = $_POST['Telepon'] ?? null;
    $Alamat = $_POST['Alamat'] ?? null;
    $Lpr_Ketentuan = $_POST['Lpr_Ketentuan'] ?? null;
    $Sistematika = $_POST['Sistematika'] ?? null;
    $Refleksi = $_POST['Refleksi'] ?? null;
    $Nilai_1 = $_POST['Nilai_1'] ?? null;
    $Bobot_10 = $_POST['Bobot_10'] ?? null;
    $Lpr_BukuHarian = $_POST['Lpr_BukuHarian'] ?? null;
    $Dokumentasi = $_POST['Dokumentasi'] ?? null;
    $Nilai_2 = $_POST['Nilai_2'] ?? null;
    $Menjawab_Jelas = $_POST['Menjawab_Jelas'] ?? null;
    $Argumentasi = $_POST['Argumentasi'] ?? null;
    $Nilai_5 = $_POST['Nilai_5'] ?? null;
    $Bobot_20 = $_POST['Bobot_20'] ?? null;
    $Presentasi_Jelas = $_POST['Presentasi_Jelas'] ?? null;
    $Mudah_Dicerna = $_POST['Mudah_Dicerna'] ?? null;
    $Nilai_4 = $_POST['Nilai_4'] ?? null;
    $Bobot_20_presentasi = $_POST['Bobot_20_presentasi'] ?? null;
    $Lpr_rencanaAksi = $_POST['Lpr_rencanaAksi'] ?? null;
    $Lpr_Ditargetkan = $_POST['Lpr_Ditargetkan'] ?? null;
    $Output_OJT = $_POST['Output_OJT'] ?? null;
    $Output_Standar = $_POST['Output_Standar'] ?? null;
    $Nilai_3 = $_POST['Nilai_3'] ?? null;
    $Bobot_40 = $_POST['Bobot_40'] ?? null;
    $Nsebelum_Perbaikan = $_POST['Nsebelum_Perbaikan'] ?? null;
    $Perubahan_topik = $_POST['Perubahan_topik'] ?? null;
    $Catatan_Penguji = $_POST['Catatan_Penguji'] ?? null;
    $Nsetelah_Perbaikan = $_POST['Nsetelah_Perbaikan'] ?? null;

    // Validate required fields and insert data into corresponding tables
    if ($Nama && $Angkatan && $Telepon && $Alamat) {
        $query = "INSERT INTO data_utama (nama, angkatan, telepon, alamat) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $Nama, $Angkatan, $Telepon, $Alamat);
        if ($stmt->execute()) {
            echo "Data berhasil disimpan ke tabel data_utama.<br>";
        } else {
            echo "Error: " . $stmt->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel data_utama belum lengkap.<br>";
    }

    // Insert data into kerangka_laporan table
    if ($Lpr_Ketentuan && $Sistematika && $Refleksi && $Nilai_1 && $Bobot_10) {
        $query_kerangka = "INSERT INTO kerangka_laporan (Lpr_Ketentuan, Sistematika, Refleksi, Nilai_1, Bobot_10) VALUES (?, ?, ?, ?, ?)";
        $stmt_kerangka = $conn->prepare($query_kerangka);
        $stmt_kerangka->bind_param("sssss", $Lpr_Ketentuan, $Sistematika, $Refleksi, $Nilai_1, $Bobot_10);
        if ($stmt_kerangka->execute()) {
            echo "Data berhasil disimpan ke tabel kerangka_laporan.<br>";
        } else {
            echo "Error: " . $stmt_kerangka->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel kerangka_laporan belum lengkap.<br>";
    }

    // Insert data into kelengkapan_ojt table
    if ($Lpr_BukuHarian && $Dokumentasi && $Nilai_2 && $Bobot_10) {
        $query_kelengkapan = "INSERT INTO kelengkapan_ojt (Lpr_BukuHarian, Dokumentasi, Nilai_2, Bobot_10) VALUES (?, ?, ?, ?)";
        $stmt_kelengkapan = $conn->prepare($query_kelengkapan);
        $stmt_kelengkapan->bind_param("ssss", $Lpr_BukuHarian, $Dokumentasi, $Nilai_2, $Bobot_10);
        if ($stmt_kelengkapan->execute()) {
            echo "Data berhasil disimpan ke tabel kelengkapan_ojt.<br>";
        } else {
            echo "Error: " . $stmt_kelengkapan->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel kelengkapan_ojt belum lengkap.<br>";
    }

    // Insert data into pertanyaan table
    if ($Menjawab_Jelas && $Argumentasi && $Nilai_5 && $Bobot_20) {
        $query_pertanyaan = "INSERT INTO pertanyaan (Menjawab_Jelas, Argumentasi, Nilai_5, Bobot_20) VALUES (?, ?, ?, ?)";
        $stmt_pertanyaan = $conn->prepare($query_pertanyaan);
        $stmt_pertanyaan->bind_param("ssss", $Menjawab_Jelas, $Argumentasi, $Nilai_5, $Bobot_20);
        if ($stmt_pertanyaan->execute()) {
            echo "Data berhasil disimpan ke tabel pertanyaan.<br>";
        } else {
            echo "Error: " . $stmt_pertanyaan->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel pertanyaan belum lengkap.<br>";
    }

    // Insert data into presentasi table
    if ($Presentasi_Jelas && $Mudah_Dicerna && $Nilai_4 && $Bobot_20_presentasi) {
        $query_presentasi = "INSERT INTO presentasi (Presentasi_Jelas, Mudah_Dicerna, Nilai_4, Bobot_20) VALUES (?, ?, ?, ?)";
        $stmt_presentasi = $conn->prepare($query_presentasi);
        $stmt_presentasi->bind_param("ssss", $Presentasi_Jelas, $Mudah_Dicerna, $Nilai_4, $Bobot_20_presentasi);
        if ($stmt_presentasi->execute()) {
            echo "Data berhasil disimpan ke tabel presentasi.<br>";
        } else {
            echo "Error: " . $stmt_presentasi->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel presentasi belum lengkap.<br>";
    }

    // Insert data into rencana_aksi table
    if ($Lpr_rencanaAksi && $Lpr_Ditargetkan && $Output_OJT && $Output_Standar && $Nilai_3 && $Bobot_40) {
        $query_rencana_aksi = "INSERT INTO rencana_aksi (Lpr_rencanaAksi, Lpr_Ditargetkan, Output_OJT, Output_Standar, Nilai_3, Bobot_40) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_rencana_aksi = $conn->prepare($query_rencana_aksi);
        $stmt_rencana_aksi->bind_param("ssssss", $Lpr_rencanaAksi, $Lpr_Ditargetkan, $Output_OJT, $Output_Standar, $Nilai_3, $Bobot_40);
        if ($stmt_rencana_aksi->execute()) {
            echo "Data berhasil disimpan ke tabel rencana_aksi.<br>";
        } else {
            echo "Error: " . $stmt_rencana_aksi->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel rencana_aksi belum lengkap.<br>";
    }

    // Insert data into data_lainnya table
    if ($Nsebelum_Perbaikan && $Perubahan_topik && $Catatan_Penguji && $Nsetelah_Perbaikan) {
        $query_data_lainnya = "INSERT INTO data_lainnya (Nsebelum_Perbaikan, Perubahan_topik, Catatan_Penguji, TT_laporan_OJT, Nsetelah_Perbaikan) 
                               VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)";
        $stmt_data_lainnya = $conn->prepare($query_data_lainnya);
        $stmt_data_lainnya->bind_param("ssss", $Nsebelum_Perbaikan, $Perubahan_topik, $Catatan_Penguji, $Nsetelah_Perbaikan);
        if ($stmt_data_lainnya->execute()) {
            echo "Data berhasil disimpan ke tabel data_lainnya.<br>";
        } else {
            echo "Error: " . $stmt_data_lainnya->error . "<br>";
        }
    } else {
        echo "Error: Data untuk tabel data_lainnya belum lengkap.<br>";
    }
}
header("Location: form_data_laporan.php");
    exit(); // Ensure the script stops after redirect
}
// Close the connection
$conn->close();
?>
