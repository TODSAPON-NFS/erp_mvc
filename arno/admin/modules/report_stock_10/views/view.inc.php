<script>
    function search(){  

        var date_end = $("#date_end").val(); 
        var stock_group_id = $("#stock_group_id").val();  
        var keyword = encodeURIComponent($("#keyword").val()); 
        var product_end = encodeURIComponent($("#product_end").val()); 
  

        window.location = "index.php?app=report_stock_10&stock_group_id="+stock_group_id+"&keyword="+keyword;
    }
    function print(type){  
        var date_end = $("#date_end").val(); 
        var stock_group_id = $("#stock_group_id").val();  
        var keyword = encodeURIComponent($("#keyword").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  
        
        window.open("print.php?app=report_stock_10&action="+type+"&stock_group_id="+stock_group_id+"&keyword="+keyword,'_blank');
    }
</script>

<?php $stock_group_code = "" ; ?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสินค้าเคลื่อนไหวที่มีปัญหา </h1>
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
                    รายงานสินค้าเคลื่อนไหวที่มีปัญหา 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คลังสินค้า / Stock </label>
                            <select id="stock_group_id" name="stock_group_id" class="form-control select"  data-live-search="true"> 
                               <option  <?php if($stock_group_id == 0){?> selected <?php }?> value="0"> ทั้งหมด  </option> 
                                <?php 
                                for($i =  0 ; $i < count($stock_group) ; $i++){
                                ?>
                                <option <?php if($stock_group[$i]['stock_group_id'] == $stock_group_id){ $stock_group_code = $stock_group[$i]['stock_group_code']   ?> selected <?php }?> value="<?php echo $stock_group[$i]['stock_group_id'] ?>"><?php echo $stock_group[$i]['stock_group_name'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : - .</p>
                        </div>
                    </div>   
                    

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>สินค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="keyword" name="keyword" value="<?PHP echo $product_start;?>"  class="form-control" />
                                </div>
                            </div>
                            <p class="help-block">0000-00</p>
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
                            <th width="20%" >คลังสินค้า</th> 
                            <th width="20%" >วันที่</th> 
                            <th width="30%">รหัสสินค้า</th>                     
                            <th align="center">จำนวน</th>
                            <th>ราคาต่อหน่วย</th> 
                            <th>ราคารวม</th>  
                            <th>รายละเอียด</th>                        
                        </tr>
                    </thead>
                    
                    <tbody><?php $num = 1 ; ?> 
                        <?php for($i = 0 ; $i < count($stock_reports) ; $i++ ){ ?>
                            <tr>
                                <td> <?php echo $num ;?> </td> 
                                <td> <?php echo $stock_reports[$i]['stock_group_name'] ;?> </td>
                                <td> <?php echo $stock_reports[$i]['stock_date'] ;?></td>
                                <td> <?php echo $stock_reports[$i]['product_code']." - ".$stock_reports[$i]['product_name']  ;?></td>
                                <td align="right"> <?php echo $stock_reports[$i]['balance_qty'] ;?></td>
                                <td align="right"> <?php echo  number_format($stock_reports[$i]['balance_stock_cost_avg'],2);?> </td>
                                <td align="right"> <?php echo  number_format($stock_reports[$i]['balance_stock_cost_avg_total'],2);?> </td>         
                                <td > 
                                    <a href="?app=report_stock_03&date_start=<?php echo $stock_reports[$i]['stock_date'] ;?>&date_end=<?php echo $stock_reports[$i]['stock_date'] ;?>&stock_start=<?php echo $stock_reports[$i]['stock_group_code']; ?>&stock_end=&product_start=<?php echo $stock_reports[$i]['product_code']; ?>" style="color:#0045E6;">
                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>

                        <?php $num++;} ?>
                    </tbody>
                    <tfoot>
                   
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
