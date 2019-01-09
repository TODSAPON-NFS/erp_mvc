<div class="row">
    <!-- /.col-lg-12 -->
     <div class="col-lg-12" align="center">
        <h1 class="page-header">รายละเอียดผู้ขาย</h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายละเอียดผู้ขาย
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                
                <div class="row">

                <!-- รูปผู้ขาย -->
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>รูปผู้ขาย / Supplier Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/product/<?PHP   if($product['supplier_logo'] != ""){ echo  $product['supplier_logo']; }else{ echo  "default.png"; } ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>

                    <div class="col-lg-2">
                            <label>รหัสผู้ขาย / Supplier code</label>
                            <p class="help-block"><?PHP    echo  $supplier_code?></p>
                    </div>

                    <div class="col-lg-3">
                            <label>ชื่อผู้ขาย / Name</label>
                            <p class="help-block"><?PHP    echo  $supplier_name_en?></p>
                            <p class="help-block">(<?PHP    echo  $supplier_name_th?>)</p>
                    </div>
                    
                    <div class="col-lg-3">
                            <label>เลขผู้เสียภาษี / Supplier Tax</label>
                            <p class="help-block"><?PHP    echo  $supplier_tax?></p>
                    </div>

                    <div class="col-lg-8">
                            <label>ที่อยู่ / Supplier Address </label>
                            <p class="help-block"><?PHP    echo  $supplier_address_1?> <?PHP    echo  $supplier_address_2?>  <?PHP    echo  $supplier_address_3?>  <?PHP    echo  $supplier_zipcode?></p>
                    </div>

                    <div class="col-lg-2">
                            <label>	เบอร์โทรศัพท์ / Supplier Tel </label>
                            <p class="help-block"><?PHP    echo  $supplier_tel?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เบอร์แฟค / Fax</label>
                            <p class="help-block"><?PHP    echo  $supplier_fax?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>อีเมล / Email</label>
                            <p class="help-block"><?PHP    echo  $supplier_email?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	บริษัทของประเทศ / Domestic </label>
                            <p class="help-block"><?PHP    echo  $supplier_domestic?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>รายละเอียด / Description</label>
                            <p class="help-block"><?PHP
                                if ($supplier_remark === null || "" || " ") {
                                    echo "-";
                                }else {
                                    echo  $supplier_remark;
                                }
                                ?>
                            </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>สาขา / Branch</label>
                            <p class="help-block"><?PHP    echo  $supplier_branch?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เขตการขาย / Zone</label>
                            <p class="help-block"><?PHP    echo  $supplier_zone?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เครดิตการจ่าย / Credit</label>
                            <p class="help-block"><?PHP    echo  $credit_day?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เงื่อนไขการชำระเงิน / Condition</label>
                            <p class="help-block"><?PHP    echo  $condition_pay?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	วงเงินอนุมัติ / Pay Limit</label>
                            <p class="help-block"><?PHP    echo  $pay_limit?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ประเภทบัญชี	 / Account</label>
                            <p class="help-block"><?PHP    echo  $account_id?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ประเภทภาษีมูลค่าเพิ่ม / Vat Type</label>
                            <p class="help-block"><?PHP    echo  $vat_type?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	ภาษีมูลค่าเพิ่ม	 / Vat</label>
                            <p class="help-block"><?PHP    echo  $vat?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	สกุลเงิน / Currency</label>
                            <p class="help-block"><?PHP    echo  $currency_id?></p>
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
                                <a class="nav-link active" id="stock-tab" data-toggle="tab" href="#stock" role="tab" aria-controls="stock" aria-selected="true">สินค้า</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="buy-tab" data-toggle="tab" href="#buy" role="tab" aria-controls="buy" aria-selected="false">ใบสั่งซื้อสินค้า</a>
                            </li>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="seller-tab" data-toggle="tab" href="#seller" role="tab" aria-controls="seller" aria-selected="false">ใบขายสินค้า</a>
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
                                
                                <th> ลำดับ  </th>
                                    <th>รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคาสินค้า</th>
                                </tr>
                            </thead>
                            <?PHP 
                            for ($i=0; $i <count($product) ; $i++) {   
                                if ( $product[$i]['product_code'] != null) {
                            
                            ?>
                            <tbody class="odd gradeX">
                            <td>   <?PHP echo $i+1; ?> </td>

                                <td> <a href="index.php?app=product_detail&product_id= <?PHP echo $product[$i]['product_id']  ?>">  <?PHP echo $product[$i]['product_code']  ?> </a></td>
                                <td>   <?PHP echo $product[$i]['product_name']  ?> </td>
                                <td style="text-align:right;">   <?PHP echo number_format( $product[$i]['product_buyprice'],2 ) ?> </td>
                            </tbody>
                            <?PHP
                            }
                                }
                                ?>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="buy" role="tabpanel" aria-labelledby="buy-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th> วันที่  </th>
                                    <th> รหัส	</th>
                                    <th>พนักงานที่เกี่ยวข้อง	</th>
                                </tr>
                            </thead>
                            
                            <?PHP 
                            for ($i=0; $i <count($customer_purchase) ; $i++) {   
                                if ( $customer_purchase[$i]['purchase_order_code'] != null && $customer_purchase[$i+1]['purchase_order_code'] != $customer_purchase[$i]['purchase_order_code']) {
                                                              
                            ?>

                            <tbody class="odd gradeX">
                                <td>   <?PHP echo $customer_purchase[$i]['purchase_order_date']  ?> </td>
                                <td>  
                                <a href="index.php?app=customer_purchase_order&action=detail&id=<?PHP echo $customer_purchase[$i]['purchase_order_id']  ?>">  <?PHP echo $customer_purchase[$i]['purchase_order_code']  ?> </a></td>
                                <td>   
                                    <?PHP echo $customer_purchase[$i]['user_name']  ?> 
                                    <?PHP echo $customer_purchase[$i]['user_lastname']  ?> 
                                </td>
                            </tbody>
                            <?PHP
                            }
                                }
                                ?>

                        </table>
                    </div>

                    <div class="tab-pane fade" id="seller" role="tabpanel" aria-labelledby="seller-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th> วันที่  </th>
                                    <th> รหัส 	</th>
                                    <th>พนักงานที่เกี่ยวข้อง	</th>
                                </tr>
                            </thead>
                            
                            <?PHP 
                            for ($i=0; $i <count($product) ; $i++) {   
                                if ( $product[$i]['invoice_supplier_code'] != null && $product[$i+1]['invoice_supplier_code'] != $product[$i]['invoice_supplier_code']) {
                                                              
                            ?>

                            <tbody class="odd gradeX">
                                <td>   <?PHP echo $product[$i]['invoice_supplier_date']  ?> </td>
                                <td>  <a href="index.php?app=invoice_supplier&action=detail&id=<?PHP echo $product[$i]['invoice_supplier_id']  ?>">  <?PHP echo $product[$i]['invoice_supplier_code']  ?> </a></td>
                                <td>   
                                    <?PHP echo $product[$i]['user_prefix']  ?> 
                                    <?PHP echo $product[$i]['user_name']  ?> 
                                    <?PHP echo $product[$i]['user_lastname']  ?> 
                                </td>
                            </tbody>
                            <?PHP
                            }
                                }
                                ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


