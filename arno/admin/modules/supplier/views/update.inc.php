<script>
    function check(){


        var supplier_code = document.getElementById("supplier_code").value;
        var supplier_name_th = document.getElementById("supplier_name_th").value;
        var supplier_name_en = document.getElementById("supplier_name_en").value;
        var supplier_type = document.getElementById("supplier_type").value;
        var supplier_tax = document.getElementById("supplier_tax").value;
        var supplier_address_1 = document.getElementById("supplier_address_1").value;
        var supplier_address_2 = document.getElementById("supplier_address_2").value;
        var supplier_address_3 = document.getElementById("supplier_address_3").value;
        var supplier_domestic = document.getElementById("supplier_domestic").value;
        var supplier_branch = document.getElementById("supplier_branch").value;
       
       
        supplier_code = $.trim(supplier_code);
        supplier_name_th = $.trim(supplier_name_th);
        supplier_name_en = $.trim(supplier_name_en);
        supplier_type = $.trim(supplier_type);
        supplier_tax = $.trim(supplier_tax);
        supplier_address_1 = $.trim(supplier_address_1);
        supplier_address_2 = $.trim(supplier_address_2);
        supplier_address_3 = $.trim(supplier_address_3);
        supplier_domestic = $.trim(supplier_domestic);
        supplier_branch = $.trim(supplier_branch);
        
        

        if(supplier_code.length == 0){
            alert("Please input Supplier code");
            document.getElementById("supplier_code").focus();
            return false;
        }else if(supplier_name_th.length == 0){
            alert("Please input Supplier name thai");
            document.getElementById("supplier_name_th").focus();
            return false;
        }else  if(supplier_name_en.length == 0){
            alert("Please input Supplier name english");
            document.getElementById("supplier_name_en").focus();
            return false;
        }else if(supplier_type.length == 0){
            alert("Please input Supplier type");
            document.getElementById("supplier_type").focus();
            return false;
        }else if(supplier_tax.length == 0){
            alert("Please input Supplier tax");
            document.getElementById("supplier_tax").focus();
            return false;
        }else if(supplier_address_1.length == 0 &&supplier_address_2.length == 0 && supplier_address_3.length == 0 ){
            alert("Please input Supplier address");
            document.getElementById("supplier_address_1").focus();
            return false;
        }else if(supplier_branch.length == 0){
            alert("Please input Supplier branch");
            document.getElementById("supplier_branch").focus();
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
            $('#img_logo').attr('src', '../upload/Supplier/default.png');
        }
    }




    function update_code(){
        var supplier_name = document.getElementById("supplier_name_en").value;
        supplier_name = $.trim(supplier_name);
        if(supplier_name.length > 0){
            $.post( "controllers/getSupplierCodeIndex.php", { 'char': supplier_name.split('')[0].toUpperCase() }, function( data ) {
                var index = parseInt(data);
                document.getElementById("supplier_code").value =  supplier_name.split('')[0].toUpperCase() + pad(index + 1,3);
            });
            
        }else{
            document.getElementById("supplier_code").value = "";
        }
    }

