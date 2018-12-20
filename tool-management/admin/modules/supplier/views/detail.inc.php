
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Supplier Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Supplier Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=supplier&action=approve&id=<?php echo $supplier_id;?>" enctype="multipart/form-data" >
                <input type="hidden"  id="supplier_id" name="supplier_id" value="<?php echo $supplier_id ?>" />
                   
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                    <label>Supplier code  </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_code']?></p>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (Thai)  </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_name_th']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (English) </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_name_en']?></p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Supplier Type </label>
                                        <p class="help-block"><?php  echo $Supplier['supplier_type']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Tax. </label>
                                        <p class="help-block"><?php  echo $Supplier['supplier_tax']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Domestic </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_domestic']?></p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_branch']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Zone </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_zone']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Credit Day </label>
                                    <p class="help-block"><?php  echo $Supplier['credit_day']?> วัน</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Pay Type </label>
                                    <p class="help-block"><?php  echo $Supplier['condition_pay']?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Telephone </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_tel']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fax </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_fax']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_email']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 1 </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_address_1']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 2 </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_address_2']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>Address 3 </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_address_3']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Zipcode </label>
                                    <p class="help-block"><?php  echo $Supplier['supplier_zipcode']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Supplier Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/Supplier/<?php echo $Supplier['supplier_logo']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>ประเภทบัญชี / Account type </label>
                            <p class="help-block"><?PHP echo $Supplier['account_type_name'];?></p>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                            <p class="help-block">
                                <?PHP if($Supplier['vat_type'] == '0'){?> 0 - ไม่มี Vat <?PHP }?>
                                <?PHP if($Supplier['vat_type'] == '1'){?> 1 - รวม Vat <?PHP }?>
                                <?PHP if($Supplier['vat_type'] == '2'){?> 2 - แยก Vat <?PHP }?>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>ภาษีมูลค่าเพิ่ม / Vat </label>
                            <p class="help-block"><? echo $Supplier['vat']?></p>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>สกุลเงิน / Curreny. </label>
                            <p class="help-block"><?PHP echo $Supplier['currency_name_en'];?></p>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>เครดิต / Credit Day </label>
                            <input id="credit_day" name="credit_day" type="text" class="form-control"  value="30" style="text-align:right;" value="<? echo $Supplier['credit_day']?>">
                            <p class="help-block"><?PHP echo $Supplier['credit_day'];?></p>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>เงื่อนไขการชำระเงิน / Pay Type </label>
                            <p class="help-block"><?PHP echo $Supplier['condition_pay'];?></p>
                        </div>
                    </div>
                </div>
                    <!-- /.row (nested) -->
                <div class="row">
                    <?php if($user[0][24] == "High" || $user[0][25] == "High" ){ ?>
                        <div class="col-lg-offset-9 col-lg-2" align="right">
                            <select id="supplier_accept_status" name="supplier_accept_status" class="form-control" data-live-search="true" >
                                <option <?php if($Supplier['supplier_accept_status'] == "Waiting"){?> selected <?php }?> >Waiting</option>
                                <option <?php if($Supplier['supplier_accept_status'] == "Approve"){?> selected <?php }?> >Approve</option>
                                <option <?php if($Supplier['supplier_accept_status'] == "Not Approve"){?> selected <?php }?> >Not Approve</option>
                            </select>
                        </div>
                        <div class="col-lg-1" align="right">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    <?php } ?>
                </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>