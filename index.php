<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit; }
require_once 'models.php';
$pModel = new Produk();
$role = $_SESSION['role'] ?? 'user';
$search = $_GET['s'] ?? '';
// Mengambil semua data barang
$data = $pModel->tampilkan(0, 100, $search);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>TOKO HARIS - Katalog</title>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4 shadow">
        <div class="container">
            <span class="navbar-brand">TOKO HARIS (User: <?= htmlspecialchars($_SESSION['username']) ?>)</span>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </nav>
    <div class="container">
        <div class="d-flex justify-content-between mb-3 align-items-center">
            <h3>Katalog Produk</h3>
            <?php if($role == 'admin'): ?>
                <a href="tambah.php" class="btn btn-success">+ Tambah Barang</a>
            <?php endif; ?>
        </div>
        
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $row): ?>
                    <tr>
                        <td><img src="uploads/<?= $row['gambar'] ?>" width="60" height="60" class="rounded border" style="object-fit:cover;"></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td class="text-success fw-bold">Rp <?= number_format($row['harga'] ?? 0, 0, ',', '.') ?></td>
                        <td><span class="badge bg-info text-dark"><?= $row['stok'] ?></span></td>
                        <td>
                            <a href="beli.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Beli</a>
                            
                            <?php if($role == 'admin'): ?>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">Edit</a>
                                <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>