<script>

function setFormat(id){
    var val =  parseFloat($(id).val().replace(',',''));

    if(isNaN(val)){
        val = 0;
    }

    $(id).val( val.toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") );

}
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Account Begin</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            บันทึกยอดบัญชียกมา / Account Begin
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post"  action="index.php?app=summit_account&action=edit"  enctype="multipart/form-data">
                    
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th width="150px" >เลขที่บัญชี<br>Account Code.</th>
                                <th>ชื่อบัญชี<br>Account Name</th>
                                <th width="200px">เครดิต<br>Credit</th>
                                <th width="200px">เดบิต<br>Debit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($accounts); $i++){
                            ?>
                            <tr class="odd gradeX" >
                                <td width="150px" align="center">
                                    <input type="hidden" name="account_id[]" value="<?PHP echo $accounts[$i]['account_id']; ?>" />
                                    <?php echo $accounts[$i]['account_code']; ?>
                                </td>
                                <td><?php echo $accounts[$i]['account_name_th']; ?></td>
                                <td width="200px" ><input type="text" name="account_credit_begin[]" onchange="setFormat(this);" style="text-align:right;" class="form-control" value="<?php echo number_format($accounts[$i]['account_credit_begin'],2); ?>" /></td>
                                <td width="200px" ><input type="text" name="account_debit_begin[]" onchange="setFormat(this);" style="text-align:right;" class="form-control" value="<?php echo number_format($accounts[$i]['account_debit_begin'],2); ?>" /></td>
                            </tr>
                        <?
                            }
                        ?>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=summit_account&action=view" class="btn btn-primary">Reset</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <br>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
