<script>

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getAssetAccountGroupByCode.php", { 'asset_account_group_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("asset_account_group_code").focus();
                $("#code_check").val(data.asset_account_group_code);
                
            } else{
                $("#code_check").val("");
            }
        });
    }

    function check(){


        var asset_account_group_code = document.getElementById("asset_account_group_code").value;
        var asset_account_group_name_th = document.getElementById("asset_account_group_name_th").value;
        var asset_account_group_name_en = document.getElementById("asset_account_group_name_en").value;
        // var code_check = document.getElementById("code_check").value;
        // var user_id = document.getElementById("user_id").value;

        asset_account_group_code = $.trim(asset_account_group_code);
        asset_account_group_name_th = $.trim(asset_account_group_name_th);
        asset_account_group_name_en = $.trim(asset_account_group_name_en);

        

        if(asset_account_group_code.length == 0){
            alert("Please input asset_account_group code");
            document.getElementById("asset_account_group_code").focus();
            return false;
        }else if(asset_account_group_name_th.length == 0){
            alert("Please input asset_account_group name th");
            document.getElementById("asset_account_group_name_th").focus();
            return false;
        }else if(asset_account_group_name_en.length == 0){
            alert("Please input asset_account_group name en ");
            document.getElementById("asset_account_group_name_en").focus();
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
        <h1 class="page-header">Account Group Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset_account_group" class="btn btn-primary active btn-menu">กลุ่มบัญชีทรัพย์สิน/Account Group</a>
        <!-- <a href="?app=asset_account_group_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a> -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เพิ่มรายการกลุ่มบัญชีทรัพย์สิน / Add Account Group List 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=asset_account_group&action=edit" >
                    <input type="hidden"  id="asset_account_group_id" name="asset_account_group_id" value="<?php echo $asset_account_group_id ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>รหัสกลุ่มบัญชีทรัพย์สิน / Account Group Code <font color="#F00"><b>*</b></font></label>
                                <input id="asset_account_group_code" name="asset_account_group_code" value="<?php echo $asset['asset_account_group_code']; ?>" class="form-control" onchange="" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อกลุ่มบัญชีทรัพย์สิน TH/ Account Group Name TH<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_account_group_name_th" name="asset_account_group_name_th"value="<?php echo $asset['asset_account_group_name_th'];?>" class="form-control">
                                    <p class="help-block">Example : บัญชี.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อกลุ่มบัญชีทรัพย์สิน EN/ Account Group Name EN <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_account_group_name_en" name="asset_account_group_name_en" value="<?php echo $asset['asset_account_group_name_en'];?>" class="form-control">
                                    <p class="help-block">Example : Accounting.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=asset_account_group" class="btn btn-default">Back</a>
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