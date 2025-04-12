<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card shadow-sm border-0">
            <div class="card-body">
                <?php if (!empty($this->session->flashdata('message'))) { ?>
                    <div class="alert alert-info">
                        <?php print_r($this->session->flashdata('message')); ?>
                    </div>
                <?php } ?>

                <h4 class="title mb-4">Konfigurasi Waktu</h4>

                <form action="<?= base_url('master_user/save_config_waktu') ?>" method="POST" enctype="multipart/form-data ?>">
                    <!-- Input Bulan & Tahun -->
                    <div class="form-group">
                        <label for="bulan">Bulan dan Tahun</label>
                        <input type="text" class="form-control date-picker" id="bulan" name="bulan" placeholder="Pilih Bulan & Tahun" required>
                    </div>

                    <!-- Input Kode Konfigurasi -->
                    <div class="form-group">
                        <label for="kode">Kode Konfigurasi</label>
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Tambahkan kode konfigurasi..." required>
                    </div>

                    <!-- Input Keterangan -->
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan deskripsi atau catatan konfigurasi..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Konfigurasi</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- RequireJS tetap jalan -->
<script data-main="<?php echo base_url() ?>assets/js/main/main-master-user" src="<?php echo base_url() ?>assets/js/require.js"></script>