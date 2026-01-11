<?php
require_once 'config/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->prepare("SELECT * FROM barang WHERE id = :id");
$stmt->execute(['id' => $_GET['id']]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - TOKO HARIS</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; display: flex; justify-content: center; padding-top: 50px; }
        .struk { width: 300px; border: 1px solid #000; padding: 15px; background: #fff; }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
    </style>
</head>
<body onload="window.print()">
    <div class="struk">
        <h2 class="text-center">TOKO HARIS</h2>
        <p class="text-center">Struk Pembayaran Sah</p>
        <div class="divider"></div>
        <p>Barang: <?= $barang['nama_barang'] ?></p>
        <p>Harga : Rp <?= number_format($barang['harga'], 0, ',', '.') ?></p>
        <p>Qty   : 1</p>
        <div class="divider"></div>
        <h3>TOTAL : Rp <?= number_format($barang['harga'], 0, ',', '.') ?></h3>
        <div class="divider"></div>
        <p class="text-center">Terima Kasih Atas Pembelian Anda!</p>
        <p class="text-center" style="margin-top:20px;">
            <a href="index.php" style="text-decoration:none; color:blue; font-size:12px;">[ Kembali ke Katalog ]</a>
        </p>
    </div>
</body>
</html>