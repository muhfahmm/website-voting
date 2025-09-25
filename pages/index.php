<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Kandidat OSIS</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/modal.css">
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

    // Handle vote submission logic (unchanged from your code)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kirim'])) {

        $nama_pemilih = $_POST['pemilih'];
        $kelas_pemilih = $_POST['kelas'];
        $kandidat_terpilih = $_POST['kandidat_terpilih'];

        if (empty($nama_pemilih) || empty($kelas_pemilih) || empty($kandidat_terpilih)) {
            echo "<script>alert('⚠️ Semua field wajib diisi!'); window.location.href='index.php';</script>";
            exit();
        }

        mysqli_begin_transaction($db);

        try {
            $sql_voter = "INSERT INTO tb_voter (nama_voter, kelas) VALUES (?, ?)";
            $stmt_voter = mysqli_prepare($db, $sql_voter);
            mysqli_stmt_bind_param($stmt_voter, "ss", $nama_pemilih, $kelas_pemilih);
            mysqli_stmt_execute($stmt_voter);

            $voter_id = mysqli_insert_id($db);
            mysqli_stmt_close($stmt_voter);

            $sql_vote_log = "INSERT INTO tb_vote_log (voter_id, nomor_kandidat) VALUES (?, ?)";
            $stmt_vote_log = mysqli_prepare($db, $sql_vote_log);
            mysqli_stmt_bind_param($stmt_vote_log, "ii", $voter_id, $kandidat_terpilih);
            mysqli_stmt_execute($stmt_vote_log);
            mysqli_stmt_close($stmt_vote_log);

            $sql_update_result = "UPDATE tb_vote_result SET jumlah_vote = jumlah_vote + 1 WHERE nomor_kandidat = ?";
            $stmt_update_result = mysqli_prepare($db, $sql_update_result);
            mysqli_stmt_bind_param($stmt_update_result, "i", $kandidat_terpilih);
            mysqli_stmt_execute($stmt_update_result);
            mysqli_stmt_close($stmt_update_result);

            mysqli_commit($db);

            echo "<script>alert('✅ Vote berhasil disimpan. Terima kasih sudah memilih!'); window.location.href='index.php';</script>";
        } catch (mysqli_sql_exception $e) {
            mysqli_rollback($db);

            $errorMessage = "Terjadi kesalahan saat memproses vote. Silakan coba lagi.";
            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'voter_id') !== false) {
                $errorMessage = "Anda hanya bisa melakukan vote sekali.";
            }
            echo "<script>alert('⚠️ " . $errorMessage . "'); window.location.href='index.php';</script>";
        } finally {
            mysqli_close($db);
            exit();
        }
    }

    // PHP to fetch candidates for the display section (unchanged)
    $query = mysqli_query($db, "SELECT * FROM tb_kandidat");
    ?>
    <div class="container">
        <div class="wrapper">
            <div class="title">
                <h1>Form Voting Ketua & Wakil OSIS Skalsa</h1>
            </div>
            <div class="kandidat">
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <div class="kandidat-card" data-id="<?= $row['nomor_kandidat']; ?>">
                        <h3>Pasangan Nomor <?= $row['nomor_kandidat']; ?></h3>
                        <div class="card-wrapper">
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
                        <div class="btn-vote">
                            <button>Pilih Kandidat</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            
            <div class="form-user">
                <h3>📝 Form Pemilih</h3>
                <form action="" method="post">
                    <label for="pemilih">Nama Pemilih</label>
                    <input type="text" id="pemilih" name="pemilih">
                    <label for="kelas">Kelas Pemilih</label>
                    <select id="kelas" name="kelas">
                        <option value="">Pilih Kelas</option>
                        <option value="X-1">X-1</option>
                        <option value="X-2">X-2</option>
                        <option value="XI-1">XI-1</option>
                        <option value="XI-2">XI-2</option>
                        <option value="XII">XII</option>
                    </select>

                    <input type="hidden" name="kandidat_terpilih" id="kandidat_terpilih">

                    <button type="submit" name="kirim">✅ Kirim Vote</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Warning -->
    <div id="modalWarning" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>⚠️ Form Belum Lengkap</h2>
            <p>Harap isi semua field dan pilih kandidat sebelum mengirim vote.</p>
            <button id="closeBtn">OK</button>
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
    <script src="assets/js/modal.js"></script>
</body>

</html>