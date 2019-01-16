           
<script src="../plugins/excel/xlsx.core.min.js"></script>  
<script src="../plugins/excel/xls.core.min.js"></script> 

<script>

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
    $("#search_pop").attr('checked',false);
    $("#bodyAdd").html('');

    if($('#stock_group_id').val() != ''){
        product_data = jsondata;
        for (var i = 0; i < jsondata.length; i++) {  
            get_product_row(jsondata[i],i);
        }

        $("#excelfile").val('');
        $('#modalAdd').modal('show');
    }else{
            alert('Please select stock group.');
    }  
    //console.log(jsondata);
}


function get_product_row(product,i){
    $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product.product_code)}, function( data ) {
        
        if (product.product_price_1 == undefined){
            product.product_price_1 = 0;
        }
        if (product.product_price_2 == undefined){
            product.product_price_2 = 0;
        }
        if (product.product_price_3 == undefined){
            product.product_price_3 = 0;
        }
        if (product.product_price_4 == undefined){
            product.product_price_4 = 0;
        }
        if (product.product_price_5 == undefined){
            product.product_price_5 = 0;
        }
        if (product.product_price_6 == undefined){
            product.product_price_6 = 0;
        }
        if (product.product_price_7 == undefined){
            product.product_price_7 = 0;
        }

        if(data != null){
            

            $("#bodyAdd").append(
                '<tr class="odd gradeX find">'+ 
                    '<td>'+  
                        '<input type="hidden" name="product_id[]" value="'+data.product_id+'" />'+
                        '['+ data.product_code_first + data.product_code +'] ' + data.product_name +
                    '</td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_1[]" value="'+product.product_price_1+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_2[]" value="'+product.product_price_2+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_3[]" value="'+product.product_price_3+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_4[]" value="'+product.product_price_4+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_5[]" value="'+product.product_price_5+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_5[]" value="'+product.product_price_6+'" readonly /></td>'+
                    '<td align="right"><input type="text" class="form-control" style="text-align: right;"  name="product_price_5[]" value="'+product.product_price_7+'" readonly /></td>'+
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
                        'ไม่มีสินค้าชื่อ "' + product.product_code + '" นี้' +
                    '</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_1+'</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_2+'</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_3+'</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_4+'</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_5+'</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_6+'</td>'+
                    '<td style="background:#888;" align="right">'+product.product_price_7+'</td>'+
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

function show_update(product_id){
    $.post( "controllers/getProductByID.php", { 'product_id': $.trim(product_id)}, function( data ) {
        if(data != null){
            $('#product_id').val(data.product_id);
            $('#product_code').val(data.product_code);
            $('#product_name').val(data.product_name);
            $('#product_price_1').val(data.product_price_1);
            $('#product_price_2').val(data.product_price_2);
            $('#product_price_3').val(data.product_price_3);
            $('#product_price_4').val(data.product_price_4);
            $('#product_price_5').val(data.product_price_5);
            $('#product_price_6').val(data.product_price_6);
            $('#product_price_7').val(data.product_price_7);
            $('#modalUpdate').modal('show');
        }
    });
}

function check_number(id){
    var price = parseFloat($(id).val(  ).replace(',',''));
    if(isNaN(price)){
        price = 0.0;
    }
    $(id).val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
}
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Price List</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายการราคาสินค้า / Product price list
                    </div>
                    <div class="col-md-4">
                    <?php if( $license_sale_employee_page == "High" ){ ?> 
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" id="excelfile" />
                            </div>
                            <div class="col-md-6" align="right">
                                <a class="btn btn-success " href="javascript:;" onclick="ExportToTable(this)" ><i class="fa fa-plus" aria-hidden="true"></i> Update price list</a>
                            </div>
                        </div> 
                        
                    <?PHP } ?>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=price_list">
                    <input type="hidden" name="app" value="price_list" />
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ผู้ขาย / Supplier </label>
                                <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($suppliers) ; $i++){
                                    ?>
                                    <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_th'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ลักษณะ / Category </label>
                                <select id="product_category_id" name="product_category_id" class="form-control select"  data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($product_category) ; $i++){
                                    ?>
                                    <option <?php if($product_category[$i]['product_category_id'] == $product_category_id){?> selected <?php }?> value="<?php echo $product_category[$i]['product_category_id'] ?>"><?php echo $product_category[$i]['product_category_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : - .</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ประเภท / Type </label>
                                <select id="product_type_id" name="product_type_id" class="form-control select"  data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($product_type) ; $i++){
                                    ?>
                                    <option <?php if($product_type[$i]['product_type_id'] == $product_type_id){?> selected <?php }?> value="<?php echo $product_type[$i]['product_type_id'] ?>"><?php echo $product_type[$i]['product_type_name'] ?> </option>
                                    <?
                                    }
                                    ?>
                                </select>
                                <p class="help-block">Example : - .</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>คำค้น <font color="#F00"><b>*</b></font></label>
                                <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                <p class="help-block">Example : T001.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" style="float:right; margin:0px 4px;" type="submit">Search</button>
                            <a href="index.php?app=price_list" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="dataTables-example_filter" class="dataTables_filter">
                            
                        </div>
                    </div>
                </div>

                    <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($product),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=price_list&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=price_list&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=price_list&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=price_list&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=price_list&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=price_list&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  >
                            <thead>
                                <tr>
                                    <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="10">No.</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัสสินค้า" width="400"> Product Code</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อสินค้า" width="600"> Product Name</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รายละเอียด" width="400">  Description</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="พิเศษ" width="10">   Premium</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ตัวแทน" width="10">   Dealer</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้จำหน่าย" width="10">   Agent/Trade</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="องค์กร" width="10">   KA</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ใหญ่" width="10">   Big</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="กลาง" width="10">   Medium</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เล็ก" width="10">   Small</th>
                                      <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="" width="10"></th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                for($i=$page * $page_size ; $i < count($product) && $i < $page * $page_size + $page_size; $i++){
                                ?>

                                <tr class="odd gradeX">
                                    <td class="text-center"><?php echo $i+1; ?></td>
                                    <td><?php echo $product[$i]['product_code']; ?></td>
                                    <td><?php echo $product[$i]['product_name']; ?></td>
                                    <td align="left"><?php echo $product[$i]['product_description']; ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_1'],2); ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_2'],2); ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_3'],2); ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_4'],2); ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_5'],2); ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_6'],2); ?></td>
                                    <td align="right"><?php echo number_format($product[$i]['product_price_7'],2); ?></td>
                                    <td>
                                    <?
                                        if($product[$i]['product_drawing'] != ""){
                                    ?>
                                        <a href="../upload/product/<?php echo $product[$i]['product_drawing'];?>" target="_blank">
                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        </a> 
                                    <?
                                        }
                                    ?>  

                                    <?PHP if($license_sale_employee_page == "High"){ ?>
                                        <a href="javascript:;" onclick="show_update('<?PHP echo $product[$i]['product_id']; ?>')"  title="แก้ไขราคาสินค้า">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                    <?PHP } ?>
                                    </td>
                                </tr>
                            <?
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($product),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=price_list&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=price_list&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=price_list&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=price_list&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=price_list&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=price_list&page=<?PHP echo $page + 2; }?>" >Next</a>
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
            

