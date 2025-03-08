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
                <form method="POST" id="form-unit-kerja" enctype="multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body"> <?php
                        if (!empty($this->session->flashdata('message_error'))) { ?> <div class="alert alert-danger"> <?php
                               print_r($this->session->flashdata('message_error'));
                               ?> </div> <?php } ?> <div class="form-group row">
                                <input type="hidden" name="users_id" value="<?php echo $users_id; ?>">
                                <label class="col-sm-3 form-label">NIP</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nip" value="<?php echo $nip ?>" class="form-control" placeholder="Masukkan NIP">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" value="<?php echo $name ?>" class="form-control" placeholder="Masukkan Nama Guru">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_kelamin" class="col-sm-3 form-label">Jenis Kelamin</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                        <option value="Laki-laki" <?php echo $jenis_kelamin === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php echo $jenis_kelamin === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">No Hp</label>
                                <div class="col-sm-9">
                                    <input type="text" name="no_hp" value="<?php echo $no_hp ?>" class="form-control" placeholder="Masukkan No Hp">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="agama" class="col-sm-3 form-label">Agama</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="agama" id="agama">
                                        <option value="Islam" <?php echo $agama === 'Islam' ? 'selected' : '' ?>>Islam</option>
                                        <option value="Kristen" <?php echo $agama === 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                                        <option value="Katolik" <?php echo $agama === 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                                        <option value="Buddha" <?php echo $agama === 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                                        <option value="Hindu" <?php echo $agama === 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                                        <option value="Khonghucu" <?php echo $agama === 'Khonghucu' ? 'selected' : '' ?>>Khonghucu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat"><?php echo $alamat ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Gaji</label>
                                <div class="col-sm-9">
                                    <input type="number" min="0" name="gaji" value="<?php echo $gaji ?>" class="form-control" placeholder="Gaji">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Tempat Lahir</label>
                                <div class="col-sm-9">
                                    <input type="text" name="tempat_lahir" value="<?php echo $tempat_lahir ?>" class="form-control" placeholder="Masukkan Tempat Lahir">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Tanggal Lahir</label>
                                <div class="col-sm-9">
                                    <input type="date" name="tanggal_lahir" value="<?php echo $tanggal_lahir ?>" class="form-control" placeholder="Masukkan Tanggal Lahir">
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
<script data-main="<?php echo base_url() ?>assets/js/main/main-guru"
        src="<?php echo base_url() ?>assets/js/require.js">
</script>