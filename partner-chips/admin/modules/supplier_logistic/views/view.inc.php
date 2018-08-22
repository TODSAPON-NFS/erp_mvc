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
                Supplier Information. 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                    <label>Supplier code  </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_code']?></p>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (Thai)  </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_name_th']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Supplier name (English) </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_name_en']?></p>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Supplier Type </label>
                                        <p class="help-block"><?php  echo $supplier['supplier_type']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                
                                    <div class="form-group">
                                        <label>Tax. </label>
                                        <p class="help-block"><?php  echo $supplier['supplier_tax']?></p>
                                    </div>
                                
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Domestic </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_domestic']?></p>
                                </div>
                            </div>
                            
                            <!-- /.col-lg-6 (nested) -->
                        </div>

                        <!-- /.row (nested) -->
                        <div class="row">
                        
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <p class="help-block"><?php  echo $supplier['supplier_branch']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Zone </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_zone']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Credit Day </label>
                                    <p class="help-block"><?php  echo $supplier['credit_day']?> วัน</p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Pay Type </label>
                                    <p class="help-block"><?php  echo $supplier['condition_pay']?> </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Telephone </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_tel']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Fax </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_fax']?></p>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Email </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_email']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 1 </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_address_1']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Address 2 </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_address_2']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label>Address 3 </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_address_3']?></p>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label>Zipcode </label>
                                    <p class="help-block"><?php  echo $supplier['supplier_zipcode']?></p>
                                </div>
                            </div>
                        </div>
                        <!-- /.row (nested) -->
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Supplier Picture </label>
                                    <img class="img-responsive" id="img_logo" src="../upload/Supplier/<?php echo $supplier['supplier_logo']; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                            Supplier Logistic.
                            <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                            <a class="btn btn-success " style="float:right;" href="?app=supplier_logistic&action=insert&id=<?php  echo $supplier_id; ?>" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                            <?PHP } ?>
                            <a href="?app=supplier&action=view" class="btn btn-primary" style="float:right;margin-right:8px;">Back</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Logistic name</th>
                                        <th>Logistic detail</th>
                                        <th>Lead time</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    for($i=0; $i < count($supplier_logistic); $i++){
                                    ?>
                                    <tr class="odd gradeX">
                                        <td><?php  echo $i+1; ?></td>
                                        <td><?php  echo $supplier_logistic[$i]['supplier_logistic_name']; ?></td>
                                        <td><?php  echo $supplier_logistic[$i]['supplier_logistic_detail']; ?></td>
                                        <td><?php  echo $supplier_logistic[$i]['supplier_logistic_lead_time']; ?></td>
                                        <td>
                                        <?php if($license_admin_page == "Medium" || $license_admin_page == "High"){ ?> 
                                            <a title="Update data" href="?app=supplier_logistic&action=update&id=<?php  echo $supplier_id; ?>&subid=<?php  echo $supplier_logistic[$i]['supplier_logistic_id'];?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a> 
                                        <?PHP } ?>
                                        <?php if($license_admin_page == "High"){ ?> 
                                            <a title="Delete data" href="?app=supplier_logistic&action=delete&id=<?php  echo $supplier_id; ?>&subid=<?php  echo $supplier_logistic[$i]['supplier_logistic_id'];?>" onclick="return confirm('You want to delete Supplier logistic : <?php  echo $supplier_logistic[$i]['supplier_logistic_name']; ?>');" style="color:red;">
                                                <i class="fa fa-times" aria-hidden="true"></i>
                                            </a>
                                        <?PHP }?>
                                        </td>
                                    </tr>
                                   <?php 
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
            
            
