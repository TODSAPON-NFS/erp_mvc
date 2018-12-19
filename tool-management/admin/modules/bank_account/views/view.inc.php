<script>
    function check(){   
        var bank_account_name = document.getElementById("bank_account_name").value; 
       
        bank_account_name = $.trim(bank_account_name); 
        
       if(bank_account_name.length == 0){
            alert("Please input category name");
            document.getElementById("bank_account_name").focus();
            return false;
        } else{
            return true;
        }
    }
</script>


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Bank Management</h1>
    </div>
   
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            บัญชีธนาคาร / Bank Account
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form  id="form_target" role="form" method="post" onsubmit="return check();" <?php if($bank_account_id == ''){ ?>action="index.php?app=bank_account&action=add"<?php }else{?> action="index.php?app=bank_account&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="bank_account_id" name="bank_account_id" value="<?php echo $bank_account_id?>"/>
                                <div class="row">
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>รหัสบัญชี <font color="#F00"><b>*</b></font></label>
                                            <input id="bank_account_code" name="bank_account_code"  class="form-control" value="<? echo $bank_account['bank_account_code'];?>">
                                            <p class="help-block">Example : C1.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>ชื่อย่อ<font color="#F00"><b>*</b></font></label>
                                            <input id="bank_account_title" name="bank_account_title"  class="form-control" value="<? echo $bank_account['bank_account_title'];?>">
                                            <p class="help-block">Example : BLL.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>สมุดบัญชีเลขที่<font color="#F00"><b>*</b></font></label>
                                            <input id="bank_account_number" name="bank_account_number"  class="form-control" value="<? echo $bank_account['bank_account_number'];?>">
                                            <p class="help-block">Example : 35-2564-191-1.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>ชื่อบัญชีเงินฝาก<font color="#F00"><b>*</b></font></label>
                                            <input id="bank_account_name" name="bank_account_name"  class="form-control" value="<? echo $bank_account['bank_account_name'];?>">
                                            <p class="help-block">Example : บัญชีเงินฝากกระแสรายวัน กรุงเทพ 9999.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>สาขา<font color="#F00"><b>*</b></font></label>
                                            <input id="bank_account_branch" name="bank_account_branch"  class="form-control" value="<? echo $bank_account['bank_account_branch'];?>">
                                            <p class="help-block">Example : รามอินทรา.</p>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>ประเภทบัญชี / Account type </label>
                                            <select id="account_id" name="account_id" class="form-control">
                                                <option value="">เลือก / Select</option>
                                                <?PHP 
                                                    for($i=0; $i < count($account) ; $i++){
                                                ?>
                                                    <option value="<?PHP echo $account[$i]['account_id'];?>" <?PHP if($account[$i]['account_id'] == $bank_account['account_id'] ){ ?> SELECTED <? } ?> ><?PHP echo $account[$i]['account_code'];?> <?PHP echo $account[$i]['account_name_th'];?></option>
                                                <?PHP
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : 2120-01.</p>
                                        </div>
                                    </div>
                                   
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=bank_account&action=view" class="btn btn-primary">Reset</a>
                                        <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>รหัสบัญชี</th>
                                        <th>ชื่อย่อ</th>
                                        <th>สมุดบัญชีเลขที่</th>
                                        <th>ชื่อบัญชีเงินฝาก</th>
                                        <th>สาขา</th>
                                        <th>ประเภทบัญชี</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($bank_accounts); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $bank_accounts[$i]['bank_account_code']; ?></td>
                                        <td><?php echo $bank_accounts[$i]['bank_account_title']; ?></td>
                                        <td><?php echo $bank_accounts[$i]['bank_account_number']; ?></td>
                                        <td><?php echo $bank_accounts[$i]['bank_account_name']; ?></td>
                                        <td><?php echo $bank_accounts[$i]['bank_account_branch']; ?></td>
                                        <td><?php echo $bank_accounts[$i]['account_name_th']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=bank_account&action=update&id=<?php echo $bank_accounts[$i]['bank_account_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=bank_account&action=delete&id=<?php echo $bank_accounts[$i]['bank_account_id'];?>" onclick="return confirm('You want to delete customer Bank Account : <?php echo $bank_accounts[$i]['bank_account_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>
                           
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
