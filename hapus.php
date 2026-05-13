<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status_login'])) {
    header("location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_pinjam = $_GET['id'];
    $id_user = $_SESSION['id_user'];

    $query_hapus = mysqli_query($koneksi, "DELETE FROM peminjaman WHERE id_pinjam='$id_pinjam' AND id_user='$id_user'");

    if ($query_hapus) {
        $_SESSION['pesan'] = "Data peminjaman berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data!";
    }
}

header("location: home.php");
exit();
?>