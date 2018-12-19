
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Invoice Customer Management</h1>
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
                <div class="row">
                    <div class="col-md-8">
                        รายการใบกำกับภาษีลูกหนี้ยกยอดมา / Invoice Customer Begin List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=summit_dedit&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=summit_dedit">
                    <input type="hidden" name="app" value="summit_dedit" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>วันที่ออกใบกำกับภาษี</label>
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
                                <label>ผู้ซื้อ </label>
                                <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                    <option value="">ทั้งหมด</option>
                                    <?php 
                                    for($i =  0 ; $i < count($customers) ; $i++){
                                    ?>
                                    <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> </option>
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
                            <button class="btn btn-primary" style="float:right; margin:0px 4px;" type="submit">Search</button>
                            <a href="index.php?app=invoice_customer" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div> 
                </form>
                <br>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($invoice_customers),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=summit_dedit&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_dedit&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=summit_dedit&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_dedit&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_dedit&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=summit_dedit&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>

                <table width="100%" class="table table-striped table-bordered table-hover"  >
                    <thead>
                        <tr>
                            <th width="48">ลำดับ <br>No.</th>
                            <th width="150">วันที่ออกใบกำกับภาษี<br>Invoice Date</th>
                            <th width="150">หมายเลขใบกำกับภาษี<br>Invoice Code.</th>
                            <th>ลูกค้า<br>Customer</th>
                            <th width="150" >ผู้ออก <br>Recieve by</th>
                            <th>จำนวนเงิน</th>
							
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        for($i=$page * $page_size ; $i < count($invoice_customers) && $i < $page * $page_size + $page_size; $i++){ 
                            $total += $invoice_customers[$i]['invoice_customer_net_price'];
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $invoice_customers[$i]['invoice_customer_date']; ?></td>
                            <td><?php echo $invoice_customers[$i]['invoice_customer_code']; ?></td>
                            <td><?php echo $invoice_customers[$i]['customer_name']; ?> </td>
                            <td><?php echo $invoice_customers[$i]['employee_name']; ?></td>
                            <td align="right"><?php echo number_format($invoice_customers[$i]['invoice_customer_net_price'],2); ?></td>

                            <td>
                                <a href="?app=summit_dedit&action=detail&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>

                                 <a href="index.php?app=summit_dedit&action=print&id=<?PHP echo $invoice_customers[$i]['invoice_customer_id'];?>" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>

                                <a href="?app=summit_dedit&action=update&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=summit_dedit&action=delete&id=<?php echo $invoice_customers[$i]['invoice_customer_id'];?>" onclick="return confirm('You want to delete Invoice Customer : <?php echo $invoice_customers[$i]['invoice_customer_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                           
                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <td colspan="5">จำนวนเงินรวม</td>
                        <td align="right"><?PHP echo number_format($total,2); ?></td>
                        <td></td>
                    </tfoot>
                </table>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($invoice_customers),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=summit_dedit&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_dedit&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=summit_dedit&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_dedit&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=summit_dedit&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=summit_dedit&page=<?PHP echo $page + 2; }?>" >Next</a>
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
            
            
