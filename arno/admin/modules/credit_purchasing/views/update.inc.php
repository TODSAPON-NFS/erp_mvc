<script>

    var stock_group_data = [
    <?php for($i = 0 ; $i < count($stock_groups) ; $i++ ){?>
        {
            stock_group_id:'<?php echo $stock_groups[$i]['stock_group_id'];?>', 
            stock_group_name:'<?php echo $stock_groups[$i]['stock_group_name'];?>'
        },
    <?php }?>
    ];

     function check(){

        var credit_purchasing_code = document.getElementById("credit_purchasing_code").value;
        var credit_purchasing_date = document.getElementById("credit_purchasing_date").value;

        
        credit_purchasing_code = $.trim(credit_purchasing_code);
        credit_purchasing_date = $.trim(credit_purchasing_date);

        if(credit_purchasing_code.length == 0){
            alert("Please input Credit Purchasing code");
            document.getElementById("credit_purchasing_code").focus();
            return false;
        }else if(credit_purchasing_date.length == 0){
            alert("Please input Credit Purchasing date");
            document.getElementById("credit_purchasing_date").focus();
            return false;
        }else{
            return true;
        }
     }

     function delete_row(id){
        $(id).closest('tr').remove();
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
                '<td>'+
                    '<input type="hidden" name="credit_purchasing_list_id[]" value="0" />'+ 
                    '<span>Code : </span>'+
                    '<input type="text" class="form-control"  name="credit_purchasing_list_code[]"  />'+
                    '<span>Name : </span>'+
                    '<input type="text" class="form-control"  name="credit_purchasing_list_name[]"  />'+
                '</td>'+
                '<td>'+
                    '<select class="form-control select" name="stock_group_id[]"  data-live-search="true" ></select>'+
                '</td>'+ 
                '<td  style="max-width:80px;">' +
                    '<input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="credit_purchasing_list_qty[]" value="1" />'+
                '</td>'+
                '<td  style="max-width:80px;">' +
                    '<input type="text" class="form-control" style="text-align:right;" name="credit_purchasing_list_unit[]" value="pcs" />'+
                '</td>'+
                '<td  style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="credit_purchasing_list_price[]" value="0.00" /></td>'+ 
                '<td width="100px"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="credit_purchasing_list_discount[]" value="0.00" /></td>'+
                '<td width="80px">'+
                    '<select class="form-control" onchange="update_sum(this)" name="credit_purchasing_list_discount_type[]">'+
                        '<option value="0"  SELECTED >%</option>'+
                        '<option value="1">-</option>'+
                    '</select>'+
                '</td>'+
                '<td  style="max-width:120px;"><input type="text" class="form-control" name="credit_purchasing_list_total[]" style="text-align:right;" value="0.00" readonly /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_select[]"]').empty();
        var str = "<option value=''>Select Product</option>";
        $.each(stock_group_data, function (index, value) {
            str += "<option value='" + value['stock_group_id'] + "'>"+value['stock_group_name']+"</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_select[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="stock_group_select[]"]').selectpicker();
    }

    function get_supplier_detail(){
        var supplier_id = parseInt(document.getElementById('supplier_id').value);
            if(supplier_id > 0){
                $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
                document.getElementById('supplier_code').value = data.supplier_code;
                document.getElementById('supplier_name').value = data.supplier_name_en +' (' + data.supplier_name_th +')';
                document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
                document.getElementById('supplier_tax').value = data.supplier_tax ;
            });
        }
        
    }

    function update_sum(id){

        var qty =  parseFloat($(id).closest('tr').children('td').children('input[name="credit_purchasing_list_qty[]"]').val(  ).replace(',',''));
        var price =  parseFloat($(id).closest('tr').children('td').children('input[name="credit_purchasing_list_price[]"]').val( ).replace(',',''));
        var sum =  0.0;
        var discount =  parseFloat($(id).closest('tr').children('td').children('input[name="credit_purchasing_list_discount[]"]').val( ).replace(',',''));
        var discount_type =  parseFloat($(id).closest('tr').children('td').children('select[name="credit_purchasing_list_discount_type[]"]').val( ).replace(',',''));
        var total =  parseFloat($(id).closest('tr').children('td').children('input[name="credit_purchasing_list_total[]"]').val( ).replace(',',''));

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
        

        $(id).closest('tr').children('td').children('input[name="credit_purchasing_list_qty[]"]').val( qty.toFixed(0).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="credit_purchasing_list_price[]"]').val( price.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ); 
        $(id).closest('tr').children('td').children('input[name="credit_purchasing_list_discount[]"]').val( discount.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $(id).closest('tr').children('td').children('input[name="credit_purchasing_list_total[]"]').val( total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        calculateAll();


    }

    function calculateAll(){

        var val = document.getElementsByName('credit_purchasing_list_total[]');
        var total = 0.0;
        var vat = parseFloat($('#credit_purchasing_vat').val().toString().replace(new RegExp(',', 'g'),''));
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#credit_purchasing_total').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

       

        if( parseInt($('#credit_purchasing_vat_type').val()) == 2){
            $('#credit_purchasing_vat_value').val((total * (vat/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#credit_purchasing_net').val((total * (vat/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        
            
        } else if (parseInt($('#credit_purchasing_vat_type').val()) == 1) {

            $('#credit_purchasing_total').val( (total * (100/(vat+100.0) ) ).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#credit_purchasing_vat_value').val(  (total - total * (100/(vat+100.0) ) ).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#credit_purchasing_net').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        }else{
            $('#credit_purchasing_vat').val( (0).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#credit_purchasing_vat_value').val((0).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#credit_purchasing_net').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        }
    }

    function generate_credit_date(){
        var day = parseInt($('#credit_purchasing_credit_day').val());
        var date = $('#credit_purchasing_date').val();

        var current_date = new Date();
        var tomorrow = new Date();

        if(isNaN(day)){
            $('#credit_purchasing_credit_day').val(0);
            day = 0;
        }else if (date == ""){
            $('#credit_purchasing_credit_day').val(("0" + current_date.getDate() ) .slice(-2) + '-' + ("0" + current_date.getMonth() + 1).slice(-2) + '-' + current_date.getFullYear());
        }

        tomorrow.setDate(current_date.getDate()+day);
        $('#credit_purchasing_credit_date').val(("0" + tomorrow.getDate() ) .slice(-2) + '-' + ("0" + (tomorrow.getMonth()+1) ).slice(-2) + '-' + tomorrow.getFullYear());
        

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Credit Purchasing Management</h1>
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
            แก้ไขซื้อเงินเชื่อ / Edit Credit Purchasing 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=credit_purchasing&action=edit&id=<?PHP echo $credit_purchasing_id; ?>" >
                <input type="hidden"  id="credit_purchasing_id" name="credit_purchasing_id" value="<?php echo $credit_purchasing_id; ?>" />
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                        <p class="help-block">Example : A0001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                        <select id="supplier_id" name="supplier_id" class="form-control select" onchange="get_supplier_detail()" data-live-search="true">
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($suppliers) ; $i++){
                                            ?>
                                            <option <?php if($suppliers[$i]['supplier_id'] == $credit_purchasing['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
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
                                        <input  id="supplier_tax" name="supplier_tax" class="form-control" value="<?php echo $supplier['supplier_tax'];?>" readonly>
                                        <p class="help-block">Example : 0305559003597.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>ชื่อตามซื้อเงินเชื่อ / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="supplier_name" name="supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> (<?php echo $supplier['supplier_name_th'];?>)" readonly>
                                        <p class="help-block">Example : Revel soft.</p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ตามใบกำภาษี / Address <font color="#F00"><b>*</b></font></label>
                                        <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><?php echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                        <p class="help-block">Example : 271/55 .</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกซื้อเงินเชื่อ / Credit Purchasing Date</label>
                                        <input type="text" id="credit_purchasing_date" name="credit_purchasing_date" value="<?PHP echo $credit_purchasing['credit_purchasing_date'];?>"  class="form-control calendar"  onchange="generate_credit_date();" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขซื้อเงินเชื่อ / Credit Purchasing code <font color="#F00"><b>*</b></font></label>
                                        <input id="credit_purchasing_code" name="credit_purchasing_code" class="form-control" value="<?PHP echo $credit_purchasing['credit_purchasing_code'];?>" readonly>
                                        <p class="help-block">Example : RR1801001.</p>
                                    </div>
                                </div>     

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ใบสั่งซื้อ / Purchase Order  <font color="#F00"><b>*</b></font> </label>
                                        <select id="purchase_order_id" name="purchase_order_id" class="form-control select" data-live-search="true" >
                                            <option value="">Select</option>
                                            <?php 
                                            for($i =  0 ; $i < count($purchase_orders) ; $i++){
                                            ?>
                                            <option value="<?php echo $purchase_orders[$i]['purchase_order_id'] ?>"  <?PHP if( $purchase_orders[$i]['purchase_order_id'] == $credit_purchasing['purchase_order_id']){ ?> SELECTED <?PHP }?> ><?php echo $purchase_orders[$i]['purchase_order_code'] ?> </option>
                                            <?
                                            }
                                            ?>
                                        </select>
                                        <p class="help-block">Example : PO1802001.</p>
                                    </div>
                                </div>               

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                                        <select id="credit_purchasing_vat_type" name="credit_purchasing_vat_type"  class="form-control" onchange="calculateAll();">
                                            <option value="0" <?PHP if($credit_purchasing['credit_purchasing_vat_type'] == '0'){?>Selected <?PHP }?> >0 - ไม่มี Vat</option>
                                            <option value="1"  <?PHP if($credit_purchasing['credit_purchasing_vat_type'] == '1'){?>Selected <?PHP }?> >1 - รวม Vat</option>
                                            <option value="2"  <?PHP if($credit_purchasing['credit_purchasing_vat_type'] == '2'){?>Selected <?PHP }?> >2 - แยก Vat</option>
                                        </select>
                                        <p class="help-block">Example : 0 - ไม่มี vat.</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>เครดิต (วัน) / Credit (Day) </label>
                                        <input id="credit_purchasing_credit_day" name="credit_purchasing_credit_day" class="form-control" value="<?PHP echo $credit_purchasing['credit_purchasing_credit_day'];?>" onchange="generate_credit_date();">
                                        <p class="help-block">Example : 30 วัน.</p>
                                    </div>
                                </div>     

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ครบกำหนด / Credit Date</label>
                                        <input type="text" id="credit_purchasing_credit_date" name="credit_purchasing_credit_date" value="<?PHP echo $credit_purchasing['credit_purchasing_credit_date'];?>"  class="form-control" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>     

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ขนส่งโดย / Delivery By </label>
                                        <input id="credit_purchasing_delivery_by" name="credit_purchasing_delivery_by" class="form-control" value="<?PHP echo $credit_purchasing['credit_purchasing_delivery_by'];?>" />
                                        <p class="help-block">Example : DHL.</p>
                                    </div>
                                </div>    

                               

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark  <font color="#F00"><b>*</b></font> </label>
                                        <textarea id="credit_purchasing_remark" name="credit_purchasing_remark" class="form-control" ><?PHP echo $credit_purchasing['credit_purchasing_remark'];?></textarea>
                                        <p class="help-block">Example : -.</p>
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
                                <th style="text-align:center;">รหัสสินค้า / ชื่อสินค้า <br>(Product Code / Product Name)</th>
                                <th style="text-align:center;">คลังสินค้า<br>(Stcok)</th>
                                <th style="text-align:center;max-width:80px;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;max-width:80px;">หน่วย<br>(Unit)</th>
                                <th style="text-align:center;max-width:120px;">ราคาต่อหน่วย<br>(Price/Unit)</th>
                                <th style="text-align:center;" colspan="2">ส่วนลด<br>(Discount)</th>
                                <th style="text-align:center;max-width:120px;">ราคาสุทธิ<br>(Net price)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($credit_purchasing_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="credit_purchasing_list_id[]" value="<?php echo $credit_purchasing_lists[$i]['credit_purchasing_list_id']; ?>" />
                                    <div>Code : </div>
                                    <input type="text" class="form-control" name="credit_purchasing_list_code[]" value="<?php echo $credit_purchasing_lists[$i]['credit_purchasing_list_code']; ?>" />
                                    <div>Name : </div>
                                    <input type="text" class="form-control" name="credit_purchasing_list_name[]" value="<?php echo $credit_purchasing_lists[$i]['credit_purchasing_list_name']; ?>" />
                                </td>
                                <td>
                                    <input type="hidden" class="form-control"  value="<?php echo $credit_purchasing_lists[$i]['stock_group_id']; ?>" />
                                    <select  class="form-control select" name="stock_group_id[]"  data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($stock_groups) ; $ii++){
                                        ?>
                                        <option <?php if($stock_groups[$ii]['stock_group_id'] == $credit_purchasing_lists[$i]['stock_group_id']){?> selected <?php }?> value="<?php echo $stock_groups[$ii]['stock_group_id'] ?>"><?php echo $stock_groups[$ii]['stock_group_name'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                               
                                <td style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="credit_purchasing_list_qty[]" value="<?php echo number_format($credit_purchasing_lists[$i]['credit_purchasing_list_qty'],0); ?>" /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="credit_purchasing_list_unit[]" value="<?php echo $credit_purchasing_lists[$i]['credit_purchasing_list_unit']; ?>" /></td>
                                <td style="max-width:100px;"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="credit_purchasing_list_price[]" value="<?php echo number_format($credit_purchasing_lists[$i]['credit_purchasing_list_price'],2); ?>" /></td>
                                
                                <td width="100px"><input type="text" class="form-control" onchange="update_sum(this)" style="text-align:right;" name="credit_purchasing_list_discount[]" value="<?php echo number_format($credit_purchasing_lists[$i]['credit_purchasing_list_discount'],2); ?>" /></td>
                                <td width="80px">
                                    <select class="form-control" onchange="update_sum(this)" name="credit_purchasing_list_discount_type[]">
                                        <option value="0" <?PHP if($credit_purchasing_lists[$i]['credit_purchasing_list_discount_type'] == 0){?> SELECTED <?PHP } ?> >%</option>
                                        <option value="1" <?PHP if($credit_purchasing_lists[$i]['credit_purchasing_list_discount_type'] == 1){?> SELECTED <?PHP } ?> >-</option>
                                    </select>
                                </td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="credit_purchasing_list_total[]" value="<?php echo number_format($credit_purchasing_lists[$i]['credit_purchasing_list_total'],2); ?>" readonly /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $credit_purchasing_lists[$i]['credit_purchasing_list_total'];
                            }

                            if( $credit_purchasing['credit_purchasing_vat_type'] == 2){
                                $vat_price = ($vat/100) * $total;
                                $net = ($vat/100) * $total + $total;
                               
                            } else if ($credit_purchasing['credit_purchasing_vat_type'] == 1) {
                                $vat_price = $total - ((100/(100+$vat)) * $total);
                                $net = $total;
                                $total = (100/(100+$vat)) * $total;
                            }else{
                                $vat = 0;
                                $vat_price = 0;
                                $net = $total;
                            }
                            

                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="8" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มสินค้า / Add product</span>
                                    </a>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="4" rowspan="3">
                                    
                                </td>
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="credit_purchasing_total" name="credit_purchasing_total" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
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
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="credit_purchasing_vat" name="credit_purchasing_vat" value="<?PHP echo $vat;?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="credit_purchasing_vat_value"  name="credit_purchasing_vat_value" value="<?PHP echo number_format($vat_price,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td colspan="3" align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="credit_purchasing_net" name="credit_purchasing_net" value="<?PHP echo number_format($net,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=credit_purchasing" class="btn btn-default">Back</a>
                        
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