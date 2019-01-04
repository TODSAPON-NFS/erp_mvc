<script>
    function search(){  
        var stock_start = $("#stock_start").val(); 
        var stock_end = $("#stock_end").val();  
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  

        window.location = "index.php?app=report_stock_01&stock_start="+stock_start+"&stock_end="+stock_end+"&product_start="+product_start+"&product_end="+product_end ;
    }
    function print(type){  
        var stock_start = $("#stock_start").val(); 
        var stock_end = $("#stock_end").val();  
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  

        window.open("print.php?app=report_stock_01&action="+type+"&stock_start="+stock_start+"&stock_end="+stock_end+"&product_start="+product_start+"&product_end="+product_end ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสินค้าและวัตถุดิบ</h1>
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
                    รายงานสินค้าและวัตถุดิบ 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
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
                        <a href="index.php?app=report_stock_01" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th >รหัส/ชื่อสินค้า</th>  
                            <th align="center">จำนวน</th>
                            <th align="center">ราคาต่อหน่วย</th>
                            <th align="center">มูลค่าคงเหลือ</th>   
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


                            if( $stock_reports[$i-1]['stock_group_name'] != $stock_reports[$i]['stock_group_name']){ 
                                
                        ?>
                        <tr class="">
                            <td colspan="4" >
                            </td>
                        </tr>
                        <tr class="">
                            <td colspan="4" >
                                <b><?php echo $stock_reports[$i]['stock_group_name']; ?></b>
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
            
            
