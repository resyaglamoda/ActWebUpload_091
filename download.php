<?php
$file = $_GET['file'];

// Tentukan folder tempat file disimpan
$folder = "uploads/";
$path = $folder . $file;

// Cek apakah file ada
if(file_exists($path)){
    // Set header untuk download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'. $file .'"');
    header('Content-Length: ' . filesize($path));
    
    // Baca dan kirim file
    readfile($path);
    exit;
} else {
    echo "File tidak ditemukan!";
}
?>