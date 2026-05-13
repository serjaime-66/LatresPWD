<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_pinjam = $_GET['id'];

$query_lama = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_pinjam='$id_pinjam' AND id_user='$id_user'");
$data_lama = mysqli_fetch_array($query_lama);

if (isset($_POST['update'])) {
    $id_lab = $_POST['id_lab'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $waktu_pinjam = $_POST['waktu_pinjam'];

    $cek_bentrok = mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE id_lab='$id_lab' AND tanggal_pinjam='$tanggal_pinjam' AND waktu_pinjam='$waktu_pinjam' AND id_pinjam != '$id_pinjam'");

    if (mysqli_num_rows($cek_bentrok) > 0) {
        $_SESSION['error'] = "Waktu yang dipilih sudah tidak tersedia!";
    } else {
        $query_update = mysqli_query($koneksi, "UPDATE peminjaman SET id_lab='$id_lab', tanggal_pinjam='$tanggal_pinjam', waktu_pinjam='$waktu_pinjam' WHERE id_pinjam='$id_pinjam'");
        
        if ($query_update) {
            $_SESSION['pesan'] = "Data peminjaman berhasil diperbarui!";
            header("location: home.php");
            exit();
        } else {
            $_SESSION['error'] = "Gagal memperbarui data!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pinjaman - Booking Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h4 class="text-center mb-4">Edit Peminjaman Lab</h4>

                        <?php if(isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger py-2"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php } ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Laboratorium</label>
                                <select class="form-select" name="id_lab" required>
                                    <?php
                                    $query_lab = mysqli_query($koneksi, "SELECT * FROM laboratorium");
                                    while ($lab = mysqli_fetch_array($query_lab)) {
                                        $selected = ($lab['id_lab'] == $data_lama['id_lab']) ? "selected" : "";
                                        echo "<option value='".$lab['id_lab']."' $selected>".$lab['nama_lab']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $data_lama['tanggal_pinjam']; ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Waktu Pinjam</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <?php 
                                    $slot_waktu = ['08:00', '10:30', '13:00', '15:30'];
                                    foreach ($slot_waktu as $w) {
                                        $checked = ($w == $data_lama['waktu_pinjam']) ? "checked" : "";
                                        echo "
                                        <div class='form-check'>
                                            <input class='form-check-input' type='radio' name='waktu_pinjam' value='$w' id='w_$w' $checked required>
                                            <label class='form-check-label' for='w_$w'>$w</label>
                                        </div>";
                                    }
                                    ?>
                                </div>
                            </div>

                            <button type="submit" name="update" class="btn btn-warning w-100 mb-2 fw-bold">Simpan Perubahan</button>
                            <a href="home.php" class="btn btn-secondary w-100">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>