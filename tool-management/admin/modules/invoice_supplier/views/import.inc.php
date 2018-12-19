<script src="../plugins/excel/xlsx.core.min.js"></script>  
<script src="../plugins/excel/xls.core.min.js"></script> 
<script>

    var vat_type = 0 ;
    var vat = 0;
    var exchange_rate_baht_value =0 ;
    var currency = "?";
    var stock_group_data = [
    <?php for($i = 0 ; $i < count($stock_groups) ; $i++ ){?>
        {
            stock_group_id:'<?php echo $stock_groups[$i]['stock_group_id'];?>',
            stock_group_name:'<?php echo $stock_groups[$i]['stock_group_name'];?>' 
        },
    <?php }?>
    ];

    var data_buffer = [];

    function check_date(id){
        var val_date = $(id).val();
        $.post( "controllers/checkPaperLockByDate.php", { 'date': val_date }, function( data ) {  
            if(data.result){ 
                alert("This "+val_date+" is locked in the system.");
                
                $("#date_check").val("1");
                //$("#invoice_supplier_date_recieve").val(data.date_now);
                $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
                document.getElementById("invoice_supplier_date_recieve").focus();
            } else{
                $("#date_check").val("0");
                get_supplier_detail();
            }
        });
    }
 
    function check(){

        var supplier_id = document.getElementById("supplier_id").value;
        var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
        var invoice_supplier_term = document.getElementById("invoice_supplier_term").value;
        var invoice_supplier_due = document.getElementById("invoice_supplier_due").value;
        var employee_id = document.getElementById("employee_id").value; 
        var date_check = document.getElementById("date_check").value;

        var invoice_supplier_code = $('input[name="invoice_supplier_code[]"]');
        var invoice_supplier_code_gen = $('input[name="invoice_supplier_code_gen[]"]');
        var invoice_supplier_date = $('input[name="invoice_supplier_date[]"]');

        var result_code = invoice_supplier_code.filter(word => word.value == '');
        var result_code_gen = invoice_supplier_code_gen.filter(word => word.value == '');
        var result_date = invoice_supplier_date.filter(word => word.value == '');
        
        supplier_id = $.trim(supplier_id); 
        invoice_supplier_date_recieve = $.trim(invoice_supplier_date_recieve);
        invoice_supplier_term = $.trim(invoice_supplier_term);
        invoice_supplier_due = $.trim(invoice_supplier_due);
        employee_id = $.trim(employee_id);


        if(result_code.length > 0){
            alert("Input invoice supplier code.");
            $(result_code[0]).focus();
            return false;
        } else if(result_code_gen.length > 0){
            alert("Input invoice supplier code recieve.");
            $(result_code_gen[0]).focus();
            return false;
        } else if(result_date.length > 0){
            alert("Input invoice supplier date.");
            $(result_date[0]).focus();
            return false;
        } else  if(date_check == "1"){
            alert("This "+invoice_supplier_date_recieve+" is locked in the system.");
            document.getElementById("invoice_supplier_date_recieve").focus();
            return false;
        } else if(supplier_id.length == 0){
            alert("Please input supplier.");
            document.getElementById("supplier_id").focus();
            return false;
        } else if(invoice_supplier_date_recieve.length == 0){
            alert("Please input invoice supplier date recieve.");
            document.getElementById("invoice_supplier_date_recieve").focus();
            return false;
        } 
        else{
            $('select[name="stock_group_id[]"]').prop('disabled', false);
            return true;
        }
        return false;


    }

    function get_supplier_detail(){
        var supplier_id = document.getElementById('supplier_id').value;
        var employee_id = document.getElementById("employee_id").value;
        var invoice_supplier_date_recieve = document.getElementById("invoice_supplier_date_recieve").value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
            if(data != null){
                document.getElementById('supplier_code').value = data.supplier_code;
                document.getElementById('invoice_supplier_name').value = data.supplier_name_en;
                document.getElementById('invoice_supplier_branch').value = data.supplier_branch;
                document.getElementById('invoice_supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
                document.getElementById('invoice_supplier_tax').value = data.supplier_tax ;
                document.getElementById('invoice_supplier_due_day').value = data.credit_day ;
                document.getElementById('invoice_supplier_term').value = data.condition_pay ;
                $('span[name="currency"]').html(data.currency_sign);
                currency = data.currency_sign;
                vat = data.vat;

            }
        });

        <?PHP if($sort == "ภายนอกประเทศ"){ ?>
            $.post( "controllers/getExchangeRateByCurrencyID.php", { 'invoice_supplier_date_recieve':invoice_supplier_date_recieve, 'supplier_id': supplier_id }, function( data ) {
                if(data != null){
                    var val =  parseFloat(data.exchange_rate_baht_value);
                    document.getElementById('exchange_rate_baht').value =  numberWithCommas(val); 
                    $('span[name="currency"]').html(data.currency_sign);
                    currency = data.currency_sign;
                }else{
                    document.getElementById('exchange_rate_baht').value = 0;
                } 
            });
        <?PHP } ?>



       
    }


    function update_invoice_supplier_due(id){
        var day = parseInt($('#invoice_supplier_due_day').val());
        var date = $('#invoice_supplier_date').val();

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#invoice_supplier_term').val(0);
            day = 0;
        }else if (date == ""){
            $('#invoice_supplier_due').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        } else{
            var date_arr = date.split('-'); 

            current_date = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
            tomorrow = new Date(date_arr[2],date_arr[1] - 1,date_arr[0]);
        }

        tomorrow.setDate(current_date.getDate()+day);
        $('#invoice_supplier_due').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());

        console.log($('#invoice_supplier_due').val());
    } 




    var number_error = 0;

    
