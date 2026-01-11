<?php
session_start();
require_once 'config/Database.php';
$db = (new Database())->getConnection();
$id = $_GET['id'];

// Cek stok barang
$stmt = $db->prepare("SELECT * FROM barang WHERE id = :id");
$stmt->execute(['id' => $id]);
$barang = $stmt->fetch(PDO::FETCH_ASSOC);

if ($barang['stok'] > 0) {
    // Kurangi stok 1 buah
    $new_stok = $barang['stok'] - 1;
    $update = $db->prepare("UPDATE barang SET stok = :stok WHERE id = :id");
    $update->execute(['stok' => $new_stok, 'id' => $id]);
    
    // Buka halaman struk
    header("Location: struk.php?id=$id");
} else {
    echo "<script>alert('Maaf, Stok Habis!'); window.location='index.php';</script>";
}
?>