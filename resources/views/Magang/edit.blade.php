<!-- Modal Edit Linimasa Magang -->
<div class="modal fade" id="magangEditModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Linimasa Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editMagangForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_magang_id">
                    
                    <!-- Universitas -->
                    <div class="mb-3">
                        <label for="edit_universitas" class="form-label">Nama Universitas:</label>
                        <input type="text" id="edit_universitas" name="universitas" class="form-control" required>
                    </div>

                    <!-- Jumlah Anak Magang -->
                    <div class="mb-3">
                        <label for="edit_jumlah_anak" class="form-label">Jumlah Anak Magang:</label>
                        <input type="number" id="edit_jumlah_anak" name="jumlah_anak" class="form-control" required>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="mb-3">
                        <label for="edit_tanggal_masuk" class="form-label">Tanggal Masuk:</label>
                        <input type="date" id="edit_tanggal_masuk" name="tanggal_masuk" class="form-control" required>
                    </div>

                    <!-- Tanggal Keluar -->
                    <div class="mb-3">
                        <label for="edit_tanggal_keluar" class="form-label">Tanggal Keluar:</label>
                        <input type="date" id="edit_tanggal_keluar" name="tanggal_keluar" class="form-control" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi:</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".btn-edit").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let universitas = this.getAttribute("data-universitas");
                let jumlah_anak = this.getAttribute("data-jumlah_anak");
                let tanggal_masuk = this.getAttribute("data-tanggal_masuk");
                let tanggal_keluar = this.getAttribute("data-tanggal_keluar");
                let deskripsi = this.getAttribute("data-deskripsi");

                editMagang(id, universitas, jumlah_anak, tanggal_masuk, tanggal_keluar, deskripsi);
            });
        });

        let magangEditModal = document.getElementById("magangEditModal");
        magangEditModal.addEventListener("hidden.bs.modal", function () {
            let editForm = document.getElementById("editMagangForm");
            if (editForm) {
                editForm.reset();
            }
            document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
            document.body.classList.remove("modal-open");
        });
    });

    function editMagang(id, universitas, jumlah_anak, tanggal_masuk, tanggal_keluar, deskripsi) {
        document.getElementById("edit_magang_id").value = id;
        document.getElementById("edit_universitas").value = universitas;
        document.getElementById("edit_jumlah_anak").value = jumlah_anak;
        document.getElementById("edit_tanggal_masuk").value = tanggal_masuk;
        document.getElementById("edit_tanggal_keluar").value = tanggal_keluar;
        document.getElementById("edit_deskripsi").value = deskripsi;

        let form = document.getElementById("editMagangForm");
        if (form) {
            form.action = "{{ url('magang') }}/" + id;
        }

        new bootstrap.Modal(document.getElementById("magangEditModal")).show();
    }
</script>
