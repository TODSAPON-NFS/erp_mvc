<script>
    function search(){  
        var product_category_id = $("#product_category_id").val(); 
        var product_type_id = $("#product_type_id").val();  
        var product_start = $("#product_start").val(); 
        var product_end = $("#product_end").val();  

        window.location = "index.php?app=report_stock_05&product_category_id="+product_category_id+"&product_type_id="+product_type_id+"&product_start="+product_start+"&product_end="+product_end ;
    }
    function print(type){  
        var product_category_id = $("#product_category_id").val(); 
        var product_type_id = $("#product_type_id").val();  
        var product_start = $("#product_start").val(); 
        var product_end = $("#product_end").val();  

        window.open("print.php?app=report_stock_05&action="+type+"&product_category_id="+product_category_id+"&product_type_id="+product_type_id+"&product_start="+product_start+"&product_end="+product_end ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานราคาขายสินค้า</h1>
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
                    รายงานราคาขายสินค้า 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>รหัสสินค้า / Product Code </label>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ลักษณะ / Category </label>
                            <select id="product_category_id" name="product_category_id" class="form-control" >
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($product_category) ; $i++){
                                ?>
                                <option <?php if($product_category[$i]['product_category_id'] == $product_category_id){?> selected <?php }?> value="<?php echo $product_category[$i]['product_category_id'] ?>"><?php echo $product_category[$i]['product_category_name'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : - .</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ประเภท / Type </label>
                            <select id="product_type_id" name="product_type_id" class="form-control" >
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($product_type) ; $i++){
                                ?>
                                <option <?php if($product_type[$i]['product_type_id'] == $product_type_id){?> selected <?php }?> value="<?php echo $product_type[$i]['product_type_id'] ?>"><?php echo $product_type[$i]['product_type_name'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : - .</p>
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
                        <a href="index.php?app=report_stock_05" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th width="5%" >No.</th>  
                            <th width="23%" >รหัสสินค้า</th>  
                            <th align="23%">ชื่อสินค้า </th>
                            <th width="7%" align="">พิเศษ</th>
                            <th width="7%" align="">ตัวแทน</th>   
                            <th width="7%" align="">ผู้จำหน่าย</th>   
                            <th width="7%" align="">องค์กร</th>   
                            <th width="7%" align="">ใหญ่</th>   
                            <th width="7%" align="">กลาง</th>   
                            <th width="7%" align="">เล็ก</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                    

                        for($i=0; $i < count($stock_reports); $i++){ 

 
                        ?>
                        <tr class="">
                            <td><?php echo ($i+1); ?></td> 
                            <td><?php echo $stock_reports[$i]['product_code']; ?></td> 
                            <td><?php echo $stock_reports[$i]['product_name']; ?></td> 
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_1'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_2'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_3'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_4'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_5'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_6'],2); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_price_7'],2); ?></td> 
                        </tr>
                        <?PHP 

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
            
            
