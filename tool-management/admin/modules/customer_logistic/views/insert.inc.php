<script>
    function check(){


        
        var customer_logistic_name = document.getElementById("customer_logistic_name").value;
        var customer_logistic_detail = document.getElementById("customer_logistic_detail").value;
        var customer_logistic_lead_time = document.getElementById("customer_logistic_lead_time").value;
       
        customer_logistic_name = $.trim(customer_logistic_name);
        customer_logistic_detail = $.trim(customer_logistic_detail);
        customer_logistic_lead_time = $.trim(customer_logistic_lead_time);
        
        

       if(customer_logistic_name.length == 0){
            alert("Please input logistic name");
            document.getElementById("customer_logistic_name").focus();
            return false;
        }else  if(customer_logistic_detail.length == 0){
            alert("Please input detail name english");
            document.getElementById("customer_logistic_detail").focus();
            return false;
        }else if(customer_logistic_lead_time.length == 0){
            alert("Please input lead time");
            document.getElementById("customer_logistic_lead_time").focus();
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
                Add Logistic 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=customer_logistic&action=add&id=<?php echo $customer_id?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Logistic name<font color="#F00"><b>*</b></font></label>
                                <input id="customer_logistic_name" name="customer_logistic_name"  class="form-control">
                                <p class="help-block">Example : Kerry.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Logistic detail <font color="#F00"><b>*</b></font></label>
                                <input id="customer_logistic_detail" name="customer_logistic_detail" class="form-control">
                                <p class="help-block">Example : ขนส่งเอกชน.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Lead time <font color="#F00"><b>*</b></font></label>
                                <input id="customer_logistic_lead_time" name="customer_logistic_lead_time" class="form-control" >
                                <p class="help-block">Example : 3 - 5 วัน.</p>
                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=customer_logistic&action=view&id=<?php echo $customer_id?>" class="btn btn-primary">Back</a>
                            <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
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