function ExportToTable(id) {  
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;  
    /*Checks whether the file is a valid excel file*/  
    if (regex.test($("#excelfile").val().toLowerCase())) {  
        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/  
        if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {  
            xlsxflag = true;  
        }  
        /*Checks whether the browser supports HTML5*/  
        if (typeof (FileReader) != "undefined") {  
            var reader = new FileReader();  
            reader.onload = function (e) {  
                var data = e.target.result;  
                /*Converts the excel data in to object*/  
                if (xlsxflag) {  
                    var workbook = XLSX.read(data, { type: 'binary' });  
                }  
                else {  
                    var workbook = XLS.read(data, { type: 'binary' });  
                }  
                /*Gets all the sheetnames of excel in to a variable*/  
                var sheet_name_list = workbook.SheetNames;  

                var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/  
                sheet_name_list.forEach(function (y) { /*Iterate through all sheets*/  
                    /*Convert the cell value to Json*/  
                    if (xlsxflag) {  
                        var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);  
                    }  
                    else {  
                        var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);  
                    }  
                    if (exceljson.length > 0 && cnt == 0) {  
                        //console.log(exceljson);
                        BindTable(exceljson,id);  
                        cnt++;  
                    }  
                });  
                $('#exceltable').show();  
            }  
            if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/  
                reader.readAsArrayBuffer($("#excelfile")[0].files[0]);  
            }  
            else {  
                reader.readAsBinaryString($("#excelfile")[0].files[0]);  
            }  
        }  
        else {  
            alert("Sorry! Your browser does not support HTML5!");  
        }  
    }  
    else {  
        alert("Please upload a valid Excel file!");  
    }  
}   


function delete_row(id){
    $(id).closest('tr').remove();
} 




