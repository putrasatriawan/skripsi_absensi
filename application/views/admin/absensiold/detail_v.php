<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Detail Mapel </div>
                </div>
            </div>
        </div>
        <form method="POST"
              id="form-kelas"
              enctype="multipart/form-data">
            <div class="main-card mb-3 card">
                <div class="card-body"> <?php
                if (!empty($this->session->flashdata('message_error'))) { ?>     <div class="alert alert-danger"> <?php
                       print_r($this->session->flashdata('message_error'));
                       ?> </div> <?php } ?> <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kode</label>
                                <input type="text"
                                       name="code"
                                       value="<?php echo $code ?>"
                                       class="form-control"
                                       readonly
                                       placeholder="Masukkan Kode Area">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Nama</label>
                                <input type="text"
                                       name="name"
                                       value="<?php echo $name ?>"
                                       class="form-control"
                                       readonlu
                                       placeholder="Masukkan Nama Unit Kerja">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Tahun Ajaran</label>
                                <input type="text"
                                       name="th_ajaran"
                                       value="<?php echo $th_ajaran ?>"
                                       class="form-control"
                                       readonlu
                                       placeholder="Masukkan Tahun Ajaran">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light align-items-center justify-content-end">
                    <a href="<?php echo base_url(); ?>kelas"
                       class="btn btn-outline-secondary mr-2">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-times fa-w-20"></i>
                        </span>Batal </a>
                    <button type="submit"
                            id="save-btn"
                            class="btn btn-primary">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-save fa-w-20"></i>
                        </span>Simpan </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script data-main="<?php echo base_url() ?>assets/js/main/main-mapel"
        src="<?php echo base_url() ?>assets/js/require.js">
</script>