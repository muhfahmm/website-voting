<?php
session_start();
require '../../db/db.php';

// cek login
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$admin = $_SESSION['username'];

// --- Pagination setup ---
$limit = 10; // jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$totalQuery = mysqli_query($db, "SELECT COUNT(*) as total FROM tb_voter v JOIN tb_vote_log l ON v.id = l.voter_id");
$totalRow = mysqli_fetch_assoc($totalQuery);
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

// Ambil data voter dengan pagination
$votedQuery = mysqli_query($db, "
    SELECT v.id, v.nama_voter, v.kelas, l.nomor_kandidat
    FROM tb_voter v
    JOIN tb_vote_log l ON v.id = l.voter_id
    ORDER BY v.kelas, v.nama_voter
    LIMIT $limit OFFSET $offset
");

$voters = [];
while ($row = mysqli_fetch_assoc($votedQuery)) {
    $voters[] = $row;
}

// jumlah siswa setiap kelas
$dataKelas = [
    "X-1"   => 10,
    "X-2"   => 20,
    "XI-1"  => 25,
    "XI-2"  => 30,
    "XII"   => 35
];

// --- Hitung berapa yang sudah vote per kelas ---
$kelasSummary = [];
foreach ($dataKelas as $kelas => $target) {
    $q = mysqli_query($db, "
        SELECT COUNT(*) as jumlah 
        FROM tb_voter v 
        JOIN tb_vote_log l ON v.id=l.voter_id 
        WHERE v.kelas='$kelas'
    ");
    $row = mysqli_fetch_assoc($q);
    $kelasSummary[$kelas] = [
        "voted" => isset($row['jumlah']) ? (int)$row['jumlah'] : 0,
        "target" => (int)$target
    ];
}

// --- Hitung distribusi kandidat per kelas ---
$hasilKandidat = [];
$q = mysqli_query($db, "
    SELECT v.kelas, l.nomor_kandidat, COUNT(*) as total_suara
    FROM tb_vote_log l
    JOIN tb_voter v ON l.voter_id = v.id
    GROUP BY v.kelas, l.nomor_kandidat
");

while ($row = mysqli_fetch_assoc($q)) {
    $kelas = $row['kelas'];
    $nomor = $row['nomor_kandidat'];
    $jumlah = $row['total_suara'];

    if (!isset($hasilKandidat[$kelas])) {
        $hasilKandidat[$kelas] = [
            "total" => 0,
            "kandidat" => []
        ];
    }

    $hasilKandidat[$kelas]["kandidat"][$nomor] = $jumlah;
    $hasilKandidat[$kelas]["total"] += $jumlah;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Voter - Voting OSIS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #2c3e50;
            color: #fff;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 8px 10px;
            border-radius: 5px;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #34495e;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background: #2c3e50;
            color: #fff;
        }

        /* Pagination */
        .pagination {
            text-align: center;
            margin-bottom: 30px;
        }

        .pagination a {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 3px;
            background: #ecf0f1;
            color: #2c3e50;
            text-decoration: none;
            border-radius: 4px;
        }

        .pagination a.active {
            background: #3498db;
            color: #fff;
            font-weight: bold;
        }

        .pagination a:hover {
            background: #2980b9;
            color: #fff;
        }

        /* Summary */
        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .kelas-card {
            background: #fff;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
        }

        .progress {
            height: 8px;
            background: #ecf0f1;
            border-radius: 5px;
            overflow: hidden;
            margin: 6px 0 12px;
        }

        .progress-fill {
            height: 100%;
            background: #3498db;
            border-radius: 5px;
            width: 0;
            transition: width 0.8s ease-in-out;
        }

        .sub-fill {
            background: #2ecc71;
            height: 100%;
            border-radius: 5px;
            transition: width 0.8s ease-in-out;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="../index.php">🏠 Dashboard</a></li>
            <li><a href="../hasil-vote/result.php">📊 Hasil</a></li>
            <li><a href="../kandidat/tambah.php">➕ Tambah Kandidat</a></li>
            <li><a href="../kandidat/daftar.php">📋 Daftar Kandidat</a></li>
            <li><a href="voter.php" class="active">👥 Daftar Voter</a></li>
            <li><a href="../auth/logout.php">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Daftar Voter</h1>

        <!-- Tabel Voter -->
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Pilihan Kandidat</th>
            </tr>
            <?php if (count($voters) > 0): ?>
                <?php foreach ($voters as $i => $v): ?>
                    <tr>
                        <td><?= $offset + $i + 1 ?></td>
                        <td><?= htmlspecialchars($v['nama_voter']) ?></td>
                        <td><?= htmlspecialchars($v['kelas']) ?></td>
                        <td>Kandidat <?= htmlspecialchars($v['nomor_kandidat']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Belum ada siswa yang memilih.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <a href="?page=<?= $p ?>" class="<?= $p == $page ? 'active' : '' ?>"><?= $p ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <!-- Ringkasan Voting per Kelas -->
        <h3>Ringkasan Voting per Kelas</h3>
        <div class="kelas-grid">
            <?php foreach ($dataKelas as $kelas => $target): ?>
                <?php
                $voted = $kelasSummary[$kelas]['voted'];
                $percent = $target > 0 ? round(($voted / $target) * 100, 2) : 0;
                ?>
                <div class="kelas-card">
                    <h4><?= $kelas ?></h4>
                    <p><?= $voted ?> dari <?= $target ?> siswa (<?= $percent ?>%)</p>
                    <div class="progress">
                        <div class="progress-fill" style="width: <?= $percent ?>%;"></div>
                    </div>

                    <div class="kandidat-list">
                        <?php if (isset($hasilKandidat[$kelas])): ?>
                            <?php foreach ($hasilKandidat[$kelas]["kandidat"] as $nomor => $jumlah): ?>
                                <?php
                                $persen = $hasilKandidat[$kelas]["total"] > 0
                                    ? round(($jumlah / $hasilKandidat[$kelas]["total"]) * 100, 2)
                                    : 0;
                                ?>
                                <div class="kandidat-item">
                                    <span>Kandidat <?= $nomor ?> - <?= $jumlah ?> suara (<?= $persen ?>%)</span>
                                    <div class="progress sub-progress">
                                        <div class="sub-fill" style="width: <?= $persen ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <i>Belum ada suara di kelas ini.</i>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>