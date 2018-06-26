<script>
    function check(){   
        var customer_contact_name = document.getElementById("customer_contact_name").value;
        var customer_contact_position = document.getElementById("customer_contact_detail").value;
        
        customer_contact_name = $.trim(customer_contact_name);
        customer_contact_position = $.trim(customer_contact_position);
        
        

       if(customer_contact_name.length == 0){
            alert("Please input contact name");
            document.getElementById("customer_contact_name").focus();
            return false;
        }else  if(customer_contact_position.length == 0){
            alert("Please input detail contact position");
            document.getElementById("customer_contact_detail").focus();
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
                Update Person Contact 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_contact&action=edit&id=<?php echo $customer_id?>" enctype="multipart/form-data">
                    <input type="hidden" id="customer_contact_id" name="customer_contact_id" value="<?php echo $customer_contact_id?>"/>
                    

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Name<font color="#F00"><b>*</b></font></label>
                                <input id="customer_contact_name" name="customer_contact_name" value="<? echo $customer_contact['customer_contact_name'];?>"  class="form-control">
                                <p class="help-block">Example : คุณวินัย ชาญชัย.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Position <font color="#F00"><b>*</b></font></label>
                                <input id="customer_contact_position" name="customer_contact_position" value="<? echo $customer_contact['customer_contact_position'];?>" class="form-control">
                                <p class="help-block">Example : ผู้จัดการฝ่ายผลิต.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Telephone </label>
                                <input id="customer_contact_tel" name="customer_contact_tel" value="<? echo $customer_contact['customer_contact_tel'];?>"  class="form-control" >
                                <p class="help-block">Example : 061-0243003.</p>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Email</label>
                                <input id="customer_contact_email" name="customer_contact_email" value="<? echo $customer_contact['customer_contact_email'];?>" class="form-control">
                                <p class="help-block">Example : winai.ch@gmail.com.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Detail </label>
                                <input id="customer_contact_detail" name="customer_contact_detail" value="<? echo $customer_contact['customer_contact_detail'];?>" class="form-control">
                                <p class="help-block">Example : - .</p>
                            </div>
                        </div>
                    </div>   

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=customer_contact&action=view&id=<?php echo $customer_id?>" class="btn btn-primary">Back</a>
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