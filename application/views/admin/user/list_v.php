<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Pengguna <div class="page-title-subheading">Pengguna User</div>
                    </div>
                </div>

                <div class="page-title-actions">
                    <a href="<?php echo base_url() ?>user/create" class="btn-shadow mr-3 btn btn-success">
                        <span class="btn-icon-wrapper pr-2 opacity-7"><i class="fa fa-plus fa-w-20"></i> </span> Tambah
                    </a>
                </div>

                <!-- import data button -->
                <button type="button" class="btn-shadow mr-3 btn btn-primary" data-toggle="modal"
                    data-target="#importModal">
                    <span class="btn-icon-wrapper pr-2 opacity-7"> <i class="fa fa-upload fa-w-20"></i> </span> Import
                </button>

                <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
                    aria-hidden="true">
                    <form id="importForm" method="post" enctype="multipart/form-data" class="mt-3">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="importModalLabel">Upload File</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <select class="form-control" id="filter-roles" name="role_id">
                                                <option selected hidden disabled>Pilih Jabatan</option>
                                                <?php foreach ($roles as $key => $value) { ?>
                                                <option value="<?php echo $value->id ?>"> <?php echo $value->name ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-9">
                                            <button type="button" id="download-template"
                                                class="btn-shadow mr-3 btn btn-info disabled">
                                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                                    <i class="fa fa-download fa-w-20"></i>
                                                </span>
                                                Download Template
                                            </button>
                                            <input type="hidden" id="role_id" name="role_id">
                                            <div class="form-group">
                                                <label for="fileInput">Pilih file untuk diunggah</label>
                                                <input type="file" class="form-control-file" id="userfile"
                                                    name="userfile" required disabled>
                                            </div>
                                            <button type="submit" class="btn btn-primary" id="upload-button"
                                                disabled>Upload</button>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- end import data button -->
        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <!-- <p class="title">Filter</p> -->
            <div class="row">

                        <!-- <div class="col-lg-3">
                            <select class="form-control" id="filter-roles-jb" name="role_name">
                                <option selected hidden disabled>Pilih Jabatan</option>
                                <?php foreach ($roles as $key => $value) { ?>
                                <option value="<?php echo $value->name ?>"> <?php echo $value->name ?></option>
                                <?php } ?>
                            </select>
                        </div> -->
                        <!-- <div class="col-lg-3">
                            <button id="btn-apply-filter" class="btn btn-primary mr-2">Terapkan</button>
                            <button id="btn-reset-filter" class="btn btn-outline-danger">Reset</button>
                        </div> -->
                    </div>
                    <hr> <?php if (!empty($this->session->flashdata('message'))) { ?>
                    <div class="alert alert-info"> <?php print_r($this->session->flashdata('message')); ?> </div>
                    <?php } ?>
                    <?php if (!empty($this->session->flashdata('message_error'))) { ?> <div class="alert alert-info">
                        <?php print_r($this->session->flashdata('message_error')); ?> </div>
                    <?php } ?>
                    <table class="table table-striped dt-responsive " id="table" style="width:100%;">
                        <thead>
                            <th>No Urut</th>
                            <th>Jabatan</th>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Aksi</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script data-main="<?php echo base_url() ?>assets/js/main/main-user"
        src="<?php echo base_url() ?>assets/js/require.js">
    </script>