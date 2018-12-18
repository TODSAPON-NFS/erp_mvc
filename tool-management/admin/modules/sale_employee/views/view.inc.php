<script>
    function check(){
            var sale_id = document.getElementById("sale_id").value;
            var customer_id = document.getElementById("customer_id").value;

            if(sale_id == ''){
                alert("Please select sale employee");
                document.getElementById("sale_id").focus();
            }else if(customer_id == ''){
                alert("Please select customer");
                document.getElementById("customer_id").focus();
            }else{
                window.location = "?app=sale_employee&action=sale&customer_id="+customer_id+"&sale_id="+sale_id;
            }
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Sale Management</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        รายชื่อพนักงานขาย
                    </div> 
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>พนักงานขาย </label>
                            <select id="sale_id" name="sale_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($user) ; $i++){
                                ?>
                                <option  value="<?php echo $user[$i]['user_id'] ?>">
                                <?php 
                                    echo $user[$i]['name']; 
                                ?> 
                                </option>

                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : ธนะ.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ลูกค้า </label>
                            <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option  value="<?php echo $customers[$i]['customer_id'] ?>">
                                <?php 
                                if($user_customer[$user[$i]['user_id']][$ii]["customer_name_th"] != "" ){
                                    echo $customers[$i]['customer_name_th'];
                                }else{
                                    echo $customers[$i]['customer_name_en'];
                                } 
                                ?> 
                                </option>

                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="check();">Save</button> 
                    </div>
                </div>
                <br>

                

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr>
                            <th style="text-align:center;">ลำดับ <br>No.</th> 
                            <th style="text-align:center;">ชื่อ <br>Name</th>
                            <th style="text-align:center;">ตำแหน่ง <br>Position</th>
                            <th style="text-align:center;">โทรศัพท์ <br>Mobile</th>
                            <th style="text-align:center;">อีเมล์ <br>Email</th>
                            <th style="text-align:left;">ลูกค้า <br>Customer</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($user); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td><?php echo $i+1; ?></td> 
                            <td><?php echo $user[$i]['name']; ?> <?php echo $user[$i]['user_lastname']; ?></td>
                            <td><?php echo $user[$i]['user_position_name']; ?></td>
                            <td class="center"><?php echo $user[$i]['user_mobile']; ?></td>
                            <td class="center"><?php echo $user[$i]['user_email']; ?></td>
                            <td class="left">
                                <ul>
                                    <?PHP for($ii = 0 ; $ii < count($user_customer[$user[$i]['user_id']]) ; $ii++ ){ ?>
                                        <li>
                                            <?PHP 
                                                if($user_customer[$user[$i]['user_id']][$ii]["customer_name_th"] != "" ){
                                                    echo $user_customer[$user[$i]['user_id']][$ii]["customer_name_th"]; 
                                                }else{
                                                    echo $user_customer[$user[$i]['user_id']][$ii]["customer_name_en"]; 
                                                }
                                                ?>

                                                 <?php if($license_admin_page == "High"){ ?> 
                                                    <a href="?app=sale_employee&action=unsale&customer_id=<?php echo $user_customer[$user[$i]['user_id']][$ii]["customer_id"];?>" onclick="return confirm('คุณต้องการยกเลิกสิทธิ์การดูแลลูกค้าชื่อ : <?php echo $user_customer[$user[$i]['user_id']][$ii]["customer_name_th"]; ?>\n จากพนักงาน <?php echo $user[$i]['name']; ?>');" style="color:red;">
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                <?PHP }?>
                                        </li>
                                    <?PHP } ?>
                                </ul>

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
            
            
