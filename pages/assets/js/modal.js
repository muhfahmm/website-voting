const formVote = document.querySelector("form");
        const modal = document.getElementById("modalWarning");
        const closeModal = document.querySelector(".modal .close");
        const closeBtn = document.getElementById("closeBtn");

        formVote.addEventListener("submit", function(e) {
            const nama = document.getElementById("pemilih").value.trim();
            const kelas = document.getElementById("kelas").value;
            const kandidat = document.getElementById("kandidat_terpilih").value;

            if (nama === "" || kelas === "" || kandidat === "") {
                e.preventDefault(); // stop submit
                modal.style.display = "flex"; // tampilkan modal
            }
        });

        // Tutup modal
        closeModal.onclick = () => modal.style.display = "none";
        closeBtn.onclick = () => modal.style.display = "none";

        // Tutup modal kalau klik di luar area modal
        window.onclick = (e) => {
            if (e.target === modal) modal.style.display = "none";
        };