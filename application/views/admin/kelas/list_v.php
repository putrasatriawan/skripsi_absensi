<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Kelas</div>
                </div>
                <div class="page-title-actions">
                    <a href="<?php echo base_url() ?>kelas/create" class="btn-shadow mr-3 btn btn-success">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span> Tambah
                    </a>
                    <button type="button" class="btn-shadow mr-3 btn btn-primary" data-toggle="modal"
                        data-target="#importModal">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-upload fa-w-20"></i>
                        </span> Import
                    </button>
                    <a href="<?php echo base_url() ?>kelas/generate_excel"
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
                            <th>Kode</th>
                            <th>Kelas</th>
                            <th>Tahun Ajaran</th>
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
                <form id="importForm" action="<?php echo base_url('kelas/import_data') ?>" method="post"
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-kelas"
    src="<?php echo base_url() ?>assets/js/require.js"></script>