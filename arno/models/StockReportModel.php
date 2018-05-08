<?php

require_once("BaseModel.php");
class StockReportModel extends BaseModel{

    function __construct(){
        $this->db = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
    }

    function getStockReportListBy(){
        $sql = "    SELECT * 
                    FROM tb_product 
                    LEFT JOIN tb_stock_report ON tb_product.product_id = tb_stock_report.product_id 
                    LEFT JOIN tb_stock_group ON tb_stock_report.stock_group_id = tb_stock_group.stock_group_id  
                    GROUP BY  tb_product.product_id, tb_stock_report.stock_group_id 
                    ORDER BY  tb_product.product_id, tb_stock_report.stock_group_id ";

        if ($result = mysqli_query($this->db,$sql, MYSQLI_USE_RESULT)) {
            $data = [];
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
                $data[] = $row;
            }
            $result->close();
            return $data;
        }
    }

}
?>