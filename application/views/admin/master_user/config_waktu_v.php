<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Master Pengguna <div class="page-title-subheading">Tambah Konfigurasi Waktu</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8 offset-md-2">
            <form action="<?= base_url('master_user/save_config_waktu') ?>" method="POST" enctype="multipart/form-data ?>">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="bulan">Bulan dan Tahun</label>
                            <input type="text" class="form-control date-picker" id="bulan" name="bulan" placeholder="Pilih Bulan & Tahun" required>
                        </div>

                        <!-- Input Kode Konfigurasi -->
                        <div class="form-group">
                            <label for="kode">Kode Konfigurasi</label>
                            <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukan kode konfigurasi" required>
                        </div>

                        <!-- Input Keterangan -->
                        <div class=" form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Masukan Keterangan" required></textarea>
                        </div>


                    </div>
                    <div class="card-footer bg-light align-items-center justify-content-end">
                        <a href="<?php echo base_url() ?>master_user" class="btn btn-outline-secondary mr-2">
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
<script data-main="<?php echo base_url() ?>assets/js/main/main-master-user" src="<?php echo base_url() ?>assets/js/require.js"></script>