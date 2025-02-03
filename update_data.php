<?php
// Proses update data
if (isset($_POST['update'])) {
    $id = $_POST['edit-id'];
    $nama = $_POST['edit-nama'];
    $angkatan = $_POST['edit-angkatan'];
    $telepon = $_POST['edit-telepon'];
    $alamat = $_POST['edit-alamat'];

    // Query untuk update data
    $sql = "UPDATE data_utama SET nama = ?, angkatan = ?, telepon = ?, alamat = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sisss", $nama, $angkatan, $telepon, $alamat, $id);
        if ($stmt->execute()) {
            echo "<script>alert('Data berhasil diupdate');</script>";
            echo "<script>window.location.href = 'data.php';</script>"; // Redirect ke halaman data setelah update
        } else {
            echo "<script>alert('Gagal mengupdate data');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error dalam persiapan statement');</script>";
    }
}
?>