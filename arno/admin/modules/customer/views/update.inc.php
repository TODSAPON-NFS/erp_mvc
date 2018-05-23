<script>
    function check(){


        var customer_code = document.getElementById("customer_code").value;
        var customer_name_th = document.getElementById("customer_name_th").value;
        var customer_name_en = document.getElementById("customer_name_en").value;
        var customer_type = document.getElementById("customer_type").value;
        var customer_tax = document.getElementById("customer_tax").value;
        var customer_address_1 = document.getElementById("customer_address_1").value;
        var customer_address_2 = document.getElementById("customer_address_2").value;
        var customer_address_3 = document.getElementById("customer_address_3").value;
        var customer_domestic = document.getElementById("customer_domestic").value;
        var customer_branch = document.getElementById("customer_branch").value;
       
       
        customer_code = $.trim(customer_code);
        customer_name_th = $.trim(customer_name_th);
        customer_name_en = $.trim(customer_name_en);
        customer_type = $.trim(customer_type);
        customer_tax = $.trim(customer_tax);
        customer_address_1 = $.trim(customer_address_1);
        customer_address_2 = $.trim(customer_address_2);
        customer_address_3 = $.trim(customer_address_3);
        customer_domestic = $.trim(customer_domestic);
        customer_branch = $.trim(customer_branch);
        
        

        if(customer_code.length == 0){
            alert("Please input customer code");
            document.getElementById("customer_code").focus();
            return false;
        }else if(customer_name_th.length == 0){
            alert("Please input customer name thai");
            document.getElementById("customer_name_th").focus();
            return false;
        }else  if(customer_name_en.length == 0){
            alert("Please input customer name english");
            document.getElementById("customer_name_en").focus();
            return false;
        }else if(customer_type.length == 0){
            alert("Please input customer type");
            document.getElementById("customer_type").focus();
            return false;
        }else if(customer_tax.length == 0){
            alert("Please input customer tax");
            document.getElementById("customer_tax").focus();
            return false;
        }else if(customer_address_1.length == 0 &&customer_address_2.length == 0 && customer_address_3.length == 0 ){
            alert("Please input customer address");
            document.getElementById("customer_address_1").focus();
            return false;
        }else if(customer_branch.length == 0){
            alert("Please input customer branch");
            document.getElementById("customer_branch").focus();
            return false;
        }else{
            return true;
        }



    }

    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }

    function readURL(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '../upload/customer/default.png');
        }
    }




    function update_code(){
        var customer_name = document.getElementById("customer_name_en").value;
        customer_name = $.trim(customer_name);
        if(customer_name.length > 0){
            $.post( "controllers/getCustomerCodeIndex.php", { 'char': customer_name.split('')[0].toUpperCase() }, function( data ) {
                var index = parseInt(data);
                document.getElementById("customer_code").value =  customer_name.split('')[0].toUpperCase() + pad(index + 1,3);
            });
            
        }else{
            document.getElementById("customer_code").value = "";
        }
    }


