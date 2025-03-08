<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block">Beranda</span>
                        </div>
                        <div class="page-title-head center-elem">
                            <a href="<?php echo base_url('report/index'); ?>" class="btn-shadow mr-3 btn btn-success">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-file-pdf-o fa-w-20"></i>
                                </span> Generate PDfff
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div id="userRolesPieChart" style="width: 100%; height: 480px;"></div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="attendanceDateFilter">Filter by Date:</label>
                    <input type="date" id="attendanceDateFilter" class="form-control" />
                </div>

                <div id="attendanceBarChart" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script data-main="<?php echo base_url() ?>assets/js/main/main-dashboard"
    src="<?php echo base_url() ?>assets/js/require.js"></script>