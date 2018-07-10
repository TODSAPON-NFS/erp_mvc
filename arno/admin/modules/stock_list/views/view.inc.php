                  
<script>
    function search(){ 
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var keyword = $("#keyword").val();

        window.location = "?app=stock_list&action=view&stock_type_id=<?PHP echo $stock_type_id?>&id=<?PHP echo $stock_group_id?>&date_start="+date_start+"&date_end="+date_end+"&keyword="+keyword;
    }
</script>
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?PHP echo $stock_group['stock_group_name'];?> </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        Stock List.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body"> 

                            <div class="row">
                                <div class="col-lg-3">
                                        <label>Date Start </label>
                                        <input type="text" id="date_start" name="date_start"  class="form-control" value="<? echo $date_start;?>" readonly/>
                                        <p class="help-block"></p>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Date End </label>
                                        <input type="text" id="date_end" name="date_end"  class="form-control" value="<? echo $date_end;?>" readonly/>
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>คำค้น <font color="#F00"><b>*</b></font></label>
                                        <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                        <p class="help-block">Example : T001.</p>
                                    </div>
                                </div>
                                <div class="col-lg-3" align="left" style="padding-top:24px;">
                                    <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                                    <a href="index.php?app=stock_list&action=view&stock_type_id=<?PHP echo $stock_type_id?>&id=<?PHP echo $stock_type_id?>" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                </div>
                                
                            </div>   

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
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($stock_list),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=stock_list&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=stock_list&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=stock_list&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=stock_list&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=stock_list&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=stock_list&page=<?PHP echo $page + 2; }?>" >Next</a>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                            </div>




                            <table width="100%" class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Product Code</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Product Status</th>
                                        <th>Old</th>
                                        <th>In</th>
                                        <th>Out</th>
                                        <th>Amount</th>
                                        <th>Minimum</th>
                                        <th>Safety</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=$page * $page_size ; $i < count($stock_list) && $i < $page * $page_size + $page_size; $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $stock_list[$i]['product_code']; ?></td>
                                        <td><?php echo $stock_list[$i]['product_name']; ?></td>
                                        <td><?php echo $stock_list[$i]['product_type']; ?></td>
                                        <td><?php echo $stock_list[$i]['product_status']; ?></td>
                                        <td><?php echo $stock_list[$i]['stock_old']; ?></td>
                                        <td><?php echo $stock_list[$i]['stock_in']; ?> (<?php echo $stock_list[$i]['stock_borrow_in']; ?>)</td>
                                        <td><?php echo $stock_list[$i]['stock_out']; ?> (<?php echo $stock_list[$i]['stock_borrow_out']; ?>)</td>
                                        <td><?php echo ($stock_list[$i]['stock_in'] - $stock_list[$i]['stock_out'] ) + $stock_list[$i]['stock_old']; ?></td>
                                        <td><a href="?app=product&action=update&id=<?php echo $stock_list[$i]['product_id']; ?>#tb-product-customer"><?php echo $stock_list[$i]['stock_minimum']; ?></a></td>
                                        <td><a href="?app=product&action=update&id=<?php echo $stock_list[$i]['product_id']; ?>#tb-product-customer"><?php echo $stock_list[$i]['stock_safety']; ?></a></td>
                                    </tr>
                                   <?
                                    }
                                   ?>
                                </tbody>
                            </table>

                            <div class="row" style="margin:0px;">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($stock_list),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=stock_list&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=stock_list&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=stock_list&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=stock_list&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=stock_list&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=stock_list&page=<?PHP echo $page + 2; }?>" >Next</a>
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
            
            
