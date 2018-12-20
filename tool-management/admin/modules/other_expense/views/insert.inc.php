<script>


     function check(){

        var other_expense_code = document.getElementById("other_expense_code").value;
        var other_expense_date = document.getElementById("other_expense_date").value;

        
        other_expense_code = $.trim(other_expense_code);
        other_expense_date = $.trim(other_expense_date);

        if(other_expense_code.length == 0){
            alert("Please input Other Expense code");
            document.getElementById("other_expense_code").focus();
            return false;
        }else if(other_expense_date.length == 0){
            alert("Please input Other Expense date");
            document.getElementById("other_expense_date").focus();
            return false;
        }else{
            return true;
        }
     }

     function delete_row(id){
        $(id).closest('tr').remove();
        calculateAll();
        calculatePay();
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
                    '<input type="hidden" name="other_expense_list_id[]" value="0" />'+  
                    '<input type="text" class="form-control"  name="other_expense_list_code[]"  />'+
                '</td>'+ 
                '<td  style="max-width:80px;">' +
                '<input type="text" class="form-control"  name="other_expense_list_name[]"  />'+
                '</td>'+ 
                '<td  style="max-width:120px;"><input type="text" class="form-control" name="other_expense_list_total[]" style="text-align:right;" onchange="calculateAll()" value="0.00"  /></td>'+
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

    }

    function add_row_pay(id){
         var index = 0;
         if(isNaN($(id).closest('table').children('tbody').children('tr').length)){
            index = 1;
         }else{
            index = $(id).closest('table').children('tbody').children('tr').length + 1;
         }
         $(id).closest('table').children('tbody').append(
            '<tr class="odd gradeX">'+
                '<td style="max-width:150px;" >'+
                    '<input type="hidden" name="other_expense_pay_id[]" value="0" />'+  
                    '<input type="text" class="form-control"  name="other_expense_pay_by[]"  />'+
                '</td>'+ 
                '<td  style="max-width:150px;" >' +
                '<input type="text" class="form-control calendar"  name="other_expense_pay_date[]"  readonly/>'+
                '</td>'+ 
                '<td  style="max-width:150px;" ><input type="text" class="form-control" name="other_expense_pay_bank[]"   /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="other_expense_pay_value[]"   /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="other_expense_pay_balance[]"   /></td>'+
                '<td  style="max-width:100px;" ><input type="text" style="text-align:right;" class="form-control" name="other_expense_pay_total[]" onchange="calculatePay()"   /></td>'+
                
                '<td>'+
                    '<a href="javascript:;" onclick="delete_row(this);" style="color:red;">'+
                        '<i class="fa fa-times" aria-hidden="true"></i>'+
                    '</a>'+
                '</td>'+
            '</tr>'
        );

        $(id).closest('table').children('tbody').children('tr:last').children('td').children('input[name="other_expense_pay_date[]"]').datepicker({ dateFormat: 'dd-mm-yy' });
                

    }

    function get_supplier_detail(){
        var supplier_id = parseInt(document.getElementById('supplier_id').value);
            if(supplier_id > 0){
                $.post( "controllers/getSupplierByID.php", { 'supplier_id': supplier_id }, function( data ) {
                document.getElementById('supplier_code').value = data.supplier_code;
                document.getElementById('supplier_name').value = data.supplier_name_en  ;
                document.getElementById('supplier_address').value = data.supplier_address_1 +'\n' + data.supplier_address_2 +'\n' +data.supplier_address_3;
                document.getElementById('supplier_tax').value = data.supplier_tax ;
            });
        }
        
    }

    function calculateAll(){

        var val = document.getElementsByName('other_expense_list_total[]');
        var total = 0.0;
        var vat = parseFloat($('#other_expense_vat').val().toString().replace(new RegExp(',', 'g'),''));
        for(var i = 0 ; i < val.length ; i++){
            
            total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
        }

        $('#other_expense_total').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );



        if( parseInt($('#other_expense_vat_type').val()) == 2){
            $('#other_expense_vat_value').val((total * (vat/100.0)).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#other_expense_net').val((total * (vat/100.0) + total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

            
        } else if (parseInt($('#other_expense_vat_type').val()) == 1) {

            $('#other_expense_total').val( (total * (100/(vat+100.0) ) ).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#other_expense_vat_value').val(  (total - total * (100/(vat+100.0) ) ).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#other_expense_net').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

        }else{
            $('#other_expense_vat').val( (0).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#other_expense_vat_value').val((0).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
            $('#other_expense_net').val((total).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );
        }
    }

    function calculatePay(){

        var val = document.getElementsByName('other_expense_pay_total[]');
        var total = 0.0;
        for(var i = 0 ; i < val.length ; i++){
            if(isNaN(val[i].value)){
                total += 0;
            }else{
                total += parseFloat(val[i].value.toString().replace(new RegExp(',', 'g'),''));
            }
            
        }

        $('#other_expense_other_pay').val(total.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Other Expense Management</h1>
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
            เพิ่มค่าใช้จ่ายอื่นๆ  / Add Other Expense 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=other_expense&action=add" >
                <input type="hidden"  id="other_expense_id" name="other_expense_id" value="<?php echo $other_expense_id; ?>" />
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
                                            <option <?php if($suppliers[$i]['supplier_id'] == $other_expense['supplier_id']){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?>  </option>
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
                                        <label>ชื่อตามค่าใช้จ่ายอื่นๆ  / Full name <font color="#F00"><b>*</b></font></label>
                                        <input  id="supplier_name" name="supplier_name" class="form-control" value="<?php echo $supplier['supplier_name_en'];?> " readonly>
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
                                        <label>วันที่ออกค่าใช้จ่ายอื่นๆ  / Other Expense Date</label>
                                        <input type="text" id="other_expense_date" name="other_expense_date" value="<?PHP echo $first_date;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขค่าใช้จ่ายอื่นๆ  / Other Expense code <font color="#F00"><b>*</b></font></label>
                                        <input id="other_expense_code" name="other_expense_code" class="form-control" value="<?PHP echo $last_code;?>" readonly>
                                        <p class="help-block">Example : RR1801001.</p>
                                    </div>
                                </div>     

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>วันที่ออกใบเสร็จ  / Bill Date</label>
                                        <input type="text" id="other_expense_bill_date" name="other_expense_bill_date" value="<?PHP echo $first_date;?>"  class="form-control calendar" readonly/>
                                        <p class="help-block">01-03-2018</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>หมายเลขใบเสร็จ  / Bill code <font color="#F00"><b>*</b></font></label>
                                        <input id="other_expense_bill_code" name="other_expense_bill_code" class="form-control" value="<?PHP echo $other_expense['other_expense_bill_code']; ?>" >
                                        <p class="help-block">Example : RR1801001.</p>
                                    </div>
                                </div>                 

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                                        <select id="other_expense_vat_type" name="other_expense_vat_type"  class="form-control" onchange="calculateAll();">
                                            <option value="0" <?PHP if($other_expense['other_expense_vat_type'] == '0'){?>Selected <?PHP }?> >0 - ไม่มี Vat</option>
                                            <option value="1"  <?PHP if($other_expense['other_expense_vat_type'] == '1'){?>Selected <?PHP }?> >1 - รวม Vat</option>
                                            <option value="2"  <?PHP if($other_expense['other_expense_vat_type'] == '2'){?>Selected <?PHP }?> >2 - แยก Vat</option>
                                        </select>
                                        <p class="help-block">Example : 0 - ไม่มี vat.</p>
                                    </div>
                                </div>

                                                              

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>หมายเหตุ / Remark  <font color="#F00"><b>*</b></font> </label>
                                        <textarea id="other_expense_remark" name="other_expense_remark" class="form-control" ><?PHP echo $other_expense['other_expense_remark'];?></textarea>
                                        <p class="help-block">Example : -.</p>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> 

                     <div>
                    <b>รายการสินค้า  : </b>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">รหัสสินค้า <br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th> 
                                <th style="text-align:center;max-width:120px;">จำนวนเงิน<br>(Total price)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($other_expense_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="other_expense_list_id[]" value="<?php echo $other_expense_lists[$i]['other_expense_list_id']; ?>" />
                                    <input type="text" class="form-control" name="other_expense_list_code[]" value="<?php echo $other_expense_lists[$i]['other_expense_list_code']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="other_expense_list_name[]" value="<?php echo $other_expense_lists[$i]['other_expense_list_name']; ?>" />
                                </td> 
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="other_expense_list_total[]" onchange="calculateAll()" value="<?php echo number_format($other_expense_lists[$i]['other_expense_list_total'],2); ?>" readonly /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $other_expense_lists[$i]['other_expense_list_total'];
                            }

                            if( $other_expense['other_expense_vat_type'] == 2){
                                $vat_price = ($vat/100) * $total;
                                $net = ($vat/100) * $total + $total;
                               
                            } else if ($other_expense['other_expense_vat_type'] == 1) {
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
                                <td colspan="4" align="center">
                                    <a href="javascript:;" onclick="add_row(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มสินค้า / Add product</span>
                                    </a>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td rowspan="3">
                                    
                                </td>
                                <td  align="left" style="vertical-align: middle;">
                                    <span>ราคารวมทั้งสิ้น / Sub total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="other_expense_total" name="other_expense_total" value="<?PHP echo number_format($total,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td  align="left" style="vertical-align: middle;">
                                    <table>
                                        <tr>
                                            <td>
                                                <span>จำนวนภาษีมูลค่าเพิ่ม / Vat</span>
                                            </td>
                                            <td style = "padding-left:8px;padding-right:8px;width:72px;">
                                                <input type="text" class="form-control" style="text-align: right;" onchange="calculateAll()" id="other_expense_vat" name="other_expense_vat" value="<?PHP echo $vat;?>" />
                                            </td>
                                            <td width="16">
                                            %
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="other_expense_vat_value"  name="other_expense_vat_value" value="<?PHP echo number_format($vat_price,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="odd gradeX">
                                <td align="left" style="vertical-align: middle;">
                                    <span>จำนวนเงินรวมทั้งสิ้น / Net Total</span>
                                </td>
                                <td style="max-width:120px;">
                                    <input type="text" class="form-control" style="text-align: right;" id="other_expense_net" name="other_expense_net" value="<?PHP echo number_format($net,2) ;?>"  readonly/>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <br>
                    <div>
                    <b>รายการชำระเงิน :</b>
                    </div>

                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;">ชำระโดยอื่นๆ <br>(Pay by)</th>
                                <th style="text-align:center;">ลงวันที่<br>(Product Name)</th> 
                                <th style="text-align:center;max-width:120px;">ธนาคาร<br>(Bank)</th>
                                <th style="text-align:center;max-width:120px;">จำนวนเงิน<br>(Total)</th>
                                <th style="text-align:center;max-width:120px;">ยอดคงเหลือ<br>(Balance)</th>
                                <th style="text-align:center;max-width:120px;">ยอดชำระ<br>(Pay)</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            for($i=0; $i < count($other_expense_pays); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td>
                                    <input type="hidden" class="form-control" name="other_expense_pay_id[]" value="<?php echo $other_expense_pays[$i]['other_expense_pay_id']; ?>" />
                                    <input type="text" class="form-control" name="other_expense_pay_by[]" value="<?php echo $other_expense_pays[$i]['other_expense_pay_by']; ?>" />
                                </td>
                                <td>
                                    <input type="text" class="form-control calendar" name="other_expense_pay_date[]" value="<?php echo $other_expense_pays[$i]['other_expense_pay_date']; ?>" readonly/>
                                </td> 
                                <td>
                                    <input type="text" class="form-control" name="other_expense_pay_bank[]" value="<?php echo $other_expense_pays[$i]['other_expense_pay_bank']; ?>" />
                                </td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="other_expense_pay_value[]" value="<?php echo number_format($other_expense_pays[$i]['other_expense_pay_value'],2); ?>"  /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="other_expense_pay_balance[]" value="<?php echo number_format($other_expense_pays[$i]['other_expense_pay_balance'],2); ?>"  /></td>
                                <td  style="max-width:120px;"><input type="text" class="form-control"  style="text-align:right;" name="other_expense_pay_total[]" value="<?php echo number_format($other_expense_pays[$i]['other_expense_pay_total'],2); ?>"  onchange="calculatePay()"  /></td>
                                <td>
                                    <a href="javascript:;" onclick="delete_row(this);" style="color:red;">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <?
                                $total += $other_expense_pays[$i]['other_expense_pay_total'];
                            }
                            

                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="odd gradeX">
                                <td colspan="7" align="center">
                                    <a href="javascript:;" onclick="add_row_pay(this);" style="color:red;">
                                        <i class="fa fa-plus" aria-hidden="true"></i> 
                                        <span>เพิ่มรายการจ่ายเงิน / Add pay list</span>
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ดอกเบี้ย</label>
                                <input id="other_expense_interest" name="other_expense_interest" style="text-align:right;" class="form-control" value="<?PHP echo number_format($other_expense['other_expense_interest'],2);?>" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>  
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>เงินสด</label>
                                <input id="other_expense_cash" name="other_expense_cash" style="text-align:right;" class="form-control" value="<?PHP echo number_format($other_expense['other_expense_cash'],2);?>" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ชำระโดย (ด้านบน)</label>
                                <input id="other_expense_other_pay" name="other_expense_other_pay" style="text-align:right;" class="form-control" value="<?PHP echo number_format($total,2);?>" readonly >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ภาษีหัก ณ ที่จ่าย</label>
                                <input id="other_expense_vat_pay" name="other_expense_vat_pay"  style="text-align:right;" class="form-control" value="<?PHP echo number_format($other_expense['other_expense_vat_pay'],2);?>" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ส่วนลดเงินสด</label>
                                <input id="other_expense_discount_cash" name="other_expense_discount_cash" style="text-align:right;" class="form-control" value="<?PHP echo number_format($other_expense['other_expense_discount_cash'],2);?>" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ยอดจ่ายจริง</label>
                                <input id="other_expense_pay" name="other_expense_pay" style="text-align:right;" class="form-control" value="<?PHP echo number_format($other_expense['other_expense_pay'],2);?>" >
                                <p class="help-block">Example : 0.00.</p>
                            </div>
                        </div>
                    </div>

                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="index.php?app=other_expense" class="btn btn-default">Back</a>
                        
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