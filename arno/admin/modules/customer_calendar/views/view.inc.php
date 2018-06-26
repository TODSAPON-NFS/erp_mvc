<script>
    function check(){   
        var customer_holiday_name = document.getElementById("customer_holiday_name").value;
        var customer_holiday_date = document.getElementById("customer_holiday_date").value;
        var holiday_id = document.getElementById("holiday_id").value;
       
        customer_holiday_date = $.trim(customer_holiday_date);
        customer_holiday_name = $.trim(customer_holiday_name);
        holiday_id = $.trim(holiday_id);
        
        if(holiday_id.length == 0){
            alert("Please input holiday type");
            document.getElementById("holiday_id").focus();
            return false;
        }else  if(holiday_id == 8 && customer_holiday_date.length == 0){
            alert("Please input holiday date");
            document.getElementById("customer_holiday_date").focus();
            return false;
        }else if(customer_holiday_name.length == 0){
            alert("Please input holiday name");
            document.getElementById("customer_holiday_name").focus();
            return false;
        }else{
            return true;
        }
    }


    function check_invoice(){   
        var date_invoice = document.getElementById("date_invoice").value;
        var invoice_shift = document.getElementById("invoice_shift").value;
       
        date_invoice = $.trim(date_invoice);
        invoice_shift = $.trim(invoice_shift);
        
       if(date_invoice.length == 0){
            alert("Please input last recieve invoice");
            document.getElementById("customer_holiday_name").focus();
            return false;
        }else  if(invoice_shift.length == 0){
            alert("Please input shift ");
            document.getElementById("invoice_shift").focus();
            return false;
        }else{
            return true;
        }
    }

    function check_bill(){   
        var date_bill = document.getElementById("date_bill").value;
        var bill_shift = document.getElementById("bill_shift").value;
       
        date_bill = $.trim(date_bill);
        bill_shift = $.trim(bill_shift);
        
       if(date_bill.length == 0){
            alert("Please input last recieve bill");
            document.getElementById("customer_holiday_name").focus();
            return false;
        }else  if(bill_shift.length == 0){
            alert("Please input shift ");
            document.getElementById("bill_shift").focus();
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
                            Customer Holiday
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return check();" <?php if($customer_holiday_id == ''){ ?>action="index.php?app=customer_holiday&action=add&id=<?php echo $customer_id;?>"<?php }else{?> action="index.php?app=customer_holiday&action=edit&id=<?php echo $customer_id;?>" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="customer_holiday_id" name="customer_holiday_id" value="<?php echo $customer_holiday_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Holiday Type<font color="#F00"><b>*</b></font></label>
                                            <select id="holiday_id" name="holiday_id"  class="form-control">
                                                <option value="">Select</option>
                                                <?php 
                                                for($i =  0 ; $i < count($holidays) ; $i++){
                                                ?>
                                                <option <?if($customer_holiday['holiday_id'] == $holidays[$i]['holiday_id'] ){?> selected <?php } ?> value="<?php echo $holidays[$i]['holiday_id'] ?>"><?php echo $holidays[$i]['holiday_name'] ?>  </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : หยุดทุกวันอาทิตย์.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Holiday Date <font color="#F00"><b>*</b></font></label>
                                            <input id="customer_holiday_date" name="customer_holiday_date" class="form-control" value="<? echo $customer_holiday['customer_holiday_date'];?>" readonly>
                                            <p class="help-block">Example : 05-12-2018.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Holiday Name <font color="#F00"><b>*</b></font></label>
                                            <input id="customer_holiday_name" name="customer_holiday_name" class="form-control" value="<? echo $customer_holiday['customer_holiday_name'];?>">
                                            <p class="help-block">Example : วันหยุดประจำ.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=customer_holiday&action=view&id=<?php echo $customer_id;?>" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Holiday Type</th>
                                        <th>Holiday Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($customer_holidays); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $customer_holidays[$i]['holiday_name']; ?></td>
                                        <td><?php echo $customer_holidays[$i]['customer_holiday_name']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=customer_holiday&action=update&id=<?php echo $customer_id;?>&sid=<?php echo $customer_holidays[$i]['customer_holiday_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=customer_holiday&action=delete&id=<?php echo $customer_id;?>&sid=<?php echo $customer_holidays[$i]['customer_holiday_id'];?>" onclick="return confirm('You want to delete customer product unit : <?php echo $customer_holidays[$i]['customer_holiday_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>



            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Customer Invoice Date
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return check_invoice();"  action="index.php?app=customer_holiday&action=edit_invoice&id=<?php echo $customer_id;?>"  enctype="multipart/form-data">
                                <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Last date for send invoice<font color="#F00"><b>*</b></font></label>
                                            <input id="date_invoice" name="date_invoice" class="form-control" value="<? echo $customer['date_invoice'];?>">
                                            <p class="help-block">Example : 25.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>Shift <font color="#F00"><b>*</b></font></label>
                                            <select id="invoice_shift" name="invoice_shift" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if($customer['invoice_shift'] == '0'){?> selected <?php } ?> value="0" >Down</option>
                                                <option <?php if($customer['invoice_shift'] == '1'){?> selected <?php } ?> value="1" >Up</option>
                                            </select>
                                            <p class="help-block">Example : Up.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=customer_holiday&action=view&id=<?php echo $customer_id;?>" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Month</th>
                                        <th>date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($invoice); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $invoice[$i]['Mount']; ?></td>
                                        <td><?php echo $invoice[$i]['Date']; ?></td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Customer Bill Date
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return check_bill();"  action="index.php?app=customer_holiday&action=edit_bill&id=<?php echo $customer_id;?>"  enctype="multipart/form-data">
                                <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $customer_id?>"/>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Last date for send bill<font color="#F00"><b>*</b></font></label>
                                            <input id="date_bill" name="date_bill" class="form-control" value="<? echo $customer['date_bill'];?>">
                                            <p class="help-block">Example : 25.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>Shift <font color="#F00"><b>*</b></font></label>
                                            <select id="bill_shift" name="bill_shift" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if($customer['bill_shift'] == '0'){?> selected <?php } ?> value="0">Down</option>
                                                <option <?php if($customer['bill_shift'] == '1'){?> selected <?php } ?> value="1">Up</option>
                                            </select>
                                            <p class="help-block">Example : Up.</p>
                                        </div>
                                    </div>
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=customer_holiday&action=view&id=<?php echo $customer_id;?>" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Month</th>
                                        <th>date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($bill); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $bill[$i]['Mount']; ?></td>
                                        <td><?php echo $bill[$i]['Date']; ?></td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
