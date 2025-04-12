<div class="app-main__outer">
  <div class="app-main__inner">
    <div class="app-page-title">
      <div class="page-title-wrapper">
        <div class="page-title-heading">
          <div>Master User</div>
        </div>
        <div class="page-title-actions">

        </div>
      </div>
    </div>
    <div class="main-card mb-3 card">
      <div class="card-body">
        <?php if (!empty($this->session->flashdata('message'))) { ?>
          <div class="alert alert-info">
            <?php echo $this->session->flashdata('message'); ?>
          </div>
        <?php } ?>

        <?php if (!empty($this->session->flashdata('message_error'))) { ?>
          <div class="alert alert-info">
            <?php echo $this->session->flashdata('message_error'); ?>
          </div>
        <?php } ?>

        <table id="table_waktu_detail" class="table table-bordered">
          <thead>
            <tr>
              <th style="width:15%">Hari</th>
              <th style="width:15%">Tanggal</th>
              <th>Pengampu</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tanggal_list as $row): ?>
            <tr>
              <td><?= $row['hari'] ?></td>
              <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
              <td>
                <?php if (isset($mapel_by_hari[$row['hari']])): ?>
                  <select name="pengampu[<?= $row['tanggal'] ?>][]" multiple class="form-control select2" data-placeholder="Pilih pengampu">
                    <?php foreach ($mapel_by_hari[$row['hari']] as $m): ?>
                      <option value="<?= $m['id_user'] ?>"><?= $m['id_user'] ?> / <?= $m['nama_mapel'] ?></option>
                    <?php endforeach; ?>
                  </select>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>
      <div class="card-footer bg-light align-items-center justify-content-end">
        <a href="<?php echo base_url() ?>master_user"
          class="btn btn-outline-secondary mr-2">
          <span class="btn-icon-wrapper pr-2 opacity-7">
            <!-- <i class="fa fa-back fa-w-20"></i> -->
          </span>Kembali </a>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-master-user" src="<?php echo base_url() ?>assets/js/require.js">
</script>