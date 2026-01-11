<?php
session_start();
// Proteksi admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once 'config/Database.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query hapus
    $sql = "DELETE FROM barang WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Gagal menghapus data.";
    }
}
?>