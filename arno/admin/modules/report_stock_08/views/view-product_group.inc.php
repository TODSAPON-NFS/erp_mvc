<script>
    function search(){ 
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var stock_start = $("#stock_start").val();
        var stock_end = $("#stock_end").val(); 
        var sort_value = $("#sort").val();
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  
             
        window.location = "index.php?app=report_stock_08&date_start="+date_start+"&date_end="+date_end+"&stock_start="+stock_start+"&stock_end="+stock_end+"&product_start="+product_start+"&product_end="+product_end+"&sort="+sort_value ;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val(); 
        var stock_start = $("#stock_start").val();
        // var stock_end = $("#stock_end").val(); 
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  

        window.open("print.php?app=report_stock_08&action="+type+"&date_start="+date_start+"&date_end="+date_end+"&stock_start="+stock_start+"&stock_end="+stock_end+"&product_start="+product_start+"&product_end="+product_end ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานสินค้าที่ไม่เคลื่อนไหว </h1>
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
                    รายงานสินค้าที่ไม่เคลื่อนไหว  
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
                        <select id="sort" name="sort" class="form-control ">
                            <option <?php if ($sort_value == '1') {?> selected <?php }?>value="1">Stock</option>
                            <option <?php if ($sort_value == '2') {?> selected <?php }?> value="2">ProductGroup</option>
                        </select> 
                    </div>
                    <div class="col-lg-2">

                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf','');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel','');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search('');">Search</button>
                        <a href="index.php?app=report_stock_08" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <!-- <thead >
                        <tr>  
                            <th width="16%" colspan="2" style="text-align:center;">รหัสสินค้า</th>     
                            <th align="center" width="28%" colspan="9" style="text-align:center;">ชื่อสินค้า</th>    
                        </tr>
                    </thead> -->
                    <tbody>
                        <?php 
                        $product_group_id = '';
                        $product_list = 0;
                        for($i=0; $i < count($stock_reports); $i++){ 
                            $product_list++;
                            if( $stock_reports[$i]['product_group_id'] != $product_group_id){ 
                                $product_group_id = $stock_reports[$i]['product_group_id'];
                                
                                ?>
                                <tr class="">
                                    <td colspan="4" >
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="4" >
                                        <b style="color:blue;">สินค้ากลุ่ม <?php echo $stock_reports[$i]['product_group_name']; ?></b>
                                    </td> 
                                    
                                </tr> 
                                <tr>  
                                    <th width="250px;" style="text-align:center;">รหัสสินค้า</th>     
                                    <th align="center"   style="text-align:center;">ชื่อสินค้า</th>    
                                    <th align="center" width="80px"  style="text-align:center;">รหัสคลัง</th>    
                                    <th align="center" width="150px"  style="text-align:center;">ประเภทสินค้า</th>    
                                </tr>
                                <tr class="">
                                    <td  align="center">
                                        <b><?php echo $stock_reports[$i]['product_code']; ?></b>
                                    </td> 
                                    <td  >
                                        <b><span style="color:blue;"><?php echo $stock_reports[$i]['product_name']; ?></span></b>
                                    </td>  
                                    <td class="text-center" >
                                        <b><span style="color:blue;"><?php echo $stock_reports[$i]['stock_group_code']; ?></span></b>
                                    </td>  
                                    <td class="text-center" >
                                        <b><span style="color:blue;"><?php echo $stock_reports[$i]['product_group_name']; ?></span></b>
                                    </td>  
                                </tr> 
                                <?PHP
                            }
                            else{  
                                ?> 
                                <tr class="">
                                    <td  align="center">
                                        <b><?php echo $stock_reports[$i]['product_code']; ?></b>
                                    </td> 
                                    <td  >
                                        <b><span style="color:blue;"><?php echo $stock_reports[$i]['product_name']; ?></span></b>
                                    </td>  
                                    <td class="text-center">
                                        <b><span style="color:blue;"><?php echo $stock_reports[$i]['stock_group_code']; ?></span></b>
                                    </td>  
                                    <td class="text-center">
                                        <b><span style="color:blue;"><?php echo $stock_reports[$i]['product_group_name']; ?></span></b>
                                    </td>  
                                </tr> 
                            <?PHP                                  
                            }
                            if($stock_reports[$i+1]['product_group_id'] != $product_group_id){  ?>
                                <tr class="">
                                    <td colspan="1" style="text-align:right;"><b><span>รวม</span></b></td>
                                    <td colspan="1" style="text-align:right;"><?php echo number_format($product_list,0); ?> รายการ</td>
                                    
                                    <td align="right" ></td>  
                                    <td align="right" ></td> 
                                </tr>
                            <?php
                            $product_list = 0;
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="">
                            <td colspan="4" >
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1" align="right"><b>รวมทั้งสิ้น</b></td>
                            <td colspan="1" align="right"><b><?php echo count($stock_reports);?> สินค้า</b></td>
                            <td></td>
                            <td></td>
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
            
            
