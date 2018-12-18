<script src="../plugins/excel/xlsx.core.min.js"></script>  
<script src="../plugins/excel/xls.core.min.js"></script> 
<script>

var options = {
    url: function(keyword) {
        return "controllers/getProductByKeyword.php?keyword="+keyword;
    },

    getValue: function(element) {
        return element.product_code ;
    },

    ajaxSettings: {
        dataType: "json",
        method: "POST",
        data: {
            dataType: "json"
        }
    },

    preparePostData: function(data) {
        data.keyword = $(".example-ajax-post").val();
        return data;
    },

    requestDelay: 400
};

var number_error = 0;

function check(){


var product_id = document.getElementById("product_id").value;
var summit_product_qty = document.getElementById("summit_product_qty").value;
var summit_product_cost = document.getElementById("summit_product_cost").value;
var stock_group_id = document.getElementById("stock_group_id").value;

product_id = $.trim(product_id);
summit_product_qty = $.trim(summit_product_qty);
summit_product_cost = $.trim(summit_product_cost);
stock_group_id = $.trim(stock_group_id);


if(product_id.length == 0){
    alert("Please input product.");
    document.getElementById("product_id").focus();
    return false;
}else if(stock_group_id.length == 0){
    alert("Please input stock");
    document.getElementById("stock_group_id").focus();
    return false;
}else if(summit_product_qty.length == 0){
    alert("Please input product qty.");
    document.getElementById("summit_product_qty").focus();
    return false;
}else if(summit_product_cost.length == 0){
    alert("Please input product price.");
    document.getElementById("summit_product_cost").focus();
    return false;
}else{
    return true;
}



}

function delete_row(id){
    $(id).closest('tr').remove();
}

function getStockDetail(){
    var stock_group_id = $('#stock_group_id').val();
    window.location = "?app=summit_product&action=view-stock&stock_group_id="+stock_group_id;

}
 

function update_sum(){

    var qty =  parseFloat(document.getElementById('summit_product_qty').value.replace(',',''));
    var price =  parseFloat(document.getElementById('summit_product_cost').value .replace(',',''));
    var sum =  parseFloat(document.getElementById('summit_product_total').value .replace(',',''));


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




    document.getElementById('summit_product_qty').value = qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById('summit_product_cost').value = price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    document.getElementById('summit_product_total').value = sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");



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
    $("#bodyAdd").html('');
    number_error = 0;
    if($('#stock_group_id').val() != ''){ 
        for (var i = 0; i < jsondata.length; i++) {  
            get_product_row(jsondata[i].product_code,jsondata[i].qty,jsondata[i].price);
        }

        $("#excelfile").val('');
        $('#modalAdd').modal('show');
    }else{
            alert('Please select stock group.');
    }  
    //console.log(jsondata);
}


function get_product_row(product_code,qty,price){
    $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
        if(data != null){
            $("#bodyAdd").append(
                '<tr class="odd gradeX find">'+ 
                    '<td>'+  
                        '<input type="hidden" name="product_id[]" value="'+data.product_id+'" />'+
                        '['+ data.product_code_first + data.product_code +'] ' + data.product_name +
                    '</td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_qty[]" value="'+qty+'" readonly /></td>'+
                    '<td><input type="text" class="form-control" style="text-align: right;" name="product_price[]"   value="'+price+'" readonly /></td>'+
                    '<td><input type="text" class="form-control" style="text-align: right;" name="product_price_total[]"  value="'+ ( parseFloat(qty.replace(',','')) * parseFloat(price.replace(',','')) )+'" readonly /></td>'+
                    '<td>'+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
            );
        }else{
            number_error ++;
            $('.number_error').html(number_error);
            $("#bodyAdd").append(
                '<tr class="odd gradeX not-find" >'+ 
                    '<td style="background:#888;">'+   
                        'ไม่มีสินค้าชื่อ "' +  product_code + '" นี้' +
                    '</td>'+
                    '<td style="background:#888;" align="right">'+qty+'</td>'+
                    '<td style="background:#888;" align="right">'+price+'</td>'+
                    '<td style="background:#888;" align="right">'+ ( parseFloat(qty.replace(',','')) * parseFloat(price.replace(',','')) )+'</td>'+
                    '<td style="background:#888;" >'+
                        '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                            '<i class="fa fa-times" aria-hidden="true"></i>'+
                        '</a>'+
                    '</td>'+
                '</tr>'
            );
        } 
    });
}


