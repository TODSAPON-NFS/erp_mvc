<script>
    function check(){


        var company_name_th = document.getElementById("company_name_th").value;
        var company_name_en = document.getElementById("company_name_en").value; 
        var company_tax = document.getElementById("company_tax").value;
        var company_address_1 = document.getElementById("company_address_1").value;
        var company_address_2 = document.getElementById("company_address_2").value;
        var company_address_3 = document.getElementById("company_address_3").value;
        var company_branch = document.getElementById("company_branch").value;
        var company_email = document.getElementById("company_email").value;
        var company_tel = document.getElementById("company_tel").value;
        var company_vat_type = document.getElementById("company_vat_type").value; 
       
        company_name_th = $.trim(company_name_th);
        company_name_en = $.trim(company_name_en); 
        company_tax = $.trim(company_tax);
        company_address_1 = $.trim(company_address_1);
        company_address_2 = $.trim(company_address_2);
        company_address_3 = $.trim(company_address_3); 
        company_branch = $.trim(company_branch);
        company_email = $.trim(company_email);
        company_tel = $.trim(company_tel);
        company_vat_type = $.trim(company_vat_type);
        
        

        if(company_name_th.length == 0){
            alert("Please input company name thai.");
            document.getElementById("company_name_th").focus();
            return false;
        }else  if(company_name_en.length == 0){
            alert("Please input company name english.");
            document.getElementById("company_name_en").focus();
            return false;
        }else if(company_tax.length == 0){
            alert("Please input company tax.");
            document.getElementById("company_tax").focus();
            return false;
        }else if(company_address_1.length == 0 &&company_address_2.length == 0 && company_address_3.length == 0 ){
            alert("Please input company address.");
            document.getElementById("company_address_1").focus();
            return false;
        }else if(company_branch.length == 0){
            alert("Please input company branch.");
            document.getElementById("company_branch").focus();
            return false;
        }else if(company_email.length == 0){
            alert("Please input company email.");
            document.getElementById("company_email").focus();
            return false;
        }else if(company_tel.length == 0){
            alert("Please input company telephone.");
            document.getElementById("company_tel").focus();
            return false;
        }else if(company_vat_type.length == 0){
            alert("Please input company vat.");
            document.getElementById("company_vat_type").focus();
            return false;
        }else{
            return true;
        }



    } 

    function readURL(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo').attr('src', '<?PHP echo $target_dir; ?>/default.png');
        }
    }

    function readURL_rectangle(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#img_logo_rectangle').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        }else{
            $('#img_logo_rectangle').attr('src', '<?PHP echo $target_dir; ?>/default.png');
        }
    }


