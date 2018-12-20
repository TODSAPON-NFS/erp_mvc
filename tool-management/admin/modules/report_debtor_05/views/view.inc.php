<script>
    function search(){ 
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_debtor_05&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.open("print.php?app=report_debtor_05&action="+type+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานลูกหนี้คงค้าง</h1>
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
                    รายงานลูกหนี้คงค้าง
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
            <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ถึงวันที่</label>
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                            <p class="help-block">01-01-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ลูกค้า </label>
                            <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>

                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?> (<?php echo $customers[$i]['customer_name_th'] ?>)</option>
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
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_debtor_05" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ <br>No.</th>
                            <th width="150">วันที่ <br>Date</th>
                            <th>ใบกำกับภาษี <br>Invoice</th> 
                            <th>จำนวนเงินรวม<br>Total</th> 
                            <th>ยอดรับจริง <br>Charged</th>  
                            <th>ยอดหนี้คงเหลือ <br>Balance</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $invoice_customer_net_price_all = 0; 
                        $finance_debit_list_paid_all = 0;  
                        $invoice_customer_balance_all = 0;
                        $index = 0;

                        for($i=0; $i < count($debtor_reports); $i++){
                            
                        if( $debtor_reports[$i-1]['customer_code'] != $debtor_reports[$i]['customer_code']){ 
                            $invoice_customer_net_price = 0; 
                            $finance_debit_list_paid = 0;  
                            $invoice_customer_balance = 0;
                            $index = 0;
                        ?> 
                        <tr class="">
                            <td colspan="6" >
                                <b>[<?php echo $debtor_reports[$i]['customer_code']; ?>] <?php echo $debtor_reports[$i]['billing_note_name']; ?></b>
                            </td> 
                        </tr>
                        
                        <?PHP
                            }
                            $index ++;
                            $invoice_customer_net_price +=  $debtor_reports[$i]['invoice_customer_net_price']; 
                            $finance_debit_list_paid +=  $debtor_reports[$i]['finance_debit_list_paid'];  
                            $invoice_customer_balance +=  $debtor_reports[$i]['invoice_customer_balance'];  
                            
                            $invoice_customer_net_price_all +=  $debtor_reports[$i]['invoice_customer_net_price']; 
                            $finance_debit_list_paid_all +=  $debtor_reports[$i]['finance_debit_list_paid'];  
                            $invoice_customer_balance_all +=  $debtor_reports[$i]['invoice_customer_balance'];  
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $index; ?></td>
                            <td><?php echo $debtor_reports[$i]['invoice_customer_date']; ?></td> 
                            <td><?php echo $debtor_reports[$i]['invoice_customer_code']; ?></td> 
                            <td  align="right" >
                                <?php echo number_format($debtor_reports[$i]['invoice_customer_net_price'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($debtor_reports[$i]['finance_debit_list_paid'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($debtor_reports[$i]['invoice_customer_balance'],2); ?>
                            </td>  
                        </tr>
                        <?PHP
                            if($debtor_reports[$i]['customer_code'] != $debtor_reports[$i+1]['customer_code']){ 
                        ?>
                        <tr class="">
                            <td colspan="3" align="center" >
                               <b><font color="black"> ยอดรวมของ <?php echo $debtor_reports[$i]['billing_note_name']; ?> จำนวน <?PHP echo number_format($index,0); ?> ใบ</font> </b>
                            </td> 
                            <td  align="right" ><b> </b></td> 
                            <td  align="right" ><b> </b></td> 
                            <td  align="right" ><b><?php echo number_format($invoice_customer_balance,2); ?></b></td>  
                        </tr>
                        <tr>
                            <td colspan="6" align="center" ></td>
                        </tr>
                        <?PHP   
                            } 
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" align="center"><b>รวมทั้งหมด</b></td>
                            <td  align="right" ><b><?php echo number_format($invoice_customer_net_price_all,2); ?></b></td> 
                            <td  align="right" ><b><?php echo number_format($finance_debit_list_paid_all,2); ?></b></td> 
                            <td  align="right" ><b><?php echo number_format($invoice_customer_balance_all,2); ?></b></td>  
                        </tr>
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