<form role="form" method="post"   action="index.php?app=price_list&action=update"   enctype="multipart/form-data">
                
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
                            <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อสินค้า" width="100">Name</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="พิเศษ" width="10">  Premium</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ตัวแทนลำดับ" width="10">  Dealer</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้จำหน่าย" width="10"> Agent/Trade</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="องค์กร" width="10">  KA</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ใหญ่" width="10">  Big</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="กลาง" width="10">  Medium</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เล็ก" width="10">  Small</th>
                              <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลบ" width="10"> Delete</th>
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
                <button type="summit" class="btn btn-primary" > Update price list </button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>

<form role="form" method="post"   action="index.php?app=price_list&action=update-single"   enctype="multipart/form-data">
                
    <div id="modalUpdate" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">แก้ไขข้อมูลราคาสินค้า / Change product price list.</h4>
                </div>

                <div  class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>สินค้า <font color="#F00"><b>*</b></font></label>
                                <div clas="row">
                                    <div class="col-md-6">
                                        <input type="hidden" id="product_id" name="product_id"  value="" />
                                        <input type="text" id="product_code" name="product_code" class="form-control" value="" readonly/>
                                    </div>
                                    <div class="col-md-6">
                                        <input  id="product_name" name="product_name" class="form-control" value="" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table width="100%"  class="table table-striped table-bordered table-hover" >
                        <tbody id="bodyAdd">
                            <tr>
                                <td>
                                พิเศษ / Premium	
                                </td>
                                <td>
                                    <input id="product_price_1" name="product_price_1" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                ตัวแทน / Dealer
                                </td>
                                <td>
                                    <input id="product_price_2" name="product_price_2" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                ผู้จำหน่าย / Agent/Trade		
                                </td>
                                <td>
                                    <input id="product_price_3" name="product_price_3" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                องค์กร / KA
                                </td>
                                <td>
                                    <input id="product_price_4" name="product_price_4" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                ใหญ่ / Big
                                </td>
                                <td>
                                    <input id="product_price_5" name="product_price_5" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                กลาง / Medium
                                </td>
                                <td>
                                    <input id="product_price_6" name="product_price_6" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                เล็ก / Small
                                </td>
                                <td>
                                    <input id="product_price_7" name="product_price_7" style="text-align:right;" class="form-control" value="" onchange="check_number(this)"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
                    <button type="summit" class="btn btn-primary" > Update price list </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>
            
            
