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
            width: 80px;
            height: 80px;
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
    <h1>📋 Daftar Kandidat Ketua & Wakil OSIS</h1>

    <div class="kandidat-container">
        <!-- Kandidat 1 -->
        <div class="kandidat-card">
            <div class="foto-wrapper">
                <img src="assets/img/kandidat/ketua1.jpg" alt="Ketua A">
                <img src="assets/img/kandidat/wakil1.jpg" alt="Wakil A">
            </div>
            <div class="kandidat-info">
                <h3>Ketua A & Wakil A</h3>
                <p>Kelas XI IPA 1</p>
            </div>
        </div>

        <!-- Kandidat 2 -->
        <div class="kandidat-card">
            <div class="foto-wrapper">
                <img src="assets/img/kandidat/ketua2.jpg" alt="Ketua B">
                <img src="assets/img/kandidat/wakil2.jpg" alt="Wakil B">
            </div>
            <div class="kandidat-info">
                <h3>Ketua B & Wakil B</h3>
                <p>Kelas XI IPS 2</p>
            </div>
        </div>

        <!-- Kandidat 3 -->
        <div class="kandidat-card">
            <div class="foto-wrapper">
                <img src="assets/img/kandidat/ketua3.jpg" alt="Ketua C">
                <img src="assets/img/kandidat/wakil3.jpg" alt="Wakil C">
            </div>
            <div class="kandidat-info">
                <h3>Ketua C & Wakil C</h3>
                <p>Kelas XI IPA 3</p>
            </div>
        </div>
    </div>
</body>

</html>