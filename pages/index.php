<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Kandidat OSIS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 1000px;
        }

        .wrapper {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .kandidat {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            flex: 1;
            background: #fafafa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
        }

        .card-wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-wrapper:hover {
            transform: translateY(-3px) scale(1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }


        .card .img img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2c3e50;
            margin-bottom: 15px;
        }

        .card h3 {
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .card p {
            margin-bottom: 5px;
            color: #555;
        }

        .form-user {
            margin-top: 30px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-user label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }

        .form-user input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .form-user button {
            width: 100%;
            padding: 12px;
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .form-user button:hover {
            background: #34495e;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .title h1 {
            color: #2c3e50;
            font-size: 26px;
        }

        .title p {
            color: #555;
            margin-top: 5px;
        }

        .card-wrapper {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 15px;
        }

        .btn-vote {
            text-align: center;
        }

        .btn-vote button {
            padding: 10px 20px;
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-vote button:hover {
            background: #34495e;
        }
    </style>
</head>

<body>
    <?php
    require '../db/db.php';
    $query = mysqli_query($db, "SELECT * FROM tb_kandidat");
    ?>
    <div class="container">
        <div class="wrapper">
            <div class="title">
                <h1>📊 Voting Ketua & Wakil OSIS</h1>
                <p>Pilih kandidat favoritmu dengan bijak ✨</p>
            </div>
            <div class="kandidat">
                <?php
                while ($row = mysqli_fetch_assoc($query)) : ?>
                    <div class="kandidat-card">
                        <div class="foto-wrapper">
                            <div class="card-wrapper">
                                <!-- Ketua -->
                                <div class="card">
                                    <div class="img">
                                        <img src="../admin/uploads/<?= $row['foto_ketua'] ?>">
                                    </div>
                                    <div class="data-user">
                                        <h3><?= $row['nama_ketua']; ?></h3>
                                        <p>Kelas: X-1</p>
                                        <p><strong>Calon Ketua</strong></p>
                                    </div>
                                </div>

                                <!-- Wakil -->
                                <div class="card">
                                    <div class="img">
                                        <img src="../admin/uploads/<?= $row['foto_wakil'] ?>">
                                    </div>
                                    <div class="data-user">
                                        <h3><?= $row['nama_wakil']; ?></h3>
                                        <p>Kelas: X-2</p>
                                        <p><strong>Calon Wakil</strong></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Button di bawah card -->
                            <div class="btn-vote">
                                <button>Pilih Kandidat</button>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </div>

            <!-- Form Pemilih -->
            <div class="form-user">
                <h3>📝 Form Pemilih</h3>
                <form action="proses_vote.php" method="post">
                    <label for="pemilih">Nama Pemilih</label>
                    <input type="text" id="pemilih" name="pemilih" required>

                    <label for="kelas">Kelas Pemilih</label>
                    <input type="text" id="kelas" name="kelas" required>

                    <button type="submit" name="kirim">✅ Kirim Vote</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>