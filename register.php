<?php
session_start();
include 'koneksi.php';

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid!";
    }
    else if (strlen($username) > 20) {
        $_SESSION['error'] = "Username tidak boleh lebih dari 20 karakter!";
    }
    else if (strlen($password) < 6) {
        $_SESSION['error'] = "Password minimal terdiri dari 6 karakter!";
    } 
    else {
        $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek_user) > 0) {
            $_SESSION['error'] = "Username sudah digunakan, cari yang lain!";
        } else {
            $query = mysqli_query($koneksi, "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')");
            
            if ($query) {
                $_SESSION['sukses'] = "Registrasi berhasil! Silakan Login.";
                header("location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "Gagal mendaftar ke database!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Booking Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">REGISTER</h4>
                        <p class="text-center text-muted mb-4">Mulai ajukan peminjaman lab</p>

                        <?php if(isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger py-2"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php } ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="register" class="btn btn-primary w-100 mb-3">Buat Akun</button>
                            <p class="text-center mb-0"><small>Sudah punya akun? <a href="login.php">Login</a></small></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>