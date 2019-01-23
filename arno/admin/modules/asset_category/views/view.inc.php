<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Category Management</h1>
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
                                    รายการแต่ละหมวดหมู่อุปกรณ์ / Category  List
                                </div>
                                <div class="col-md-4">
                                    <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                        <a class="btn btn-success " style="float:right;" href="?app=asset_category&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
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
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อ TH" width="200"> Name TH</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อ EN" width="200"> Name EN</th>
                                        <th  width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($asset); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td style="text-align:center;"><?php echo $i+1; ?></td>
                                        <td ><?php echo $asset[$i]['asset_category_name_th']; ?></td>
                                        <td ><?php echo $asset[$i]['asset_category_name_en']; ?></td>
                                        <td style="text-align:center;">
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a href="?app=asset_category&action=update&id=<?php echo $asset[$i]['asset_category_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP }?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a href="?app=asset_category&action=delete&id=<?php echo $asset[$i]['asset_category_id'];?>" onclick="return confirm('You want to delete asset : <?php echo $asset[$i]['asset_category_name_th']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
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
            
            
