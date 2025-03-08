<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Data Absen</div>
                </div>
                <div class="page-title-actions">
                    <a href="<?php echo base_url() ?>data_absen/create" class="btn-shadow mr-3 btn btn-success">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span> Tambah
                    </a>
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
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Tahun Angkatan</th>
                        <th>Kode Kelas</th>
                        <th>Aksi</th>
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
                <form id="importForm" action="<?php echo base_url('data_absen/import_data') ?>" method="post"
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-data_absen"
    src="<?php echo base_url() ?>assets/js/require.js">
</script>