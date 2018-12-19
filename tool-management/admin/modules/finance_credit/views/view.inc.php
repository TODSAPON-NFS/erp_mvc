<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Finance Credit Management</h1>
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
            ออกใบจ่ายชำระหนี้ /  Finance Credit Supplier to do
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div style="font-size:18px;padding: 8px 0px;">แยกตามผู้ขาย</div>
                        <table width="100%" class="table table-striped table-bordered table-hover" >
                            <thead>
                                <tr>
                                    <th width="64px" >No.</th>
                                    <th>Supplier</th>
                                    <th width="180px" >Open Finance Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                for($i=0; $i < count($supplier_orders); $i++){
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td>
                                        <a href="?app=finance_credit&action=insert&supplier_id=<?php echo $supplier_orders[$i]['supplier_id'];?>">
                                            <?php echo $supplier_orders[$i]['supplier_name_en']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="?app=finance_credit&action=insert&supplier_id=<?php echo $supplier_orders[$i]['supplier_id'];?>">
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
                        รายการใบจ่ายชำระหนี้ / Finance Credit List
                    </div>
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=finance_credit&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="get" action="index.php?app=finance_credit">
                    <input type="hidden" name="app" value="finance_credit" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>วันที่ออกใบจ่ายชำระหนี้</label>
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
                            <button class="btn btn-primary" style="float:right; margin:0px 4px;" type="submit">Search</button>
                            <a href="index.php?app=finance_credit" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                        </div>
                    </div>
                </form>
                <br>
                 

                <div class="row">
                    <div class="col-sm-12"> 
                        <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th width="48"> ลำดับ <br>No.</th>
                                    <th width="160">วันที่ออกใบจ่ายชำระหนี้ <br>Finance Credit Date</th>
                                    <th width="180">หมายเลขใบจ่ายชำระหนี้ <br>Finance Credit Code.</th>
                                    <th>ผู้ขาย <br>Supplier</th>
                                    <th width="150" > ผู้ออก<br>Create by</th> 
                                    <th width="150" > สมุดรายวันจ่าย<br>Journal Payment</th> 
                                    
                                    <th width="120" > จำนวนเงินรวม<br>Total</th>
                                    <th width="120" > ยอดจ่ายจริง<br>Paid</th>
                                    <th width="120" > สถานะ<br>Status</th>  
                                    <th width="64"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $finance_credit_total = 0;
                                $finance_credit_pay = 0;
                                for($i = 0  ; $i < count($finance_credits) ; $i++){
                                    $finance_credit_total += $finance_credits[$i]['finance_credit_total'];
                                    $finance_credit_pay += $finance_credits[$i]['finance_credit_pay'];
                                ?>
                                <tr class="odd gradeX">
                                    <td><?php echo $i+1; ?></td>
                                    <td><?php echo $finance_credits[$i]['finance_credit_date']; ?></td>
                                    <td><?php echo $finance_credits[$i]['finance_credit_code']; ?></td>
                                    <td><?php echo $finance_credits[$i]['supplier_name']; ?> </td>
                                    <td><?php echo $finance_credits[$i]['employee_name']; ?></td>
                                    <td>
                                        <?PHP if($finance_credits[$i]['journal_cash_payment_id'] > 0){ ?>
                                        <a target="blank" href="print.php?app=report_journal_04&type=id&action=pdf&id=<?php echo $finance_credits[$i]['journal_cash_payment_id'];?>" target="_blank"><?php echo $finance_credits[$i]['journal_cash_payment_code']; ?></a>
                                        <?PHP }else{ ?>
                                        -
                                        <?PHP }?>
                                    </td>
                                    <td align="right" ><?PHP echo number_format($finance_credits[$i]['finance_credit_total'],2); ?></td>
                                    <td align="right" ><?PHP echo number_format($finance_credits[$i]['finance_credit_pay'],2); ?></td>
                                    <td>
                                        <?PHP if($finance_credits[$i]['finance_credit_total'] == $finance_credits[$i]['finance_credit_pay']){ ?>
                                            <b>ชำระเงินครบแล้ว</b>
                                        <?PHP }else if($finance_credits[$i]['finance_credit_total'] < $finance_credits[$i]['finance_credit_pay']){ ?>
                                            <font color="red"><b>ชำระเงินเกิน</b></font>
                                        <?PHP }else{ ?> 
                                            <font color="red"><b>ยังชำระเงินไม่ครบ</b></font>
                                        <?PHP } ?>
                                    </td> 

                                    <td> 

                                        <a href="print.php?app=finance_credit&action=pdf&id=<?PHP echo $finance_credits[$i]['finance_credit_id'];?>" target="blank">
                                            <i class="fa fa-print" aria-hidden="true"></i>
                                        </a>

                                        <a href="?app=finance_credit&action=update&id=<?php echo $finance_credits[$i]['finance_credit_id'];?>">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a> 
                                        <a href="?app=finance_credit&action=delete&id=<?php echo $finance_credits[$i]['finance_credit_id'];?>" onclick="return confirm('You want to delete Finance Credit : <?php echo $finance_credits[$i]['finance_credit_code']; ?>');" style="color:red;">
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
                                    <td align="right" ><?PHP echo number_format($finance_credit_total,2); ?></td>
                                    <td align="right" ><?PHP echo number_format($finance_credit_pay,2); ?></td>
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
            
            
