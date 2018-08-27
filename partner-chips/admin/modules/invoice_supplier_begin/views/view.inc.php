
<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=summit_credit&date_start="+date_start+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
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

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายการใบกำกับภาษีเจ้าหนี้ยกยอดมา / Invoice Supplier Begin List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=summit_credit&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

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

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br> No.</th>
                            <th width="150">หมายเลขรับ <br> Receive Code.</th>
                            <th width="150">วันที่ออกใบกำกับ <br> Invoice Date</th>
                            <th width="150">หมายเลขใบกำกับ <br> Invoice Code.</th>
                            <th>ผู้ขาย <br> Supplier</th>
                            <th width="150" >รับโดย <br> Recieve by</th> 
							<th>จำนวนเงิน <br> Balance</th>
                            <th width="64"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        for($i=0; $i < count($invoice_suppliers); $i++){
                            $total += $invoice_suppliers[$i]['invoice_supplier_net_price'];
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code_gen']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_date']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?></td>
                            <td><?php echo $invoice_suppliers[$i]['supplier_name']; ?> </td>
                            <td><?php echo $invoice_suppliers[$i]['employee_name']; ?></td>
                            
                            <td align="right"><?php echo number_format($invoice_suppliers[$i]['invoice_supplier_net_price'],2); ?></td>
                            <td>
                                <a href="?app=summit_credit&action=detail&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a> 

                                <a href="?app=summit_credit&action=update&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <a href="?app=summit_credit&action=delete&id=<?php echo $invoice_suppliers[$i]['invoice_supplier_id'];?>" onclick="return confirm('You want to delete Invoice Supplier : <?php echo $invoice_suppliers[$i]['invoice_supplier_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                           
                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <td colspan="6">
                            จำนวนเงินทั้งหมด / total
                        </td>
                        <td align="right">
                            <?PHP echo number_format($total,2);?>
                        </td>
                        <td>
                        </td>
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
