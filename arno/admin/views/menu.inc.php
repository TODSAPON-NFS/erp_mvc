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
                        <li>
                            <div class="nav-title">ระบบพื้นฐาน</div>
                        </li>
                        <li>
                            <a href="?app=employee"><i class="fa fa-user" aria-hidden="true"></i> พนักงาน (Employee)</a>
                        </li>

                        <li>
                            <a href="?app=supplier"><i class="fa fa-building-o" aria-hidden="true"></i> ผู้ขาย (Supplier)</a>
                        </li>

                        <li>
                            <a href="?app=customer"><i class="fa fa-users" aria-hidden="true"></i> ลูกค้า (Employee)</a>
                        </li>
                        <li>
                            <a href="?app=product"><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> สินค้า (Product)</a>
                        </li>
                        <li>
                            <div class="nav-title">ระบบจัดซื้อ</div>
                        </li>
                        <li>
                            <a href="?app=regrind_supplier"><i class="fa  fa-file-o" aria-hidden="true"></i> ใบส่งรีกายร์สินค้า (Send Regrind)</a>
                        </li>
                        <li>
                            <a href="?app=regrind_supplier_receive"><i class="fa  fa-file-o" aria-hidden="true"></i> ใบรับรีกายร์สินค้า (Receive Regrind)</a>
                        </li>
                        <li>
                            <a href="?app=delivery_note_supplier"><i class="fa  fa-file-o" aria-hidden="true"></i> ใบยืมจากผู้ขาย (Supplier DN)</a>
                        </li>
                        <li>
                            <a href="?app=delivery_note_customer"><i class="fa  fa-file-o" aria-hidden="true"></i> ใบยืมลูกค้า (Customer DN)</a>
                        </li>
                        <li>
                            <a href="?app=purchase_request"><i class="fa  fa-file" aria-hidden="true"></i> ร้องขอสั่งซื้อสินค้า (PR)</a>
                        </li>
                        <li>
                            <a href="?app=customer_purchase_order"><i class="fa  fa-map" aria-hidden="true"></i> ใบสั่งซื้อลูกค้า (Customer PO) </a>
                        </li>
                        <li>
                            <a href="?app=purchase_order"><i class="fa  fa fa-file-powerpoint-o" aria-hidden="true"></i> ใบสั่งซื้อ (PO)</a>
                        </li>
                        <li>
                            <a href="?app=invoice_supplier"><i class="fa  fa-file-text-o" aria-hidden="true"></i> ใบรับสินค้า (Supplier Invoice) </a>
                        </li>
                        <li>
                            <div class="nav-title">ระบบขายสินค้า</div>
                        </li>
                        <li>
                            <a href="?app=quotation"><i class="fa fa-database fa-fw" aria-hidden="true"></i>ใบเสนอราคา (Quotation) </a>
                        </li>
                        <li>
                            <a href="?app=invoice_customer"><i class="fa  fa-file-pdf-o" aria-hidden="true"></i> ใบกำกับภาษี (Customer Invoice)</a>
                        </li>
                        <li>
                            <a href="?app=credit_note"><i class="fa  fa-credit-card" aria-hidden="true"></i> ใบลดหนี้ (Credit Note)</a>
                        </li>
                        <li>
                            <a href="?app=debit_note"><i class="fa  fa-credit-card-alt" aria-hidden="true"></i> ใบเพิ่มหนี้ (Debit Note)</a>
                        </li>
                        <li>
                            <a href="?app=billing_note"><i class="fa  fa-money" aria-hidden="true"></i> ใบวางบิล (Billing Note)</a>
                        </li>
                        <li>
                            <a href="?app=official_receipt"><i class="fa  fa-money" aria-hidden="true"></i> ใบเสร็จ (Official Receipt)</a>
                        </li>
                        <li>
                            <div class="nav-title">ระบบคลังสินค้า</div>
                        </li>
                        <li>
                            <a href="?app=search_product"><i class="fa fa-search fa-fw" aria-hidden="true"></i>ค้นหาสินค้า (Search product) </a>
                        </li>
                        <li>
                            <a href="?app=stock_type"><i class="fa fa-database fa-fw" aria-hidden="true"></i>คลังสินค้า (Stock) </a>
                        </li>
                        <li>
                            <a href="?app=stock_move"><i class="fa fa-share-square" aria-hidden="true"></i> ใบโอนคลังสินค้า (Move Stock)</a>
                        </li>
                        <li>
                            <a href="?app=stock_issue"><i class="fa fa-outdent" aria-hidden="true"></i> ใบนำออกสินค้า (Issue Stock)</a>
                        </li>
                        
                        <!-- /.nav-second-level -->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>