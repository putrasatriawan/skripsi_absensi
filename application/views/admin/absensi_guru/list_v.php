<div class="app-main__outer">
    <div class="app-main__inner">
    <div class="main-card mb-3 card shadow-lg">
    <div class="card-body">
        <!-- Header -->
        <div class="text-center mb-4">
            <h3 class="font-weight-bold text-primary">Absensi <br> Check in/ Check out</h3>
        </div>

        <?php if ($photo): ?>
            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <h4 class="text-success">Anda sudah absen hari ini!</h4>
                    <img src="data:image/png;base64,<?= $photo ?>" alt="Attendance Photo" class="img-fluid rounded shadow-sm" />
                </div>
            </div>
        <?php else: ?>

        <!-- Card Biodata -->
        <div class="d-flex justify-content-center">
        <div class="card border-primary  shadow-lg">
            <div class="card-header bg-primary text-white text-center font-weight-bold">
                Biodata
            </div>
            <div class="card-body" style="font-size: 17px">
                <div class="row">
                    <div class="col-6 font-weight-bold py-2 border-bottom">Nama</div>
                    <div class="col-6 py-2 border-bottom">Putra</div>

                    <div class="col-6 font-weight-bold py-2 border-bottom">NIP</div>
                    <div class="col-6 py-2 border-bottom">123456</div>

                    <div class="col-6 font-weight-bold py-2 border-bottom">Jabatan</div>
                    <div class="col-6 py-2 border-bottom">Staff IT</div>

                    <div class="col-6 font-weight-bold py-2 border-bottom">Departemen</div>
                    <div class="col-6 py-2 border-bottom">Teknologi</div>

                    <div class="col-6 font-weight-bold py-2 border-bottom">Jam</div>
                    <div class="col-6 py-2 border-bottom" id="time-info"></div>

                    <div class="col-6 font-weight-bold py-2 border-bottom">Jarak</div>
                    <div class="col-6 py-2 border-bottom" id="distance-info"></div>
                </div>
            </div>
        </div>


        <?php endif; ?>
        </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <div class="location-box">
                    <p id="geo-location" class="text-muted">📍 Lokasi belum terdeteksi</p>
                    <button class="btn btn-outline-info get-location">🔍 Cek Lokasi</button>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <p id="submit-status" class="text-muted">Silakan ambil foto untuk melanjutkan absensi.</p>
            </div>
        </div>
    </div>
</div>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var definedLatitude = <?= json_encode($latitude) ?>;
    var definedLongitude = <?= json_encode($longitude) ?>;
</script>
<!-- Styling -->
<style>
   .logo-container {
        display: flex;
        justify-content: center; /* Membuat logo sejajar di tengah */
        gap: 15px; /* Jarak antara logo */
        margin-top: 20px; /* Jarak dari tombol */
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

    /* Center video and shoot button */
    #video,
    .shoot-button {
        display: block;
        margin: 20px auto;
        /* Center horizontally with margin */
    }
</style>


<script data-main="<?php echo base_url() ?>assets/js/main/main-absensi_guru"
    src="<?php echo base_url() ?>assets/js/require.js"></script>