<?php

require_once("BaseModel.php");
class StockMoveListModel extends BaseModel{

    function __construct(){
        if(!static::$db){
            static::$db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        }
    }

    function getStockMoveListBy($stock_move_id){
        $sql = " SELECT tb_stock_move_list.product_id, 
        CONCAT(product_code_first,product_code) as product_code, 
        product_name,   
        stock_move_list_id, 
        stock_move_list_qty,
        stock_move_list_remark 
        FROM tb_stock_move_list LEFT JOIN tb_product ON tb_stock_move_list.product_id = tb_product.product_id 
        WHERE stock_move_id = '$stock_move_id' 
        ORDER BY stock_move_list_id 
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


    function insertStockMoveList($data = []){
        $sql = " INSERT INTO tb_stock_move_list (
            stock_move_id,
            product_id,
            stock_move_list_qty, 
            stock_move_list_remark,
            addby,
            adddate,
            updateby,
            lastupdate
        ) VALUES (
            '".$data['stock_move_id']."', 
            '".$data['product_id']."', 
            '".$data['stock_move_list_qty']."', 
            '".$data['stock_move_list_remark']."',
            '".$data['addby']."', 
            NOW(), 
            '".$data['updateby']."', 
            NOW() 
        ); 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {

            $id = mysqli_insert_id(static::$db);

            $sql = "
                CALL insert_stock_move('".
                $data['stock_group_id_out']."','".
                $data['stock_group_id_in']."','".
                $id."','".
                $data['product_id']."','".
                $data['stock_move_list_qty']."','".
                $data['stock_date']."');
            ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

            return $id; 
        }else {
            return 0;
        }

    }

    

    function updateStockMoveListById($data,$id){

        $sql = " UPDATE tb_stock_move_list 
            SET product_id = '".$data['product_id']."', 
            stock_move_id = '".$data['stock_move_id']."',  
            stock_move_list_qty = '".$data['stock_move_list_qty']."',  
            stock_move_list_remark = '".$data['stock_move_list_remark']."' 
            WHERE stock_move_list_id = '$id' 
        ";

        //echo $sql . "<br><br>";
        if (mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            $sql = "
                CALL update_stock_move('".
                $data['stock_group_id_out']."','".
                $data['stock_group_id_in']."','".
                $id."','".
                $data['product_id']."','".
                $data['stock_move_list_qty']."','".
                $data['stock_date']."');
            ";

            //echo $sql . "<br><br>";

            mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

           return true;
        }else {
            return false;
        }
    }




    function deleteStockMoveListByID($id){
        $sql = "DELETE FROM tb_stock_move_list WHERE stock_move_list_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockMoveListByStockMoveID($id){


        $sql = "DELETE FROM tb_stock_move_list WHERE stock_move_id = '$id' ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

    }

    function deleteStockMoveListByStockMoveIDNotIN($id,$data){
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

        $sql = "    SELECT stock_move_list_id, stock_group_id_out,  stock_group_id_in
                    FROM  tb_stock_move 
                    LEFT JOIN tb_stock_move_list ON tb_stock_move.stock_move_id = tb_stock_move_list.stock_move_id
                    WHERE tb_stock_move_list.stock_move_id = '$id' 
                    AND stock_move_list_id NOT IN ($str) ";   

        $sql_delete=[];
        if ($result = mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT)) {
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $sql_delete [] = "
                    CALL delete_stock_move('".
                    $row['stock_group_id_out']."','".
                    $row['stock_group_id_in']."','".
                    $row['stock_move_list_id']."');
                ";
               
            }
            $result->close();
        }

        for($i = 0 ; $i < count($sql_delete); $i++){
            mysqli_query(static::$db,$sql_delete[$i], MYSQLI_USE_RESULT);
        }





        $sql = "DELETE FROM tb_stock_move_list WHERE stock_move_id = '$id' AND stock_move_list_id NOT IN ($str) ";
        mysqli_query(static::$db,$sql, MYSQLI_USE_RESULT);

        

    }
}
?>