<?php

require_once("BaseModel.php");
class StockIssueListModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockIssueListBy($stock_issue_id){
        $sql = " SELECT tb_stock_issue_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        stock_issue_list_id, 
        stock_issue_list_qty,
        stock_issue_list_price,
        stock_issue_list_total,
        stock_issue_list_remark 
        FROM tb_stock_issue_list LEFT JOIN tb_product ON tb_stock_issue_list.product_id = tb_product.product_id 
        WHERE stock_issue_id = '$stock_issue_id' 
        ORDER BY stock_issue_list_id 
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


    function insertStockIssueList($data = []){
        $sql = " INSERT INTO tb_stock_issue_list (
            stock_issue_id,
            product_id,
            stock_issue_list_qty, 
            stock_issue_list_price,
            stock_issue_list_total,
            stock_issue_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_issue_id']."', 
            '".$data['product_id']."', 
            '".$data['stock_issue_list_qty']."', 
            '".$data['stock_issue_list_price']."', 
            '".$data['stock_issue_list_total']."', 
            '".$data['stock_issue_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id($this->db);

            $sql = "
                CALL insert_stock_issue('".
                $data['stock_group_id']."','".
                $id."','".
                $data['product_id']."','".
                $data['stock_issue_list_qty']."','".
                $data['stock_date']."');
            ";

            //echo $sql . "<br><br>";

            mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockIssueListById($data,$id){

        $sql = " UPDATE tb_stock_issue_list 
            SET product_id = '".$data['product_id']."', 
            stock_issue_id = '".$data['stock_issue_id']."',  
            stock_issue_list_qty = '".$data['stock_issue_list_qty']."',  
            stock_issue_list_price = '".$data['stock_issue_list_price']."', 
            stock_issue_list_total = '".$data['stock_issue_list_total']."',
            stock_issue_list_remark = '".$data['stock_issue_list_remark']."' 
            WHERE stock_issue_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $sql = "
                CALL update_stock_issue('".
                $data['stock_group_id']."','".
                $id."','".
                $data['product_id']."','".
                $data['stock_issue_list_qty']."','".
                $data['stock_date']."');
            ";

            //echo $sql . "<br><br>";

            mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

           return true;
        }else {
            return false;
        }
    }




    function deleteStockIssueListByID($id){
        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_list_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockIssueListByStockIssueID($id){


        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_id = '$id' ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockIssueListByStockIssueIDNotIN($id,$data){
        $str ='';
        if(is_array($data)){ 
            for($i=0; $i < count($data) ;$i++){
                $str .= $data[$i];
                if($i + 1 < count($data)){
                    $str .= ',';
                }
            }
        }else if ($data != ''){
            $str = $data;
        }else{
            $str='0';
        }

        $sql = "    SELECT stock_issue_list_id, stock_group_id 
                    FROM  tb_stock_issue 
                    LEFT JOIN tb_stock_issue_list ON tb_stock_issue.stock_issue_id = tb_stock_issue_list.stock_issue_id
                    WHERE tb_stock_issue_list.stock_issue_id = '$id' 
                    AND stock_issue_list_id NOT IN ($str) ";   

        $sql_delete=[];
        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $sql_delete [] = "
                    CALL delete_stock_issue('".
                    $row['stock_group_id']."','".
                    $row['stock_issue_list_id']."');
                ";
               
            }
            $result->close();
        }

        for($i = 0 ; $i < count($sql_delete); $i++){
            mysqli_query($this->db,$sql_delete[$i], MYSQLI_USE_RESULT);
        }





        $sql = "DELETE FROM tb_stock_issue_list WHERE stock_issue_id = '$id' AND stock_issue_list_id NOT IN ($str) ";
        mysqli_query($this->db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>