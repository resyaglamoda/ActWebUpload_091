<?php
echo "<pre>";
echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "File: " . print_r($_FILES, true) . "\n";

$upload_dir = getcwd() . "/uploads/";
echo "Upload directory: " . $upload_dir . "\n";

if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "Folder uploads dibuat\n";
}

if (isset($_FILES["fileUpload"]) && $_FILES["fileUpload"]["error"] == 0) {
    $tmp_name = $_FILES["fileUpload"]["tmp_name"];
    $name = $_FILES["fileUpload"]["name"];
    $target = $upload_dir . $name;
    
    echo "Temp file: " . $tmp_name . "\n";
    echo "Target: " . $target . "\n";
    echo "Temp exists? " . (file_exists($tmp_name) ? "Yes" : "No") . "\n";
    
    if (move_uploaded_file($tmp_name, $target)) {
        echo "BERHASIL! File ada di: <a href='uploads/$name'>uploads/$name</a>";
    } else {
        echo "GAGAL! Error: " . $_FILES["fileUpload"]["error"];
    }
} else {
    echo "Tidak ada file yang diupload. Error code: " . $_FILES["fileUpload"]["error"];
}
echo "</pre>";
?>