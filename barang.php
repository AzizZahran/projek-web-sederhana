<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "penyewaan";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
$queryInsertBarang = "INSERT INTO Barang (id_barang, nama_barang, jenis_barang, harga_sewa, stok) VALUES
    ('001T', 'TENDA KAPASITAS 4-5 ORANG DOUBLE LAYER', 'TENDA', 35000, 4),
    ('002T', 'TENDA KAPASITAS 2-3 ORANG DOUBLE LAYER', 'TENDA', 30000, 4),
    ('003T', 'TENDA KAPASITAS 2-3 ORANG SINGLE LAYER', 'TENDA', 22000, 4),
    ('001C', 'SLEEPING BAG POLAR', 'ALAT TIDUR', 8000, 10),
    ('002C', 'MATRAS', 'ALAT TIDUR', 5000, 10),
    ('003C', 'MATRAS ALUMUNIUM FOIL', 'ALAT TIDUR', 7000, 10),
    ('001B', 'LAMPU TENDA', 'PENERANGAN', 4000, 10),
    ('002B', 'HEADLAMP CAS', 'PENERANGAN', 7000, 10),
    ('001M', 'NESTING TNI', 'ALAT MASAK', 8000, 8),
    ('002M', 'KOMPOR', 'ALAT MASAK', 7000, 12),
    ('003M', 'KOMPOR PORTABLE', 'ALAT MASAK', 8000, 12),
    ('004M', 'GAS PORTABLE', 'ALAT MASAK', 6000, 25),
    ('001A', 'CARRIER 60L', 'TREKKING', 30000, 5),
    ('002A', 'CARRIER 45L', 'TREKKING', 25000, 5),
    ('003A', 'SEPATU HIKING', 'TREKKING', 25000, 5)";
mysqli_query($conn, $queryInsertBarang);
mysqli_close($conn);
echo "Database dan tabel berhasil dibuat.";
?>