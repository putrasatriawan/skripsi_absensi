<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card shadow-lg">
            <div class="card-body">
                <?php if ($show_view): ?>
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-primary">Absensi <br> Check in / Check out</h3>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <h4 class="text-success">✅ Anda Sudah <?php echo $label_check ?></h4>
                            <img src="data:image/png;base64,<?= $photo ?>" alt="Attendance Photo" class="img-fluid rounded shadow-sm mb-4" style="width: 200px; height: auto;" />

                            <div class="card p-3 mb-3">
                                <div class="d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="text-info">💼 Status Kerja:</span><br>
                                        <strong><?= $status_work ?></strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-info">📋 Status:</span><br>
                                        <strong><?= $status ?></strong>
                                    </div>
                                    <div>
                                        <span class="text-info">🕒 Init Time:</span><br>
                                        <strong><?= $init_time ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>

                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold text-primary">Absensi <br> Check in / Check out</h3>
                    </div>

                    <hr>

                    <!-- Card Biodata -->
                    <div class="d-flex justify-content-center">
                        <div class="card border-primary shadow-lg" style="border-radius: 20px; overflow: hidden;">
                            <div class="d-flex justify-content-between align-items-center bg-primary text-white p-3" style="border-radius: 20px 20px 0 0;">
                                <span style="font-size: 1.2em;">Biodata</span>
                                <div class="d-flex">
                                    <div class="sinyal shadow mx-2"></div>
                                    <div class="green_sinyal shadow d-none"></div>
                                </div>
                            </div>
                            <div class="card-body" style="font-size: 17px">
                                <div class="row">
                                    <div class="col-6 font-weight-bold py-2 border-bottom">Nama</div>
                                    <div class="col-6 py-2 border-bottom">
                                        <?php echo (isset($user_master->first_name) ? $user_master->first_name : '') . ' ' . (isset($user_master->last_name) ? $user_master->last_name : ''); ?>
                                    </div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">NIP</div>
                                    <div class="col-6 py-2 border-bottom">
                                        <?php echo isset($user_master->nik) ? $user_master->nik : '-'; ?>
                                    </div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Jabatan</div>
                                    <div class="col-6 py-2 border-bottom">
                                        <?php echo isset($user_master->role_name) ? $user_master->role_name : '-'; ?>
                                    </div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Jam Masuk</div>
                                    <div class="col-6 py-2 border-bottom" id="check_in">
                                        <?php echo isset($config_check[0]->check_in) ? $config_check[0]->check_in : '-'; ?>
                                    </div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Jam Keluar</div>
                                    <div class="col-6 py-2 border-bottom" id="check_out">
                                        <?php echo isset($config_check[0]->check_out) ? $config_check[0]->check_out : '-'; ?>
                                    </div>


                                    <?php
                                    $hari = [
                                        'Sunday' => 'Minggu',
                                        'Monday' => 'Senin',
                                        'Tuesday' => 'Selasa',
                                        'Wednesday' => 'Rabu',
                                        'Thursday' => 'Kamis',
                                        'Friday' => 'Jumat',
                                        'Saturday' => 'Sabtu'
                                    ];

                                    $namaHari = $hari[date('l')];
                                    $tanggal = date('d-m-Y');
                                    ?>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Tanggal</div>
                                    <div class="col-6 py-2 border-bottom">
                                        <?php echo "$namaHari, $tanggal"; ?>
                                    </div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Jam</div>
                                    <div class="col-6 py-2 border-bottom" id="time-info"></div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Jarak</div>
                                    <div class="col-6 py-2 border-bottom" id="distance-info"></div>

                                    <div class="col-6 font-weight-bold py-2 border-bottom">Status</div>
                                    <div class="col-6 py-2 border-bottom" id="status-info"></div>
                                </div>
                            </div>

                        </div>



                    </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12 text-center">
                    <div class="location-box">
                        <p id="geo-location" class="text-muted">📍 Lokasi belum terdeteksi</p>
                        <!-- <button class="btn btn-outline-info get-location">🔍 Cek Lokasi</button> -->
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center" id="open-camera">
                <button class="btn btn-lg btn-outline-primary pulse">
                    📸 Buka Kamera untuk Absen
                </button>
                <div class="camera-container" style="width: 320px; height: 320px; margin: auto; position: relative; overflow: hidden; border-radius: 10px;">
                    <video id="video" width="100%" height="100%" autoplay style="object-fit: cover;"></video>
                </div>
                <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>
                <button class="btn btn-lg btn-outline-success shoot-button" style="display: none;">
                    📸 Shoot
                </button>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <p id="submit-status" class="text-muted">Silakan ambil foto untuk melanjutkan absensi.</p>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div id="loadingModal" style="display: none;">
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 8px; text-align: center;">
            <h5>Sebentar, Lokasi Anda Sedang Dipindai...</h5>
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var definedLatitude = <?= json_encode($latitude) ?>;
    var definedLongitude = <?= json_encode($longitude) ?>;
</script>
<!-- Styling -->
<style>
    .logo-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .logo-bulat {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .merah {
        background-color: red;
    }

    .hijau {
        background-color: green;
    }

    .card {
        border-radius: 15px;
        background-color: #f9f9f9;
    }

    .info-box,
    .time-box,
    .location-box {
        padding: 20px;
        border: 1px solid #ebebeb;
        border-radius: 10px;
        margin-bottom: 20px;
        background-color: #fff;
    }

    .btn-outline-primary {
        padding: 15px 40px;
        font-size: 1.2em;
        transition: 0.3s;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.4);
        }

        70% {
            box-shadow: 0 0 0 30px rgba(0, 123, 255, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }

    .location-box button {
        font-size: 1.1em;
        padding: 10px 30px;
    }

    #video,
    .shoot-button {
        display: block;
        margin: 20px auto;
    }

    .sinyal,
    .green_sinyal {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: red;
    }

    .green_sinyal {
        background-color: green;
    }
</style>


<script data-main="<?php echo base_url() ?>assets/js/main/main-absensi"
    src="<?php echo base_url() ?>assets/js/require.js"></script>