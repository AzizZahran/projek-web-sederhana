<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'penyewaan';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idKonsumen = $_POST['id_konsumen'];
    $namaKonsumen = $_POST['nama_konsumen'];
    $alamat = $_POST['alamat'];
    $noHp = $_POST['hp'];

    $queryInsertKonsumen = "INSERT INTO Konsumen (id_konsumen, nama_konsumen, alamat, hp) VALUES ('$idKonsumen', '$namaKonsumen', '$alamat', '$noHp')";
    if (mysqli_query($conn, $queryInsertKonsumen)) {
        $status = 'Data konsumen berhasil ditambahkan.';
    } else {
        $status = 'Terjadi kesalahan: ' . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Input Konsumen</title>
    <Link rel="stylesheet" type="text/css" href="knsmni.css">
</head>
<body>
    <div class="container">
        <h1>Form Input Konsumen</h1>
        <?php if (!empty($status)) { ?>
            <div class="status"><?php echo $status; ?></div>
        <?php } ?>
        <form method="POST" action="">
            <label for="id_konsumen">ID Konsumen:</label>
            <input type="text" name="id_konsumen" id="id_konsumen" required>
            <label for="nama_konsumen">Nama Konsumen:</label>
            <input type="text" name="nama_konsumen" id="nama_konsumen" required>
            <label for="alamat">Alamat:</label>
            <textarea name="alamat" id="alamat" required></textarea>
            <label for="no_hp">No. HP:</label>
            <input type="text" name="hp" id="hp" required>
            <input type="submit" value="Tambah Konsumen">
        </form> <br>
        <a href="menu.php">Kembali ke Menu</a>
    </div>
</body>
</html>