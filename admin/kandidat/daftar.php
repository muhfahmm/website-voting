<?php
session_start();
require '../../db/db.php';

// cek login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$query = mysqli_query($db, "SELECT * FROM tb_kandidat");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Kandidat - Voting OSIS</title>
    <link rel="stylesheet" href="../assets/css/daftar.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            padding: 40px 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
    </style>
</head>

<body>
    <div class="kandidat-container">
        <?php
        if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) : ?>
            <div class="kandidat-card">
                <div class="foto-wrapper">
                    <img src="../uploads/<?= $row['foto_ketua'] ?>">
                    <img src="../uploads/<?= $row['foto_wakil'] ?>">
                </div>
                <h3><?= $row['nama_ketua']; ?> - <?= $row['nama_wakil']; ?></h3>
                <a href="hapus.php?id=<?php echo $row['id']; ?>">Hapus</a>
            </div>
        <?php endwhile; 
        } else { ?>
        <?php echo "kandidat masih kosong";
        ?>
        <?php

        }
        ?>


    </div>

</body>

</html>