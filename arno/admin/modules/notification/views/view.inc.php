
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php echo $type ?> Notifications</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i> Notifications list
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="list-group">
                <?php for($i=0 ; $i < count($notifications) ;$i++){ ?>
                <a href="<?php echo $notifications[$i]['notification_url'];?>" class="list-group-item <?php if($notifications[$i]['notification_seen_date'] != ""){ ?>notify<? }else{ ?> notify-active <?php } ?>">

                        <i class="fa fa-comment fa-fw"></i>
                        <?php echo $notifications[$i]['notification_detail'];?> 
                        <span class="pull-right text-muted small"><em><?php echo $notifications[$i]['notification_date'];?></em>
                        </span>
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