function BindTable(jsondata,id) {
    number_error = 0; 

    if($('#supplier_id').val() != ''){
        product_data = jsondata;


        
        var total = 0;
        var str_html = "";
        var count = 0;
        for (var i = 0; i < jsondata.length; i++) {  
            
            if(i==0){
                str_html += '<hr/>'+
                            '<div> '+
                                '<div class="col-lg-6">'+
                                    '<div class="form-group">'+
                                        '<label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>'+
                                        '<input name="invoice_supplier_code_gen[]" class="form-control" onchange="check_code(this)" value="<?php echo $last_code;?>" >'+
                                        '<input name="invoice_check" type="hidden" value="" />'+
                                        '<p class="help-block">Example : RR1801001 OR RF1801001.</p>'+
                                    '</div>'+
                                '</div>'+

                                '<div class="col-lg-6">'+
                                    '<div class="form-group">'+
                                        '<label>วันที่ออกใบกำกับภาษี / Date</label>'+
                                        '<input type="text"  name="invoice_supplier_date[]"  class="form-control calendar"  readonly/>'+
                                        '<p class="help-block">31/01/2018</p>'+
                                    '</div>'+
                                '</div>'+ 
                                '<div class="col-lg-6">'+
                                    '<div class="form-group">'+
                                        '<label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>'+
                                        '<input  name="invoice_supplier_code[]" value="'+jsondata[i].invoice+'" class="form-control" >'+
                                        '<p class="help-block">Example : INV1801001.</p>'+
                                    '</div>'+
                                '</div>';
                            '</div>';
                            //onchange="update_invoice_supplier_due(this)"

                str_html += '<table width="100%" class="table table-striped table-bordered table-hover" >'+
                        '<thead>'+
                            '<tr>'+
                                '<th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>'+
                                '<th style="text-align:center;">รายละเอียดสินค้า <br> (Product Detail)</th>'+
                                '<th style="text-align:center;">คลังสินค้า <br> (Stock)</th>'+
                                '<th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>'+
                                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                '<th style="text-align:center;" width="150">ราคาต่อหน่วย <span name="currency">'+currency+'</span> <br> (Unit price <span name="currency">'+currency+'</span>) </th>'+
                                <?PHP } ?>
                                '<th style="text-align:center;" width="150">ราคาต่อหน่วยบาท <br> (Unit price bath) </th>'+
                                '<th style="text-align:center;" width="150">จำนวนเงินบาท <br> (Amount bath)</th>'+
                                '<th width="24"></th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>';
            }else if(jsondata[i].invoice != jsondata[i-1].invoice){

                var total_val = 0;
                var vat_val = 0;
                var net_val = 0;

                if(vat_type == 1){
                    total_val = $total - ((vat/( 100 + vat)) * total);
                } else if(vat_type == 2){
                    total_val = total;
                } else {
                    total_val = total;
                }

                if( vat_type  == 1){
                     vat_val = ( vat /( 100 +  vat )) *  total;
                } else if(  vat_type  == 2){
                     vat_val = ( vat /100) * total;
                } else {
                    vat_val = 0.0;
                } 


                if( vat_type == 1){
                    net_val =  total;
                } else if( vat_type  == 2){
                    net_val = ( vat /100) *  total + total;
                } else {
                    net_val = total;
                } 

                str_html += '</tbody>'+
                        '<tfoot> '+
                            '<tr class="odd gradeX">'+
                                '<td '+
                                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                'colspan="3" '+
                                <?PHP } else { ?>
                                'colspan="2" '+
                                <?PHP } ?> 
                                'rowspan="3">    '+
                                '</td>'+
                                '<td colspan="3" align="left" style="vertical-align: middle;">'+
                                    '<span>ราคารวมทั้งสิ้น / Sub total</span>'+
                                '</td>'+
                                '<td> '+
                                    '<input type="hidden"   name="invoice_supplier_list_count[]" value="'+count+'" />'+
                                    '<input type="text" class="form-control" style="text-align: right;"  name="invoice_supplier_total_price[]" value="'+numberWithCommas(total_val)+'"  readonly/>'+
                                '</td>'+
                                '<td>'+
                                '</td>'+
                            '</tr>'+
                            '<tr class="odd gradeX">'+
                                '<td colspan="3" align="left" style="vertical-align: middle;">'+
                                    '<table>'+
                                        '<tr>'+
                                            '<td>'+
                                                '<span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>'+
                                            '</td>'+
                                            '<td style = "padding-left:8px;padding-right:8px;width:72px;">'+
                                                '<input type="text" class="form-control" style="text-align: right;"   name="invoice_supplier_vat[]" value="'+numberWithCommas(vat)+'" onchange="calculateAll(this);"/>'+
                                            '</td>'+
                                            '<td width="16">'+
                                            '%'+
                                            '</td>'+
                                        '</tr>'+
                                    '</table>'+ 
                                '</td>'+
                                '<td>'+ 
                                    '<input type="text" class="form-control" style="text-align: right;"   name="invoice_supplier_vat_price[]" value="'+numberWithCommas(vat_val)+'"  readonly/>'+
                                '</td>'+
                                '<td>'+
                                '</td>'+
                            '</tr>'+
                            '<tr class="odd gradeX">'+
                                '<td colspan="3" align="left" style="vertical-align: middle;">'+
                                    '<span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>'+
                                '</td>'+
                                '<td>'+
                                    
                                    '<input type="text" class="form-control" style="text-align: right;"  name="invoice_supplier_net_price[]" value="'+numberWithCommas(net_val)+'" readonly/>'+
                                '</td>'+
                                '<td>'+
                                '</td>'+
                            '</tr>'+
                        '</tfoot>'+
                    '</table>';

                str_html += '<hr/>'+
                            '<div> '+
                                '<div class="col-lg-6">'+
                                    '<div class="form-group">'+
                                        '<label>หมายเลขรับใบกำกับภาษี / recieve code <font color="#F00"><b>*</b></font></label>'+
                                        '<input name="invoice_supplier_code_gen[]" class="form-control" onchange="check_code(this)" value="<?php echo $last_code;?>" >'+
                                        '<input name="invoice_check" type="hidden" value="" />'+
                                        '<p class="help-block">Example : RR1801001 OR RF1801001.</p>'+
                                    '</div>'+
                                '</div>'+

                                '<div class="col-lg-6">'+
                                    '<div class="form-group">'+
                                        '<label>วันที่ออกใบกำกับภาษี / Date</label>'+
                                        '<input type="text" name="invoice_supplier_date[]" class="form-control calendar"  readonly/>'+
                                        '<p class="help-block">31/01/2018</p>'+
                                    '</div>'+
                                '</div>'+ 
                                '<div class="col-lg-6">'+
                                    '<div class="form-group">'+
                                        '<label>หมายเลขใบกำกับภาษี / Inv code <font color="#F00"><b>*</b></font></label>'+
                                        '<input  name="invoice_supplier_code[]" value="'+jsondata[i].invoice+'" class="form-control" >'+
                                        '<p class="help-block">Example : INV1801001.</p>'+
                                    '</div>'+
                                '</div>';
                            '</div>';
//onchange="update_invoice_supplier_due(this)"
                str_html += '<table width="100%" class="table table-striped table-bordered table-hover" >'+
                        '<thead>'+
                            '<tr>'+
                                '<th style="text-align:center;">รหัสสินค้า <br> (Product Code)</th>'+
                                '<th style="text-align:center;">รายละเอียดสินค้า <br> (Product Detail)</th>'+
                                '<th style="text-align:center;">คลังสินค้า <br> (Stock)</th>'+
                                '<th style="text-align:center;" width="150">จำนวน <br> (Qty)</th>'+
                                <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                '<th style="text-align:center;" width="150">ราคาต่อหน่วย <span name="currency">'+currency+'</span> <br> (Unit price <span name="currency">'+currency+'</span>) </th>'+
                                <?PHP } ?>
                                '<th style="text-align:center;" width="150">ราคาต่อหน่วยบาท <br> (Unit price bath) </th>'+
                                '<th style="text-align:center;" width="150">จำนวนเงินบาท <br> (Amount bath)</th>'+
                                '<th width="24"></th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'; 
                total = 0;
                count = 0;
                
            }

            <?PHP 
                if($sort == "ภายนอกประเทศ"){
            ?>
                    
                    exchange_rate_baht = parseFloat(document.getElementById('exchange_rate_baht').value.toString().replace(new RegExp(',', 'g'),'')); 

                    var cost_qty = parseFloat(jsondata[i].qty);
                    var cost_price_ex = parseFloat(jsondata[i].priceNet) ;
                    var cost_price = parseFloat(jsondata[i].priceNet) *  exchange_rate_baht_value  ; 

                    var cost_total = parseFloat(jsondata[i].total) *  exchange_rate_baht_value ;
            <?PHP
                }else{
            ?>
                    var cost_qty = parseFloat(jsondata[i].qty);
                    var cost_price = parseFloat(jsondata[i].priceNet) ;
                    var cost_total = parseFloat(jsondata[i].total);
            <?PHP
                }
            ?>

            total += cost_total;

            count ++;
            str_html += '<tr class="odd gradeX">'+
                    '<td><input type="hidden" name="purchase_order_list_id[]" value="0" /> '+
                        '<input type="hidden" name="invoice_supplier_list_cost[]" value="'+cost_total+'" />'+
                        '<input type="hidden" name="product_code[]" value="'+jsondata[i].edp+'" />'+
                        '<input type="hidden" name="purchase_order_code[]" value="'+jsondata[i].poCode+'" />'+
                        '<input type="hidden" name="purchase_order_list_no[]" value="'+jsondata[i].poNo+'" />'+
                        '<input type="hidden" name="invoice_supplier_list_cost[]" value="'+cost_total+'" />'+
                        '<input type="hidden" name="old_cost[]" value="0" />'+
                        '<input type="hidden" name="old_qty[]" value="0" />'+
                        '<input type="hidden" name="product_id[]" class="form-control" value="0" />'+
                        '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="'+jsondata[i].edp+'"  readonly/>'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" class="form-control" name="product_name[]" readonly value="'+jsondata[i].item+'" />'+
                        '<input type="text" class="form-control" name="invoice_supplier_list_product_name[]"  placeholder="Product Name (Supplier)"/>'+
                        '<input type="text" class="form-control" name="invoice_supplier_list_product_detail[]"  placeholder="Product Detail (Supplier)" />'+
                        '<input type="text" class="form-control" name="invoice_supplier_list_remark[]"  placeholder="Remark" value="'+jsondata[i].poCode+'" />'+
                    '</td>'+
                    '<td> '+
                        '<select name="stock_group_id[]" class="form-control select" data-live-search="true" > '+
                        '</select> '+
                    '</td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_supplier_list_qty[]" value="'+cost_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" /></td>'+
                    <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                        '<td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="purchase_order_list_price[]" value="'+cost_price_ex.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" /></td>'+
                    <?PHP } ?>
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  onchange="update_sum(this);" name="invoice_supplier_list_price[]" value="'+cost_price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;" readonly onchange="update_sum(this);" name="invoice_supplier_list_total[]" value="'+cost_total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")+'" /></td>'+
                    '<td>'+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>';

            
        }

        var total_val = 0;
        var vat_val = 0;
        var net_val = 0;

        if(vat_type == 1){
            total_val = total - ((vat/( 100 + vat)) * total);
        } else if(vat_type == 2){
            total_val = total;
        } else {
            total_val = total;
        }

        if( vat_type  == 1){
                vat_val = ( vat /( 100 +  vat )) *  total;
        } else if(  vat_type  == 2){
                vat_val = ( vat /100) * total;
        } else {
            vat_val = 0.0;
        } 


        if( vat_type == 1){
            net_val =  total;
        } else if( vat_type  == 2){
            net_val = ( vat /100) *  total + total;
        } else {
            net_val = total;
        } 

        str_html += '</tbody>'+
                '<tfoot> '+
                    '<tr class="odd gradeX">'+
                        '<td '+
                        <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                        'colspan="3" '+
                        <?PHP } else { ?>
                        'colspan="2" '+
                        <?PHP } ?> 
                        'rowspan="3">    '+
                        '</td>'+
                        '<td colspan="3" align="left" style="vertical-align: middle;">'+
                            '<span>ราคารวมทั้งสิ้น / Sub total</span>'+
                        '</td>'+
                        '<td> '+
                            '<input type="hidden"   name="invoice_supplier_list_count[]" value="'+count+'" />'+
                            '<input type="text" class="form-control" style="text-align: right;"  name="invoice_supplier_total_price[]" value="'+numberWithCommas(total_val)+'"  readonly/>'+
                        '</td>'+
                        '<td>'+
                        '</td>'+
                    '</tr>'+
                    '<tr class="odd gradeX">'+
                        '<td colspan="3" align="left" style="vertical-align: middle;">'+
                            '<table>'+
                                '<tr>'+
                                    '<td>'+
                                        '<span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>'+
                                    '</td>'+
                                    '<td style = "padding-left:8px;padding-right:8px;width:72px;">'+
                                        '<input type="text" class="form-control" style="text-align: right;"   name="invoice_supplier_vat[]" value="'+numberWithCommas(vat)+'" onchange="calculateAll(this);"/>'+
                                    '</td>'+
                                    '<td width="16">'+
                                    '%'+
                                    '</td>'+
                                '</tr>'+
                            '</table>'+ 
                        '</td>'+
                        '<td>'+ 
                            '<input type="text" class="form-control" style="text-align: right;"   name="invoice_supplier_vat_price[]" value="'+numberWithCommas(vat_val)+'"  readonly/>'+
                        '</td>'+
                        '<td>'+
                        '</td>'+
                    '</tr>'+
                    '<tr class="odd gradeX">'+
                        '<td colspan="3" align="left" style="vertical-align: middle;">'+
                            '<span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>'+
                        '</td>'+
                        '<td>'+
                            
                            '<input type="text" class="form-control" style="text-align: right;"  name="invoice_supplier_net_price[]" value="'+numberWithCommas(net_val)+'" readonly/>'+
                        '</td>'+
                        '<td>'+
                        '</td>'+
                    '</tr>'+
                '</tfoot>'+
            '</table>' ;

        $("#display_import").html(str_html);

        var str = "";
        $.each(stock_group_data, function (index, value) { 
            str += "<option value='" + value['stock_group_id'] + "'>" +  value['stock_group_name'] + "</option>"; 
        });
        $('select[name="stock_group_id[]"]').html(str);

        $('select[name="stock_group_id[]"]').selectpicker();

        $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });

        $("#excelfile").val(''); 
    }else{
            alert('Please select supplier.');
    }  
    //console.log(jsondata);
}




