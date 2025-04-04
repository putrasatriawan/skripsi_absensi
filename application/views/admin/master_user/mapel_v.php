<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Master Mapel</div>
                </div>
            </div>
        </div>
        <form method="POST"
            id="form-logbook"
            enctype="multipart/form-data" action="<?php echo base_url(); ?>Master_user/save_jadwal">
            <div class=" main-card mb-3 card">
                <div class="col-md-12 mt-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="card-title mb-3">Daftar Sub</p>
                        <div class="ml-auto">
                            <button id="rowbuttonedit"
                                type="button"
                                class="btn btn-success mb-3"><span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-plus fa-w-20"></i>
                                </span>Tambah</button>
                        </div>
                    </div>
                </div>
                <div id="form-container-edit"
                    class="col-md-12"></div>
                <div class="card-footer bg-light align-items-center justify-content-end">
                    <a href="<?php echo base_url() ?>p_mr01_02"
                        class="btn btn-outline-secondary mr-2">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-times fa-w-20"></i>
                        </span>Batal </a>
                    <button id="save-btn-logbook"
                        type="submit"
                        class="btn btn-primary">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-save fa-w-20"></i>
                        </span>Simpan </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var id_user = <?php echo $id ?>
</script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-master-user" src="<?php echo base_url() ?>assets/js/require.js">
</script>