<div class="row">
    <!-- /.col-lg-12 -->
     <div class="col-lg-12" align="center">
        <h1 class="page-header"><?php  echo $header_page?></h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <?php  echo $header_page?>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
                <div class="row">

                <!-- รูปสินค้า -->
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปสินค้า / Product Picture <font color="#F00"><b>*</b></font></label>
                                    <img class="img-responsive" id="img_logo" src="../upload/product/<?php if($product['product_logo'] != ""){ echo $product['product_logo']; }else{ echo "default.png"; } ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    <!-- รหัสสินค้า -->
                    <div class="col-lg-4">
                            <label>รหัสสินค้า / Product code</label>
                            <p class="help-block"><?php  echo $product_code?></p>
                    </div>

                    <!-- ชื่อสินค้า -->
                    <div class="col-lg-4">
                            <label>ชื่อสินค้า / Name</label>
                            <p class="help-block"><?php  echo $product_name?></p>
                    </div>
                    
                    <!-- ลักษณะสินค้า -->
                    <div class="col-lg-4">
                            <label>ลักษณะสินค้า / Product Category</label>
                            <p class="help-block"><?php  echo $product_category_name?></p>
                    </div>

                    <!-- กลุ่มสินค้า -->
                    <div class="col-lg-4">
                            <label>กลุ่มสินค้า / Product Group </label>
                            <p class="help-block"><?php  echo $product_group_name?></p>
                    </div>

                    <!-- ประเภทสินค้า / Product Type -->
                    <div class="col-lg-4">
                            <label>กลุ่มสินค้า / Product Group </label>
                            <p class="help-block"><?php  echo $product_type_name?></p>
                    </div>
                    
                    <!-- หมายเลขบาร์โค๊ต / Barcode -->
                    <div class="col-lg-4">
                            <label>หมายเลขบาร์โค๊ต / Barcode</label>
                            <p class="help-block"><?php  echo $product_barcode?></p>
                    </div>
                    
                    <!-- หน่วยสินค้า / Product Unit -->
                    <div class="col-lg-4">
                            <label>หน่วยสินค้า / Product Unit</label>
                            <p class="help-block"><?php  echo $product_unit?></p>
                    </div>
                    
                    <!-- สถานะสินค้า / Produc Status -->
                    <div class="col-lg-4">
                            <label>สถานะสินค้า / Produc Status</label>
                            <p class="help-block"><?php  echo $product_status?></p>
                    </div>
                    
                    <!-- รายละเอียดสินค้า / Description -->
                    <div class="col-lg-6">
                            <label>รายละเอียดสินค้า / Description</label>
                            <p class="help-block"><?php  echo $product_description?></p>
                    </div>
                    
                </div>
                <!-- /.panel-row -->                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                    
                    
                <ul class="nav nav-tabs" id="myTab" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" id="stock-tab" data-toggle="tab" href="#stock" role="tab" aria-controls="stock" aria-selected="true">คลังสินค้า</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="seller-tab" data-toggle="tab" href="#seller" role="tab" aria-controls="seller" aria-selected="false">ผู้ขาย</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="customer-tab" data-toggle="tab" href="#customer" role="tab" aria-controls="customer" aria-selected="false">ลูกค้า</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="document-tab" data-toggle="tab" href="#document" role="tab" aria-controls="document" aria-selected="false">เอกสาร</a>
                    </li>

                </ul>
                    </div>
                </div>
            </div>
            
            <!-- /.panel-heading -->
            <div class="panel-body">

                <!-- 
                <script>
                    $('#myTab a[href="#stock"]').tab('show') // Select tab by name
                    $('#myTab li:first-child a').tab('show') // Select first tab
                    $('#myTab li:last-child a').tab('show') // Select last tab
                    $('#myTab li:nth-child(3) a').tab('show') // Select third tab
                </script> 
                -->
                <script>
                    $(function () {
                        $('#myTab li:first-child a').tab('show') // Select first tab
                    })
                </script>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade "  id="stock" role="tabpanel" aria-labelledby="stock-tab">

                                
                        <table width="100%" class="table table-striped table-bordered table-hover" >

                            <thead>
                                <tr>
                                    <th>คลังสินค้า</th>
                                    <th>จำนวน</th>
                                </tr>
                            </thead>

                            <?for ($i=0; $i <count( $stock_report ); $i++) {
                                
                                if ( $stock_report[$i+1] ['stock_group_name'] !=  $stock_report[$i] ['stock_group_name']) {
                                    # code...
                                    
                            ?>
                            <tbody class="odd gradeX">
                                <td>
                                    <?php echo  $stock_report[$i] ['stock_group_name']?>
                                </td>
                                <td>
                                    <?php echo$stock_report[$i] ['stock_report_qty'] ?>
                                </td>
                            
                            </tbody>
                            <?}
                            }?>
                        </table>

                    </div>

                    <div class="tab-pane fade" id="seller" role="tabpanel" aria-labelledby="seller-tab">
                    
                       
                        
                    <table width="100%" class="table table-striped table-bordered table-hover" >

                        <thead>
                            <tr>
                                <th>รหัส</th>
                                <th>ชื่อผู้ขาย</th>
                                <th>ราคา</th>
                            </tr>
                        </thead>

                        <?for ($i=0; $i <count( $stock_report ); $i++) { 
                            if ( $stock_report[$i+1] ['supplier_code'] !=  $stock_report[$i] ['supplier_code']) {
                                # code...
                            
                            ?>
                        <tbody class="odd gradeX">
                            <td>
                                <?php echo  $stock_report[$i] ['supplier_code']?>
                            </td>
                            <td>
                                <?php echo$stock_report[$i] ['supplier_name_en'] ?>
                            </td>
                            </td>
                            <td>
                                <?php echo number_format($stock_report[$i] ['product_buyprice'],2) ?>
                            </td>

                        </tbody>
                        <?
                        }
                    }?>
                    </table>


                    </div>

                    <div class="tab-pane fade" id="customer" role="tabpanel" aria-labelledby="customer-tab">
                    
                        
                    <table width="100%" class="table table-striped table-bordered table-hover" >

                        <thead>
                            <tr>
                                <th>รหัส</th>
                                <th>ชื่อลูกค้า</th>
                                <th>ราคา</th>
                            </tr>
                        </thead>

                        <?for ($i=0; $i <count( $stock_report ); $i++) { 
                            if ( $stock_report[$i+1] ['customer_code'] !=  $stock_report[$i] ['customer_code']) {
                                # code...
                            
                            ?>
                        <tbody class="odd gradeX">
                            <td>
                                <?php echo  $stock_report[$i] ['customer_code']?>
                            </td>
                            <td>
                                <?php echo$stock_report[$i] ['customer_name_en'] ?>
                            </td>
                            </td>
                            <td>
                                <?php echo ($stock_report[$i] ['product_price']) ?>
                            </td>

                        </tbody>
                        <?
                        }
                    }?>
                    </table>

                    </div>

                    <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-tab">
                    
                            
                            
                        <table width="100%" class="table table-striped table-bordered table-hover" >

                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>รหัสเอกสาร</th>
                                    <th>คลังสินค้า</th>
                                    <th>จำนวน</th>

                                </tr>
                            </thead>

                            <?for ($i=0; $i <count( $paper ); $i++) { 
                                if ( $paper[$i+1] ['paper_code'] !=  $paper[$i] ['paper_code']) {
                                    # code...
                                
                                ?>
                            <tbody class="odd gradeX">
                                <td>
                                    <?php echo  $paper[$i] ['paper_date']?>
                                </td>
                                <td>
                                    <?php echo$paper[$i] ['paper_code'] ?>
                                </td>
                                <td>
                                    <?php echo ($paper[$i] ['stock_group_name']) ?>
                                </td>
                                <td>
                                    <?php echo ($paper[$i] ['paper_qty']) ?>
                                </td>

                            </tbody>
                            <?
                                }
                            }?>
                        </table>

                    </div>

                </div>


            </div>
        </div>
    </div>


</div>


