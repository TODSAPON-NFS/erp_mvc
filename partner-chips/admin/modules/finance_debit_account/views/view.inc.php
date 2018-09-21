<script>
    function check(){   
        var finance_debit_account_code = document.getElementById("finance_debit_account_code").value;
        var finance_debit_account_name = document.getElementById("finance_debit_account_name").value; 
       
        finance_debit_account_code = $.trim(finance_debit_account_code);
        finance_debit_account_name = $.trim(finance_debit_account_name);
        
       if(finance_debit_account_code.length == 0){
            alert("Please input Received code");
            document.getElementById("finance_debit_account_code").focus();
            return false;
        }else  if(finance_debit_account_name.length == 0){
            alert("Please input Received name");
            document.getElementById("finance_debit_account_name").focus();
            return false;
        }else{
            return true;
        }
    }
</script>


<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Received Setting</h1>
    </div>
   
    <!-- /.col-lg-12 -->
</div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        กำหนดวิธีการรับชำระหนี้ / Received Setting
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return check();" <?php if($finance_debit_account_id == ''){ ?>action="index.php?app=finance_debit_account&action=add"<?php }else{?> action="index.php?app=finance_debit_account&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="finance_debit_account_id" name="finance_debit_account_id" value="<?php echo $finance_debit_account_id?>"/>
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>รหัสวิธีการรับชำระหนี้ <font color="#F00"><b>*</b></font></label>
                                            <input id="finance_debit_account_code" name="finance_debit_account_code"  class="form-control" value="<? echo $finance_debit_account['finance_debit_account_code'];?>">
                                            <p class="help-block">Example : C1.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label>ชื่อวิธีการรับชำระหนี้<font color="#F00"><b>*</b></font></label>
                                            <input id="finance_debit_account_name" name="finance_debit_account_name"  class="form-control" value="<? echo $finance_debit_account['finance_debit_account_name'];?>">
                                            <p class="help-block">Example : เช็ครับ (Cheque).</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <label>ประเภทเช็ค<font color="#F00"><b>*</b></font></label>
                                            <select id="finance_debit_account_cheque" name="finance_debit_account_cheque" class="form-control">
                                                <option value="0" <?PHP if($finance_debit_account['finance_debit_account_cheque'] == "0"){ ?> selected <?PHP } ?> >ไม่ใช่</option>
                                                <option value="1" <?PHP if($finance_debit_account['finance_debit_account_cheque'] == "1"){ ?> selected <?PHP } ?> >ใช่</option>
                                            </select>    
                                            <p class="help-block">Example : ไม่ใช่.</p>
                                        </div>
                                    </div> 
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>ธนาคาร </label>
                                            <select id="bank_account_id" name="bank_account_id" class="form-control select" data-live-search="true" >
                                                <option value="">เลือก / Select</option>
                                                <?PHP 
                                                    for($i=0; $i < count($bank_account) ; $i++){
                                                ?>
                                                    <option value="<?PHP echo $bank_account[$i]['bank_account_id'];?>" <?PHP if($bank_account[$i]['bank_account_id'] == $finance_debit_account['bank_account_id'] ){ ?> SELECTED <? } ?> >[<?PHP echo $bank_account[$i]['bank_account_code'];?>] <?PHP echo $bank_account[$i]['bank_account_name'];?></option>
                                                <?PHP
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : [C1] ธนาคารกรุงเทพ.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>ประเภทบัญชี </label>
                                            <select id="account_id" name="account_id"  class="form-control select" data-live-search="true" >
                                                <option value="">เลือก / Select</option>
                                                <?PHP 
                                                    for($i=0; $i < count($account) ; $i++){
                                                ?>
                                                    <option value="<?PHP echo $account[$i]['account_id'];?>" <?PHP if($account[$i]['account_id'] == $finance_debit_account['account_id'] ){ ?> SELECTED <? } ?> ><?PHP echo $account[$i]['account_code'];?> <?PHP echo $account[$i]['account_name_th'];?></option>
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
                                        <a href="?app=finance_debit_account&action=view" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>รหัสวิธีการรับชำระหนี้</th>
                                        <th>ชื่อวิธีการรับชำระหนี้</th>
                                        <th>ประเภทเช็ค</th>
                                        <th>ธนาคาร</th> 
                                        <th>ประเภทบัญชี</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($finance_debit_accounts); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $finance_debit_accounts[$i]['finance_debit_account_code']; ?></td>
                                        <td><?php echo $finance_debit_accounts[$i]['finance_debit_account_name']; ?></td>
                                        <td><?PHP if($finance_debit_accounts[$i]['finance_debit_account_cheque'] == "0"){ ?> ไม่ใช่ <?PHP } else { ?> ใช่ <?PHP } ?></td>
                                        <td><?PHP if($finance_debit_accounts[$i]['bank_account_code'] != ""){ ?>[<?php echo $finance_debit_accounts[$i]['bank_account_code']; ?>] <?PHP } ?> <?php echo $finance_debit_accounts[$i]['bank_account_name']; ?></td> 
                                        <td><?PHP if($finance_debit_accounts[$i]['account_code'] != ""){ ?>[<?php echo $finance_debit_accounts[$i]['account_code']; ?>] <?PHP } ?> <?php echo $finance_debit_accounts[$i]['account_name_th']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=finance_debit_account&action=update&id=<?php echo $finance_debit_accounts[$i]['finance_debit_account_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=finance_debit_account&action=delete&id=<?php echo $finance_debit_accounts[$i]['finance_debit_account_id'];?>" onclick="return confirm('You want to delete customer Bank Account : <?php echo $finance_debit_accounts[$i]['finance_debit_account_name']; ?>');" style="color:red;">
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
            
            
