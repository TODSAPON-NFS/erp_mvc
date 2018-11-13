
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">บำรุงรักษาระบบ</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <table width="100%" class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <th width="80px">ลำดับ</th>
                    <th>ระบบ</th> 
                    <th width="120px">ดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>ระบบซื้อสินค้า</td> 
                    <td>
                        <a href="javascript:;" onclick="maintenance_purchase();">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>ระบบขายสินค้า</td> 
                    <td>
                        <a href="javascript:;" onclick="maintenance_sale();">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>ระบบคลังสินค้า</td> 
                    <td>
                        <a href="javascript:;" onclick="maintenance_stock();">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>ระบบการเงิน</td> 
                    <td>
                        <a href="javascript:;" onclick="maintenance_finance();">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>ระบบบัญชี</td> 
                    <td>
                        <a href="javascript:;" onclick="maintenance_accounts();">
                            <i class="fa fa-wrench" aria-hidden="true"></i>
                        </a> 
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="loader"></div>
        <div clas="loader-txt">
            <p>
                ระบบกำลังดำเนินการซ่อมแซมระบบ <br><br>
            </p>
            <div align="left">
                <table width="100%">
                    <tr>
                        <td> </td>
                        <td valign="middle" style="padding:4px;" width="24px">1. </td>
                        <td valign="middle" style="padding:4px;" >ระบบซื้อสินค้า</td> 
                        <td valign="middle" style="padding:4px;" width="24px">
                            <span id="span_purchase"></span>
                        </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td valign="middle" style="padding:4px;" width="24px">2. </td>
                        <td valign="middle" style="padding:4px;" >ระบบขายสินค้า</td>   
                        <td valign="middle" style="padding:4px;" width="24px">
                            <span id="span_sale"></span>
                        </td> 
                        <td> </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td valign="middle" style="padding:4px;" width="24px">3. </td>
                        <td valign="middle" style="padding:4px;" >ระบบคลังสินค้า</td>    
                        <td valign="middle" style="padding:4px;" width="24px">
                            <span id="span_stock"></span>
                        </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td valign="middle" style="padding:4px;" width="24px">4. </td>
                        <td valign="middle" style="padding:4px;" >ระบบการเงิน</td>   
                        <td valign="middle" style="padding:4px;" width="24px">
                            <span id="span_finance"></span> 
                        </td> 
                        <td> </td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td valign="middle" style="padding:4px;" width="24px">5. </td>
                        <td valign="middle" style="padding:4px;" >ระบบบัญชี</td>   
                        <td valign="middle" style="padding:4px;" width="24px">
                            <span id="span_account"></span>
                        </td> 
                        <td> </td>
                    </tr>
                </table>
            </div>
            <p>
                <small><font color="red">กรุณารอจนกว่าหน้าต่างนี้จะปิดลง</font></small>
            </p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function maintenance_purchase() {
    $("#span_purchase").html('<span class="loader-sub"></span>');
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });

    $.post( "controllers/runMaintenancePurchase.php", {}, function( data ) { 
        $("#span_purchase").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
        $("#loadMe").modal('hide');
    });
}

function maintenance_sale() {
    $("#span_purchase").html('<span class="loader-sub"></span>');
    $("#span_sale").html('<span class="loader-sub"></span>');
    $("#loadMe").modal({ 
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });
    $.post( "controllers/runMaintenancePurchase.php", {}, function( data ) { 
        $("#span_purchase").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
        $.post( "controllers/runMaintenanceSale.php", {}, function( data ) { 
            $("#span_sale").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
            $("#loadMe").modal('hide');
        });
    });
}

function maintenance_stock() {
    $("#span_purchase").html('<span class="loader-sub"></span>');
    $("#span_sale").html('<span class="loader-sub"></span>');
    $("#span_stock").html('<span class="loader-sub"></span>');
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });
    $.post( "controllers/runMaintenancePurchase.php", {}, function( data ) { 
        $("#span_purchase").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
        $.post( "controllers/runMaintenanceSale.php", {}, function( data ) { 
            $("#span_sale").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
            $.post( "controllers/runMaintenanceStock.php", {}, function( data ) { 
                $("#span_stock").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
                $("#loadMe").modal('hide');
            });
        });
    });
}

function maintenance_finance() {
    $("#span_purchase").html('<span class="loader-sub"></span>');
    $("#span_sale").html('<span class="loader-sub"></span>');
    $("#span_stock").html('<span class="loader-sub"></span>');
    $("#span_finance").html('<span class="loader-sub"></span>');
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });
    $.post( "controllers/runMaintenancePurchase.php", {}, function( data ) { 
        $("#span_purchase").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
        $.post( "controllers/runMaintenanceSale.php", {}, function( data ) { 
            $("#span_sale").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
            $.post( "controllers/runMaintenanceStock.php", {}, function( data ) { 
                $("#span_stock").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
                $.post( "controllers/runMaintenanceFinance.php", {}, function( data ) { 
                    $("#span_finance").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
                    $("#loadMe").modal('hide');
                });
            });
        });
    });

}

function maintenance_account() {
    $("#span_purchase").html('<span class="loader-sub"></span>');
    $("#span_sale").html('<span class="loader-sub"></span>');
    $("#span_stock").html('<span class="loader-sub"></span>');
    $("#span_finance").html('<span class="loader-sub"></span>');
    $("#span_account").html('<span class="loader-sub"></span>');
    $("#loadMe").modal({
      backdrop: "static", //remove ability to close modal with click
      keyboard: false, //remove option to close with keyboard
      show: true //Display loader!
    });

     $.post( "controllers/runMaintenancePurchase.php", {}, function( data ) { 
        $("#span_purchase").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
        $.post( "controllers/runMaintenanceSale.php", {}, function( data ) { 
            $("#span_sale").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
            $.post( "controllers/runMaintenanceStock.php", {}, function( data ) { 
                $("#span_stock").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
                $.post( "controllers/runMaintenanceFinance.php", {}, function( data ) { 
                    $("#span_finance").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
                    $.post( "controllers/runMaintenanceAccount.php", {}, function( data ) { 
                        $("#span_account").html('<font color="green"><i class="fa fa-check" aria-hidden="true"></i></font>');
                        $("#loadMe").modal('hide');
                    });
                });
            });
        });
    });
}


</script>

<style>

/** SPINNER CREATION **/

.loader {
  position: relative;
  text-align: center;
  margin: 15px auto 35px auto;
  z-index: 9999;
  display: block;
  width: 80px;
  height: 80px;
  border: 10px solid rgba(0, 0, 0, .3);
  border-radius: 50%;
  border-top-color: #000;
  animation: spin 1s ease-in-out infinite;
  -webkit-animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}

@-webkit-keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}


.loader-sub {
  position: relative;
  text-align: center; 
  z-index: 9999;
  display: block; 
  width: 24px;
  height: 24px;
  border: 10px solid rgba(0, 0, 0, .3);
  border-radius: 50%;
  border-top-color: #000;
  animation: spinsub 1s ease-in-out infinite;
  -webkit-animation: spin 1s ease-in-out infinite;
}

@keyframes spinsub {
  to {
    -webkit-transform: rotate(360deg);
  }
}

@-webkit-keyframes spinsub {
  to {
    -webkit-transform: rotate(360deg);
  }
}
</style>