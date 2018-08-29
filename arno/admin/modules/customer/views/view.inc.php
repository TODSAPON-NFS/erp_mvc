
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Customer Management</h1>
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
                                    รายการลูกค้า / Customer List
                                </div>
                                <div class="col-md-4">
                                <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                    <a class="btn btn-success " style="float:right;" href="?app=customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="get" action="index.php?app=customer">
                                <input type="hidden" name="app" value="customer" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>กลุ่มลูกค้า <font color="#F00"><b>*</b></font></label>
                                            <select name="end_user" class="form-control">
                                                <option value="">Select all</option>
                                                <option value="0" <?PHP if($end_user == '0'){ ?> SELECTED <?PHP }?> >Dealer</option>
                                                <option value="1" <?PHP if($end_user == '1'){ ?> SELECTED <?PHP }?> >End user</option>
                                            </select>
                                            <p class="help-block">Example : End user.</p>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ประเภท <font color="#F00"><b>*</b></font></label>
                                            <select id="customer_type" name="customer_type" class="form-control">
                                                <option value="">Select all</option>
                                                <?PHP 
                                                    for($i=0; $i < count($customer_types) ; $i++){
                                                ?>
                                                    <option value="<?PHP echo $customer_types[$i]['customer_type_id'];?>" <?PHP if($customer_types[$i]['customer_type_id'] == $customer_type){?> Selected <?PHP }?>> <?PHP echo $customer_types[$i]['customer_type_name'];?></option>
                                                <?PHP
                                                    }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : End user.</p>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>คำค้น (ลูกค้า) <font color="#F00"><b>*</b></font></label>
                                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                                            <p class="help-block">Example : T001.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>คำค้น (บริษัทผู้ดูแล) <font color="#F00"><b>*</b></font></label>
                                            <input id="keyword_end" name="keyword_end" class="form-control" value="<?PHP echo $keyword_end;?>" >
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
                                        <a href="index.php?app=customer" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                                    </div>
                                </div>
                            </form>

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
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($customer),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=customer&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=customer&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=customer&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=customer&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=customer&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=customer&page=<?PHP echo $page + 2; }?>" >Next</a>
                                            </li>


                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <table width="100%" class="table table-striped table-bordered table-hover" >
                                        <thead>
                                            <tr>
                                                <th style="text-align:center">ลำดับ <br>No.</th>
                                                <th style="text-align:center">รหัสลูกค้า <br>Code</th> 
                                                <th style="text-align:center">ชื่ออังกฤษ <br>Name english</th>
                                                <th style="text-align:center">เลขผู้เสียภาษี <br>TAX ID</th>
                                                <th style="text-align:center">โทรศัพท์ <br>Mobile</th>
                                                <th style="text-align:center">ประเภทลูกค้า <br>Type</th>
                                                <th style="text-align:center">สิทธิ์การขาย <br>Sales privileges</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            for($i=0; $i < count($customer); $i++){
                                            ?>
                                            <tr class="odd gradeX">
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $customer[$i]['customer_code']; ?></td> 
                                                <td><?php echo $customer[$i]['customer_name_en']; ?></td>
                                                <td><?php echo $customer[$i]['customer_tax']; ?></td>
                                                <td class="center"><?php echo $customer[$i]['customer_tel']; ?></td>
                                                <td class="center">
                                                    <?php echo $customer[$i]['customer_type_name']; ?>
                                                    
                                                </td>
                                                <td class="center">
                                                    <?php echo $customer[$i]['customer_end_user_name']; ?>
                                                
                                                </td>
                                                <td>
                                                <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                                    <a title="View Detail" href="?app=customer&action=detail&id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                    </a> 
                                                    <a title="End users" href="?app=customer_end_users&action=view&customer_id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-user-secret" aria-hidden="true"></i>
                                                    </a> 
                                                    <a title="calendar" href="?app=customer_holiday&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                                    </a>
                                                    <a title="Bank account" href="?app=customer_account&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-university" aria-hidden="true"></i>
                                                    </a> 
                                                    <a title="Person contact" href="?app=customer_contact&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-users" aria-hidden="true"></i>
                                                    </a>
                                                    <a title="Logistic type" href="?app=customer_logistic&action=view&id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-truck" aria-hidden="true"></i>
                                                    </a>
                                                    <a title="Update data" href="?app=customer&action=update&id=<?php echo $customer[$i]['customer_id'];?>">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a> 
                                                <?PHP }?>
                                                <?php if($license_admin_page == "High"){ ?> 
                                                    <a title="Delete data" href="?app=customer&action=delete&id=<?php echo $customer[$i]['customer_id'];?>" onclick="return confirm('You want to delete customer : <?php echo $customer[$i]['customer_name']; ?>');" style="color:red;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                <?PHP }?>
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
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($customer),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=customer&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=customer&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=customer&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=customer&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=customer&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=customer&page=<?PHP echo $page + 2; }?>" >Next</a>
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
            
            
