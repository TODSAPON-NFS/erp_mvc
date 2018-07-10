<?php
require_once('../models/HolidayModel.php');
require_once('../models/CustomerModel.php');
require_once('../models/CustomerHolidayModel.php');
$path = "modules/customer_holiday/views/";
$model_customer = new CustomerModel;
$model_holiday = new HolidayModel;
$model_customer_holiday = new CustomerHolidayModel;
$customer_id = $_GET['id'];
$customer_holiday_id = $_GET['sid'];

if(!isset($_GET['action'])){
    $customer = $model_customer->getCustomerByID($customer_id);
    
    $customer_holidays = $model_customer_holiday->getCustomerHolidayBy($customer_id);
    $holidays = $model_holiday->getHolidayBy($customer_id);
    if($customer_holiday_id != ''){
        $customer_holiday = $model_customer_holiday->getCustomerHolidayByID($customer_holiday_id);
    }
    require_once($path.'view.inc.php');

}else if ($_GET['action'] == 'delete'  && ( $license_admin_page == 'High') ){

    $model_customer_holiday->deleteCustomerHolidayByID($customer_holiday_id);
?>
    <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
<?php

}else if ($_GET['action'] == 'add' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['holiday_id'])){
        $data = [];
        $data['holiday_id'] = $_POST['holiday_id'];
        $data['customer_id'] = $customer_id;
        $data['customer_holiday_name'] = $_POST['customer_holiday_name'];
        $data['customer_holiday_date'] = $_POST['customer_holiday_date'];  
       
            $id = $model_customer_holiday->insertCustomerHoliday($data);
            if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['holiday_id'])){
        $data = [];
        $data['holiday_id'] = $_POST['holiday_id'];
        $data['customer_id'] = $customer_id;
        $data['customer_holiday_name'] = $_POST['customer_holiday_name'];
        $data['customer_holiday_date'] = $_POST['customer_holiday_date'];  
        $id = $model_customer_holiday->updateCustomerHolidayByID($customer_holiday_id,$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit_bill' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['date_bill'])){
        $data = [];
        $data['date_bill'] = $_POST['date_bill'];
        $data['bill_shift'] = $_POST['bill_shift'];
            
        $id = $model_customer->updateCustomerBillByID($customer_id,$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else if ($_GET['action'] == 'edit_invoice' && ($license_admin_page == 'Medium' || $license_admin_page == 'High') ){
    if(isset($_POST['date_invoice'])){
        $data = [];
        $data['date_invoice'] = $_POST['date_invoice'];
        $data['invoice_shift'] = $_POST['invoice_shift'];

        $id = $model_customer->updateCustomerInvoiceByID($customer_id,$data);
        if($id > 0){
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }else{
    ?>
            <script>window.location="index.php?app=customer_holiday&action=view&id=<?php echo $customer_id;?>"</script>
    <?php
            }
                    
        }
    
}else{
    $customer = $model_customer->getCustomerByID($customer_id);
    
    $customer_holidays = $model_customer_holiday->getCustomerHolidayBy($customer_id);
    $holidays = $model_holiday->getHolidayBy($customer_id);
    if($customer_holiday_id != ''){
        $customer_holiday = $model_customer_holiday->getCustomerHolidayByID($customer_holiday_id);
    }

    $invoice = getDateOfMount($customer_holidays,$customer['date_invoice'],$customer['invoice_shift']);
    $bill = getDateOfMount($customer_holidays,$customer['date_bill'],$customer['bill_shift']);
    require_once($path.'view.inc.php');

}




function getDateOfMount($holiday, $last_date, $shift){
    date_default_timezone_set('Asia/Bangkok');
    $data = array();

    for($i=1; $i <= 12; $i++){
        $time = strtotime($i.'/'.$last_date.'/'.date('Y'));
        
        $check = true;
        while($check){
            $j =0;
            for(; $j < count($holiday); $j++){
                if($holiday[$j]['all_week'] == 1 && $holiday[$j]['holiday_id'] == date('w',$time)){
                    break;
                }else if ($holiday[$j]['all_week'] == 0 && $holiday[$j]['customer_holiday_date'] == date('d-m-Y',$time)){
                    break;
                }
            }

            if($j == count($holiday)){
                break;
            }else{
                if($shift == 0){
                    $time = strtotime("-1 day",$time );  
                }else{
                    $time = strtotime("+1 day",$time );
                }
                
            }
        }
        
        $data[]=array(
            'Mount' => date('M-Y',strtotime($i.'/'.'01'.'/'.date('Y'))),
            'Date' => date('d-m-Y',$time),
        );
    }

    return $data;
}
?>