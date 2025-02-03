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
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Mengambil ID dari formulir
    $id = $_POST['id'];

    // Query untuk menghapus data dari berbagai tabel
    $sql1 = "DELETE FROM data_utama WHERE id = ?";
    $sql2 = "DELETE FROM kelengkapan_ojt WHERE id = ?";
    $sql3 = "DELETE FROM kerangka_laporan WHERE id = ?";
    $sql3 = "DELETE FROM pertanyaan WHERE id = ?";
    $sql3 = "DELETE FROM presentasi WHERE id = ?";
    $sql3 = "DELETE FROM rencana_aksi WHERE id = ?";
    $sql3 = "DELETE FROM data_lainnya WHERE id = ?";
    // Menyiapkan dan mengeksekusi query
    if ($stmt1 = $conn->prepare($sql1)) {
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
    }

    if ($stmt2 = $conn->prepare($sql2)) {
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
    }

    if ($stmt3 = $conn->prepare($sql3)) {
        $stmt3->bind_param("i", $id);
        $stmt3->execute();
    }

    // Menutup statement
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();

    // Menutup koneksi
    $conn->close();

    // Memberi tahu pengguna bahwa data telah dihapus
}
?>
