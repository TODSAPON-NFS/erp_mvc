<script src="../plugins/excel/xlsx.core.min.js"></script>  
<script src="../plugins/excel/xls.core.min.js"></script> 
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

    function check(){


        var stock_issue_code = document.getElementById("stock_issue_code").value;
        var employee_id = document.getElementById("employee_id").value;
        var invoice_customer_id = document.getElementById("invoice_customer_id").value;
        var stock_group_id = document.getElementById("stock_group_id").value;
        var stock_issue_date = document.getElementById("stock_issue_date").value;
        
        
        stock_issue_code = $.trim(stock_issue_code);
        stock_issue_date = $.trim(stock_issue_date);
        employee_id = $.trim(employee_id);
        stock_group_id = $.trim(stock_group_id_out);
        invoice_customer_id = $.trim(stock_group_id_in);
        

        if(invoice_customer_id.length == 0){
            alert("Please input invoice code");
            document.getElementById("invoice_customer_id").focus();
            return false;
        }else if(stock_group_id.length == 0){
            alert("Please input stock group");
            document.getElementById("stock_group_id").focus();
            return false;
        }else if(stock_issue_code.length == 0){
            alert("Please input delivery note stock issue code");
            document.getElementById("stock_issue_code").focus();
            return false;
        }else if(stock_issue_date.length == 0){
            alert("Please input delivery note stock issue date");
            document.getElementById("stock_issue_date").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }



    }


    
    function delete_row(id){
        $(id).closest('tr').remove();
     }


     function show_data(id){
         
            $.post( "controllers/getProductDataByID.php", { 'product_id': $(id).val(),'stock_group_id':$('#stock_group_id').val() }, function( data ) {
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val( data.product_name );
                $(id).closest('tr').children('td').children('input[name="stock_issue_list_qty[]"]').val( data.product_qty );
                $(id).closest('tr').children('td').children('input[name="stock_issue_list_price[]"]').val( data.product_price );
                update_sum(id);
            });
         
       
        
     }


     function add_row(id){
        if($('#stock_group_id').val() != ''){
            var index = 0;
            if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                index = 1;
            }else{
                index = $(id).closest('table').children('tbody').children('tr').length + 1;
            }
            $(id).closest('table').children('tbody').append(
                '<tr class="odd gradeX">'+
                    '<td>'+  
                        '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                    '</td>'+
                    '<td>'+
                    '<input type="text" class="form-control" name="product_name[]" readonly />'+
                    '<span>Remark:</span>'+
                    '<input type="text" class="form-control" name="stock_issue_list_remark[]" />'+
                    '</td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;" onchange="update_sum(this);" name="stock_issue_list_qty[]"  /></td>'+
                    '<td><input type="text" class="form-control" style="text-align: right;" name="stock_issue_list_price[]"  readonly /></td>'+
                    '<td><input type="text" class="form-control" style="text-align: right;" name="stock_issue_list_total[]"  readonly /></td>'+
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
        }else{
             alert('Please select stock group.');
        }
    }

    function get_invoice_customer_detail(){
        var invoice_customer_id = document.getElementById('invoice_customer_id').value;
        $.post( "controllers/getInvoiceCustomerByID.php", { 'invoice_customer_id': invoice_customer_id }, function( data ) {
            document.getElementById('employee_name').value = data.user_name + data.user_lastname +' (' + data.user_position_name +')';
            document.getElementById('invoice_customer_name').value = data.invoice_customer_name;
            document.getElementById('invoice_customer_address').value = data.invoice_customer_address ;
            document.getElementById('invoice_customer_tax').value = data.invoice_customer_tax ;
            document.getElementById('invoice_customer_total_price').value = data.invoice_customer_total_price ;
        });
    }

    function calculateAll(){

        var val = document.getElementsByName('stock_issue_list_total[]');
        var total = 0.0;

        for(var i = 0 ; i < val.length ; i++){
            if($.isNumeric(val[i].value.toString().replace(new RegExp(',', 'g'),''))){
                total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
            }
            
        }

        $('#stock_issue_total').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
    }

    function update_sum(id){

        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="stock_issue_list_qty[]"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="stock_issue_list_price[]"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="stock_issue_list_total[]"]').val( ).replace(',',''));

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

        $(id).closest('tr').children('td').children('input[name="stock_issue_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="stock_issue_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="stock_issue_list_total[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();


    }

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

    function BindTable(jsondata,id) {
        
        if($('#stock_group_id').val() != ''){
            for (var i = 0; i < jsondata.length; i++) {  
                $.post( "controllers/getProductDataByCode.php", { 'product_code': jsondata[i].product_code,'stock_group_id':$('#stock_group_id').val(),'qty':jsondata[i].qty }, function( data ) {
                    if(data != null){
                        var index = 0;
                        if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                            index = 1;
                        }else{
                            index = $(id).closest('table').children('tbody').children('tr').length + 1;
                        }
                        
                        $(id).closest('table').children('tbody').append(
                            '<tr class="odd gradeX">'+
                                '<td>'+  
                                    '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                                '</td>'+
                                '<td>'+
                                '<input type="text" class="form-control" name="product_name[]" value="'+data.product_name+'" readonly />'+
                                '<span>Remark:</span>'+
                                '<input type="text" class="form-control" name="stock_issue_list_remark[]" />'+
                                '</td>'+
                                '<td align="right"><input type="text" class="form-control" style="text-align: right;" onchange="update_sum(this);" name="stock_issue_list_qty[]" value="'+data.product_qty+'"  /></td>'+
                                '<td><input type="text" class="form-control" style="text-align: right;" name="stock_issue_list_price[]"   value="'+data.product_price+'" readonly /></td>'+
                                '<td><input type="text" class="form-control" style="text-align: right;" name="stock_issue_list_total[]"  value="'+(data.product_qty * data.product_price)+'" readonly /></td>'+
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
                            if(value['product_code'] == data.product_code){
                                str += "<option value='" + value['product_id'] + "' selected>"+value['product_code']+"</option>";
                            }else{
                                str += "<option value='" + value['product_id'] + "' >"+value['product_code']+"</option>";
                            }
                            
                        });
                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

                        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();  
                        calculateAll();
                    }else{
                        var index = 0;
                        if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
                            index = 1;
                        }else{
                            index = $(id).closest('table').children('tbody').children('tr').length + 1;
                        }
                        $(id).closest('table').children('tbody').append(
                            '<tr class="odd gradeX">'+
                                '<td>'+  
                                    '<select class="form-control select" type="text" name="product_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                                '</td>'+
                                '<td>'+
                                '<input type="text" class="form-control" name="product_name[]" readonly />'+
                                '<span>Remark:</span>'+
                                '<input type="text" class="form-control" name="stock_issue_list_remark[]" />'+
                                '</td>'+
                                '<td align="right"><input type="text" class="form-control" style="text-align: right;" onchange="update_sum(this);" name="stock_issue_list_qty[]"  /></td>'+
                                '<td><input type="text" class="form-control" style="text-align: right;" name="stock_issue_list_price[]"  readonly /></td>'+
                                '<td><input type="text" class="form-control" style="text-align: right;" name="stock_issue_list_total[]"  readonly /></td>'+
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
                    
                });
            }
            $("#excelfile").val('');
        }else{
             alert('Please select stock group.');
        }  
        //console.log(jsondata);
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Stock Issue Management</h1>
    </div>
    <div class="col-lg-6" align="right">
       
    </div>
    <!-- /.col-lg-12 -->
