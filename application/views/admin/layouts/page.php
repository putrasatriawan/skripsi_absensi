<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?> <?php   $this->load->view("admin/layouts/header");?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
   <div class="loading-container" id="loading">
            <div class="spinner"></div>
         </div>

    <input type="hidden"
           id="base_url"
           value="<?php echo base_url();?>">
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <?php $this->load->view("admin/layouts/topbar");?> <div class="app-main">
            <?php $this->load->view("admin/layouts/sidemenu");?> 
            <?php $this->load->view($content);?> <div>
            </div>
        </div>
        <div class="modal fade"
             id="alert_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body alert-msg">
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-sm btn-default alert-cancel"
                                data-dismiss="modal">Batal</button>
                        <button type="button"
                                class="btn btn-sm btn-danger alert-ok">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        
</body>

</html>