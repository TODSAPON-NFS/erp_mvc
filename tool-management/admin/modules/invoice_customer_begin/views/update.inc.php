<script>
    
    function check(){

        var customer_id = document.getElementById("customer_id").value;
        var invoice_customer_code = document.getElementById("invoice_customer_code").value;
        var invoice_customer_date = document.getElementById("invoice_customer_date").value;
        var invoice_customer_term = document.getElementById("invoice_customer_term").value;
        var invoice_customer_due = document.getElementById("invoice_customer_due").value;
        var employee_id = document.getElementById("employee_id").value;

        
        customer_id = $.trim(customer_id);
        invoice_customer_code = $.trim(invoice_customer_code);
        invoice_customer_date = $.trim(invoice_customer_date);
        invoice_customer_term = $.trim(invoice_customer_term);
        invoice_customer_due = $.trim(invoice_customer_due);
        employee_id = $.trim(employee_id);

        if(customer_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("customer_id").focus();
            return false;
        }else if(invoice_customer_code.length == 0){
            alert("Please input invoice Customer date.");
            document.getElementById("invoice_customer_code").focus();
            return false;
        }else if(invoice_customer_date.length == 0){
            alert("Please input invoice Customer date.");
            document.getElementById("invoice_customer_date").focus();
            return false;
        }
        else if(invoice_customer_term.length == 0){
            alert("Please input invoice Customer term.");
            document.getElementById("invoice_customer_term").focus();
            return false;
        }else if(invoice_customer_due.length == 0){
            alert("Please input invoice Customer due");
            document.getElementById("invoice_customer_due").focus();
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
        $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
            document.getElementById('customer_code').value = data.customer_code;
            document.getElementById('invoice_customer_name').value = data.customer_name_en;
            document.getElementById('invoice_customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
            document.getElementById('invoice_customer_tax').value = data.customer_tax ;
            document.getElementById('invoice_customer_due_day').value = data.credit_day ;
            generate_credit_date();
        });
    }


     function calculateAll(){

        var total = parseFloat($('#invoice_customer_total_price').val().toString().replace(new RegExp(',', 'g'),''));
        if(isNaN(total)){
            total = 0.0;
        }

        $('#invoice_customer_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_vat_price').val((total * ($('#invoice_customer_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_customer_net_price').val((total * ($('#invoice_customer_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

    function generate_credit_date(){
        var day = parseInt(document.getElementById('invoice_customer_due_day').value);
        var date = document.getElementById('invoice_customer_date').value ;

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            document.getElementById('invoice_customer_due_day').value = 0;
            day = 0;
        }else if (date == ""){
            document.getElementById('invoice_customer_date').value = ("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear();
        }

        if (day > 0){
            document.getElementById('invoice_customer_term').value = "เครดิต";
        }else{
            document.getElementById('invoice_customer_term').value = "เงินสด";
        }

        tomorrow.setDate(current_date.getDate()+day);
        document.getElementById('invoice_customer_due').value =  ("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear();
        

    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Invoice Customer Begin Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            แก้ไขใบกำกับภาษีลูกหนี้ยกยอดมา / Edit Invoice Customer Begin
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=summit_dedit&action=edit&id=<?php echo $invoice_customer_id;?>" >
                    <input type="hidden"  id="invoice_customer_id" name="invoice_customer_id" value="<?php echo $invoice_customer_id; ?>" />
                    <input type="hidden"  id="invoice_customer_date" name="invoice_customer_date" value="<?php echo $invoice_customer['invoice_customer_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer['customer_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้ซื้อ / Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" onchange="get_customer_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $customer['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_name" name="invoice_customer_name" class="form-control" value="<?php echo $customer['customer_name_en'];?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="invoice_customer_address" name="invoice_customer_address" class="form-control" rows="5" ><?php echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_tax" name="invoice_customer_tax" class="form-control" value="<?php echo $customer['customer_tax'];?>" >
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <input type="text" id="invoice_customer_date" name="invoice_customer_date" value="<?PHP echo $invoice_customer['invoice_customer_date'];?>" onchange="generate_credit_date();"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_customer_code" name="invoice_customer_code" class="form-control" value="<?PHP echo $invoice_customer['invoice_customer_code'];?>" >
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        <input type="text" id="invoice_customer_due_day" name="invoice_customer_due_day"  class="form-control" value="<?php echo $customer['credit_day'];?>" onchange="generate_credit_date();"/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_customer_due" name="invoice_customer_due"  class="form-control calendar" value="<?PHP echo $invoice_customer['invoice_customer_due'];?>" onchange="generate_credit_date();" readonly/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_customer_term" name="invoice_customer_term"  class="form-control" value="<?PHP echo $invoice_customer['invoice_customer_term'];?>"  />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>
                                

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $invoice_customer['employee_id']){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>สถานะใบสั่งซื้อ / Invoice status  <font color="#F00"><b>*</b></font> </label>
                                        <select id="invoice_customer_close" name="invoice_customer_close" class="form-control" >
                                            <option <?PHP if($invoice_customer['invoice_customer_close'] == "0"){ ?> selected <?PHP }?> value="0">ใช้งาน</option>
                                            <option <?PHP if($invoice_customer['invoice_customer_close'] == "1"){ ?> selected <?PHP }?> value="1">เลิกใช้งาน</option>
                                        </select>
                                        <p class="help-block">Example : ใช้งาน.</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="2" rowspan="3">
                                    
                                </td>
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td width="200px">
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_total_price"  name="invoice_customer_total_price" onchange="calculateAll()" value="<?PHP echo number_format($invoice_customer['invoice_customer_total_price'],2) ;?>"  />
                                </td> 
                            </tr>
                            <tr class="odd gradeX">
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="invoice_customer_vat" name="invoice_customer_vat" value="<?PHP echo $invoice_customer['invoice_customer_vat'];?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td width="200px">
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_vat_price"  name="invoice_customer_vat_price" value="<?PHP echo number_format($invoice_customer['invoice_customer_vat_price'],2);?>"  readonly/>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td width="200px">
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_customer_net_price" name="invoice_customer_net_price" value="<?PHP echo number_format($invoice_customer['invoice_customer_net_price'],2);?>" readonly/>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=summit_dedit" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <a href="index.php?app=summit_dedit&action=print&id=<?PHP echo $invoice_customer_id?>" class="btn btn-danger">Print</a>
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