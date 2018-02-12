<script>
    function check(){


        var supplier_account_no = document.getElementById("supplier_account_no").value;
        var supplier_account_name = document.getElementById("supplier_account_name").value;
        var supplier_account_bank = document.getElementById("supplier_account_bank").value;
        var supplier_account_branch = document.getElementById("supplier_account_branch").value;

       
        supplier_account_no = $.trim(supplier_account_no);
        supplier_account_name = $.trim(supplier_account_name);
        supplier_account_bank = $.trim(supplier_account_bank);
        supplier_account_branch = $.trim(supplier_account_branch);
        
        

        if(supplier_account_no.length == 0){
            alert("Please input account no");
            document.getElementById("supplier_account_no").focus();
            return false;
        }else if(supplier_account_name.length == 0){
            alert("Please input account name");
            document.getElementById("supplier_account_name").focus();
            return false;
        }else  if(supplier_account_bank.length == 0){
            alert("Please input bank name english");
            document.getElementById("supplier_account_bank").focus();
            return false;
        }else if(supplier_account_branch.length == 0){
            alert("Please input branch name");
            document.getElementById("supplier_account_branch").focus();
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
                Update Account 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=supplier_account&action=edit&id=<?php echo $supplier_id?>" enctype="multipart/form-data">
                    <input type="hidden" id="supplier_account_id" name="supplier_account_id" value="<?php echo $supplier_account_id?>"/>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Account No<font color="#F00"><b>*</b></font></label>
                                <input id="supplier_account_no" name="supplier_account_no"  class="form-control" value="<? echo $supplier_account['supplier_account_no'];?>">
                                <p class="help-block">Example : 123-2-35646-8.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Account name <font color="#F00"><b>*</b></font></label>
                                <input id="supplier_account_name" name="supplier_account_name" class="form-control" value="<? echo $supplier_account['supplier_account_name'];?>">
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Bank name <font color="#F00"><b>*</b></font></label>
                                <input id="supplier_account_bank" name="supplier_account_bank" class="form-control" value="<? echo $supplier_account['supplier_account_bank'];?>">
                                <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Branch <font color="#F00"><b>*</b></font></label>
                                <input id="supplier_account_branch" name="supplier_account_branch" class="form-control" value="<? echo $supplier_account['supplier_account_branch'];?>">
                                <p class="help-block">Example : BBK.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Detail </label>
                                <input id="supplier_account_detail" name="supplier_account_detail" class="form-control" value="<? echo $supplier_account['supplier_account_detail'];?>">
                                <p class="help-block">Example : -</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=supplier_account&action=view&id=<?php echo $supplier_id?>" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-success">Save</button>
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