</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">จัดการผู้ขาย / Supplier Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                แก้ไขผู้ขาย / Update Supplier 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=supplier&action=edit" enctype="multipart/form-data" >
                <input type="hidden"  id="supplier_id" name="supplier_id" value="<?php echo $supplier_id ?>" />
                <input type="hidden"  id="supplier_logo_o" name="supplier_logo_o" value="<?php echo $Supplier['supplier_logo']; ?>" />    
                    
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>รหัสผู้จำหน่าย / Supplier code<font color="#F00"><b>*</b></font></label>
                                    <input id="supplier_code" name="supplier_code" readonly="true" class="form-control" value="<? echo $Supplier['supplier_code']?>">
                                    <p class="help-block">Example : R001.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ชื่อผู้จำหน่าย (ไทย) / Supplier name (Thai)<font color="#F00"><b>*</b></font></label>
                                    <input id="supplier_name_th" name="supplier_name_th" class="form-control" value="<? echo $Supplier['supplier_name_th']?>">
                                    <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ชื่อผู้จำหน่าย (อังกฤษ) / Supplier name (English)<font color="#F00"><b>*</b></font></label>
                                    <input id="supplier_name_en" name="supplier_name_en" class="form-control" onChange="update_code()" value="<? echo $Supplier['supplier_name_en']?>">
                                    <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>การจดทะเบียน / Company registration <font color="#F00"><b>*</b></font></label>
                                        <select id="supplier_type" name="supplier_type" class="form-control">
                                            <option value="">Select</option>
                                            <option <?php if($Supplier['supplier_type'] == 'บริษัทจำกัด'){?> selected <?php } ?> >บริษัทจำกัด</option>
                                            <option <?php if($Supplier['supplier_type'] == 'บริษัทมหาชนจำกัด'){?> selected <?php } ?> >บริษัทมหาชนจำกัด</option>
                                            <option <?php if($Supplier['supplier_type'] == 'ห้างหุ้นส่วน'){?> selected <?php } ?> >ห้างหุ้นส่วน</option>
                                            <option <?php if($Supplier['supplier_type'] == 'กิจการเจ้าของเดียว'){?> selected <?php } ?> >กิจการเจ้าของเดียว</option>
                                        </select>
                                        <p class="help-block">Example : บริษัทจำกัด.</p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>เลขประจำตัวผู้เสียภาษี / Tax. <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_tax" name="supplier_tax" class="form-control" value="<? echo $Supplier['supplier_tax']?>">
                                        <p class="help-block">Example : 123456789012345.</p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>พื้นที่จดทะเบียน / Domestic <font color="#F00"><b>*</b></font> </label>
                                    <select id="supplier_domestic" name="supplier_domestic" class="form-control">
                                        <option value="">Select</option>
                                        <option <?php if($Supplier['supplier_domestic'] == 'ภายในประเทศ'){?> selected <?php } ?> >ภายในประเทศ</option>
                                        <option <?php if($Supplier['supplier_domestic'] == 'ภายนอกประเทศ'){?> selected <?php } ?> >ภายนอกประเทศ</option>
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
                                    <select id="supplier_branch" name="supplier_branch" class="form-control">
                                            <option value="">Select</option>
                                            <option <?php if($Supplier['supplier_branch'] == 'สำนักงานใหญ่'){?> selected <?php } ?> >สำนักงานใหญ่</option>
                                            <option <?php if($Supplier['supplier_branch'] == 'สำนักงานย่อย'){?> selected <?php } ?> >สำนักงานย่อย</option>
                                        </select>
                                    <p class="help-block">Example : สำนักงานใหญ่.</p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>พื้นที่การขาย / Zone </label>
                                    <input id="supplier_zone" name="supplier_zone" type="text" class="form-control" value="<? echo $Supplier['supplier_zone']?>">
                                    <p class="help-block">Example : ชลบุรี.</p>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>โทรศัพท์ / Telephone </label>
                                    <input id="supplier_tel" name="supplier_tel" type="text" class="form-control" value="<? echo $Supplier['supplier_tel']?>">
                                    <p class="help-block">Example : 023456789.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>แฟกซ์ / Fax </label>
                                    <input id="supplier_fax" name="supplier_fax" type="text" class="form-control" value="<? echo $Supplier['supplier_fax']?>">
                                    <p class="help-block">Example : 020265389-01.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>อีเมล์ / Email </label>
                                    <input id="supplier_email" name="supplier_email" type="email" class="form-control" value="<? echo $Supplier['supplier_email']?>" >
                                    <p class="help-block">Example : admin@arno.co.th.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ที่อยู่ 1 / Address 1 </label>
                                    <input id="supplier_address_1" name="supplier_address_1" type="text" class="form-control" value="<? echo $Supplier['supplier_address_1']?>" >
                                    <p class="help-block">Example : ตึกบางนาธานี.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ที่อยู่ 2 / Address 2 </label>
                                    <input id="supplier_address_2" name="supplier_address_2" type="text" class="form-control" value="<? echo $Supplier['supplier_address_2']?>">
                                    <p class="help-block">Example : เขตบางนา.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>ที่อยู่ 3 / Address 3 </label>
                                    <input id="supplier_address_3" name="supplier_address_3" type="text" class="form-control" value="<? echo $Supplier['supplier_address_3']?>">
                                    <p class="help-block">Example : กรุงเทพ.</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>รหัสไปรษณีย์ / Zipcode </label>
                                    <input id="supplier_zipcode" name="supplier_zipcode" type="text" class="form-control" value="<? echo $Supplier['supplier_zipcode']?>">
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
                                    <label>รูปผู้ขาย / Supplier Picture <font color="#F00"><b>*</b></font></label>
                                    <img class="img-responsive" id="img_logo" src="../upload/Supplier/<?php echo $Supplier['supplier_logo']; ?>" />
                                
                                    <input accept=".jpg , .png"   type="file" id="supplier_logo" name="supplier_logo" onChange="readURL(this);">
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
                            <select id="account_type" name="account_type" class="form-control">
                                <option value="">เลือก / Select</option>
                                <?PHP 
                                    for($i=0; $i < count($account_type) ; $i++){
                                ?>
                                    <option value="<?PHP echo $account_type[$i]['account_type_id'];?>"><?PHP echo $account_type[$i]['account_type_name'];?></option>
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
                                <option value="0" <?PHP if($Supplier['vat_type'] == '0'){?>Selected <?PHP }?> >0 - ไม่มี Vat</option>
                                <option value="1"  <?PHP if($Supplier['vat_type'] == '1'){?>Selected <?PHP }?> >1 - รวม Vat</option>
                                <option value="2"  <?PHP if($Supplier['vat_type'] == '2'){?>Selected <?PHP }?> >2 - แยก Vat</option>
                            </select>
                            <p class="help-block">Example : 0 - ไม่มี vat.</p>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label>ภาษีมูลค่าเพิ่ม / Vat </label>
                            <input id="vat" name="vat" type="text" class="form-control"  style="text-align:right;" value="<? echo $Supplier['vat']?>">
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
                                    <option value="<?PHP echo $currency[$i]['currency_id'];?>" <?PHP if($currency[$i]['currency_id'] == $Supplier['currency_id']){?> Selected <?PHP }?>><?PHP echo $currency[$i]['currency_name_en'];?></option>
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
                            <input id="credit_day" name="credit_day" type="text" class="form-control"  value="30" style="text-align:right;" value="<? echo $Supplier['credit_day']?>">
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
                                <option <?PHP if($Supplier['condition_pay'] == 'เช็ค'){?>Selected <?PHP }?> >เช็ค</option>
                                <option <?PHP if($Supplier['condition_pay'] == 'เงินสด'){?>Selected <?PHP }?> >เงินสด</option>
                                <option <?PHP if($Supplier['condition_pay'] == 'โอนเงิน'){?>Selected <?PHP }?> >โอนเงิน</option>
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