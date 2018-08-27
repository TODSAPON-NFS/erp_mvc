           

            <div class="row">
                <div class="col-lg-6">
                    <h1 class="page-header">Product Management</h1>
                </div>
                <div class="col-lg-6" align="right">
                    <a href="?app=product" class="btn btn-primary active btn-menu">สินค้า / Product</a>
                    <a href="?app=product_category" class="btn btn-primary btn-menu">ลักษณะ / Category</a>
                    <a href="?app=product_type" class="btn btn-primary btn-menu">ประเภท / Type</a>
                    <a href="?app=product_group" class="btn btn-primary btn-menu">กลุ่ม / Group</a>
                    <a href="?app=product_unit" class="btn btn-primary btn-menu">หน่วย / Unit</a>
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
                                    รายการสินค้า / Product List
                                </div>
                                <div class="col-md-4">
                                <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                    <a class="btn btn-success " style="float:right;" href="?app=product&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="get" action="index.php?app=product">
                                <input type="hidden" name="app" value="product" />
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ผู้ขาย / Supplier </label>
                                            <select id="supplier_id" name="supplier_id" class="form-control select"  data-live-search="true">
                                                <option value="">ทั้งหมด</option>
                                                <?php 
                                                for($i =  0 ; $i < count($suppliers) ; $i++){
                                                ?>
                                                <option <?php if($suppliers[$i]['supplier_id'] == $supplier_id){?> selected <?php }?> value="<?php echo $suppliers[$i]['supplier_id'] ?>"><?php echo $suppliers[$i]['supplier_name_th'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ลักษณะ / Category </label>
                                            <select id="product_category_id" name="product_category_id" class="form-control select"  data-live-search="true">
                                                <option value="">ทั้งหมด</option>
                                                <?php 
                                                for($i =  0 ; $i < count($product_category) ; $i++){
                                                ?>
                                                <option <?php if($product_category[$i]['product_category_id'] == $product_category_id){?> selected <?php }?> value="<?php echo $product_category[$i]['product_category_id'] ?>"><?php echo $product_category[$i]['product_category_name'] ?> </option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <p class="help-block">Example : - .</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ประเภท / Type </label>
                                            <select id="product_type_id" name="product_type_id" class="form-control select"  data-live-search="true">
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
                                        <a href="index.php?app=product" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
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
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($product),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=product&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=product&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=product&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=product&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=product&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=product&page=<?PHP echo $page + 2; }?>" >Next</a>
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
                                                <th>No.</th>
                                                <th>รหัสสินค้า <br>Product Code</th>
                                                <th>ชื่อสินค้า <br>Product Name</th>
                                                <th>รายละเอียด <br> Description</th>
                                                <th>สถานะ <br> Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php 
                                            for($i=$page * $page_size ; $i < count($product) && $i < $page * $page_size + $page_size; $i++){
                                            ?>

                                            <tr class="odd gradeX">
                                                <td><?php echo $i+1; ?></td>
                                                <td><?php echo $product[$i]['product_code']; ?></td>
                                                <td><?php echo $product[$i]['product_name']; ?></td>
                                                <td class="center"><?php echo $product[$i]['product_description']; ?></td>
                                                <td class="center"><?php echo $product[$i]['product_status']; ?></td>
                                                <td>
                                                <?
                                                    if($product[$i]['product_drawing'] != ""){
                                                ?>
                                                    <a href="../upload/product/<?php echo $product[$i]['product_drawing'];?>" target="_blank">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </a> 
                                                <?
                                                    }
                                                ?>
                                                <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                                    <a href="?app=product&action=update&id=<?php echo $product[$i]['product_id'];?>">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a> 
                                                <?PHP } ?>
                                                <?php if($license_admin_page == "High"){ ?> 
                                                    <a href="?app=product&action=delete&id=<?php echo $product[$i]['product_id'];?>" onclick="return confirm('You want to delete product : <?php echo $product[$i]['product_name']; ?>');" style="color:red;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                <?PHP } ?>
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
                                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">Showing <?PHP echo number_format($page * $page_size +1,0) ; ?> to <?PHP echo number_format($page * $page_size + $page_size,0) ; ?> of <?PHP echo number_format(count($product),0);?> entries</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dataTables_paginate paging_simple_numbers" >
                                        <ul class="pagination">

                                            <li class="paginate_button previous <?PHP if($page == 0){ ?>disabled<?PHP } ?>" >
                                                <a href="<?PHP if($page == 0){?>javascript:;<?PHP }else{ ?>index.php?app=product&page=<?PHP echo $page; }?>">Previous</a>
                                            </li>

                                            <?PHP if($page > 0){ ?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=product&page=1">1</a>
                                            </li>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <?PHP } ?>

                                                
                                            <li class="paginate_button active"  >
                                                <a href="index.php?app=product&page=<?PHP echo $page+1;?>"><?PHP echo number_format($page + 1);?></a>
                                            </li>

                                            <?PHP for($i = $page + 1 ; $i < $page_max && $i <= $page + 5 ; $i++ ){?>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=product&page=<?PHP echo $i + 1;?>"><?PHP echo number_format($i + 1,0);?></a>
                                            </li>
                                            <?PHP } ?>
                                           


                                            <?PHP if($page < $page_max){ ?>
                                            <li class="paginate_button disabled"   >
                                                <a href="#">…</a>
                                            </li>
                                            <li class="paginate_button "  >
                                                <a href="index.php?app=product&page=<?PHP echo $page_max;?>"><?PHP echo number_format($page_max,0);?></a>
                                            </li>
                                            <?PHP } ?>

                                            <li class="paginate_button next <?PHP if($page+1 == $page_max){ ?>disabled<?PHP } ?>"   >
                                                <a href="<?PHP if($page+1 == $page_max){?>javascript:;<?PHP }else{ ?>index.php?app=product&page=<?PHP echo $page + 2; }?>" >Next</a>
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
            
            
