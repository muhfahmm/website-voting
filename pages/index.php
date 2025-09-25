<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Kandidat OSIS</title>
    <link rel="stylesheet" href="assets/css/index.css">
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
                <h1>Form Voting Ketua & Wakil OSIS Skalsa</h1>
            </div>
            <!-- Daftar Kandidat -->
            <div class="kandidat">
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <div class="kandidat-card" data-id="<?= $row['nomor_kandidat']; ?>">
                        <h3>Pasangan Nomor <?= $row['nomor_kandidat']; ?></h3>
                        <div class="card-wrapper">
                            <!-- Ketua -->
                            <div class="card">
                                <div class="img">
                                    <img src="../admin/uploads/<?= $row['foto_ketua'] ?>">
                                </div>
                                <div class="data-user">
                                    <h3><?= $row['nama_ketua']; ?></h3>
                                    <p>Kelas: X-1</p>
                                    <p><strong>Calon Ketua OSIS Nomor <?= $row['nomor_kandidat']; ?></strong></p>
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
                                    <p><strong>Calon Wakil OSIS Nomor <?= $row['nomor_kandidat']; ?></strong></p>
                                </div>
                            </div>
                        </div>
                        <!-- Button Pilih -->
                        <div class="btn-vote">
                            <button>Pilih Kandidat</button>
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

                    <!-- Input tersembunyi untuk kandidat yang dipilih -->
                    <input type="hidden" name="kandidat_terpilih" id="kandidat_terpilih">

                    <button type="submit" name="kirim">✅ Kirim Vote</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Tambahan style kandidat non-aktif */
        .kandidat-card.disabled {
            background: #d9d9d9ff !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }
    </style>

    <script>
        const kandidatCards = document.querySelectorAll(".kandidat-card");
        const inputHidden = document.getElementById("kandidat_terpilih");

        let selectedCard = null; // menyimpan kandidat yang dipilih

        kandidatCards.forEach(card => {
            card.addEventListener("click", () => {
                // jika klik kandidat yang sama lagi → batalkan
                if (selectedCard === card) {
                    card.classList.remove("active");
                    inputHidden.value = "";
                    selectedCard = null;

                    // aktifkan kembali semua kandidat
                    kandidatCards.forEach(c => {
                        c.classList.remove("disabled");
                    });
                    return;
                }

                // jika sudah ada pilihan → abaikan klik kandidat lain
                if (selectedCard) {
                    return;
                }

                // kalau belum ada yang dipilih → pilih kandidat ini
                card.classList.add("active");
                inputHidden.value = card.dataset.id;
                selectedCard = card;

                // disable kandidat lain
                kandidatCards.forEach(c => {
                    if (c !== card) {
                        c.classList.add("disabled");
                    }
                });
            });
        });
    </script>


</body>

</html>