<script>

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getAssetDepartmentByCode.php", { 'asset_department_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("asset_department_code").focus();
                $("#code_check").val(data.asset_department_code);
                
            } else{
                $("#code_check").val("");
            }
        });
    }

    function check(){


        var asset_department_code = document.getElementById("asset_department_code").value;
        var asset_department_name_th = document.getElementById("asset_department_name_th").value;
        var asset_department_name_en = document.getElementById("asset_department_name_en").value;
        // var code_check = document.getElementById("code_check").value;
        // var user_id = document.getElementById("user_id").value;

        asset_department_code = $.trim(asset_department_code);
        asset_department_name_th = $.trim(asset_department_name_th);
        asset_department_name_en = $.trim(asset_department_name_en);

        

        if(asset_department_code.length == 0){
            alert("Please input asset_department code");
            document.getElementById("asset_department_code").focus();
            return false;
        }else if(asset_department_name_th.length == 0){
            alert("Please input asset_department name th");
            document.getElementById("asset_department_name_th").focus();
            return false;
        }else if(asset_department_name_en.length == 0){
            alert("Please input asset_department name en ");
            document.getElementById("asset_department_name_en").focus();
            return false;
        }
        else{
            // var canvas = document.getElementById("signature");
            // var dataURL = canvas.toDataURL("image/png");
            // document.getElementById('hidden_data').value = dataURL;
            return true;
        }



    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Department Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset_department" class="btn btn-primary active btn-menu">แผนก/Department</a>
        <!-- <a href="?app=asset_department_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a> -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มรายการแผนก / Add Department List 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=asset_department&action=edit" >
                    <input type="hidden"  id="asset_department_id" name="asset_department_id" value="<?php echo $asset_department_id ?>" />
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>รหัสแผนก / Department Code <font color="#F00"><b>*</b></font></label>
                                <input id="asset_department_code" name="asset_department_code" value="<?php echo $asset['asset_department_code']; ?>" class="form-control" onchange="" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อแผนก TH/ Department Name TH<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_department_name_th" name="asset_department_name_th"value="<?php echo $asset['asset_department_name_th'];?>" class="form-control">
                                    <p class="help-block">Example : บัญชี.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-3">
                            
                                <div class="form-group">
                                    <label>ชื่อแผนก EN/ Department Name EN <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_department_name_en" name="asset_department_name_en" value="<?php echo $asset['asset_department_name_en'];?>" class="form-control">
                                    <p class="help-block">Example : Accounting.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=asset_department" class="btn btn-default">Back</a>
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