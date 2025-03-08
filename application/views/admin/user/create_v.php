<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Tambah Pengguna <div class="page-title-subheading"> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 offset-md-2">
                <form method="POST" id="form" enctype="
                      multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-3 control-label">Nama</label>
                                <div class="col-sm-9">
                                    <input type="name" class="form-control" id="name" placeholder="Masukan Nama"
                                        name="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 control-label">NIS/NIK/NIP</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="nik" placeholder="Masukan NIK/NIS/NIP"
                                        name="nik">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 control-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="email" placeholder="Masukan Email"
                                        name="email">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 control-label">Jabatan</label>
                                <div class="col-sm-9">
                                    <select id="role_id" name="role_id" class="form-control">
                                        <option value="">Pilih Jabatan</option>
                                        <?php foreach ($roles as $key => $role) { ?>
                                            <option value="<?php echo $role->id; ?>"
                                                data-role-name="<?php echo $role->name; ?>">
                                                <?php echo $role->name; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div id="kelas_siswa" class="form-group row" style="display: none;">
                                <label for="inputPassword3" class="col-sm-3 control-label">Kelompok Kelas</label>
                                <div class="col-sm-9">
                                    <select id="kelas_id" name="kelas_id" class="form-control">
                                        <option value="">Pilih Kelompok Kelas</option>
                                        <?php foreach ($kelompok_kelas as $key => $kelas_item) { ?>
                                            <option value="<?php echo $kelas_item->id; ?>">
                                                <?php echo $kelas_item->kode_kelas; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div id="jk_siswa" class="form-group row" style="display: none;">
                                <label for="inputPassword3" class="col-sm-3 control-label">Jenis Kelamin</label>
                                <div class="col-sm-9">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jk" value="Laki-laki">
                                        <span class="form-check-label">Laki-Laki</span>
                                    </label>
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="jk" value="Perempuan">
                                        <span class="form-check-label">Perempuan</span>
                                    </label>
                                </div>
                            </div>
                            <div id="ttl_siswa" class="form-group row" style="display: none;">
                                <label for="inputPassword3" class="col-sm-3 control-label">TTL</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="ttl" placeholder="Masukan TTL"
                                        name="ttl"></textarea>
                                </div>
                            </div>
                            <div id="no_hp_siswa" class="form-group row" style="display: none;">
                                <label for="inputPassword3" class="col-sm-3 control-label">No Hp</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="no_hp" placeholder="Masukan No Hp"
                                        name="no_hp">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 control-label">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" placeholder="Masukan Alamat"
                                        name="address"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 control-label">Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-3 control-label">Ulangi Password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="password_confirm"
                                        name="password_confirm">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="photo" class="col-sm-3 control-label">Photo</label>
                                <div class="col-sm-9">
                                    <input type="file" name="photo" id="photo" class="form-control" accept="image/*">

                                </div>
                            </div>

                        </div>
                        <div class="card-footer bg-light align-items-center justify-content-end">
                            <a href="<?php echo base_url() ?>user" class="btn btn-outline-secondary mr-2">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-times fa-w-20"></i>
                                </span>Batal </a>
                            <button type="submit" class="btn btn-primary">
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
<script data-main="<?php echo base_url() ?>assets/js/main/main-user" src="<?php echo base_url() ?>assets/js/require.js">
</script>