
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
    <div class="col-lg-6 ">
        <div class="panel panel-notify-<?php echo $type_color;?>">
            <div class="panel-heading panel-notify ">
                <div class="btn-group">
                
                    

                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <!-- <input onclick="check_all();"type="checkbox" id="checkAll" name="select_all" value="Bike"> -->
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </button>
                    
                    <ul class="dropdown-menu" role="menu">
                        <li><a style="color:black;" href="javascript:;" onclick="check_all();">All</a></li>
                        <li><a style="color:black;" href="javascript:;" onclick="seen();">seen</a></li>
                        <li><a style="color:black;" href="javascript:;" onclick="unseen();">Unseen</a></li>
                        <li><a style="color:black;" href="javascript:;" onclick="noSelect();">NoSelect</a></li>
                    </ul>
                </div>
                <!-- <button data-toggle="tooltip" data-placement="top" title="ทำเครื่องหมายว่าอ่านแล้ว!" type="button"  class="btn"></button> -->
                <button type="button" class="btn btn-<?php echo $type_color;?>" data-toggle="tooltip" data-placement="top" title="ทำเครื่องหมายว่าอ่านแล้ว!" onclick="set_seen();" id="read_btn"  >
                    <i class="fa fa-envelope-open fa-fw"></i>
                </button>
                <button type="button" class="btn btn-<?php echo $type_color;?>" data-toggle="tooltip" data-placement="top" title="ทำเครื่องหมายว่ายังไม่อ่าน!" onclick="set_unseen();" id="unread_btn"  >
                    <i class="fa fa-envelope fa-fw"></i>
                </button>
                <button type="button" class="btn btn-<?php echo $type_color;?>" data-toggle="tooltip" data-placement="top" title="ลบ!" onclick="set_delete();" id="delete_btn"  >
                    <i class="fa fa-trash-o fa-fw"></i>
                </button>
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
                <form id="set_form" action="?app=notification&action-set=setseen&action=<?php echo $seen_type;?>&type=<?php echo $type;?>" method="post">
                    <?php for($i=0 ; $i < count($notifications) ;$i++){ ?>
                    
                    <a href="<?php echo $notifications[$i]['notification_url'];?>&notification=<?php echo $notifications[$i]['notification_id'];?>" class="list-group-item <?php if($notifications[$i]['notification_seen_date'] != ""){ ?>notify<? }else{ ?> notify-active <?php } ?>">
                    <input value="<?php echo $notifications[$i]['notification_id'];?>" type="checkbox" class="check" name="notification_id[]" onclick="OnSelect();" value-type="<?php if($notifications[$i]['notification_seen_date'] != ""){ ?>seen<? }else{ ?>unseen<?php } ?>">

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
                </form>
                
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

<script>
$('#read_btn').hide(); 
$('#unread_btn').hide();
$('#delete_btn').hide();
$(document).ready(function($){
  $('[data-toggle="tooltip"]').tooltip();  
  
});
function check_all(){
    $('input[name="notification_id[]"]').prop('checked', true);
    OnSelect();
}

function seen(){
    $('input[name="notification_id[]"]').prop('checked', false);
    $('input[value-type="seen"]').prop('checked', true);
    OnSelect();
}
function unseen(){
    $('input[name="notification_id[]"]').prop('checked', false);
    $('input[value-type="unseen"]').prop('checked', true);
    OnSelect();
}
function noSelect(){
    $('input[name="notification_id[]"]').prop('checked', false);
    OnSelect();
}

function OnSelect(){
    $('#read_btn').hide();
    $('#unread_btn').hide();
    $('#delete_btn').hide();
    var notification_id =  $('input[name="notification_id[]"]');
    for(var i=0;i< notification_id.length;i++){
        if($(notification_id[i]).prop('checked')==true){
            $('#read_btn').show();
            $('#unread_btn').show();
            $('#delete_btn').show();
            break;
        }
    }
}
function set_seen(){
    $('#set_form').prop('action', '?app=notification&action-set=setseen&action=<?php echo $seen_type;?>&type=<?php echo $type;?>');
    $('#set_form').submit();
}
function set_unseen(){
    $('#set_form').prop('action', '?app=notification&action-set=setunseen&action=<?php echo $seen_type;?>&type=<?php echo $type;?>');
    $('#set_form').submit();
}
function set_delete(){
    $('#set_form').prop('action', '?app=notification&action-set=setdelete&action=<?php echo $seen_type;?>&type=<?php echo $type;?>');
    $('#set_form').submit();
}

</script>