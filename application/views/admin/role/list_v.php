<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Jabatan <div class="page-title-subheading">Jabatan User</div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="<?php echo base_url() ?>role/create"
                       class="btn-shadow mr-3 btn btn-success">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span> Tambah </a>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body"> <?php if (!empty($this->session->flashdata('message'))) { ?> <div
                     class="alert alert-info"> <?php
              print_r($this->session->flashdata('message'));
              ?> </div> <?php } ?> <?php if (!empty($this->session->flashdata('message_error'))) { ?> <div
                     class="alert alert-info"> <?php
              print_r($this->session->flashdata('message_error'));
              ?> </div> <?php } ?> <table class="table table-striped dt-responsive "
                       id="table"
                       style="width:100%;">
                    <thead>
                        <th>No Urut</th>
                        <th>Nama Jabatan</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-role"
        src="<?php echo base_url() ?>assets/js/require.js">
</script>