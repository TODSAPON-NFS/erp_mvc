<?php
    if(!isset($_GET['app'])){
        require_once("modules/dashboard/views/index.inc.php");
    }else if($_GET['app'] == "notification"){
        require_once("modules/notification/views/index.inc.php");
    }
    
    
    else if($_GET['app'] == "employee"){
        require_once("modules/user/views/index.inc.php");
    }else if($_GET['app'] == "employee_position"){
        require_once("modules/user_position/views/index.inc.php");
    }else if($_GET['app'] == "employee_license"){
        require_once("modules/user_license/views/index.inc.php");
    }
    
    
    else if($_GET['app'] == "product"){
        require_once("modules/product/views/index.inc.php");
    }else if($_GET['app'] == "product_type"){
        require_once("modules/product_type/views/index.inc.php");
    }else if($_GET['app'] == "product_group"){
        require_once("modules/product_group/views/index.inc.php");
    }else if($_GET['app'] == "product_unit"){
        require_once("modules/product_unit/views/index.inc.php");
    }
    
    
    else if($_GET['app'] == "customer"){
        require_once("modules/customer/views/index.inc.php");
    }else if($_GET['app'] == "customer_account"){
        require_once("modules/customer_account/views/index.inc.php");
    }else if($_GET['app'] == "customer_contact"){
        require_once("modules/customer_contact/views/index.inc.php");
    }else if($_GET['app'] == "customer_logistic"){
        require_once("modules/customer_logistic/views/index.inc.php");
    }else if($_GET['app'] == "customer_holiday"){
        require_once("modules/customer_holiday/views/index.inc.php");
    }
    
    
    else if($_GET['app'] == "supplier"){
        require_once("modules/supplier/views/index.inc.php");
    }else if($_GET['app'] == "supplier_account"){
        require_once("modules/supplier_account/views/index.inc.php");
    }else if($_GET['app'] == "supplier_contact"){
        require_once("modules/supplier_contact/views/index.inc.php");
    }else if($_GET['app'] == "supplier_logistic"){
        require_once("modules/supplier_logistic/views/index.inc.php");
    }

    
    else if($_GET['app'] == "stock_move"){
        require_once("modules/stock_move/views/index.inc.php");
    }else if($_GET['app'] == "stock_group"){
        require_once("modules/stock_group/views/index.inc.php");
    }else if($_GET['app'] == "stock"){
        require_once("modules/stock/views/index.inc.php");
    }else if($_GET['app'] == "stock_list"){
        require_once("modules/stock_list/views/index.inc.php");
    }else if($_GET['app'] == "stock_in"){
        require_once("modules/stock_in/views/index.inc.php");
    }else if($_GET['app'] == "stock_out"){
        require_once("modules/stock_out/views/index.inc.php");
    }


    else if($_GET['app'] == "purchase_request"){
        require_once("modules/purchase_request/views/index.inc.php");
    }else if($_GET['app'] == "purchase_order"){
        require_once("modules/purchase_order/views/index.inc.php");
    }else if($_GET['app'] == "customer_purchase_order"){
        require_once("modules/customer_purchase_order/views/index.inc.php");
    }else if($_GET['app'] == "invoice_supplier"){
        require_once("modules/invoice_supplier/views/index.inc.php");
    }else if($_GET['app'] == "invoice_customer"){
        require_once("modules/invoice_customer/views/index.inc.php");
    }else if($_GET['app'] == "exchange_rate_baht"){
        require_once("modules/exchange_rate_baht/views/index.inc.php");
    }else if($_GET['app'] == "credit_note"){
        require_once("modules/credit_note/views/index.inc.php");
    }else if($_GET['app'] == "debit_note"){
        require_once("modules/debit_note/views/index.inc.php");
    }else if($_GET['app'] == "billing_note"){
        require_once("modules/billing_note/views/index.inc.php");
    }
    
    else if($_GET['app'] == "delivery_note_customer"){
        require_once("modules/delivery_note_customer/views/index.inc.php");
    }else if($_GET['app'] == "delivery_note_supplier"){
        require_once("modules/delivery_note_supplier/views/index.inc.php");
    }
?>