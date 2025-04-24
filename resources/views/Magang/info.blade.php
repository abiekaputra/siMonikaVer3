<!-- Modal Info Linimasa Magang -->
<div class="modal fade" id="modalInfoMagang" tabindex="-1" aria-labelledby="modalInfoMagangLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInfoMagangLabel">Detail Linimasa Magang</h5>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <p><strong>Universitas:</strong> <span id="infoUniversitas"></span></p>
                        <p><strong>Jumlah Anak:</strong> <span id="infoJumlahAnak"></span></p>
                        <p><strong>Mulai:</strong> <span id="infoMulai"></span></p>
                        <p><strong>Tenggat:</strong> <span id="infoTenggat"></span></p>
                        <p><strong>Deskripsi:</strong> <span id="infoDeskripsi"></span></p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-warning btn-sm btn-edit" 
                    data-id="{{ $item->id }}" 
                    data-universitas="{{ $item->universitas }}" 
                    data-jumlah_anak="{{ $item->jumlah_anak }}" 
                    data-tanggal_masuk="{{ $item->tanggal_masuk }}" 
                    data-tanggal_keluar="{{ $item->tanggal_keluar }}" 
                    data-deskripsi="{{ $item->deskripsi }}" 
                    data-bs-toggle="modal" 
                    data-bs-target="#magangEditModal">
                    <i class="bi bi-pencil-square"></i>
                </button>

                <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>
</div>
