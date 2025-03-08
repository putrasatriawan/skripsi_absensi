<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Siswa / Siswi</div>
                </div>
                <div class="page-title-actions">
                    <!-- <a href="<?php echo base_url() ?>siswa/create" class="btn-shadow mr-3 btn btn-success">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span> Tambah
                    </a> -->
                    <button type="button" class="btn-shadow mr-3 btn btn-primary" data-toggle="modal"
                        data-target="#importModal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-upload fa-w-20"></i>
                        </span> Import
                    </button>
                    <a href="<?php echo base_url() ?>assets/template/template-data-siswa.xlsx"
                        class="btn-shadow mr-3 btn btn-info">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-download fa-w-20"></i>
                        </span> Download Template
                    </a>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <?php if (!empty($this->session->flashdata('message'))) { ?>
                    <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message'); ?>
                    </div>
                <?php } ?>

                <?php if (!empty($this->session->flashdata('message_error'))) { ?>
                    <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message_error'); ?>
                    </div>
                <?php } ?>

                <table class="table table-striped dt-responsive" id="table" style="width:100%; text-align: center;">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th>Nama siswa / siswi</th>
                            <th>NIK</th>
                            <th>jenis Kelamin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="importForm" action="<?php echo base_url('siswa/import_data') ?>" method="post"
                    enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileInput">Pilih file untuk diunggah</label>
                        <input type="file" class="form-control-file" id="userfile" name="userfile" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="importForm" class="btn btn-primary">Upload</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editSiswaModal" tabindex="-1" role="dialog" aria-labelledby="editGrurModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSiswaModalLabel">Edit Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSiswaForm">
                    <input type="hidden" id="id" name="id" />
                    <div class="row">
                        <div class="col text-left">
                            <button type="button" id="prevBtn" class="btn btn-secondary">Previous</button>
                        </div>
                        <div class="col text-right">
                            <button type="button" id="nextBtn" class="btn btn-secondary">Next</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required />
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required />
                    </div>
                    <div class="form-group">
                        <label for="jk">Jenis Kelamin</label>
                        <select class="form-control" id="jk" name="jk" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ttl">Tempat Tanggal Lahir</label>
                        <input type="text" class="form-control" id="ttl" name="ttl" required />
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required />
                    </div>
                    <div class="form-group">
                        <label for="kode_kelas_id">Kelompok Kelas</label>
                        <select class="form-control" id="kode_kelas_id" name="kode_kelas_id" required>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-siswa" src="<?php echo base_url() ?>assets/js/require.js">
</script>