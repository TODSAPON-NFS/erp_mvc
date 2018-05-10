<!-- jQuery -->
    <script src="../template/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../template/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../template/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../template/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../template/vendor/datatables-responsive/dataTables.responsive.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js" ></script>
    
     

    <!-- Custom Theme JavaScript -->
    <script src="../template/dist/js/sb-admin-2.js"></script>

    <!-- Custom Dropdown Theme JavaScript -->
    <script src="../template/dist/js/bootstrap-select.min.js"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->


    <script src="../template/dist/js/jquery-ui.js"></script>
    <script src="../lib/functions.js"></script>

    <!-- Morris Charts JavaScript -->
    <?php if($_GET['app'] =="" ){?>
    <script src="../template/vendor/raphael/raphael.min.js"></script>
    <script src="../template/vendor/morrisjs/morris.min.js"></script>
    <script src="../template/data/morris-data.js"></script>
    <?php }?>
    <script>

    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
        $('#tb-product-customer').DataTable({
            responsive: true
        });
        $('.select').selectpicker();
        <?PHP if($_GET['app'] == "employee" && ($_GET['action'] == 'update' || $_GET['action'] == 'insert') ){ ?>

var c = document.getElementById("signature");
    var ctx = c.getContext("2d");
    var img = new Image();
    img.onload = function() {
        ctx.drawImage(img, 0, 0);
    };
    img.src = '<?PHP echo $target_dir . $user['user_id'] . ".png";?>';
    
        var canvas = document.getElementById("signature");
        var signaturePad = new SignaturePad(canvas);
        
        $('#clear-signature').on('click', function(){
            signaturePad.clear();
        });

        <?PHP } ?>
        

    });



    $( function() {
        $( "#customer_holiday_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#stock_date" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#date_start" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( "#date_end" ).datepicker({ dateFormat: 'dd-mm-yy' });
        $( ".calendar" ).datepicker({ dateFormat: 'dd-mm-yy' });
    } );



    $( function() {
        $( "#draggable" ).draggable();
    } );

    </script>