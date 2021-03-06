<script>
    function search(){ 
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var view_type = $("#view_type").val();

        window.location = "index.php?app=report_creditor_07&date_end="+date_end+"&supplier_id="+supplier_id+"&view_type="+view_type;
    }
    function print(type){ 
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var view_type = $("#view_type").val();

        window.open("print.php?app=report_creditor_07&action="+type+"&date_end="+date_end+"&supplier_id="+supplier_id+"&view_type="+view_type,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานวิเคราะห์อายุเจ้าหนี้</h1>
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
                    รายงานวิเคราะห์อายุเจ้าหนี้
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ถึงวันที่</label>
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                            <p class="help-block">01-01-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ผู้ขาย </label>
                            <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>

                                <?php 
                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                ?>
                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> (<?php echo $suppliers[$i]['supplier_name_th'] ?>)</option>
                                <?
                                }
                                ?>

                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
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
                        <a href="index.php?app=report_creditor_07" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th rowspan="2" width="48" style="text-align: center;vertical-align: middle;"> ลำดับ</th> 
                            <th rowspan="2" style="text-align: center;vertical-align: middle;" >ผู้ขาย</th> 
                            <th rowspan="2" style="text-align: center;vertical-align: middle;" >จำนวนเอกสาร</th> 
                            <th colspan="3" style="text-align: center;vertical-align: middle;" >จะครบกำหนด</th>  
                            <th colspan="4" style="text-align: center;vertical-align: middle;" >เกินกำหนด</th> 
                            <th rowspan="2" style="text-align: center;vertical-align: middle;" >ยอดคงค้าง</th>   
                        </tr>
                        <tr>
                            <th style="text-align: center;vertical-align: middle;" >เกิน 60 วัน</th> 
                            <th style="text-align: center;vertical-align: middle;" >ภายใน 60 วัน</th> 
                            <th style="text-align: center;vertical-align: middle;" >ภายใน 30 วัน</th> 
                            <th style="text-align: center;vertical-align: middle;" >1 - 30 วัน</th>  
                            <th style="text-align: center;vertical-align: middle;" >31 - 60 วัน</th>  
                            <th style="text-align: center;vertical-align: middle;" >61 - 90 วัน</th>  
                            <th style="text-align: center;vertical-align: middle;" >เกิน 90 วัน</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $paper_number = 0;   
                        $due_comming_more_than_60 = 0;  
                        $due_comming_in_60 = 0;  
                        $due_comming_in_30 = 0;  
                        $over_due_1_to_30 = 0;  
                        $over_due_31_to_60 = 0;  
                        $over_due_61_to_90 = 0;  
                        $over_due_more_than_90 = 0;  
                        $balance = 0;  
                        for($i=0; $i < count($creditor_reports); $i++){
                            $paper_number +=  $creditor_reports[$i]['paper_number']; 
                            $due_comming_more_than_60 +=  $creditor_reports[$i]['due_comming_more_than_60'];  
                            $due_comming_in_60 +=  $creditor_reports[$i]['due_comming_in_60'];  
                            $due_comming_in_30 +=  $creditor_reports[$i]['due_comming_in_30'];  
                            $over_due_1_to_30 +=  $creditor_reports[$i]['over_due_1_to_30'];  
                            $over_due_31_to_60 +=  $creditor_reports[$i]['over_due_31_to_60'];  
                            $over_due_61_to_90 +=  $creditor_reports[$i]['over_due_61_to_90'];  
                            $over_due_more_than_90 +=  $creditor_reports[$i]['over_due_more_than_90'];  
                            $balance +=  $creditor_reports[$i]['balance'];  
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td>[<?php echo $creditor_reports[$i]['supplier_code']; ?>] <?php echo $creditor_reports[$i]['supplier_name_en']; ?></td>
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['paper_number'],0); ?>
                            </td>
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['due_comming_more_than_60'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['due_comming_in_60'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['due_comming_in_30'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['over_due_1_to_30'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['over_due_31_to_60'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['over_due_61_to_90'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['over_due_more_than_90'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['balance'],0); ?>
                            </td>  
                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" align="center"><b>รวม</b></td>
                            <td  align="right" ><b><?php echo number_format($paper_number,0); ?></b></td> 
                            <td  align="right" ><b><?php echo number_format($due_comming_more_than_60,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($due_comming_in_60,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($due_comming_in_30,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($over_due_1_to_30,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($over_due_31_to_60,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($over_due_61_to_90,2); ?></b></td>  
                            <td  align="right" ><b><?php echo number_format($over_due_more_than_90,2); ?></b></td>
                            <td  align="right" ><b><?php echo number_format($balance,2); ?></b></td>   
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
