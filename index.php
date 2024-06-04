<?php
session_start();
include('koneksi.php');

if (isset($_SESSION['username'])) {
    header("Location: menu.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $data = [];

    $query = mysqli_query($koneksi, "SELECT username, password FROM admin WHERE username = '$username' AND password = '$password'");
    $data = mysqli_fetch_array($query);
    $num = mysqli_num_rows($query);

    if (!empty($data)) {
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];

        setcookie("message", "delete", time() - 1);
        header("location: menu.php");
    } else {
        setcookie("message", "Maaf, Username atau Password salah", time() + 3600);
        header("location: index.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <Link rel="stylesheet" type="text/css" href="indx.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($_COOKIE['message'])) { ?>
            <div class="status"><?php echo $_COOKIE['message']; ?></div>
        <?php } ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>