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
                <!-- Informasi Umum -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Penggajian</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Kode Waktu</th>
                                <td>: <strong>KW-2025-04</strong></td>
                            </tr>
                            <tr>
                                <th>Bulan & Tahun</th>
                                <td>: <strong>April 2025</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Biodata User</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Nama</th>
                                <td>: <strong>John Doe</strong></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: <strong>john.doe@example.com</strong></td>
                            </tr>
                            <tr>
                                <th>Jabatan</th>
                                <td>: <strong>Guru Matematika</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Informasi Gaji -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Detail Gaji</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Gaji per Jam</th>
                                <td>Rp 100.000</td>
                            </tr>
                            <tr>
                                <th>Total Jam Mengajar</th>
                                <td>40 Jam</td>
                            </tr>
                            <tr>
                                <th>Pemotongan</th>
                                <td>40.000</td>
                            </tr>
                            <tr>
                                <th>Akumulasi Gaji</th>
                                <td>Rp 4.000.000</td>
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
                                    <th>Jumlah Jam</th>
                                    <th>Mapel</th>
                                    <th>Kelas</th>
                                    <th>Gaji</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Senin</td>
                                    <td>13:00 - 15:00</td>
                                    <td>3</td>
                                    <td>MTK</td>
                                    <td>10 B</td>
                                    <td>30.000</td>
                                    <td>13:00</td>
                                    <td>15:00</td>
                                    <td>Tepat</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Rabu</td>
                                    <td>14:00 - 16:00</td>
                                    <td>3</td>
                                    <td>TIK</td>
                                    <td>11 A</td>
                                    <td>30.000</td>
                                    <td>14:00</td>
                                    <td>16:00</td>
                                    <td>Tepat</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Jumat</td>
                                    <td>13:00 - 15:00</td>
                                    <td>3</td>
                                    <td>BINDO</td>
                                    <td>10 C</td>
                                    <td>30.000</td>
                                    <td>14:00</td>
                                    <td>15:00</td>
                                    <td>Terlambat</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Gaji Akhir -->
                <div class="row">
                    <div class="col-md-12 text-right">
                        <h5>Gaji Akhir: <span class="text-success">Rp 4.000.000</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script data-main="<?php echo base_url() ?>assets/js/main/main-penggajian"
    src="<?php echo base_url() ?>assets/js/require.js"></script>