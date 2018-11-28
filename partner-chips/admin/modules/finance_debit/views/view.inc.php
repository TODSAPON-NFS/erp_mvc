
<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Finance Debit Management</h1>
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
            ออกใบรับชำระหนี้ /  Finance Debit Customer to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="font-size:18px;padding: 8px 0px;">แยกตามผู้ซื้อ</div>
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >No.</th>
                                    <th>Customer</th>
                                    <th width="180px" >Open Finance Debit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($customer_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td>
                                        <a href="?app=finance_debit&action=insert&customer_id=<?php echo $customer_orders[$i]['customer_id'];?>">
                                        <?php echo $customer_orders[$i]['customer_name_en']; ?> 
                                        </a>
                                    </td>
                                    <td>
                                        <a href="?app=finance_debit&action=insert&customer_id=<?php echo $customer_orders[$i]['customer_id'];?>">
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
                    
                </div>
                
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
                    <div class="col-md-8">
                        รายการใบรับชำระหนี้ / Finance Debit List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=finance_debit&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=finance_debit">
                    <input type="hidden" name="app" value="finance_debit" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>วันที่ออกใบรับชำระหนี้</label>
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
                            <a href="index.php?app=finance_debit" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div>
                </form>
                <br> 

                <div class="row">
                    <div class="col-sm-12">

                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example" >
                            <thead>
                                <tr>
                                    <th width="48"> ลำดับ <br>No.</th>
                                    <th width="160">วันที่ออกใบรับชำระหนี้ <br>Finance Debit Date</th>
                                    <th width="180">หมายเลขใบรับชำระหนี้ <br>Finance Debit Code.</th>
                                    <th>ผู้ซื้อ <br>Customer</th>
                                    <th width="150" > ผู้ออก<br>Create by</th> 
                                    <th width="150" > สมุดรายวันรับ<br>Journal Receipt</th> 
                                    <th width="120" > จำนวนเงินรวม<br>Total</th>
                                    <th width="120" > ยอดรับจริง<br>Charged</th>
                                    <th width="120" > สถานะ<br>Status</th>  
                                    <th width="64"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $finance_debit_total = 0;
                                $finance_debit_pay = 0;
                                for($i = 0  ; $i < count($finance_debits) && $i < $page * $page_size + $page_size; $i++){
                                    $finance_debit_total += $finance_debits[$i]['finance_debit_total'];
                                    $finance_debit_pay += $finance_debits[$i]['finance_debit_pay'];
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $finance_debits[$i]['finance_debit_date']; ?></td>
                                    <td><?php echo $finance_debits[$i]['finance_debit_code']; ?></td>
                                    <td><?php echo $finance_debits[$i]['customer_name']; ?> </td>
                                    <td><?php echo $finance_debits[$i]['employee_name']; ?></td>
                                    <td>
                                        <?PHP if($finance_debits[$i]['journal_cash_receipt_id'] > 0){ ?>
                                        <a target="blank" href="print.php?app=report_journal_03&type=id&action=pdf&id=<?php echo $finance_debits[$i]['journal_cash_receipt_id'];?>" target="_blank"><?php echo $finance_debits[$i]['journal_cash_receipt_code']; ?></a>
                                        <?PHP }else{ ?>
                                        -
                                        <?PHP }?>
                                    </td>
                                    <td align="right" ><?PHP echo number_format($finance_debits[$i]['finance_debit_total'],2); ?></td>
                                    <td align="right" ><?PHP echo number_format($finance_debits[$i]['finance_debit_pay'],2); ?></td>
                                    <td>
                                        <?PHP if($finance_debits[$i]['finance_debit_total'] == $finance_debits[$i]['finance_debit_pay']){ ?>
                                            <b>รับชำระครบแล้ว</b>
                                        <?PHP }else if($finance_debits[$i]['finance_debit_total'] > $finance_debits[$i]['finance_debit_pay']){ ?>
                                            <font color="red"><b>ยังรับชำระไม่ครบ</b></font>
                                        <?PHP }else{ ?>
                                            <font color="red"><b>รับชำระเกินจำนวน</b></font>
                                        <?PHP } ?>
                                    </td>
                                    
                                    <td> 

                                        <a href="print.php?app=finance_debit&action=pdf&id=<?PHP echo $finance_debits[$i]['finance_debit_id'];?>" target="blank" >
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>

                                        <a href="?app=finance_debit&action=update&id=<?php echo $finance_debits[$i]['finance_debit_id'];?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <a href="?app=finance_debit&action=delete&id=<?php echo $finance_debits[$i]['finance_debit_id'];?>" onclick="return confirm('You want to delete Finance Debit : <?php echo $finance_debits[$i]['finance_debit_code']; ?>');" style="color:red;">
                                            <i class="fa fa-times" aria-hidden="true"></i>
                                        </a>
                                
                                    </td>

                                </tr>
                                <?
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6"> </td>
                                    <td align="right" ><?PHP echo number_format($finance_debit_total,2); ?></td>
                                    <td align="right" ><?PHP echo number_format($finance_debit_pay,2); ?></td>
                                    <td colspan="2"> </td> 
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
            
            
