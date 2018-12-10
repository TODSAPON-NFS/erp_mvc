<script>
    function search(){ 
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 

        window.location = "index.php?app=report_stock_03&date_start="+date_start+"&date_end="+date_end ;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 

        window.open("print.php?app=report_stock_03&action="+type+"&date_start="+date_start+"&date_end="+date_end ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสรุปยอดเคลื่อนไหวสินค้า </h1>
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
                    รายงานสรุปยอดเคลื่อนไหวสินค้า  
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ช่วงวัน</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>รหัสคลัง</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="stock_start" name="stock_start" value="<?PHP echo $stock_start;?>"  class="form-control" />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="stock_end" name="stock_end" value="<?PHP echo $stock_end;?>"  class="form-control" />
                                </div>
                            </div>
                            <p class="help-block">01 - 99</p>
                        </div>
                    </div>   
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>สินค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="product_start" name="product_start" value="<?PHP echo $product_start;?>"  class="form-control" />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="product_end" name="product_end" value="<?PHP echo $product_end;?>"  class="form-control" />
                                </div>
                            </div>
                            <p class="help-block">0000-00 - 9999-99</p>
                        </div>
                    </div>   
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf','');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel','');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search('');">Search</button>
                        <a href="index.php?app=report_stock_02" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>  
                            <th width="16%" colspan="2" style="text-align:center;">ใบสำคัญ</th>     
                            <th align="center" width="28%" colspan="3" style="text-align:center;">รายการรับ</th>    
                            <th align="center" width="28%" colspan="3" style="text-align:center;">รายการจ่าย</th>    
                            <th align="center" width="28%" colspan="3" style="text-align:center;">คงเหลือ</th>    
                        </tr>
                        <tr>  
                            <th  style="text-align:center;" width="8%">วันที่<br></th>    
                            <th  style="text-align:center;" width="8%">เลขที่<br></th>    
                            <th  style="text-align:right;" width="8%">จำนวน</th>   
                            <th  style="text-align:right;" width="10%">ราคาต่อหน่วย</th>   
                            <th  style="text-align:right;" width="10%">มูลค่ารับ</th>  
                            <th  style="text-align:right;" width="8%">จำนวน</th>   
                            <th  style="text-align:right;" width="10%">ราคาต่อหน่วย</th>   
                            <th  style="text-align:right;" width="10%">มูลค่าจ่าย</th>  
                            <th  style="text-align:right;" width="8%">จำนวน</th>   
                            <th  style="text-align:right;" width="10%">ราคาต่อหน่วย</th>   
                            <th  style="text-align:right;" width="10%">มูลค่าคงเหลือ</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        $stock_report_qty = 0;
                        $stock_report_cost_avg = 0;
                        $stock_report_total = 0;

                        $stock_report_qty_sum = 0;
                        $stock_report_cost_avg_sum =  0; 
                        $stock_report_total_sum =  0; 

                        for($i=0; $i < count($stock_reports); $i++){ 


                            if( $stock_reports[$i-1]['product_name'] != $stock_reports[$i]['product_name']){ 
                                
                                ?>
                                <tr class="">
                                    <td colspan="11" >
                                    </td>
                                </tr>
                                <tr class="">
                                    <td colspan="2" >
                                        <b><?php echo $stock_reports[$i]['product_code']; ?>&nbsp;<?php echo $stock_reports[$i]['product_name']; ?></b>
                                    </td> 
                                    <td colspan="9" >
                                        <b style="color:blue;"><?php echo $stock_reports[$i]['product_code']; ?>&nbsp;<?php echo $stock_reports[$i]['product_name']; ?></b>
                                    </td> 
                                    
                                </tr>
                                
                                <?PHP
                            } 

                            $stock_report_qty +=  $stock_reports[$i]['stock_report_qty'];
                            $stock_report_cost_avg +=  $stock_reports[$i]['stock_report_cost_avg'];
                            $stock_report_total +=  $stock_reports[$i]['stock_report_total'];
                        ?>
                        <tr class="">
                            <td><?php echo $stock_reports[$i]['product_code'].' '.$stock_reports[$i]['product_name']; ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_qty'],0); ?> Pc.</td> 
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_cost_avg'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_total'],2); ?></td> 
                        </tr>
                        <?PHP





                            if($stock_reports[$i]['stock_group_name'] != $stock_reports[$i+1]['stock_group_name']){ 

                                $stock_report_qty_sum += $stock_report_qty;
                                $stock_report_cost_avg_sum +=  $stock_report_cost_avg; 
                                $stock_report_total_sum +=  $stock_report_total; 
                        ?>
                        <tr class="">
                            <td align="center" >
                               <b><font color="black"> ยอดคงเหลือ</font> </b>
                            </td> 
                            <td align="right"><b><font color="black"><?php echo number_format($stock_report_qty,0); ?> </font></b> </td>
                            <td align="right"><b><font color="black"><?php echo number_format($stock_report_cost_avg,2); ?> </font></b> </td> 
                            <td align="right"><b><font color="black"><?php echo number_format($stock_report_total,2); ?> </font></b> </td>  
                        </tr>
                        <?PHP  
                                $stock_report_qty = 0;
                                $stock_report_cost_avg = 0;
                                $stock_report_total = 0;
                            }




                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td align="center">รวม</td>
                            <td align="right" ><?php echo number_format($stock_report_qty_sum,0); ?></td>
                            <td align="right" ><?php echo number_format($stock_report_cost_avg_sum,2); ?></td>
                            <td align="right" ><?php echo number_format($stock_report_total_sum,2); ?></td> 
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
            
            
