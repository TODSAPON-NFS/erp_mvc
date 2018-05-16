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

        var quotation_code = document.getElementById("quotation_code").value;
        var quotation_type = document.getElementById("quotation_type").value;
        var employee_id = document.getElementById("employee_id").value;
        var urgent_time = document.getElementById("urgent_time").value;
        var urgent_status = document.getElementById("urgent_status").value;

        
        quotation_code = $.trim(quotation_code);
        quotation_type = $.trim(quotation_type);
        employee_id = $.trim(employee_id);
        urgent_time = $.trim(urgent_time);
        urgent_status = $.trim(urgent_status);
        

        if(quotation_code.length == 0){
            alert("Please input Quotation code");
            document.getElementById("quotation_code").focus();
            return false;
        }else if(quotation_type.length == 0){
            alert("Please input Quotation type");
            document.getElementById("quotation_type").focus();
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
        var product_name = "";
        var data = product_data.filter(val => val['product_id'] == $(id).val());
        if(data.length > 0){
            $(id).closest('tr').children('td').children('input[name="product_name[]"]').val( data[0]['product_name'] );
            $(id).closest('tr').children('td').children('input[name="product_id[]"]').val( $(id).val() );
        }
        
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
                    '<input type="hidden" name="quotation_list_id[]" value="0" />'+
                    '<input type="hidden" name="product_id[]" value="0" />'+
                    '<select class="form-control select" name="product_select[]"  onchange="show_data(this);" data-live-search="true" ></select>'+
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

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="product_select[]"]').empty();
        var str = "<option value=''>Select Product</option>";
        $.each(product_data, function (index, value) {
            str += "<option value='" + value['product_id'] + "'>"+value['product_code']+"</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="product_select[]"]').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select[name="product_select[]"]').selectpicker();
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="m_quotation_list_delivery[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
    }

    function get_customer_detail(){
        var customer_id = parseInt(document.getElementById('customer_id').value);
            if(customer_id > 0){
                $.post( "controllers/getCustomerByID.php", { 'customer_id': customer_id }, function( data ) {
                document.getElementById('customer_code').value = data.customer_code;
                document.getElementById('customer_name').value = data.customer_name_en +' (' + data.customer_name_th +')';
                document.getElementById('customer_address').value = data.customer_address_1 +'\n' + data.customer_address_2 +'\n' +data.customer_address_3;
                document.getElementById('customer_tax').value = data.customer_tax ;

                getNewCode();
            });
        }
        
    }

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
        var customer_code = document.getElementById('customer_code').value;
        var emp = $("#emp_id option:selected").text().substring(0, 2).toUpperCase();
        var first_code = customer_code+"-"+emp+"-<?PHP echo date("y").date("m"); ?>"; 
        document.getElementById('employee_id').value = document.getElementById('emp_id').value; 
        $.post( "controllers/getQuotationCodeIndex.php", { 'first_code': first_code }, function( data ) {
            document.getElementById('quotation_code').value = data;
        });

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Job Management</h1>
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
            เพิ่มงานจัดการต้นทุน / Add Job 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=quotation&action=add" >
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
                                            <option <?php if($customers[$i]['customer_id'] == $quotation['customer_id']){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
                                        <label>ชื่อตามงานจัดการต้นทุน / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="customer_name" name="customer_name" class="form-control" value="<?php echo $customer['customer_name_en'];?> (<?php echo $customer['customer_name_th'];?>)" >
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
                            </div>
                        </div>
                        <div class="col-lg-1">
                        </div>
                        <div class="col-lg-5">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขงานจัดการต้นทุน / Job code <font color="#F00"><b>*</b></font></label>
                                        <input id="job_code" name="job_code" class="form-control" value="<?PHP echo $last_code;?>" readonly>
                                        <p class="help-block">Example : JOB1801001.</p>
                                    </div>
                                </div>    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ชื่องานงานจัดการต้นทุน / Job name <font color="#F00"><b>*</b></font></label>
                                        <input id="job_name" name="job_name" class="form-control" value="<?PHP echo $last_code;?>" >
                                        <p class="help-block">Example : Collar.</p>
                                    </div>
                                </div>     
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ราคาต่อชิ้น / Price/Piece <font color="#F00"><b>*</b></font></label>
                                        <input id="job_price" name="job_price" class="form-control" value="<?PHP echo $last_code;?>" >
                                        <p class="help-block">Example : Collar.</p>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ต้นทุนต่อชิ้น / Cost/Piece <font color="#F00"><b>*</b></font></label>
                                        <input id="job_cost" name="job_cost" class="form-control" value="<?PHP echo $last_code;?>" >
                                        <p class="help-block">Example : Collar.</p>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ยอดผลิตต่อเดือน / Production/Month <font color="#F00"><b>*</b></font></label>
                                        <input id="job_production" name="job_production" class="form-control" value="<?PHP echo $last_code;?>" >
                                        <p class="help-block">Example : Collar.</p>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่เริ่มงาน / Start Date</label>
                                        <input type="text" id="job_start" name="job_start" value="<?PHP echo $first_date;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่สิ้นสุดงาน / End Date</label>
                                        <input type="text" id="job_end" name="job_end" value="<?PHP echo $first_date;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>                       

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark  <font color="#F00"><b>*</b></font> </label>
                                        <textarea id="job_remark" name="job_remark" class="form-control" ><?PHP echo $quotation['quotation_remark'];?></textarea>
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=quotation" class="btn btn-default">Back</a>
                        
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