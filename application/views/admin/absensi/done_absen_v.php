<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card shadow-lg border-0">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-4x text-success"></i>
                    <h3 class="mt-3 mb-2">Status Absensi</h3>
                    <hr class="w-25 mx-auto">
                    <p id="geo-location" class="text-muted lead mb-0 animate-fade-in">Anda Telah Absen</p>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12 text-center">
                    <div class="location-box mt-2">
                        <small class="text-secondary">Data lokasi Anda telah tercatat dengan aman.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome (untuk icon) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    .animate-fade-in {
        animation: fadeIn ease 1.5s;
        -webkit-animation: fadeIn ease 1.5s;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .main-card {
        background: #f9f9fb;
        border-radius: 16px;
    }

    .fa-check-circle {
        animation: popIn 0.6s ease;
    }
</style>
<script data-main="<?php echo base_url() ?>assets/js/main/main-absensi"
    src="<?php echo base_url() ?>assets/js/require.js"></script>