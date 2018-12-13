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
        <h1 class="page-header">Supplier Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Supplier 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=supplier&action=add" enctype="multipart/form-data">
                    
                    
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier code<font color="#F00"><b>*</b></font></label>
                                    <input id="supplier_code" name="supplier_code" readonly="true" class="form-control">
                                    <p class="help-block">Example : R001.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (Thai)<font color="#F00"><b>*</b></font></label>
                                    <input id="supplier_name_th" name="supplier_name_th" class="form-control">
                                    <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (English)<font color="#F00"><b>*</b></font></label>
                                    <input id="supplier_name_en" name="supplier_name_en" class="form-control" onChange="update_code()">
                                    <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Supplier Type <font color="#F00"><b>*</b></font></label>
                                        <select id="supplier_type" name="supplier_type" class="form-control">
                                            <option value="">Select</option>
                                            <option>บริษัทจำกัด</option>
                                            <option>บริษัทมหาชนจำกัด</option>
                                            <option>ห้างหุ้นส่วน</option>
                                            <option>กิจการเจ้าของเดียว</option>
                                        </select>
                                        <p class="help-block">Example : บริษัทจำกัด.</p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Tax. <font color="#F00"><b>*</b></font></label>
                                        <input id="supplier_tax" name="supplier_tax" class="form-control">
                                        <p class="help-block">Example : 123456789012345.</p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Domestic <font color="#F00"><b>*</b></font> </label>
                                    <select id="supplier_domestic" name="supplier_domestic" class="form-control">
                                            <option value="">Select</option>
                                            <option>ภายในประเทศ</option>
                                            <option>ภายนอกประเทศ</option>
            
                                        </select>
                                    <p class="help-block">Example : ภายนอกประเทศ.</p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Branch <font color="#F00"><b>*</b></font></label>
                                    <select id="supplier_branch" name="supplier_branch" class="form-control">
                                            <option value="">Select</option>
                                            <option>สำนักงานใหญ่</option>
                                            <option>สำนักงานย่อย</option>
                                        </select>
                                    <p class="help-block">Example : สำนักงานใหญ่.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Zone </label>
                                    <input id="supplier_zone" name="supplier_zone" type="text" class="form-control">
                                    <p class="help-block">Example : ชลบุรี.</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Credit Day </label>
                                    <input id="credit_day" name="credit_day" type="text" class="form-control">
                                    <p class="help-block">Example : 30 (วัน).</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Pay Type </label>
                                    <select id="condition_pay" name="condition_pay" class="form-control">
                                            <option value="">Select</option>
                                            <option>เช็ค</option>
                                            <option>เงินสด</option>
                                            <option>โอนเงิน</option>
                                        </select>
                                    <p class="help-block">Example : เงินสด.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Telephone </label>
                                    <input id="supplier_tel" name="supplier_tel" type="text" class="form-control">
                                    <p class="help-block">Example : 023456789.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fax </label>
                                    <input id="supplier_fax" name="supplier_fax" type="text" class="form-control">
                                    <p class="help-block">Example : 020265389-01.</p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email </label>
                                    <input id="supplier_email" name="supplier_email" type="email" class="form-control">
                                    <p class="help-block">Example : admin@arno.co.th.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 1 </label>
                                    <input id="supplier_address_1" name="supplier_address_1" type="text" class="form-control">
                                    <p class="help-block">Example : ตึกบางนาธานี.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 2 </label>
                                    <input id="supplier_address_2" name="supplier_address_2" type="text" class="form-control">
                                    <p class="help-block">Example : เขตบางนา.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>Address 3 </label>
                                    <input id="supplier_address_3" name="supplier_address_3" type="text" class="form-control">
                                    <p class="help-block">Example : กรุงเทพ.</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Zipcode </label>
                                    <input id="supplier_zipcode" name="supplier_zipcode" type="text" class="form-control">
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
                                    <label>Supplier Picture <font color="#F00"><b>*</b></font></label>
                                    <img class="img-responsive" id="img_logo" src="../upload/Supplier/default.png" />
                                    <input accept=".jpg , .png"   type="file" id="supplier_logo" name="supplier_logo" onChange="readURL(this);">
                                    <p class="help-block">Example : .jpg or .png </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- /.row (nested) -->




                   

                    
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
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