function search_pop_like(id){ 

    if($(id).is(':checked')){
        $('tr[class="odd gradeX find"]').hide();
        console.log("checked");
    }else{
        $('tr[class="odd gradeX find"]').show();
        console.log("unchecked");
    }
}

function export_error(){
    $('tr[class="odd gradeX find"]').remove();
    var d = new Date();

    var downloadLink = document.createElement("a");
    downloadLink.href = 'data:application/vnd.ms-excel,' + encodeURIComponent($('#tb_import').html());
    downloadLink.download = "export-error "+d.getFullYear() +"-"+ (d.getMonth() + 1) +"-"+ d.getDate() +".xls";

    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
    //window.open('data:application/vnd.ms-excel,filename=export-error.xls,' + encodeURIComponent($('#tb_import').html()));
    $('#modalAdd').modal('hide');

}





<?PHP if($sort == "ภายนอกประเทศ"){ ?>
    function update_sum(id){ 
      
        var qty = $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]');
        var purchase_price = $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="purchase_order_list_price[]"]')  ;
        var price = $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_list_price[]"]')  ;
        var sum = $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_list_total[]"]')  ;

        var exchange_rate = parseFloat(document.getElementById('exchange_rate_baht').value.replace(',',''));
        console.log(purchase_price);
        console.log(qty);
        for(var i = 0 ; i < qty.length ; i++){  
            
            var val_qty =  parseFloat(qty[i].value.replace(',',''));
            var val_purchase_price =  parseFloat(purchase_price[i].value.replace(',',''));
            var val_price =  parseFloat(price[i].value.replace(',',''));
            var val_sum =  parseFloat(sum[i].value.replace(',',''));
            

            if(isNaN(val_qty)){
                val_qty = 0;
            }

            if(isNaN(val_purchase_price)){
                val_purchase_price = 0.0;
            }

            if(isNaN(val_price)){
                val_price = 0.0;
            }

            if(isNaN(val_sum)){
                val_sum = 0.0;
            }

            val_price =  val_purchase_price * exchange_rate;
            val_sum = val_qty*val_price;

            console.log("val_price",val_price);

            qty[i].value = val_qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;
            price[i].value = numberWithCommas(val_price.toFixed(4)) ;
            sum[i].value = val_sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
        }  
        calculateAll(id);


    }
