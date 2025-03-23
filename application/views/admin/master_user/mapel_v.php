<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Master Mapel</div>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="mapelForm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Nama Mapel & Jam</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris untuk setiap hari -->
                            <?php 
                                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                foreach ($days as $day) : 
                            ?>
                            <tr>
                                <td style="width: 15%;"><strong><?php echo $day; ?></strong></td>
                                <td>
                                    <div id="mapel-container-<?php echo $day; ?>">
                                        <!-- Kontainer untuk menampung input dinamis -->
                                    </div>
                                    <input type="hidden" name="hari[]" value="<?php echo $day; ?>">
                                    <button type="button" class="btn btn-sm btn-success mt-2 add-mapel" data-day="<?php echo $day; ?>">Tambah Mapel</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
  var id = <?php echo $id ?> 
</script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-master-user" src="<?php echo base_url() ?>assets/js/require.js">
</script>