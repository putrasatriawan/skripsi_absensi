<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Input Absensi </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <form method="POST" id="form-absen" enctype="multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body"> <?php
                                                if (!empty($this->session->flashdata('message_error'))) { ?> <div class="alert alert-danger">
                                    <?php
                                                    print_r($this->session->flashdata('message_error'));
                                    ?> </div> <?php } ?>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <input type="date" name="tanggal" class="form-control" id="tanggal" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea type="text" name="keterangan" class="form-control" placeholder="Masukkan Keterangan">

                                    </textarea>
                                </div>
                            </div>


                            <div class="table-responsive" id="inittable">
                                <table id="absensi_table" class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th colspan="3">Mahasiswa</th>
                                            <th colspan="5">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th>Nis</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Hadir</th>
                                            <th>Alpa</th>
                                            <th>Sakit</th>
                                            <th>Izin</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class=" card-footer bg-light align-items-center justify-content-end">
                            <a href="<?php echo base_url(); ?>absensi" class="btn btn-outline-secondary mr-2">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-times fa-w-20"></i>
                                </span>Batal </a>
                            <button type="submit" id="save-btn" class="btn btn-primary">
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
<script data-main="<?php echo base_url() ?>assets/js/main/main-absensi" src="<?php echo base_url() ?>assets/js/require.js">
</script>