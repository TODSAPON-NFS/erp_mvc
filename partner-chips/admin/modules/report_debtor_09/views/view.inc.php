<script>
    function search(){  
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var view_type = $("#view_type").val();

        window.location = "index.php?app=report_debtor_09&code_start="+code_start+"&code_end="+code_end+"&view_type="+view_type;
    }
    function print(type){ 
        var code_start = $("#code_start").val();
        var code_end = $("#code_end").val();
        var view_type = $("#view_type").val();

        window.open("print.php?app=report_debtor_09&action="+type+"&code_start="+code_start+"&code_end="+code_end+"&view_type="+view_type,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานรายละเอียดลูกค้า</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                    รายงานรายละเอียดลูกค้า
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>รหัสลูกค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="code_start" name=code_start" value="<?PHP echo $code_start;?>"  class="form-control " />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="code_end" name="code_end" value="<?PHP echo $code_end;?>"  class="form-control " />
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>แสดง <font color="#F00"><b>*</b></font></label>
                            <select id="view_type" name="view_type" class="form-control "  >
                                <option <?php if($view_type == ''){?> selected <?php }?> value="">แบบย่อ</option>
                                <option <?php if($view_type == 'full'){?> selected <?php }?> value="full">แบบละเอียด</option> 
                            </select>
                            <p class="help-block">Example : แบบย่อ.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_debtor_09" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="48" style="text-align: center;vertical-align: middle;"> ลำดับ</th>
                            <th style="text-align: center;vertical-align: middle;" >รหัส</th> 
                            <th style="text-align: center;vertical-align: middle;" >ชื่อภาษาไทย</th> 
                            <th style="text-align: center;vertical-align: middle;" >ชื่อภาษาอังกฤษ</th> 
                            <th style="text-align: center;vertical-align: middle;" >เลขผู้เสียภาษี</th>  
                            <th style="text-align: center;vertical-align: middle;" >เบอร์โทรศัพท์</th> 
                            <th style="text-align: center;vertical-align: middle;" >Fax.</th> 
                            <th style="text-align: center;vertical-align: middle;" >อีเมล</th>   
                        </tr> 
                    </thead>
                    <tbody>
                        <?php  
                        for($i=0; $i < count($debtor_reports); $i++){ 
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $debtor_reports[$i]['customer_code']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['customer_name_th']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['customer_name_en']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['customer_tax']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['customer_tel']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['customer_fax']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['customer_email']; ?></td> 
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
            
            