<?PHP } else { ?>
     function update_sum(id){
        var val_qty  = $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_list_qty[]"]');
        for(var i = 0 ; i < val_qty.length ; i++){ 
            id = val_qty[i];
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
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_cost[]"]').val( price.toFixed(2) );
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $(id).closest('tr').children('td').children('input[name="invoice_supplier_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        }
        calculateAll(id);

        
    }

<?PHP } ?>


    function calculateAll(id){
        
  
        var val = $(id).closest('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_list_total[]"]');  
        var total = 0.0;

        var vat =  parseFloat($(id).closest('table').children('tfoot').children('tr').children('td').children('table').children('tbody').children('tr').children('td').children('input[name="invoice_supplier_vat[]"]').val().replace(',',''));
        
        
        for(var i = 0 ; i < val.length ; i++){ 
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $(id).closest('table').children('tfoot').children('tr').children('td').children('input[name="invoice_supplier_total_price[]"]').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('table').children('tfoot').children('tr').children('td').children('input[name="invoice_supplier_vat_price[]"]').val((total * (vat/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('table').children('tfoot').children('tr').children('td').children('input[name="invoice_supplier_net_price[]"]').val((total * (vat/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }



    function calculate_list(id){
        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="qty"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="price"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="total"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="qty"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="price"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="total"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

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
                <div class="row">
                    <div class="col-md-6">
                        เพิ่มใบกำกับภาษีรับเข้า / Import Invoice Supplier
                    </div> 
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" id="excelfile" />
                            </div>
                            <div class="col-md-6" align="right">
                                <a class="btn btn-success " href="javascript:;" onclick="ExportToTable(this)" ><i class="fa fa-plus" aria-hidden="true"></i> Import invoice list </a>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=invoice_supplier&action=import-save" >
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
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
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_name" name="invoice_supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> " >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_supplier_branch" name="invoice_supplier_branch" class="form-control" value="<?php echo $supplier['supplier_branch'];?>" >
                                        <p class="help-block">Example : 0000 </p>
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
                            <?PHP if($sort == "ภายนอกประเทศ"){ ?>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Exchange rate Baht<font color="#F00"><b>*</b></font></label>
                                        <input  id="exchange_rate_baht" name="exchange_rate_baht"  class="form-control" value="<?php echo number_format($exchange_rate_baht['exchange_rate_baht_value'],5);?>" >
                                        <p class="help-block">Example : 0.</p>
                                    </div>
                                </div> 
                            <?PHP } ?>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row">

                                 <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่รับสินค้า / Date recieve</label>
                                        <input type="text" id="invoice_supplier_date_recieve" name="invoice_supplier_date_recieve" value="<?PHP echo $first_date;?>"  class="form-control calendar" onchange="check_date(this);" readonly/>
                                        <input id="date_check" type="hidden" value="" />
                                        <p class="help-block">31/01/2018</p>
                                    </div>
                                </div>        
                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>ผู้รับใบกำกับภาษี / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option <?PHP if($admin_id == $users[$i]['user_id']){?> SELECTED <?PHP }?> value="<?php echo $users[$i]['user_id'] ?>"><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                <div class="col-lg-6" style="display:none">
                                    <div class="form-group">
                                        <label>เครดิต / Credit Day </label>
                                        <input type="text" id="invoice_supplier_due_day" name="invoice_supplier_due_day"  class="form-control" value="<?PHP echo $supplier['credit_day']; ?>" /> 
                                        <p class="help-block">30</p>
                                    </div>
                                </div> 

                                <div class="col-lg-6" style="display:none">
                                    <div class="form-group">
                                        <label>กำหนดชำระ / Due </label>
                                        <input type="text" id="invoice_supplier_due" name="invoice_supplier_due"  class="form-control calendar" value="" readonly/> 
                                        <p class="help-block">01-03-2018 </p>
                                    </div>
                                </div>

                                <div class="col-lg-12" style="display:none">
                                    <div class="form-group">
                                        <label>เงื่อนไขการชำระ / term </label>
                                        <input type="text" id="invoice_supplier_term" name="invoice_supplier_term"  class="form-control" value="<?PHP echo $supplier['condition_pay']; ?>" />
                                        <p class="help-block">Bank </p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>

                    <div id="display_import">
                    
                    </div>
                   
                
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=invoice_supplier" class="btn btn-default">Back</a>
                            <button type="reset" class="btn btn-primary">Reset</button>
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
 