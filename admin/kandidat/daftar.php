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

        .kandidat-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .kandidat-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            transition: 0.3s;
        }

        .kandidat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .foto-wrapper {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .foto-wrapper img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #2c3e50;
        }

        .kandidat-info {
            margin-top: 10px;
        }

        .kandidat-info h3 {
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .kandidat-info p {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="kandidat-container">
        <?php
        while ($row = mysqli_fetch_assoc($query)) : ?>
            <div class="kandidat-card">
                <div class="foto-wrapper">
                    <img src="../uploads/<?= $row['foto_ketua'] ?>">
                    <img src="../uploads/<?= $row['foto_wakil'] ?>">
                </div>
                <h3><?= $row['nama_ketua']; ?> - <?= $row['nama_wakil']; ?></h3>
            </div>
        <?php endwhile; ?>

    </div>

</body>

</html>