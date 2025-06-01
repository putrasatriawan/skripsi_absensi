<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block">Beranda</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Statistik Singkat -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light p-3 shadow-sm">
                    <h5 class="text-muted">Total Guru</h5>
                    <h3 class="text-primary"><?php echo $total_guru ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light p-3 shadow-sm">
                    <h5 class="text-muted">Kehadiran Bulan Ini</h5>
                    <h3 class="text-success"><?php echo $get_absen_this_month ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Collapse Utama -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <strong>Pengguna Berdasarkan Role</strong>
                        <button class="btn btn-sm btn-light" data-toggle="collapse" data-target="#collapseRoleMain" aria-expanded="false" aria-controls="collapseRoleMain">
                            Tampilkan Semua
                        </button>
                    </div>

                    <div id="collapseRoleMain" class="collapse">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if (!empty($grouped_absensi)): ?>
                                        <?php $index = 0;
                                        foreach ($grouped_absensi as $role => $users): ?>
                                            <?php $collapseId = 'collapseRole' . $index++; ?>
                                            <div class="card mb-2 shadow-sm">
                                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                                    <strong class="small"><?= htmlspecialchars($role) ?></strong>
                                                    <button class="btn btn-sm btn-link p-0 text-primary" data-toggle="collapse" data-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                                                        ▼
                                                    </button>
                                                </div>
                                                <div id="<?= $collapseId ?>" class="collapse">
                                                    <div class="card-body p-2">
                                                        <ul class="list-group list-group-flush small">
                                                            <?php foreach ($users as $user): ?>
                                                                <li class="list-group-item py-1 px-2">
                                                                    <strong><?= htmlspecialchars($user->first_name) ?></strong>
                                                                    (<?= htmlspecialchars($user->username) ?> - <?= htmlspecialchars($user->email) ?>)
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="alert alert-warning small">Tidak ada data pengguna tersedia.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart Total User -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div id="userRolesPieChart" style="width: 100%; height: 400px;"></div>
            </div>

            <div class="col-md-6">
                <div id="barChartAbsensi" style="width: 100%; height: 400px;"></div>
            </div>
        </div>



        <!-- Daftar Pengguna Per Role -->

    </div>
</div>

<!-- Highcharts & RequireJS -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-dashboard"
    src="<?php echo base_url() ?>assets/js/require.js"></script>