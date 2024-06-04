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

$queryKonsumen = "SELECT * FROM Konsumen";
$resultKonsumen = mysqli_query($conn, $queryKonsumen);

$queryTransaksi = "SELECT * FROM Transaksi";
$resultTransaksi = mysqli_query($conn, $queryTransaksi);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus_konsumen'])) {
        $idKonsumen = $_POST['hapus_konsumen'];

        $queryDeleteKonsumen = "DELETE FROM Konsumen WHERE id_konsumen = '$idKonsumen'";
        if (mysqli_query($conn, $queryDeleteKonsumen)) {
            $status = 'Data konsumen berhasil dihapus.';
            header("Location: tampildata.php"); // Mengarahkan kembali ke halaman tampildata.php
            exit();
        } else {
            $status = 'Terjadi kesalahan saat menghapus data konsumen: ' . mysqli_error($conn);
        }
    }

    if (isset($_POST['hapus_transaksi'])) {
        $idTransaksi = $_POST['hapus_transaksi'];

        $queryTransaksi = "SELECT * FROM Transaksi WHERE id_transaksi = '$idTransaksi'";
        $resultTransaksi = mysqli_query($conn, $queryTransaksi);
        $rowTransaksi = mysqli_fetch_assoc($resultTransaksi);
        $namaBarang = $rowTransaksi['nama_barang'];
        $jumlahBarang = $rowTransaksi['jumlah_barang'];

        $queryDeleteTransaksi = "DELETE FROM Transaksi WHERE id_transaksi = '$idTransaksi'";
        if (mysqli_query($conn, $queryDeleteTransaksi)) {
            $queryBarang = "SELECT * FROM Barang WHERE nama_barang = '$namaBarang'";
            $resultBarang = mysqli_query($conn, $queryBarang);
            $rowBarang = mysqli_fetch_assoc($resultBarang);

            $stok = $rowBarang['stok'];
            $newStok = $stok + $jumlahBarang;
            $queryUpdateStok = "UPDATE Barang SET stok = $newStok WHERE nama_barang = '$namaBarang'";
            mysqli_query($conn, $queryUpdateStok);

            $status = 'Data transaksi berhasil dihapus.';
            header("Location: tampildata.php");
            exit();
        } else {
            $status = 'Terjadi kesalahan saat menghapus data transaksi: ' . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penyewaan</title>
    <link rel="stylesheet" type="text/css" href="tmpl.css">
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
            <?php while ($row = mysqli_fetch_assoc($resultBarang)) { ?>
                <tr>
                    <td><?php echo $row['id_barang']; ?></td>
                    <td><?php echo $row['nama_barang']; ?></td>
                    <td><?php echo $row['jenis_barang']; ?></td>
                    <td>Rp <?php echo $row['harga_sewa']; ?></td>
                    <td><?php echo $row['stok']; ?></td>
                </tr>
            <?php } ?>
        </table>
        <h2>Data Konsumen</h2>
        <table>
            <tr>
                <th>ID Konsumen</th>
                <th>Nama Konsumen</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($resultKonsumen)) { ?>
                <tr>
                    <td><?php echo $row['id_konsumen']; ?></td>
                    <td><?php echo $row['nama_konsumen']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td><?php echo $row['hp']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="hapus_konsumen" value="<?php echo $row['id_konsumen']; ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <h2>Data Transaksi</h2>
        <table>
            <tr>
                <th>ID Transaksi</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Nama Konsumen</th>
                <th>Harga Total</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($resultTransaksi)) { ?>
                <tr>
                    <td><?php echo $row['id_transaksi']; ?></td>
                    <td><?php echo $row['nama_barang']; ?></td>
                    <td><?php echo $row['jumlah_barang']; ?></td>
                    <td><?php echo $row['nama_konsumen']; ?></td>
                    <td> Rp <?php echo $row['harga_total']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td><?php echo $row['hp']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="hapus_transaksi" value="<?php echo $row['id_transaksi']; ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a href="menu.php">Kembali ke Menu</a>
    </div>
</body>
</html>