<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Data Absen

                    </div>
                </div>
                <div class="page-title-actions">
                    <?php if ($this->data['is_can_create']) { ?>
                        <button class="btn-shadow mr-3 btn btn-success create-button">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus fa-w-20"></i>
                            </span> Tambah
                        </button>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body"> <?php if (!empty($this->session->flashdata('message'))) { ?>
                    <div class="alert alert-info">
                        <?php print_r($this->session->flashdata('message')); ?>
                    </div> <?php } ?>
                <?php if (!empty($this->session->flashdata('message_error'))) { ?>
                    <div class="alert alert-info">
                        <?php print_r($this->session->flashdata('message_error')); ?>
                    </div> <?php } ?>
                <table class="table table-striped dt-responsive " id="table" style="width:100%; text-align: center;">
                    <thead>
                        <th class="w-1">No</th>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status Kerja</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Absen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="post" action="<?php echo base_url('data_absen/update'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">

                    <div class="form-group">
                        <label>Nama User</label>
                        <input type="text" class="form-control" name="nama_user" id="edit-nama_user" readonly>
                    </div>

                    <div class="form-group">
                        <label id="label-init-time">Check In</label>
                        <input type="time" class="form-control" name="init_time" id="edit-init_time">
                    </div>

                    <div class="form-group">
                        <label for="status_work">Status Kerja</label>
                        <select class="form-control" name="status_work" id="edit-status_work">
                            <option value="Work From Home">Work From Home</option>
                            <option value="Work From Office">Work From Office</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" id="edit-status">
                            <option value="Tepat Waktu">Tepat Waktu</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Pulang">Pulang</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Foto</label><br>
                        <img id="edit-photo" src="" alt="Foto" style="width: 150px; height: auto;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Tambah Data Absen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createForm" method="post" action="<?php echo base_url('data_absen/create'); ?>">
                <div class="modal-body">
                    <input type="hidden" name="id" id="create-id">

                    <div class="form-group">
                        <label>Nama User</label>
                        <!-- <input type="text" class="form-control" name="nama_user" id="create-nama_user"> -->
                        <select name="nama_user" class="form-control" required>
                            <option value="">Pilih User</option>
                            <?php foreach ($user as $row): ?>
                                <option value="<?php echo $row->id . '|' . $row->role_id; ?>"><?php echo $row->first_name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Absen</label>
                        <input type="date" class="form-control" name="tgl_absen" id="create-tgl_absen" required>
                    </div>

                    <div class="form-group">
                        <label id="label-init-time">Check In</label>
                        <input type="time" class="form-control" name="init_time" id="create-init_time">
                    </div>

                    <div class="form-group">
                        <label for="status_work">Status Kerja</label>
                        <select class="form-control" name="status_work" id="create-status_work">
                            <option value="Work From Home">Work From Home</option>
                            <option value="Work From Office">Work From Office</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status_work">Is Check In?</label>
                        <select class="form-control" name="is_check_in" id="create-is_check_in">
                            <option value="check_in">Check In</option>
                            <option value="check_out">Check Out</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" id="create-status" required>
                            <option value="Tepat Waktu">Tepat Waktu</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Pulang">Pulang</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label>Foto</label><br>
                        <img id="create-photo" src="" alt="Foto" style="width: 150px; height: auto;">
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Data Absen</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 30%;"><strong>Nama User</strong></td>
                        <td>: <span id="detail-nama_user"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Jam Masuk</strong></td>
                        <td>: <span id="detail-init_time"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Status Kerja</strong></td>
                        <td>: <span id="detail-status_work"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: <span id="detail-status"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Absen</strong></td>
                        <td>: <span id="detail-is_check_in"></span></td>
                    </tr>
                    <tr>
                        <td><strong>Foto</strong></td>
                        <td>: <img id="detail-photo" src="" alt="Foto" style="width: 150px;"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailMapelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Mapel</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Mapel</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Hari</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="mapel_list">
                        <!-- Filled dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>







<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-data_absen"
    src="<?php echo base_url() ?>assets/js/require.js">
</script>