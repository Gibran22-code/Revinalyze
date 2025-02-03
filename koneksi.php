<?php

$servername = "localhost"; 
$username = "root";        
$password = "";            
$database = "db_penilaian";  

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

?>
