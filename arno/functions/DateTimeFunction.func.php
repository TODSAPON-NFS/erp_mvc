<?PHP 
class DateTimeFunction{
    function changeDateFormat($date){
        $dt = explode(' ',$date);
        $dt = explode('-',$dt[0]);
        return $dt[2].'-'.$dt[1].'-'.$dt[0];
    }
}

?>