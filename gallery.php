<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Gambar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .preview-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        .btn-download, .btn-delete {
            padding: 5px 10px;
            margin: 0 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download {
            background-color: #2196F3;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .btn-download:hover {
            background-color: #0b7dda;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }
        .upload-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="file"] {
            margin: 10px 0;
            padding: 10px;
        }
        .btn-upload {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-upload:hover {
            background-color: #45a049;
        }
        .format-info {
            color: #666;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h1>📸 Upload Gambar</h1>
<p class="subtitle">Kelola file gambar Anda dengan mudah</p>

<?php
// Folder tempat menyimpan file
$target_dir = "uploads/";

// Proses upload file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {
    $file = $_FILES['fileUpload'];
    $target_file = $target_dir . basename($file['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Cek ekstensi yang diizinkan
    $allowed = array('jpg', 'jpeg', 'png', 'gif');
    
    if (in_array($imageFileType, $allowed)) {
        // Cek ukuran file (max 500KB = 500000 bytes)
        if ($file['size'] <= 500000) {
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                echo "<p style='color:green; text-align:center;'>✅ Berhasil upload: " . htmlspecialchars($file['name']) . "</p>";
            } else {
                echo "<p style='color:red; text-align:center;'>❌ Gagal upload!</p>";
            }
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Ukuran file terlalu besar! Maksimal 500KB.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Format tidak didukung! Hanya JPG, JPEG, PNG, GIF.</p>";
    }
}

// Proses hapus file
if (isset($_GET['delete'])) {
    $file_to_delete = $target_dir . $_GET['delete'];
    if (file_exists($file_to_delete)) {
        unlink($file_to_delete);
        echo "<p style='color:green; text-align:center;'>✅ File berhasil dihapus!</p>";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Proses download file
if (isset($_GET['download'])) {
    $file_to_download = $target_dir . $_GET['download'];
    if (file_exists($file_to_download)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'. $_GET['download'] .'"');
        header('Content-Length: ' . filesize($file_to_download));
        readfile($file_to_download);
        exit;
    }
}

// Baca semua file di folder uploads
$files = array_diff(scandir($target_dir), array('.', '..'));
?>

<!-- Tabel Daftar File -->
<h2>📋 Daftar Berkas yang Diunggah</h2>
<table>
    <thead>
        <tr>
            <th>Pratinjau</th>
            <th>Nama Berkas</th>
            <th>Tipe</th>
            <th>Ukuran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($files)): ?>
            <tr>
                <td colspan="5" style="text-align:center;">Belum ada file yang diupload</td>
            </tr>
        <?php else: ?>
            <?php foreach ($files as $file): ?>
                <?php 
                $file_path = $target_dir . $file;
                $file_ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                $file_size = round(filesize($file_path) / 1024, 2); // KB
                ?>
                <tr>
                    <td>
                        <?php if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="<?php echo $file_path; ?>" class="preview-img" alt="<?php echo $file; ?>">
                        <?php else: ?>
                            <span>📄</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $file; ?></td>
                    <td><?php echo strtoupper($file_ext); ?></td>
                    <td><?php echo $file_size; ?> KB</td>
                    <td>
                        <a href="?download=<?php echo urlencode($file); ?>" class="btn-download">⬇ Unduh</a>
                        <a href="?delete=<?php echo urlencode($file); ?>" class="btn-delete" onclick="return confirm('Yakin hapus file ini?')">🗑 Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- Form Upload -->
<div class="upload-form">
    <h3>📤 Unggah File Baru</h3>
    <form action="" method="post" enctype="multipart/form-data">
        <p>Format yang didukung: <strong>JPG, JPEG, PNG, GIF</strong> (Max. <strong>500KB</strong>)</p>
        <input type="file" name="fileUpload" id="fileUpload" accept="image/jpeg,image/png,image/gif">
        <br>
        <input type="submit" value="Unggah File" class="btn-upload">
    </form>
</div>

</body>
</html>