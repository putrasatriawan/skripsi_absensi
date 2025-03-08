<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Profile </div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-header">
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link active"
                           href="#profile"
                           data-toggle="tab">Profil Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link"
                           href="#password"
                           data-toggle="tab">Ganti Password</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active "
                         id="profile">
                        <input type="hidden"
                               id="user_id"
                               value="">
                        <form class="form-horizontal"
                              id="form"
                              method="POST"
                              enctype="multipart/form-data"
                              x
                              action="">
                            <div class="box-body"> <?php if (!empty($this->session->flashdata('message_error'))) { ?> <div
                                                             class="alert alert-danger"> <?php
                                                             print_r($this->session->flashdata('message_error'));
                                                             ?> </div> <?php } ?> <?php if (!empty($this->session->flashdata('message'))) { ?> <div
                                                             class="alert alert-success"> <?php
                                                             print_r($this->session->flashdata('message'));
                                                             ?> </div> <?php } ?> <input type="hidden"
                                       name="id"
                                       value="<?php echo $id; ?>">
                                <div class="form-group row">
                                    <label for="inputEmail3"
                                           class="col-sm-3 control-label">Nama Lengkap</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control"
                                               id="nama_lengkap"
                                               placeholder="Nama Lengkap"
                                               name="nama_lengkap"
                                               value="<?php echo $name; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3"
                                           class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email"
                                               class="form-control"
                                               id="email"
                                               placeholder="Email"
                                               name="email"
                                               value="<?php echo $email; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3"
                                           class="col-sm-3 control-label">Nik</label>
                                    <div class="col-sm-9">
                                        <input type="number"
                                               class="form-control"
                                               id="nik"
                                               placeholder="Nik"
                                               name="nik"
                                               value="<?php echo $nik; ?>">
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label for="inputPassword3"
                                           class="col-sm-3 control-label">Foto</label>
                                    <div class="col-sm-9">
                                        <input class="form-control photo"
                                               type="file"
                                               id="photo"
                                               name="photo"
                                               accept=".jpg">
                                        <div id="thumbnailContainer"
                                             class="preview-image mt-2"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div id="thumbnailContainer"
                                         class="mt-2"
                                         style="max-width: 150px;">
                                    </div>
                                </div> -->
                            </div>
                            <div class="box-footer text-right">
                                <a href="<?php echo base_url(); ?>dashboard"
                                   class="btn btn-sm btn-default ">Batal</a>
                                <button type="submit"
                                        class="btn btn-sm btn-success"
                                        name="profil_pengguna"
                                        value="1"
                                        id="save-btn">Simpan</button>
                            </div>
                    </div>
                    </form>
                </div>
            
            <div class="tab-content">
                <div class="tab-pane"
                     id="password">
                    <form class="form-horizontal"
                          id="forms"
                          method="POST"
                          action="<?php echo base_url() ?>profile">
                        <div class="box-body"> 
                            <?php if (!empty($this->session->flashdata('messages'))) { ?> <div
                                                         class="alert alert-success"> <?php
                                                         print_r($this->session->flashdata('messages'));
                                                         ?> </div> <?php } ?> 
                            <input type="hidden"
                                   name="id"
                                   value="<?php echo $id; ?>">
                            <div class="form-group row">
                                <label for="inputEmail3"
                                       class="col-sm-3 control-label">Password Lama</label>
                                <div class="col-sm-9">
                                    <input type="password"
                                           class="form-control"
                                           id="old_password"
                                           name="old_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputEmail3"
                                       class="col-sm-3 control-label">Password Baru</label>
                                <div class="col-sm-9">
                                    <input type="password"
                                           class="form-control"
                                           id="new_password"
                                           name="new_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3"
                                       class="col-sm-3 control-label">Konfirmasi Password Baru</label>
                                <div class="col-sm-9">
                                    <input type="password"
                                           class="form-control"
                                           id="confirm_password"
                                           name="confirm_password">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <a href="<?php echo base_url(); ?>dashboard"
                                       class="btn btn-sm btn-default ">Batal</a>
                                    <button type="submit"
                                            class="btn btn-sm btn-success"
                                            name="password_pengguna"
                                            id="save-btn2">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
       
    </div>
</div>
</div>
<script data-main="<?php echo base_url() ?>assets/js/main/main-profile"
        src="<?php echo base_url() ?>assets/js/require.js"></script>