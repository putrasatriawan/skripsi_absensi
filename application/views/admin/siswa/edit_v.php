<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Ubah Ruang </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 offset-md-2">
                <form method="POST"
                      id="form-unit-kerja"
                      enctype="multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body"> <?php
                        if (!empty($this->session->flashdata('message_error'))) { ?>     <div class="alert alert-danger"> <?php
                               print_r($this->session->flashdata('message_error'));
                               ?> </div> <?php } ?> <div class="form-group row">
                                <label class="col-sm-3 form-label">Kode</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                           name="code"
                                           value="<?php echo $code ?>"
                                           class="form-control"
                                           placeholder="Masukkan Kode">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                           name="name"
                                           value="<?php echo $name ?>"
                                           class="form-control"
                                           placeholder="Masukkan Nama Unit Kerja">
                                </div>
                            </div>
                             <div class="form-group row">
                                <label class="col-sm-3 form-label">Deskripsi</label>
                                <div class="col-sm-9">
                                    <input type="text"
                                           name="description"
                                           value="<?php echo $description ?>"
                                           class="form-control"
                                           placeholder="Masukkan Deskripsi">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light align-items-center justify-content-end">
                            <a href="<?php echo base_url(); ?>ruang"
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
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-ruang"
        src="<?php echo base_url() ?>assets/js/require.js">
</script>