

<script>
    function update_paper(id){
        var paper_id = $(id).closest('tr').children('td').children('input[name="paper_id"]').val();
        var paper_type_id = $(id).closest('tr').children('td').children('input[name="paper_type_id"]').val();
        var paper_code = $(id).closest('tr').children('td').children('input[name="paper_code"]').val();
        var paper_name_th = $(id).closest('tr').children('td').children('input[name="paper_name_th"]').val();
        var paper_name_en = $(id).closest('tr').children('td').children('input[name="paper_name_en"]').val();
        var journal_id = $(id).closest('tr').children('td').children('input[name="journal_id"]').val();
        var journal_description = $(id).closest('tr').children('td').children('input[name="journal_description"]').val();
        var paper_lock = $(id).closest('tr').children('td').children('input[name="paper_lock"]').val(); 

        $("#paper_id").val(paper_id);
        $("#paper_type_id").val(paper_type_id);
        $("#paper_type").val(paper_type_id);
        $("#paper_code").val(paper_code);
        $("#paper_name_th").val(paper_name_th);
        $("#paper_name_en").val(paper_name_en);
        $("#journal_id").val(journal_id);
        $("#journal").val(journal_id);
        $("#journal_description").val(journal_description);
        $("#paper_lock").val(paper_lock);

        

        $('#modalAdd').modal('show');
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">กำหนดเลขที่เอกสาร</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="col-md-12">
                    รายการเอกสาร
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table width="100%"  class="table table-striped table-bordered table-hover" >
                                <thead>
                                    <th style="width:80px;">ลำดับ</th>
                                    <th style="width:180px;">หมวด</th>
                                    <th style="">ชื่อเอกสาร</th>
                                    <th style="">ชื่อภาษาอังกฤษ</th>
                                    <th style="">เลขที่เอกสาร</th>
                                    <th style="">สมุดรายวัน</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                <?php  for($index =  0 ; $index < count($papers) ; $index++){ ?>
                                    <tr class="odd gradeX">
                                        <td align="center">
                                            <?PHP echo $index + 1; ?>
                                        </td>
                                        <td >
                                            <?PHP echo $papers[$index]['paper_type_name']; ?>
                                        </td>
                                        <td >
                                            <?PHP echo $papers[$index]['paper_name_th']; ?>
                                        </td>
                                        <td >
                                            <?PHP echo $papers[$index]['paper_name_en']; ?>
                                        </td>
                                        <td >
                                            <?PHP echo $papers[$index]['paper_code']; ?>
                                        </td>
                                        <td >
                                            <?PHP echo $papers[$index]['journal_name']; ?>
                                        </td>
                                        <td >
                                            <input type="hidden" name="paper_id" value="<?PHP echo $papers[$index]['paper_id']; ?>" />
                                            <input type="hidden" name="paper_type_id" value="<?PHP echo $papers[$index]['paper_type_id']; ?>" />
                                            <input type="hidden" name="paper_code" value="<?PHP echo $papers[$index]['paper_code']; ?>" />
                                            <input type="hidden" name="paper_name_th" value="<?PHP echo $papers[$index]['paper_name_th']; ?>" /> 
                                            <input type="hidden" name="paper_name_en" value="<?PHP echo $papers[$index]['paper_name_en']; ?>" />
                                            <input type="hidden" name="journal_id" value="<?PHP echo $papers[$index]['journal_id']; ?>" />
                                            <input type="hidden" name="journal_description" value="<?PHP echo $papers[$index]['journal_description']; ?>" />
                                            <input type="hidden" name="paper_lock" value="<?PHP echo $papers[$index]['paper_lock']; ?>" />
                                            <a href="javascript:;" onclick="update_paper(this)"  title="แก้เอกสาร">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?PHP } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div> 


<!-- /.row -->
<form role="form" method="post"  id="form_target"  action="index.php?app=paper&action=edit"  enctype="multipart/form-data">
    <div id="modalAdd" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">แก้ไขเอกสาร</h4>
            </div>

            <div  class="modal-body">
                <div class="row"> 
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>หมวด <font color="#F00"><b>*</b></font> </label>
                            <input type="hidden" name="paper_id" id="paper_id" value="0" />
                            <input type="hidden" id="paper_type_id" name="paper_type_id" value="" >

                            <select id="paper_type" name="paper_type" class="form-control"   DISABLED > 
                                <?php 
                                for($i =  0 ; $i < count($paper_types) ; $i++){
                                ?>
                                <option  value="<?php echo $paper_types[$i]['paper_type_id'] ?>"><?php echo $paper_types[$i]['paper_type_name'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : -.</p>
                        </div>
                    </div>
                </div>
                
                <div class="row"> 
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>บัญทึกบัญชี ลงสมุดรายวัน <font color="#F00"><b>*</b></font> </label>
                            <input id="journal_id" name="journal_id" type="hidden" value="" >
                            <select id="journal" name="journal" class="form-control"   DISABLED > 
                                <?php 
                                for($i =  0 ; $i < count($journals) ; $i++){
                                ?>
                                <option  value="<?php echo $journals[$i]['journal_id'] ?>"><?php echo $journals[$i]['journal_name'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : -.</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>คำอธิบายในสมุดรายวัน <font color="#F00"><b>*</b></font> </label>
                            <input id="journal_description" name="journal_description" class="form-control" value="" >
                            <p class="help-block">Example : -.</p>
                        </div>
                    </div>
                </div>

                <div class="row"> 
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ชื่อเอกสาร <font color="#F00"><b>*</b></font> </label>
                            <input id="paper_name_th" name="paper_name_th" class="form-control" value="" >
                            <p class="help-block">Example : -.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>ชื่อภาษาอังกฤษ <font color="#F00"><b>*</b></font> </label>
                            <input id="paper_name_en" name="paper_name_en" class="form-control" value="" >
                            <p class="help-block">Example : -.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>รหัสเอกสาร <font color="#F00"><b>*</b></font> </label>
                            <input id="paper_code" name="paper_code" class="form-control" value="" >
                            <p class="help-block">Example : -.</p>
                        </div>
                    </div>
                </div>
           
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="check_login('form_target');" class="btn btn-success">Save</button>
            </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</form>
            
