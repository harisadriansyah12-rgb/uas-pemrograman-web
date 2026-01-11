<?php
session_start();
// Hanya admin yang bisa edit
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once 'config/Database.php';
$database = new Database();
$db = $database->getConnection();

// Ambil data lama berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM barang WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Proses Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['id'];
    $nama = $_POST['nama_barang'];
    $stok = $_POST['stok'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek jika user upload gambar baru
    if ($_FILES['gambar']['name'] != "") {
        $gambar = time() . "_" . $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "uploads/" . $gambar);
    } else {
        $gambar = $gambar_lama; // Tetap pakai gambar lama jika tidak ganti
    }

    $sql = "UPDATE barang SET nama_barang = :nama, stok = :stok, gambar = :gambar WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':stok', $stok);
    $stmt->bindParam(':gambar', $gambar);
    $stmt->bindParam(':id', $id_barang);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Barang - TOKO HARIS</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow" style="max-width: 500px;">
            <div class="card-header bg-warning"><strong>Edit Produk</strong></div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $barang['id'] ?>">
                    <input type="hidden" name="gambar_lama" value="<?= $barang['gambar'] ?>">
                    
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= $barang['nama_barang'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $barang['stok'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Ganti Gambar (Kosongkan jika tidak ganti)</label>
                        <input type="file" name="gambar" class="form-control">
                        <p class="small text-muted">Gambar sekarang: <?= $barang['gambar'] ?></p>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>