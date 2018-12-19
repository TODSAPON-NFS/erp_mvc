<link rel="stylesheet" href="../template/calendar/css/styles.css">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Customer Management - <?php echo $customer['customer_name_th']; ?> Calendar</h1>
        </div>
    <!-- /.col-lg-12 -->
    </div>
    <div id="draggable" class="fixed">
        <div id="fixed_body" class="fixed-body">
            <div class="row">
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(0);" class="link"><span class="date-select color-today"></span> วันนี้ </a>
                </div>
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(1);" class="link"><span class="date-select color-event"></span> วันหยุด </a>
                </div>
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(2);" class="link"><span class="date-select color-bill"></span> วันวางบิล </a>
                </div>
                <div class="col-md-12">
                    <a href="javascript:;" onclick="select_date_type(3);" class="link"><span class="date-select color-invoice"></span> วันรับ Invoice ช้าสุด </a>
                </div>
            </div>
            <div class="row" align="right">
                <div class="col-md-12">
                    <button class="btn btn-primary " style="margin-right:4px;" onclick="location.reload();">Refresh</button>
                    <button class="btn btn-success " style="margin-right:4px;" onclick="save_calendar('<?php echo $customer_id; ?>')">Save</button>
                </div>
            </div>
        </div>
        <div id="fixed_header" class="fixed-header" onclick="toggle_select();">
            <i class="fa fa-cog" aria-hidden="true"></i>
        </div>
    </div>

    <div id="calendar_div" class="row mouse-today"></div>
    <script src="../template/calendar/function.js"></script>
       
<script type="text/javascript">

var d = new Date();

var date_event = [{
    date:{
        day:d.getDate(),
        month:d.getMonth()+1,
        year:d.getFullYear()
    }, 
    type:0,
    class:'badge-today color-today', 
    detail:'ปัจจุบัน'
},{
    date:{
        day:25,
        month:1,
        year:2018
    }, 
    type:1,
    class:'badge-event color-event', 
    detail:'วันหยุด'
},{
    date:{
        day:24,
        month:1,
        year:2018
    }, 
    type:3,
    class:'badge-invoice color-invoice', 
    detail:'รับ Invoice วันสุดท้าย'
},{
    date:{
        day:1,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
},{
    date:{
        day:2,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
},{
    date:{
        day:3,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
},{
    date:{
        day:4,
        month:2,
        year:2018
    }, 
    type:2,
    class:'badge-bill color-bill', 
    detail:'วันวางบิล'
}];



    //load initCalendar 
    window.onload =function() {
        initCalendar();
    };
    
</script>
