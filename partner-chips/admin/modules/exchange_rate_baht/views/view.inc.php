<script>
    function check(){   
        var currency_id = document.getElementById("currency_id").value;
        var exchange_rate_baht_date = document.getElementById("exchange_rate_baht_date").value;
        var exchange_rate_baht_value = document.getElementById("exchange_rate_baht_value").value;

        currency_id = $.trim(currency_id);
        exchange_rate_baht_date = $.trim(exchange_rate_baht_date);
        exchange_rate_baht_value = $.trim(exchange_rate_baht_value);

       if(currency_id.length == 0){
            alert("Please input currency.");
            document.getElementById("currency_id").focus();
            return false;
        }else  if(exchange_rate_baht_date.length == 0){
            alert("Please input date of exchange rate baht.");
            document.getElementById("exchange_rate_baht_date").focus();
            return false;
        }else  if(exchange_rate_baht_value.length == 0){
            alert("Please input value of exchange rate baht.");
            document.getElementById("exchange_rate_baht_value").focus();
            return false;
        }else{
            return true;
        }
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">อัตราการแรกเปลี่ยน / Exchange Rate Baht</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            อัตราการแรกเปลี่ยน / Exchange Rate Baht
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <form role="form" method="post" onsubmit="return check();" <?php if($exchange_rate_baht_id == ''){ ?>action="index.php?app=exchange_rate_baht&action=add"<?php }else{?> action="index.php?app=exchange_rate_baht&action=edit&id=<? echo $exchange_rate_baht_id; ?>" <?php }?> enctype="multipart/form-data">
                    <input type="hidden" id="exchange_rate_baht_id" name="exchange_rate_baht_id" value="<?php echo $exchange_rate_baht_id?>"/>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>สกุลเงิน / Currency <font color="#F00"><b>*</b></font></label>
                                <select id="currency_id" name="currency_id"  class="form-control select" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php 
                                    for($i =  0 ; $i < count($currencies) ; $i++){
                                    ?>
                                    <option <?if($currencies[$i]['currency_id'] == $exchange_rate_baht['currency_id'] ){?> selected <?php } ?> value="<?php echo $currencies[$i]['currency_id'] ?>">[<?PHP echo $currencies[$i]['currency_code'];?>] <?PHP echo $currencies[$i]['currency_country'];?> - <?PHP echo $currencies[$i]['currency_name'];?> (<?PHP echo $currencies[$i]['currency_sign'];?>)</option>
                                    <?
                                    }
                                    ?>
                                </select>
                               <p class="help-block">Example : Dollar.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>วันที่ / Date <font color="#F00"><b>*</b></font></label>
                                <input type="text" id="exchange_rate_baht_date" name="exchange_rate_baht_date"  class="form-control calendar" value="<?php echo $exchange_rate_baht['exchange_rate_baht_date']; ?>" readonly/>
                                <p class="help-block">Example : 01-03-2018.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label>อัตราการแลกเปลี่ยน / Exchange rate <font color="#F00"><b>*</b></font></label>
                            <input type="text" id="exchange_rate_baht_value" name="exchange_rate_baht_value"  class="form-control" value="<? echo number_format($exchange_rate_baht['exchange_rate_baht_value'],5);?>" />
                            <p class="help-block">30.25</p>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <a href="?app=currency_type&action=view" class="btn btn-primary">Reset</a>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                    <br>
                </form>
                
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
                        รายการอัตราการแรกเปลี่ยน / Exchange Rate Baht List.
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form action="?app=exchange_rate_baht" method="post">

                                <div class="row">
                                    <div class="col-lg-3">
                                            <label>จากวันที่ / Date Start </label>
                                            <input type="text" id="date_start" name="date_start"  class="form-control calendar" value="<? echo $date_start;?>" readonly/>
                                            <p class="help-block"></p>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label>ถึงวันที่ / Date End </label>
                                            <input type="text" id="date_end" name="date_end"  class="form-control calendar" value="<? echo $date_end;?>" readonly/>
                                            <p class="help-block"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" align="left" style="padding-top:24px;">
                                        <button type="submit" class="btn btn-success">Veiw</button>
                                    </div>
                                    
                                </div>   
                                <br>
                            </from>
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">ลำดับ<br>No.</th>
                                        <th style="text-align:center">วันที่<br>Date</th>
                                        <th style="text-align:center">สกุลเงิน<br>Currency</th>
                                        <th style="text-align:center">อัตราการแลกเปลี่ยน<br>Exchange Rate</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($exchange_rate_bahts); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td style="text-align:center"><?php echo $i+1; ?></td>
                                        <td style="text-align:center"><?php echo $exchange_rate_bahts[$i]['exchange_rate_baht_date']; ?></td>
                                        <td style="text-align:center">[<?PHP echo $exchange_rate_bahts[$i]['currency_code'];?>] <?PHP echo $exchange_rate_bahts[$i]['currency_country'];?> - <?PHP echo $exchange_rate_bahts[$i]['currency_name'];?> (<?PHP echo $exchange_rate_bahts[$i]['currency_sign'];?>)</td>
                                        <td style="text-align:right"><?php echo $exchange_rate_bahts[$i]['exchange_rate_baht_value']; ?></td>
                                        <td>
                                            
                                            <a title="Update data" href="?app=exchange_rate_baht&action=update&id=<?php echo $exchange_rate_bahts[$i]['exchange_rate_baht_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Delete data" href="?app=exchange_rate_baht&action=delete&id=<?php echo $exchange_rate_bahts[$i]['exchange_rate_baht_id'];?>" onclick="return confirm('You want to delete exchange rate baht : <?php echo $exchange_rate_bahts[$i]['currency_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                   <?
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
            
            
