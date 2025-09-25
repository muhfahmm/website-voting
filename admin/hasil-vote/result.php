<?php
session_start();
require '../../db/db.php';

// cek login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$admin = $_SESSION['username'];

// Ambil data kandidat beserta jumlah suara
$query = mysqli_query($db, "
    SELECT k.nomor_kandidat, k.nama_ketua, k.nama_wakil, COUNT(v.id) AS total_suara
    FROM tb_kandidat k
    LEFT JOIN tb_vote_log v ON k.nomor_kandidat = v.nomor_kandidat
    GROUP BY k.nomor_kandidat, k.nama_ketua, k.nama_wakil
    ORDER BY k.nomor_kandidat ASC
");

// Hitung total semua suara
$totalQuery = mysqli_query($db, "SELECT COUNT(*) AS total FROM tb_vote_log");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalVotes = $totalRow['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Voting OSIS</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        .bar-chart {
            margin-top: 20px;
        }

        .bar {
            margin: 10px 0;
            background: #eee;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .bar-label {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .bar-fill {
            height: 30px;
            line-height: 30px;
            color: #fff;
            text-align: right;
            padding-right: 10px;
            background: #3498db;
            transition: width 0.5s ease;
        }

        .bar-fill.red {
            background: #e74c3c;
        }

        .bar-fill.green {
            background: #2ecc71;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">🏠 Dashboard</a></li>
            <li><a href="kandidat/tambah.php">➕ Tambah Kandidat</a></li>
            <li><a href="kandidat/daftar.php">📋 Daftar Kandidat</a></li>
            <li><a href="./auth/logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <b><?php echo htmlspecialchars($admin); ?></b></p>
        </header>
            <!-- Diagram Bar -->
            <div class="bar-chart">
                <?php
                mysqli_data_seek($query, 0); // reset pointer ke awal
                while ($row = mysqli_fetch_assoc($query)) {
                    $persentase = $totalVotes > 0 ? round(($row['total_suara'] / $totalVotes) * 100, 2) : 0;
                ?>
                    <div class="bar">
                        <div class="bar-label"><?= $row['nama_ketua']; ?> & <?= $row['nama_wakil']; ?></div>
                        <div class="bar-fill" style="width: <?= $persentase; ?>%;"><?= $persentase; ?>%</div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
</body>

</html>
