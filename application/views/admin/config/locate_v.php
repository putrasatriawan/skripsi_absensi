<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body"> <?php if (!empty($this->session->flashdata('message'))) { ?>
                    <div class="alert alert-info">
                        <?php print_r($this->session->flashdata('message')); ?>
                    </div> <?php } ?>
                <div id="alert-container"></div>
                <h4 class="title">Config</h4>
                <hr>
                <div id="alert-container"></div>
                <form id="configForm" class="mb-5">
                    <div class="form-group">
                        <label for="latitude">Latitude:</label>
                        <input type="text" name="latitude" id="latitude" value="<?= isset($latitude) ? $latitude : '' ?>" class="form-control" placeholder="Enter latitude">
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude:</label>
                        <input type="text" name="longitude" id="longitude" value="<?= isset($longitude) ? $longitude : '' ?>" class="form-control" placeholder="Enter longitude">
                    </div>


                    <button type="submit" id="save-btn" class="btn btn-primary">Update LAN & LAT</button>
                </form>
                <?php if (!empty($this->session->flashdata('message_error'))) { ?>
                    <div class="alert alert-info">
                        <?php print_r($this->session->flashdata('message_error')); ?>
                    </div> <?php } ?>
                <table class="table table-striped dt-responsive " id="table" style="width:100%; text-align: center;">
                    <thead>
                        <th class="w-1">No</th>
                        <th>Jabatan</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Aksi</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Ubah Jam Masuk & Jam Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="<?= base_url('config/save_check') ?>">
                <div class="modal-body">
                    <input type="hidden" name="roles_id" id="roles_id">
                    <input type="hidden" name="id_check" id="id_check">

                    <div class="mb-3">
                        <label for="checkIn" class="form-label">Jam Masuk</label>
                        <input type="time" class="form-control" id="check_in" name="check_in" required>
                    </div>
                    <div class="mb-3">
                        <label for="checkOut" class="form-label">Jam Keluar</label>
                        <input type="time" class="form-control" id="check_out" name="check_out" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script data-main="<?php echo base_url() ?>assets/js/main/main-config"
    src="<?php echo base_url() ?>assets/js/require.js"></script>