

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">กำหนดบัญชีที่ต้องลงรายวัน</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>


<?php  for($i =  0 ; $i < count($account_groups) ; $i++){ ?>

<!-- /.row -->
<form role="form"  id="form_target" method="post"  action="index.php?app=account_setting&action=edit"  enctype="multipart/form-data">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-12">
                    <?PHP echo $account_groups[$i]['account_group_name']; ?>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table width="100%"  class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <th style="width:180px;">รายการ</th>
                                    <th style="min-width:320px;">บัญชี</th>
                                </thead>
                                <tbody>
                                <?php  for($index =  0 ; $index < count($account_settings[$account_groups[$i]['account_group_id']]) ; $index++){ ?>
                                    <tr class="odd gradeX">
                                        <td style="width:180px;">
                                            <?PHP echo $account_settings[$account_groups[$i]['account_group_id']][$index]['account_setting_name']; ?>
                                        </td>
                                        <td style="min-width:320px;">
                                            <input type="hidden" name="account_setting_id[]" value="<?PHP echo $account_settings[$account_groups[$i]['account_group_id']][$index]['account_setting_id']; ?>" />
                                            <select  class="form-control select" name="account_id[]" data-live-search="true" >
                                                <option value="0">ไม่ระบุ</option>
                                                <?php 
                                                for($ii =  0 ; $ii < count($accounts) ; $ii++){
                                                ?>
                                                <option <?php if($accounts[$ii]['account_id'] == $account_settings[$account_groups[$i]['account_group_id']][$index]['account_id']){?> selected <?php }?> value="<?php echo $accounts[$ii]['account_id'] ?>">[<?php echo $accounts[$ii]['account_code'] ?>] <?php echo $accounts[$ii]['account_name_th'] ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?PHP } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=account_setting&action=view" class="btn btn-primary">Reset</a>
                            <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <br>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</form>
<?PHP } ?>


            
