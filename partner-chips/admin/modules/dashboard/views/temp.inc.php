
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading panel-heading-dash">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo count($notifications_pr);?></div>
                        <div>New Purchase Request!</div>
                    </div>
                </div>
            </div>
            <a href="?app=notification&type=Purchase Request">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading panel-heading-dash">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo count($notifications_po);?></div>
                        <div>New Purchase Order!</div>
                    </div>
                </div>
            </div>
            <a href="?app=notification&type=Purchase Order">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading panel-heading-dash">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo count($notifications_cpo);?></div>
                        <div>New Customer Order!</div>
                    </div>
                </div>
            </div>
            <a href="?app=notification&type=Customer Order">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading panel-heading-dash">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-support fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?php echo count($notifications_ns);?></div>
                        <div>New Supplier!</div>
                    </div>
                </div>
            </div>
            <a href="?app=notification&type=Supplier Approve">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-red"> <!-- panel แสดงยอดขายตามปี -->
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> แสดงยอดขายตามปี
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            Actions
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="#">Action</a>
                            </li>
                            <li><a href="#">Another action</a>
                            </li>
                            <li><a href="#">Something else here</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <!-- <div id="morris-area-chart"></div> -->
                <canvas id="myChart" width="400" height="400"></canvas>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->       
        
    </div>
    <!-- /.col-lg-8 -->
    <div class="col-lg-4">
        <div class="panel panel-primary"> <!-- Notifications Panel -->
            <div class="panel-heading " style="min-height:0px;">
                <i class="fa fa-bell fa-fw"></i> Notifications Panel
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body-notify-dash scroll1">
                <div class="list-group">
                <?php for($i=0 ; $i < count($notifications) ;$i++){ ?>
                <a href="<?php echo $notifications[$i]['notification_url'];?>&notification=<?php echo $notifications[$i]['notification_id'];?>" class="list-group-item <?php if($notifications[$i]['notification_seen_date'] != ""){ ?>notify<? }else{ ?> notify-active <?php } ?>">

                        <?php if($notifications[$i]['notification_type'] =='Purchase Request'){ ?><i class="fa fa-comments fa-fw fa-notify"></i> <?php }
                            else if ($notifications[$i]['notification_type'] =='Purchase Order'){?><i class="fa fa-tasks fa-fw fa-notify"></i> <?php }
                            else if ($notifications[$i]['notification_type'] =='Customer Order'){?><i class="fa fa-cart-plus fa-fw fa-notify"></i> <?php }
                            else {?><i class="fa fa-support fa-fw fa-notify"></i> <?php }
                            ?>
                        <?php echo $notifications[$i]['notification_detail'];?> 
                        <div class=" text-muted small"><em><?php echo $notifications[$i]['notification_date'];?></em>
                        </div>
                    </a>
                <?
                    //if($i >= 10){break;}    
                }
                
                ?>
                </div>
                <!-- /.list-group -->
                <div class="sticky-bot">
                    <a class="see_all" href="index.php?app=notification">
                        <strong>See All Alerts</strong>
                        <i class="fa fa-angle-right"></i>
                        <i class="fa fa-angle-right"></i>
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
    </div>
    <!-- /.col-lg-4 -->
</div>
<!-- /.row -->
<script>
    
    $(function () {
        var dataLineChart;
        // UpdateLineChart();
        $('#myTable').dataTable({
            "bInfo" : false,
            "pagingType": "simple",
            "lengthChange": false
        });

        var table = $('#myTable').DataTable();
    
        
        
        var Color = ["#004D40", "#00695C","#00796B","#00897B","#009688","#26A69A", "#4DB6AC","#80CBC4","#B2DFDB","#81C784"];
        var bar = document.getElementById("BarChart");
        var barChart = new Chart(bar, {
                type: 'horizontalBar',
                data: {
                labels: [],
                datasets: [
                    {
                    data: [],
                    label: "ยอดขาย :",
                    backgroundColor: Color ,
                    
                    }
                ]
                },
                options: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'ยอดขายตามลูกค้า 2018'
                }
                }
        }); 

        $('#myTable').on( 'page.dt', function () {
            var info = table.page.info();
            // $('#pageInfo').html( 'Showing page: '+info.page+' of '+info.pages );
            console.log("Showing page: "+info.page);
            UpdateBarChart(info.page,barChart);
            
        } );
        // UpdateBarChart(0,barChart);
    
    }); 
    $(document).ready( function () {
            UpdateLineChart();            
            // UpdateDonutChart();

    });   
    function renderLineChart(data, labels) {
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'ยอดขาย',
                        data: data,
                        borderColor: 'rgba(169, 50, 38,0.0)',
                        borderWidth:0,
                        fill: true,
                    backgroundColor: 'rgba(169, 50, 38, 0.5)',
                    }]
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            stepSize: 2000000
                        }
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                }
            });

    }
    function UpdateLineChart(){ //*****function Update Event Line chart And Update data****** */
            $.post( "controllers/getNetPriceGroupBySales.php",{ 'user_id': <?php echo $user['user_id'] ;?> }, function( result ) {    
                if(result != null){   
                    var data = [];
                    var labels = [];
                    // data.push(result.net_price);
                    for(var i=0;i<result.length;i++){
                        data[i]=result[i].net_price;
                        labels[i]=result[i].invoice_date;
                    }
                    renderLineChart(data, labels); //****Update Line Chart Data****** */
                }
            });        
        }

    function UpdateBarChart(limit,barChart){ //*****function Next Event Bar chart And Update data****** */
        $.post( "controllers/getNetPriceGroupByCustomer.php",{ 'limit': limit }, function( data ) {    
            if(data != null){ 
                var labels=[];
                var net_price = [];  
                barChart.data.labels.pop();
                barChart.data.datasets.forEach((dataset) => {
                    dataset.data.pop();
                });
                for(var i=0;i<data.length;i++){
                        net_price[i]=data[i].net_price;
                        labels[i]=data[i].code;
                        
                        // barChart.data.labels.push(data[i].code);
                        // barChart.data.datasets[0].data.push(data[i].net_price);
                    }
                    barChart.data.labels =labels;
                    barChart.data.datasets[0].data = net_price;
                    barChart.update();
            }
        });        
    }
    function UpdateDonutChart(){ //*****function Update Event Donut chart And Update data****** */
            $.post( "controllers/getNetPriceGroupBySales.php", function( data ) {    
                if(data != null){  
                    var labels=[];
                    var net_price = [];  
                    for(var i=0;i<data.length;i++){
                        net_price[i]=data[i].net_price;
                        labels[i]=data[i].sales_name;
                    } 
                    renderDonutChart(net_price,labels); //****Update Donut Chart Data****** */
                }
            });        
        }
    
    


</script>