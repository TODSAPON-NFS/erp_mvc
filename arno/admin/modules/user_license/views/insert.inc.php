<script>
    function check(){


        var license_name = document.getElementById("license_name").value;
        var license_sale_page = document.getElementById("license_sale_page").value;
        var license_purchase_page = document.getElementById("license_purchase_page").value;
        var license_inventery_page = document.getElementById("license_inventery_page").value;
        var license_manager_page = document.getElementById("license_manager_page").value;
        var license_account_page = document.getElementById("license_account_page").value;
        var license_report_page = document.getElementById("license_report_page").value;
       
       
        
        license_name = $.trim(license_name);
        license_sale_page = $.trim(license_sale_page);
        license_purchase_page = $.trim(license_purchase_page);
        license_inventery_page = $.trim(license_inventery_page);
        license_manager_page = $.trim(license_manager_page);
        license_account_page = $.trim(license_account_page);
        license_report_page = $.trim(license_report_page);
       

        if(license_name.length == 0){
            alert("Please input license name");
            document.getElementById("license_name").focus();
            return false;
        }else{
            return true;
        }



    }

</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Employee Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        <a href="?app=employee" class="btn btn-primary  btn-menu">Employee</a>
        <a href="?app=employee_license" class="btn btn-primary active btn-menu">License</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Add Employee License  
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=employee_license&action=add" >
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>License name <font color="#F00"><b>*</b></font></label>
                                <input id="license_name" name="license_name" class="form-control">
                                <p class="help-block">Example : สิทธ์การใช้งานที่ 1.</p>
                            </div>
                        </div>
                    </div>

                     <!-- /.row (nested) -->
                     <div class="row">
                       
                     <div class="col-lg-4">
                            <div class="form-group">
                                <label>Sale Page  </label>
                                <select id="license_sale_page" name="license_sale_page" class="form-control">
                                        
                                        <option>No</option>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                    </select>
                                <p class="help-block">Example : Each.</p>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Purchase Page  </label>
                                <select id="license_purchase_page" name="license_purchase_page" class="form-control">
                                        
                                        <option>No</option>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                    </select>
                                <p class="help-block">Example : Each.</p>
                            </div>
                        </div>
                        
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Manager Page  </label>
                                <select id="license_manager_page" name="license_manager_page" class="form-control">
                                        
                                        <option>No</option>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                    <div class="row">
                       
                     <div class="col-lg-4">
                            <div class="form-group">
                                <label>Inventery Page  </label>
                                <select id="license_inventery_page" name="license_inventery_page" class="form-control">
                                        
                                        <option>No</option>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>


                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Sale Page  </label>
                                <select id="license_account_page" name="license_account_page" class="form-control">
                                        
                                        <option>No</option>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>
                        
                        
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Report Page  </label>
                                <select id="license_report_page" name="license_report_page" class="form-control">
                                        
                                        <option>No</option>
                                        <option>Low</option>
                                        <option>Medium</option>
                                        <option>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>