<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Edit Pengguna <div class="page-title-subheading"> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 offset-md-2">
                <form method="POST" id="form" enctype="multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <input type="hidden" name="kd_kec_state" id="kd_kec_state" value="">

                            <div class="box-body">
                                <!-- Error Message Display -->
                                <?php if (!empty($this->session->flashdata('message_error'))) { ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('message_error'); ?>
                                </div>
                                <?php } ?>

                                <!-- Hidden Inputs -->
                                <input type="hidden" name="id" id="user_id" value="<?php echo $id; ?>">
                                <input type="hidden" id="role_id_selected" value="<?php echo $role_id; ?>">

                                <!-- Profile Image Display -->
                                <?php if (!empty($foto)) { ?>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label"></label>
                                    <div class="col-sm-9">
                                        <img width="100px"
                                            src="<?php echo base_url() . "assets/images/foto/" . $foto; ?>">
                                    </div>
                                </div>
                                <?php } ?>

                                <!-- Name Field -->
                                <div class="form-group row">
                                    <label for="name" class="col-sm-3 control-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" placeholder="Name" name="name"
                                            value="<?php echo $first_name; ?>">
                                    </div>
                                </div>

                                <!-- NIK Field -->
                                <div class="form-group row">
                                    <label for="nik" class="col-sm-3 control-label">NIK</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="nik" placeholder="NIK" name="nik"
                                            value="<?php echo $nik; ?>">
                                    </div>
                                </div>

                                <!-- Email Field -->
                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" placeholder="Email"
                                            name="email" value="<?php echo $email; ?>">
                                    </div>
                                </div>

                                <hr>

                                <!-- Role Field -->
                                <div class="form-group row">
                                    <label for="role_id" class="col-sm-3 control-label">Jabatan</label>
                                    <div class="col-sm-9">
                                        <select id="role_id" name="role_id" class="form-control">
                                            <option value="">Pilih Role</option>
                                            <?php foreach ($roles as $key => $role) { ?>
                                            <option value="<?php echo $role->id; ?>"
                                                <?php echo $role->id == $role_id ? 'selected' : '' ?>>
                                                <?php echo $role->name; ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Photo Upload Section -->
                                <div class="form-group row">
                                    <label for="photo" class="col-sm-3 control-label">Photo</label>
                                    <div class="col-sm-9">
                                        <input type="file" name="photo" id="photo" class="form-control"
                                            accept="image/*">

                                        <!-- Display current photo if available -->
                                        <?php if (!empty($photo)): ?>
                                        <div class="mt-2">
                                            <img src="<?php echo base_url('uploads/photo_profile/' . $photo); ?>"
                                                alt="Current Photo" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer bg-light align-items-center justify-content-end">
                            <a href="<?php echo base_url() ?>user" class="btn btn-outline-secondary mr-2">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-times fa-w-20"></i>
                                </span>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-save fa-w-20"></i>
                                </span>Simpan
                            </button>
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