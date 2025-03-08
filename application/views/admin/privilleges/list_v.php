<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Jabatan <div class="page-title-subheading">Jabatan User</div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="<?php echo base_url() ?>privileges/create"
                       class="btn-shadow mr-3 btn btn-success">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-plus fa-w-20"></i>
                        </span> Tambah </a>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
        <div class="card-body">
                    <div class="table-responsive">

                        <?php if (!empty($this->session->flashdata('message'))) { ?>
                        <div class="alert alert-info">
                            <?php
                print_r($this->session->flashdata('message'));
                ?>
                        </div>
                        <?php } ?>
                        <table class="table table-striped"
                               id="table">
                            <thead>
                                <th>No Urut</th>
                                <th>Jabatan</th>
                                <th>Aksi</th>
                            </thead>
                        </table>
                    </div>
                </div>
        </div>
    </div>
</div>
<script data-main="<?php echo base_url() ?>assets/js/main/main-privilleges"
        src="<?php echo base_url() ?>assets/js/require.js">
</script>