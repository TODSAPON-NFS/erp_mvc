<script>
    function search(){ 
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=report_creditor_05&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword;
    }
    function print(type){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var supplier_id = $("#supplier_id").val();
        var keyword = $("#keyword").val();

        window.open("print.php?app=report_creditor_05&action="+type+"&date_end="+date_end+"&supplier_id="+supplier_id+"&keyword="+keyword,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานเจ้าหนี้คงค้าง</h1>
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
                    รายงานเจ้าหนี้คงค้าง
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
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_creditor_05" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th width="48"> ลำดับ </th>
                            <th width="150">วันที่ </th>
                            <th>ใบรับสินค้า </th> 
                            <th>ใบกำกับภาษี </th> <?PHP if($supplier_id != '' && $supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                            <th>อัตราการเเลกเปลี่ยน <br>(บาท)  </th> 
                            <th>จำนวนเงินรวม <br>(<?PHP echo $supplier['currency_code']; ?>)  </th> 
                <?PHP }?>   <th>จำนวนเงินรวม <br> (บาท) </th> 
                            <th>ยอดจ่ายจริง <br> (บาท) </th>  
                            <th>ยอดหนี้คงเหลือ <br> (บาท) </th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $invoice_supplier_net_price_all = 0; 
                        $finance_credit_list_paid_all = 0;  
                        $invoice_supplier_balance_all = 0;
                        $index = 0;

                        for($i=0; $i < count($creditor_reports); $i++){
                            
                        if( $creditor_reports[$i-1]['supplier_code'] != $creditor_reports[$i]['supplier_code']){ 
                            $invoice_supplier_net_price = 0; 
                            $finance_credit_list_paid = 0;  
                            $invoice_supplier_balance = 0;
                            $index = 0;
                        ?> 
                        <tr class="">
                            <td colspan="7" >
                                <b>[<?php echo $creditor_reports[$i]['supplier_code']; ?>] - <?php echo $creditor_reports[$i]['invoice_supplier_name']; ?></b>
                            </td> 
                        </tr>
                        
                        <?PHP
                            }
                            $index ++;
                            $invoice_supplier_net_price +=  $creditor_reports[$i]['invoice_supplier_net_price']; 
                            $finance_credit_list_paid +=  $creditor_reports[$i]['finance_credit_list_paid'];  
                            $invoice_supplier_balance +=  $creditor_reports[$i]['invoice_supplier_balance'];  
                            
                            $invoice_supplier_net_price_all +=  $creditor_reports[$i]['invoice_supplier_net_price']; 
                            $finance_credit_list_paid_all +=  $creditor_reports[$i]['finance_credit_list_paid'];  
                            $invoice_supplier_balance_all +=  $creditor_reports[$i]['invoice_supplier_balance'];
                            $exchange_balance_EUR = $creditor_reports[$i]['invoice_supplier_balance']/$creditor_reports[$i]['exchange_rate_baht_value'];
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $index; ?></td>
                            <td><?php echo $creditor_reports[$i]['invoice_supplier_date']; ?></td> 
                            <td><?php echo $creditor_reports[$i]['invoice_supplier_code_gen']; ?></td> 
                            <td><?php echo $creditor_reports[$i]['invoice_supplier_code']; ?></td> 

                            <?PHP if($supplier_id != '' && $supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                            <td align="right"><?php echo number_format($creditor_reports[$i]['exchange_rate_baht_value'],5); ?></td>
                            <td align="right"><?php echo number_format( $exchange_balance_EUR,2); ?></td>
                            <?PHP }?> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['invoice_supplier_net_price'],2); ?>
                            </td> 
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['finance_credit_list_paid'],2); ?>
                            </td>  
                            <td  align="right" >
                                <?php echo number_format($creditor_reports[$i]['invoice_supplier_balance'],2); ?>
                            </td>  
                        </tr>
                        <?PHP
                            if($creditor_reports[$i]['supplier_code'] != $creditor_reports[$i+1]['supplier_code']){ 
                        ?>

                        <tr class="">
                            <td colspan="4" align="center" >
                               <b><font color="black"> ยอดรวมของ <?php echo $creditor_reports[$i]['invoice_supplier_name']; ?> จำนวน <?PHP echo number_format($index,0); ?> ใบ</font> </b>
                            </td> 
                            <?PHP if($supplier_id != '' && $supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                            <td align="right"></td>
                            <td align="right"></td>
                            <?PHP }?>
                            <td  align="right" ><b> </b></td> 
                            <td  align="right" ><b> </b></td> 
                            <td  align="right" ><b><?php echo number_format($invoice_supplier_balance,2); ?></b></td>  
                        </tr>
                        <tr>
                            <td colspan="7" align="center" ></td>
                        </tr>
                        <?PHP   
                            } 
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" align="center"><b>รวมทั้งหมด</b></td>
                            <?PHP if($supplier_id != '' && $supplier['supplier_domestic'] == "ภายนอกประเทศ"){ ?>
                            <td align="right"></td>
                            <td align="right"></td>
                            <?PHP }?>
                            <td  align="right" ><b><?php echo number_format($invoice_supplier_net_price_all,2); ?></b></td> 
                            <td  align="right" ><b><?php echo number_format($finance_credit_list_paid_all,2); ?></b></td> 
                            <td  align="right" ><b><?php echo number_format($invoice_supplier_balance_all,2); ?></b></td>  
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
            
            
