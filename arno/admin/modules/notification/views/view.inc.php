
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php echo $type ?> Notifications</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <a href="index.php?app=notification&type=Purchase Request" class="btn btn-success"><i style="margin-right:4px;" class="fa fa-comments fa-fw fa-notify"></i>Purchase Request</a>
        <a href="index.php?app=notification&type=Purchase Order" class="btn btn-warning"><i style="margin-right:4px;" class="fa fa-tasks fa-fw fa-notify"></i>Purchase Order</a>
        <a href="index.php?app=notification&type=Customer Order" class="btn btn-info"><i class="fa fa-cart-plus fa-fw fa-notify"></i> Customer Order</a>
        <a href="index.php?app=notification&type=Supplier Approve" class="btn btn-primary"><i style="margin-right:4px;"class="fa fa-support fa-fw fa-notify"></i>Supplier Approve</a>
    </div>
    <!-- /.col-lg-12 -->
</div>
<div class="row ">
    <div class="col-lg-7 ">
        <div class="panel panel-notify-<?php if($type =="Purchase Request"){ echo "success";} else if($type =="Purchase Order"){ echo "warning";}else if($type =="Customer Order"){ echo "info";}else { echo "primary";} ?> ">
            <div class="panel-heading panel-notify ">
                <i class="fa fa-bell fa-fw"></i> Notifications list
                <span class="pull-right "  >
                    <a href="index.php?app=notification&action=all">All </a> 
                </span>
                <span class="pull-right" style="margin-right: 30px;">
                    <a href="index.php?app=notification&action=seen&type=<?php echo $type;?>" > seen</a>
                </span>
                <span class="pull-right" style="margin-right: 30px;">
                    <a href="index.php?app=notification&action=unseen&type=<?php echo $type;?>" > unseen</a>
                </span>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body-notify  scroll1">
                <div class="list-group">
                <?php for($i=0 ; $i < count($notifications) ;$i++){ ?>
                
                <a href="<?php echo $notifications[$i]['notification_url'];?>&notification=<?php echo $notifications[$i]['notification_id'];?>" class="list-group-item <?php if($notifications[$i]['notification_seen_date'] != ""){ ?>notify<? }else{ ?> notify-active <?php } ?>">

                <?php if($notifications[$i]['notification_type'] =='Purchase Request'){ ?><i class="fa fa-comments fa-fw fa-notify"></i> <?php }
                 else if ($notifications[$i]['notification_type'] =='Purchase Order'){?><i class="fa fa-tasks fa-fw fa-notify"></i> <?php }
                 else if ($notifications[$i]['notification_type'] =='Customer Order'){?><i class="fa fa-cart-plus fa-fw fa-notify"></i> <?php }
                 else {?><i class="fa fa-support fa-fw fa-notify"></i> <?php }
                 ?>
                        <?php echo $notifications[$i]['notification_detail'];?> 
                        <div class="text-muted small"><em><?php echo $notifications[$i]['notification_date'];?></em>
                        </div>
                    </a>
                <?}?>
                </div>
                <!-- /.list-group -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
    </div>
    <!-- /.col-lg-4 -->
</div>
<!-- /.row -->
