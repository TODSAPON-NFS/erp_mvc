<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Depreciate Management</h1>
    </div>
    <div class="col-lg-6" align="right">
    
        <!-- <a href="?app=asset" class="btn btn-primary active btn-menu">พนักงาน / Employee</a>
        <a href="?app=asset_license" class="btn btn-primary  btn-menu">สิทธิ์การใช้งาน / License</a> -->
    
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
                                    รายการทรัพย์สิน / Asset List
                                </div>
                                <div class="col-md-4">
                                    <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                        <a class="btn btn-success " style="float:right;" href="?app=asset&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                    <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="24"> No.</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัส" width="80"> Code</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อ" width="200"> Name</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่ซื้อ" width="200"> Buy Date</th>
                                        <th  width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($asset); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td style="text-align:center;"><?php echo $i+1; ?></td>
                                        <td ><?php echo $asset[$i]['asset_code']; ?></td>
                                        <td><?php echo $asset[$i]['asset_name_th']; ?></td>
                                        <td><?php echo $asset[$i]['asset_buy_date']; ?></td>
                                        <td style="text-align:center;">
                                        <?php if($asset[$i]['asset_depreciate'] =='1'){?>
                                            <a href="?app=asset&action=detail&id=<?php echo $asset[$i]['asset_id'];?>">
                                                <i style="margin-left:4px;margin-right:4px;" class="fa fa-money" aria-hidden="true"></i>
                                            </a> 
                                        <?php }?>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a href="?app=asset&action=update&id=<?php echo $asset[$i]['asset_id'];?>">
                                                <i style="margin-left:4px;margin-right:4px;" class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP }?>
                                           
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a href="?app=asset&action=delete&id=<?php echo $asset[$i]['asset_id'];?>" onclick="return confirm('You want to delete asset : <?php echo $asset[$i]['name']; ?>');" style="color:red;">
                                                <i style="margin-left:4px;margin-right:4px;" class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        <?PHP }?>
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
            
            
