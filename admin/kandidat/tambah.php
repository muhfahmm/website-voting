<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Kandidat - Voting OSIS</title>
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

        .card {
            background: #fff;
            padding: 20px;
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }

        form label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #2c3e50;
        }

        form input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        form button {
            width: 100%;
            padding: 10px;
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            transition: 0.3s;
        }

        form button:hover {
            background: #34495e;
        }
    </style>
</head>

<body>
    <!-- Menu Tambah Kandidat -->
    <section class="card">
        <h2>➕ Tambah Kandidat</h2>
        <form action="proses_tambah.php" method="post" enctype="multipart/form-data">
            <label for="nomor_kandidat">Nomor Kandidat</label>
            <input type="number" id="nomor_kandidat" name="nomor_kandidat" placeholder="Masukkan nomor kandidat..." required>

            <label for="nama_ketua">Nama Ketua</label>
            <input type="text" id="nama_ketua" name="nama_ketua" placeholder="Masukkan nama ketua..." required>

            <label for="kelas_ketua">Kelas Ketua</label>
            <input type="text" id="kelas_ketua" name="kelas_ketua" placeholder="Masukkan kelas ketua..." required>

            <label for="foto_ketua">Foto Ketua</label>
            <input type="file" id="foto_ketua" name="foto_ketua" required>

            <label for="nama_wakil">Nama Wakil</label>
            <input type="text" id="nama_wakil" name="nama_wakil" placeholder="Masukkan nama wakil..." required>

            <label for="kelas_wakil">Kelas Wakil</label>
            <input type="text" id="kelas_wakil" name="kelas_wakil" placeholder="Masukkan kelas wakil..." required>

            <label for="foto_wakil">Foto Wakil</label>
            <input type="file" id="foto_wakil" name="foto_wakil" required>

            <button type="submit">Simpan</button>
        </form>
    </section>
</body>

</html>