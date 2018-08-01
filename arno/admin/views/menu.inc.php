            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Arno Thailand ERP</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw">
                            <?php if(count($notifications_new) > 0){?>
                            <span class="alert">
                                <?php echo count($notifications_new);?>
                            </span>
                            <?php } ?>
                        </i> 
                        <i class="fa fa-caret-down"></i>
                        
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                    <?php 
                    for($i=0 ; $i < count($notifications) ;$i++){ ?>
                        <li <?php if($notifications[$i]['notification_seen_date'] == ""){ ?>class="notify-active"<?php }else{ ?> class="notify" <?php } ?> >
                            <a href="<?php echo $notifications[$i]['notification_url'];?>&notification=<?php echo $notifications[$i]['notification_id'];?>" >
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> <?php echo $notifications[$i]['notification_detail'];?> 
                                    <span class="pull-right text-muted small"><?php echo $notifications[$i]['notification_date'];?></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                    <?php
                        if($i == 10){break;}
                    } 
                
                    ?>
                        <li>
                            <a class="text-center" href="index.php?app=notification">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="../logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->



            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                    <? 



                    //license_admin_page
                    if($license_admin_page == "High" || $license_admin_page == "Medium" || $license_admin_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                   $_GET["app"]=='employee'
                                || $_GET["app"]=='supplier'
                                || $_GET["app"]=='customer'
                                || $_GET["app"]=='product' 
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                 ระบบพื้นฐาน <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse"> 
                                <li>
                                    <a href="?app=employee" <?PHP if($_GET['app'] == "employee"){?> class="active" <?PHP } ?> ><i class="fa fa-user" aria-hidden="true"></i> พนักงาน (Employee)</a>
                                </li>

                                <li>
                                    <a href="?app=supplier" <?PHP if($_GET['app'] == "supplier"){?> class="active" <?PHP } ?> ><i class="fa fa-building-o" aria-hidden="true"></i> ผู้ขาย (Supplier)</a>
                                </li>

                                <li>
                                    <a href="?app=customer" <?PHP if($_GET['app'] == "customer"){?> class="active" <?PHP } ?> ><i class="fa fa-users" aria-hidden="true"></i> ลูกค้า (Customer)</a>
                                </li>
                                <li>
                                    <a href="?app=product" <?PHP if($_GET['app'] == "product"){?> class="active" <?PHP } ?> ><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> สินค้า (Product)</a>
                                </li>
                                
                            </ul>
                        </li>
                    <? 
                    }


                    //license_sale_employee_page
                    if($license_sale_employee_page == "High" || $license_sale_employee_page == "Medium" || $license_sale_employee_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='sale_employee' || 
                                $_GET["app"]=='price_list'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบพนักงานขาย <span class="glyphicon arrow"></span>
                            </a>

                            <ul class="collapse">  
                                <li>
                                    <a href="?app=sale_employee" <?PHP if($_GET['app'] == "sale_employee"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายชื่อพนักงานขาย</a>
                                </li>
                                <li>
                                    <a href="?app=price_list"  <?PHP if($_GET['app'] == "price_list"){?> class="active" <?PHP } ?>    ><i class="fa  fa-file-o" aria-hidden="true"></i> ราคาสินค้า</a>
                                </li>  
                            </ul>
                        </li>
                    <? 
                    }

                    //	license_request_page
                    if($license_request_page == "High" || $license_request_page == "Medium" || $license_request_page == "Low" ){
                    ?>

                        <li 
                        <?PHP 
                            if($_GET["app"]=='request_test'){
                                echo ' class="active" ';
                            }elseif($_GET["app"]=='request_standard'){
                                echo ' class="active" ';
                            }else if($_GET["app"]=='request_special'){
                                echo ' class="active" ';
                            }else if($_GET["app"]=='request_regrind'){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบสั่งสินค้าทดลอง <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">
                                <?PHP if($license_request_page == "High" || $license_request_page == "Medium"){ ?>
                                <li>
                                    <a href="?app=request_test" <?PHP if($_GET['app'] == "request_test"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งสินค้าทดลอง (Request Test)</a>
                                </li>
                                <?PHP } ?>


                                <li>
                                    <a href="?app=request_standard" <?PHP if($_GET['app'] == "request_standard"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้ามาตรฐาน (Standard Tool)</a>
                                </li>
                                <li>
                                    <a href="?app=request_special" <?PHP if($_GET['app'] == "request_special"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้าพิเศษ (Special Tool)</a>
                                </li>
                                <li>
                                    <a href="?app=request_regrind" <?PHP if($_GET['app'] == "request_regrind"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้ารีกายด์ (Regrind Tool)</a>
                                </li>
                               


                            </ul>
                        </li>

                    <? 
                    }


                    //	license_delivery_note_page
                    if($license_delivery_note_page == "High" || $license_delivery_note_page == "Medium" || $license_delivery_note_page == "Low" ){
                    ?>


                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='delivery_note_supplier'
                                || $_GET["app"]=='delivery_note_customer'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบใบยืม <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" > 
                                <li>
                                    <a href="?app=delivery_note_supplier" <?PHP if($_GET['app'] == "delivery_note_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบยืมจากผู้ขาย (Supplier DN)</a>
                                </li>
                                <li>
                                    <a href="?app=delivery_note_customer" <?PHP if($_GET['app'] == "delivery_note_customer"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบยืมลูกค้า (Customer DN)</a>
                                </li>
                            </ul>
                           
                        </li>
                    <? 
                    }


                    //license_regrind_page
                    if($license_regrind_page == "High" || $license_regrind_page == "Medium" || $license_regrind_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='regrind_supplier'
                                || $_GET["app"]=='regrind_supplier_receive' 
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบรีกายร์สินค้า <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" > 
                                <li>
                                    <a href="?app=regrind_supplier" <?PHP if($_GET['app'] == "regrind_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบส่งรีกายร์สินค้า (Send Regrind)</a>
                                </li>
                                <li>
                                    <a href="?app=regrind_supplier_receive" <?PHP if($_GET['app'] == "regrind_supplier_receive"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรับรีกายร์สินค้า (Receive Regrind)</a>
                                </li>
                            </ul>
                        </li>
                    <? 
                    }


                    //	license_purchase_page
                    if($license_purchase_page == "High" || $license_purchase_page == "Medium" || $license_purchase_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                  
                                   $_GET["app"]=='purchase_request'
                                || $_GET["app"]=='purchase_order'
                                || $_GET["app"]=='invoice_supplier'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบจัดซื้อ <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse" >  
                               
                                <li>
                                    <a href="?app=purchase_request" <?PHP if($_GET['app'] == "purchase_request"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ร้องขอสั่งซื้อสินค้า (PR)</a>
                                </li>
                                
                                <?PHP if($license_purchase_page == "High" || $license_purchase_page == "Medium"){ ?>
                                <li>
                                    <a href="?app=purchase_order" <?PHP if($_GET['app'] == "purchase_order"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อ (PO)</a>
                                </li>
                                <li>
                                    <a href="?app=invoice_supplier" <?PHP if($_GET['app'] == "invoice_supplier"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรับสินค้า (Supplier Invoice) </a>
                                </li>
                                <?PHP } ?>
                            </ul>
                        </li>
                    <? 
                    }


                    //license_sale_page
                    if($license_sale_page == "High" || $license_sale_page == "Medium" || $license_sale_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='quotation'
                                || $_GET["app"]=='customer_purchase_order'
                                || $_GET["app"]=='invoice_customer'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                            ระบบขายสินค้า <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">   


                                <li>
                                    <a href="?app=quotation"><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเสนอราคา (Quotation) </a>
                                </li>


                                <?PHP if($license_sale_page == "High" || $license_sale_page == "Medium" ){?>
                                <li>
                                    <a href="?app=customer_purchase_order" <?PHP if($_GET['app'] == "customer_purchase_order"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบสั่งซื้อลูกค้า (Customer PO) </a>
                                </li>
                                <li>
                                    <a href="?app=invoice_customer" <?PHP if($_GET['app'] == "invoice_customer"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบกำกับภาษี (Customer Invoice)</a>
                                </li>
                                
                                <?PHP } ?>


                            </ul>
                        </li>
                    <? 
                    }


                    //license_inventery_page
                    if($license_inventery_page == "High" || $license_inventery_page == "Medium" || $license_inventery_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                            if(
                                $_GET["app"]=='search_product'
                                || $_GET["app"]=='stock_type'
                                || $_GET["app"]=='stock_move'
                                || $_GET["app"]=='stock_issue'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบคลังสินค้า <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">    

                                <li>
                                    <a href="?app=search_product" <?PHP if($_GET['app'] == "search_product"){?> class="active" <?PHP } ?> ><i class="fa fa-search fa-fw" aria-hidden="true"></i> ค้นหาสินค้า (Search product) </a>
                                </li>

                                <li>
                                    <a href="?app=stock_type" <?PHP if($_GET['app'] == "stock_type"){?> class="active" <?PHP } ?> ><i class="fa fa-database fa-fw" aria-hidden="true"></i> คลังสินค้า (Stock) </a>
                                </li>



                                <?PHP if($license_inventery_page == "High" || $license_inventery_page == "Medium" ){?>
                                <li>
                                    <a href="?app=stock_move" <?PHP if($_GET['app'] == "stock_move"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบโอนคลังสินค้า (Transfer Stock)</a>
                                </li>
                                <li>
                                    <a href="?app=stock_issue" <?PHP if($_GET['app'] == "stock_issue"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบนำออกสินค้า (Issue Stock)</a>
                                </li>
                                <?PHP } ?>


                            </ul>
                        </li>
                    <? 
                    }


                    //license_account_page
                    if($license_account_page == "High" || $license_account_page == "Medium" || $license_account_page == "Low" ){
                    ?>

                        <li
                        <?PHP 
                             if(
                                $_GET["app"]=='account' || 
                                $_GET["app"]=='account_setting' || 
                                $_GET["app"]=='paper' ||
                                $_GET["app"]=='credit_note' || 
                                $_GET["app"]=='debit_note' || 
                                $_GET["app"]=='billing_note' || 
                                $_GET["app"]=='official_receipt'|| 
                                substr($_GET["app"],0,15) =='journal_special' ||
                                $_GET["app"] =='other_expense' ||
                                $_GET["app"] =='credit_purchasing' ||
                                $_GET["app"] =='journal_general' ||
                                substr($_GET["app"],0,15) =='journal_special' ||
                                substr($_GET["app"],0,7) =='summit_'||
                                substr($_GET["app"],0,4) =='bank'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบบัญชี <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">  
                                <li>
                                    <a href="?app=account"><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> ผังบัญชี (Account Stucture)</a>
                                </li>
                                <?PHP 
                                if($license_account_page == "High" ){
                                ?>
                                <li>
                                    <a href="?app=account_setting" <?PHP if($_GET['app'] == "account_setting"){?> class="active" <?PHP } ?> ><i class="fa  fa-cog fa-fw" aria-hidden="true"></i> กำหนดบัญชีที่ต้องลงรายวัน (Account Setting)</a>
                                </li>
                                <li>
                                    <a href="?app=paper" <?PHP if($_GET['app'] == "paper"){?> class="active" <?PHP } ?> ><i class="fa  fa-cog fa-fw" aria-hidden="true"></i> กำหนดเลขที่เอกสาร (Paper Setting)</a>
                                </li>
                                <?PHP } ?>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,7) =='summit_'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> บันทึกยอดยกมา <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=summit_dedit" <?PHP if($_GET['app'] == "summit_dedit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ลูกหนี้คงค้าง</a>
                                        </li> 
                                        <li>
                                            <a href="?app=summit_credit" <?PHP if($_GET['app'] == "summit_credit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> เจ้าหนี้คงค้าง</a>
                                        </li> 
                                        <li>
                                            <a href="?app=summit_product" <?PHP if($_GET['app'] == "summit_product"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สินค้า/วัตถุดิบ </a>
                                        </li>
                                        <li>
                                            <a href="?app=summit_check_pre_receipt" <?PHP if($_GET['app'] == "summit_check_pre_receipt"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> เช็ครับล่วงหน้า </a>
                                        </li>
                                        <li>
                                            <a href="?app=summit_check_pre_pay" <?PHP if($_GET['app'] == "summit_check_pre_pay"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> เช็คจ่ายล่วงหน้า</a>
                                        </li>
                                        <li>
                                            <a href="?app=summit_account" <?PHP if($_GET['app'] == "summit_account"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ยอดบัญชี</a>
                                        </li>
                                    </ul>
                                </li>

                                <li
                                <?PHP 
                                    if(
                                        $_GET["app"]=='account' || 
                                        $_GET["app"]=='credit_note' || 
                                        $_GET["app"]=='debit_note' || 
                                        $_GET["app"]=='billing_note' || 
                                        $_GET["app"]=='official_receipt'|| 
                                        substr($_GET["app"],0,7) =='finance_'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> การเงิน <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=finance_debit" <?PHP if($_GET['app'] == "finance_debit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รับชำระหนี้</a>
                                        </li> 
                                        <li>
                                            <a href="?app=finance_credit" <?PHP if($_GET['app'] == "finance_credit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> จ่ายชำระหนี้</a>
                                        </li> 
                                        <li>
                                            <a href="?app=credit_note" <?PHP if($_GET['app'] == "credit_note"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบลดหนี้ (Credit Note)</a>
                                        </li>
                                        <li>
                                            <a href="?app=debit_note" <?PHP if($_GET['app'] == "debit_note"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเพิ่มหนี้ (Debit Note)</a>
                                        </li>
                                        <li>
                                            <a href="?app=billing_note" <?PHP if($_GET['app'] == "billing_note"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบวางบิล (Billing Note)</a>
                                        </li>
                                        <li>
                                            <a href="?app=official_receipt" <?PHP if($_GET['app'] == "official_receipt"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ใบเสร็จ (Official Receipt)</a>
                                        </li>
                                    </ul>
                                </li>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,4) =='bank'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-university" aria-hidden="true"></i> ธนาคาร <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=bank_check_in_deposit" <?PHP if($_GET['app'] == "bank_check_in_deposit"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> บันทึกเช็คนำฝาก</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_in_pass" <?PHP if($_GET['app'] == "bank_check_in_pass"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> บันทึกเช็คผ่าน</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_in" <?PHP if($_GET['app'] == "bank_check_in"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ทะเบียนเช็ครับ</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_pay_pass" <?PHP if($_GET['app'] == "bank_check_pay_pass"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ผ่านเช็คจ่าย</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_check_pay" <?PHP if($_GET['app'] == "bank_check_pay"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ทะเบียนเช็คจ่าย</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank_account" <?PHP if($_GET['app'] == "bank_account"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายละเอียดบัญชีเงินฝาก</a>
                                        </li> 
                                        <li>
                                            <a href="?app=bank" <?PHP if($_GET['app'] == "bank"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> รายละเอียดธนาคาร</a>
                                        </li> 
                                    </ul>
                                </li>
                                

                                <li>
                                    <a href="?app=other_expense" <?PHP if($_GET['app'] == "other_expense"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ค่าใช้จ่ายอื่นๆ</a>
                                </li>
                                <li>
                                    <a href="?app=credit_purchasing" <?PHP if($_GET['app'] == "credit_purchasing"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> ซื้อเงินเชื่อ</a>
                                </li>
                                <li>
                                    <a href="?app=journal_general" <?PHP if($_GET['app'] == "journal_general"){?> class="active" <?PHP } ?> ><i class="fa fa-book" aria-hidden="true"></i> สมุดรายวันทั่วไป</a>
                                </li>
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,15) =='journal_special'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-book" aria-hidden="true"></i> สมุดรายวันเฉพาะ <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=journal_special_01" <?PHP if($_GET['app'] == "journal_special_01"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันซื่อสินค้า</a>
                                        </li> 
                                        <li>
                                            <a href="?app=journal_special_02" <?PHP if($_GET['app'] == "journal_special_02"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันขายสินค้า</a>
                                        </li> 
                                        <li>
                                            <a href="?app=journal_special_03" <?PHP if($_GET['app'] == "journal_special_03"){?> class="active" <?PHP } ?>  ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันรับเงิน</a>
                                        </li>
                                        <li>
                                            <a href="?app=journal_special_04" <?PHP if($_GET['app'] == "journal_special_04"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันจ่ายเงิน</a>
                                        </li>
                                        <li>
                                            <a href="?app=journal_special_05" <?PHP if($_GET['app'] == "journal_special_05"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันส่งคืนสินค้าและจำนวนที่ได้ลด</a>
                                        </li>
                                        <li>
                                            <a href="?app=journal_special_06" <?PHP if($_GET['app'] == "journal_special_06"){?> class="active" <?PHP } ?> ><i class="fa  fa-file-o" aria-hidden="true"></i> สมุดรายวันรับคืนสินค้าและจำนวนที่ลดให้</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    <? 
                    }


                    //license_account_page
                    if($license_report_page == "High" || $license_report_page == "Medium" || $license_report_page == "Low" ){
                    ?>
                        <li
                        <?PHP 
                             if(
                                substr($_GET["app"],0,13) =='report_debtor'
                            ){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบรายงาน <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">
                                <li
                                <?PHP 
                                    if(
                                        substr($_GET["app"],0,13) =='report_debtor'
                                    ){
                                        echo ' class="active" ';
                                    }
                                ?> 
                                >
                                    <a href="#" >
                                        <i class="fa fa-line-chart" aria-hidden="true"></i> ลูกหนี้ <span class="glyphicon arrow"></span>
                                    </a>
                                    <ul class="collapse" >
                                        <li>
                                            <a href="?app=report_debtor_01" ><i class="fa fa-outdent" aria-hidden="true"></i> รับเงินมัดจำ</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_02" ><i class="fa fa-outdent" aria-hidden="true"></i> ขายเงินสด</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_03" ><i class="fa fa-outdent" aria-hidden="true"></i> ใบสั่งขาย</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_04" ><i class="fa fa-outdent" aria-hidden="true"></i> ขายเงินเชื่อ</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_05" ><i class="fa fa-outdent" aria-hidden="true"></i> รายได้อื่นๆ</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_06" ><i class="fa fa-outdent" aria-hidden="true"></i> ใบวางบิล</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_07" ><i class="fa fa-outdent" aria-hidden="true"></i> ใบลดหนี้/รับคืน</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_08" ><i class="fa fa-outdent" aria-hidden="true"></i> รับชำระหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_09" ><i class="fa fa-outdent" aria-hidden="true"></i> ลูกหนี้คงค้าง</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_10" ><i class="fa fa-outdent" aria-hidden="true"></i> สถานะลูกหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_11" ><i class="fa fa-outdent" aria-hidden="true"></i> วิเคราะห์อายุลูกหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_12" ><i class="fa fa-outdent" aria-hidden="true"></i> รายการเคลื่อนไหวลูกหนี้</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_13" ><i class="fa fa-outdent" aria-hidden="true"></i> ลูกหนี้เกินวงเงิน</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_14" ><i class="fa fa-outdent" aria-hidden="true"></i> รายละเอียดลูกค้า</a>
                                        </li>
                                        <li>
                                            <a href="?app=report_debtor_15" ><i class="fa fa-outdent" aria-hidden="true"></i> ใบเสนอราคา</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    <? 
                    }

                    ?>

                        <li 
                        <?PHP 
                            if($_GET["app"]=='job'){
                                echo ' class="active" ';
                            }
                        ?> 
                        >
                            <a href="#" class="nav-title">
                                ระบบความรู้ <span class="glyphicon arrow"></span>
                            </a>
                            <ul class="collapse">
                                <li>
                                    <a href="?app=job" <?PHP if($_GET['app'] == "job"){?> class="active" <?PHP } ?> ><i class="fa fa-outdent" aria-hidden="true"></i> งานจัดการต้นทุน (Cost per Part)</a>
                                </li>
                            </ul>
                        </li>
                        <!-- /.nav-second-level -->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>