<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'penyewaan';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$queryBarang = "SELECT * FROM Barang";
$resultBarang = mysqli_query($conn, $queryBarang);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus_konsumen'])) {
        $idKonsumen = $_POST['hapus_konsumen'];

        $queryTransaksi = "SELECT * FROM Transaksi WHERE id_konsumen = '$idKonsumen'";
        $resultTransaksi = mysqli_query($conn, $queryTransaksi);
        $rowTransaksi = mysqli_fetch_assoc($resultTransaksi);
        $namaBarang = $rowTransaksi['nama_barang'];
        $jumlahBarang = $rowTransaksi['jumlah_barang'];

        $queryDeleteKonsumen = "DELETE FROM Konsumen WHERE id_konsumen = '$idKonsumen'";
        if (mysqli_query($conn, $queryDeleteKonsumen)) {
            $queryBarang = "SELECT * FROM Barang WHERE nama_barang = '$namaBarang'";
            $resultBarang = mysqli_query($conn, $queryBarang);
            $rowBarang = mysqli_fetch_assoc($resultBarang);

            $stok = $rowBarang['stok'];
            $newStok = $stok + $jumlahBarang;
            $queryUpdateStok = "UPDATE Barang SET stok = $newStok WHERE nama_barang = '$namaBarang'";
            mysqli_query($conn, $queryUpdateStok);

            $status = 'Data konsumen berhasil dihapus.';
            header("Location: tampildata.php");
            exit();
        } else {
            $status = 'Terjadi kesalahan saat menghapus data konsumen: ' . mysqli_error($conn);
        }
    }

    if (isset($_POST['sewa_barang'])) {
        $namaBarang = $_POST['nama_barang'];
        $jumlahBarang = $_POST['jumlah_barang'];
        $namaKonsumen = $_POST['nama_konsumen'];
        $alamat = $_POST['alamat'];
        $noHP = $_POST['no_hp'];

        $queryBarang = "SELECT * FROM Barang WHERE nama_barang = '$namaBarang'";
        $resultBarang = mysqli_query($conn, $queryBarang);
        $rowBarang = mysqli_fetch_assoc($resultBarang);

        if (!$rowBarang) {
            $status = 'Barang tidak tersedia.';
        } else {
            $stok = $rowBarang['stok'];

            if ($stok < $jumlahBarang) {
                $status = 'Stok barang tidak mencukupi.';
            } else {
                $hargaSewa = $rowBarang['harga_sewa'];
                $hargaTotal = $hargaSewa * $jumlahBarang;
                $newStok = $stok - $jumlahBarang;
                $queryUpdateStok = "UPDATE Barang SET stok = $newStok WHERE nama_barang = '$namaBarang'";
                mysqli_query($conn, $queryUpdateStok);

                $idTransaksi = uniqid();

                $queryInsertTransaksi = "INSERT INTO Transaksi (id_transaksi, nama_barang, nama_konsumen, jumlah_barang, harga_total, alamat, hp) VALUES ('$idTransaksi', '$namaBarang', '$namaKonsumen', $jumlahBarang, $hargaTotal, '$alamat', '$noHP')";
                if (mysqli_query($conn, $queryInsertTransaksi)) {
                    $status = 'Barang berhasil disewa.';
                } else {
                    $status = 'Terjadi kesalahan saat menyimpan data transaksi: ' . mysqli_error($conn);
                }
            }
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Penyewaan</title>
    <link rel="stylesheet" type="text/css" href="trnsks.css">
</head>

<body>
    <div class="container">
        <h1>Data Penyewaan</h1>
        <?php if (!empty($status)) { ?>
            <div class="status"><?php echo $status; ?></div>
        <?php } ?>
        <h2>Data Barang</h2>
        <table>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Jenis Barang</th>
                <th>Harga Sewa</th>
                <th>Stok Barang</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($resultBarang)) {
                $stok = $row['stok'];
            ?>
                <tr>
                    <td><?php echo $row['id_barang']; ?></td>
                    <td><?php echo $row['nama_barang']; ?></td>
                    <td><?php echo $row['jenis_barang']; ?></td>
                    <td><?php echo $row['harga_sewa']; ?></td>
                    <td><?php echo $stok; ?></td>
                </tr>
            <?php } ?>
        </table>

        <h2>Sewa Barang</h2>
        <form method="POST" action="">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" required>

            <label>Jumlah Barang</label>
            <input type="number" name="jumlah_barang" required>

            <label>Nama Konsumen</label>
            <input type="text" name="nama_konsumen" required>

            <label>Alamat</label>
            <textarea name="alamat" required></textarea>

            <label>No. HP</label>
            <input type="text" name="no_hp" required>

            <input type="submit" name="sewa_barang" value="Sewa Barang">
        </form>

        <a href="menu.php">Kembali ke Menu</a>
    </div>
</body>
</html>