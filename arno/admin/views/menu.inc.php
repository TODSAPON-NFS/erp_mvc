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
                            <a href="?app=employee"><i class="fa fa-user" aria-hidden="true"></i> Employee</a>
                        </li>

                        <li>
                            <a href="?app=supplier"><i class="fa fa-building-o" aria-hidden="true"></i> Supplier</a>
                        </li>

                        <li>
                            <a href="?app=customer"><i class="fa fa-users" aria-hidden="true"></i> Customer</a>
                        </li>
                        <li>
                            <a href="?app=product"><i class="fa  fa-cubes fa-fw" aria-hidden="true"></i> Product</a>
                        </li>
                        <li>
                            <a href="?app=delivery_note"><i class="fa  fa-file-o" aria-hidden="true"></i> Delivery Note (DN)</a>
                        </li>
                        <li>
                            <a href="?app=purchase_request"><i class="fa  fa-file" aria-hidden="true"></i> Purchase Request (PR)</a>
                        </li>
                        <li>
                            <a href="?app=customer_order"><i class="fa  fa-map" aria-hidden="true"></i> Customer Order </a>
                        </li>
                        <li>
                            <a href="?app=customer_order"><i class="fa  fa-file-text-o" aria-hidden="true"></i> Supplier Invoice </a>
                        </li>
                        <li>
                            <a href="?app=purchase_order"><i class="fa  fa fa-file-powerpoint-o" aria-hidden="true"></i> Purchase Order (PO)</a>
                        </li>
                        <li>
                            <a href="?app=invoice"><i class="fa  fa-file-pdf-o" aria-hidden="true"></i> Invoice</a>
                        </li>
                        <li>
                            <a href="?app=move_stock"><i class="fa fa-database fa-fw" aria-hidden="true"></i> Move Stock</a>
                        </li>
                        <!-- /.nav-second-level -->
                        <li>
                            <a href="#"><i class="fa  fa-archive fa-fw"></i> Stock Main<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="?app=stock_list">Stock List </a>
                                </li>
                                <li>
                                    <a href="?app=stock_in">Stock In </a>
                                </li>
                                <li>
                                    <a href="?app=stock_out">Stock Out </a>
                                </li>
                                <li>
                                    <a href="?app=stock_borrow_in">Stock Borrow In </a>
                                </li>
                                <li>
                                    <a href="?app=stock_borrow_out">Stock Borrow Out </a>
                                </li>
                            </ul> 
                        </li>
                        <!-- /.nav-second-level -->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>