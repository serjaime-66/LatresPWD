<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("location: login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['id_user'];
    $id_lab = $_POST['id_lab'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $waktu_pinjam = $_POST['waktu_pinjam'];

    $cek_bentrok = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_lab='$id_lab' AND tanggal_pinjam='$tanggal_pinjam' AND waktu_pinjam='$waktu_pinjam'");

    if (mysqli_num_rows($cek_bentrok) > 0) {
        $_SESSION['error'] = "Waktu yang dipilih sudah tidak tersedia!";
    } else {
        $query_insert = mysqli_query($koneksi, "INSERT INTO peminjaman (id_user, id_lab, tanggal_pinjam, waktu_pinjam) VALUES ('$id_user', '$id_lab', '$tanggal_pinjam', '$waktu_pinjam')");
        
        if ($query_insert) {
            $_SESSION['pesan'] = "Berhasil mengajukan pinjaman lab!";
            header("location: home.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal menyimpan data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Pinjaman - Booking Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Form Peminjaman Lab</h4>

                        <?php if(isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger py-2"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php } ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Laboratorium</label>
                                <select class="form-select" name="id_lab" required>
                                    <option value="" selected disabled>-- Pilih Lab --</option>
                                    <?php
                                    $query_lab = mysqli_query($koneksi, "SELECT * FROM laboratorium");
                                    while ($lab = mysqli_fetch_array($query_lab)) {
                                        echo "<option value='".$lab['id_lab']."'>".$lab['nama_lab']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" class="form-control" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Waktu Pinjam</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="waktu_pinjam" value="08:00" id="w1" required>
                                        <label class="form-check-label" for="w1">08:00</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="waktu_pinjam" value="10:30" id="w2">
                                        <label class="form-check-label" for="w2">10:30</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="waktu_pinjam" value="13:00" id="w3">
                                        <label class="form-check-label" for="w3">13:00</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="waktu_pinjam" value="15:30" id="w4">
                                        <label class="form-check-label" for="w4">15:30</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="simpan" class="btn btn-primary w-100 mb-2">Simpan Ajuan</button>
                            <a href="home.php" class="btn btn-secondary w-100">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>