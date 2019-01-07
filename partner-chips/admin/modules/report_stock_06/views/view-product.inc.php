<script>
    function search(){ 
        var date_target = $("#date_target").val();
        var table_name = $("#table_name").val(); 
        var stock_start = $("#stock_start").val();
        var stock_end = $("#stock_end").val(); 
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  
        var group_by = $("#group_by").val(); 
        var paper_code = $("#paper_code").val(); 

        var table_name_text = document.getElementById('table_name').options[document.getElementById('table_name').selectedIndex ].text
        var group_by_text = document.getElementById('group_by').options[document.getElementById('group_by').selectedIndex ].text

        window.location = "index.php?app=report_stock_06&date_target="+date_target+"&table_name="+table_name+"&stock_start="+stock_start+"&stock_end="+stock_end+"&product_start="+product_start+"&product_end="+product_end+"&group_by="+group_by+"&paper_code="+paper_code+"&table_name_text="+table_name_text+"&group_by_text="+group_by_text ;
    }
    function print(type){
        var date_target = $("#date_target").val();
        var table_name = $("#table_name").val(); 
        var stock_start = $("#stock_start").val();
        var stock_end = $("#stock_end").val(); 
        var product_start = encodeURIComponent($("#product_start").val()); 
        var product_end = encodeURIComponent($("#product_end").val());  
        var group_by = $("#group_by").val(); 
        var paper_code = $("#paper_code").val(); 

        var table_name_text = document.getElementById('table_name').options[document.getElementById('table_name').selectedIndex ].text
        var group_by_text = document.getElementById('group_by').options[document.getElementById('group_by').selectedIndex ].text

        window.open("print.php?app=report_stock_06&action="+type+"&date_target="+date_target+"&table_name="+table_name+"&stock_start="+stock_start+"&stock_end="+stock_end+"&product_start="+product_start+"&product_end="+product_end+"&group_by="+group_by+"&paper_code="+paper_code+"&table_name_text="+table_name_text+"&group_by_text="+group_by_text ,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">รายงานรายการประจำวันสินค้า </h1>
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
                    รายงานรายการประจำวันสินค้า  
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>วันที่</label>
                            <div class="row">
                                <div class="col-md-11">
                                    <input type="text" id="date_target" name="date_target" value="<?PHP echo $date_target;?>"  class="form-control calendar" readonly/>
                                </div> 
                            </div>
                            <p class="help-block">01-01-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>รหัสคลัง</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="stock_start" name="stock_start" value="<?PHP echo $stock_start;?>"  class="form-control" />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="stock_end" name="stock_end" value="<?PHP echo $stock_end;?>"  class="form-control" />
                                </div>
                            </div>
                            <p class="help-block">01 - 99</p>
                        </div>
                    </div>   
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>สินค้า</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="product_start" name="product_start" value="<?PHP echo $product_start;?>"  class="form-control" />
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="product_end" name="product_end" value="<?PHP echo $product_end;?>"  class="form-control" />
                                </div>
                            </div>
                            <p class="help-block">0000-00 - 9999-99</p>
                        </div>
                    </div>  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>รหัสเอกสาร </label>
                            <input id="paper_code" name="paper_code" class="form-control" value="<?PHP echo $paper_code;?>">
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ประเภท</label>
                            <select id="table_name" name="table_name" class="form-control" >
                                <option value="">ทั้งหมด</option> 
                                <option <?php if($table_name == 'delivery_note_supplier'){?> selected <?php }?> value="delivery_note_supplier">รายการยืมเข้า</option> 
                                <option <?php if($table_name == 'delivery_note_customer'){?> selected <?php }?> value="delivery_note_customer">รายการยืมออก</option> 
                                <option <?php if($table_name == 'invoice_supplier'){?> selected <?php }?> value="invoice_supplier">รายการซื้อเข้า</option> 
                                <option <?php if($table_name == 'invoice_customer'){?> selected <?php }?> value="invoice_customer">รายการขายออก</option> 
                                <option <?php if($table_name == 'stock_move'){?> selected <?php }?> value="stock_move">รายการย้ายคลังสินค้า</option> 
                                <option <?php if($table_name == 'stock_issue'){?> selected <?php }?> value="stock_issue">รายการตัดคลังสินค้า</option> 
                                <option <?php if($table_name == 'credit_note'){?> selected <?php }?> value="credit_note">รายการใบลดหนี้</option> 
                                <option <?php if($table_name == 'regrind_supplier'){?> selected <?php }?> value="regrind_supplier">รายการใบ Regrind</option> 
                                <option <?php if($table_name == 'stock_change_product'){?> selected <?php }?> value="stock_change_product">รายการย้ายสินค้าไปยังชื่อสินค้าอื่น</option>  
                            </select>
                            <p class="help-block">Example : - .</p>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ประเภทรายงาน</label>
                            <select id="group_by" name="group_by" class="form-control" >
                                <option value="">ความเคลื่อนไหว</option> 
                                <option <?php if($group_by == 'product_code'){?> selected <?php }?> value="product_code">รายชื่อสินค้า</option> 
                                <option <?php if($group_by == 'stock_group_code'){?> selected <?php }?> value="stock_group_code">คลังสินค้า</option>  
                            </select>
                            <p class="help-block">Example : - .</p>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf','');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel','');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search('');">Search</button>
                        <a href="index.php?app=report_stock_06" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>  
                            <th width="16%" colspan="2" style="text-align:center;">ใบสำคัญ</th>     
                            <th align="center" width="28%" colspan="3" style="text-align:center;">รายการรับ</th>    
                            <th align="center" width="28%" colspan="3" style="text-align:center;">รายการจ่าย</th>    
                            <th align="center" width="28%" colspan="3" style="text-align:center;">คงเหลือ</th>    
                        </tr>
                        <tr>  
                            <th  style="text-align:center;" width="8%">วันที่<br></th>    
                            <th  style="text-align:center;" width="8%">เลขที่<br></th>    
                            <th  style="text-align:right;" width="8%">จำนวน</th>   
                            <th  style="text-align:right;" width="10%">ราคาต่อหน่วย</th>   
                            <th  style="text-align:right;" width="10%">มูลค่ารับ</th>  
                            <th  style="text-align:right;" width="8%">จำนวน</th>   
                            <th  style="text-align:right;" width="10%">ราคาต่อหน่วย</th>   
                            <th  style="text-align:right;" width="10%">มูลค่าจ่าย</th>  
                            <th  style="text-align:right;" width="8%">จำนวน</th>   
                            <th  style="text-align:right;" width="10%">ราคาต่อหน่วย</th>   
                            <th  style="text-align:right;" width="10%">มูลค่าคงเหลือ</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                 

                        for($i=0; $i < count($stock_reports); $i++){ 


                            if( $stock_reports[$i-1]['product_name'] != $stock_reports[$i]['product_name']){ 
                                $product_list++;
                                ?> 
                                <tr class="">
                                    <td colspan="2" >
                                        <b><?php echo $stock_reports[$i]['product_code']; ?></b>
                                    </td> 
                                    <td colspan="9" >
                                        <b style="color:blue;"><?php echo $stock_reports[$i]['product_name']; ?></b>
                                    </td>  
                                </tr> 
                                
                                <?PHP
                            }  
 

                        }
                        ?>
                    </tbody> 
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
