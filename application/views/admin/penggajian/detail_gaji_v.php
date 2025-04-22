<?php
$grouped_users = [];
$total_jam_valid = $summary['total_jam_valid'];
$total_gaji = $summary['total_gaji'];
$total_pemotongan = $summary['total_pemotongan'];
$gaji_akhir = $summary['gaji_akhir'];
$user = $summary['user'];
$grouped_users = $summary['grouped_users'];
?>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Detail Gaji</div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card shadow-sm border-0">
            <div class="card-body">
                <?php if (!empty($summary)): ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informasi Penggajian</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Kode Waktu</th>
                                    <td>: <strong><?= $user->kode_config_master ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Bulan & Tahun</th>
                                    <td>: <strong><?= date('F Y', strtotime($user->created_at)) ?></strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Biodata User</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Nama</th>
                                    <td>: <strong><?= $user->name_user ?></strong></td>
                                </tr>
                                <tr>
                                    <th>NIP</th>
                                    <td>: <strong><?= $user->nip_master_user ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>: <strong><?= $user->jenis_kelamin_master_user ?></strong></td>
                                </tr>
                                <tr>
                                    <th>No HP</th>
                                    <td>: <strong><?= $user->no_hp_master_user ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Agama</th>
                                    <td>: <strong><?= $user->agama_master_user ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>: <strong><?= $user->alamat_master_user ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Detail Gaji</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Jam Mengajar (Valid)</th>
                                    <td><?= number_format($total_jam_valid, 2) ?> Jam</td>
                                </tr>
                                <tr>
                                    <th>Gaji per Jam</th>
                                    <td>Rp <?= number_format($user->gaji_master_user, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <th>Pemotongan</th>
                                    <td>Rp <?= number_format($total_pemotongan, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <th>Akumulasi Gaji</th>
                                    <td>Rp <?= number_format($total_gaji, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <th><strong>Gaji Akhir</strong></th>
                                    <td><strong class="text-success">Rp <?= number_format($gaji_akhir, 0, ',', '.') ?></strong></td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Jadwal Mengajar</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Hari</th>
                                        <th>Jam</th>
                                        <th>Durasi</th>
                                        <th>Mapel</th>
                                        <th>Gaji</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($grouped_users as $tanggal => $items): ?>
                                        <?php
                                        $totalDurasi = 0;
                                        $totalGaji = 0;
                                        ?>
                                        <?php foreach ($items as $index => $u): ?>
                                            <?php $durasi = $u->durasi_jam; ?>
                                            <tr>
                                                <?php if ($index === 0): ?>
                                                    <td rowspan="<?= count($items) ?>"><?= $u->tanggal ?></td>
                                                    <td rowspan="<?= count($items) ?>"><?= $u->hari ?></td>
                                                <?php endif; ?>
                                                <td><?= $u->jam_mulai ?> - <?= $u->jam_selesai ?></td>
                                                <td><?= $durasi ?> jam</td>
                                                <td><?= $u->nama_mapel ?></td>
                                                <?php
                                                $totalDurasi += $durasi;
                                                $totalGaji += $durasi * $u->gaji_master_user;
                                                ?>
                                                <?php if ($index === 0): ?>
                                                    <td rowspan="<?= count($items) ?>">Rp <?= number_format($totalGaji, 0, ',', '.') ?></td>
                                                <?php endif; ?>
                                                <td><?= $u->roles_check_in ?: '-' ?></td>
                                                <td><?= $u->roles_check_out ?: '-' ?></td>
                                                <td><?= $u->is_check_in ?: '-' ?></td>
                                                <td>
                                                    <?php
                                                    if (empty($u->tanggal_absen)) {
                                                        echo '<span class="text-danger">Tidak Hadir</span>';
                                                    } elseif ($u->status == 'hadir') {
                                                        echo '<span class="text-success">Tepat</span>';
                                                    } else {
                                                        echo ucfirst($u->status);
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total Jam Mengajar Valid</th>
                                        <th colspan="7"><?= number_format($total_jam_valid, 2) ?> Jam</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-end">
                            <h5>Gaji Akhir: <span class="text-success">Rp <?= number_format($gaji_akhir, 0, ',', '.') ?></span></h5>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-danger">Data tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>