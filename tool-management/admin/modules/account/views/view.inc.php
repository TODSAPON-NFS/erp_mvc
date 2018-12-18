<script>
    function check(){   
        var account_name = document.getElementById("account_name").value;
        var account_detail = document.getElementById("account_detail").value;
       
        account_name = $.trim(account_name);
        account_detail = $.trim(account_detail);
        
       if(account_name.length == 0){
            alert("Please input logistic name");
            document.getElementById("account_name").focus();
            return false;
        }else  if(account_detail.length == 0){
            alert("Please input detail name english");
            document.getElementById("account_detail").focus();
            return false;
        }else{
            return true;
        }
    }

    function addAccount(account_control, account_level){
        $("#account_control").val(account_control);
        $("#account_level").val(parseInt(account_level)+1);
    }
</script>


<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Account Structure</h1>
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
                            <div class="col-md-8">
                            บัญชั / Product Group
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-success " style="float:right;" href="?app=account&action=view&id=0" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <?PHP 
                                        generateTree($accounts);
                                    ?>
                                </div>

                                <div class="col-lg-4">
                                    <?PHP if($account_id != ""){?>
                                    <form  id="form_target" role="form" method="post" onsubmit="return check();" <?php if($_GET['action'] == 'view' || $_GET['action'] == ''){ ?>action="index.php?app=account&action=add"<?php }else{?> action="index.php?app=account&action=edit&id=<?PHP echo $account_id;?>" <?php }?> enctype="multipart/form-data">
                                        <input type="hidden" id="account_id" name="account_id" value="<?php echo $account_id?>"/>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>เลขที่บัญชี<font color="#F00"><b>*</b></font></label>
                                                    <input id="account_code" name="account_code"  class="form-control" value="<? echo $account['account_code'];?>">
                                                    <p class="help-block">Example : 10000.</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>ชื่อไทย<font color="#F00"><b>*</b></font></label>
                                                    <input id="account_name_th" name="account_name_th" class="form-control" value="<? echo $account['account_name_th'];?>">
                                                    <p class="help-block">Example : -.</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>ชื่ออังกฤษ<font color="#F00"><b>*</b></font></label>
                                                    <input id="account_name_en" name="account_name_en" class="form-control" value="<? echo $account['account_name_en'];?>">
                                                    <p class="help-block">Example : -.</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>บัญชีควบคุม<font color="#F00"><b>*</b></font></label>

                                                    <input id="account_control" name="account_control" type="hidden" value="<?php if($_GET['action'] == 'view' || $_GET['action'] == ''){ echo $account['account_id']; } else { echo $account['account_control']; }?>" readonly />
                                                    <input  class="form-control" value="<?php  echo $account['account_code']; ?>" readonly />
                                                    <p class="help-block">Example : -.</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>ระดับบัญชี<font color="#F00"><b>*</b></font></label>
                                                    <input id="account_level" name="account_level" class="form-control" value="<?php if($_GET['action'] == 'view' || $_GET['action'] == ''){ echo $account['account_level']+1; } else { echo $account['account_level']; }?>"  readonly />
                                                    <p class="help-block">Example : -.</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>หมวดบัญชี<font color="#F00"><b>*</b></font></label>
                                                    <select id="account_group" name="account_group" class="form-control">
                                                        <option value="1" <?PHP if($account["account_group"] == '1'){?> SELECTED <? } ?> >ทรัพย์สิน<option>
                                                        <option value="2" <?PHP if($account["account_group"] == '2'){?> SELECTED <? } ?> >หนี้สิน<option>
                                                        <option value="3" <?PHP if($account["account_group"] == '3'){?> SELECTED <? } ?> >ทุน<option>
                                                        <option value="4" <?PHP if($account["account_group"] == '4'){?> SELECTED <? } ?> >รายได้<option>
                                                        <option value="5" <?PHP if($account["account_group"] == '5'){?> SELECTED <? } ?> >ค่าใช้จ่าย<option>
                                                    </select>
                                                    <p class="help-block">Example : -.</p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>ประเภทบัญชี<font color="#F00"><b>*</b></font></label>
                                                    <select id="account_type" name="account_type" class="form-control">
                                                        <option value="0" <?PHP if($account["account_type"] == '0'){?> SELECTED <? } ?> >บัญชีย่อย</option>
                                                        <option value="1" <?PHP if($account["account_type"] == '1'){?> SELECTED <? } ?> >บัญชีควบคุม</option>
                                                    </select>
                                                    <p class="help-block">Example : -.</p>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="row">
                                            <div class="col-lg-offset-6 col-lg-6" align="right">
                                                <a href="?app=account&action=view" class="btn btn-primary">Reset</a>
                                                <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                        <br>
                                    </form>
                                    <?PHP } ?>
                                </div>
                                
                            </div>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
