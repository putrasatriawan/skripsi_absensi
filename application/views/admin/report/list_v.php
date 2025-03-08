<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h4 class="title">Filter</h4>
                <br>
                <div>
                <button id="generatePdfBtn" class="btn-shadow mr-3 btn btn-success">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-plus fa-w-20"></i>
                    </span> Generate PDF
                </button>
                </div>
                <hr>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="siswa_id_filter" class="col-sm-2 control-label">Pengampu</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" id="siswa_id_filter">
                                    <option value="">Pilih Semua Pengampu</option>
                                    <?php foreach ($pengampu as $key => $value) : ?>
                                        <option value="<?php echo $value->id ?>"><?php echo $value->guru ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <label for="kode_kelas_id_filter" class="col-sm-2 control-label">Kode Kelas</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" id="kode_kelas_id_filter">
                                    <option value="">Pilih Semua Kode Kelas</option>
                                    <?php foreach ($kode_kelas as $key => $value) : ?>
                                        <option value="<?php echo $value->id ?>"><?php echo $value->kode_kelas ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal end date belum selesai -->
                        <div class="form-group row">
                            <label for="tanggal_filter_start" class="col-sm-2 control-label">Start Date</label>
                            <div class="col-sm-4">
                                <input type="date" name="tanggal_filter_start" class="form-control" id="tanggal_filter_start">
                            </div>

                            <label for="mapel_id_filter" class="col-sm-2 control-label">Mapel</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" id="mapel_id_filter">
                                    <option value="">Pilih Semua Mapel</option>
                                    <?php foreach ($mapel as $key => $value) : ?>
                                        <option value="<?php echo $value->id ?>"><?php echo $value->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal start date belum selesai -->
                        <div class="form-group row">
                            <label for="tanggal_filter_end" class="col-sm-2 control-label">End Date</label>
                                <div class="col-sm-4">
                                    <input type="date" name="tanggal_filter_end" class="form-control" id="tanggal_filter_end">
                                </div>

                            <label for="status_id_filter" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" id="status_id_filter">
                                    <option value="">Pilih Semua Status</option>
                                    <option value="hadir">Hadir</option>
                                    <option value="alpa">Alpa</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="izin">Izin</option>
                                    <option value="keterangan">Keterangan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-offset-2 col-sm-4">
                                <button id="btn-apply-filter" class="btn btn-primary mr-2">Terapkan</button>
                                <button id="btn-reset-filter" class="btn btn-outline-danger">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flash messages -->
                <?php if (!empty($this->session->flashdata('message'))) : ?>
                    <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message'); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($this->session->flashdata('message_error'))) : ?>
                    <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message_error'); ?>
                    </div>
                <?php endif; ?>

                <hr>

                <div id="attendance_data"></div>
            </div>
        </div>
    </div>
</div>

<!-- <script data-main="<?php // echo base_url() 
                        ?>assets/js/main/main-absensi" src="<?php // echo base_url() 
                                                            ?>assets/js/require.js"></script> -->
<script data-main="<?php echo base_url() ?>assets/js/main/main-report"
    src="<?php echo base_url() ?>assets/js/require.js"></script>