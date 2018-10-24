<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=journal_special_02&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Journal Purchase Management</h1>
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
                        รายการสมุดรายวันขาย / Journal Sale List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=journal_special_02&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=journal_special_02">
                    <input type="hidden" name="app" value="journal_special_02" />
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>วันที่ออกสมุดรายวันขาย</label>
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
                            <a href="index.php?app=journal_special_02" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div>
                </form>
                <br>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($journal_sales),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=journal_special_02&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=journal_special_02&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=journal_special_02&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=journal_special_02&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=journal_special_02&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=journal_special_02&page=<?PHP echo $page + 2; }?>" >Next</a>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th>ลำดับ <br>No.</th>
                            <th>วันที่ <br>Journal Sale Date</th>
                            <th>หมายเลขสมุดรายวันขาย <br>Journal Sale No.</th>
                            <th>ชื่อสมุดรายวันขาย <br>Name.</th>
                            <th>ใบกำกับภาษี <br>Invoice.</th> 
                            <th>เดบิต <br>Debit.</th>
                            <th>เครดิต <br>Credit.</th>
                            <th>สถานะ <br>Status.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                        for($i=$page * $page_size ; $i < count($journal_sales) && $i < $page * $page_size + $page_size; $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $journal_sales[$i]['journal_sale_date']; ?></td>
                            <td><?php echo $journal_sales[$i]['journal_sale_code']; ?></td>
                            <td><?php echo $journal_sales[$i]['journal_sale_name']; ?></td>
                            <td>
                                <?php if($journal_sales[$i]['invoice_customer_id'] == 0) { ?> 
                                -
                                <?php }else{ ?>
                                <a href="?app=invoice_customer&action=update&id=<?php echo $journal_sales[$i]['invoice_customer_id']; ?>" target="_blank" ><?php echo $journal_sales[$i]['invoice_customer_code']; ?> </a>
                                <?php } ?>
                            </td> 
                            <td align="right"><?php echo number_format($journal_sales[$i]['journal_debit'],2); ?></td> 
                            <td align="right"><?php echo number_format($journal_sales[$i]['journal_credit'],2); ?></td>  
                            <td align="center">
                                <?PHP if(number_format($journal_sales[$i]['journal_debit'],2) == number_format($journal_sales[$i]['journal_credit'],2)){ ?>
                                    <font color="green"><b>ยอดตรง</b></font>
                                <?PHP } else { ?> 
                                    <font color="red"><b>ยอดไม่ตรง</b></font>
                                <?PHP } ?>
                            </td>  
                            <td>
                                <a href="print.php?app=report_journal_04&type=id&action=pdf&id=<?php echo $journal_sales[$i]['journal_sale_id'];?>" target="_blank" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=journal_special_02&action=update&id=<?php echo $journal_sales[$i]['journal_sale_id'];?>" style="color:orange;">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=journal_special_02&action=delete&id=<?php echo $journal_sales[$i]['journal_sale_id'];?>" onclick="return confirm('You want to delete Journal Cash Payment : <?php echo $journal_sales[$i]['journal_sale_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a> 
                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row" style="margin:0px;">
                    <div class="col-sm-6">
                        <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($journal_sales),0);?> entries</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="dataTables_paginate paging_simple_numbers" >
                            <ul class="pagination">

                                <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                    <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=journal_special_02&page=<?PHP echo $page; }?>">Previous</a>
                                </li>

                                <?PHP if($page > 0){ ?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=journal_special_02&page=1">1</a>
                                </li>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <?PHP } ?>

                                    
                                <li class="paginate_button active"  >
                                    <a href="index.php?app=journal_special_02&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                </li>

                                <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=journal_special_02&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                </li>
                                <?PHP } ?>
                                


                                <?PHP if($page < $page_max){ ?>
                                <li class="paginate_button disabled"   >
                                    <a href="#">…</a>
                                </li>
                                <li class="paginate_button "  >
                                    <a href="index.php?app=journal_special_02&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                </li>
                                <?PHP } ?>

                                <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                    <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=journal_special_02&page=<?PHP echo $page + 2; }?>" >Next</a>
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


