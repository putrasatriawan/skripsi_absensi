<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Tambah Kelompok Kelas </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 offset-md-2">
                <form method="POST"
                      id="form-kelompok_kelas"
                      enctype="multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body"> <?php
                        if (!empty($this->session->flashdata('message_error'))) { ?>     <div class="alert alert-danger"> <?php
                               print_r($this->session->flashdata('message_error'));
                               ?> </div> <?php } ?> <div class="form-group row">
                            <label class="col-sm-3 form-label">Kelas</label>
                            <div class="col-sm-9">
                                <select name="kelas_id" class="form-control">
                                    <option value="">Pilih Kelas</option>
                                    <!-- Populate options with data from the `kelas` table -->
                                    <?php foreach ($kelas as $row): ?>
                                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Nomor Kelas</label>
                            <div class="col-sm-9">
                                <select name="nomor_kelas" class="form-control">
                                    <option value="">Pilih Nomor Kelas</option>
                                    <?php foreach (range('A', 'N') as $letter): ?>
                                    <option value="<?php echo $letter; ?>"><?php echo $letter; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Jurusan ID</label>
                            <div class="col-sm-9">
                                <select name="jurusan_id" class="form-control">
                                    <option value="">Pilih Jurusan</option>
                                    <?php foreach ($jurusan as $row): ?>
                                    <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 form-label">Tahun Angkatan</label>
                            <div class="col-sm-9">
                                <select name="tahun_angkatan" class="form-control">
                                    <option value="">Pilih Tahun Angkatan</option>
                                    <?php 
                                    $currentYear = date('Y');
                                    for ($i = $currentYear - 10; $i <= $currentYear + 1; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        </div>
                        <div class="card-footer bg-light align-items-center justify-content-end">
                            <a href="<?php echo base_url(); ?>kelompok_kelas"
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
<script data-main="<?php echo base_url() ?>assets/js/main/main-kelompok_kelas"
        src="<?php echo base_url() ?>assets/js/require.js">
</script>