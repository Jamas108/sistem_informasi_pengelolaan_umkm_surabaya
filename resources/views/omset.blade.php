<div class="tab-pane fade" id="omset" role="tabpanel" aria-labelledby="omset-tab">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="m-0 font-weight-bold">Data Omset UMKM</h5>
        </div>
        <div class="card-body p-4">
            <!-- Form for adding omset data -->
            <div id="omset-form-container" class="mb-4">
                <!-- UMKM Selection -->
                <div class="row mb-3">
                    <label for="umkm_id"
                        class="col-sm-3 col-form-label font-weight-bold">Pilih UMKM</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="umkm_id" name="omset[umkm_id]">
                            <option value="">-- Pilih UMKM --</option>
                            @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                <option value="{{ $umkm->id }}">
                                    {{ $umkm->nama_usaha }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Jangka Waktu -->
                <div class="row mb-3">
                    <label for="jangka_waktu"
                        class="col-sm-3 col-form-label font-weight-bold">Jangka
                        Waktu</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="jangka_waktu"
                            name="omset[jangka_waktu]">
                    </div>
                </div>

                <!-- Nilai Omset -->
                <div class="row mb-3">
                    <label for="omset"
                        class="col-sm-3 col-form-label font-weight-bold">Nilai
                        Omset</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>
                            <input type="text" class="form-control currency-input"
                                id="total_omset" name="omset[total_omset]" placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="row mb-3">
                    <label for="keterangan"
                        class="col-sm-3 col-form-label font-weight-bold">Keterangan</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="keterangan"
                            name="omset[keterangan]">
                            <option value="">-- Pilih Status --</option>
                            <option value="AKTIF">AKTIF</option>
                            <option value="TIDAK AKTIF">TIDAK AKTIF</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-md btn-success"
                            id="tambah-data-omset">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Data Omset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table of existing omset data -->
            <div class="mt-5">
                <div class="card border-left-primary shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-omset"
                                width="100%" cellspacing="0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center" width="5%">NO</th>
                                        <th width="15%">UMKM</th>
                                        <th width="15%">Jangka Waktu</th>
                                        <th width="20%">Nilai Omset</th>
                                        <th width="15%">Keterangan</th>

                                        <th class="text-center" width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($omsetData) && count($omsetData) > 0)
                                        @foreach ($omsetData as $index => $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $index + 1 }}</td>
                                                <td>{{ $item->dataUmkm->nama_usaha }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->jangka_waktu)) }}
                                                </td>
                                                <td>Rp.
                                                    {{ number_format($item->omset, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @if ($item->keterangan == 'AKTIF')
                                                        <span
                                                            class="badge badge-success">AKTIF</span>
                                                    @elseif ($item->keterangan == 'TIDAK AKTIF')
                                                        <span class="badge badge-danger">TIDAK
                                                            AKTIF</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-warning btn-sm edit-omset"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">Belum
                                                ada data omset</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

             <!-- Modal for editing omset -->
             <div class="modal fade" id="editOmsetModal" tabindex="-1" role="dialog"
             aria-labelledby="editOmsetModalLabel" aria-hidden="true">
             <div class="modal-dialog modal-lg">
                 <div class="modal-content">
                     <div class="modal-header bg-gradient-primary text-white">
                         <h5 class="modal-title" id="editOmsetModalLabel">Edit Data
                             Omset</h5>
                         <button type="button" class="close text-white"
                             data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                     </div>
                     <div class="modal-body">
                         <form id="edit-omset-form">
                             <input type="hidden" id="edit_omset_id" name="id">

                             <div class="form-group row mb-3">
                                 <label for="edit_umkm_id"
                                     class="col-sm-3 col-form-label">UMKM</label>
                                 <div class="col-sm-9">
                                     <select class="form-control" id="edit_umkm_id"
                                         name="umkm_id">
                                         @foreach ($pelakuUmkm->dataUmkm as $umkm)
                                             <option value="{{ $umkm->id }}">
                                                 {{ $umkm->nama_usaha }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>

                             <div class="form-group row mb-3">
                                 <label for="edit_jangka_waktu"
                                     class="col-sm-3 col-form-label">Jangka
                                     Waktu</label>
                                 <div class="col-sm-9">
                                     <input type="date" class="form-control"
                                         id="edit_jangka_waktu" name="jangka_waktu">
                                 </div>
                             </div>

                             <div class="form-group row mb-3">
                                 <label for="edit_omset"
                                     class="col-sm-3 col-form-label">Nilai Omset</label>
                                 <div class="col-sm-9">
                                     <div class="input-group">
                                         <div class="input-group-prepend">
                                             <span class="input-group-text">Rp.</span>
                                         </div>
                                         <input type="text"
                                             class="form-control currency-input"
                                             id="edit_omset" name="omset">
                                     </div>
                                 </div>
                             </div>

                             <div class="form-group row mb-3">
                                 <label for="edit_keterangan"
                                     class="col-sm-3 col-form-label">Keterangan</label>
                                 <div class="col-sm-9">
                                     <select class="form-control" id="edit_keterangan"
                                         name="keterangan">
                                         <option value="AKTIF">AKTIF</option>
                                         <option value="TIDAK AKTIF">TIDAK AKTIF
                                         </option>
                                     </select>
                                 </div>
                             </div>
                         </form>
                     </div>

                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary"
                             data-dismiss="modal">Batal</button>
                         <button type="button" class="btn btn-primary"
                             id="save-edit-omset">Simpan Perubahan</button>
                     </div>
                 </div>
