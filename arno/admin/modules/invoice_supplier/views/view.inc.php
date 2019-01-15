

<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=invoice_supplier&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Supplier Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        
    </div>
    <!-- /.col-lg-12 -->
</div>

<?PHP if($license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายการใบกำกับภาษีรับเข้าตามผู้ขายในประเทศ
                    </div>
                    <?PHP if($license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                    <div class="col-md-4" align="right">
                        <a class="btn btn-danger " style="margin:4px;" href="?app=invoice_supplier&action=import-view&sort=ภายในประเทศ" ><i class="fa fa-plus" aria-hidden="true"></i> Import</a>
                        <a class="btn btn-success " style="margin:4px;" href="?app=invoice_supplier&action=insert&sort=ภายในประเทศ" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                    <?PHP } ?>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div style="font-size:18px;padding: 8px 0px;">แยกตามผู้ขาย</div>
                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Supplier</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($supplier_orders_in); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $supplier_orders_in[$i]['supplier_name_en']; ?></td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&supplier_id=<?php echo $supplier_orders_in[$i]['supplier_id'];?>">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </a>

                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
                
                <div style="font-size:18px;padding: 8px 0px;">แยกตามใบสั่งซื้อ</div>
                <table width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Purchase Order</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($purchase_orders_in); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $purchase_orders_in[$i]['purchase_order_code']; ?> </td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&supplier_id=<?php echo $purchase_orders_in[$i]['supplier_id'];?>&purchase_order_id=<?php echo $purchase_orders_in[$i]['purchase_order_id'];?>">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </a>

                            </td>

                        </tr>
                        <?
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

    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        รายการใบกำกับภาษีรับเข้าตามผู้ขายนอกประเทศ
                    </div>
                    <div class="col-md-6" align="right">
                        <a class="btn btn-primary " style="margin:4px;"  href="?app=exchange_rate_baht&action=view" ><i class="fa fa-plus" aria-hidden="true"></i> Exchange rate</a> 
                        <a class="btn btn-danger " style="margin:4px;" href="?app=invoice_supplier&action=import-view&sort=ภายนอกประเทศ" ><i class="fa fa-plus" aria-hidden="true"></i> Import</a>
                        <a class="btn btn-success " style="margin:4px;"  href="?app=invoice_supplier&action=insert&sort=ภายนอกประเทศ" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                    
                </div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div style="font-size:18px;padding: 8px 0px;">แยกตามผู้ขาย</div>
                <table width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Supplier</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($supplier_orders_out); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $supplier_orders_out[$i]['supplier_name_en']; ?> </td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&sort=ภายนอกประเทศ&supplier_id=<?php echo $supplier_orders_out[$i]['supplier_id'];?>">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </a>

                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
                
                <div style="font-size:18px;padding: 8px 0px;">แยกตามใบสั่งซื้อ</div>
                <table width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="64px" >No.</th>
                            <th>Purchase Order</th>
                            <th width="180px" >Open Invoice Supplier</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($purchase_orders_out); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $purchase_orders_out[$i]['purchase_order_code']; ?>  </td>
                            <td>
                                <a href="?app=invoice_supplier&action=insert&sort=ภายนอกประเทศ&supplier_id=<?php echo $purchase_orders_out[$i]['supplier_id'];?>&purchase_order_id=<?php echo $purchase_orders_out[$i]['purchase_order_id'];?>">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </a>

                            </td>

                        </tr>
                        <?
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

<?PHP } ?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            รายการใบกำกับภาษีรับเข้า / Invoice Supplier List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่รับใบกำกับภาษี</label>
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
                            <label>ผู้ขาย </label>
                            <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=invoice_supplier" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br> 

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  id="dataTables-example">
                            <thead>
                                <tr>
                                    <th class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="32">No.</th>
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขรับสินค้า" width="104">Recieve Code</th>
                                    <th class="datatable-th" data-original-title="วันที่รับสินค้า" data-container="body" data-toggle="tooltip" data-placement="top" title="" width="100">Recieve Date</th>
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบกำกับภาษี" width="110">Invoice Code</th>
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ตามใบกำกับภาษี" width="94">Invoice Date</th>                                    
                                    <th class="datatable-th" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เอกสารที่เกี่ยวข้อง" width="120">Purchase Order</th>
                                    <th class="datatable-th text-center" data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ขาย" >Supplier</th>
                                    <!--
                                    <th width="150" >Recieve by</th>
                                    -->
                                    <th>จำนวนเงิน</th>
                                    <th>ภาษีซื้อ</th>
                                    <th>จำนวนเงินสุทธิ</th>
                                    
                                    <th width="100"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $invoice_supplier_total_price =0;
                                $invoice_supplier_vat_price =0;
                                $invoice_supplier_net_price =0;
                                for($i=0; $i < count($invoice_suppliers); $i++){
                                    $invoice_supplier_total_price +=$invoice_suppliers[$i]['invoice_supplier_total_price'];
                                    $invoice_supplier_vat_price +=$invoice_suppliers[$i]['invoice_supplier_vat_price'];
                                    $invoice_supplier_net_price +=$invoice_suppliers[$i]['invoice_supplier_net_price'];
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code_gen']; ?></td>
                                    <td><?php echo $invoice_suppliers[$i]['invoice_supplier_date_recieve']; ?></td>
                                    <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?></td>
                                    <td><?php echo $invoice_suppliers[$i]['invoice_supplier_date']; ?></td> 
                                    <td><?php 

                                        $purchase_orders = $invoice_supplier_model->getPurchaseOrderByInvoiceSupplierId($invoice_suppliers[$i]['invoice_supplier_id']);
                                        // echo '<pre>';
                                        // print_r ($purchase_orders);
                                        // echo '</pre>';
                                        for($j=0; $j < count($purchase_orders); $j++){ ?>
                                            <a href="?app=purchase_order&action=detail&id=<?php echo $purchase_orders[$j]['purchase_order_id'];?>" target = "_blank" title="ดูรายละเอียดใบสั่งซื้อ">
                                            <?php echo $purchase_orders[$j]['purchase_order_code']; ?>
                                            </a><br>
                                            <?php
                                        } 
                                    
                                    ?>
                                    
                                    </td>
                                    <td><?php echo $invoice_suppliers[$i]['supplier_name']; ?> </td>
                                    <!--
                                    <td><?php echo $invoice_suppliers[$i]['employee_name']; ?></td>
                                    -->
                                    <td align="right"><?php echo number_format($invoice_suppliers[$i]['invoice_supplier_total_price'],2); ?></td>
                                    <td align="right"><?php echo number_format($invoice_suppliers[$i]['invoice_supplier_vat_price'],2); ?></td>
                                    <td align="right"><?php echo number_format($invoice_suppliers[$i]['invoice_supplier_net_price'],2); ?></td>

                                    <td>
                                        <a href="?app=invoice_supplier&action=detail&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" style="color:#0045E6;">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>
 
                                        

                                        <?PHP if($invoice_suppliers[$i]['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                                        
                                        <a href="?app=invoice_supplier&action=cost&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>">
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        </a>

                                        <a href="print.php?app=invoice_supplier_abroad&action=pdf&type=credit&id=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" target="_blank" title="พิมพ์ใบตั้งเจ้าหนี้ต่างประเทศ"  style="color:green;">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>
                                        <a href="print.php?app=invoice_supplier_abroad&action=pdf&type=receive&id=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" target="_blank" title="พิมพ์ใบรับสินค้า">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a> 

                                        <?PHP } else { ?>
                                        
                                        <?PHP /*
                                        <a href="print.php?app=invoice_supplier&action=pdf&lan=th&id=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" target="_blank" title="พิมพ์เอกสารภาษาไทย"  style="color:orange;">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a> */
                                        ?>

                                        <a href="print.php?app=invoice_supplier&action=pdf&lan=en&id=<?PHP echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" target="_blank" title="พิมพ์ใบรับสินค้า">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a> 

                                        <?PHP } ?>


                                        <?PHP if($license_purchase_page == "Medium" || $license_purchase_page == "High"){ ?>
                                        <a href="?app=invoice_supplier&action=update&sort=<?PHP echo $invoice_suppliers[$i]['supplier_domestic'];?>&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" style="color:orange;">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <?PHP } ?>


                                        <?PHP if( $license_purchase_page == "High"){ ?>
                                        <a href="?app=invoice_supplier&action=delete&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" onclick="return confirm('You want to delete Invoice Supplier : <?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                        <?PHP } ?>

                                        
                                    </td>

                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr class="odd gradeX">
                                    <td colspan ="7"><b>จำนวนเงินรวม</b></td>
                                    <td align="right"><?php echo number_format($invoice_supplier_total_price,2); ?></td>
                                    <td align="right"><?php echo number_format($invoice_supplier_vat_price,2); ?></td>
                                    <td align="right"><?php echo number_format($invoice_supplier_net_price,2); ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                 
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>        
<script>
$(function () {
    $("[data-toggle='tooltip']").tooltip();
    
});
</script>            
