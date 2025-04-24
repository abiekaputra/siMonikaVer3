<!-- Modal Tambah Linimasa Magang -->
<div class="modal fade" id="magangCreateModal" tabindex="-1" aria-labelledby="magangCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="magangForm" method="POST" action="{{ route('magang.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="magangCreateModalLabel">Tambah Magang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nama Universitas -->
                    <div class="mb-3">
                        <label for="universitas" class="form-label">Nama Universitas</label>
                        <input type="text" class="form-control" id="universitas" name="universitas" required>
                    </div>

                    <!-- Jumlah Anak Magang -->
                    <div class="mb-3">
                        <label for="jumlah_anak" class="form-label">Jumlah Anak Magang</label>
                        <input type="number" class="form-control" id="jumlah_anak" name="jumlah_anak" required>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                    </div>

                    <!-- Tanggal Keluar -->
                    <div class="mb-3">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" required>
                    </div>

                    <!-- Deskripsi (Opsional) -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
