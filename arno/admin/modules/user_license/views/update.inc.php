<script>
    function check(){
        var license_name = document.getElementById("license_name").value;       
        license_name = $.trim(license_name); 
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
        <a href="?app=employee" class="btn btn-primary  btn-menu">พนักงาน / Employee</a>
        <a href="?app=employee_license" class="btn btn-primary active btn-menu">สิทธิ์การใช้งาน / License</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            แก้ไขสิทธิ์การใช้งาน / Edit License
                
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=employee_license&action=edit" >
                    
                    <input type="hidden" id="license_id" name="license_id"  value="<?php echo $license['license_id']?>"/>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>ชื่อสิทธิ์การใช้งาน <font color="#F00"><b>*</b></font></label>
                                <input id="license_name" name="license_name" class="form-control" value="<?php echo $license['license_name']?>">
                                <p class="help-block">Example : สิทธ์การใช้งานที่ 1.</p>
                            </div>
                        </div>
                    </div>

                     <!-- /.row (nested) -->
                     <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>1. ระบบพื้นฐาน </label>
                                <select id="license_admin_page" name="license_admin_page" class="form-control">
                                        
                                        <option <?php if($license['license_admin_page'] == 'No'){?> selected <?php } ?>>No</option>
                                        <option <?php if($license['license_admin_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                        <option <?php if($license['license_admin_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                        <option <?php if($license['license_admin_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>2. ระบบพนักงานขาย  </label>
                                <select id="license_sale_employee_page" name="license_sale_employee_page" class="form-control">
                                        
                                        <option <?php if($license['license_sale_employee_page'] == 'No'){?> selected <?php } ?>>No</option>
                                        <option <?php if($license['license_sale_employee_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                        <option <?php if($license['license_sale_employee_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                        <option <?php if($license['license_sale_employee_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>3. ระบบสั่งสินค้าทดลอง  </label>
                                <select id="license_request_page" name="license_request_page" class="form-control">
                                        
                                        <option <?php if($license['license_request_page'] == 'No'){?> selected <?php } ?>>No</option>
                                        <option <?php if($license['license_request_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                        <option <?php if($license['license_request_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                        <option <?php if($license['license_request_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>4. ระบบใบยืม  </label>
                                <select id="license_delivery_note_page" name="license_delivery_note_page" class="form-control">
                                        
                                        <option <?php if($license['license_delivery_note_page'] == 'No'){?> selected <?php } ?>>No</option>
                                        <option <?php if($license['license_delivery_note_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                        <option <?php if($license['license_delivery_note_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                        <option <?php if($license['license_delivery_note_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>5. ระบบรีกายด์สินค้า  </label>
                                <select id="license_regrind_page" name="license_regrind_page" class="form-control">
                                        
                                        <option <?php if($license['license_regrind_page'] == 'No'){?> selected <?php } ?>>No</option>
                                        <option <?php if($license['license_regrind_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                        <option <?php if($license['license_regrind_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                        <option <?php if($license['license_regrind_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>6. ระบบจัดซื้อ </label>
                                <select id="license_purchase_page" name="license_purchase_page" class="form-control">
                                        
                                <option <?php if($license['license_purchase_page'] == 'No'){?> selected <?php } ?>>No</option>
                                <option <?php if($license['license_purchase_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                <option <?php if($license['license_purchase_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                <option <?php if($license['license_purchase_page'] == 'High'){?> selected <?php } ?>>High</option>

                                    </select>
                                <p class="help-block">Example : Each.</p>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>7. ขายสินค้า  </label>
                                <select id="license_sale_page" name="license_sale_page" class="form-control">
                                        
                                <option <?php if($license['license_sale_page'] == 'No'){?> selected <?php } ?>>No</option>
                                <option <?php if($license['license_sale_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                <option <?php if($license['license_sale_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                <option <?php if($license['license_sale_page'] == 'High'){?> selected <?php } ?>>High</option>

                                    </select>
                                <p class="help-block">Example : Each.</p>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>8. ระบบคลังสินค้า  </label>
                                <select id="license_inventery_page" name="license_inventery_page" class="form-control">
                                        
                                <option <?php if($license['license_inventery_page'] == 'No'){?> selected <?php } ?>>No</option>
                                <option <?php if($license['license_inventery_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                <option <?php if($license['license_inventery_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                <option <?php if($license['license_inventery_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>9. ระบบบัญชี   </label>
                                <select id="license_account_page" name="license_account_page" class="form-control">
                                        
                                <option <?php if($license['license_account_page'] == 'No'){?> selected <?php } ?>>No</option>
                                <option <?php if($license['license_account_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                <option <?php if($license['license_account_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                <option <?php if($license['license_account_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>
                        
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>10. ระบบรายงาน  </label>
                                <select id="license_report_page" name="license_report_page" class="form-control">
                                        
                                <option <?php if($license['license_report_page'] == 'No'){?> selected <?php } ?>>No</option>
                                <option <?php if($license['license_report_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                <option <?php if($license['license_report_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                <option <?php if($license['license_report_page'] == 'High'){?> selected <?php } ?>>High</option>
                                    </select>
                                <p class="help-block">Example : No.</p>
                            </div>
                        </div>

                        
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>11. ระบบผู้จัดการ  </label>
                                <select id="license_manager_page" name="license_manager_page" class="form-control">
                                        
                                        <option <?php if($license['license_manager_page'] == 'No'){?> selected <?php } ?>>No</option>
                                        <option <?php if($license['license_manager_page'] == 'Low'){?> selected <?php } ?>>Low</option>
                                        <option <?php if($license['license_manager_page'] == 'Medium'){?> selected <?php } ?>>Medium</option>
                                        <option <?php if($license['license_manager_page'] == 'High'){?> selected <?php } ?>>High</option>
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