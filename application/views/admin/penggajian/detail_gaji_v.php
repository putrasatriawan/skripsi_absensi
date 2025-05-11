<div class="app-main__outer">
  <div class="app-main__inner">
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div>Detail Gaji</div>
        </div>
      </div>
    </div>

    <div class="main-card mb-3 card shadow-lg border-0">
      <div class="card-body">
      <div class="main-card mb-3 card shadow-sm border-0">
  <div class="card-body">
    <h5 class="card-title text-primary">Informasi Guru</h5>
    <div class="row">
      <div class="col-md-6">
        <table class="table table-sm table-borderless">
          <tr>
            <th>Nama</th>
            <td>: <?= $master_user[0]->name ?></td>
          </tr>
          <tr>
            <th>Username</th>
            <td>: <?= $master_user[0]->nip ?></td>
          </tr>
          <tr>
            <th>Jenis Kelamin</th>
            <td>: <?= $master_user[0]->jenis_kelamin ?></td>
          </tr>
          <tr>
            <th>Agama</th>
            <td>: <?= $master_user[0]->agama ?></td>
          </tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-sm table-borderless">
          <tr>
            <th>No. HP</th>
            <td>: <?= $master_user[0]->no_hp ?></td>
          </tr>
          <tr>
            <th>Alamat</th>
            <td>: <?= $master_user[0]->alamat ?></td>
          </tr>
          <tr>
            <th>Gaji Per Jam</th>
            <td>: Rp <?= number_format($master_user[0]->gaji, 0, ',', '.') ?></td>
          </tr>
          <tr>
            <th>Pemotongan</th>
            <td>: Rp <?= number_format($master_user[0]->pemotongan ?? 0, 0, ',', '.') ?> / <?= $master_user[0]->type_pemotongan ?></td>
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
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Durasi</th>
                <th>Status Mapel</th>
                <th>Log Absensi</th>
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
                        echo ucfirst($detail->absen_list[0]->status_mapel ?? '-');
                      } else {
                        echo '<span class="text-muted">Belum Absen</span>';
                      }
                    ?>
                  </td>
                  <td class="text-start">
                    <?php if (!empty($detail->absen_list)): ?>
                      <ul class="list-unstyled mb-0 ps-3">
                        <?php foreach ($detail->absen_list as $absen): ?>
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
