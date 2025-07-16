<div class="app-main__outer">
  <div class="app-main__inner">
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div>Detail Gaji</div>
        </div>
        <div class="page-title-actions">
          <a href="<?php echo base_url('penggajian/report/' . $id . '/' . $id_config_master); ?>" target="_blank">
            <div class="btn-shadow mr-3 btn btn-success">
              <span class="btn-icon-wrapper pr-2 opacity-7">
                <i class="fa fa-download fa-w-20"></i>
              </span> Download Report
            </div>
          </a>
        </div>
      </div>

      <div class="main-card mb-3 mt-4 card shadow-lg border-0">
        <div class="card-body">
          <div class="main-card mb-3 card shadow-sm border-0">
            <div class="card-body">
              <h5 class="card-title text-primary">Informasi Guru</h5>
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-sm table-borderless">
                    <tr>
                      <th class="text-left">Nama</th>
                      <td class="text-left">: <?= $master_user[0]->name ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Username</th>
                      <td class="text-left">: <?= $master_user[0]->nip ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Jenis Kelamin</th>
                      <td class="text-left">: <?= $master_user[0]->jenis_kelamin ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Agama</th>
                      <td class="text-left">: <?= $master_user[0]->agama ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Role</th>
                      <td class="text-left">: <?= $master_user[0]->role_name ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Jam Masuk</th>
                      <td class="text-left">: <?= $master_user[0]->check_in ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Jam Keluar</th>
                      <td class="text-left">: <?= $master_user[0]->check_out ?></td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table class="table table-sm table-borderless">
                    <tr>
                      <th class="text-left">No. HP</th>
                      <td class="text-left">: <?= $master_user[0]->no_hp ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Alamat</th>
                      <td class="text-left">: <?= $master_user[0]->alamat ?></td>
                    </tr>
                    <tr>
                      <th class="text-left">Gaji Per Jam</th>
                      <td class="text-left">:
                        <?php
                        if (!empty($master_user) && isset($master_user[0]->gaji) && $master_user[0]->gaji !== "" && $master_user[0]->gaji !== null) {
                          echo "Rp " . number_format((float) $master_user[0]->gaji, 0, ',', '.');
                        } else {
                          echo "-";
                        }
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <th class="text-left">Pemotongan</th>
                      <td class="text-left">: Rp <?= number_format($master_user[0]->pemotongan ?? 0, 0, ',', '.') ?> / <?= $master_user[0]->type_pemotongan ?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

          </div>

          <h5 class="card-title text-primary">Jadwal Mapel dan Rekap Absensi</h5>
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Hari</th>
                  <th>Tanggal</th>
                  <th>Nama Mapel</th>
                  <th>Jam Mulai Mapel</th>
                  <th>Jam Selesai Mapel</th>
                  <th>Durasi</th>
                  <th>Kehadiran Mapel</th>
                  <th>Log Mapel</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($merged_detail_absen as $index => $detail): ?>
                  <tr>
                    <td><?= $index + 1 ?></td>
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
                    <td class="text-start">
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
                <tr class="bg-light">
                  <th colspan="6" class="text-end">Total Durasi</th>
                  <th colspan="3"><?= number_format($total_durasi, 2) ?> Jam</th>
                </tr>
                <tr class="table-success">
                  <th colspan="6" class="text-end">Total Durasi Hadir</th>
                  <th colspan="3"><?= number_format($total_durasi_hadir, 2) ?> Jam</th>
                </tr>
                <tr class="table-info">
                  <th colspan="6" class="text-end">Gaji Tanpa Pemotongan</th>
                  <th colspan="3">Rp <?= number_format($total_gaji, 0, ',', '.') ?></th>
                </tr>
                <tr class="table-warning">
                  <th colspan="6" class="text-end">Total Pemotongan</th>
                  <th colspan="3">Rp <?= number_format($total_pemotongan, 0, ',', '.') ?></th>
                </tr>
                <tr class="table-primary">
                  <th colspan="6" class="text-end"><strong>Total Gaji Setelah Pemotongan</strong></th>
                  <th colspan="3"><strong>Rp <?= number_format($total_gaji_pemotongan, 0, ',', '.') ?></strong></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>