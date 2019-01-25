<script>

	var options = {
        url: function(keyword) {
            return "controllers/getProductByKeyword.php?keyword="+keyword;
        },

        getValue: function(element) {
            return element.product_code ;
        },

        template: {
            type: "description",
            fields: {
                description: "product_name"
            }
        },
        
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.keyword = $(".example-ajax-post:focus").val();
            return data;
        },

        requestDelay: 400
    };

     function check(){

       var quotation_code = document.getElementById("quotation_code").value; 
        var employee_id = document.getElementById("employee_id").value; 
        var customer_id = document.getElementById("customer_id").value; 

        
        quotation_code = $.trim(quotation_code); 
        employee_id = $.trim(employee_id); 
        customer_id = $.trim(customer_id); 
        

        if(quotation_code.length == 0){
            alert("Please input Quotation code");
            document.getElementById("quotation_code").focus();
            return false;
        }else if(customer_id.length == 0){
            alert("Please input customer");
            document.getElementById("customer_id").focus();
            return false;
        }else if(employee_id.length == 0){
            alert("Please input employee");
            document.getElementById("employee_id").focus();
            return false;
        }else{
            return true;
        }
     }
     function update_line(){
        var td_number = $('table[name="tb_list"]').children('tbody').children('tr').children('td:first-child');
        for(var i = 0; i < td_number.length ;i++){
            td_number[i].innerHTML = (i+1);
        }
    }
     function delete_row(id){
        $(id).closest('tr').remove();
        update_line();
     }


    function show_data(id){
        var product_code = $(id).val();
        $.post( "controllers/getProductByCode.php", { 'product_code': $.trim(product_code)}, function( data ) {
            if(data != null){
                $(id).closest('tr').children('td').children('input[name="product_name[]"]').val(data.product_name)
                $(id).closest('tr').children('td').children('input[name="product_id[]"]').val(data.product_id)

                if(customer_type_id == 4){
                    $(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val(data.product_price_1);
                }else if(customer_type_id == 3){
                    $(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val(data.product_price_2);
                }else if(customer_type_id == 2){
                    $(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val(data.product_price_3);
                }else if(customer_type_id == 1){
                    $(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val(data.product_price_4);
                }else{
                    $(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val(data.product_price_5);
                }

                update_sum(id);
                
            }
        });
        
     }
     
     function add_row(id){
         var index = 0;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
         $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td class="sorter">'+
                '</td>'+
                '<td>'+
                    '<input type="hidden" name="quotation_list_id[]" value="0" />'+
                    '<input type="hidden" name="product_id[]" value="0" />'+
                    '<input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" />'+ 
                '</td>'+
                '<td>'+
                    '<input type="text" class="form-control"  name="product_name[]" readonly />'+
                    '<div>หมายเหตุ</div>'+
                    '<input type="text" class="form-control"  name="quotation_list_remark[]" />'+
                '</td>'+
                '<td  style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="quotation_list_qty[]" value="1" /></td>'+
                '<td  style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="quotation_list_price[]" value="0.00" /></td>'+
                '<td  style="max-width:120px;"><input type="text" class="form-control" style="text-align:right;" name="quotation_list_sum[]" value="0.00" readonly /></td>'+
                '<td width="100px"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="quotation_list_discount[]" value="0.00" /></td>'+
                '<td width="80px">'+
                    '<select class="form-control" onchange="update_sum(this)" name="quotation_list_discount_type[]">'+
                        '<option value="0"  SELECTED >%</option>'+
                        '<option value="1">-</option>'+
                    '</select>'+
                '</td>'+
                '<td  style="max-width:120px;"><input type="text" class="form-control" name="quotation_list_total[]" style="text-align:right;" value="0.00" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(".example-ajax-post").easyAutocomplete(options);
        update_line();
    }

    function get_customer_detail(){
        var customer_id = parseInt(document.getElementById('customer_id').value);
            if(customer_id > 0){
                $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
                document.getElementById('customer_code').value = data.customer_code;
                document.getElementById('customer_name').value = data.customer_name_en ;
                document.getElementById('customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
                document.getElementById('customer_tax').value = data.customer_tax ;
                customer_type_id =  data.customer_type_id;
                getNewCode();
            });
        }
        
    }

    var customer_type_id = 0;

    function update_sum(id){

        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="quotation_list_qty[]"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val( ).replace(',',''));
        var sum =  parseFloat($(id).closest('tr').children('td').children('input[name="quotation_list_sum[]"]').val( ).replace(',',''));
        var discount =  parseFloat($(id).closest('tr').children('td').children('input[name="quotation_list_discount[]"]').val( ).replace(',',''));
        var discount_type =  parseFloat($(id).closest('tr').children('td').children('select[name="quotation_list_discount_type[]"]').val( ).replace(',',''));
        var total =  parseFloat($(id).closest('tr').children('td').children('input[name="quotation_list_total[]"]').val( ).replace(',',''));

        if(isNaN(qty)){
            qty = 0;
        }

        if(isNaN(price)){
            price = 0.0;
        }

        if(isNaN(sum)){
            sum = 0.0;
        }

        if(isNaN(discount)){
            discount = 0.0;
        }

        if(isNaN(discount_type)){
            discount_type = 0.0;
        }

        if(isNaN(total)){
            total = 0.0;
        }

        sum = qty*price;
        
        if(discount_type == 0){
            total = sum - sum * (discount / 100);
        }else{
            total = sum - discount ;
        }
        

        $(id).closest('tr').children('td').children('input[name="quotation_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="quotation_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="quotation_list_sum[]"]').val( sum.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="quotation_list_discount[]"]').val( discount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="quotation_list_total[]"]').val( total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();


    }

    function calculateAll(){

        var val = document.getElementsByName('quotation_list_total[]');
        var total = 0.0;

        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#quotation_total').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        $('#quotation_vat_price').val((total * ($('#quotation_vat').val()/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#quotation_vat_net').val((total * ($('#quotation_vat').val()/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        
    }

    function getNewCode(){
        var customer_id = document.getElementById('customer_id').value;
        var employee_id = document.getElementById('emp_id').value; 

        document.getElementById('employee_id').value = document.getElementById('emp_id').value; 
        $.post( "controllers/getQuotationCodeIndex.php", { 'customer_id': customer_id,'employee_id':employee_id }, function( data ) {
            document.getElementById('quotation_code').value = data;
        });

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Quotation Management</h1>
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
            เพิ่มใบเสนอราคาสินค้า / Add Quotation 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=quotation&action=add" >
                <input type="hidden"  id="quotation_id" name="quotation_id" value="<?php echo $quotation_id; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ซื้อ / Customer Code <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" class="form-control" value="<? echo $customer['customer_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ผู้ซื้อ / Customer  <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_id" name="customer_id" class="form-control select" onchange="get_customer_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($customers) ; $i++){
                                            ?>
                                            <option <?php if($customers[$i]['customer_id'] == $quotation['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?>  </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax <font color="#F00"><b>*</b></font></label>
                                        <input  id="customer_tax" name="customer_tax" class="form-control" value="<?php echo $customer['customer_tax'];?>" >
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามใบเสนอราคา / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="customer_name" name="customer_name" class="form-control" value="<?php echo $customer['customer_name_en'];?>  " >
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="customer_address" name="customer_address" class="form-control" rows="5" ><?php echo $customer['customer_address_1'] ."\n". $customer['customer_address_2'] ."\n". $customer['customer_address_3'];?></textarea >
                                        <p class="help-block">Example : 271/55 .</p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ชื่อผู้ติดต่อ / Contact name <font color="#F00"><b>*</b></font></label>
                                        <input  id="quotation_contact_name" name="quotation_contact_name" class="form-control" value="<?php echo $quotation['quotation_contact_name'];?>" >
                                        <p class="help-block">Example : ศุภชัย ลิ้มภัครดี.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>เบอร์โทรผู้ติดต่อ / Contact telephone <font color="#F00"><b>*</b></font></label>
                                        <input  id="quotation_contact_tel" name="quotation_contact_tel" class="form-control" value="<?php echo $quotation['quotation_contact_tel'];?>" >
                                        <p class="help-block">Example : 0610243003.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>อีเมล์ผู้ติดต่อ / Contact email <font color="#F00"><b>*</b></font></label>
                                        <input  id="quotation_contact_email" name="quotation_contact_email" class="form-control" value="<?php echo $quotation['quotation_contact_email'];?>" >
                                        <p class="help-block">Example : thana.t@revelsoft.co.th.</p>
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
                                        <label>วันที่ออกใบเสนอราคา / Quotation Date</label>
                                        <input type="text" id="quotation_date" name="quotation_date" value="<?PHP echo $first_date;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบเสนอราคา / Quotation code <font color="#F00"><b>*</b></font></label>
                                        <input id="quotation_code" name="quotation_code" class="form-control" value="<?PHP echo $last_code;?>" readonly>
                                        <p class="help-block">Example : INV1801001.</p>
                                    </div>
                                </div>                              

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>พนักงานขาย / Sale  <font color="#F00"><b>*</b></font> </label>
                                        <input id="employee_id" type="hidden" name="employee_id" value="<? echo $user["user_id"];?>" />
                                        <select id="emp_id" class="form-control select" data-live-search="true" onchange="getNewCode();">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($users) ; $i++){
                                            ?>
                                            <option value="<?php echo $users[$i]['user_id'] ?>" <?PHP if( $users[$i]['user_id'] == $user_id){ ?> SELECTED <?PHP }?> ><?php echo $users[$i]['name'] ?> (<?php echo $users[$i]['user_position_name'] ?>)</option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : Thana Tepchuleepornsil.</p>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark  <font color="#F00"><b>*</b></font> </label>
                                        <textarea id="quotation_remark" name="quotation_remark" class="form-control" ><?PHP echo $quotation['quotation_remark'];?></textarea>
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    Our reference :
                    </div>

                    <table name="tb_list" width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="60">ลำดับ </th>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า/หมายเหตุ<br>(Product Name/Remark)</th>
                                <th style="text-align:center;max-width:100px;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;max-width:100px;">ราคาต่อชิ้น<br>(Price)</th>
                                <th style="text-align:center;max-width:120px;">ราคารวม<br>(Total price)</th>
                                <th style="text-align:center;" colspan="2">ส่วนลด<br>(Discount)</th>
                                <th style="text-align:center;max-width:120px;">ราคาสุทธิ<br>(Net price)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="sorted_table">
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($quotation_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td class="sorter">
                                    <?PHP echo ($i + 1); ?>.
                                </td>
                                <td>
                                    <input type="hidden" class="form-control" name="quotation_list_id[]" value="<?php echo $quotation_lists[$i]['quotation_list_id']; ?>" /> 
                                    <input type="hidden" name="product_id[]" class="form-control" value="<?php echo $quotation_lists[$i]['product_id']; ?>" />
                                    <input class="example-ajax-post form-control" name="product_code[]" onchange="show_data(this);" placeholder="Product Code" value="<?php echo $quotation_lists[$i]['product_code']; ?>"  readonly/>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="product_name[]" readonly value="<?php echo $quotation_lists[$i]['product_name']; ?>" />
                                    <div>หมายเหตุ.</div>
                                    <input type="text" class="form-control" name="quotation_list_remark[]" value="<?php echo $quotation_lists[$i]['quotation_list_remark']; ?>" />
                                </td>
                                <td style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="quotation_list_qty[]" value="<?php echo $quotation_lists[$i]['quotation_list_qty']; ?>" /></td>
                                <td style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="quotation_list_price[]" value="<?php echo $quotation_lists[$i]['quotation_list_price']; ?>" /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="quotation_list_sum[]" value="<?php echo $quotation_lists[$i]['quotation_list_sum']; ?>" readonly /></td>
                                <td width="100px"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="quotation_list_discount[]" value="<?php echo $quotation_lists[$i]['quotation_list_discount']; ?>" /></td>
                                <td width="80px">
                                    <select class="form-control" onchange="update_sum(this)" name="quotation_list_discount_type[]">
                                        <option value="0" <?PHP if($quotation_lists[$i]['quotation_list_discount_type'] == 0){?> SELECTED <?PHP } ?> >%</option>
                                        <option value="1" <?PHP if($quotation_lists[$i]['quotation_list_discount_type'] == 1){?> SELECTED <?PHP } ?> >-</option>
                                    </select>
                                </td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="quotation_list_total[]" value="<?php echo $quotation_lists[$i]['quotation_list_total']; ?>" readonly /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $quotation_lists[$i]['quotation_list_total'];
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="10" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มสินค้า / Add product</span>
                                    </a>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="5" rowspan="3">
                                    
                                </td>
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="quotation_total" name="quotation_total" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="quotation_vat" name="quotation_vat" value="<?PHP echo $vat;?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="quotation_vat_price"  name="quotation_vat_price" value="<?PHP echo number_format(($vat/100) * $total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="quotation_vat_net" name="quotation_vat_net" value="<?PHP echo number_format(($vat/100) * $total + $total,2) ;?>" readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=quotation" class="btn btn-default">Back</a>
                        
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button  type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
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

<script>
    $(".example-ajax-post").easyAutocomplete(options);
    $('.sorted_table').sortable({
        handle: ".sorter" , 
        update: function( event, ui ) {
            update_line(); 
        }
    });
</script>