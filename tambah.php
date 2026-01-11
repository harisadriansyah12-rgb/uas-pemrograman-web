<?php
session_start();

// 1. CEK LOGIN: Jika belum login, lempar ke login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 2. CEK ROLE: Jika bukan admin, lempar ke index.php dengan pesan aman
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location='index.php';</script>";
    exit;
}

require_once 'models.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama   = $_POST['nama_barang'];
    $stok   = $_POST['stok'];
    
    // Proses Upload Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];
    
    // Pastikan folder uploads sudah ada
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    $path = "uploads/" . time() . "_" . $gambar; // Tambah time() agar nama file unik

    if (move_uploaded_file($tmp, $path)) {
        $pModel = new Produk();
        $namaFileBaru = time() . "_" . $gambar;
        if ($pModel->tambah($nama, $stok, $namaFileBaru)) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menyimpan ke database.";
        }
    } else {
        $error = "Gagal upload gambar. Pastikan folder 'uploads' dapat ditulis!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Barang - TOKO HARIS</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white d-flex justify-content-between">
                        <h4 class="mb-0">Tambah Barang Baru</h4>
                        <small>Admin: <?= $_SESSION['username'] ?></small>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Laptop Asus" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" min="1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Pilih Gambar Produk</label>
                                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                                <div class="form-text">Gunakan format .jpg, .png, atau .webp</div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-success">Simpan ke Katalog</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>