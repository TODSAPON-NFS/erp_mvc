<script>

    function check_code(id){
        var code = $(id).val();
        var code_check = $("#code_check").val();
        $.post( "controllers/getAssetCategoryByCode.php", { 'asset_category_code': code }, function( data ) {  
            if(data != null){ 
                if(data.asset_category_code != code_check){
                    alert("This "+code_check+" is already in the system. check_code");
                    console.log('data.asset_category_code :',data.asset_category_code);
                    document.getElementById("asset_category_code").focus();
                }
                
                else{
                    $("#code_check").val("");
                }
                
            } else{
                $("#code_check").val("");
            }
        });
    }

    function check(){


        var asset_category_code = document.getElementById("asset_category_code").value;
        var asset_category_name_th = document.getElementById("asset_category_name_th").value;
        var asset_category_name_en = document.getElementById("asset_category_name_en").value;
        var code_check = document.getElementById("code_check").value;
        
        asset_category_code = $.trim(asset_category_code);
        asset_category_name_th = $.trim(asset_category_name_th);
        asset_category_name_en = $.trim(asset_category_name_en);

        
         if(asset_category_code.length == 0){
            alert("Please input asset_category code");
            document.getElementById("asset_category_code").focus();
            return false;
        }else if(asset_category_name_th.length == 0){
            alert("Please input asset_category name th");
            document.getElementById("asset_category_name_th").focus();
            return false;
        }else if(asset_category_name_en.length == 0){
            alert("Please input asset_category name en ");
            document.getElementById("asset_category_name_en").focus();
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
        <h1 class="page-header">Category Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset_category" class="btn btn-primary active btn-menu">หมวดหมู่อุปกรณ์/Category</a>
        <!-- <a href="?app=asset_category_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a> -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">   
            <div class="panel-heading">
            แก้ไขรายการหมวดหมู่อุปกรณ์ / Edit Category List 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=asset_category&action=edit" >
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>รหัสหมวดหมู่อุปกรณ์ / Category Code <font color="#F00"><b>*</b></font></label>
                                <input id="code_check" name="code_check"type="hidden" value="<?php echo $asset['asset_category_code']; ?>" />
                                <input id="asset_category_id" name="asset_category_id"type="hidden" value="<?php echo $asset['asset_category_id']; ?>" />
                                <input id="asset_category_code" name="asset_category_code" value="<?php echo $asset['asset_category_code']; ?>" class="form-control" onchange="" />
                                
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อหมวดหมู่อุปกรณ์ TH/ Category Name TH<font color="#F00"><b>*</b></font></label>
                                    <input id="asset_category_name_th" name="asset_category_name_th"value="<?php echo $asset['asset_category_name_th'];?>" class="form-control">
                                    <p class="help-block">Example : บัญชี.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อหมวดหมู่อุปกรณ์ EN/ Category Name EN <font color="#F00"><b>*</b></font></label>
                                    <input id="asset_category_name_en" name="asset_category_name_en" value="<?php echo $asset['asset_category_name_en'];?>" class="form-control">
                                    <p class="help-block">Example : Accounting.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=asset_category" class="btn btn-default">Back</a>
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