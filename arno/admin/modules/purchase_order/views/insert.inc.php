<script>
    function check(){


        var purchase_order_type = document.getElementById("purchase_order_type").value;
        var supplier_id = document.getElementById("supplier_id").value;
        var purchase_order_code = document.getElementById("purchase_order_code").value;
        var purchase_order_date = document.getElementById("purchase_order_date").value;
        var purchase_order_credit_term = document.getElementById("purchase_order_credit_term").value;
        var employee_id = document.getElementById("employee_id").value;
        
        purchase_order_type = $.trim(purchase_order_type);
        supplier_id = $.trim(supplier_id);
        purchase_order_code = $.trim(purchase_order_code);
        purchase_order_date = $.trim(purchase_order_date);
        purchase_order_credit_term = $.trim(purchase_order_credit_term);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input Supplier");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(purchase_order_date.length == 0){
            alert("Please input purchase Order Date");
            document.getElementById("purchase_order_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }


</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Purchase Order Management</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Purchase Order  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=purchase_order&action=add" >
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Type <font color="#F00"><b>*</b></font></label>
                                        <select id="purchase_order_type" name="purchase_order_type" class="form-control">
                                                <option value="">Select</option>
                                                <option>IN</option>
                                                <option>OUT</option>
                                            </select>
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" readonly>
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <select id="supplier_id" name="supplier_id" class="form-control" >
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Code <font color="#F00"><b>*</b></font></label>
                                        <input id="purchase_order_code" name="purchase_order_code" class="form-control" value="<?php echo $last_code;?>" readonly>
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Purchase Order Date</label>
                                        <input type="text" id="purchase_order_date" name="purchase_order_date"  class="form-control calendar" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Credit term (Day)</label>
                                        <input type="text" id="purchase_order_credit_term" name="purchase_order_credit_term"  class="form-control"/>
                                        <p class="help-block">10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control" >
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Delivery by</label>
                                        <input type="text" id="purchase_order_delivery_by" name="purchase_order_delivery_by"  class="form-control"/>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
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