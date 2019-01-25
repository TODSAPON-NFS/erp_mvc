<script>

    function check_code(id){
        var code = $(id).val();
        $.post( "controllers/getAssetByCode.php", { 'asset_code': code }, function( data ) {  
            if(data != null){ 
                alert("This "+code+" is already in the system.");
                document.getElementById("asset_code").focus();
                $("#code_check").val(data.asset_code);
                
            } else{
                $("#code_check").val("");
            }
        });
    }

    function check(){


        var asset_code = document.getElementById("asset_code").value;
        var asset_name_th = document.getElementById("asset_name_th").value;
        var asset_name_en = document.getElementById("asset_name_en").value;
        var asset_category_id = document.getElementById("asset_category_id").value;
        var asset_registration_no = document.getElementById("asset_registration_no").value;
        var asset_department_id = document.getElementById("asset_department_id").value;
        var asset_depreciate = document.getElementById("asset_depreciate").value;
        var asset_buy_date = document.getElementById("asset_buy_date").value;
        var asset_use_date = document.getElementById("asset_use_date").value;
        var asset_cost_price = document.getElementById("asset_cost_price").value;
        var asset_scrap_price = document.getElementById("asset_scrap_price").value;
        var asset_expire = document.getElementById("asset_expire").value;
        var asset_rate = document.getElementById("asset_rate").value;
        var asset_depreciate_type = document.getElementById("asset_depreciate_type").value;
        var asset_depreciate_transfer = document.getElementById("asset_depreciate_transfer").value;
        var asset_depreciate_manual = document.getElementById("asset_depreciate_manual").value;
        var asset_depreciate_initial = document.getElementById("asset_depreciate_initial").value;
        var asset_manual_date = document.getElementById("asset_manual_date").value;
        var asset_sale_date = document.getElementById("asset_sale_date").value;
        var asset_price = document.getElementById("asset_price").value;
        var asset_income = document.getElementById("asset_income").value;
        var asset_id = document.getElementById("asset_id").value;
        var code_check = document.getElementById("code_check").value;
        
        asset_code = $.trim(asset_code);
        asset_name_th = $.trim(asset_name_th);
        asset_name_en = $.trim(asset_name_en);
        asset_category_id = $.trim(asset_category_id);
        asset_registration_no = $.trim(asset_registration_no);
        asset_department_id = $.trim(asset_department_id);
        asset_depreciate = $.trim(asset_depreciate);
        asset_buy_date = $.trim(asset_buy_date);
        asset_use_date = $.trim(asset_use_date);
        asset_cost_price = $.trim(asset_cost_price);
        asset_scrap_price = $.trim(asset_scrap_price);
        asset_expire = $.trim(asset_expire);
        asset_rate = $.trim(asset_rate);
        asset_depreciate_type = $.trim(asset_depreciate_type);
        asset_depreciate_transfer = $.trim(asset_depreciate_transfer);
        asset_depreciate_manual = $.trim(asset_depreciate_manual);
        asset_depreciate_initial = $.trim(asset_depreciate_initial);
        asset_manual_date = $.trim(asset_manual_date);
        asset_sale_date = $.trim(asset_sale_date);
        asset_price = $.trim(asset_price);
        asset_income = $.trim(asset_income);

        

        if(code_check != "" && code_check != asset_id){
            alert("This "+code_check+" is already in the system.");
            document.getElementById("code_check").focus();
            return false;
        }else if(asset_code.length == 0){
            alert("Please input asset code");
            document.getElementById("asset_code").focus();
            return false;
        }else if(asset_name_th.length == 0){
            alert("Please input asset name th");
            document.getElementById("asset_name_th").focus();
            return false;
        }else if(asset_name_en.length == 0){
            alert("Please input asset name en ");
            document.getElementById("asset_name_en").focus();
            return false;
        }else if(asset_buy_date.length == 0){
            alert("Please input asset buy date");
            document.getElementById("asset_buy_date").focus();
            return false;
        }else if(asset_use_date.length == 0){
            alert("Please input asset use date");
            document.getElementById("asset_use_date").focus();
            return false;
        }else if(asset_scrap_price.length == 0){
            alert("Please input asset scrap price");
            document.getElementById("asset_scrap_price").focus();
            return false;
        }else if(asset_expire.length == 0){
            alert("Please input asset expire");
            document.getElementById("asset_expire").focus();
            return false;
        }else if(asset_rate.length == 0){
            alert("Please input asset rate");
            document.getElementById("asset_rate").focus();
            return false;
        }
        else{
            return true;
        }



    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">ค่าเสื่อมทรัพย์สิน Depreciate</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=asset" class="btn btn-primary active btn-menu">ทรัพย์สิน / Asset</a>
        <!-- <a href="?app=asset_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a> -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            การเสื่อมทรัพย์สิน / Asset Depreciate
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form  id="form_target" role="form" method="post" onsubmit="return check();" action="index.php?app=asset&action=edit" >
                    <input type="hidden"  id="asset_id" name="asset_id" value="<?php echo $asset_id ?>" />
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>รหัสทรัพย์สิน / Asset Code <font color="#F00"><b>*</b></font></label>
                                <input readonly id="asset_code" name="asset_code" class="form-control" value="<?php echo $asset['asset_code']?>" onchange="check_code(this)" />
                                <input id="code_check" type="hidden" value="" />
                                <p class="help-block">Example : 0000001.</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อทรัพย์สิน TH/ Name TH<font color="#F00"><b>*</b></font></label>
                                    <input readonly id="asset_name_th" name="asset_name_th" value="<?php echo $asset['asset_name_th']?>" class="form-control">
                                    <p class="help-block">Example : คอมพิวเตอร์.</p>
                                </div>
                            
                        </div>
                        <div class="col-lg-4">
                            
                                <div class="form-group">
                                    <label>ชื่อทรัพย์สิน EN/  Name EN <font color="#F00"><b>*</b></font></label>
                                    <input readonly id="asset_name_en" name="asset_name_en" value="<?php echo $asset['asset_name_en']?>" class="form-control">
                                    <p class="help-block">Example : Computer.</p>
                                </div>
                        </div>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->

                   
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                        รายละเอียด 
                    </div>
                    
                </div>
            </div>
            <!-- /.panel-heading -->
            
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example2">
                    <thead>
                        <tr>
                            <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="No" width="24"> งวด</th>
                            <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="Calculate" width="24"> การคำนวณ</th>
                            <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="Depreciate" width="80"> ค่าเสื่อม</th>
                            <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="Net price" width="200"> ราคาสุทธิ</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $expire = $asset['asset_expire']; 
                        $rate = $asset['asset_rate'];                                     
                        $cost_price = $asset['asset_cost_price']; 
                        $scrap_price = $asset['asset_scrap_price']; 
                        $net_price = $cost_price ;
                        $calculate_price = $cost_price - $scrap_price;
                        $depreciate = 0;
                        $added=0;

                        $use_date = $asset['asset_use_date'];
                        $date = DateTime::createFromFormat('d-m-Y',  $use_date);
                        $YY = intval($date->format('Y'));
                        $mm = intval($date->format('m'));
                        $dd = intval($date->format('d'));                    
                        $maxDays=intval($date->format('t'));  
                        $rest_of_day = $maxDays - $dd;
                        echo $rest_of_day;
                        $number = cal_days_in_month(CAL_GREGORIAN, 8, 2003);
                        ?>
                        <?php
                        for($i=0; $i < $expire; $i++){
                            for($j = 1; $j<=12; $j++){

                                ?>
                                <tr class="odd gradeX">
                                    <td style="text-align:center;">เดือนที่  <?php echo $j; ?>ปี <?php echo $YY+$i;?></td>
                                    <td style="text-align:center;">
                                        <?php 
                                        $days = cal_days_in_month(CAL_GREGORIAN, $j, $YY+$i);
                                        echo number_format($calculate_price,2)."x".($rate/100)." /(365x".$days.")";
                                        ?>                                        
                                    </td>
                                    <td style="text-align:right;">
                                        <?php  
                                        $result = ($calculate_price * ($rate/100)) / 365* $days;
                                        echo number_format($result,2);
                                        ?>
                                    </td>                                       
                                    <td style="text-align:right;">
                                        <?php  
                                            $net_price = $net_price-$result; 
                                            echo number_format($net_price,2);
                                        ?>
                                    </td>                                        
                                </tr>
                            <?php 
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
<div class="row">
<?php 
    $use_date = $asset['asset_use_date'];
    $date = DateTime::createFromFormat('d-m-Y',  '01-02-2020');
    $YY = intval($date->format('Y'));
    $mm = intval($date->format('m'));
    $dd = intval($date->format('d'));                    
    $maxDays=intval($date->format('t'));  
    $rest_of_day = $maxDays - $dd;
    echo $maxDays;
    // $currentDayOfMonth=date('j');

    // if($maxDays == $currentDayOfMonth){
    // //Last day of month
    // }else{
    // //Not last day of the month
    // }

    ?>
</div>
<script>

$(document).ready(function($){
    $('#dataTables-example2').dataTable( {
        "ordering": false,
        "pageLength": 12
        } );
        
	});
</script>