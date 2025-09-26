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
</head>
<body>
    <div class="card">
        <h2>Edit Kandidat</h2>
        <form action="../api/proses-edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['id']; ?>">

            <label for="nama_ketua">Nama Ketua</label>
            <input type="text" id="nama_ketua" name="nama_ketua" value="<?= $data['nama_ketua']; ?>" required>

            <label for="nama_wakil">Nama Wakil</label>
            <input type="text" id="nama_wakil" name="nama_wakil" value="<?= $data['nama_wakil']; ?>" required>

            <label>Foto Ketua</label>
            <div class="preview">
                <img id="preview_ketua" src="../uploads/<?= $data['foto_ketua']; ?>" alt="Foto Ketua">
            </div>
            <div class="file-input">
                <label class="file-input-label">Pilih Foto Ketua</label>
                <input type="file" name="foto_ketua" accept="image/*" onchange="previewImage(this, 'preview_ketua')">
            </div>

            <label>Foto Wakil</label>
            <div class="preview">
                <img id="preview_wakil" src="../uploads/<?= $data['foto_wakil']; ?>" alt="Foto Wakil">
            </div>
            <div class="file-input">
                <label class="file-input-label">Pilih Foto Wakil</label>
                <input type="file" name="foto_wakil" accept="image/*" onchange="previewImage(this, 'preview_wakil')">
            </div>

            <button type="submit" name="edit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="daftar.php" class="back-btn">⬅ Kembali</a>
        </form>
    </div>

    <script>
        function previewImage(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
