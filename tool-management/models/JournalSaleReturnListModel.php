<?php

require_once("BaseModel.php");
class JournalSaleReturnListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getJournalSaleReturnListBy($journal_credit_note_id){
        $sql = " SELECT 
        journal_credit_note_list_id, 
        journal_credit_note_list_name,
        journal_credit_note_list_debit,
        journal_credit_note_list_credit, 
        tb_journal_credit_note_list.account_id, 
        account_name_th,  
        account_name_en 
        FROM tb_journal_credit_note_list LEFT JOIN tb_account ON tb_journal_credit_note_list.account_id = tb_account.account_id 
        WHERE journal_credit_note_id = '$journal_credit_note_id' 
        ORDER BY journal_credit_note_list_id 
        ";

        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }

    }


    function insertJournalSaleReturnList($data = []){
        $sql = " INSERT INTO tb_journal_credit_note_list (
            journal_credit_note_id,
            account_id,
            journal_credit_note_list_name,
            journal_credit_note_list_debit,
            journal_credit_note_list_credit,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['journal_credit_note_id']."', 
            '".$data['account_id']."', 
            '".$data['journal_credit_note_list_name']."', 
            '".$data['journal_credit_note_list_debit']."',
            '".$data['journal_credit_note_list_credit']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            return mysqli_insert_id(static::$db);
        }else {
            return 0;
        }

    }

    function updateJournalSaleReturnListById($data,$id){

        $sql = " UPDATE tb_journal_credit_note_list 
            SET account_id = '".$data['account_id']."', 
            journal_credit_note_list_name = '".$data['journal_credit_note_list_name']."',
            journal_credit_note_list_debit = '".$data['journal_credit_note_list_debit']."',
            journal_credit_note_list_credit = '".$data['journal_credit_note_list_credit']."' 
            WHERE journal_credit_note_list_id = '$id' 
        ";


        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
           return true;
        }else {
            return false;
        }
    }


    function deleteJournalSaleReturnListByID($id){
        $sql = "DELETE FROM tb_journal_credit_note_list WHERE journal_credit_note_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalSaleReturnListByJournalSaleReturnID($id){
        $sql = "DELETE FROM tb_journal_credit_note_list WHERE journal_credit_note_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteJournalSaleReturnListByJournalSaleReturnIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                if($data[$i] != ""){
                    $str .= $data[$i];
                    if($i + 1 < count($data)){
                        $str .= ',';
                    }
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        if( $str==''){
            $str='0';
        }

        $sql = "DELETE FROM tb_journal_credit_note_list WHERE journal_credit_note_id = '$id' AND journal_credit_note_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }
}
?>