</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Customer Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                แก้ไขลูกค้า / Update Customer 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=customer&action=edit" enctype="multipart/form-data" >
                    <input type="hidden"  id="customer_id" name="customer_id" value="<?php echo $customer_id ?>" />
                    <input type="hidden"  id="customer_logo_o" name="customer_logo_o" value="<?php echo $customer['customer_logo']; ?>" />    
                        
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>รหัสลูกค้า / Customer code<font color="#F00"><b>*</b></font></label>
                                        <input id="customer_code" name="customer_code" readonly="true" class="form-control" value="<? echo $customer['customer_code']?>">
                                        <p class="help-block">Example : R001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ชื่อลูกค้า (ไทย) / Customer name (Thai)<font color="#F00"><b>*</b></font></label>
                                        <input id="customer_name_th" name="customer_name_th" class="form-control" value="<? echo $customer['customer_name_th']?>">
                                        <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>ชื่อลูกค้า (อังกฤษ) / Customer name (English)<font color="#F00"><b>*</b></font></label>
                                        <input id="customer_name_en" name="customer_name_en" class="form-control" onChange="update_code()" value="<? echo $customer['customer_name_en']?>">
                                        <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                
                                <div class="col-lg-4">
                                    
                                        <div class="form-group">
                                            <label>การจดทะเบียน / Company registration<font color="#F00"><b>*</b></font></label>
                                            <select id="customer_type" name="customer_type" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if($customer['customer_type'] == 'บริษัทจำกัด'){?> selected <?php } ?> >บริษัทจำกัด</option>
                                                <option <?php if($customer['customer_type'] == 'บริษัทมหาชนจำกัด'){?> selected <?php } ?> >บริษัทมหาชนจำกัด</option>
                                                <option <?php if($customer['customer_type'] == 'ห้างหุ้นส่วน'){?> selected <?php } ?> >ห้างหุ้นส่วน</option>
                                                <option <?php if($customer['customer_type'] == 'กิจการเจ้าของเดียว'){?> selected <?php } ?> >กิจการเจ้าของเดียว</option>
                                            </select>
                                            <p class="help-block">Example : บริษัทจำกัด.</p>
                                        </div>
                                    
                                </div>
                                <div class="col-lg-4">
                                    
                                        <div class="form-group">
                                            <label>เลขประจำตัวผู้เสียภาษี / Tax. <font color="#F00"><b>*</b></font></label>
                                            <input id="customer_tax" name="customer_tax" class="form-control" value="<? echo $customer['customer_tax']?>">
                                            <p class="help-block">Example : 123456789012345.</p>
                                        </div>
                                    
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>พื้นที่จดทะเบียน / Domestic <font color="#F00"><b>*</b></font> </label>
                                        <select id="customer_domestic" name="customer_domestic" class="form-control">
                                            <option value="">Select</option>
                                            <option <?php if($customer['customer_domestic'] == 'ภายในประเทศ'){?> selected <?php } ?> >ภายในประเทศ</option>
                                            <option <?php if($customer['customer_domestic'] == 'ภายนอกประเทศ'){?> selected <?php } ?> >ภายนอกประเทศ</option>
                                        </select>
                                        <p class="help-block">Example : ภายนอกประเทศ.</p>
                                    </div>
                                </div>
                                
                                <!-- /.col-lg-6 (nested) -->
                            </div>

                            <!-- /.row (nested) -->
                            <div class="row">
                            
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>สาขา / Branch <font color="#F00"><b>*</b></font></label>
                                        <input id="customer_branch" name="customer_branch" class="form-control" value="<? echo $customer['customer_branch']?>"/>
                                        <p class="help-block">Example : 0000 = สำนักงานใหญ่.</p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label>พื้นที่การขาย / Zone </label>
                                        <input id="customer_zone" name="customer_zone" type="text" class="form-control" value="<? echo $customer['customer_zone']?>">
                                        <p class="help-block">Example : ชลบุรี.</p>
                                    </div>
                                </div>
                            
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>โทรศัพท์ / Telephone </label>
                                        <input id="customer_tel" name="customer_tel" type="text" class="form-control" value="<? echo $customer['customer_tel']?>">
                                        <p class="help-block">Example : 023456789.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>แฟกซ์ / Fax </label>
                                        <input id="customer_fax" name="customer_fax" type="text" class="form-control" value="<? echo $customer['customer_fax']?>">
                                        <p class="help-block">Example : 020265389-01.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>อีเมล์ / Email </label>
                                        <input id="customer_email" name="customer_email" type="email" class="form-control" value="<? echo $customer['customer_email']?>" >
                                        <p class="help-block">Example : admin@arno.co.th.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 1 / Address 1 </label>
                                        <input id="customer_address_1" name="customer_address_1" type="text" class="form-control" value="<? echo $customer['customer_address_1']?>" >
                                        <p class="help-block">Example : ตึกบางนาธานี.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>ที่อยู่ 2 / Address 2 </label>
                                        <input id="customer_address_2" name="customer_address_2" type="text" class="form-control" value="<? echo $customer['customer_address_2']?>">
                                        <p class="help-block">Example : เขตบางนา.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label>ที่อยู่ 3 / Address 3 </label>
                                        <input id="customer_address_3" name="customer_address_3" type="text" class="form-control" value="<? echo $customer['customer_address_3']?>">
                                        <p class="help-block">Example : กรุงเทพ.</p>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label>รหัสไปรษณีย์ / Zipcode </label>
                                        <input id="customer_zipcode" name="customer_zipcode" type="text" class="form-control" value="<? echo $customer['customer_zipcode']?>">
                                        <p class="help-block">Example : 20150.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>รูปลูกค้า / Customer Picture <font color="#F00"><b>*</b></font></label>
                                        <img class="img-responsive" id="img_logo" src="../upload/customer/<?php echo $customer['customer_logo']; ?>" />
                                    
                                        <input accept=".jpg , .png"   type="file" id="customer_logo" name="customer_logo" onChange="readURL(this);">
                                        <p class="help-block">Example : .jpg or .png </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>ประเภทบัญชี / Account type </label>
                                <select id="account_id" name="account_id" class="form-control">
                                    <option value="">เลือก / Select</option>
                                    <?PHP 
                                        for($i=0; $i < count($account) ; $i++){
                                    ?>
                                        <option value="<?PHP echo $account[$i]['account_id'];?>" <?PHP if($account[$i]['account_id'] == $customer['account_id'] ){ ?> SELECTED <? } ?> ><?PHP echo $account[$i]['account_code'];?> <?PHP echo $account[$i]['account_name_th'];?></option>
                                    <?PHP
                                        }
                                    ?>
                                </select>
                                <p class="help-block">Example : 2120-01.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                                <select id="vat_type" name="vat_type" class="form-control">
                                    <option value=""  >เลือก / Select</option>
                                    <option value="0" <?PHP if($customer['vat_type'] == '0'){?>Selected <?PHP }?> >0 - ไม่มี Vat</option>
                                    <option value="1"  <?PHP if($customer['vat_type'] == '1'){?>Selected <?PHP }?> >1 - รวม Vat</option>
                                    <option value="2"  <?PHP if($customer['vat_type'] == '2'){?>Selected <?PHP }?> >2 - แยก Vat</option>
                                </select>
                                <p class="help-block">Example : 0 - ไม่มี vat.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>ภาษีมูลค่าเพิ่ม / Vat </label>
                                <input id="vat" name="vat" type="text" class="form-control"  style="text-align:right;" value="<? echo $customer['vat']?>">
                                <p class="help-block">Example : 7.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>สกุลเงิน / Curreny. </label>
                                <select id="currency_id" name="currency_id" class="form-control">
                                    <option value="">เลือก / Select</option>
                                    <?PHP 
                                        for($i=0; $i < count($currency) ; $i++){
                                    ?>
                                        <option value="<?PHP echo $currency[$i]['currency_id'];?>" <?PHP if($currency[$i]['currency_id'] == $customer['currency_id']){?> Selected <?PHP }?>><?PHP echo $currency[$i]['currency_name_en'];?></option>
                                    <?PHP
                                        }
                                    ?>
                                </select>
                                <p class="help-block">Example : 3.</p>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>เครดิต / Credit Day </label>
                                <input id="credit_day" name="credit_day" type="text" class="form-control"  value="30" style="text-align:right;" value="<? echo $customer['credit_day']?>">
                                <p class="help-block">Example : 30 (วัน).</p>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>เงื่อนไขการชำระเงิน / Pay Type </label>
                                <select id="condition_pay" name="condition_pay" class="form-control">
                                    <option value="">เลือก / Select</option>
                                    <option <?PHP if($customer['condition_pay'] == 'เช็ค'){?>Selected <?PHP }?> >เช็ค</option>
                                    <option <?PHP if($customer['condition_pay'] == 'เงินสด'){?>Selected <?PHP }?> >เงินสด</option>
                                    <option <?PHP if($customer['condition_pay'] == 'โอนเงิน'){?>Selected <?PHP }?> >โอนเงิน</option>
                                </select>
                                <p class="help-block">Example : เงินสด.</p>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
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