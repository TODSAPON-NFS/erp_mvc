<script>
    function check(){


        var customer_account_no = document.getElementById("customer_account_no").value;
        var customer_account_name = document.getElementById("customer_account_name").value;
        var customer_account_bank = document.getElementById("customer_account_bank").value;
        var customer_account_branch = document.getElementById("customer_account_branch").value;

       
        customer_account_no = $.trim(customer_account_no);
        customer_account_name = $.trim(customer_account_name);
        customer_account_bank = $.trim(customer_account_bank);
        customer_account_branch = $.trim(customer_account_branch);
        
        

        if(customer_account_no.length == 0){
            alert("Please input account no");
            document.getElementById("customer_account_no").focus();
            return false;
        }else if(customer_account_name.length == 0){
            alert("Please input account name");
            document.getElementById("customer_account_name").focus();
            return false;
        }else  if(customer_account_bank.length == 0){
            alert("Please input bank name english");
            document.getElementById("customer_account_bank").focus();
            return false;
        }else if(customer_account_branch.length == 0){
            alert("Please input branch name");
            document.getElementById("customer_account_branch").focus();
            return false;
        }else{
            return true;
        }



    }

</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Customer Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Customer Information. 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                    <label>Customer code  </label>
                                    <p class="help-block"><? echo $customer['customer_code']?></p>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Customer name (Thai)  </label>
                                    <p class="help-block"><? echo $customer['customer_name_th']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Customer name (English) </label>
                                    <p class="help-block"><? echo $customer['customer_name_en']?></p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Customer Type </label>
                                        <p class="help-block"><? echo $customer['customer_type']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Tax. </label>
                                        <p class="help-block"><? echo $customer['customer_tax']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Domestic </label>
                                    <p class="help-block"><? echo $customer['customer_domestic']?></p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <p class="help-block"><? echo $customer['customer_branch']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Zone </label>
                                    <p class="help-block"><? echo $customer['customer_zone']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Credit Day </label>
                                    <p class="help-block"><? echo $customer['credit_day']?> วัน</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Pay Type </label>
                                    <p class="help-block"><? echo $customer['condition_pay']?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Telephone </label>
                                    <p class="help-block"><? echo $customer['customer_tel']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fax </label>
                                    <p class="help-block"><? echo $customer['customer_fax']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email </label>
                                    <p class="help-block"><? echo $customer['customer_email']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 1 </label>
                                    <p class="help-block"><? echo $customer['customer_address_1']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 2 </label>
                                    <p class="help-block"><? echo $customer['customer_address_2']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>Address 3 </label>
                                    <p class="help-block"><? echo $customer['customer_address_3']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Zipcode </label>
                                    <p class="help-block"><? echo $customer['customer_zipcode']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Customer Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/customer/<?php if($customer['customer_logo'] != ''){echo $customer['customer_logo'];}else{echo 'default.png';}  ?>" />
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
                Add Account 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_account&action=add&id=<?php echo $customer_id?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Account No<font color="#F00"><b>*</b></font></label>
                                <input id="customer_account_no" name="customer_account_no"  class="form-control">
                                <p class="help-block">Example : 123-2-35646-8.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Account name <font color="#F00"><b>*</b></font></label>
                                <input id="customer_account_name" name="customer_account_name" class="form-control">
                                <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Bank name <font color="#F00"><b>*</b></font></label>
                                <input id="customer_account_bank" name="customer_account_bank" class="form-control" >
                                <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Branch <font color="#F00"><b>*</b></font></label>
                                <input id="customer_account_branch" name="customer_account_branch" class="form-control" >
                                <p class="help-block">Example : BBK.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Detail </label>
                                <input id="customer_account_detail" name="customer_account_detail" class="form-control">
                                <p class="help-block">Example : -</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=customer_account&action=view&id=<?php echo $customer_id?>" class="btn btn-primary">Back</a>
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