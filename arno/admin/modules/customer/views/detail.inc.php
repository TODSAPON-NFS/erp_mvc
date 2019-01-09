<div class="row">
    <!-- /.col-lg-12 -->
     <div class="col-lg-12" align="center">
        <h1 class="page-header"><?PHP    echo  $header_page?></h1>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        <?PHP    echo  $header_page?>
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
                                    <label>รูป / Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/product/<?PHP   if($product['customer_logo'] != ""){ echo  $product['customer_logo']; }else{ echo  "default.png"; } ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <br>
                    <div class="col-lg-1">
                            <label>	รหัสลูกค้า / Code</label>
                            <p class="help-block"><?PHP    echo  $customer_code?></p>
                    </div>

                    <div class="col-lg-3">
                            <label>ชื่อลูกค้า / Name</label>
                            <p class="help-block"><?PHP    echo  $customer_name_en?></p>
                            <p class="help-block">(<?PHP    echo  $customer_name_th?>)</p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>ประเภทบริษัท / Type</label>
                            <p class="help-block"><?PHP    echo  $customer_type?></p>
                    </div>

                    <div class="col-lg-2">
                            <label>เลขผู้เสียภาษี / TAX </label>
                            <p class="help-block"><?PHP    echo  $customer_tax?></p>
                    </div>

                    <div class="col-lg-8">
                            <label>ที่อยู่ / Address </label>
                            <p class="help-block"><?PHP   
                                echo  $customer_address_1;
                                echo  " ";
                                echo  $customer_address_2;
                                echo  " ";
                                echo  $customer_address_3; 
                                echo  " ";
                                echo  $customer_zipcode;
                             ?>
                             </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เบอร์โทรศัพท์ / TEL</label>
                            <p class="help-block"><?PHP    echo  $customer_tel?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	เบอร์แฟค / FAX </label>
                            <p class="help-block"><?PHP    echo  $customer_fax?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>	อีเมล / Email</label>
                            <p class="help-block"><?PHP    echo  $customer_email?></p>
                    </div>                                       
                    
                    <div class="col-lg-2">
                            <label>	บริษัทของประเทศ / Domestic </label>
                            <p class="help-block"><?PHP    echo  $customer_domestic?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>รายละเอียด / Description</label>
                            <p class="help-block"><?PHP
                                if ($customer_remark === null || "" || " ") {
                                    echo "-";
                                }else {
                                    echo  $customer_remark;
                                }
                                ?>
                            </p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>สาขา / Branch</label>
                            <p class="help-block"><?PHP    echo  $customer_branch?></p>
                    </div>
                    
                    <div class="col-lg-2">
                            <label>เขตการขาย / Zone</label>
                            <p class="help-block"><?PHP    echo  $customer_zone?></p>
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
                                <a class="nav-link" id="quo-tab" data-toggle="tab" href="#quo" role="tab" aria-controls="quo" aria-selected="false">ใบเสนอราคา</a>
                            </li>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="cus-tab" data-toggle="tab" href="#cus" role="tab" aria-controls="cus" aria-selected="false">ใบสั่งซื้อสินค้า</a>
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
                                    <th>ลำดับ</th>
                                    <th>รหัสสินค้า</th>
                                    <th>ชื่อสินค้า</th>
                                    <th>ราคาสินค้า</th>
                                </tr>
                            </thead>
                            <?PHP 
                                for ($i=0; $i < count($product); $i++) { 
                                
                            ?>
                            <tbody class="odd gradeX">
                                <td style="text-align:right;"> <?PHP echo $i+1 ?></td>
                                <td> <a href="index.php?app=product_detail&product_id= <?PHP echo $product[$i]['product_id']  ?>">  <?PHP echo $product[$i]['product_code']  ?> </a></td>

                                <td style="text-align:right;"> <?PHP echo $product[$i] ['product_code'] ?></td>
                                <td> <?PHP echo $product[$i] ['product_name'] ?></td>
                                <td style="text-align:right;"> <?PHP echo number_format($product[$i] ['product_price'] ,2)?></td>
                            </tbody>
                            <?PHP 

                                }

                            ?>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="quo" role="tabpanel" aria-labelledby="quo-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th> วันที่  </th>
                                    <th> รหัส</th>
                                    <th>พนักงานที่เกี่ยวข้าง	</th>
                                </tr>
                            </thead>
                            <?PHP 
                            for ($i=0; $i <count($quotation) ; $i++) {   
                                if ( $quotation[$i]['quotation_code'] != null && $quotation[$i+1]['quotation_code'] != $quotation[$i]['quotation_code']) {
                                                              
                            ?>

                            <tbody class="odd gradeX">
                                <td>   <?PHP echo $quotation[$i]['quotation_date']  ?> </td>
                                <td> <a href="index.php?app=quotation&action=detail&id=<?PHP echo $quotation[$i]['quotation_id']  ?>"><?PHP echo $quotation[$i]['quotation_code']  ?> </a>  </td>
                                <td>  
                                 <?PHP echo $quotation[$i]['user_prefix']  ?>
                                 <?PHP echo $quotation[$i]['user_name']  ?>
                                 <?PHP echo $quotation[$i]['user_lastname']  ?>
                                
                                 </td>
                            </tbody>
                            <?PHP
                            }
                                }
                                ?>
                        </table>
                    </div>


                    <div class="tab-pane fade" id="cus" role="tabpanel" aria-labelledby="cus-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th> วันที่  </th>
                                    <th> รหัส</th>
                                    <th>พนักงานที่เกี่ยวข้าง	</th>
                                </tr>
                            </thead>
                            <?PHP 
                            for ($i=0; $i <count($product) ; $i++) {   
                                if ( $product[$i]['customer_purchase_order_code'] != null && $product[$i+1]['customer_purchase_order_code'] != $product[$i]['customer_purchase_order_code']) {
                                                              
                            ?>

                            <tbody class="odd gradeX">
                                <td>   <?PHP echo $product[$i]['customer_purchase_order_date']  ?> </td>
                                <td> <a href="index.php?app=customer_purchase_order&action=detail&id=<?PHP echo $product[$i]['customer_purchase_order_id']  ?>"><?PHP echo $product[$i]['customer_purchase_order_code']  ?> </a>  </td>
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

                    <div class="tab-pane fade" id="seller" role="tabpanel" aria-labelledby="seller-tab">
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th> วันที่  </th>
                                    <th> รหัส</th>
                                    <th>พนักงานที่เกี่ยวข้าง	</th>
                                </tr>
                            </thead>
                            <?PHP 
                            for ($i=0; $i <count($invoice) ; $i++) {   
                                if ( $invoice[$i]['invoice_customer_code'] != null && $invoice[$i+1]['invoice_customer_code'] != $invoice[$i]['invoice_customer_code']) {
                                                              
                            ?>

                            <tbody class="odd gradeX">
                                <td>   <?PHP echo $invoice[$i]['invoice_customer_date']  ?> </td>
                                <td> <a href="index.php?app=invoice_customer&action=detail&id=<?PHP echo $invoice[$i]['invoice_customer_id']  ?>"><?PHP echo $invoice[$i]['invoice_customer_code']  ?> </a>  </td>
                                <td>  
                                 <?PHP echo $invoice[$i]['user_prefix']  ?>
                                 <?PHP echo $invoice[$i]['user_name']  ?>
                                 <?PHP echo $invoice[$i]['user_lastname']  ?>
                                
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


