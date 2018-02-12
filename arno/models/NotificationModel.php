<?php

require_once("BaseModel.php");
class NotificationModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getNotificationBy($user_id,$str =''){
        if($str != ""){
            $str = "AND notification_seen_date ='' ";
        }
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE user_id = '$user_id' 
        $str
        ORDER BY STR_TO_DATE(notification_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";
        
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getNotificationByType($user_id,$type,$str =''){
        if($str != ""){
            $str = "AND notification_seen_date ='' ";
        }
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE user_id = '$user_id' 
        AND notification_type = '$type' 
        $str
        ORDER BY STR_TO_DATE(notification_date,'%Y-%m-%d %H:%i:%s') DESC 
         ";
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function getNotificationByID($id){
        $sql = " SELECT * 
        FROM tb_notification 
        WHERE notification_id = '$id' 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data;
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data = $row;
            }
            $result->close();
            return $data;
        }
    }


    function setNotification($type="",$detail="",$url="",$page="",$status=""){
        $sql = "
            SELECT user_id FROM tb_user LEFT JOIN tb_license ON tb_user.license_id = tb_license.license_id 
            WHERE tb_license.$page IN ($status) 
        ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $str=array();
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $str[] ="INSERT INTO tb_notification 
                ( user_id, notification_type, notification_seen, notification_date,notification_seen_date,notification_detail,notification_url) 
                VALUES ('".$row['user_id']."','$type','0',NOW(),'','$detail','$url')";

                

            }
            $result->close();

            for($i=0; $i < count($str); $i++){
                mysqli_query($this->db,$str[$i], MYSQLI_USE_RESULT);
            }
        }

    }

    function setNotificationSeenByID($id){
        $sql = " UPDATE tb_notification SET 
        notification_seen_date = NOW() , 
        notification_seen = '1'  
        WHERE notification_id = $id 
        ";


        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }

    function setNotificationSeenByURL($url){
        $sql = " UPDATE tb_notification SET 
        notification_seen_date = NOW() , 
        notification_seen = '1'  
        WHERE notification_url LIKE ('%$url%') 
        ";

        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }


    }


    function deleteNotificationByID($id){
        $sql = " DELETE FROM tb_notification WHERE notification_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }
}
?>