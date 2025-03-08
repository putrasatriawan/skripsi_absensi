<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ZONETEMP | Login</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/base.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom.css">
</head>

<body>
    <div class="app-container bg-white body-tabs-shadow">
        <div class="app-container">
            <div class="row mx-0">
                <div class="col-md-12 px-0">
                    <div class="h-100">
                        <div class="d-flex  justify-content-center align-items-center row mx-0">
                            <div class="mx-auto app-login-box col-md-10">
                                <!-- <div class="app-logo-inverse mx-auto mb-3"></div> -->
                                <div class="w-100 mx-auto">
                                    <div class="px-4">
                                        <div class="modal-body px-0">
                                            <div class="h5 modal-title text-center">
                                                <img src="<?php echo base_url(); ?>assets/images/logo.png"
                                                    class="logo-login mb-5">
                                                <h4 class="mt-2">
                                                    <div>Nama aplikasnya</div>
                                                    <span>Sistem Informasi Akademik</span>
                                                </h4>
                                            </div> <?php if (!empty($this->session->flashdata('message_error'))) { ?>
                                                <div class="alert alert-danger">
                                                    <?php
                                                        print_r($this->session->flashdata('message_error'));
                                                    ?>
                                                </div>
                                            <?php } ?>
                                            <form action="<?php echo base_url(); ?>auth/login" method="post"
                                                id="form-login">
                                                <div class="position-relative form-group">
                                                    <div class="d-flex align-items-center input-box">
                                                        <div class="icon-login"><i class="pe-7s-user"></i></div>
                                                        <div class="input-login">
                                                            <label class="form-label m-0">NISN</label>
                                                            <input type="text" class="form-control" name="username"
                                                                id="username" placeholder="Masukkan NIK Anda"
                                                                autocomplete="off" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="position-relative form-group">
                                                    <div class="d-flex align-items-center input-box">
                                                        <div class="icon-login"><i class="pe-7s-lock"></i></div>
                                                        <div class="input-login">
                                                            <label class="form-label m-0">Password</label>
                                                            <input type="password" class="form-control" name="password"
                                                                id="password" placeholder="Password" autocomplete="off"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="position-relative form-check"><input name="check" id="exampleCheck"
                                                type="checkbox" class="form-check-input"><label for="exampleCheck"
                                                class="form-check-label">Keep me logged in</label>
                                        </div>
                                        <div class="clearfix">
                                            <div class="float-right">
                                                <button type="submit" class="btn btn-primary btn-lg"
                                                    id="btn-login">Login To Dashboard</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                    <hr>
                                    <div class="text-center text-dark opacity-8 mt-3">Copyright © ZONETEMP 2023</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--SCRIPTS INCLUDES-->
    <!--CORE-->
    <script data-main="<?php echo base_url() ?>assets/js/main/main-login"
        src="<?php echo base_url() ?>assets/js/require.js"></script>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
</body>



</html>