</div>
<div id="display">
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มใบตัดคลังสินค้า /  Add Stock Issue   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <form role="form" method="post" onsubmit="return check();" action="index.php?app=stock_issue&action=add" enctype="multipart/form-data">
                <div class="row">
                        <div class="col-lg-5">
                            <div class="row">
                            <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ใบกำกับภาษี / Invoice Code <font color="#F00"><b>*</b></font></label>
                                        <select id="invoice_customer_id" name="invoice_customer_id" class="form-control select" data-live-search="true" onchange="get_invoice_customer_detail();">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($invoice_customers) ; $i++)
                                            {
                                            ?>
                                            <option <?php if($invoice_customers[$i]['invoice_customer_id'] == $stock_issue['invoice_customer_id']){?> selected <?php }?> value="<?php echo $invoice_customers[$i]['invoice_customer_id'] ?>"><?php echo $invoice_customers[$i]['invoice_customer_code'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Main Stock.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ตัดจากคลังสินค้า / Stock  <font color="#F00"><b>*</b></font> </label>
                                        <select id="stock_group_id" name="stock_group_id" class="form-control select" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($stock_groups) ; $i++)
                                            {
                                            ?>
                                            <option <?php if($stock_groups[$i]['stock_group_id'] == $stock_issue['stock_group_id']){?> selected <?php }?> value="<?php echo $stock_groups[$i]['stock_group_id'] ?>"><?php echo $stock_groups[$i]['stock_group_name'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Tool Management Stock.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ชื่อตามใบกำกับภาษี / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_name" name="invoice_customer_name" class="form-control" value="<?php echo $invoice_customer['invoice_customer_name'];?>" >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="invoice_customer_address" name="invoice_customer_address" class="form-control" rows="5" ><?php echo $invoice_customer['invoice_customer_address'];?></textarea >
                                        <p class="help-block">Example : IN.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_tax" name="invoice_customer_tax" class="form-control" value="<?php echo $invoice_customer['invoice_customer_tax'];?>" >
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Employee <font color="#F00"><b>*</b></font></label>
                                        <input  id="employee_name" name="employee_name" class="form-control" value="<?php echo $invoice_customer['user_name'];?> <?php echo $invoice_customer['user_lastname'];?>" >
                                        <p class="help-block">Example : thana thepchuleepornsil.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>จำนวนเงินรวม / Total <font color="#F00"><b>*</b></font></label>
                                        <input  id="invoice_customer_total_price" name="invoice_customer_total_price" class="form-control" value="<?php echo $invoice_customer['invoice_customer_total_price'];?>" >
                                        <p class="help-block">Example : 1000.</p>
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div class="col-lg-2">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเลขใบตัดสินค้า / Stock Issue Code <font color="#F00"><b>*</b></font></label>
                                        <input id="stock_issue_code" name="stock_issue_code" class="form-control" value="<?php echo $last_code;?>" readonly>
                                        <p class="help-block">Example : SM1801001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>วันที่ออกใบตัดสินค้า / Stock Issue Date</label>
                                        <input type="text" id="stock_issue_date" name="stock_issue_date"  class="form-control calendar" value="<?php echo $stock_issue['stock_issue_date'];?>" readonly/>
                                        <p class="help-block">31-01-2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ผู้ตัดสินค้า / Employee  <font color="#F00"><b>*</b></font> </label>
                                        <select id="employee_id" name="employee_id" class="form-control select" data-live-search="true">
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
                                        <label>หมายเหตุ / Remark</label>
                                        <textarea id="stock_issue_remark" name="stock_issue_remark"  class="form-control"><?php echo $stock_issue['stock_issue_remark'];?></textarea>
                                        <p class="help-block">- </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า / หมายเหตุ<br>(Product Name / Remark)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">ราคาต่อหน่วย<br>(Qty)</th>
                                <th style="text-align:center;">ราคารวม<br>(Qty)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($stock_issue_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <select  class="form-control select" name="product_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($products) ; $ii++){
                                        ?>
                                        <option <?php if($products[$ii]['product_id'] == $stock_issue_lists[$i]['product_id']){?> selected <?php }?> value="<?php echo $products[$ii]['product_id'] ?>"><?php echo $products[$ii]['product_code'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $stock_issue_lists[$i]['product_name']; ?>" />
                                    <span>Remark : </span>
                                    <input type="text" class="form-control" name="stock_issue_list_remark[]" value="<?php echo $stock_issue_lists[$i]['stock_issue_list_remark']; ?>" />
                                </td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" onchange="update_sum(this);"  name="stock_issue_list_qty[]" value="<?php echo $stock_issue_lists[$i]['stock_issue_list_qty']; ?>" /></td>
                                <td><input type="text" class="form-control" style="text-align: right;"  name="stock_issue_list_price[]" value="<?php echo $stock_issue_lists[$i]['stock_issue_list_price']; ?>" readonly /></td>
                                <td><input type="text" class="form-control" style="text-align: right;"  name="stock_issue_list_total[]" value="<?php echo $stock_issue_lists[$i]['stock_issue_list_total']; ?>" readonly /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="file" id="excelfile" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="button" id="viewfile" value="เพิ่มรายการสินค้า" onclick="ExportToTable(this)" /> 
                                        </div>
                                    </div> 
                                </td>
                                <td colspan="2" align="right">
                                    ต้นทุนรวม
                                </td>
                                <td>
                                <input type="text" class="form-control" style="text-align: right;" id="stock_issue_total"  name="stock_issue_total" value="<?php echo $stock_issue['stock_issue_total']; ?>" readonly />
                                </td>
                                <td>
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=stock_issue" class="btn btn-default">Back</a>
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