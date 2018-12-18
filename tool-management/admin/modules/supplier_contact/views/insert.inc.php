<script>
    function check(){


        
        var supplier_contact_name = document.getElementById("supplier_contact_name").value;
        var supplier_contact_position = document.getElementById("supplier_contact_detail").value;
        
        supplier_contact_name = $.trim(supplier_contact_name);
        supplier_contact_position = $.trim(supplier_contact_position);
        
        

       if(supplier_contact_name.length == 0){
            alert("Please input contact name");
            document.getElementById("supplier_contact_name").focus();
            return false;
        }else  if(supplier_contact_position.length == 0){
            alert("Please input detail contact position");
            document.getElementById("supplier_contact_detail").focus();
            return false;
        }else{
            return true;
        }



    }

</script>

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
                Supplier Information. 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                    <label>Supplier code  </label>
                                    <p class="help-block"><? echo $supplier['supplier_code']?></p>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (Thai)  </label>
                                    <p class="help-block"><? echo $supplier['supplier_name_th']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (English) </label>
                                    <p class="help-block"><? echo $supplier['supplier_name_en']?></p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Supplier Type </label>
                                        <p class="help-block"><? echo $supplier['supplier_type']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Tax. </label>
                                        <p class="help-block"><? echo $supplier['supplier_tax']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Domestic </label>
                                    <p class="help-block"><? echo $supplier['supplier_domestic']?></p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <p class="help-block"><? echo $supplier['supplier_branch']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Zone </label>
                                    <p class="help-block"><? echo $supplier['supplier_zone']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Credit Day </label>
                                    <p class="help-block"><? echo $supplier['credit_day']?> วัน</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Pay Type </label>
                                    <p class="help-block"><? echo $supplier['condition_pay']?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Telephone </label>
                                    <p class="help-block"><? echo $supplier['supplier_tel']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fax </label>
                                    <p class="help-block"><? echo $supplier['supplier_fax']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email </label>
                                    <p class="help-block"><? echo $supplier['supplier_email']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 1 </label>
                                    <p class="help-block"><? echo $supplier['supplier_address_1']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 2 </label>
                                    <p class="help-block"><? echo $supplier['supplier_address_2']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>Address 3 </label>
                                    <p class="help-block"><? echo $supplier['supplier_address_3']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Zipcode </label>
                                    <p class="help-block"><? echo $supplier['supplier_zipcode']?></p>
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
                                    <img class="img-responsive" id="img_logo" src="../upload/Supplier/<?php echo $supplier['supplier_logo']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>



<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Person Contact 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form"  id="form_target" method="post" onsubmit="return check();" action="index.php?app=supplier_contact&action=add&id=<?php echo $supplier_id?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Name<font color="#F00"><b>*</b></font></label>
                                <input id="supplier_contact_name" name="supplier_contact_name"  class="form-control">
                                <p class="help-block">Example : คุณวินัย ชาญชัย.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Position <font color="#F00"><b>*</b></font></label>
                                <input id="supplier_contact_position" name="supplier_contact_position" class="form-control">
                                <p class="help-block">Example : ผู้จัดการฝ่ายผลิต.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Telephone </label>
                                <input id="supplier_contact_tel" name="supplier_contact_tel" class="form-control" >
                                <p class="help-block">Example : 061-0243003.</p>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input id="supplier_contact_email" name="supplier_contact_email"  class="form-control">
                                <p class="help-block">Example : winai.ch@gmail.com.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Detail </label>
                                <input id="supplier_contact_detail" name="supplier_contact_detail" class="form-control">
                                <p class="help-block">Example : - .</p>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=supplier_contact&action=view&id=<?php echo $supplier_id?>" class="btn btn-primary">Back</a>
                            <button  type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>