

    <?PHP 
    require_once('../../models/RegrindSupplierModel.php');
    require_once('../../models/RegrindSupplierListModel.php');

    $regrind_supplier_id = $_GET['id'];

    $regrind_supplier_model = new RegrindSupplierModel;
    $regrind_supplier_list_model = new RegrindSupplierListModel;

    $regrind_supplier = $regrind_supplier_model->getRegrindSupplierViewByID($regrind_supplier_id);
    $regrind_supplier_lists = $regrind_supplier_list_model->getRegrindSupplierListBy($regrind_supplier_id);
    
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Revel Soft - ERP System</title>

    <!-- Bootstrap Core CSS -->
    <link href="../../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../template/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../template/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Dropdown CSS -->
    <link href="../../template/dist/css/bootstrap-select.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../template/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="../template/vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../../template/vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    


    <link rel="stylesheet" href="../../template/dist/css/jquery-ui.css">


    <!-- Main CSS -->
    <link href="../../css/styles.css" rel="stylesheet">
<style>
label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
    font-size: 12;
}

body , p, table{
    font-size: 12;
}
</style>
<!-- /.row -->
<div class="row" >
    <div class="col-lg-12" style="margin-top:32px;">
        <div class="panel panel-default">
            <div class="panel-heading" style="text-align:center;padding: 16px;font-size: 24px;">
            ใบรีกายร์สินค้าจากผู้ขาย / Regrind Supplier
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%">
                        <tr>
                            <td>
                                <table  width="100%">
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-5 col-sm-5">
                                                    <div class="form-group">
                                                        <label>รหัสผู้ขาย / Supplier Code <font color="#F00"><b>*</b></font></label>
                                                        <p class="help-block"><? echo $regrind_supplier['supplier_code'];?></p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 col-sm-7">
                                                    <div class="form-group">
                                                        <label>ผู้ขาย / Supplier  <font color="#F00"><b>*</b></font> </label>
                                                        <p class="help-block"><?php echo $regrind_supplier['supplier_name_en'] ?> (<?php echo $regrind_supplier['supplier_name_th'] ?>)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>ชื่อผู้ติดต่อ / Contact name <font color="#F00"><b>*</b></font></label>
                                                        <p class="help-block"><?PHP echo $regrind_supplier['contact_name']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>ที่อยู่ / Address <font color="#F00"><b>*</b></font></label>
                                                        <p class="help-block"><? echo $regrind_supplier['supplier_address_1'] ."\n". $regrind_supplier['supplier_address_2'] ."\n". $regrind_supplier['supplier_address_3'];?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="64">
                            </td>
                            <td>
                                <table  width="100%">
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>หมายเลขใบรีกายร์ / RG Code <font color="#F00"><b>*</b></font></label>
                                                        <p class="help-block"><?PHP echo $regrind_supplier['regrind_supplier_code']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>วันที่ออกใบรีกายร์ / RG Date</label>
                                                        <p class="help-block"><?PHP echo $regrind_supplier['regrind_supplier_date']; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>ผู้ออกเอกสารรีกายด์ / Employee  <font color="#F00"><b>*</b></font> </label>
                                                    
                                                        <p class="help-block"><?PHP echo $regrind_supplier['user_name']; ?> <?PHP echo $regrind_supplier['user_lastname']; ?> (<?PHP echo $regrind_supplier['user_position_name']; ?>)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>หมายเหตุ / Remark</label>
                                                        <p class="help-block"><?PHP echo $regrind_supplier['regrind_supplier_remark']; ?> </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table> 
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                            <tr>
                                <th style="text-align:center;" width="48">ลำดับ<br>(No.)</th>
                                <th style="text-align:center;">รหัสสินค้า<br>(Product Code)</th>
                                <th style="text-align:center;">ชื่อสินค้า<br>(Product Name)</th>
                                <th style="text-align:center;">จำนวน<br>(Qty)</th>
                                <th style="text-align:center;">หมายเหตุ<br>(Remark)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            for($i=0; $i < count($regrind_supplier_lists); $i++){
                            ?>
                            <tr class="odd gradeX">
                                <td style="text-align:center;" >
                                    <?php echo $i+1; ?>.
                                </td>
                                <td>
                                    <?php echo $regrind_supplier_lists[$i]['product_code']; ?>
                                </td>
                                <td><?php echo $regrind_supplier_lists[$i]['product_name']; ?></td>
                                <td align="right"><?php echo $regrind_supplier_lists[$i]['regrind_supplier_list_qty']; ?></td>
                                <td ><?php echo $regrind_supplier_lists[$i]['regrind_supplier_list_remark']; ?></td>
                            </tr>
                            <?
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="row" style="padding:0px;">
                            <div class="col-md-6 col-sm-6 col-xs-6 " align="center">
                            <b>ผู้รับสินค้ารีกายด์</b>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="center">
                            <b>ผู้ส่งสินค้ารีกายด์</b>
                            </div>
                    </div> 
                    <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6" align="center">
                            <img src="<?PHP echo $regrind_supplier['contact_signature'];?>"  width="72" height="72"/>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="center">
                            <img src="<?PHP echo $regrind_supplier['user_signature'];?>" width="72" height="72" />
                            </div>
                    </div> 
                    <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6" align="center">
                            <span>...............................................</span><br>
                            <B>(<?PHP echo $regrind_supplier['contact_name']; ?>)</B>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="center">
                            <span>...............................................</span><br>
                            <b>(<?PHP echo $regrind_supplier['user_name']; ?> <?PHP echo $regrind_supplier['user_lastname']; ?>)</b>
                            </div>
                    </div> 
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>

<script src="../../template/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../../template/vendor/bootstrap/js/bootstrap.min.js"></script>