<script>
    function search(){
        var date_start = $("#date_start").val();
        var date_end = $("#date_end").val();
        var customer_id = $("#customer_id").val();
        var keyword = $("#keyword").val();

        window.location = "index.php?app=delivery_note_customer&date_start="+date_start+"&date_end="+date_end+"&customer_id="+customer_id+"&keyword="+keyword;
    }
</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">Delivery Note Customer Management</h1>
    </div>
    <div class="col-lg-6" align="right">
        
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
                        รายการใบยืมสินค้าสำหรับลูกค้า / Delivery Note Customer List
                    </div>

                    <?php if($license_delivery_note_page == "Low" || $license_delivery_note_page == "Medium" || $license_delivery_note_page == "High"){ ?> 
                    <div class="col-md-4">
                        <a class="btn btn-success " style="float:right;" href="?app=delivery_note_customer&action=insert" ><i class="fa fa-plus" aria-hidden="true"></i> Add</a>
                    </div>
                    <?PHP } ?>

                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>วันที่ออกใบยืม</label>
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" id="date_start" name="date_start" value="<?PHP echo $date_start;?>"  class="form-control calendar" readonly/>
                                </div>
                                <div class="col-md-1" align="center">
                                    -
                                </div>
                                <div class="col-md-5">
                                    <input type="text" id="date_end" name="date_end" value="<?PHP echo $date_end;?>"  class="form-control calendar" readonly/>
                                </div>
                            </div>
                            <p class="help-block">01-01-2018 - 31-12-2018</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ผู้ซื้อ </label>
                            <select id="customer_id" name="customer_id" class="form-control select"  data-live-search="true">
                                <option value="">ทั้งหมด</option>
                                <?php 
                                for($i =  0 ; $i < count($customers) ; $i++){
                                ?>
                                <option <?php if($customers[$i]['customer_id'] == $customer_id){?> selected <?php }?> value="<?php echo $customers[$i]['customer_id'] ?>"><?php echo $customers[$i]['customer_name_en'] ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <p class="help-block">Example : บริษัท ไทยซัมมิท โอโตโมทีฟ จำกัด.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>คำค้น <font color="#F00"><b>*</b></font></label>
                            <input id="keyword" name="keyword" class="form-control" value="<?PHP echo $keyword;?>" >
                            <p class="help-block">Example : T001.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary" style="float:right; margin:0px 4px;" onclick="search();">Search</button>
                        <a href="index.php?app=delivery_note_customer" class="btn btn-default" style="float:right; margin:0px 4px;">Reset</a>
                    </div>
                </div>
                <br>

                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลำดับ" width="10" >    No.</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเลขใบยืม" width="100" >    DNC No.</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="วันที่รับใบยืม" width="100" >    DNC Date</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ลูกค้า" width="200" >    Customer</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ติดต่อ" width="200" >    Contact</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="ผู้ส่ง" width="200" >    Send by</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="หมายเหตุ" width="100" >    Remark</th>
                            <th  style="text-align:center;" class="datatable-th"data-container="body" data-toggle="tooltip" data-placement="top" title="" data-original-title="" width="10" ></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        for($i=0; $i < count($delivery_note_customers); $i++){
                        ?>
                        <tr class="odd gradeX">
                            <td class="text-center"><?php echo $i+1; ?></td>
                            <td><?php echo $delivery_note_customers[$i]['delivery_note_customer_code']; ?>-<?PHP echo strtoupper(substr($delivery_note_customers[$i]['employee_name'],0,2));?></td>


                            
                                        
                            <td data-order="<?php echo  $timestamp = strtotime( $delivery_note_customers[$i]['delivery_note_customer_date'] )?>" >
                                        <?php echo ( $delivery_note_customers[$i]['delivery_note_customer_date'] ); ?>
                                    </td>
                                    


                            <td><?php echo $delivery_note_customers[$i]['customer_name']; ?></td>
                            <td><?php echo $delivery_note_customers[$i]['contact_name']; ?></td>
                            <td><?php echo $delivery_note_customers[$i]['employee_name']; ?></td>
                            <td><?php echo $delivery_note_customers[$i]['delivery_note_customer_remark']; ?></td>

                            <td>
                                <?
                                    if($delivery_note_customers[$i]['delivery_note_customer_file'] != ""){
                                ?>
                                    <a href="../upload/delivery_note_customer/<?php echo $delivery_note_customers[$i]['delivery_note_customer_file'];?>" target="_blank">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a> 
                                <?
                                    }
                                ?>

                                <a href="print.php?app=delivery_note_customer&action=pdf&id=<?PHP echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>" target="blank" >
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </a>
                                

                                <a href="?app=delivery_note_customer&action=detail&id=<?php echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>">
                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                </a>
                                
                                
                                <?php if(($license_delivery_note_page == "Low" && $admin_id == $delivery_note_customers[$i]['employee_id']) || $license_delivery_note_page == "Medium" || $license_delivery_note_page == "High"){ ?> 
                                <a href="?app=delivery_note_customer&action=update&id=<?php echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a> 
                                <?PHP } ?>

                                <?php if(($license_delivery_note_page == "Low" && $admin_id == $delivery_note_customers[$i]['employee_id']) || $license_delivery_note_page == "High"){ ?> 
                                <a href="?app=delivery_note_customer&action=delete&id=<?php echo $delivery_note_customers[$i]['delivery_note_customer_id'];?>" onclick="return confirm('You want to delete Delivery Note Customer : <?php echo $delivery_note_customers[$i]['delivery_note_customer_code']; ?>');" style="color:red;">
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                                <?PHP } ?>

                            </td>

                        </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>


