<script>
    function search(){     
        var product_start = $("#product_start").val(); 
        var product_end = $("#product_end").val();  

        window.location = "index.php?app=report_stock_04&product_start="+product_start+"&product_end="+product_end ;
    }
    function print(type){     
        var product_start = $("#product_start").val(); 
        var product_end = $("#product_end").val();  

        window.open("print.php?app=report_stock_04&action="+type+"&product_start="+product_start+"&product_end="+product_end ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานรายละเอียดสินค้า</h1>
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
                    รายงานรายละเอียดสินค้า 
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
                        <a href="index.php?app=report_stock_04" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th style="text-align:center;" width="5%" >No.</th>  
                            <th width="15%" >รหัสสินค้า</th>  
                            <th width="15%">ชื่อสินค้า </th>
                            <th width="7%" align="">ลักษณะสินค้า</th>
                            <th width="7%" align="">กลุ่มสินค้า</th>   
                            <th width="7%" align="">ประเภทสินค้า</th>   
                            <th width="7%" align="">บาร์โค๊ต</th>   
                            <th width="7%" align="">หน่วยสินค้า</th>   
                            <th width="7%" align="">บัญชีเมื่อซื้อ</th>   
                            <th width="7%" align="">บัญชีเมื่อขาย</th>    
                            <th width="16%" align="">รายละเอียดสินค้า</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                    

                        for($i=0; $i < count($stock_reports); $i++){ 

 
                        ?>
                        <tr class="">
                            <td align="center"><?php echo ($i+1); ?></td> 
                            <td align="left"><?php echo $stock_reports[$i]['product_code_first'].$stock_reports[$i]['product_code']; ?></td> 
                            <td align="left"><?php echo $stock_reports[$i]['product_name']; ?></td> 
                            <td align="left"><?php echo $stock_reports[$i]['product_category_name']; ?></td>
                            <td align="left"><?php echo $stock_reports[$i]['product_group_name']; ?></td>
                            <td align="left"><?php echo $stock_reports[$i]['product_type_name']; ?></td>
                            <td align="left"><?php echo $stock_reports[$i]['product_barcode']; ?></td>
                            <td align="left"><?php echo $stock_reports[$i]['product_unit_name']; ?></td>
                            <td align="left"><?php echo $stock_reports[$i]['buy_account_name']; ?></td>
                            <td align="left"><?php echo $stock_reports[$i]['sale_account_name']; ?></td> 
                            <td align="left"><?php echo $stock_reports[$i]['product_description']; ?></td> 
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
            
            
