<script>

    var account_data = [
    <?php for($i = 0 ; $i < count($accounts) ; $i++ ){?>
        {
            account_id:'<?php echo $accounts[$i]['account_id'];?>',
            account_code:'<?php echo $accounts[$i]['account_code'];?>',
            account_name_th:'<?php echo $accounts[$i]['account_name_th'];?>',
            account_name_en:'<?php echo $accounts[$i]['account_name_en'];?>'
        },
    <?php }?>
    ];

    var vat_id  ;

    function check(){


        var journal_cash_payment_code = document.getElementById("journal_cash_payment_code").value;
        var journal_cash_payment_date = document.getElementById("journal_cash_payment_date").value;
        var journal_cash_payment_name = document.getElementById("journal_cash_payment_name").value;
        
        var debit_total = parseFloat($('#journal_cash_payment_list_debit').val( ).toString().replace(new RegExp(',', 'g'),''));
        var credit_total = parseFloat($('#journal_cash_payment_list_credit').val( ).toString().replace(new RegExp(',', 'g'),''));

        journal_cash_payment_code = $.trim(journal_cash_payment_code);
        journal_cash_payment_date = $.trim(journal_cash_payment_date);
        journal_cash_payment_name = $.trim(journal_cash_payment_name);
        

        if(journal_cash_payment_code.length == 0){
            alert("Please input Journal Payment code");
            document.getElementById("journal_cash_payment_code").focus();
            return false;
        }else if(journal_cash_payment_date.length == 0){
            alert("Please input Journal Payment date");
            document.getElementById("journal_cash_payment_date").focus();
            return false;
        }else if(journal_cash_payment_name.length == 0){
            alert("Please input journal_cash_payment name");
            document.getElementById("journal_cash_payment_name").focus();
            return false;
        }else if (debit_total != credit_total){
            alert("Can not save data. \nBecause credit value and debit value not match. "); 
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
                    '<input type="hidden" name="journal_cash_payment_list_id[]" value="0" />'+     
                    '<select class="form-control select" type="text" name="account_id[]" onchange="show_data(this);" data-live-search="true" ></select>'+
                '</td>'+
                '<td><input type="text" class="form-control" name="journal_cash_payment_list_name[]" value="' + document.getElementById("journal_cash_payment_name").value + '" /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" onclick="show_vat(this);"  value="0" onchange="val_format(this);" name="journal_cash_payment_list_debit[]"  /></td>'+
                '<td align="right"><input type="text" class="form-control" style="text-align: right;" value="0" onchange="val_format(this);" name="journal_cash_payment_list_credit[]" /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').empty();
        var str = "<option value=''>Select account</option>";
        $.each(account_data, function (index, value) {
            str += "<option value='" + value['account_id'] + "'>["+value['account_code']+"] " +  value['account_name_th'] + "</option>";
        });
        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').html(str);

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('select').selectpicker();
    }


    function show_vat(id){ 
        vat_id = $(id);
        var account = $(id).closest('tr').children('td').children('div').children('select[name="account_id[]"] ').val(); 
        if(account == 40 ){
            $('#modalAdd').modal('show');
        }
    }


    function show_data(id){ 

        //ภาษีซื้อ
        if($(id).val() == 40){
            var account = $(id).closest('table').children('tbody').children('tr').children('td').children('select[name="account_id[]"] ');

            account = account.filter(word => word.value = 40);

            if(account.length > 1){
                alert("มีการระบุ [1154-00] ภาษีซื้อ ภายในเอกสารนี้แล้ว");
                $(id).val(0);
            }else{ 
                vat_id = $(id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]');
                $(id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').prop('readonly', true);
                $(id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').prop('readonly', true);
                $('#modalAdd').modal('show');
            }

        }else if($(id).val() == 241){
            var account = $(id).closest('table').children('tbody').children('tr').children('td').children('select[name="account_id[]"] ');

            account = account.filter(word => word.value = 241);

            if(account.length > 1){
                alert("มีการระบุ [5390-02] ภาษีซื้อขอคืนไม่ได้ ภายในเอกสารนี้แล้ว");
                $(id).val(0);
            }else{
                $('#modalAdd').modal('show');
            }

        }else{
            $(id).closest('tr').children('td').children('input[name="journal_cash_payment_list_debit[]"]').prop('readonly', false);
            $(id).closest('tr').children('td').children('input[name="journal_cash_payment_list_credit[]"]').prop('readonly', false);
        }
        
    }

    function update_vat(){
        var product_price =  parseFloat($("#product_price").val().replace(',',''));
        var product_vat =  parseFloat($("#product_vat").val().replace(',',''));

        if(isNaN(product_vat)){
            product_vat = 0.0;
        }
        
        if(isNaN(product_price)){
            product_price = 0.0;
            $("#product_vat").val(0.00);
        } else{
            $("#product_vat").val( (product_price * (7/100.0) ).toFixed(2) );
        }

    }

    function update_vat_non(){
        var product_price_non =  parseFloat($("#product_price_non").val().replace(',',''));
        var product_vat_non =  parseFloat($("#product_vat_non").val().replace(',',''));

        if(isNaN(product_vat_non)){
            product_vat_non = 0.0;
        }
        
        if(isNaN(product_price_non)){
            product_price_non = 0.0;
            $("#product_vat_non").val(0.00);
        } else{
            $("#product_vat_non").val( (product_price_non * (7/100.0) ).toFixed(2) );
        }
    }

    function set_vat (id){ 
        var account = $('select[name="account_id[]"] ');
        

        account = account.filter(word => word.value = 40);

        if(account.length > 0){
            console.log(vat_id);
            if(vat_id[0] == undefined){
                vat_id.value = $("#product_vat").val();
            }else{
                vat_id[0].value = $("#product_vat").val();
            }
            $('#modalAdd').modal('hide');
        }

    }

    function get_supplier_detail(){
        var supplier_id = document.getElementById('supplier_id').value;
        $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
            document.getElementById('supplier_code').value = data.supplier_code;
            document.getElementById('supplier_name').value = data.supplier_name;
            document.getElementById('supplier_tax').value = data.supplier_tax;
            document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
        });
    }

    function val_format(id){
        var val =  parseFloat($(id).val().replace(',',''));  
        if(isNaN(val)){
            val = 0;
        }
        $(id).val( val.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ); 
        calculateAll();
    }

    function calculateAll(){
        var debit = document.getElementsByName('journal_cash_payment_list_debit[]');
        var credit = document.getElementsByName('journal_cash_payment_list_credit[]');
        var debit_total = 0.0;
        var credit_total = 0.0;

        for(var i = 0 ; i < debit.length ; i++){
            
            debit_total += parseFloat(debit[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        for(var i = 0 ; i < credit.length ; i++){
            
            credit_total += parseFloat(credit[i].value.toString().replace(new RegExp(',', 'g'),''));
        } 

        $('#journal_cash_payment_list_debit').val((debit_total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        $('#journal_cash_payment_list_credit').val((credit_total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

</script>


<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal Payment  Management</h1>
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
            เพิ่มสมุดรายวันจ่ายเงิน /  Add Journal Payment   
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=journal_special_04&action=edit&id=<?PHP echo $journal_cash_payment_id; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขสมุดรายวันจ่ายเงิน / Journal Payment Code <font color="#F00"><b>*</b></font></label>
                                <input id="journal_cash_payment_code" name="journal_cash_payment_code" class="form-control" value="<?php echo $journal_cash_payment['journal_cash_payment_code'];?>" readonly>
                                <p class="help-block">Example : JG1801001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันจ่ายเงิน / Journal Payment Date</label>
                                <input type="text" id="journal_cash_payment_date" name="journal_cash_payment_date"  class="form-control calendar" value="<?php echo $journal_cash_payment['journal_cash_payment_date'];?>" readonly/>
                                <p class="help-block">31/01/2018</p>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>หัวข้อสมุดรายวันจ่ายเงิน / Journal Payment Name</label>
                                <input type="text" id="journal_cash_payment_name" name="journal_cash_payment_name"  class="form-control" value="<?php echo $journal_cash_payment['journal_cash_payment_name'];?>" />
                                <p class="help-block"></p>
                            </div>
                        </div>
    
                    </div>
                        
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">บัญชี<br>(Account)</th>
                                <th style="text-align:center;">รายละเอียด<br>(Description)</th>
                                <th style="text-align:center;">เดบิต<br>(Debit)</th>
                                <th style="text-align:center;">เครดิต<br>(Credit)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            $journal_cash_payment_list_debit = 0;
                            $journal_cash_payment_list_credit = 0;
                            for($i=0; $i < count($journal_cash_payment_lists); $i++){
                                $journal_cash_payment_list_debit += $journal_cash_payment_lists[$i]['journal_cash_payment_list_debit'];
                                $journal_cash_payment_list_credit += $journal_cash_payment_lists[$i]['journal_cash_payment_list_credit'];
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" name="journal_cash_payment_list_id[]" value="0" />
                                    <select  class="form-control select" name="account_id[]" onchange="show_data(this);" data-live-search="true" >
                                        <option value="">Select</option>
                                        <?php 
                                        for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                        ?>
                                        <option <?php if($accounts[$ii]['account_id'] == $journal_cash_payment_lists[$i]['account_id']){?> selected <?php }?> value="<?php echo $accounts[$ii]['account_id'] ?>"><?php echo $accounts[$ii]['account_code'] ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="right"><input type="text" class="form-control" name="journal_cash_payment_list_name[]" value="<?php echo $journal_cash_payment_lists[$i]['journal_cash_payment_list_name']; ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_cash_payment_list_debit[]" onclick="show_vat(this);" onchange="val_format(this);" value="<?php echo number_format($journal_cash_payment_lists[$i]['journal_cash_payment_list_debit'],2); ?>" /></td>
                                <td align="right"><input type="text" class="form-control" style="text-align: right;" name="journal_cash_payment_list_credit[]" onchange="val_format(this);" value="<?php echo number_format($journal_cash_payment_lists[$i]['journal_cash_payment_list_credit'],2); ?>" /></td>
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
                                <td colspan="2" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มบัญชี / Add account</span>
                                    </a>

                                    <!-- ****************************************************************************************************************** -->
                                    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg " role="document">
                                            <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">ป้อนรายละเอียดรายการภาษีซื้อ</h4>
                                            </div>

                                            <div  class="modal-body" align="left">
                                                <div class="row"> 
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>เลขที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                                                            <input id="invoice_code" name="invoice_code" class="form-control" value="<?php echo $journal_cash_payment_invoices['invoice_code']; ?>" >
                                                            <p class="help-block">Example : -.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>วันที่ใบกำกับภาษี <font color="#F00"><b>*</b></font> </label>
                                                            <input id="invoice_date" name="invoice_date" class="form-control calendar" value="<?php echo $journal_cash_payment_invoices['invoice_date']; ?>" readonly />
                                                            <p class="help-block">Example : -.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="form-group">
                                                            <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                                            <input id="supplier_code" name="supplier_code" class="form-control" value="<? echo $supplier['supplier_code'];?>" readonly>
                                                            <p class="help-block">Example : A0001.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
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
                                                            <input id="supplier_name" name="supplier_name" value="<?php echo $journal_cash_payment_invoices['supplier_name']; ?>" class="form-control" />
                                                            <p class="help-block">Example : Revel Soft (บริษัท เรเวลซอฟต์ จำกัด).</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label>เลขประจำตัวผู้เสียภาษี / Tax ID. <font color="#F00"><b>*</b></font></label>
                                                            <input id="supplier_tax" name="supplier_tax" class="form-control" readonly>
                                                            <p class="help-block">Example : Somchai Wongnai.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                                            <textarea  id="supplier_address" name="supplier_address" class="form-control" rows="5" readonly><? echo $supplier['supplier_address_1'] ."\n". $supplier['supplier_address_2'] ."\n". $supplier['supplier_address_3'];?></textarea >
                                                            <p class="help-block">Example : -.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row"> 
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>ยื่นภาษีรวมในงวด <font color="#F00"><b>*</b></font> </label>
                                                            <input id="vat_section" name="vat_section" class="form-control" value="<?php echo $journal_cash_payment_invoices['vat_section']; ?>" >
                                                            <p class="help-block">Example : 08/61.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>ยื่นเพิ่มเติม <font color="#F00"><b>*</b></font> </label>
                                                            <input id="vat_section_add" name="vat_section_add" class="form-control" value="<?php echo $journal_cash_payment_invoices['vat_section_add']; ?>" >
                                                            <p class="help-block">Example : -.</p>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>

                                            <table width="100%" class="table table-striped table-bordered table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th style="text-align:center;" colspan="2">ภาษีซื้อขอคืนได้</th>
                                                        <th style="text-align:center;" colspan="2">ภาษีซื้อขอคืนไม่ได้</th>
                                                        <th style="text-align:center;" rowspan="2">มูลค่าสินค้าหรือบริการอัตราศูนย์</th>  
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align:center;" >มูลค่าสินค้า</th>
                                                        <th style="text-align:center;" >จำนวนภาษี</th>
                                                        <th style="text-align:center;" >มูลค่าสินค้า</th>
                                                        <th style="text-align:center;" >จำนวนภาษี</th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <tr class="odd gradeX">
                                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_price" name="product_price" onchange="update_vat()" value="<?php echo $journal_cash_payment_invoices['product_price']; ?>" /></td>
                                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_vat" name="product_vat" readonly value="<?php echo $journal_cash_payment_invoices['product_vat']; ?>" /></td>
                                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_price_non" name="product_price_non" onchange="update_vat_non()" value="<?php echo $journal_cash_payment_invoices['product_price_non']; ?>" /></td>
                                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_vat_non" name="product_vat_non" readonly  value="<?php echo $journal_cash_payment_invoices['product_vat_non']; ?>" /></td>
                                                        <td align="right"><input type="text" class="form-control" style="text-align: right;" id="product_non"  name="product_non"  value="<?php echo $journal_cash_payment_invoices['product_non']; ?>" /></td>
                                                    </tr> 
                                                </tbody> 
                                            </table> 

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success" onclick="set_vat(this)" >Save</button>
                                            </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                <!-- ****************************************************************************************************************** -->

                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="journal_cash_payment_list_debit" value="<?php echo number_format($journal_cash_payment_list_debit,2); ?>" readonly />
                                </td>
                                <td>
                                    <input type="text" class="form-control" style="text-align: right;" id="journal_cash_payment_list_credit" value="<?php echo number_format($journal_cash_payment_list_credit,2); ?>" readonly />
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table> 

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=journal_special_04" class="btn btn-default">Back</a>
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