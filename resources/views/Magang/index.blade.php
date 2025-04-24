<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Magang - siMonika</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Toastr & SweetAlert2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vis-timeline/7.4.6/vis-timeline-graph2d.min.js"></script>

    <!-- Vis.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/vis-timeline/7.4.6/vis-timeline-graph2d.min.css"
        rel="stylesheet">

    <style>
        .zoom-controls {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .zoom-btn {
            width: 30px;
            height: 30px;
            font-size: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    @include('templates.sidebar')

    <div class="main-content p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-0">Magang</h2>
                <p class="text-muted">Menampilkan data magang yang terdaftar</p>
            </div>
            <div class="button-action">
                @if ($magang->isNotEmpty())
                    <button id="toggleView" class="btn btn-secondary">Tampilkan Tabel</button>
                @endif
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#magangCreateModal">
                    <i class="bi bi-plus-lg"></i> Tambah Magang
                </button>
            </div>
        </div>

        @if ($magang->isEmpty())
            <div class="alert alert-warning text-center">
                <i class="alert alert-warning text-center"></i> Belum ada data magang.
            </div>
        @else
            <div id="timelineContainer" style="position: relative;">
                <div id="timeline"></div>
                <div class="zoom-controls">
                    <button id="zoomIn" class="btn btn-info zoom-btn"><i class="bi bi-plus-lg"></i></button>
                    <button id="zoomOut" class="btn btn-info zoom-btn"><i class="bi bi-dash-lg"></i></button>
                </div>
            </div>

            <div id="tableContainer" class="d-none">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Universitas</th>
                            <th>Jumlah Anak Magang</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($magang as $item)
                            <tr>
                                <td>{{ $item->universitas }}</td>
                                <td>{{ $item->jumlah_anak }}</td>
                                <td>{{ $item->tanggal_masuk }}</td>
                                <td>{{ $item->tanggal_keluar }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btn-edit" data-id="{{ $item->id }}"
                                        data-universitas="{{ $item->universitas }}" data-jumlah_anak="{{ $item->jumlah_anak }}"
                                        data-tanggal_masuk="{{ $item->tanggal_masuk }}"
                                        data-tanggal_keluar="{{ $item->tanggal_keluar }}"
                                        data-deskripsi="{{ $item->deskripsi }}" data-bs-toggle="modal"
                                        data-bs-target="#magangEditModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <form id="delete-form-{{ $item->id }}" action="{{ route('magang.destroy', $item->id) }}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @include('magang.create')
    @include('magang.edit')
    @include('magang.info')
</body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let toggleButton = document.getElementById("toggleView");
        if (toggleButton) {
            toggleButton.addEventListener("click", function () {
                document.getElementById("tableContainer").classList.toggle("d-none");
                document.getElementById("timelineContainer").classList.toggle("d-none");
                this.textContent = this.textContent.includes("Tabel") ? "Tampilkan Linimasa" : "Tampilkan Tabel";
            });
        }

        let container = document.getElementById("timeline");
        let zoomStep = 0.5;

        document.getElementById('zoomIn').addEventListener('click', function () {
            let currentRange = timeline.getWindow();
            let start = currentRange.start.valueOf();
            let end = currentRange.end.valueOf();
            let interval = end - start;
            let newInterval = interval * (1 - zoomStep);
            let newStart = start + (interval - newInterval) / 2;
            let newEnd = end - (interval - newInterval) / 2;
            timeline.setWindow(newStart, newEnd);
        });

        document.getElementById('zoomOut').addEventListener('click', function () {
            let currentRange = timeline.getWindow();
            let start = currentRange.start.valueOf();
            let end = currentRange.end.valueOf();
            let interval = end - start;
            let newInterval = interval * (1 + zoomStep);
            let newStart = start - (newInterval - interval) / 2;
            let newEnd = end + (newInterval - interval) / 2;
            timeline.setWindow(newStart, newEnd);
        });

        function getItems() {
            return new vis.DataSet([
                @foreach ($magang as $item)
                                    {
                        id: {{ $item->id }},
                        content: "{{ $item->universitas }}",
                        start: "{{ $item->tanggal_masuk }}",
                        end: "{{ $item->tanggal_keluar }}",
                        deskripsi: "{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}",
                        universitas: "{{ $item->universitas }}",
                        jumlah_anak: "{{ $item->jumlah_anak }}",
                        style: "background-color: green; color: white;"
                    },
                @endforeach
        ]);
        }

        let options = {
            stack: false,
            showCurrentTime: true,
            zoomable: true,
            orientation: { axis: "top" },
            margin: {
                item: 10,
                axis: 10
            }
        };

        let items = getItems();
        let timeline = new vis.Timeline(container, items, options);

        // Info Modal
        timeline.on("select", function (props) {
            if (props.items.length > 0) {
                let item = items.get(props.items[0]);

                $("#infoUniversitas").text(item.universitas);
                $("#infoJumlahAnak").text(item.jumlah_anak);
                $("#infoMulai").text(item.start);
                $("#infoTenggat").text(item.end);
                $("#infoDeskripsi").text(item.deskripsi);

                let btnEdit = document.querySelector("#modalInfoMagang .btn-edit");
                btnEdit.setAttribute("data-id", item.id);
                btnEdit.setAttribute("data-universitas", item.universitas);
                btnEdit.setAttribute("data-jumlah_anak", item.jumlah_anak);
                btnEdit.setAttribute("data-mulai", item.start);
                btnEdit.setAttribute("data-tenggat", item.end);
                btnEdit.setAttribute("data-deskripsi", item.deskripsi || "");

                let btnDelete = document.querySelector("#modalInfoMagang .btn-delete");
                btnDelete.setAttribute("data-id", item.id);

                $("#modalInfoMagang").modal("show");
            }
        });

        // Event listener untuk tombol Edit di modal Info
        document.querySelectorAll(".btn-edit").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");
                let universitas = this.getAttribute("data-universitas");
                let jumlahAnak = this.getAttribute("data-jumlah_anak");
                let mulai = this.getAttribute("data-mulai");
                let tenggat = this.getAttribute("data-tenggat");
                let deskripsi = this.getAttribute("data-deskripsi");

                // Mengisi form edit dengan data yang dipilih
                document.getElementById("edit_magang_id").value = id;
                document.getElementById("edit_universitas").value = universitas;
                document.getElementById("edit_jumlah_anak").value = jumlahAnak;
                document.getElementById("edit_mulai").value = mulai;
                document.getElementById("edit_tenggat").value = tenggat;
                document.getElementById("edit_deskripsi").value = deskripsi;

                // Menampilkan modal edit
                $("#magangEditModal").modal("show");
            });
        });

        // Validasi Tanggal
        let mulaiInput = document.getElementById("mulai");
        let tenggatInput = document.getElementById("tenggat");

        function validateDateInput() {
            let mulai = new Date(mulaiInput.value);
            let tenggat = new Date(tenggatInput.value);

            if (mulai > tenggat) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Input',
                    text: 'Tanggal mulai tidak boleh lebih besar dari tenggat!',
                });

                mulaiInput.value = "";
                return false;
            }
            return true;
        }

        mulaiInput.addEventListener("change", validateDateInput);
        tenggatInput.addEventListener("change", validateDateInput);

        // Submit Edit Linimasa
        let editForm = document.getElementById("editMagangForm");
        if (editForm) {
            editForm.addEventListener("submit", function (event) {
                event.preventDefault();

                if (!validateDateInput()) return;

                let formData = new FormData(editForm);
                let id = document.getElementById("edit_magang_id").value;

                fetch("{{ url(path: 'magang') }}/" + id, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let modalElement = document.getElementById("magangEditModal");
                            let modalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) {
                                modalInstance.hide();
                            }

                            document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());

                            Swal.fire({
                                icon: "success",
                                title: "Berhasil!",
                                text: "Data Magang berhasil diperbarui!",
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Gagal!",
                                text: data.message || "Terjadi kesalahan saat memperbarui data.",
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Gagal memperbarui data. Coba lagi!",
                        });
                    });
            });
        }

        // Pop Up Hapus
        document.querySelectorAll(".btn-delete").forEach(button => {
            button.addEventListener("click", function () {
                let id = this.getAttribute("data-id");

                Swal.fire({
                    title: "Yakin ingin menghapus?",
                    text: "Data magang yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('magang') }}/${id}`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                "X-HTTP-Method-Override": "DELETE"
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Berhasil!",
                                        text: "Data Magang berhasil dihapus!",
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Gagal!",
                                        text: "Terjadi kesalahan saat menghapus data.",
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Gagal menghapus data. Coba lagi!",
                                });
                            });
                    }
                });
            });
        });
    });
</script>

</html>