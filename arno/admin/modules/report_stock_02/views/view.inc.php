<script>
    function search(){  

        var date_end = $("#date_end").val(); 
        var stock_group_id = $("#stock_group_id").val();  
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val()); 
        var status_qty  = $("#status_qty").val();

        window.location = "index.php?app=report_stock_02&date_end="+date_end+"&stock_group_id="+stock_group_id+"&product_start="+product_start+"&product_end="+product_end +"&status_qty="+status_qty;
    }
    function print(type){  
        var date_end = $("#date_end").val(); 
        var stock_group_id = $("#stock_group_id").val();  
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  
        var status_qty  = $("#status_qty").val();
        window.open("print.php?app=report_stock_02&action="+type+"&date_end="+date_end+"&stock_group_id="+stock_group_id+"&product_start="+product_start+"&product_end="+product_end+ "&status_qty="+status_qty,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสินค้าคงเหลือ</h1>
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
                    รายงานสินค้าคงเหลือ 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>ณ วันที่</label> 
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/> 
                            <p class="help-block">01-01-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คลังสินค้า / Stock </label>
                            <select id="stock_group_id" name="stock_group_id" class="form-control select"  data-live-search="true"> 
                            <option <?php if($stock_group[$i]['stock_group_id'] == $stock_group_id){?> selected <?php }?> value="0">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($stock_group) ; $i++){
                                ?>
                                <option <?php if($stock_group[$i]['stock_group_id'] == $stock_group_id){?> selected <?php }?> value="<?php echo $stock_group[$i]['stock_group_id'] ?>"><?php echo $stock_group[$i]['stock_group_name'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : - .</p>
                        </div>
                    </div>   
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>จำนวนสินค้า </label>
                            <select id="status_qty" name="status_qty" class="form-control select"  data-live-search="true"> 
                                
                                <option  value="0" <?php if($status_qty==0 ){?> selected  <?php } ?> > ทั้งหมด </option>
                                <option  value="1" <?php if($status_qty==1 ){?> selected  <?php } ?> > สินค้าที่ไม่ติดลบ </option>
                                <option  value="2" <?php if($status_qty==2 ){?> selected  <?php } ?> > สินค้าที่่ติดลบ </option>
                               
                            </select>
                            <p class="help-block">Example : - .</p>
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
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_stock_02" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th width="64px">ลำดับ</th>
                            <th >รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>  
                            <th align="center">จำนวน</th>
                            <?PHP /*?>
                            <th align="center">ราคาต่อหน่วย</th>
                            <th align="center">มูลค่าคงเหลือ</th>   
                            <?PHP */ ?>
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
                        if( count($stock_reports) == 0){

                        }else{ 
                            ///echo $stock_group_id;
                             if($stock_group_id ==0 ){?>

                                <tr class="">
                                    <td colspan="4" >
                                    </td>
                                </tr>
                                <tr class="">
                                <td colspan="4" >
                                <b><?php echo คลังสินค้าทั้งหมด; ?></b>
                                </td>    
                        </tr>
                        <?php }else{?>
                        <tr class="">
                        <td colspan="4" >
                        </td>
                        </tr>
                        <tr class="">
                        <td colspan="4" >
                        <b><?php echo $stock_reports[$i]['stock_group_name']; ?></b>
                        </td>    
                        </tr>
                    
                  <?php }}

                        for($i=0; $i < count($stock_reports); $i++){ 


                            if( $stock_reports[$i-1]['stock_group_name'] != $stock_reports[$i]['stock_group_name']){ 
                                $stock_report_qty = 0;
                                $stock_report_cost_avg = 0;
                                $stock_report_total = 0;    
                        ?>
                       
                        
                        <?PHP
                            } 

                            $stock_report_qty +=  $stock_reports[$i]['stock_report_qty'];
                            $stock_report_cost_avg +=  $stock_reports[$i]['stock_report_cost_avg'];
                            $stock_report_total +=  $stock_reports[$i]['stock_report_total'];
                        ?>
                        <tr class="">
                            <td><?php echo number_format(($i + 1),0); ?></td>
                            <td><?php echo $stock_reports[$i]['product_code']; ?></td>
                            <td><?php echo $stock_reports[$i]['product_name']; ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_qty'],0); ?> Pc.</td> 
                            <?PHP /* ?>
                            <td align="right"><?php if ($stock_reports[$i]['stock_report_qty'] != 0){ echo number_format($stock_reports[$i]['stock_report_cost_avg'],2); }else{ echo 0;} ?></td>
                            <td align="right"><?php if ($stock_reports[$i]['stock_report_qty'] != 0){ echo number_format($stock_reports[$i]['stock_report_total'],2); }else{ echo 0;} ?></td> 
                            <?PHP */ ?>
                        </tr>
                        <?PHP





                            if($stock_reports[$i]['stock_group_name'] != $stock_reports[$i+1]['stock_group_name']){ 

                                $stock_report_qty_sum += $stock_report_qty;
                                $stock_report_cost_avg_sum +=  $stock_report_cost_avg; 
                                $stock_report_total_sum +=  $stock_report_total; 
                        ?>
                        <?PHP /* ?>
                        <tr class="">
                            
                            <td align="center" colspan="3">
                               <b><font color="black"> ยอดคงเหลือ</font> </b>
                            </td> 
                            <td align="right"><b><font color="black">  </font></b> </td>
                            
                            <td align="right"><b><font color="black">  </font></b> </td> 
                            <td align="right"><b><font color="black"><?php echo number_format($stock_report_total,2); ?> </font></b> </td>  
                            
                        </tr>
                        <?PHP */ ?>
                        <?PHP  
                                $stock_report_qty = 0;
                                $stock_report_cost_avg = 0;
                                $stock_report_total = 0;
                            }




                        }
                        ?>
                    </tbody>
                    <tfoot>
                    <?PHP /* ?>
                        <tr>
                            
                            <td align="center" colspan="3">รวม</td>
                            <td align="right" > </td>
                            
                            <td align="right" > </td>
                            <td align="right" ><?php echo number_format($stock_report_total_sum,2); ?></td> 
                            
                        </tr>
                        <?PHP */ ?>
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
