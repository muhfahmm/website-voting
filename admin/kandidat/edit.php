<?php
session_start();
require '../../db/db.php';

// cek login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

// ambil data kandidat berdasarkan id
$id = $_GET['id'];
$query = mysqli_query($db, "SELECT * FROM tb_kandidat WHERE id='$id'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo "Data kandidat tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kandidat</title>
    <link rel="stylesheet" href="../assets/css/form.css">
</head>
<body>
    <h2>Edit Kandidat</h2>
    <form action="../api/proses-edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <label>Nama Ketua</label><br>
        <input type="text" name="nama_ketua" value="<?= $data['nama_ketua']; ?>" required><br><br>

        <label>Nama Wakil</label><br>
        <input type="text" name="nama_wakil" value="<?= $data['nama_wakil']; ?>" required><br><br>

        <label>Foto Ketua</label><br>
        <img src="../uploads/<?= $data['foto_ketua']; ?>" width="100"><br>
        <input type="file" name="foto_ketua"><br><br>

        <label>Foto Wakil</label><br>
        <img src="../uploads/<?= $data['foto_wakil']; ?>" width="100"><br>
        <input type="file" name="foto_wakil"><br><br>

        <button type="submit" name="edit">Simpan Perubahan</button>
    </form>
</body>
</html>
