<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Report Gaji - <?= $master_user[0]->name ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .bg-light {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h2>Laporan Gaji Guru</h2>

    <table class="no-border">
        <tr>
            <th>Nama</th>
            <td>: <?= $master_user[0]->name ?></td>
            <th>No. HP</th>
            <td>: <?= $master_user[0]->no_hp ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td>: <?= $master_user[0]->nip ?></td>
            <th>Alamat</th>
            <td>: <?= $master_user[0]->alamat ?></td>
        </tr>
        <tr>
            <th>Jenis Kelamin</th>
            <td>: <?= $master_user[0]->jenis_kelamin ?></td>
            <th>Gaji Per Jam</th>
            <td>: Rp <?= number_format($master_user[0]->gaji, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Agama</th>
            <td>: <?= $master_user[0]->agama ?></td>
            <th>Pemotongan</th>
            <td>: Rp <?= number_format($master_user[0]->pemotongan ?? 0, 0, ',', '.') ?> / <?= $master_user[0]->type_pemotongan ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td>: <?= $master_user[0]->role_name ?></td>
        </tr>
        <tr>
            <th>Jam Masuk</th>
            <td>: <?= $master_user[0]->check_in ?></td>
        </tr>
        <tr>
            <th>Jam Keluar</th>
            <td>: <?= $master_user[0]->check_out ?></td>
        </tr>

    </table>

    <h3>Jadwal Mapel dan Rekap Absensi</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Nama Mapel</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Durasi</th>
                <th>Kehadiran Mapel</th>
                <th>Log Mapel</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($merged_detail_absen as $index => $detail): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= $detail->hari ?></td>
                    <td><?= $detail->tanggal ?></td>
                    <td><?= $detail->name_mapel ?></td>
                    <td><?= $detail->jam_mulai ?></td>
                    <td><?= $detail->jam_selesai ?></td>
                    <td><?= $detail->durasi ?></td>
                    <td>
                        <?php
                        if (!empty($detail->absen_list)) {
                            $filtered_absensi = array_filter($detail->absen_list, function ($absen) use ($detail) {
                                return $absen->id_mapel == $detail->id_mapel;
                            });
                            if (!empty($filtered_absensi)) {
                                $status_list = [];
                                foreach ($filtered_absensi as $absen) {
                                    $status_list[] = ucfirst($absen->status_mapel ?? '-');
                                }
                                echo implode(', ', $status_list);
                            } else {
                                echo '<span class="text-muted">Belum Absen</span>';
                            }
                        } else {
                            echo '<span class="text-muted">Belum Absen</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if (!empty($detail->absen_list)): ?>
                            <ul class="list-unstyled mb-0 ps-3">
                                <?php
                                $seen = [];
                                foreach ($detail->absen_list as $absen):
                                    $key = $absen->init_time . '|' . $absen->is_check_in . '|' . $absen->status . '|' . $absen->status_work;

                                    if (in_array($key, $seen)) continue;

                                    $seen[] = $key;
                                ?>
                                    <li>
                                        <?= date('H:i', strtotime($absen->init_time)) ?> -
                                        <?= ucfirst($absen->is_check_in) ?> |
                                        <strong><?= $absen->status ?></strong>
                                        (<?= $absen->status_work ?>)
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <span class="text-danger">Tidak Ada Absensi</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Total Durasi</th>
                <td colspan="3"><?= number_format($total_durasi, 2) ?> Jam</td>
            </tr>
            <tr>
                <th colspan="6" class="text-right">Total Durasi Hadir</th>
                <td colspan="3"><?= number_format($total_durasi_hadir, 2) ?> Jam</td>
            </tr>
            <tr>
                <th colspan="6" class="text-right">Gaji Tanpa Pemotongan</th>
                <td colspan="3">Rp <?= number_format($total_gaji, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th colspan="6" class="text-right">Total Pemotongan</th>
                <td colspan="3">Rp <?= number_format($total_pemotongan, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <th colspan="6" class="text-right text-bold">Total Gaji Setelah Pemotongan</th>
                <td colspan="3" class="text-bold">Rp <?= number_format($total_gaji_pemotongan, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

</body>

</html>