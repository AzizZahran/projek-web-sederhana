<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Halaman</title>
    <Link rel="stylesheet" type="text/css" href="mn.css">
</head>
<body>
    <div class="container">
        <h1>Menu Halaman</h1>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>

        <ul class="menu">
            <li><a href="konsumen.php">Form Input Konsumen</a></li>
            <li><a href="transaksi.php">Form Transaksi</a></li>
            <li><a href="tampildata.php">Data Penyewaan</a></li>
        </ul>
        <br><br>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>