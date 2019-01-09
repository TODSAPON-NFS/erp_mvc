<?PHP  
    session_start();
    $user_admin = $_SESSION['user'];
    $admin_id = $user_admin[0][0]; 


    if($_GET['app'] == "delivery_note_customer"){
        require_once("print/delivery_note_customer/views/index.inc.php");
    }elseif($_GET['app'] == "purchase_order"){
        require_once("print/purchase_order/views/index.inc.php");
    }elseif($_GET['app'] == "invoice_customer"){
        require_once("print/invoice_customer/views/index.inc.php");
    }else if($_GET['app'] == "billing_note"){
        require_once("print/billing_note/views/index.inc.php");
    }else if($_GET['app'] == "official_receipt"){
        require_once("print/official_receipt/views/index.inc.php");
    }else if($_GET['app'] == "finance_debit"){
        require_once("print/finance_debit/views/index.inc.php");
    }else if($_GET['app'] == "finance_credit"){
        require_once("print/finance_credit/views/index.inc.php");
    }else if($_GET['app'] == "report_journal_general"){
        require_once("print/report_journal_general/views/index.inc.php");
    }else if($_GET['app'] == "report_journal_01"){
        require_once("print/report_journal_01/views/index.inc.php");
    }else if($_GET['app'] == "report_journal_02"){
        require_once("print/report_journal_02/views/index.inc.php");
    }else if($_GET['app'] == "report_journal_03"){
        require_once("print/report_journal_03/views/index.inc.php");
    }else if($_GET['app'] == "report_journal_04"){
        require_once("print/report_journal_04/views/index.inc.php");
    }elseif($_GET['app'] == "invoice_supplier"){
        require_once("print/invoice_supplier/views/index.inc.php");
    }elseif($_GET['app'] == "invoice_supplier_abroad"){
        require_once("print/invoice_supplier_abroad/views/index.inc.php");
    }

/* ################################################### รายงานลูกหนี้ ################################################### */
    else if($_GET['app'] == "report_debtor_01"){
        require_once("print/report_debtor_01/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_02"){
        require_once("print/report_debtor_02/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_03"){
        require_once("print/report_debtor_03/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_04"){
        require_once("print/report_debtor_04/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_05"){
        require_once("print/report_debtor_05/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_06"){
        require_once("print/report_debtor_06/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_07"){
        require_once("print/report_debtor_07/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_08"){
        require_once("print/report_debtor_08/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_09"){
        require_once("print/report_debtor_09/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_10"){
        require_once("print/report_debtor_10/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_11"){
        require_once("print/report_debtor_11/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_12"){
        require_once("print/report_debtor_12/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_13"){
        require_once("print/report_debtor_13/views/index.inc.php");
    } else if($_GET['app'] == "report_debtor_14"){
        require_once("print/report_debtor_14/views/index.inc.php");
    } 

/* ################################################### สิ้นสุด รายงานลูกหนี้ ################################################### */




/* ################################################### รายงานลูกหนี้ ################################################### */

else if($_GET['app'] == "report_creditor_01"){
    require_once("print/report_creditor_01/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_02"){
    require_once("print/report_creditor_02/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_03"){
    require_once("print/report_creditor_03/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_04"){
    require_once("print/report_creditor_04/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_05"){
    require_once("print/report_creditor_05/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_06"){
    require_once("print/report_creditor_06/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_07"){
    require_once("print/report_creditor_07/views/index.inc.php");
}else if($_GET['app'] == "report_creditor_08"){
    require_once("print/report_creditor_08/views/index.inc.php");
}
/* ################################################### สิ้นสุด รายงานเจ้าหนี้ ################################################### */




/* ################################################### รายงานบัญชี ################################################### */

    else if($_GET['app'] == "report_account_01"){
        require_once("print/report_account_01/views/index.inc.php");
    } else if($_GET['app'] == "report_account_02"){
        require_once("print/report_account_02/views/index.inc.php");
    } else if($_GET['app'] == "report_account_03"){
        require_once("print/report_account_03/views/index.inc.php");
    } else if($_GET['app'] == "report_account_04"){
        require_once("print/report_account_04/views/index.inc.php");
    } else if($_GET['app'] == "report_account_05"){
        require_once("print/report_account_05/views/index.inc.php");
    } else if($_GET['app'] == "report_account_06"){
        require_once("print/report_account_06/views/index.inc.php");
    }else if($_GET['app'] == "report_account_07"){
        require_once("print/report_account_07/views/index.inc.php");
    }else if($_GET['app'] == "report_account_08"){
        require_once("print/report_account_08/views/index.inc.php");
    }else if($_GET['app'] == "report_account_09"){
        require_once("print/report_account_09/views/index.inc.php");
    }else if($_GET['app'] == "report_account_10"){
        require_once("print/report_account_10/views/index.inc.php");
    }


/* ################################################### สิ้นสุด รายงานบัญชี ################################################### */






/* ################################################### รายงานลูกหนี้ ################################################### */
else if($_GET['app'] == "report_creditor_01"){
    require_once("print/report_creditor_01/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_02"){
    require_once("print/report_creditor_02/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_03"){
    require_once("print/report_creditor_03/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_04"){
    require_once("print/report_creditor_04/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_05"){
    require_once("print/report_creditor_05/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_06"){
    require_once("print/report_creditor_06/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_07"){
    require_once("print/report_creditor_07/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_08"){
    require_once("print/report_creditor_08/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_09"){
    require_once("print/report_creditor_09/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_10"){
    require_once("print/report_creditor_10/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_11"){
    require_once("print/report_creditor_11/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_12"){
    require_once("print/report_creditor_12/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_13"){
    require_once("print/report_creditor_13/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_14"){
    require_once("print/report_creditor_14/views/index.inc.php");
} else if($_GET['app'] == "report_creditor_15"){
    require_once("print/report_creditor_15/views/index.inc.php");
}   

/* ################################################### สิ้นสุด รายงานลูกหนี้ ################################################### */



/* ################################################### รายงานภาษี ################################################### */
else if($_GET['app'] == "report_tax_01"){
    require_once("print/report_tax_01/views/index.inc.php");
} else if($_GET['app'] == "report_tax_02"){
    require_once("print/report_tax_02/views/index.inc.php");
} else if($_GET['app'] == "report_tax_03"){
    require_once("print/report_tax_03/views/index.inc.php");
} 

/* ################################################### สิ้นสุด รายงานภาษี ################################################### */


/* ################################################### สินค้าคงคลัง ################################################### */
else if($_GET['app'] == "report_stock_01"){
    require_once("print/report_stock_01/views/index.inc.php");
} else if($_GET['app'] == "report_stock_02"){
    require_once("print/report_stock_02/views/index.inc.php");
} else if($_GET['app'] == "report_stock_03"){
    require_once("print/report_stock_03/views/index.inc.php");
} else if($_GET['app'] == "report_stock_04"){
    require_once("print/report_stock_04/views/index.inc.php");
} else if($_GET['app'] == "report_stock_05"){
    require_once("print/report_stock_05/views/index.inc.php");
} else if($_GET['app'] == "report_stock_06"){
    require_once("print/report_stock_06/views/index.inc.php");
} else if($_GET['app'] == "report_stock_07"){
    require_once("print/report_stock_07/views/index.inc.php");
} else if($_GET['app'] == "report_stock_08"){
    require_once("print/report_stock_08/views/index.inc.php");
} else if($_GET['app'] == "report_stock_09"){
    require_once("print/report_stock_09/views/index.inc.php");
}  

/* ################################################### สิ้นสุด สินค้าคงคลัง ################################################### */

?>