            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Supplier Management</h1>
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
                                    Supplier List
                                </div>
                                <div class="col-md-4">
                                <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                    <a class="btn btn-success " style="float:right;" href="?app=supplier&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                                <?PHP } ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="24"> No.</th>
                                        <th style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="รหัสลูกค้า" width="50">Code</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่อไทย" width="200"> Name thai</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ชื่ออังกฤษ" width="200"> Name english</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="บริษัท" width="200"> Domestic</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="เลขผู้เสียภาษี" width="100"> TAX ID</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="โทรศัพท์" width="80"> Mobile</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="อีเมล์" width="100"> Email</th>
                                        <th style="text-align:center;"class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="" width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($supplier); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td  style="text-align:center;"><?php echo $i+1; ?></td>
                                        <td style="text-align:center;"><?php echo $supplier[$i]['supplier_code']; ?></td>
                                        <td><?php echo $supplier[$i]['supplier_name_th']; ?></td>
                                        <td><?php echo $supplier[$i]['supplier_name_en']; ?></td>
                                        <td><?php echo $supplier[$i]['supplier_domestic']; ?></td>
                                        <td><?php echo $supplier[$i]['supplier_tax']; ?></td>
                                        <td class="center"><?php echo $supplier[$i]['supplier_tel']; ?></td>
                                        <td class="center"><?php echo $supplier[$i]['supplier_email']; ?></td>
                                        <td  style="text-align:center;">
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a title="View Detail" href="?app=supplier&action=detail&id=<?php echo $supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Bank account" href="?app=supplier_account&action=view&id=<?php echo $supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-university" aria-hidden="true"></i>
                                            </a> 
                                            <a title="Person contact" href="?app=supplier_contact&action=view&id=<?php echo $supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-users" aria-hidden="true"></i>
                                            </a>
                                            <a title="Logistic type" href="?app=supplier_logistic&action=view&id=<?php echo $supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-truck" aria-hidden="true"></i>
                                            </a>
                                            <a title="Update data" href="?app=supplier&action=update&id=<?php echo $supplier[$i]['supplier_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a title="Delete data" href="?app=supplier&action=delete&id=<?php echo $supplier[$i]['supplier_id'];?>" onclick="return confirm('You want to delete Supplier : <?php echo $supplier[$i]['supplier_name']; ?>');" style="color:red;">
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
            
            
