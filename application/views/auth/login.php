<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Absensi Guru</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .main-head {
            height: 150px;
            background: #FFF;
        }

         .sidenav {
            height: 100%;
            background-color: #2c3e50; /* Dark blue color */
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main {
            padding: 0px 10px;
        }

        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }
        }

        @media screen and (max-width: 450px) {
            .login-form {
                margin-top: 10%;
            }

            .register-form {
                margin-top: 10%;
            }
        }

        @media screen and (min-width: 768px) {
            .main {
                margin-left: 40%;
            }

            .sidenav {
                width: 40%;
                position: fixed;
                z-index: 1;
                top: 0;
                left: 0;
            }

            .login-form {
                margin-top: 80%;
            }

            .register-form {
                margin-top: 20%;
            }
        }

        .login-main-text {
            margin-top: 20%;
            padding: 60px;
            color: #fff;
        }

        .login-main-text h2 {
            font-weight: 300;
        }

        .btn-black {
            background-color: #000 !important;
            color: #fff;
        }

        /* Style for the logo */
        .logo {
            width: 150px; /* Adjust the size */
            height: auto;
            margin-right: 15px;
        }
    </style>
</head>

<body>
    <div class="sidenav">
        <div class="login-main-text">
            <!-- Logo Added here -->
            <h2><br>Sistem Informasi <br>Absensi Guru</h2>
            <p>Silakan login untuk melakukan presensi harian.</p>
            <img src="<?php  echo base_url(); ?>uploads/logo.png?>" class="logo" alt="Logo">  
        </div>
    </div>

    <div class="main">
        <div class="col-md-6 col-sm-12">
            <div class="login-form">
                <form action="<?php echo base_url(); ?>Auth/login" method="post" id="form-login">
                    <?php if (!empty($this->session->flashdata('message_error'))) { ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('message_error'); ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" id="username"
                            placeholder="Masukkan Username" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Masukkan Password" autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-black btn-lg" id="btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script data-main="<?php echo base_url() ?>assets/js/main/main-login"
        src="<?php echo base_url() ?>assets/js/require.js"></script>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
</body>

</html>
