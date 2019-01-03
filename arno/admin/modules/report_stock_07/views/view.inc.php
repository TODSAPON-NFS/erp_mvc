<script>
    function search(){  
        // alert();
        var supplier_id = encodeURIComponent($("#supplier_id").val()); 
        var product_type_id = encodeURIComponent($("#product_type_id").val());  
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());   
        var product_qty = encodeURIComponent($("#product_qty").val());    
        var product_qty_text = document.getElementById('product_qty').options[document.getElementById('product_qty').selectedIndex ].text 
        
        window.location = "index.php?app=report_stock_07&product_type_id="+product_type_id+"&supplier_id="+supplier_id+"&product_start="+product_start+"&product_end="+product_end+"&product_qty="+product_qty+"&product_qty_text="+product_qty_text ;
    }
    function print(type){  
        var supplier_id = encodeURIComponent($("#supplier_id").val()); 
        var product_type_id = encodeURIComponent($("#product_type_id").val());  
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  
        var product_qty = encodeURIComponent($("#product_qty").val());   
        var product_qty_text = document.getElementById('product_qty').options[document.getElementById('product_qty').selectedIndex ].text 

        window.open("print.php?app=report_stock_07&action="+type+"&product_type_id="+product_type_id+"&supplier_id="+supplier_id+"&product_start="+product_start+"&product_end="+product_end+"&product_qty="+product_qty+"&product_qty_text="+product_qty_text ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานจุดสั่งซื้อ</h1>
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
                    รายงานจุดสั่งซื้อ 
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ผู้ขาย / Supplier </label>
                            <select id="supplier_id" name="supplier_id" class="form-control"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                ?>
                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_en'] ?> </option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    
                </div>
                <div class="row"> 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>จุดสั่งซื้อ </label>
                            <select id="product_qty" name="product_qty" class="form-control "  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <option <?php if($product_qty == 'low'){?> selected <?php }?> value="low">ต่ำกว่าเกณฑ์</option>
                                <option <?php if($product_qty == 'normal'){?> selected <?php }?> value="normal">ภายในเกณฑ์</option>
                                <option <?php if($product_qty == 'high'){?> selected <?php }?> value="high">สูงกว่าเกณฑ์</option> 
                            </select>
                            <p class="help-block">Example : -</p>
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
                        <a href="index.php?app=report_stock_07" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th width="5%" >No.</th>  
                            <th width="17.5%" >รหัสสินค้า</th>  
                            <th align="17.5%">ชื่อสินค้า </th>
                            <th width="25%" align="">ผู้ขาย</th>
                            <th width="7%" align="center">จุดต่ำสุด</th>   
                            <th width="7%" align="center">จุดสั่งซื้อ</th>   
                            <th width="7%" align="center">จุดสูงสุด</th>   
                            <th width="7%" align="center">คงเหลือ</th>   
                            <th width="7%" align="center">ต้องสั่งซื้อ</th>    
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
                            <td><?php echo $stock_reports[$i]['supplier_name_en']; ?></td> 
                            <td align="right"><?php echo number_format($stock_reports[$i]['minimum_stock'],0); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['safety_stock'],0); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['maximum_stock'],0); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_qty'],0); ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['product_buy'],0); ?></td> 
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
            
            