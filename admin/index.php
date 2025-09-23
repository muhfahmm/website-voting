<?php
session_start();
require '../db/db.php';

// if (!isset($_SESSION['admin'])) {
//     header("Location: ./auth/register.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Voting OSIS</title>
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
        }

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
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background: #34495e;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        header {
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table thead {
            background: #2c3e50;
            color: #fff;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* Diagram batang */
        .bar-chart {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .bar {
            display: flex;
            align-items: center;
            margin: 10px 0;
        }

        .bar-label {
            width: 200px;
            font-weight: bold;
        }

        .bar-fill {
            height: 25px;
            background: #3498db;
            text-align: right;
            color: #fff;
            line-height: 25px;
            padding-right: 8px;
            border-radius: 5px;
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
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="#">🏠 Dashboard</a></li>
            <li><a href="kandidat/tambah.php">➕ Tambah Kandidat</a></li>
            <li><a href="kandidat/daftar.php">📋 Daftar Kandidat</a></li>
            <li><a href="#">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Konten Utama -->
    <div class="main-content">
        <header>
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, Admin 👋</p>
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
                    <tr>
                        <td>1</td>
                        <td>Ketua A & Wakil A</td>
                        <td>120</td>
                        <td>40%</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ketua B & Wakil B</td>
                        <td>90</td>
                        <td>30%</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Ketua C & Wakil C</td>
                        <td>90</td>
                        <td>30%</td>
                    </tr>
                </tbody>
            </table>

            <!-- Diagram batang manual -->
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