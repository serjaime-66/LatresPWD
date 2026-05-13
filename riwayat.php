<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat - Booking Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="home.php">Booking Lab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="home.php">Home</a>
                <a class="nav-link active" href="riwayat.php">Riwayat</a>
                <a class="nav-link text-warning" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h4 class="mb-4">Riwayat Peminjaman Anda</h4>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Laboratorium</th>
                                <th>Tanggal Pinjam</th>
                                <th>Waktu Slot</th>
                                <th>Waktu Mengajukan (Timestamp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "
                                SELECT peminjaman.*, laboratorium.nama_lab 
                                FROM peminjaman 
                                JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab 
                                WHERE peminjaman.id_user = '$id_user' 
                                ORDER BY peminjaman.tanggal_pinjam DESC, peminjaman.waktu_pinjam DESC
                            ");

                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td class='fw-bold text-primary'>" . $row['nama_lab'] . "</td>";
                                    echo "<td>" . date('d F Y', strtotime($row['tanggal_pinjam'])) . "</td>";
                                    echo "<td><span class='badge bg-success'>" . $row['waktu_pinjam'] . "</span></td>";
                                    echo "<td>" . $row['created_at'] . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Belum ada riwayat peminjaman.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>