function checkAll(id)
{
    var checkbox = document.getElementsByName("check_all");

    if (checkbox[0].checked == true ){
        $('input[name="check_all"]').prop('checked', true);
        $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', true);
    }else{
        $('input[name="check_all"]').prop('checked', false);
        $(id).closest('table').children('tbody').children('tr').children('td').children('input[type="checkbox"]').prop('checked', false);
    }
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

function getProductDetail(id){
    var product_code = $(id).val();
    $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
        if(data != null){
            $('#product_id').val(data.product_id);
        }
    }); 

}
 
</script>

<div class="row">
    <div class="col-lg-8">
        <h1 class="page-header">Summit Product Management</h1>
    </div>
    <div class="col-lg-4" align="right">
        <a href="?app=summit_product&action=view-product" class="btn btn-primary btn-menu ">แบ่งตามสินค้า</a>
        <a href="?app=summit_product&action=view-stock" class="btn btn-primary btn-menu active">แบ่งตามคลังสินค้า</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                รายละเอียดคลังสินค้า / Stock detail 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>คลังสินค้า / Stock <font color="#F00"><b>*</b></font></label>
                                    <select id="stock_group_id" name="stock_group_id" onchange="getStockDetail()"  class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($stock_groups) ; $i++){
                                    ?>
                                    <option <?if($stock_groups[$i]['stock_group_id'] == $stock_group_id ){?> selected <?php } ?> value="<?php echo $stock_groups[$i]['stock_group_id'] ?>"><?php echo $stock_groups[$i]['stock_group_name'] ?>  </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                
                                <div class="form-group">
                                    <label>รายละเอียด / Description  <font color="#F00"><b>*</b></font> </label>
                                    <p id="stock_group_detail" class="help-block"><?PHP echo $stock_group ['stock_group_detail'];?></p>
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
                รายการสินค้ายกยอดมา
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();"   action="index.php?app=summit_product&action=add-stock&stock_group_id=<?php echo $stock_group_id?>"   enctype="multipart/form-data">
                <input type="hidden"  id="stock_group_id" name="stock_group_id" value="<?php echo $stock_group_id ?>" />
                   
                   <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>สินค้า / Product <font color="#F00"><b>*</b></font></label>
                                <input id="product_id" name="product_id"  type="hidden" />
                                <input class="example-ajax-post form-control" id="product_id_select" name="product_id_select" onchange="getProductDetail(this);" placeholder="Product Code" value="<?php echo $product['product_code']; ?>"  />
                                <p class="help-block">Example : WNMG080406EN .</p>
                            </div>
                        </div>
                       
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>จำนวน / Qty <font color="#F00"><b>*</b></font></label>
                                <input id="summit_product_qty" name="summit_product_qty" type="text" onchange="update_sum();" class="form-control" style="text-align:right;" value="<?php echo number_format($summit_products['summit_product_qty'],0);?>">
                                <p class="help-block">Example : 100 pc.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ราคา / price <font color="#F00"><b>*</b></font></label>
                                <input id="summit_product_cost" name="summit_product_cost" type="text" onchange="update_sum();" class="form-control" style="text-align:right;" value="<?php echo number_format($summit_products['summit_product_cost'],2);?>">
                                <p class="help-block">Example : 50.</p>
                            </div>
                        </div>
                       
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>ราคารวม / Total price <font color="#F00"><b>*</b></font></label>
                                <input id="summit_product_total" name="summit_product_total" type="text" class="form-control" style="text-align:right;" value="<?php echo number_format($summit_products['summit_product_total'],2);?>" readonly>
                                <p class="help-block">Example : 5000.</p>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=product&action=update&id=<? echo $product_id;?>" class="btn btn-primary" >Reset</a>
                            <button  type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-6">
                        <input type="file" id="excelfile" />
                    </div>
                    <div class="col-md-6">
                        <input type="button" id="viewfile" value="เพิ่มรายการสินค้า" onclick="ExportToTable(this)" /> 
                    </div>
                </div> 

                <br>
                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($summit_products),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>
                <form role="form" method="post" onsubmit="return confirm('คุณต้องการลบข้อมูลที่เลือกใช่หรือไม่');"   action="?app=summit_product&action=delete-all-stock&stock_group_id=<?php echo $stock_group_id;?>" >
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th> </th>
                                <th>No.</th>
                                <th>สินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคาต่อชิ้น</th>
                                <th>ราคารวม</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                            for($i=$page * $page_size ; $i < count($summit_products) && $i < $page * $page_size + $page_size; $i++){
                            ?>
                            
                            <tr class="odd gradeX">
                                <td><input type="checkbox" name="summit_product_id[]" value="<?php echo $summit_products[$i]['summit_product_id'];?>" /></td>
                                <td><?php echo $i+1; ?></td>
                                <td>[<?php echo $summit_products[$i]['product_code_first'] . $summit_products[$i]['product_code']; ?>] <?php echo $summit_products[$i]['product_name']; ?>  </td>
                                <td align="right"><?php echo number_format($summit_products[$i]['summit_product_qty'],0); ?></td>
                                <td align="right"><?php echo number_format($summit_products[$i]['summit_product_cost'],2); ?></td>
                                <td align="right"><?php echo number_format($summit_products[$i]['summit_product_total'],2); ?></td>
                                <td>
                                    <a href="?app=summit_product&action=delete-stock&stock_group_id=<?php echo $stock_group_id;?>&summit_product_id=<?php echo $summit_products[$i]['summit_product_id'];?>" onclick="return confirm('You want to delete supplier : <?php echo $summit_products[$i]['supplier_name_en']; ?> (<?php echo $summit_products[$i]['supplier_name_th']; ?>)');" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>

                            <?
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    <input type="checkbox" value="all" name="check_all" onclick="checkAll(this)" />
                                    <button type="summit" class="btn btn-danger">ลบข้อมูล</button>
                                </td>
                                <td align="right">
                                    <b>มูลค่ารวมทั้งหมด</b>
                                </td>
                                <td align="right">
                                    <b>
                                    <?PHP
                                    $total = 0; 
                                    for($i=0 ; $i < count($summit_products); $i ++){
                                        $total += $summit_products[$i]['summit_product_total'];
                                    }
                                    echo number_format($total,2);
                                    ?>
                                    </b>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($summit_products),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=summit_product&action=view-stock&stock_group_id=<?php echo $stock_group_id?>&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>

                            </ul>
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


<form role="form" method="post"   action="index.php?app=summit_product&action=addgroup-stock&stock_group_id=<?php echo $stock_group_id?>"   enctype="multipart/form-data">
                
    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">เลือกรายการสินค้า / Choose product</h4>
            </div>

            <div  class="modal-body">
                <div class="row">
                    <div class="col-md-offset-8 col-md-4" align="right">
                        <input type="checkbox" id="search_pop" onchange="search_pop_like(this)"  /> แสดงรายการที่มีปัญหาจำนวน <span class="number_error"></span> รายการ
                    </div>
                </div>
                <br>
                <div id="tb_import">
                    <table width="100%"  class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr> 
                                <th style="text-align:center;">ชื่อสินค้า</th>
                                <th style="text-align:right;" width="100">จำนวน <br> (Qty)</th>
                                <th style="text-align:right;" width="100">ราคา <br> (Price)</th>
                                <th style="text-align:right;" width="100">ราคารวม <br> (Total price)</th>
                                <th> ลบ <br> Delete</th>
                            </tr>
                        </thead>
                        <tbody id="bodyAdd">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger"  onclick="export_error()" >Export Error (<span class="number_error"></span>)</button>
                <button type="summit" class="btn btn-primary" > Add </button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>
<script>
$(".example-ajax-post").easyAutocomplete(options);
</script>