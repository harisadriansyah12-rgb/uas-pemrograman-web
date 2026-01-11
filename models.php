<?php
require_once 'config/Database.php';

class Produk {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function tampilkan($start, $limit, $search = '') {
        $sql = "SELECT * FROM barang WHERE nama_barang LIKE :search LIMIT :start, :limit";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hitungTotal($search = '') {
        $sql = "SELECT COUNT(*) as total FROM barang WHERE nama_barang LIKE :search";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$search%";
        $stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res['total'];
    }

    public function tambah($nama, $stok, $gambar) {
        $sql = "INSERT INTO barang (nama_barang, stok, gambar) VALUES (:nama, :stok, :gambar)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':stok', $stok);
        $stmt->bindParam(':gambar', $gambar);
        return $stmt->execute();
    }
}
?>