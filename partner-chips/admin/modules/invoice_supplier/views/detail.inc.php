<script>
    var product_data = [
    <?php for($i = 0 ; $i < count($products) ; $i++ ){?>
        {
            product_id:'<?php echo $products[$i]['product_id'];?>',
            product_code:'<?php echo $products[$i]['product_code'];?>',
            product_name:'<?php echo $products[$i]['product_name'];?>'
        },
    <?php }?>
    ];

    var data_buffer = [];
    function check(){

        var supplier_id = document.getElementById("supplier_id").value;
        var invoice_supplier_code = document.getElementById("invoice_supplier_code").value;
        var invoice_supplier_date = document.getElementById("invoice_supplier_date").value;
        var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
        var invoice_supplier_term = document.getElementById("invoice_supplier_term").value;
        var invoice_supplier_due = document.getElementById("invoice_supplier_due").value;
        var employee_id = document.getElementById("employee_id").value;

        
        supplier_id = $.trim(supplier_id);
        invoice_supplier_code = $.trim(invoice_supplier_code);
        invoice_supplier_date = $.trim(invoice_supplier_date);
        invoice_supplier_date_recieve = $.trim(invoice_supplier_date_recieve);
        invoice_supplier_term = $.trim(invoice_supplier_term);
        invoice_supplier_due = $.trim(invoice_supplier_due);
        employee_id = $.trim(employee_id);

        if(supplier_id.length == 0){
            alert("Please input iupplier.");
            document.getElementById("supplier_id").focus();
            return false;
        }else if(invoice_supplier_code.length == 0){
            alert("Please input invoice supplier date.");
            document.getElementById("invoice_supplier_code").focus();
            return false;
        }else if(invoice_supplier_date.length == 0){
            alert("Please input invoice supplier date.");
            document.getElementById("invoice_supplier_date").focus();
            return false;
        }else if(invoice_supplier_date_recieve.length == 0){
            alert("Please input invoice supplier date recieve.");
            document.getElementById("invoice_supplier_date_recieve").focus();
            return false;
        }else if(invoice_supplier_term.length == 0){
            alert("Please input invoice supplier term.");
            document.getElementById("invoice_supplier_term").focus();
            return false;
        }else if(invoice_supplier_due.length == 0){
            alert("Please input invoice supplier due");
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
        });
    }

    
    function delete_row(id){
        $(id).closest('tr').remove();
     }

     function show_data(id){
        var product_name = "";
        var data = product_data.filter(val => val['product_id'] == $(id).val());
        if(data.length > 0){
            $(id).closest('tr').children('td').children('input[name="product_name[]"]').val( data[0]['product_name'] );
        }
        
     }

     function update_sum(id){

          var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]').val(  ).replace(',',''));
          var price =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( ).replace(',',''));
          var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="invoice_supplier_list_total[]"]').val( ).replace(',',''));

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0.0;
        }

        if(isNaN(sum)){
            sum = 0.0;
        }

        sum = qty*price;

        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();

        
    }



    function show_purchase_order(id){
        var supplier_id = document.getElementById('supplier_id').value;
        var val = document.getElementsByName('purchase_order_list_id[]');
        var purchase_order_list_id = [];
        
        for(var i = 0 ; i < val.length ; i++){
            purchase_order_list_id.push(val[i].value);
        }
        
        if(supplier_id != ""){

            $.post( "controllers/getInvoiceSupplierListBySupplierID.php", { 'supplier_id': supplier_id, 'purchase_order_list_id': JSON.stringify(purchase_order_list_id) }, function( data ) {
                if(data.length > 0){
                    data_buffer = data;
                    var content = "";
                    for(var i = 0; i < data.length ; i++){

                        content += '<tr class="odd gradeX">'+
                                        '<td>'+
                                            '<input type="checkbox" name="p_id" value="'+data[i].product_id+'" />'+     
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_code+
                                        '</td>'+
                                        '<td>'+
                                            data[i].product_name+
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].invoice_supplier_list_qty +
                                        '</td>'+
                                        '<td align="right">'+
                                            data[i].invoice_supplier_list_price +
                                        '</td>'+
                                        '<td align="right">'+
                                            (data[i].invoice_supplier_list_qty * data[i].invoice_supplier_list_price) +
                                        '</td>'+
                                    '</tr>';

                    }
                    $('#bodyAdd').html(content);
                    $('#modalAdd').modal('show');

                }else{
                    add_row_new(id);
                }
                
            });
        }else{
            alert("Please select supplier.");
        }
        
    } 

    function add_row(id){
        $('#modalAdd').modal('hide');
        var checkbox = document.getElementsByName('p_id');
        for(var i = 0 ; i < (checkbox.length); i++){
            if(checkbox[i].checked){

                var index = 0;
                if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                    index = 1;
                }else{
                    index = $(id).closest('table').children('tbody').children('tr').length + 1;
                }

                $(id).closest('table').children('tbody').append(
                    '<tr class="odd gradeX">'+
                        '<td>'+
                            '<input type="hidden" name="purchase_order_list_id[]" value="'+ data_buffer[i].purchase_order_list_id +'" readonly />'+     
                            '<select class="form-control select" type="text" name="product_id" onchange="show_data(this);" data-live-search="true" ></select>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="product_name[]" value="'+ data_buffer[i].product_name +'" readonly />'+
                            '<input type="text" class="form-control" name="invoice_customer_list_product_name[]" placeholder="Product Name (Supplier)" />'+
                            '<input type="text" class="form-control" name="invoice_customer_list_product_detail[]" placeholder="Product Detail (Supplier)" />'+
                            '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark"/>'+
                        '</td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_qty[]" onchange="update_sum(this);" value="'+ data_buffer[i].invoice_supplier_list_qty +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_price[]" onchange="update_sum(this);" value="'+ data_buffer[i].invoice_supplier_list_price +'" /></td>'+
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_total[]" onchange="update_sum(this);"  value="'+ (data_buffer[i].invoice_supplier_list_qty * data_buffer[i].invoice_supplier_list_price) +'" readonly /></td>'+
                        '<td>'+
                            '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                                '<i class="fa fa-times" aria-hidden="true"></i>'+
                            '</a>'+
                        '</td>'+
                    '</tr>'
                );

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').empty();
                var str = "<option value=''>Select Product</option>";
                $.each(product_data, function (index, value) {
                    if(value['product_id'] == data_buffer[i].product_id){
                        str += "<option value='" + value['product_id'] + "' selected >"+value['product_code']+"</option>";
                    }else{
                        str += "<option value='" + value['product_id'] + "'>"+value['product_code']+"</option>";
                    }
                    
                });

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

                $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();

            }
            
        }
        calculateAll();
    }

    


    function add_row_new(id){
        $('#modalAdd').modal('hide');
        var index = 0;
        if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
        }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
        }
        $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td>'+
                    '<input type="hidden" name="purchase_order_list_id[]" value="0" />'+     
                    '<select class="form-control select" type="text" name="product_id" onchange="show_data(this);" data-live-search="true" ></select>'+
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_product_name[]" placeholder="Product Name (Supplier)" />'+
                    '<input type="text" class="form-control" name="invoice_customer_list_product_detail[]" placeholder="Product Detail (Supplier)" />'+
                    '<input type="text" class="form-control" name="invoice_supplier_list_remark[]" placeholder="Remark"/>'+
                '</td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_qty[]" onchange="update_sum(this);" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_price[]" onchange="update_sum(this);" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" name="invoice_supplier_list_total[]" onchange="update_sum(this);" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').empty();
        var str = "<option value=''>Select Product</option>";
        $.each(product_data, function (index, value) {
            str += "<option value='" + value['product_id'] + "'>"+value['product_code']+"</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();
    }


    function checkAll(id)
    {
        var checkbox = document.getElementById('check_all');
        if (checkbox.checked == true){
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
        }else{
            $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
        }
    }


    function calculateAll(){

        var val = document.getElementsByName('invoice_supplier_list_total[]');
        var total = 0.0;
        
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#invoice_supplier_total_price').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_vat_price').val((total * ($('#invoice_supplier_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#invoice_supplier_net_price').val((total * ($('#invoice_supplier_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
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
            รายละเอียดใบกำกับภาษีรับเข้า / Invoice Supplier Detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&action=edit&id=<?php echo $invoice_supplier_id;?>" >
                    <input type="hidden"  id="invoice_supplier_id" name="invoice_supplier_id" value="<?php echo $invoice_supplier_id; ?>" />
                    <input type="hidden"  id="invoice_supplier_date" name="invoice_supplier_date" value="<?php echo $invoice_supplier['invoice_supplier_date']; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><? echo $invoice_supplier['supplier_code'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_name_en'] ?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $supplier['supplier_address_1'] ."\n". $invoice_supplier['supplier_address_2'] ."\n". $invoice_supplier['supplier_address_3'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?php echo $invoice_supplier['supplier_tax'];?></p>
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
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_due'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_term'];?></p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่รับใบกำกับภาษี / Date recieve</label>
                                        
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_date_recieve'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>
                                        <p class="help-block"><?PHP echo $invoice_supplier['invoice_supplier_code_gen'];?></p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
                                       
                                        <p class="help-block"><?PHP echo $invoice_supplier['user_name'];?> <?PHP echo $invoice_supplier['user_lastname'];?> (<?PHP echo $invoice_supplier['user_position_name'];?>)</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ลำดับ <br> (์No.)</th>
                                <th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>
                                <th style="text-align:center;">รายละเอียดสินค้า <br> (Product Detail)</th>
                                <th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>
                                <th style="text-align:center;" width="150">ราคาต่อหน่วย <br> (Unit price) </th>
                                <th style="text-align:center;" width="150">จำนวนเงิน <br> (Amount)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($invoice_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td align="center">
                                    <?php echo $i+1; ?>.
                                </td>
                                
                                <td>
                                    <?php echo $invoice_supplier_lists[$i]['product_code']; ?>
                                </td>

                                <td>
                                    <b><?php echo $invoice_supplier_lists[$i]['product_name']; ?></b><br>
                                    <span>Sub name : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_name']; ?><br>
                                    <span>Detail : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_product_detail']; ?><br>
                                    <span>Remark : </span><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_remark']; ?><br>
                                </td>

                                <td align="right"><?php echo $invoice_supplier_lists[$i]['invoice_supplier_list_qty']; ?></td>
                                <td align="right"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                                <td align="right"><?php echo  number_format($invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'],2); ?></td>
                                
                            </tr>
                            <?
                                $total += $invoice_supplier_lists[$i]['invoice_supplier_list_qty'] * $invoice_supplier_lists[$i]['invoice_supplier_list_price'];
                            }
                            ?>
                        </tbody>

                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="3" rowspan="3">
                                    
                                </td>
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td style="text-align: right;">
                                <?PHP
                                    if($invoice_supplier['vat_type'] == 1){
                                        $total_val = $total - (($invoice_supplier['vat']/( 100 + $invoice_supplier['vat'] )) * $total);
                                    } else if($invoice_supplier['vat_type'] == 2){
                                        $total_val = $total;
                                    } else {
                                        $total_val = $total;
                                    }
                                ?>
                                    <?PHP echo number_format($total_val,2) ;?>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;text-align: right;">
                                            <?PHP echo $invoice_supplier['vat'];?>
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="text-align: right;">
                                <?PHP 
                                    if($invoice_supplier['vat_type'] == 1){
                                        $vat_val = ($invoice_supplier['vat']/( 100 + $invoice_supplier['vat'] )) * $total;
                                    } else if($invoice_supplier['vat_type'] == 2){
                                        $vat_val = ($invoice_supplier['vat']/100) * $total;
                                    } else {
                                        $vat_val = 0.0;
                                    }
                                    ?>
                                    <?PHP echo number_format($vat_val,2) ;?>
                                </td>

                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="2" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="text-align: right;">
                                <?PHP 
                                    if($invoice_supplier['vat_type'] == 1){
                                        $net_val =  $total;
                                    } else if($invoice_supplier['vat_type'] == 2){
                                        $net_val = ($invoice_supplier['vat']/100) * $total + $total;
                                    } else {
                                        $net_val = $total;
                                    }
                                    ?>
                                   <?PHP echo number_format($net_val,2) ;?>
                                </td>

                            </tr>
                        </tfoot>
                    </table>   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_supplier" class="btn btn-default">Back</a>
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