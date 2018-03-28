<script>
    function check(){


       
        var customer_id = document.getElementById("customer_id").value;
        var customer_purchase_order_code = document.getElementById("customer_purchase_order_code").value;
        var customer_purchase_order_date = document.getElementById("customer_purchase_order_date").value;
        var customer_purchase_order_credit_term = document.getElementById("customer_purchase_order_credit_term").value;
        var employee_id = document.getElementById("employee_id").value;
        
  
        customer_id = $.trim(customer_id);
        customer_purchase_order_code = $.trim(customer_purchase_order_code);
        customer_purchase_order_date = $.trim(customer_purchase_order_date);
        customer_purchase_order_credit_term = $.trim(customer_purchase_order_credit_term);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input Customer");
            document.getElementById("customer_id").focus();
            return false;
        }else if(customer_purchase_order_date.length == 0){
            alert("Please input purchase Order Date");
            document.getElementById("customer_purchase_order_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }

    function get_customer_detail(){
        var customer_id = document.getElementById('customer_id').value;
        if(customer_id != ''){
            $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
                document.getElementById('customer_code').value = data.customer_code;
                document.getElementById('customer_tax').value = data.customer_tax;
                document.getElementById('customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            });
        }
        
    }


</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Customer Order Management</h1>
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
               เพิ่มใบสั่งซื้อสินค้าของลูกค้า / Add Customer Order  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer_purchase_order&action=add" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสลูกค้า / Customer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" readonly>
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ลูกค้า / Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" data-live-search="true" onchange="get_customer_detail()">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ / Address <font color="#F00"></font></label>
                                        <textarea  id="customer_address" name="customer_address" class="form-control" rows="7" readonly></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>หมายเลขผู้เสียภาษี / Tax. <font color="#F00"></font></label>
                                        <input id="customer_tax" name="customer_tax" class="form-control" readonly>
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark  <font color="#F00"></font></label>
                                        <textarea  id="customer_purchase_order_remark" name="customer_purchase_order_remark" class="form-control" rows="7" ></textarea >
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขที่ใบสั่งซื้อ / PO Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_purchase_order_code" name="customer_purchase_order_code" class="form-control" value="<?php echo $last_code;?>" >
                                        <p class="help-block">Example : PO1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบสั่งซื้อ / PO Date</label>
                                        <input type="text" id="customer_purchase_order_date" name="customer_purchase_order_date"  class="form-control calendar" readonly/>
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จ่ายเงินภายใน (วัน) / Credit term (Day)</label>
                                        <input type="text" id="customer_purchase_order_credit_term" name="customer_purchase_order_credit_term"  class="form-control"/>
                                        <p class="help-block">10 </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true" >
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
                                        <label>จัดส่งโดย / Delivery by</label>
                                        <input type="text" id="customer_purchase_order_delivery_by" name="customer_purchase_order_delivery_by"  class="form-control"/>
                                        <p class="help-block">DHL </p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ไฟล์แนบ / File </label>
                                        <input accept=".pdf"   type="file" id="customer_purchase_order_file" name="customer_purchase_order_file" >
                                        <p class="help-block">Example : .pdf</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=customer_purchase_order" class="btn btn-default">Back</a>
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