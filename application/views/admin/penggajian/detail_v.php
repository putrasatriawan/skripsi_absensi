<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Detail Penggajian
                        <div class="page-title-subheading">Data Penggajian User</div>
                    </div>
                </div>
                <!-- <div class="page-title-actions">
                    <div class="page-title-actions">
                        <a href="<?php echo base_url() ?>master_user/config_waktu"
                            class="btn-shadow mr-3 btn btn-success">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fa fa-plus fa-w-20"></i>
                            </span> Tambah </a>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <!-- <p class="title">Filter</p>
                <div class="row">

                    <div class="col-lg-3">

                    </div>
                    <div class="col-lg-3">
                        <button id="btn-apply-filter" class="btn btn-primary mr-2">Terapkan</button>
                        <button id="btn-reset-filter" class="btn btn-outline-danger">Reset</button>
                    </div>
                </div> -->
                <!-- <hr>    -->
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

                <table class="table table-striped dt-responsive" id="table_penggajian" style="width:100%; text-align: center;">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th></th>
                            <th>Nama</th>
                            <th></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</div>


<script data-main="<?php echo base_url() ?>assets/js/main/main-penggajian"
    src="<?php echo base_url() ?>assets/js/require.js"></script>