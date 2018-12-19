
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">ล็อกงวดบัญชี</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<form id="form_target_1" role="form" method="post" action="index.php?app=paper_lock&action=generate">
    <div class="row"> 
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <label>วันที่เริ่มรอบบัญชี :</label>
                <div class="row"> 
                    <div class="col-lg-8 col-md-8">
                        <input name="date_start" type="text" class="form-control calendar" value="<?PHP echo $date_start?>" /> 
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <button type="button" onclick="check_login('form_target_1');" class="btn btn-success" onclick="" > สร้างรอบบัญชี </button> 
                    </div>
                </div>
            </div>
        </div> 
    </div>
</form>

<form id="form_target_2" role="form" method="post" action="index.php?app=paper_lock&action=update">
<div class="row">
    <div class="col-lg-12 col-md-12">
        <table width="100%" class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th colspan="4">รอบบัญชีปัจจุบัน</th> 
                    <th colspan="4">รอบบัญชีใหม่</th>
                </tr>
                <tr>
                    <th width="140px">ผู้กรอกข้อมูล</th>
                    <th width="140px">ผู้ตรวจสอบบัญชี</th> 
                    <th width="72px">ลำดับ</th> 
                    <th >วันสิ้นงวด</th>
                    <th width="140px">ผู้กรอกข้อมูล</th>
                    <th width="140px">ผู้ตรวจสอบบัญชี</th> 
                    <th width="72px">ลำดับ</th> 
                    <th >วันสิ้นงวด</th>
                </tr>
            </thead>
            <tbody>
            <?PHP for($i=0; $i < 12 ; $i++ ){ ?>
                <tr>
                    <td><input name="paper_lock_1[]" type="checkbox" value="<?PHP echo $paper_locks[$i]['paper_lock_id']; ?>" <?PHP if($paper_locks[$i]['paper_lock_1'] == "1"){ ?> CHECKED <?PHP } ?> /></td> 
                    <td><input name="paper_lock_2[]" type="checkbox" value="<?PHP echo $paper_locks[$i]['paper_lock_id']; ?>" <?PHP if($paper_locks[$i]['paper_lock_2'] == "1"){ ?> CHECKED <?PHP } ?> /></td> 
                    <td><?PHP echo $i + 1 ;?></td>
                    <td> 
                        <?PHP echo $paper_locks[$i]["paper_lock_date"] ;?>
                    </td>

                    <td><input name="paper_lock_1[]" type="checkbox" value="<?PHP echo $paper_locks[$i+12]['paper_lock_id']; ?>" <?PHP if($paper_locks[$i+12]['paper_lock_1'] == "1"){ ?> CHECKED <?PHP } ?> /></td> 
                    <td><input name="paper_lock_2[]" type="checkbox" value="<?PHP echo $paper_locks[$i+12]['paper_lock_id']; ?>" <?PHP if($paper_locks[$i+12]['paper_lock_2'] == "1"){ ?> CHECKED <?PHP } ?> /></td> 
                    <td><?PHP echo $i + 13 ;?></td>
                    <td> 
                    <?PHP echo $paper_locks[$i+12]["paper_lock_date"] ;?>
                    </td>

                </tr>
            <?PHP } ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row"> 
    <div class="col-lg-12 col-md-12" align="right">
        <button type="button" onclick="check_login('form_target_2');" class="btn btn-success" onclick="" > บันทึก </button> 
    </div>
</div>
</form>
