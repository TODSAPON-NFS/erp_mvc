<script>
    
    function check(){

        var supplier_id = document.getElementById("supplier_id").value;
        var invoice_supplier_code = document.getElementById("invoice_supplier_code").value;
        var invoice_supplier_date = document.getElementById("invoice_supplier_date").value;
        var invoice_supplier_term = document.getElementById("invoice_supplier_term").value;
        var invoice_supplier_due = document.getElementById("invoice_supplier_due").value;
        var employee_id = document.getElementById("employee_id").value;

        
        supplier_id = $.trim(supplier_id);
        invoice_supplier_code = $.trim(invoice_supplier_code);
        invoice_supplier_date = $.trim(invoice_supplier_date);
        invoice_supplier_term = $.trim(invoice_supplier_term);
        invoice_supplier_due = $.trim(invoice_supplier_due);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(invoice_supplier_code.length == 0){
            alert("Please input Invoice Supplier date.");
            document.getElementById("invoice_supplier_code").focus();
            return false;
        }else if(invoice_supplier_date.length == 0){
            alert("Please input Invoice Supplier date.");
            document.getElementById("invoice_supplier_date").focus();
            return false;
        }
        else if(invoice_supplier_term.length == 0){
            alert("Please input Invoice Supplier term.");
            document.getElementById("invoice_supplier_term").focus();
            return false;
        }else if(invoice_supplier_due.length == 0){
            alert("Please input Invoice Supplier due");
            document.getElementById("invoice_supplier_due").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }

    function get_supplier_detail(){
        var supplier_id = document.getElementById('supplier_id').value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('invoice_supplier_name').value = data.supplier_name_en;
            document.getElementById('invoice_supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
            document.getElementById('invoice_supplier_tax').value = data.supplier_tax ;
            document.getElementById('invoice_supplier_due_day').value = data.credit_day ;
            generate_credit_date();
        });
    }


    function calculateAll(){

            var total = parseFloat($('#invoice_supplier_total_price').val().toString().replace(new RegExp(',', 'g'),''));
            if(isNaN(total)){
                total = 0.0;
            }

            $('#invoice_supplier_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#invoice_supplier_vat_price').val((total * ($('#invoice_supplier_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#invoice_supplier_net_price').val((total * ($('#invoice_supplier_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

    function generate_credit_date(){
        var day = parseInt(document.getElementById('invoice_supplier_due_day').value);
        var date = document.getElementById('invoice_supplier_date').value ;

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            document.getElementById('invoice_supplier_due_day').value = 0;
            day = 0;
        }else if (date == ""){
            document.getElementById('invoice_supplier_date').value = ("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear();
        }

        if (day > 0){
            document.getElementById('invoice_supplier_term').value = "เครดิต";
        }else{
            document.getElementById('invoice_supplier_term').value = "เงินสด";
        }

        tomorrow.setDate(current_date.getDate()+day);
        document.getElementById('invoice_supplier_due').value =  ("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear();
        

    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Invoice Supplier Begin Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            แก้ไขใบกำกับภาษีเจ้าหนี้ยกยอดมา / Edit Invoice Supplier Begin
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=summit_credit&action=edit&id=<?php echo $invoice_supplier_id;?>" >
                    <input type="hidden"  id="invoice_supplier_id" name="invoice_supplier_id" value="<?php echo $invoice_supplier_id; ?>" />
                    <input type="hidden"  id="invoice_supplier_date" name="invoice_supplier_date" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly />
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <select id="supplier_id" name="supplier_id" class="form-control select" onchange="get_supplier_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?php if($suppliers[$i]['supplier_id'] == $supplier['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
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
                                        <input  id="invoice_supplier_name" name="invoice_supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> " >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="invoice_supplier_address" name="invoice_supplier_address" class="form-control" rows="5" ><?php echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_tax" name="invoice_supplier_tax" class="form-control" value="<?php echo $supplier['supplier_tax'];?>" >
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
                                        <label>วันที่รับสินค้า / Date recieve</label>
                                        <input type="text" id="invoice_supplier_date_recieve" name="invoice_supplier_date_recieve" value="<?PHP echo $invoice_supplier['invoice_supplier_date_recieve'];?>"  class="form-control calendar" readonly />
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code_gen" name="invoice_supplier_code_gen" class="form-control" value="<?PHP echo $invoice_supplier['invoice_supplier_code_gen'];?>" />
                                        <p class="help-block">Example : RR1801001 OR RF1801001.</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบกำกับภาษี / Date</label>
                                        <input type="text" id="invoice_supplier_date" name="invoice_supplier_date" value="<?PHP echo $invoice_supplier['invoice_supplier_date'];?>" onchange="generate_credit_date();"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        <input id="invoice_supplier_code" name="invoice_supplier_code" class="form-control" value="<?PHP echo $invoice_supplier['invoice_supplier_code'];?>" >
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        <input type="text" id="invoice_supplier_due_day" name="invoice_supplier_due_day"  class="form-control" value="<?php echo $supplier['credit_day'];?>" onchange="generate_credit_date();"/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_supplier_due" name="invoice_supplier_due"  class="form-control calendar" value="<?PHP echo $invoice_supplier['invoice_supplier_due'];?>" onchange="generate_credit_date();" readonly/>
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_supplier_term" name="invoice_supplier_term"  class="form-control" value="<?PHP echo $invoice_supplier['invoice_supplier_term'];?>"  />
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
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $invoice_supplier['employee_id']){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>สถานะใบสั่งขาย / Invoice status  <font color="#F00"><b>*</b></font> </label>
                                        <select id="invoice_supplier_close" name="invoice_supplier_close" class="form-control" >
                                            <option <?PHP if($invoice_supplier['invoice_supplier_close'] == "0"){ ?> selected <?PHP }?> value="0">ใช้งาน</option>
                                            <option <?PHP if($invoice_supplier['invoice_supplier_close'] == "1"){ ?> selected <?PHP }?> value="1">เลิกใช้งาน</option>
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
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_total_price"  name="invoice_supplier_total_price" onchange="calculateAll()" value="<?PHP echo number_format($invoice_supplier['invoice_supplier_total_price'],2) ;?>"  />
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
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="invoice_supplier_vat" name="invoice_supplier_vat" value="<?PHP echo $invoice_supplier['invoice_supplier_vat'];?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td width="200px">
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_vat_price"  name="invoice_supplier_vat_price" value="<?PHP echo number_format($invoice_supplier['invoice_supplier_vat_price'],2);?>"  readonly/>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td width="300px" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td width="200px">
                                    <input type="text" class="form-control" style="text-align: right;" id="invoice_supplier_net_price" name="invoice_supplier_net_price" value="<?PHP echo number_format($invoice_supplier['invoice_supplier_net_price'],2);?>" readonly/>
                                </td>
                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=summit_credit" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <a href="index.php?app=summit_credit&action=print&id=<?PHP echo $invoice_supplier_id?>" class="btn btn-danger">Print</a>
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