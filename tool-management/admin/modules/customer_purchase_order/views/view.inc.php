<script>

    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#search_customer_id").val();
        var keyword = $("#keyword").val();
        var status = $("#status").val();
        window.location = "index.php?app=customer_purchase_order&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&status="+status+"&keyword="+keyword;
    }

    function changeURL(id){
        var data = $(id).val();
        if(data == ""){
            $("#link").attr("href","?app=customer_purchase_order&action=insert");
        }else{
            $("#link").attr("href","?app=customer_purchase_order&action=insert&quotation_id="+data);
        }
    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Customer Order Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        
    </div>
    <!-- /.col-lg-12 -->
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            เปิดใบยืมสินค้าของลูกค้าอ้างอิงตามบริษัท / Delivery Note Customer to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="64px" >ลำดับ <br>No.</th>
                            <th>ลูกค้า <br>Customer</th>
                            <th width="180px" >เปิดใบยืมสินค้าของลูกค้า <br>Open Delivery Note Customer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($customer_orders); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $customer_orders[$i]['customer_name_en']; ?> </td>
                            <td>
                                <a href="?app=customer_purchase_order&action=insert&customer_id=<?php echo $customer_orders[$i]['customer_id'];?>">
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


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        รายการใบสั่งซื้อสินค้าของลูกค้า / Customer Order List
                    </div>
                    <div class="col-md-2" align="right">
                    <span><b>สร้างจากใบเสนอราคา : </b></span>
                    </div>
                    <div class="col-md-3">
                        
                        <select id="customer_id" name="customer_id" class="form-control select" data-live-search="true" onchange="changeURL(this);">
                            <option value="">None</option>
                            <?php 
                            for($i =  0 ; $i < count($quotations) ; $i++){
                            ?>
                            <option value="<?php echo $quotations[$i]['quotation_id'] ?>">
                                <?php echo $quotations[$i]['quotation_code'] ?>
                                <?php if($quotations[$i]['quotation_rewrite_no'] > 0){ ?><b><font color="#F00">Rewrite <?PHP echo $quotations[$i]['quotation_rewrite_no']; ?></font></b> <?PHP } ?> <?php if($quotations[$i]['quotation_cancelled'] == 1){ ?><b><font color="#F00">Cancelled</font></b> <?PHP } ?>
                            </option>
                            <?
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a class="btn btn-success " id="link" style="float:right;" href="?app=customer_purchase_order&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>

            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>วันที่รับสั่งซื้อสินค้าของลูกค้า</label>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ผู้ซื้อ </label>
                            <select id="search_customer_id" name="search_customer_id" class="form-control select"   data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>แยกตามประเภท </label>
                            <select id="status" name="status" class="form-control select" data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <option <?php if($status == '1'){?> selected <?php }?> value="1">ยังไม่มีการสั่งสินค้า</option>
                                <option <?php if($status == '2'){?> selected <?php }?> value="2">สั่งสินค้าแล้ว</option>
                                <option <?php if($status == '3'){?> selected <?php }?> value="3">ส่งสินค้ายังไม่ครบ</option>
                                <option <?php if($status == '4'){?> selected <?php }?> value="4">ส่งสินค้าครบแล้ว</option>
                            </select>
                            <p class="help-block">Example : ยังไม่มีการสั่งสินค้า.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>แสดง </label>
                            <select id="view_type" name="view_type" class="form-control select" data-live-search="true"> 
                                <option <?php if($view_type == 'paper'){?> selected <?php }?> value="paper">ตามใบสั่งซื้อ</option> 
                                <option <?php if($view_type == 'product'){?> selected <?php }?> value="product">ตามรายการสั่งซื้อ</option> 
                            </select>
                            <p class="help-block">Example : ตามใบสั่งซื้อ.</p>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                        <a href="index.php?app=customer_purchase_order" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="dataTables_length" id="dataTables-example_length">
                            
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div id="dataTables-example_filter" class="dataTables_filter">
                            
                        </div>
                    </div>
                </div>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($customer_purchase_orders),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=customer_purchase_order&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=customer_purchase_order&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=customer_purchase_order&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=customer_purchase_order&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=customer_purchase_order&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=customer_purchase_order&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <table width="100%" class="table table-striped table-bordered table-hover"  >
                            <thead>
                                <tr>
                                    <th>ลำดับ<br>No.</th>
                                    <th>วันที่รับใบสั่งซื้อ<br>PO Date</th>
                                    <th>หมายเลขใบสั่งซื้อ<br>PO No.</th>
                                    <th>ลูกค้า<br>Customer</th>
                                    <th>พนักงาน<br>Employee</th> 
                                    <th>หมายเหตุ<br>Remark</th>
                                    <th>ใบกำกับภาษี<br>Invoice</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=$page * $page_size ; $i < count($customer_purchase_orders) && $i < $page * $page_size + $page_size; $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_date']; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['customer_name']; ?></td>
                                    <td><?php echo $customer_purchase_orders[$i]['employee_name']; ?></td> 
                                    <td><?php echo $customer_purchase_orders[$i]['customer_purchase_order_remark']; ?></td>
                                    <td>-</td>
                                    <td>
                                        <?
                                            if($customer_purchase_orders[$i]['customer_purchase_order_file'] != ""){
                                        ?>
                                            <a href="../upload/customer_purchase_order/<?php echo $customer_purchase_orders[$i]['customer_purchase_order_file'];?>" target="_blank">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                            </a> 
                                        <?
                                            }
                                        ?>
                                        <a href="?app=customer_purchase_order&action=detail&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                        </a>

                                    <?php if($customer_purchase_orders[$i]['customer_purchase_order_status'] == "Waiting"){ ?>


                                        <?PHP if ( $license_sale_page == "Medium" || $license_sale_page == "High" ) { ?>
                                        <a href="?app=customer_purchase_order&action=update&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <?PHP } ?>


                                        <?PHP if ( $license_sale_page == "High" ) { ?>
                                        <a href="?app=customer_purchase_order&action=delete&id=<?php echo $customer_purchase_orders[$i]['customer_purchase_order_id'];?>" onclick="return confirm('You want to delete Customer Purchase Order : <?php echo $customer_purchase_orders[$i]['customer_purchase_order_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                        <?PHP } ?>

                                        
                                    <?php } ?>

                                    </td>

                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($customer_purchase_orders),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=customer_purchase_order&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=customer_purchase_order&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=customer_purchase_order&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=customer_purchase_order&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=customer_purchase_order&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=customer_purchase_order&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


