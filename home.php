<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$hari_ini = date('Y-m-d'); 
$slot_waktu = ['08:00', '10:30', '13:00', '15:30'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home - Booking Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-floating {
            position: fixed;
            bottom: 30px;
            right: 30px;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="home.php">Booking Lab</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="home.php">Home</a>
                <a class="nav-link" href="riwayat.php">Riwayat</a>
                <a class="nav-link text-warning" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        
        <?php if(isset($_SESSION['pesan'])) { ?>
            <div class="alert alert-success py-2"><?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
        <?php } ?>
        <?php if(isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger py-2"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php } ?>

        <div class="row mb-4 align-items-center">
            <div class="col-md-7">
                <h5 class="mb-0">Laboratorium yang tersedia hari ini (<?php echo date('d/m/Y'); ?>)</h5>
            </div>
            <div class="col-md-5">
                <form method="GET" action="" class="d-flex shadow-sm">
                    <input type="text" name="cari" class="form-control me-2" placeholder="Cari laboratorium..." value="<?php echo isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <?php if(isset($_GET['cari']) && $_GET['cari'] != '') { ?>
                        <a href="home.php" class="btn btn-outline-danger ms-2">Reset</a>
                    <?php } ?>
                </form>
            </div>
        </div>
        
        <div class="row mb-5">
            <?php
            if (isset($_GET['cari']) && $_GET['cari'] != '') {
                $kata_kunci = $_GET['cari'];
                $query_lab = mysqli_query($koneksi, "SELECT * FROM laboratorium WHERE nama_lab LIKE '%$kata_kunci%'");
            } else {
                $query_lab = mysqli_query($koneksi, "SELECT * FROM laboratorium");
            }

            if (mysqli_num_rows($query_lab) > 0) {
                while ($lab = mysqli_fetch_array($query_lab)) {
                    $id_lab = $lab['id_lab'];
            ?>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-primary"><?php echo $lab['nama_lab']; ?></h6>
                                <div class="d-flex flex-wrap gap-2 mt-3">
                                    <?php
                                    foreach ($slot_waktu as $waktu) {
                                        $cek_booking = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_lab='$id_lab' AND tanggal_pinjam='$hari_ini' AND waktu_pinjam='$waktu'");
                                        if (mysqli_num_rows($cek_booking) == 0) {
                                            echo "<span class='badge bg-success p-2' style='font-size: 13px;'>$waktu</span>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                } 
            } else {
                echo "<div class='col-12'><div class='alert alert-warning text-center'>Laboratorium tidak ditemukan.</div></div>";
            }
            ?>
        </div>

        <h5 class="mb-3">Ajuan pinjaman saat ini</h5>
        <div class="row pb-5">
            <?php
            $query_riwayat = mysqli_query($koneksi, "
                SELECT peminjaman.*, laboratorium.nama_lab 
                FROM peminjaman 
                JOIN laboratorium ON peminjaman.id_lab = laboratorium.id_lab 
                WHERE peminjaman.id_user = '$id_user' 
                ORDER BY peminjaman.created_at DESC 
                LIMIT 5
            ");

            if (mysqli_num_rows($query_riwayat) > 0) {
                while ($riwayat = mysqli_fetch_array($query_riwayat)) {
            ?>
                    <div class="col-12 mb-3">
                        <div class="card shadow-sm border-0 border-start border-primary border-4">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1"><?php echo $riwayat['nama_lab']; ?></h6>
                                    <small class="text-muted">Diajukan: <?php echo date('d/m/Y H:i', strtotime($riwayat['created_at'])); ?></small><br>
                                    <span class="badge bg-primary mt-1">Jadwal: <?php echo date('d/m/Y', strtotime($riwayat['tanggal_pinjam'])) . " | " . $riwayat['waktu_pinjam']; ?></span>
                                </div>
                                <div>
                                    <a href="edit.php?id=<?php echo $riwayat['id_pinjam']; ?>" class="btn btn-light btn-sm text-secondary border">EDIT</a>
                                    <a href="hapus.php?id=<?php echo $riwayat['id_pinjam']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Batalkan peminjaman ini?')">HAPUS</a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php 
                }
            } else {
                echo "<div class='col-12'><div class='alert alert-light text-center border'>Belum ada ajuan pinjaman.</div></div>";
            }
            ?>
        </div>
    </div>

    <a href="tambah.php" class="btn btn-primary btn-floating text-white shadow">+</a>

</body>
</html>