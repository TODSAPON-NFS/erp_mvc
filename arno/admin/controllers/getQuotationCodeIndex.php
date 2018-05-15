<?php 
require_once('../../models/QuotationModel.php');
$model_quotation = new QuotationModel;
$last_code = $model_quotation->getQuotationLastID($_POST["first_code"],3);
echo $last_code;
?>