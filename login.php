<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['status_login'])) {
    header("location: home.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cari_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($cari_user) > 0) {
        $data_user = mysqli_fetch_array($cari_user);
        
        if ($password == $data_user['password']) {
            $_SESSION['status_login'] = true;
            $_SESSION['id_user'] = $data_user['id_user'];
            $_SESSION['username'] = $data_user['username'];
            
            header("location: home.php");
            exit();
        } else {
            $_SESSION['error'] = "Password tidak valid!";
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Booking Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">LOGIN</h4>
                        <p class="text-center text-muted mb-4">Selamat Datang Kembali</p>

                        <?php if(isset($_SESSION['sukses'])) { ?>
                            <div class="alert alert-success py-2"><?php echo $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
                        <?php } ?>

                        <?php if(isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger py-2"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php } ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100 mb-3">Masuk</button>
                            <p class="text-center mb-0"><small>Belum punya akun? <a href="register.php">Register</a></small></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>