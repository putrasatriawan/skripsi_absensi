<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Edit Jabatan <div class="page-title-subheading"> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8 offset-md-2">
                <form method="POST" id="form" enctype="multipart/form-data">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                          
                            <div class="form-group row">
                                <label class="col-sm-3" for="">Nama Jabatan</label>
                                <div class="col-sm-9">
                                    <input class="form-control" value="<?php echo $name;?>" type="name" id="name" name="name" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3" for="">Deskripsi</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="description" name="description" autocomplete="off"><?php echo $description;?></textarea>
                                </div>    
                            </div>    
                        </div>              
                        <div class="card-footer bg-light align-items-center justify-content-end">
                            <a href="<?php echo base_url() ?>role"
                               class="btn btn-outline-secondary mr-2">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-times fa-w-20"></i>
                                </span>Batal </a>
                            <button type="submit" 
                            class="btn btn-primary">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-save fa-w-20"></i>
                                </span>Simpan </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script data-main="<?php echo base_url()?>assets/js/main/main-role" src="<?php echo base_url()?>assets/js/require.js"></script>

  