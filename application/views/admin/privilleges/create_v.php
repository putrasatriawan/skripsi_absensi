<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>Tambah Hak Akses <div class="page-title-subheading"> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <form method="POST"
                  enctype="
                  multipart/form-data">
                <div class="main-card mb-3 card">
                    <div class="card-body"> 
                  <?php if(!empty($this->session->flashdata('message_error'))){?>
                  <div class="alert alert-danger">
                  <?php   
                     print_r($this->session->flashdata('message_error'));
                  ?>
                  </div>
                  <?php }?> 
                  <div class="form-group row">
                    <label class="col-sm-3 form-control-label">Pilih Jabatan</label>
                    <div class="col-sm-9">
                      <select id="role_id" name="role_id" class="form-control">
                           <option value="">Pilih Jabatan</option>
                          <?php foreach($roles as $role){?>
                          <option value="<?php echo $role->id;?>"><?php echo $role->name;?></option>
                          <?php }?>
                      </select>
                    </div>
                  </div>
                  <div class="box-divider mbot-15"></div>
                  <table class="table table-striped">
                      <thead>
                        <th style="width:70px"> <label class="mar-0 control control--checkbox"> <input type="checkbox" name="checkAll" id="checkAll"> <div class="control__indicator" style="top:-13px;"></div></label ></th>
                        <th>Menu</th>
                        <th>Fungsi</th>
                      </thead>
                      <?php 
                    
                      foreach($menus as $key => $data_menu){?>
                        <tr>
                          <td class="valign-mid"> 
                            <label class="control control--checkbox"> 
                              <input type="checkbox" class="cb-element" name="menus[]" value="<?php echo $data_menu['id'];?>">
                              <div class="control__indicator" style="top:4px;"></div>
                            </label> 
                          </td>
                          <td class="valign-mid"> <span class="valign-mid"><?php echo $data_menu['name'];?></span>
                              <div class="btn-group dropdown pull-right padright-15 borright-soft-grey">
                                <button class="btn white dropdown-toggle btn-sm hide-caret" data-toggle="dropdown">Pilih Fungsi</button>
                                <div class="pad-10 dropdown-menu dropdown-menu-form dropdown-menu-scale pull-right">
                                  <?php    
                                  foreach($data_menu['functions'] as $function){   
                                    ?> 
                                     <label class="col-12 control control--checkbox"><span class="pull-left fsize-14 padleft-10"><?php echo $function['name']?> </span><input type="checkbox" class="cb-element-child" name="functions[<?php echo $data_menu['id'];?>][]" value="<?php echo $function['id']?>"><div class="control__indicator no-bg-right"></div></label>
                                  <?php  }?>
                                </div>
                              </div>                 
                          </td>
                          <td class="valign-mid">
                            <?php    
                                    foreach($data_menu['functions'] as $function){   
                                     
                                  ?>
                                    <span class="label fsize-14 w-normal marright-5 text-black">
                                        <?php echo $function['name']?>
                                      </span>
                                    
                                <?php  }?>   
                            </td>
                        </tr>
                      <?php }?>
                    </table>
                    </div>
                    <div class="card-footer bg-light align-items-center justify-content-end">
                    <a href="<?php echo base_url('privileges');?>" class="btn btn-default">Batal</a>
                    <button id="save-btn" type="submit" class="btn btn-primary" >Simpan</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>

<script data-main="<?php echo base_url()?>assets/js/main/main-privilleges" src="<?php echo base_url()?>assets/js/require.js"></script>