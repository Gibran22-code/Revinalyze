<?php
include('koneksi.php');

// Fungsi untuk mengambil daftar ID dan nama peserta dengan nilai > 70 per field
function getAbove70Participants($conn, $table, $field) {
    $sql = "SELECT $table.id, data_utama.nama FROM $table 
            INNER JOIN data_utama ON $table.id = data_utama.id 
            WHERE $table.$field > 70";
    $result = $conn->query($sql);
    $participants = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $participants[] = $row;
        }
    }
    return $participants;
}

// Array untuk menyimpan tabel dan kolom
$fields = [
    'kelengkapan_ojt' => ['Lpr_BukuHarian', 'Dokumentasi', 'Nilai_2', 'Bobot_10'],
    'kerangka_laporan' => ['Lpr_Ketentuan', 'Sistematika', 'Refleksi', 'Nilai_1', 'Bobot_10'],
    'pertanyaan' => ['Menjawab_Jelas', 'Argumentasi', 'Nilai_5', 'Bobot_20'],
    'presentasi' => ['Presentasi_Jelas', 'Mudah_Dicerna', 'Nilai_4', 'Bobot_20'],
    'rencana_aksi' => ['Lpr_rencanaAksi', 'Lpr_Ditargetkan', 'Output_OJT', 'Output_Standar', 'Nilai_3', 'Bobot_40'],
    'data_lainnya' => ['Nsebelum_Perbaikan', 'Nsetelah_Perbaikan']
];

$participantsData = [];

// Mengambil data peserta
foreach ($fields as $table => $columns) {
    foreach ($columns as $field) {
        $participantsData[$table][$field] = getAbove70Participants($conn, $table, $field);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" href="Pictures/RV1.jpg" type="image/jpeg">
    <title>Daftar Peserta Nilai > 70</title>
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
        h2, h3 {
            color: rgba(255, 255, 255, 0.88);
        }
        table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        }

        th {
        background-color: #1b98e0;
        color: white;
        }

        tr:hover {
        background-color: rgba(27, 152, 224, 0.3);
        }
        .hidden {
            display: none;
        }
        button {
            margin: 5px 0;
            padding: 5px 10px;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
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

    .container {
            padding: 20px;
        }
        header a { color: rgba(255, 255, 255, 0.88); text-decoration: none; margin: 0px; font-size: 1.1rem; text-transform: uppercase; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s ease, transform 0.3s ease; } 
        header a:hover { background-color: rgb(90, 211, 86); transform: scale(0.9); } 
        header h1 { color: rgba(255, 255, 255, 0.88);;font-size: 1.2rem; margin:0px;} 
        nav { height:50px;   background-color: rgb(36, 87, 104); color: rgba(255, 255, 255, 0.88); text-decoration: none; margin: 0px; font-size: 1.1rem; text-transform: uppercase; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s ease, transform 0.3s ease; } 
        footer {
        background-color: rgba(0, 0, 0, 0.86);
        color: white;
        padding: 20px 10px;
        text-align: center;
        animation: fadeIn 2s ease-in-out;
    }

    footer .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        flex-wrap: wrap;
        font-family: 'Poppins', sans-serif; /* Menggunakan font Poppins untuk footer */
    }

    footer .social-icons a {
        color: #fff;
        margin: 0 15px;
        font-size: 1.5rem;
        transition: color 0.3s ease;
    }

    footer .social-icons a:hover {
        color: #5cb85c;
    }
    
    
    </style>
</head>
<body>
    <header>
        <h1>Daftar Peserta dengan Nilai > 70</h1>
        <a href="index.php">Dashboard</a>
    </header>

    <div class="container">

        <?php foreach ($participantsData as $table => $columns): ?>
            <div id="<?php echo htmlspecialchars($table); ?>">
             <nav><h3><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($table))); ?></h3></nav>
                <?php foreach ($columns as $field => $participants): ?>
                    <?php if (!empty($participants)): ?>
                        <h4><?php echo htmlspecialchars($field); ?>: <?php echo count($participants); ?> ID</h4>
                        <button onclick="toggleTable('<?php echo htmlspecialchars($table . '_' . $field); ?>')">Tampilkan</button>
                        <table id="<?php echo htmlspecialchars($table . '_' . $field); ?>" class="hidden">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participants as $participant): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($participant['id']); ?></td>
                                        <td><?php echo htmlspecialchars($participant['nama']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        // Fungsi untuk toggle tampilan tabel peserta
        function toggleTable(elementId) {
            const element = document.getElementById(elementId);
            element.classList.toggle('hidden');
        }
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
    <a href="index.php" class="back-button">Kembali</a>
</body>
</html>