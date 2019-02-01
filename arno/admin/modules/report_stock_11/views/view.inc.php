<script>
    function search(){  

        var date_end = $("#date_end").val(); 

        window.location = "index.php?app=report_stock_11&date_end="+date_end;
    }
    function print(type){  
        var date_end = $("#date_end").val(); 
        
        window.open("print.php?app=report_stock_11&action="+type+"&date_end="+date_end,'_blank');
    }
</script>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">มูลค่าตามคลังสินค้า</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>


<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-8">
                    มูลค่าตามคลังสินค้า 
                    </div>
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row"> 
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ณ วันที่</label> 
                            <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/> 
                            <p class="help-block">01-01-2018</p>
                        </div>
                    </div>              
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-danger" style="float:right; margin:0px 4px;" onclick="print('pdf','');">PDF</button>
                        <button class="btn btn-success" style="float:right; margin:0px 4px;" onclick="print('excel','');">Excel</button>
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=report_stock_02" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                        <tr> 
                            <th width="64px">ลำดับ</th>
                            <th >รหัสคลัง</th>
                            <th>ชื่อคลังค้า</th> 
                            <th>มูลค่า</th> 
                            <th> % </th>
                   
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        $sumQty = 0 ; 
                        $sumCost_avg_total = 0 ; 
                        $sumall = 0 ;
                        for($i=0; $i < count($stock_reports); $i++){ 
                            $sumall+=$stock_reports[$i]['stock_report_avg_total'] ;
                        }

                        for($i=0; $i < count($stock_reports); $i++){ 

                            $sumQty += $stock_reports[$i]['stock_report_qty'];
                            $sumCost_avg_total += $stock_reports[$i]['stock_report_avg_total'] ;
                        ?>
                        <tr class="">
                            <td><?php echo number_format(($i + 1),0); ?></td>
                            <td><?php echo $stock_reports[$i]['stock_group_code']; ?></td>
                            <td><?php echo $stock_reports[$i]['stock_group_name']; ?></td>
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_avg_total'],2); ?> </td> 
                            <td align="right"><?php echo number_format($stock_reports[$i]['stock_report_avg_total']/$sumall *100,2); ?> %</td> 
                         
                        </tr>
                        <?PHP
                        }
                         
                        ?>
                    </tbody>
                    <tfoot>
                   
                        <tr>
                            
                            <td align="center" colspan="3">รวมทั้งหมด</td>
                            <td align="right" ><?php echo number_format($sumCost_avg_total,2); ?></td>                        
                            <td align="right" ><?php echo number_format($sumall/$sumall*100,2); ?> %</td> 
                            
                        </tr>
                       
                    </tfoot>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
            
            
