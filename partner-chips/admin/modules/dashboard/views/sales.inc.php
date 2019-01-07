<<<<<<< HEAD

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
        <div class="panel panel-default">
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
        <div class="panel panel-primary">
            <div class="panel-heading">

                <i class="fa fa-bar-chart-o fa-fw"></i> Bar Chart แสดงยอดขายแต่ละปี
                <!-- <div id="pageInfo">
                    
                </div> -->
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
                <div class="row">
                    
                    <!-- /.col-lg-4 (nested) -->
                    <div class="col-lg-4">
                    <canvas id="BarChart" width="400" height="400"></canvas>
                    </div>
                    <!-- /.col-lg-8 (nested) -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
    </div>
    <!-- /.col-lg-8 -->


    <div class="col-lg-4">
        <div class="panel panel-primary">
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
        <!-- <div class="panel panel-green">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Pie Chart Example
            </div> -->
            <!-- <div class="panel-body"> -->
                <!-- <canvas id="PieChart"></canvas> -->
                <!-- <a href="#" class="btn btn-default btn-block">View Details</a> -->
            <!-- </div> -->
            <!-- /.panel-body -->
        <!-- </div> -->
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
                type: 'bar',
                data: {
                labels: [],
                datasets: [
                    {
                    data: [],
                    label: "ยอดขายทั้งปี",
                    backgroundColor: Color ,
                    
                    }
                ]
                },
                options: {
                    legend: { display: true },
                    title: {
                        display: true,
                        text: 'ยอดขายตามปี'
                    },
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            barPercentage: 0.5
                        }]
                    }
                }
                
        }); 

        $('#myTable').on( 'page.dt', function () {
            var info = table.page.info();
            // $('#pageInfo').html( 'Showing page: '+info.page+' of '+info.pages );
            // console.log("Showing page: "+info.page);
            UpdateBarChart(barChart);
            
        } );
        UpdateBarChart(barChart);
    
    }); 
    $(document).ready( function () {
            UpdateLineChart();            
            // UpdatePieChart();

    });   
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    function addData(chart, label, data) {
        chart.data.labels.push(label);
        chart.data.datasets.forEach((dataset) => {
            dataset.data.push(data);
        });
        chart.update();
    }
    function removeData(chart) {
        chart.data.labels.pop();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        chart.update();
    }
    function renderLineChart(data, labels) {
        var ctx = document.getElementById("myChart").getContext('2d');
        // console.log(labels);
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
                    backgroundColor: 'rgba(38, 166, 154, 0.5)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                callback: function (data) {
                                    return numeral(data).format('0,0.00')
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return "ยอดขาย: "+tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+" บาท";
                            }
                        }
                    }
                }
            });

    }    
    function renderPieChart(data, labels){
        var Pie = document.getElementById("PieChart");
        var color = [];
        for(var i=0;i<data.length;i++){
            color[i] = getRandomColor();
        }
        
        var PieChart =  new Chart(Pie, {
            type: 'pie',
            data: {
            labels: labels,
            datasets: [{
                // label: "Population (millions)",
                // backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                backgroundColor:color,
                data: data,
                borderWidth:0
            }]
            },
            options: {
                title: {
                    display: true,
                    text: 'ยอดขายตาม sales'
                },
                maintainAspectRatio: true,
            }
        });
    }
    function UpdateLineChart(){ //*****function Update Event Line chart And Update data****** */
            var id = <?php echo $user['user_id'];?>;
            $.post( "controllers/getNetPriceBySales.php",{ 'user_id': id }, function( result ) {    
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

    function UpdateBarChart(barChart){ //*****function Next Event Bar chart And Update data****** */
        var id = <?php echo $user['user_id'];?>;
        $.post( "controllers/getNetPriceGroupBySales.php",{ 'user_id': id }, function( data ) {    
            if(data != null){ 
                var labels=[];
                var net_price = [];  
                for(var i=0;i<data.length;i++){
                        net_price[i]=data[i].net_price;
                        labels[i]=data[i].invoice_date;
                    }
                    barChart.data.labels =labels;
                    barChart.data.datasets[0].data = net_price;
                    barChart.options.scales.yAxes[0].ticks.beginAtZero=true;
                    barChart.options.tooltips.callbacks.label = function(tooltipItem, net_price) {
                                return "ยอดขาย: "+tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+" บาท";
                            }
                    barChart.options.scales.yAxes[0].ticks.callback = function (net_price) {
                                    return numeral(net_price).format('0,0')
                                }
                    barChart.update();
            }
        });        
    }
    function UpdatePieChart(){ //*****function Update Event Pie chart And Update data****** */
            var id = <?php echo $user['user_id'];?>;
            $.post( "controllers/getNetPriceGroupBySales.php",{ 'user_id': id }, function( data ) {    
                if(data != null){  
                    var labels=[];
                    var net_price = [];  
                    for(var i=0;i<data.length;i++){
                        // net_price[i]=parseFloat(data[i].net_price).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        net_price[i]=data[i].net_price;
                        labels[i]=data[i].sales_name;
                    } 
                    renderPieChart(net_price,labels); //****Update Pie Chart Data****** */
                }
            });        
        }
    
    


=======

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
        <div class="panel panel-default">
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
        <div class="panel panel-primary">
            <div class="panel-heading">

                <i class="fa fa-bar-chart-o fa-fw"></i> Bar Chart แสดงยอดขายแต่ละปี
                <!-- <div id="pageInfo">
                    
                </div> -->
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
                <div class="row">
                    
                    <!-- /.col-lg-4 (nested) -->
                    <div class="col-lg-4">
                    <canvas id="BarChart" width="400" height="400"></canvas>
                    </div>
                    <!-- /.col-lg-8 (nested) -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        
    </div>
    <!-- /.col-lg-8 -->


    <div class="col-lg-4">
        <div class="panel panel-primary">
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
        <!-- <div class="panel panel-green">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i> Pie Chart Example
            </div> -->
            <!-- <div class="panel-body"> -->
                <!-- <canvas id="PieChart"></canvas> -->
                <!-- <a href="#" class="btn btn-default btn-block">View Details</a> -->
            <!-- </div> -->
            <!-- /.panel-body -->
        <!-- </div> -->
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
                type: 'bar',
                data: {
                labels: [],
                datasets: [
                    {
                    data: [],
                    label: "ยอดขายทั้งปี",
                    backgroundColor: Color ,
                    
                    }
                ]
                },
                options: {
                    legend: { display: true },
                    title: {
                        display: true,
                        text: 'ยอดขายตามปี'
                    },
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            barPercentage: 0.5
                        }]
                    }
                }
                
        }); 

        $('#myTable').on( 'page.dt', function () {
            var info = table.page.info();
            // $('#pageInfo').html( 'Showing page: '+info.page+' of '+info.pages );
            // console.log("Showing page: "+info.page);
            UpdateBarChart(barChart);
            
        } );
        UpdateBarChart(barChart);
    
    }); 
    $(document).ready( function () {
            UpdateLineChart();            
            // UpdatePieChart();

    });   
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
    function addData(chart, label, data) {
        chart.data.labels.push(label);
        chart.data.datasets.forEach((dataset) => {
            dataset.data.push(data);
        });
        chart.update();
    }
    function removeData(chart) {
        chart.data.labels.pop();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.pop();
        });
        chart.update();
    }
    function renderLineChart(data, labels) {
        var ctx = document.getElementById("myChart").getContext('2d');
        // console.log(labels);
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
                    backgroundColor: 'rgba(38, 166, 154, 0.5)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                callback: function (data) {
                                    return numeral(data).format('0,0.00')
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return "ยอดขาย: "+tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+" บาท";
                            }
                        }
                    }
                }
            });

    }    
    function renderPieChart(data, labels){
        var Pie = document.getElementById("PieChart");
        var color = [];
        for(var i=0;i<data.length;i++){
            color[i] = getRandomColor();
        }
        
        var PieChart =  new Chart(Pie, {
            type: 'pie',
            data: {
            labels: labels,
            datasets: [{
                // label: "Population (millions)",
                // backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
                backgroundColor:color,
                data: data,
                borderWidth:0
            }]
            },
            options: {
                title: {
                    display: true,
                    text: 'ยอดขายตาม sales'
                },
                maintainAspectRatio: true,
            }
        });
    }
    function UpdateLineChart(){ //*****function Update Event Line chart And Update data****** */
            var id = <?php echo $user['user_id'];?>;
            $.post( "controllers/getNetPriceBySales.php",{ 'user_id': id }, function( result ) {    
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

    function UpdateBarChart(barChart){ //*****function Next Event Bar chart And Update data****** */
        var id = <?php echo $user['user_id'];?>;
        $.post( "controllers/getNetPriceGroupBySales.php",{ 'user_id': id }, function( data ) {    
            if(data != null){ 
                var labels=[];
                var net_price = [];  
                for(var i=0;i<data.length;i++){
                        net_price[i]=data[i].net_price;
                        labels[i]=data[i].invoice_date;
                    }
                    barChart.data.labels =labels;
                    barChart.data.datasets[0].data = net_price;
                    barChart.options.scales.yAxes[0].ticks.beginAtZero=true;
                    barChart.options.tooltips.callbacks.label = function(tooltipItem, net_price) {
                                return "ยอดขาย: "+tooltipItem.yLabel.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')+" บาท";
                            }
                    barChart.options.scales.yAxes[0].ticks.callback = function (net_price) {
                                    return numeral(net_price).format('0,0')
                                }
                    barChart.update();
            }
        });        
    }
    function UpdatePieChart(){ //*****function Update Event Pie chart And Update data****** */
            var id = <?php echo $user['user_id'];?>;
            $.post( "controllers/getNetPriceGroupBySales.php",{ 'user_id': id }, function( data ) {    
                if(data != null){  
                    var labels=[];
                    var net_price = [];  
                    for(var i=0;i<data.length;i++){
                        // net_price[i]=parseFloat(data[i].net_price).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        net_price[i]=data[i].net_price;
                        labels[i]=data[i].sales_name;
                    } 
                    renderPieChart(net_price,labels); //****Update Pie Chart Data****** */
                }
            });        
        }
    
    


>>>>>>> bfe174f8f8a6ccd61604b3210c62329d9f03ccee
</script>