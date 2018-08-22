<script>
    function check(){   
        var bank_code = document.getElementById("bank_code").value;
        var bank_name = document.getElementById("bank_name").value;
       
        bank_code = $.trim(bank_code);
        bank_name = $.trim(bank_name);
        
        if(bank_code.length == 0){
            alert("Please input bank code");
            document.getElementById("bank_code").focus();
            return false;
        }else if(bank_name.length == 0){
            alert("Please input bank name");
            document.getElementById("bank_name").focus();
            return false;
        }else{
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
                            ธนาคาร / Bank
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="post" onsubmit="return check();" <?php if($bank_id == ''){ ?>action="index.php?app=bank&action=add"<?php }else{?> action="index.php?app=bank&action=edit" <?php }?> enctype="multipart/form-data">
                                <input type="hidden" id="bank_id" name="bank_id" value="<?php echo $bank_id?>"/>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>รหัสธนาคาร <font color="#F00"><b>*</b></font></label>
                                            <input id="bank_code" name="bank_code"  class="form-control" value="<? echo $bank['bank_code'];?>">
                                            <p class="help-block">Example : C1.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>ชื่อ<font color="#F00"><b>*</b></font></label>
                                            <input id="bank_name" name="bank_name"  class="form-control" value="<? echo $bank['bank_name'];?>">
                                            <p class="help-block">Example : BLL.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>รายละเอียด<font color="#F00"><b>*</b></font></label>
                                            <input id="bank_detail" name="bank_detail"  class="form-control" value="<? echo $bank['bank_detail'];?>">
                                            <p class="help-block">Example : 35-2564-191-1.</p>
                                        </div>
                                    </div>
                                    
                                   
                                </div>    
                                <div class="row">
                                    <div class="col-lg-offset-9 col-lg-3" align="right">
                                        <a href="?app=bank&action=view" class="btn btn-primary">Reset</a>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                                <br>
                            </form>



                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ลำดับ <br>No.</th>
                                        <th>รหัสธนาคาร</th>
                                        <th>ชื่อ</th>
                                        <th>รายละเอียด</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($banks); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $banks[$i]['bank_code']; ?></td>
                                        <td><?php echo $banks[$i]['bank_name']; ?></td>
                                        <td><?php echo $banks[$i]['bank_detail']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=bank&action=update&id=<?php echo $banks[$i]['bank_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=bank&action=delete&id=<?php echo $banks[$i]['bank_id'];?>" onclick="return confirm('You want to delete customer Bank : <?php echo $banks[$i]['bank_name']; ?>');" style="color:red;">
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
            
            