</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ตั้งค่าบริษัท / Company Setting.</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            ตั้งค่าบริษัท / Company Setting 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=setting&action=edit" enctype="multipart/form-data" >
                <input type="hidden"  id="company_image_o" name="company_image_o" value="<?php echo $company['company_image']; ?>" />    
                <input type="hidden"  id="company_image_rectangle_o" name="company_image_rectangle_o" value="<?php echo $company['company_image_rectangle']; ?>" />     
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row"> 
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ชื่อบริษัท (ไทย) / Company name (Thai)<font color="#F00"><b>*</b></font></label>
                                    <input id="company_name_th" name="company_name_th" class="form-control" value="<? echo $company['company_name_th']?>">
                                    <p class="help-block">Example : บริษัท เรเวลซอฟต์ จำกัด.</p>
                                </div>
                            </div> 
                            <div class="col-lg-3"> 
                                <div class="form-group">
                                    <label>เลขประจำตัวผู้เสียภาษี / Tax. <font color="#F00"><b>*</b></font></label>
                                    <input id="company_tax" name="company_tax" class="form-control" value="<? echo $company['company_tax']?>">
                                    <p class="help-block">Example : 123456789012345.</p>
                                </div> 
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>สาขา (ไทย) / Branch (Thai) <font color="#F00"><b>*</b></font></label>
                                    <input id="company_branch" name="company_branch" class="form-control" value="<? echo $company['company_branch']?>"/>
                                    <p class="help-block">Example : 0000 = สำนักงานใหญ่, 0001 = สาขาย่อยที่ 1 .</p>
                                </div>
                            </div>
                            
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>ประเภทภาษีมูลค่าเพิ่ม / Vat type </label>
                                    <select id="company_vat_type" name="company_vat_type" class="form-control">
                                        <option value=""  >เลือก / Select</option>
                                        <option value="0" <?PHP if($company['company_vat_type'] == '0'){?>Selected <?PHP }?> >0 - ไม่มี Vat</option>
                                        <option value="1"  <?PHP if($company['company_vat_type'] == '1'){?>Selected <?PHP }?> >1 - รวม Vat</option>
                                        <option value="2"  <?PHP if($company['company_vat_type'] == '2'){?>Selected <?PHP }?> >2 - แยก Vat</option>
                                    </select>
                                    <p class="help-block">Example : 0 - ไม่มี vat.</p>
                                </div>
                            </div> 
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>โทรศัพท์ / Telephone </label>
                                    <input id="company_tel" name="company_tel" type="text" class="form-control" value="<? echo $company['company_tel']?>">
                                    <p class="help-block">Example : 023456789.</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>แฟกซ์ / Fax </label>
                                    <input id="company_fax" name="company_fax" type="text" class="form-control" value="<? echo $company['company_fax']?>">
                                    <p class="help-block">Example : 020265389-01.</p>
                                </div>
                            </div>
                           
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ที่อยู่ 1 / Address 1 </label>
                                    <input id="company_address_1" name="company_address_1" type="text" class="form-control" value="<? echo $company['company_address_1']?>" >
                                    <p class="help-block">Example : ตึกบางนาธานี.</p>
                                </div>
                            </div> 
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ที่อยู่ 2 / Address 2 </label>
                                    <input id="company_address_2" name="company_address_2" type="text" class="form-control" value="<? echo $company['company_address_2']?>">
                                    <p class="help-block">Example : เขตบางนา.</p>
                                </div>
                            </div> 
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label>ที่อยู่ 3 / Address 3 </label>
                                    <input id="company_address_3" name="company_address_3" type="text" class="form-control" value="<? echo $company['company_address_3']?>">
                                    <p class="help-block">Example : กรุงเทพ 20150.</p>
                                </div>
                            </div> 
                        </div> 
                        <br>
                        <hr>
                        <br>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ชื่อบริษัท (อังกฤษ) / Company name (English)<font color="#F00"><b>*</b></font></label>
                                    <input id="company_name_en" name="company_name_en" class="form-control" onChange="update_code()" value="<? echo $company['company_name_en']?>">
                                    <p class="help-block">Example : Revel Soft Co., Ltd.</p>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>สาขา (อังกฤษ) / Branch (English) <font color="#F00"><b>*</b></font></label>
                                    <input id="company_branch_en" name="company_branch_en" class="form-control" value="<? echo $company['company_branch_en']?>"/>
                                    <p class="help-block">Example : 0000 = สำนักงานใหญ่, 0001 = สาขาย่อยที่ 1 .</p>
                                </div>
                            </div>
                        </div> 
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ที่อยู่ (อังกฤษ) 1 / Address (English) 1  </label>
                                    <input id="company_address_en_1" name="company_address_en_1" type="text" class="form-control" value="<? echo $company['company_address_en_1']?>" >
                                    <p class="help-block">Example : ตึกบางนาธานี.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>ที่อยู่ (อังกฤษ) 2 / Address (English) 2 </label>
                                    <input id="company_address_en_2" name="company_address_en_2" type="text" class="form-control" value="<? echo $company['company_address_en_2']?>">
                                    <p class="help-block">Example : เขตบางนา.</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label>ที่อยู่ (อังกฤษ) 3 / Address (English) 3 </label>
                                    <input id="company_address_en_3" name="company_address_en_3" type="text" class="form-control" value="<? echo $company['company_address_en_3']?>">
                                    <p class="help-block">Example : กรุงเทพ 20150.</p>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>อีเมล์ / Email </label>
                                    <input id="company_email" name="company_email" type="email" class="form-control" value="<? echo $company['company_email']?>" >
                                    <p class="help-block">Example : admin@arno.co.th</p>
                                </div>
                            </div> 
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>เอสเอ็มทีพี / SMTP </label>
                                    <input id="company_email_smtp" name="company_email_smtp" type="email" class="form-control" value="<? echo $company['company_email_smtp']?>" >
                                    <p class="help-block">Example : mail.arno.co.th</p>
                                </div>
                            </div> 
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>พอร์ต / Port </label>
                                    <input id="company_email_port" name="company_email_port" type="email" class="form-control" value="<? echo $company['company_email_port']?>" >
                                    <p class="help-block">Example : 445</p>
                                </div>
                            </div> 
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>ยูสเซอร์ / Username </label>
                                    <input id="company_email_user" name="company_email_user" type="email" class="form-control" value="<? echo $company['company_email_user']?>" >
                                    <p class="help-block">Example : 445</p>
                                </div>
                            </div> 
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>รหัสผ่าน / Password </label>
                                    <input id="company_email_password" name="company_email_password" type="password" class="form-control" value="<? echo $company['company_email_password']?>" >
                                    <p class="help-block">Example : 445</p>
                                </div>
                            </div> 
                        </div> 
                        <!-- /.row (nested) -->
                    </div>

                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปบริษัท / Company Picture <font color="#F00"><b>*</b></font></label>
                                    <img class="img-responsive" id="img_logo" src="<?php echo $target_dir.$company['company_image']; ?>" />
                                
                                    <input accept=".jpg , .png"   type="file" id="company_image" name="company_image" onChange="readURL(this);">
                                    <p class="help-block">Example : .jpg or .png </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปบริษัท (แสดงหน้าแรก) / Company Picture (Home) <font color="#F00"><b>*</b></font></label>
                                    <img class="img-responsive" id="img_logo_rectangle" src="<?php echo $target_dir.$company['company_image_rectangle']; ?>" />
                                
                                    <input accept=".jpg , .png"   type="file" id="company_image_rectangle" name="company_image_rectangle" onChange="readURL_rectangle(this);">
                                    <p class="help-block">Example : .jpg or .png </p>
                                </div>
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