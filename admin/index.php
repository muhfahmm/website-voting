<?php
session_start();
require '../db/db.php';

// cek login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$admin = $_SESSION['username'];

$query = mysqli_query($db, "SELECT * FROM tb_kandidat")
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Voting OSIS</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link rel="stylesheet" href="./assets/css/global.css">
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

        <!-- Hasil Voting -->
        <section class="card">
            <h2>📊 Hasil Voting Sementara</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pasangan Kandidat</th>
                        <th>Jumlah Suara</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nomor_kandidat']; ?></td>
                            <td><?= $row['nama_ketua']; ?> - <?= $row['nama_wakil']; ?></td>
                            <td>100%</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Diagram -->
            <div class="bar-chart">
                <div class="bar">
                    <div class="bar-label">Ketua A & Wakil A</div>
                    <div class="bar-fill" style="width: 40%;">40%</div>
                </div>
                <div class="bar">
                    <div class="bar-label">Ketua B & Wakil B</div>
                    <div class="bar-fill red" style="width: 30%;">30%</div>
                </div>
                <div class="bar">
                    <div class="bar-label">Ketua C & Wakil C</div>
                    <div class="bar-fill green" style="width: 30%;">30%</div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>