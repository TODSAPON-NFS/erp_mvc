<script>
    function check(){


        var stock_type_code = document.getElementById("stock_type_code").value;
        var stock_type_code = document.getElementById("stock_type_code").value;
       
        
        stock_type_code = $.trim(stock_type_code);
        stock_type_code = $.trim(stock_type_code);
        
        

        if(stock_type_code.length == 0){
            alert("Please input stock type code");
            document.getElementById("stock_type_code").focus();
            return false;
        }else if(stock_type_code.length == 0){
            alert("Please input stock type name");
            document.getElementById("stock_type_code").focus();
            return false;
        }else{
            return true;
        }



    }


</script>

<div class="row">
    <div class="col-lg-6">
        <h1 class="page-header">ประเภทคลังสินค้า / Stock</h1>
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
               เพิ่มประเภทคลังสินค้า / Add Stock 
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <form role="form" method="post" onsubmit="return check();" action="index.php?app=stock_type&action=add" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>หมายเลขประเภทคลังสินค้า / Stock type code. <font color="#F00"><b>*</b></font></label>
                                <input id="stock_type_code" name="stock_type_code" class="form-control">
                                <p class="help-block">Example : 01.</p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>ชื่อประเภทคลังสินค้า / Stock type name </label>
                                <input id="stock_type_name" name="stock_type_name" type="text" class="form-control" />
                                <p class="help-block">Example : Main Stock.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-offset-9 col-lg-3" align="right">
                            <button type="reset" class="btn btn-primary">Reset</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>