<?php
// file: index.php (halaman voting user)
// pastikan path require db sesuai struktur proyekmu
session_start();
require '../db/db.php'; // sesuaikan path jika perlu

// POST handling (server-side validation + DB ops)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kirim'])) {

    // ambil & sanitasi input
    $nama_pemilih      = isset($_POST['pemilih']) ? trim($_POST['pemilih']) : '';
    $role              = isset($_POST['role']) ? trim($_POST['role']) : 'siswa';
    // jika input 'kelas' tidak dikirim (karena disabled saat guru), kita treat sebagai empty string
    $kelas_pemilih     = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
    $kandidat_terpilih = isset($_POST['kandidat_terpilih']) ? (int)$_POST['kandidat_terpilih'] : 0;

    // basic server-side validation
    if ($nama_pemilih === '' || $kandidat_terpilih <= 0) {
        echo "<script>alert('⚠️ Nama dan pilihan kandidat wajib diisi!'); window.location.href='index.php';</script>";
        exit();
    }

    if ($role !== 'siswa' && $role !== 'guru') {
        echo "<script>alert('⚠️ Role tidak valid.'); window.location.href='index.php';</script>";
        exit();
    }

    // jika role siswa -> kelas wajib
    if ($role === 'siswa' && $kelas_pemilih === '') {
        echo "<script>alert('⚠️ Untuk role siswa, kolom kelas wajib diisi.'); window.location.href='index.php';</script>";
        exit();
    }

    // untuk kompatibilitas DB, simpan kelas kosong jika guru (atau set NULL jika kamu ubah struktur)
    $kelas_db = ($role === 'siswa') ? $kelas_pemilih : '';

    mysqli_begin_transaction($db);

    try {
        // Insert tb_voter
        $sql_voter = "INSERT INTO tb_voter (nama_voter, kelas, role, created_at) VALUES (?, ?, ?, NOW())";
        $stmt_voter = mysqli_prepare($db, $sql_voter);
        if (!$stmt_voter) throw new mysqli_sql_exception(mysqli_error($db));
        mysqli_stmt_bind_param($stmt_voter, "sss", $nama_pemilih, $kelas_db, $role);
        mysqli_stmt_execute($stmt_voter);
        $voter_id = mysqli_insert_id($db);
        mysqli_stmt_close($stmt_voter);

        // Insert tb_vote_log
        $sql_vote_log = "INSERT INTO tb_vote_log (voter_id, nomor_kandidat, created_at) VALUES (?, ?, NOW())";
        $stmt_vote_log = mysqli_prepare($db, $sql_vote_log);
        if (!$stmt_vote_log) throw new mysqli_sql_exception(mysqli_error($db));
        mysqli_stmt_bind_param($stmt_vote_log, "ii", $voter_id, $kandidat_terpilih);
        mysqli_stmt_execute($stmt_vote_log);
        mysqli_stmt_close($stmt_vote_log);

        // Update tb_vote_result (jika kamu pakai tabel hasil terpisah)
        // Jika tidak ada tabel tb_vote_result jangan jalankan ini (atau sesuaikan)
        $sql_update_result = "UPDATE tb_vote_result SET jumlah_vote = jumlah_vote + 1 WHERE nomor_kandidat = ?";
        $stmt_update_result = mysqli_prepare($db, $sql_update_result);
        if ($stmt_update_result) {
            mysqli_stmt_bind_param($stmt_update_result, "i", $kandidat_terpilih);
            mysqli_stmt_execute($stmt_update_result);
            mysqli_stmt_close($stmt_update_result);
        }

        mysqli_commit($db);

        // tampilkan modal success inline (tetap di halaman)
        echo "
            <div id='modalSuccess' class='modal' style='display:flex;'>
                <div class='modal-content'>
                    <span class='close'>&times;</span>
                    <div class='icon'>✅</div>
                    <h2>Vote Berhasil!</h2>
                    <p>Terima kasih sudah memilih. Suaramu sudah tersimpan.</p>
                    <button id='okBtn'>OK</button>
                </div>
            </div>
            <style>
            .modal { display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; }
            .modal-content { background:#fff; padding:24px; border-radius:10px; width:90%; max-width:420px; text-align:center; box-shadow:0 6px 20px rgba(0,0,0,0.15); position:relative; }
            .modal-content .close { position:absolute; right:12px; top:8px; cursor:pointer; font-size:20px; color:#666; }
            .modal-content .icon { font-size:44px; color:#2ecc71; margin-bottom:8px; }
            .modal-content button { background:#3498db; color:#fff; border:none; padding:10px 18px; border-radius:6px; cursor:pointer; }
            </style>
            <script>
            document.addEventListener('DOMContentLoaded', function(){
                const modal = document.getElementById('modalSuccess');
                const closeBtn = modal.querySelector('.close');
                const okBtn = document.getElementById('okBtn');
                modal.style.display = 'flex';
                closeBtn.onclick = okBtn.onclick = function(){ modal.style.display='none'; window.location.href='index.php'; };
                window.onclick = (e) => { if (e.target === modal) { modal.style.display='none'; window.location.href='index.php'; } };
            });
            </script>
        ";
        exit();
    } catch (mysqli_sql_exception $e) {
        mysqli_rollback($db);
        // Beri pesan yang membantu; kalau production bisa disederhanakan
        $err = addslashes($e->getMessage());
        echo "<script>alert('⚠️ Terjadi kesalahan saat memproses vote: {$err}'); window.location.href='index.php';</script>";
        exit();
    } finally {
        if ($db) mysqli_close($db);
    }
}

// ambil data kandidat
$query = mysqli_query($db, "SELECT * FROM tb_kandidat ORDER BY nomor_kandidat ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Voting Kandidat OSIS</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/slider.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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

        .wrapper {
            width: 100%;
            max-width: 1100px;
        }

        .title h1 {
            color: #2c3e50;
            margin-bottom: 18px;
        }

        .kandidat-slider {
            position: relative;
            margin-bottom: 22px;
            overflow: hidden;
        }

        .slider-wrapper {
            display: flex;
            transition: transform .35s ease;
        }

        /* styling kartu kandidat */
        .kandidat-card {
            min-width: 100%;
            padding: 12px;
            box-sizing: border-box;
        }

        .card-wrapper {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 14px;
            width: 250px;
            /* fixed width agar konsisten */
            height: 320px;
            /* biar tidak gepeng panjang */
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);

            display: flex;
            flex-direction: column;
            align-items: center;
            /* horizontal center */
            justify-content: center;
            /* vertical center */
            text-align: center;

            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }


        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card .img img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            border: 2px solid #ddd;
        }

        .card .data-user h3 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #2c3e50;
        }

        .card .data-user p {
            font-size: 14px;
            color: #555;
            margin: 2px 0;
        }


        .btn-vote {
            margin-top: 12px;
        }

        .btn-vote button {
            padding: 10px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            background: #3498db;
            color: #fff;
        }

        /* form */
        .form-user {
            background: #fff;
            padding: 18px;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .form-user label {
            display: block;
            margin-top: 10px;
            font-weight: 600;
            color: #333;
        }

        .form-user input[type="text"],
        .form-user select,
        .form-user button {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .form-user button {
            background: #2ecc71;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .small-note {
            font-size: 13px;
            color: #666;
            margin-top: 6px;
        }

        /* hide class */
        .hidden {
            display: none !important;
        }

        /* modal warning (client-side) */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 420px;
            text-align: center;
        }

        .modal-content .close {
            position: absolute;
            right: 12px;
            top: 8px;
            cursor: pointer;
            font-size: 20px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container" style="display:flex; justify-content:center; align-items:flex-start; min-height:100vh;">
        <div class="wrapper">
            <div class="title">
                <h1>Form Voting Ketua & Wakil OSIS Skalsa</h1>
            </div>
            <div class="kandidat-slider">
                <div class="slider-wrapper">
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <div class="kandidat-card" data-id="<?= htmlspecialchars($row['nomor_kandidat']); ?>">
                            <h3>Pasangan Nomor <?= htmlspecialchars($row['nomor_kandidat']); ?></h3>
                            <div class="card-wrapper">
                                <div class="card">
                                    <div class="img"><img src="../admin/uploads/<?= htmlspecialchars($row['foto_ketua']) ?>" alt="foto ketua"></div>
                                    <div class="data-user">
                                        <h3><?= htmlspecialchars($row['nama_ketua']); ?></h3>
                                        <p><?= htmlspecialchars($row['kelas_ketua']); ?></p>
                                        <p><strong>Calon Ketua OSIS Nomor <?= htmlspecialchars($row['nomor_kandidat']); ?></strong></p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="img"><img src="../admin/uploads/<?= htmlspecialchars($row['foto_wakil']) ?>" alt="foto wakil"></div>
                                    <div class="data-user">
                                        <h3><?= htmlspecialchars($row['nama_wakil']); ?></h3>
                                        <p><?= htmlspecialchars($row['kelas_wakil']); ?></p>
                                        <p><strong>Calon Wakil OSIS Nomor <?= htmlspecialchars($row['nomor_kandidat']); ?></strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-vote"><button type="button">Pilih Kandidat</button></div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <button class="slider-btn prev" aria-label="prev" style="position:absolute;left:8px;top:40%;"><i class="bi bi-chevron-left"></i></button>
                <button class="slider-btn next" aria-label="next" style="position:absolute;right:8px;top:40%;"><i class="bi bi-chevron-right"></i></button>
            </div>

            <div class="form-user">
                <div style="margin-bottom:18px;">
                    <h3>Form Pemilih</h3>
                    <p class="small-note" style="color:red;font-family:'Lucida Sans';">Wajib diisi!</p>
                </div>
                <form action="" method="post" id="formVote" novalidate>
                    <label for="pemilih">Nama Pemilih</label>
                    <input type="text" id="pemilih" name="pemilih" autocomplete="off" placeholder="Nama lengkap">

                    <label for="role">Role</label>
                    <select id="role" name="role" aria-controls="kelasWrap">
                        <option value="siswa" selected>Siswa</option>
                        <option value="guru">Guru</option>
                    </select>
                    <div class="small-note">Pilih role pemilih: jika <strong>guru</strong>, field kelas akan disembunyikan.</div>

                    <div id="kelasWrap">
                        <label for="kelas">Kelas Pemilih</label>
                        <select id="kelas" name="kelas">
                            <option value="">Pilih Kelas</option>
                            <option value="X-1">X-1</option>
                            <option value="X-2">X-2</option>
                            <option value="XI-1">XI-1</option>
                            <option value="XI-2">XI-2</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <input type="hidden" name="kandidat_terpilih" id="kandidat_terpilih">
                    <button type="submit" name="kirim">Kirim Vote</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Warning (client-side) -->
    <div id="modalWarning" class="modal" aria-hidden="true">
        <div class="modal-content">
            <span class="close" id="closeModalWarning">&times;</span>
            <h2>⚠️ Form Belum Lengkap</h2>
            <p>Harap isi semua field wajib dan pilih kandidat sebelum mengirim vote.</p>
            <button id="btnModalOk">OK</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const kandidatCards = Array.from(document.querySelectorAll(".kandidat-card"));
            const inputHidden = document.getElementById("kandidat_terpilih");
            const form = document.getElementById("formVote");
            const modalWarning = document.getElementById("modalWarning");
            const closeBtnModal = document.getElementById('closeModalWarning');
            const okModalBtn = document.getElementById('btnModalOk');

            const roleSelect = document.getElementById('role');
            const kelasWrap = document.getElementById('kelasWrap');
            const kelasSelect = document.getElementById('kelas');
            const nameInput = document.getElementById('pemilih');

            // Slider basics
            const sliderWrapper = document.querySelector('.slider-wrapper');
            const prevBtn = document.querySelector('.slider-btn.prev');
            const nextBtn = document.querySelector('.slider-btn.next');
            let currentIndex = 0;
            const slides = kandidatCards.length;

            function updateSlider() {
                sliderWrapper.style.transform = `translateX(-${currentIndex * 100}%)`;
            }
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex < slides - 1) ? currentIndex + 1 : 0;
                updateSlider();
            });
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides - 1;
                updateSlider();
            });

            // kandidat selection handling
            let selectedCard = null;
            kandidatCards.forEach(card => {
                const btn = card.querySelector('.btn-vote button');
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    // toggle selection
                    if (selectedCard === card) {
                        // unselect
                        card.classList.remove('active');
                        inputHidden.value = '';
                        selectedCard = null;
                        kandidatCards.forEach(c => {
                            c.classList.remove('disabled');
                            c.querySelector('.btn-vote button').textContent = 'Pilih Kandidat';
                        });
                        return;
                    }
                    if (selectedCard) return; // sudah ada pilihan
                    // select this
                    card.classList.add('active');
                    inputHidden.value = card.dataset.id;
                    selectedCard = card;
                    btn.textContent = 'Batal Pilih';
                    kandidatCards.forEach(c => {
                        if (c !== card) {
                            c.classList.add('disabled');
                            c.querySelector('.btn-vote button').textContent = 'Pilih Kandidat';
                        }
                    });
                });

                // klik pada card juga memilih (opsional)
                card.addEventListener('click', () => {
                    if (!card.classList.contains('disabled')) card.querySelector('.btn-vote button').click();
                });
            });

            // Show/hide kelas based on role
            function updateKelasVisibility() {
                if (roleSelect.value === 'guru') {
                    // hide kelas completely and disable the select (so it won't be submitted)
                    kelasWrap.classList.add('hidden');
                    kelasSelect.value = '';
                    kelasSelect.disabled = true;
                    kelasSelect.setAttribute('aria-hidden', 'true');
                } else {
                    kelasWrap.classList.remove('hidden');
                    kelasSelect.disabled = false;
                    kelasSelect.removeAttribute('aria-hidden');
                }
            }
            // init
            updateKelasVisibility();
            roleSelect.addEventListener('change', updateKelasVisibility);

            // Modal handlers
            function openModal() {
                modalWarning.style.display = 'flex';
                modalWarning.setAttribute('aria-hidden', 'false');
            }

            function closeModal() {
                modalWarning.style.display = 'none';
                modalWarning.setAttribute('aria-hidden', 'true');
            }
            closeBtnModal.addEventListener('click', closeModal);
            okModalBtn.addEventListener('click', closeModal);
            window.addEventListener('click', (e) => {
                if (e.target === modalWarning) closeModal();
            });

            // Form submit validation
            form.addEventListener('submit', function(e) {
                const nama = nameInput.value.trim();
                const role = roleSelect.value;
                const kelas = kelasSelect.value;
                const kandidat = inputHidden.value;

                // validate name & candidate first
                if (!nama || !kandidat) {
                    e.preventDefault();
                    openModal();
                    return;
                }

                // only enforce kelas when role is siswa
                if (role === 'siswa' && (!kelas || kelas === '')) {
                    e.preventDefault();
                    alert('⚠️ Untuk role siswa, silakan pilih kelas terlebih dahulu.');
                    return;
                }

                // ok -> let the form submit to server
            });

            // Accessibility: allow keyboard selection for cards (optional)
            kandidatCards.forEach((card, idx) => {
                card.setAttribute('tabindex', '0');
                card.addEventListener('keydown', (ev) => {
                    if (ev.key === 'Enter' || ev.key === ' ') {
                        ev.preventDefault();
                        card.click();
                    }
                });
            });
        });
    </script>
</body>

</html>