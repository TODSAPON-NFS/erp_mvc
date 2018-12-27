-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 26, 2018 at 09:35 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `revelsof_erp-tm`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `cost_in` (IN `sg_id` INT, IN `p_id` INT, IN `in_qty` INT, IN `in_cost` DOUBLE)  NO SQL
BEGIN
	DECLARE stock_qty INT DEFAULT 0;
    DECLARE stock_cost DOUBLE DEFAULT 0.0;
    
    DECLARE new_qty INT DEFAULT 0;
    DECLARE new_cost DOUBLE DEFAULT 0.0;
    
    
    SELECT stock_report_qty , stock_report_cost_avg 
    INTO stock_qty, stock_cost 
    FROM tb_stock_report
    WHERE stock_group_id = sg_id 
    AND product_id = p_id ;
    
    SET new_qty = stock_qty + in_qty;
    SET new_cost = ((stock_qty * stock_cost) + (in_qty * in_cost))/new_qty;
    
    UPDATE tb_stock_report 
    SET stock_report_qty = new_qty , 
    stock_report_cost_avg = new_cost 
    WHERE stock_group_id = sg_id AND product_id = p_id;
    

END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `cost_out` (IN `sg_id` INT, IN `p_id` INT, IN `out_qty` INT, IN `out_cost` DOUBLE)  NO SQL
BEGIN
	DECLARE stock_qty INT DEFAULT 0;
    DECLARE stock_cost DOUBLE DEFAULT 0.0;
    
    DECLARE new_qty INT DEFAULT 0;
    DECLARE new_cost DOUBLE DEFAULT 0.0;
    
    
    SELECT stock_report_qty , stock_report_cost_avg 
    INTO stock_qty, stock_cost 
    FROM tb_stock_report
    WHERE stock_group_id = sg_id 
    AND product_id = p_id ;
    
    SET new_qty = stock_qty - out_qty;
    SET new_cost = ((stock_qty * stock_cost) - (out_qty * out_cost))/new_qty;
    
    UPDATE tb_stock_report 
    SET stock_report_qty = new_qty , 
    stock_report_cost_avg = new_cost 
    WHERE stock_group_id = sg_id AND product_id = p_id;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `cost_update` (IN `sg_id` INT, IN `p_id` INT, IN `old_qty` INT, IN `old_cost` DOUBLE, IN `new_qty` INT, IN `new_cost` DOUBLE)  NO SQL
BEGIN
	DECLARE stock_qty INT DEFAULT 0;
    DECLARE stock_cost DOUBLE DEFAULT 0.0;
    
    DECLARE new_stock_qty INT DEFAULT 0;
    DECLARE new_stock_cost DOUBLE DEFAULT 0.0;
    DECLARE new_stock_qty_out INT DEFAULT 0.0;
    
    SELECT stock_report_qty , stock_report_cost_avg 
    INTO stock_qty, stock_cost 
    FROM tb_stock_report
    WHERE stock_group_id = sg_id 
    AND product_id = p_id ;
    
    SET new_stock_qty_out = stock_qty - old_qty;
    SET new_stock_cost = ((stock_qty * stock_cost) - (old_qty * old_cost))/new_stock_qty_out;
    
    
    SET new_stock_qty = new_stock_qty_out + new_qty;
    SET new_stock_cost = ((new_stock_qty_out * new_stock_cost) + (new_qty * new_cost))/new_stock_qty;
    
    
    UPDATE tb_stock_report 
    SET stock_report_qty = new_stock_qty , 
    stock_report_cost_avg = new_stock_cost 
    WHERE stock_group_id = sg_id AND product_id = p_id;
    
    
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `createRowStockReport` (IN `sg_id` INT, IN `p_id` INT)  NO SQL
BEGIN

DECLARE check_row INT DEFAULT 0;

SELECT COUNT(*) INTO check_row 
FROM tb_stock_report 
WHERE tb_stock_report.stock_group_id = sg_id 
AND tb_stock_report.product_id = p_id ;

IF check_row = 0 THEN
	INSERT INTO tb_stock_report (stock_group_id,product_id) 
    VALUES (sg_id,p_id);  
END IF;

END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `delete_stock_credit` (IN `sg_id` INT, IN `icnl_id` INT, IN `p_id` INT, IN `q` INT, IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;
IF sg_id != '0' THEN 
    SELECT stock_group_id, table_name INTO stock_id, tb_name 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT stock_group_id, table_name INTO stock_id, tb_name 
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;

CALL createRowStockReport(stock_id,p_id);
CALL cost_out(stock_id,p_id,q,cost);


SET @sql = CONCAT('DELETE FROM ',
                  tb_name,' WHERE credit_note_list_id = "',icnl_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `delete_stock_customer` (IN `sg_id` INT, IN `icl_id` INT, IN `p_id` INT, IN `q` INT, IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT table_name, stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name, stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;

CALL createRowStockReport(stock_id,p_id);
CALL cost_in(stock_id,p_id,q,cost);


SET @sql = CONCAT('DELETE FROM ',
                  tb_name,' WHERE invoice_customer_list_id = "',icl_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `delete_stock_issue` (IN `sgi` INT, IN `sili` INT, IN `p_id` INT, IN `q` INT, IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;

IF sgi != '0' THEN 
    SELECT stock_group_id, table_name INTO stock_id ,tb_name 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sgi;
ELSE
	SELECT stock_group_id, table_name INTO stock_id ,tb_name 
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;


CALL createRowStockReport(stock_id,p_id);
CALL cost_in(stock_id,p_id,q,cost);


SET @sql = CONCAT('DELETE FROM ',
                  tb_name,' WHERE stock_issue_list_id = "',sili,'"');
                  
PREPARE s FROM @sql;
EXECUTE s;



END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `delete_stock_move` (IN `sgio` INT, IN `sgii` INT, IN `smli` INT, IN `p_id` INT, IN `q` INT, IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name_out VARCHAR(20);
DECLARE tb_name_in VARCHAR(20);
DECLARE col VARCHAR(40);

SELECT table_name INTO tb_name_out 
FROM tb_stock_group 
WHERE tb_stock_group.stock_group_id = sgio;


SELECT table_name INTO tb_name_in 
FROM tb_stock_group 
WHERE tb_stock_group.stock_group_id = sgii;


CALL createRowStockReport(stock_id,p_id);
CALL cost_in(sgio,p_id,q,cost);


SET @sql_out = CONCAT('DELETE FROM ',
                  tb_name_out,' WHERE stock_move_list_id = "',smli,'"');
                  
PREPARE s_out FROM @sql_out;
EXECUTE s_out;



CALL createRowStockReport(stock_id,p_id);
CALL cost_out(sgii,p_id,q,cost);


SET @sql_in = CONCAT('DELETE FROM ',
                  tb_name_in,' WHERE stock_move_list_id = "',smli,'"');
                  
PREPARE s_in FROM @sql_in;
EXECUTE s_in;


END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `delete_stock_summit` (IN `sg_id` INT, IN `sp_id` INT, IN `p_id` INT, IN `q` INT, IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT table_name, stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name, stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;

CALL createRowStockReport(stock_id,p_id);
CALL cost_out(stock_id,p_id,q,cost);



SET @sql = CONCAT('DELETE FROM ',
                  tb_name,' WHERE summit_product_id = "',sp_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `delete_stock_supplier` (IN `sg_id` INT, IN `icl_id` INT, IN `p_id` INT, IN `q` INT, IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT table_name, stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name, stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;

CALL createRowStockReport(stock_id,p_id);
CALL cost_out(stock_id,p_id,q,cost);



SET @sql = CONCAT('DELETE FROM ',
                  tb_name,' WHERE invoice_supplier_list_id = "',icl_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `insert_stock_credit` (IN `sg_id` INT, IN `icnl_id` INT, IN `p_id` INT, IN `q` INT, IN `sd` VARCHAR(50), IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT stock_group_id, table_name INTO stock_id ,tb_name 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT stock_group_id, table_name INTO stock_id ,tb_name 
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;


CALL createRowStockReport(stock_id,p_id);
CALL cost_in(stock_id,p_id,q,cost);



SET @sql = CONCAT('INSERT INTO ',
                  tb_name,' (stock_type,product_id,',
                  'qty,stock_date,credit_note_list_id)',
				  ' VALUE ("in",',p_id,',',
                  q,',"',sd,'",',icnl_id,')');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `insert_stock_customer` (IN `sg_id` INT, IN `icl_id` INT, IN `p_id` INT, IN `q` INT, IN `sd` VARCHAR(50), IN `cost` INT)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE col VARCHAR(40);
DECLARE stock_qty DOUBLE DEFAULT 0.0;
DECLARE stock_cost DOUBLE DEFAULT 0.0;
DECLARE new_qty DOUBLE DEFAULT 0.0;
DECLARE new_cost DOUBLE DEFAULT 0.0;
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT `table_name`,`stock_group_id` INTO tb_name ,stock_id 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id; 
ELSE
	SELECT `table_name`,`stock_group_id` INTO tb_name ,stock_id 
    FROM tb_stock_group 
    LIMIT 0,1; 
END IF;



CALL createRowStockReport(stock_id,p_id);
CALL cost_out(stock_id,p_id,q,cost);




SET @sql = CONCAT('INSERT INTO ',
                  tb_name,' (stock_type,product_id,',
                  'qty,stock_date,invoice_customer_list_id)',
				  ' VALUE ("out",',p_id,',',
                  q,',"',sd,'",',icl_id,')');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `insert_stock_issue` (IN `sgi` INT, IN `sili` INT, IN `p_id` INT, IN `q` INT, IN `sid` VARCHAR(50), IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0;

IF sgi != '0' THEN 
    SELECT stock_group_id, table_name INTO stock_id ,tb_name 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sgi;
ELSE
	SELECT stock_group_id, table_name INTO stock_id ,tb_name 
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;


CALL createRowStockReport(stock_id,p_id);
CALL cost_out(stock_id,p_id,q,cost);


SET @sql = CONCAT('INSERT INTO ',
                  tb_name,' (stock_type,product_id,',
                  'qty,stock_date,stock_issue_list_id)',
				  ' VALUE ("out",',p_id,',',
                  q,',"',sid,'",',sili,')');

PREPARE s FROM @sql;
EXECUTE s;


END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `insert_stock_move` (IN `sgio` INT, IN `sgii` INT, IN `smli` INT, IN `p_id` INT, IN `q` INT, IN `smd` VARCHAR(50), IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name_out VARCHAR(20);
DECLARE tb_name_in VARCHAR(20);


SELECT table_name INTO tb_name_out 
FROM tb_stock_group 
WHERE tb_stock_group.stock_group_id = sgio;

SELECT table_name INTO tb_name_in 
FROM tb_stock_group 
WHERE tb_stock_group.stock_group_id = sgii;



CALL createRowStockReport(stock_id,p_id);
CALL cost_out(sgio,p_id,q,cost);


SET @sql_out = CONCAT('INSERT INTO ',
                  tb_name_out,' (stock_type,product_id,',
                  'qty,stock_date,stock_move_list_id)',
				  ' VALUE ("out",',p_id,',',
                  q,',"',smd,'",',smli,')');

PREPARE s_out FROM @sql_out;
EXECUTE s_out;


CALL createRowStockReport(stock_id,p_id);
CALL cost_in(sgii,p_id,q,cost);


SET @sql_in = CONCAT('INSERT INTO ',
                  tb_name_in,' (stock_type,product_id,',
                  'qty,stock_date,stock_move_list_id)',
				  ' VALUE ("in",',p_id,',',
                  q,',"',smd,'",',smli,')');

PREPARE s_in FROM @sql_in;
EXECUTE s_in;

END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `insert_stock_summit` (IN `sg_id` INT, IN `sp_id` INT, IN `p_id` INT, IN `q` INT, IN `sd` VARCHAR(50), IN `cost` INT)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE col VARCHAR(40);
DECLARE stock_qty DOUBLE DEFAULT 0.0;
DECLARE stock_cost DOUBLE DEFAULT 0.0;
DECLARE new_qty DOUBLE DEFAULT 0.0;
DECLARE new_cost DOUBLE DEFAULT 0.0;
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT `table_name`,`stock_group_id` INTO tb_name ,stock_id 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id; 
ELSE
	SELECT `table_name`,`stock_group_id` INTO tb_name ,stock_id 
    FROM tb_stock_group 
    LIMIT 0,1; 
END IF;



CALL createRowStockReport(stock_id,p_id);
CALL cost_in(stock_id,p_id,q,cost);



SET @sql = CONCAT('INSERT INTO ',
                  tb_name,' (stock_type,product_id,',
                  'qty,stock_date,summit_product_id)',
				  ' VALUE ("in",',p_id,',',
                  q,',"',sd,'",',sp_id,')');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `insert_stock_supplier` (IN `sg_id` INT, IN `icl_id` INT, IN `p_id` INT, IN `q` INT, IN `sd` VARCHAR(50), IN `cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE col VARCHAR(40);
DECLARE stock_qty DOUBLE DEFAULT 0.0;
DECLARE stock_cost DOUBLE DEFAULT 0.0;
DECLARE new_qty DOUBLE DEFAULT 0.0;
DECLARE new_cost DOUBLE DEFAULT 0.0;
DECLARE stock_id INT DEFAULT 0;

IF sg_id != '0' THEN 
    SELECT `table_name`,`stock_group_id` INTO tb_name ,stock_id 
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id; 
ELSE
	SELECT `table_name`,`stock_group_id` INTO tb_name ,stock_id 
    FROM tb_stock_group 
    LIMIT 0,1; 
END IF;



CALL createRowStockReport(stock_id,p_id);
CALL cost_in(stock_id,p_id,q,cost);



SET @sql = CONCAT('INSERT INTO ',
                  tb_name,' (stock_type,product_id,',
                  'qty,stock_date,invoice_supplier_list_id)',
				  ' VALUE ("in",',p_id,',',
                  q,',"',sd,'",',icl_id,')');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `update_stock_credit` (IN `sg_id` INT, IN `icnl_id` INT, IN `p_id` INT, IN `sd` VARCHAR(50), IN `old_q` INT, IN `old_cost` DOUBLE, IN `new_q` INT, IN `new_cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0.0;

IF sg_id != '0' THEN 
    SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name , stock_group_id INTO tb_name , stock_id 
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;


CALL createRowStockReport(stock_id,p_id);
CALL cost_update(stock_id,p_id,old_q,old_cost,new_q,new_cost);



SET @sql = CONCAT('UPDATE ',
                  tb_name,' SET stock_type = "in", ',
                  'product_id = "',p_id,'", ',
                  'qty = "',q,'", ',
                  'stock_date = "',sd,'" ',
                  'WHERE credit_note_list_id = "',icnl_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `update_stock_customer` (IN `sg_id` INT, IN `icl_id` INT, IN `p_id` INT, IN `sd` VARCHAR(50), IN `old_q` INT, IN `old_cost` DOUBLE, IN `new_q` INT, IN `new_cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0.0;

IF sg_id != '0' THEN 
    SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;

CALL createRowStockReport(stock_id,p_id);
CALL cost_update(stock_id,p_id,old_q,old_cost,new_q,new_cost);




SET @sql = CONCAT('UPDATE ',
                  tb_name,' SET stock_type = "out", ',
                  'product_id = "',p_id,'", ',
                  'qty = "',q,'", ',
                  'stock_date = "',sd,'" ',
                  'WHERE invoice_customer_list_id = "',icl_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `update_stock_issue` (IN `sgi` INT, IN `sili` INT, IN `p_id` INT, IN `sid` VARCHAR(50), IN `old_q` INT, IN `old_cost` INT, IN `new_q` INT, IN `new_cost` INT)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0.0;

IF sg_id != '0' THEN 
    SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;


CALL createRowStockReport(stock_id,p_id);
CALL cost_update(stock_id,p_id,old_q,old_cost,new_q,new_cost);


SET @sql = CONCAT('UPDATE ',
                  tb_name,' SET stock_type = "out", ',
                  'product_id = "',p_id,'", ',
                  'qty = "',q,'", ',
                  'stock_date = "',sid,'" ',
                  'WHERE stock_issue_list_id = "',sili,'"');

PREPARE s FROM @sql;
EXECUTE s;


END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `update_stock_move` (IN `sgio` INT, IN `sgii` INT, IN `smli` INT, IN `pid` INT, IN `smd` VARCHAR(50), IN `old_q` INT, IN `old_cost` DOUBLE, IN `new_q` INT, IN `new_cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name_out VARCHAR(20);
DECLARE tb_name_in VARCHAR(20);

SELECT table_name INTO tb_name_out 
FROM tb_stock_group 
WHERE tb_stock_group.stock_group_id = sgio;

SELECT table_name INTO tb_name_in 
FROM tb_stock_group 
WHERE tb_stock_group.stock_group_id = sgii;

CALL createRowStockReport(stock_id,p_id);
CALL cost_update(sgio,p_id,old_q,old_cost,new_q,new_cost);


SET @sql_out = CONCAT('UPDATE ',
                  tb_name_out,' SET stock_type = "out", ',
                  'product_id = "',pid,'", ',
                  'qty = "',q,'", ',
                  'stock_date = "',smd,'" ',
                  'WHERE stock_move_list_id = "',smli,'"');

PREPARE s_out FROM @sql_out;
EXECUTE s_out;

CALL createRowStockReport(stock_id,p_id);
CALL cost_update(sgii,p_id,old_q,old_cost,new_q,new_cost);


SET @sql_in = CONCAT('UPDATE ',
                  tb_name_in,' SET stock_type = "out", ',
                  'product_id = "',pid,'", ',
                  'qty = "',q,'", ',
                  'stock_date = "',smd,'" ',
                  'WHERE stock_move_list_id = "',smli,'"');

PREPARE s_in FROM @sql_in;
EXECUTE s_in;


END$$

CREATE DEFINER=`revelsof_erp-tm`@`localhost` PROCEDURE `update_stock_supplier` (IN `sg_id` INT, IN `icl_id` INT, IN `p_id` INT, IN `sd` VARCHAR(50), IN `old_q` INT, IN `old_cost` DOUBLE, IN `new_q` INT, IN `new_cost` DOUBLE)  NO SQL
BEGIN
DECLARE tb_name VARCHAR(20);
DECLARE stock_id INT DEFAULT 0.0;

IF sg_id != '0' THEN 
    SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    WHERE tb_stock_group.stock_group_id = sg_id;
ELSE
	SELECT table_name , stock_group_id INTO tb_name , stock_id
    FROM tb_stock_group 
    LIMIT 0,1;
END IF;


CALL createRowStockReport(stock_id,p_id);
CALL cost_update(stock_id,p_id,old_q,old_cost,new_q,new_cost);


SET @sql = CONCAT('UPDATE ',
                  tb_name,' SET stock_type = "in", ',
                  'product_id = "',p_id,'", ',
                  'qty = "',new_q,'", ',
                  'stock_date = "',sd,'" ',
                  'WHERE invoice_supplier_list_id = "',icl_id,'"');

PREPARE s FROM @sql;
EXECUTE s;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `geography`
--

CREATE TABLE `geography` (
  `GEO_ID` int(5) NOT NULL,
  `GEO_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `geography`
--

INSERT INTO `geography` (`GEO_ID`, `GEO_NAME`) VALUES
(1, 'ภาคเหนือ'),
(2, 'ภาคกลาง'),
(3, 'ภาคตะวันออกเฉียงเหนือ'),
(4, 'ภาคตะวันตก'),
(5, 'ภาคตะวันออก'),
(6, 'ภาคใต้');

-- --------------------------------------------------------

--
-- Table structure for table `tb_account`
--

CREATE TABLE `tb_account` (
  `account_id` int(11) NOT NULL,
  `account_code` varchar(100) NOT NULL COMMENT 'เลขที่บัญชี',
  `account_name_th` varchar(100) NOT NULL,
  `account_name_en` varchar(100) NOT NULL COMMENT 'ชื่อบัญชีภาษาอังกฤษ',
  `account_control` int(11) NOT NULL COMMENT 'บัญชีความคุม',
  `account_level` int(11) NOT NULL COMMENT 'ระดับบัญชี',
  `account_group` int(11) NOT NULL COMMENT 'หมวดบัญชี',
  `account_type` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `account_debit_begin` double NOT NULL COMMENT 'เครดิตเริ่มต้น',
  `account_credit_begin` double NOT NULL COMMENT 'เดบิตเริ่มต้น',
  `account_debit` double NOT NULL COMMENT 'เดบิตรวม',
  `account_credit` double NOT NULL COMMENT 'เครดิตรวม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางประเภทบัญชี';

--
-- Dumping data for table `tb_account`
--

INSERT INTO `tb_account` (`account_id`, `account_code`, `account_name_th`, `account_name_en`, `account_control`, `account_level`, `account_group`, `account_type`, `account_debit_begin`, `account_credit_begin`, `account_debit`, `account_credit`) VALUES
(1, '1000-00', 'สินทรัพย์', '', 0, 1, 1, 1, 0, 0, 0, 0),
(2, '1100-00', 'สินทรัพย์หมุนเวียน', '', 1, 2, 1, 1, 0, 0, 0, 0),
(3, '1110-00', 'เงินสดและเงินฝากธนาคาร', '', 2, 3, 1, 1, 0, 0, 0, 0),
(4, '1111-00', 'เงินสด', '', 3, 4, 1, 0, 0, 0, 0, 0),
(5, '1111-50', 'เงินสดย่อย', '', 3, 4, 1, 0, 10000, 0, 0, 0),
(6, '1112-00', 'เงินฝากกระแสรายวัน', '', 3, 4, 1, 1, 0, 0, 0, 0),
(7, '1112-01', 'ธ.กสิกรไทย-กระแสรายวัน 27372-9', '', 6, 5, 1, 0, 0, 19431.99, 0, 0),
(8, '1113-00', 'เงินฝากออมทรัพย์', '', 3, 4, 1, 1, 0, 0, 0, 0),
(9, '1113-01', 'ธ.กสิกรไทย-ออมทรัพย์ 27100-9', '', 8, 5, 1, 0, 631908.25, 0, 0, 0),
(10, '1113-02', 'ธ.ยูโอบี-ออมทรัพย์ ', '', 8, 5, 1, 0, 0, 0, 0, 0),
(11, '1114-00', 'เงินฝากประจำ', '', 3, 4, 1, 1, 0, 0, 0, 0),
(12, '1120-00', 'เงินลงทุนระยะสั้น', '', 2, 3, 1, 0, 0, 0, 0, 0),
(13, '1130-00', 'ลูกหนี้การค้าและตั๋วเงินรับ', '', 2, 3, 1, 1, 0, 0, 0, 0),
(14, '1131-00', 'ลูกหนี้การค้าและตั๋วเงินรับในประเทศ', '', 13, 4, 1, 1, 0, 0, 0, 0),
(15, '1131-01', 'ลูกหนี้การค้า', '', 14, 5, 1, 0, 2348538.72, 0, 0, 0),
(16, '1131-02', 'เช็ครับลงวันที่ล่วงหน้า', '', 14, 5, 1, 0, 0, 0, 0, 0),
(17, '1131-03', 'ลูกหนี้การค้า-อื่น', '', 14, 5, 1, 0, 0, 0, 0, 0),
(18, '1131-05', 'เช็คคืน', '', 14, 5, 1, 0, 0, 0, 0, 0),
(19, '1133-00', 'ค่าเผื่อหนี้สงสัยจะสูญ', '', 14, 5, 1, 1, 0, 0, 0, 0),
(20, '1133-01', 'ลูกหนี้เช็คคืน', '', 19, 6, 1, 0, 0, 0, 0, 0),
(21, '1133-02', 'ลูกหนี้การค้าเกินกำหนด', '', 19, 6, 1, 0, 0, 0, 0, 0),
(22, '1134-01', 'ลูกหนี้บริษัทในเครือ TM', '', 19, 6, 1, 0, 0, 0, 0, 0),
(23, '1136-00', 'ลูกหนี้บริษัทฯในเครือและเกี่ยวข้องกัน', '', 13, 4, 1, 1, 0, 0, 0, 0),
(24, '1140-00', 'สินค้าคงเหลือ', '', 2, 3, 1, 1, 0, 0, 0, 0),
(25, '1140-01', 'สินค้าคงเหลือ-ต้นงวด', '', 24, 4, 1, 0, 0, 0, 0, 0),
(26, '1140-02', 'สินค้าคงเหลือ-ปลายงวด', '', 24, 4, 1, 0, 0, 0, 0, 0),
(27, '1140-03', 'สินค้าระหว่างทาง', '', 24, 4, 1, 0, 0, 0, 0, 0),
(28, '1144-00', 'ค่าเผื่อสินค้าคงเหลือ', '', 24, 4, 1, 1, 0, 0, 0, 0),
(29, '1144-01', 'สำรองสินค้าล้าสมัย', '', 28, 5, 1, 0, 0, 0, 0, 0),
(30, '1144-02', 'สำรองสินค้าต่ำกว่าทุน', '', 28, 5, 1, 0, 0, 0, 0, 0),
(31, '1150-00', 'สินทรัพย์หมุนเวียนอื่น ๆ', '', 2, 3, 1, 1, 0, 0, 0, 0),
(32, '1151-00', 'ค่าใช้จ่ายจ่ายล่วงหน้า', '', 31, 4, 1, 1, 0, 0, 0, 0),
(33, '1151-01', 'ค่าใช้จ่ายจ่ายล่วงหน้า-ค่าสินค้า', '', 32, 5, 1, 0, 0, 0, 0, 0),
(34, '1151-04', 'ค่าใช้จ่ายจ่ายล่วงหน้า-อื่น ๆ', '', 32, 5, 1, 0, 219.18, 0, 0, 0),
(35, '1152-00', 'เงินทดลองจ่ายพนักงาน', '', 31, 4, 1, 0, 44682, 0, 0, 0),
(36, '1152-01', 'เงินทดลองจ่าย-อื่นๆ', '', 31, 4, 1, 0, 0, 0, 0, 0),
(37, '1153-00', 'รายได้ค้างรับ', '', 31, 4, 1, 1, 0, 0, 0, 0),
(38, '1153-01', 'ดอกเบี้ยค้างรับ', '', 37, 5, 1, 0, 0, 0, 0, 0),
(39, '1153-02', 'รายได้ค้างรับอื่น', '', 37, 5, 1, 0, 0, 0, 0, 0),
(40, '1154-00', 'ภาษีซื้อ', '', 31, 4, 1, 0, 0, 0, 0, 0),
(41, '1154-01', 'ภาษีซื้อ-ยังไม่ขอคืน', '', 31, 4, 1, 0, 0, 0, 0, 0),
(42, '1155-00', 'ภาษีซื้อ-ยังไม่ถึงกำหนด', '', 31, 4, 1, 0, 0, 0, 0, 0),
(43, '1156-00', 'ลูกหนี้-กรมสรรพากร', '', 31, 4, 1, 0, 0, 0, 0, 0),
(44, '1157-00', 'ภาษี', '', 31, 4, 1, 0, 0, 0, 0, 0),
(45, '1158-00', 'ภาษีเงินได้นิติบุคคลจ่ายล่วงหน้า', '', 31, 4, 1, 0, 10.51, 0, 0, 0),
(46, '1159-00', 'เงินมัดจำจ่าย', '', 31, 4, 1, 0, 17432.98, 0, 0, 0),
(47, '1200-00', 'ลูกหนี้เงินให้กู้ยืมแก่กรรมการและลูกจ้าง', '', 1, 2, 1, 1, 0, 0, 0, 0),
(48, '1210-00', 'ลูกหนี้เงินให้กู้ยืม-พนักงาน', '', 47, 3, 1, 1, 0, 0, 0, 0),
(49, '1210-01', 'ลูกหนี้เงินให้กู้พนักงาน', '', 48, 4, 1, 0, 0, 0, 0, 0),
(50, '1300-00', 'เงินลงทุนในบริษัทในเครือ', '', 1, 2, 1, 1, 0, 0, 0, 0),
(51, '1400-00', 'ที่่ดิน อาคารและอุปกรณ์สุทธิ', '', 1, 2, 1, 1, 0, 0, 0, 0),
(52, '1410-00', 'ที่ดิน อาคาร และอุปกรณ์', '', 51, 3, 1, 1, 0, 0, 0, 0),
(53, '1410-01', 'ที่ดิน', '', 52, 4, 1, 0, 0, 0, 0, 0),
(54, '1410-02', 'อาคาร', '', 52, 4, 1, 0, 0, 0, 0, 0),
(55, '1410-03', 'อุปกรณ์สำนักงาน', '', 52, 4, 1, 0, 39233.64, 0, 0, 0),
(56, '1410-04', 'เครื่องตกแต่งและเฟอร์นิเจอร์', '', 52, 4, 1, 0, 7350, 0, 0, 0),
(57, '1410-05', 'ยานพาหนะ', '', 52, 4, 1, 0, 0, 0, 0, 0),
(58, '1410-06', 'เครื่่องจักรและอุปกรณ์', '', 52, 4, 1, 0, 0, 0, 0, 0),
(59, '1410-99', 'อาคารห้องชุดระหว่างปรับปรุง', '', 52, 4, 1, 0, 0, 0, 0, 0),
(60, '1420-00', 'ค่าเสื่อมราคาสะสม', '', 51, 3, 1, 1, 0, 0, 0, 0),
(61, '1420-02', 'ค่าเสื่อมราคาสะสม-อาคาร', '', 60, 4, 1, 0, 0, 0, 0, 0),
(62, '1420-03', 'ค่าเสื่อมราคาสะสม-อุปกรณ์สำนักงาน', '', 60, 4, 1, 0, 0, 2780.34, 0, 0),
(63, '1420-04', 'ค่าเสื่อมราคาสะสม-เครื่องตกแต่งสำนักงาน', '', 60, 4, 1, 0, 0, 728.86, 0, 0),
(64, '1420-05', 'ค่าเสื่อมราคาสะสม-ยานพาหนะ', '', 60, 4, 1, 0, 0, 0, 0, 0),
(65, '1420-06', 'ค่าเสื่อมราคาสะสม-เครื่องจักร', '', 60, 4, 1, 0, 0, 0, 0, 0),
(66, '1500-00', 'สินทรัพย์อื่น ๆ', '', 1, 2, 1, 1, 0, 0, 0, 0),
(67, '1500-01', 'กรมธรรม์ประกันอัคคีภัย-สินค้าและอาคาร', '', 66, 3, 1, 0, 0, 0, 0, 0),
(68, '1500-04', 'เงินประกันอื่น', '', 66, 3, 1, 0, 0, 0, 0, 0),
(69, '2000-00', 'หนี้สิน', '', 0, 1, 2, 1, 0, 0, 0, 0),
(70, '2100-00', 'หนี้สินหมุนเวียน', '', 69, 2, 2, 1, 0, 0, 0, 0),
(71, '2120-00', 'เจ้าหนี้การค้าและตั๋วเงินจ่าย', '', 70, 3, 2, 1, 0, 0, 0, 0),
(72, '2120-01', 'เจ้าหนี้การค้า-ในประเทศ', '', 71, 4, 2, 0, 0, 12954.49, 0, 0),
(73, '2120-02', 'เจ้าหนี้การค้า-ต่างประเทศ', '', 71, 4, 2, 0, 0, 2098304.88, 0, 0),
(74, '2121-00', 'เช็คจ่ายล่วงหน้า', '', 71, 4, 2, 1, 0, 0, 0, 0),
(75, '2121-01', 'เช็คจ่ายล่วงหน้า', '', 74, 5, 2, 0, 0, 183456.44, 0, 0),
(76, '2122-00', 'เงินกู้-ธนาคาร T/R', '', 71, 4, 2, 1, 0, 0, 0, 0),
(77, '2122-01', 'เงินกู้-ธ.กสิกรไทย  T/R', '', 76, 5, 2, 0, 0, 0, 0, 0),
(78, '2130-00', 'หนี้สินหมุนเวียนอื่น', '', 70, 3, 2, 1, 0, 0, 0, 0),
(79, '2131-00', 'ค่าใช้จ่ายค้างจ่าย', '', 78, 4, 2, 1, 0, 0, 0, 0),
(80, '2131-04', 'เงินประกันสังคมรอนำส่ง', '', 79, 5, 2, 0, 0, 5750, 0, 0),
(81, '2131-99', 'ค่าใช้จ่ายค้างจ่าย-อื่น ๆ', '', 79, 5, 2, 0, 0, 127770.46, 0, 0),
(82, '2132-00', 'ภาษีถูกหัก ณ ที่จ่าย', '', 78, 4, 2, 1, 0, 0, 0, 0),
(83, '2132-01', 'ภาษีหัก ณ ที่จ่ายค้างจ่าย ภงด.1', '', 82, 5, 2, 0, 0, 6750, 0, 0),
(84, '2132-02', 'ภาษีหัก ณ ที่จ่ายค้างจ่าย ภงด.3', '', 82, 5, 2, 0, 0, 0, 0, 0),
(85, '2132-03', 'ภาษีหัก ณ ที่จ่ายค้างจ่าย ภงด.53', '', 82, 5, 2, 0, 0, 1041.29, 0, 0),
(86, '2133-00', 'เงินมัดจำรับและเงินค้ำประกัน', '', 78, 4, 2, 1, 0, 0, 0, 0),
(87, '2133-01', 'รายได้รับล่วงหน้า-ค่าสินค้า', '', 86, 5, 2, 0, 0, 0, 0, 0),
(88, '2133-02', 'มัดจำรับล่วงหน้า', '', 86, 5, 2, 0, 0, 0, 0, 0),
(89, '2134-00', 'ภาษีมูลค่าเพิ่ม ค้างจ่าย', '', 78, 4, 2, 1, 0, 0, 0, 0),
(90, '2135-00', 'ภาษีขาย', '', 89, 5, 2, 0, 0, 0, 0, 0),
(91, '2136-00', 'ภาษีขาย-รอเรียกเก็บ', '', 89, 5, 2, 0, 0, 0, 0, 0),
(92, '2137-00', 'เจ้าหนี้กรมสรรพากร', '', 89, 5, 2, 0, 0, 23667.68, 0, 0),
(93, '2138-00', 'ภาษีนิติบุคคล ค้างจ่าย', '', 78, 4, 2, 0, 0, 0, 0, 0),
(94, '2200-00', 'เงินกู้ยืมระยะยาว-ธนาคารและสถาบันอื่น', '', 69, 2, 2, 1, 0, 0, 0, 0),
(95, '2210-00', 'เงินกู้ยืมระยะยาว', '', 94, 3, 2, 1, 0, 0, 0, 0),
(96, '2210-01', 'เงินกู้ยืมระยะยาว-UOB', '', 95, 4, 2, 0, 0, 0, 0, 0),
(97, '2300-00', 'หนี้สินอื่น ๆ', '', 69, 2, 2, 1, 0, 0, 0, 0),
(98, '2310-00', 'เจ้าหนี้เช่าซื้อรถยนต์', '', 97, 3, 2, 1, 0, 0, 0, 0),
(99, '2320-00', 'ผลประโยชน์ของพนักงาน', '', 97, 3, 2, 1, 0, 0, 0, 0),
(100, '2320-01', 'Provision for Employee Retirement', '', 99, 4, 2, 0, 0, 0, 0, 0),
(101, '2390-00', 'เงินกู้ยืมจากกรรมการ', '', 97, 3, 2, 1, 0, 0, 0, 0),
(102, '2390-01', 'เงินกู้ยืมจากกรรมการ', '', 101, 4, 2, 0, 0, 0, 0, 0),
(103, '3000-00', 'ส่วนของผู้ถือหุ้น', '', 0, 1, 3, 1, 0, 0, 0, 0),
(104, '3100-00', 'ทุน', '', 103, 2, 3, 0, 0, 1000000, 0, 0),
(105, '3200-00', 'กำไรสะสม', '', 103, 2, 3, 0, 0, 0, 0, 0),
(106, '3300-00', 'กำไร(ขาดทุน)', '', 103, 2, 3, 0, 0, 0, 0, 0),
(107, '3400-00', 'เงินปันผล-จ่าย', '', 103, 2, 3, 0, 0, 0, 0, 0),
(108, '4000-00', 'รายได้', '', 0, 1, 4, 1, 0, 0, 0, 0),
(109, '4100-00', 'รายได้จากการขายสินค้า-สุทธิ', '', 108, 2, 4, 1, 0, 0, 0, 0),
(110, '4100-01', 'รายได้-ขายอะไหล่ชิ้นส่วน', '', 109, 3, 4, 0, 0, 7016518.5, 0, 0),
(111, '4110-01', 'รับคืนสินค้า', '', 109, 3, 4, 0, 0, 0, 0, 0),
(112, '4110-02', 'ส่วนลดจ่าย', '', 109, 3, 4, 0, 0, 0, 0, 0),
(113, '4200-00', 'รายได้จากการซ่อม-สุทธิ', '', 108, 2, 4, 1, 0, 0, 0, 0),
(114, '4900-00', 'รายได้อื่น ๆ', '', 108, 2, 4, 1, 0, 0, 0, 0),
(115, '4910-01', 'ดอกเบี้ยรับ', '', 114, 3, 4, 0, 0, 1051.07, 0, 0),
(116, '4920-01', 'รายได้จากการขายเศษวัสดุ', '', 114, 3, 4, 0, 0, 0, 0, 0),
(117, '4920-02', 'กำไร(ขาดทุน)ขายทรัพย์สิน', '', 114, 3, 4, 0, 0, 0, 0, 0),
(118, '4930-01', 'รายได้อื่น ๆ', '', 114, 3, 4, 0, 0, 0, 0, 0),
(119, '5000-00', 'ค่าใช้จ่าย', '', 0, 1, 5, 1, 0, 0, 0, 0),
(120, '5100-00', 'ต้นทุนขายสุทธิ', '', 119, 2, 5, 1, 0, 0, 0, 0),
(121, '5110-00', 'ต้นทุนสินค้าเพื่อขาย', '', 120, 3, 5, 0, 0, 0, 0, 0),
(122, '5130-00', 'ซื้อสุทธิ', '', 120, 3, 5, 1, 0, 0, 0, 0),
(123, '5130-01', 'ซื้อ', '', 122, 4, 5, 0, 5372755.52, 0, 0, 0),
(124, '5130-02', 'ส่วนลดรับ', '', 122, 4, 5, 0, 0, 0, 0, 0),
(125, '5130-03', 'ส่งคืนสินค้า', '', 122, 4, 5, 0, 0, 0, 0, 0),
(126, '5130-04', 'ค่าใช้จ่ายนำเข้าสินค้า', '', 122, 4, 5, 0, 408380.93, 0, 0, 0),
(127, '5130-05', 'ค่าอากรขาเข้า', '', 122, 4, 5, 0, 450239, 0, 0, 0),
(128, '5130-06', 'ตัดสินค้าทดสอบ', '', 122, 4, 5, 0, 0, 0, 0, 0),
(129, '5140-00', 'ต้นทุนบริการสุทธิ', '', 120, 3, 5, 1, 0, 0, 0, 0),
(130, '5140-01', 'ค่าแรง-Regrind', '', 129, 4, 5, 0, 0, 0, 0, 0),
(131, '5140-02', 'อะไหล่และชิ้นส่วน', '', 129, 4, 5, 0, 0, 0, 0, 0),
(132, '5140-03', 'ค่าแรงซ่อมอะไหล่และชิ้นส่วน', '', 129, 4, 5, 0, 0, 0, 0, 0),
(133, '5150-00', 'ต้นทุนค่าแรงและค่าโสหุ้ย', '', 120, 3, 5, 1, 0, 0, 0, 0),
(134, '5150-01', 'เงินเดือนและค่าแรง', '', 133, 4, 5, 0, 0, 0, 0, 0),
(135, '5150-02', 'ล่วงเวลา', '', 133, 4, 5, 0, 0, 0, 0, 0),
(136, '5150-03', 'โบนัส', '', 133, 4, 5, 0, 0, 0, 0, 0),
(137, '5150-04', 'ประกันสังคม', '', 133, 4, 5, 0, 0, 0, 0, 0),
(138, '5150-05', 'ค่ากองทุนสำรองเลี้ยงชีพ', '', 133, 4, 5, 0, 0, 0, 0, 0),
(139, '5150-06', 'ค่าครองชีพ', '', 133, 4, 5, 0, 0, 0, 0, 0),
(140, '5150-07', 'ค่าสวัสดิการอื่น ๆ', '', 133, 4, 5, 0, 0, 0, 0, 0),
(141, '5150-08', 'ค่าตำแหน่งและวิชาชีพ', '', 133, 4, 5, 0, 0, 0, 0, 0),
(142, '5180-00', 'ค่าสินค้าล้าสมัยและต่ำกว่าทุน', '', 120, 3, 5, 1, 0, 0, 0, 0),
(143, '5180-01', 'ค่าสินค้าล้าสมัย', '', 142, 4, 5, 0, 0, 0, 0, 0),
(144, '5180-02', 'ค่าสินค้าต่ำกว่าทุน', '', 142, 4, 5, 0, 0, 0, 0, 0),
(145, '5190-00', 'สินค้าคงเหลือ', '', 120, 3, 5, 1, 0, 0, 0, 0),
(146, '5190-01', 'สินค้าคงเหลือ -ต้นงวด', '', 145, 4, 5, 0, 0, 0, 0, 0),
(147, '5190-02', 'สินค้าคงเหลือ -ปลายงวด', '', 145, 4, 5, 0, 0, 0, 0, 0),
(148, '5200-00', 'ค่าใช้จ่ายในการขาย', '', 119, 2, 5, 1, 0, 0, 0, 0),
(149, '5210-00', 'ค่านายหน้า', '', 148, 3, 5, 0, 0, 0, 0, 0),
(150, '5220-00', 'ค่าโฆษณาและส่งเสริมการขาย', '', 148, 3, 5, 1, 0, 0, 0, 0),
(151, '5220-01', 'ค่าโฆษณา', '', 150, 4, 5, 0, 0, 0, 0, 0),
(152, '5220-02', 'ค่าส่งเสริมการขาย', '', 150, 4, 5, 0, 26869.86, 0, 0, 0),
(153, '5220-03', 'ค่าสินค้าตัวอย่าง', '', 150, 4, 5, 0, 1713, 0, 0, 0),
(154, '5220-04', 'Premium Gift', '', 150, 4, 5, 0, 0, 0, 0, 0),
(155, '5230-00', 'ค่าขนส่งและค่าพาหนะ', '', 148, 3, 5, 1, 0, 0, 0, 0),
(156, '5230-01', 'ค่าพาหนะ', '', 155, 4, 5, 0, 1090, 0, 0, 0),
(157, '5230-02', 'ค่าใช้จ่ายในการส่งออก', '', 155, 4, 5, 0, 19968.98, 0, 0, 0),
(158, '5240-00', 'ค่าที่พักเดินทางและพาหนะ', '', 148, 3, 5, 1, 0, 0, 0, 0),
(159, '5240-01', 'ค่าใช้จ่ายเดินทางและยานพาหนะ', '', 158, 4, 5, 0, 98569.24, 0, 0, 0),
(160, '5240-02', 'ค่าที่พัก', '', 158, 4, 5, 0, 0, 0, 0, 0),
(161, '5250-00', 'ค่ารับรองและจัดเลี้ยง', '', 148, 3, 5, 1, 0, 0, 0, 0),
(162, '5250-01', 'ค่ารับรอง', '', 161, 4, 5, 0, 9925, 0, 0, 0),
(163, '5250-02', 'ค่าจัดเลี้ยงและสันทนาการ', '', 161, 4, 5, 0, 0, 0, 0, 0),
(164, '5300-00', 'ค่าใช้จ่ายในการบริหาร', '', 119, 2, 5, 1, 0, 0, 0, 0),
(165, '5310-00', 'ค่าใช้จ่ายเกี่ยวกับพนักงาน-เงินเดือนและสวัสดิการ', '', 164, 3, 5, 1, 0, 0, 0, 0),
(166, '5311-00', 'เงินเดือนและค่าล่วงเวลา', '', 165, 4, 5, 1, 0, 0, 0, 0),
(167, '5311-01', 'เงินเดือน', '', 166, 5, 5, 0, 609190.32, 0, 0, 0),
(168, '5311-02', 'ค่าล่วงเวลา', '', 166, 5, 5, 0, 0, 0, 0, 0),
(169, '5311-03', 'ค่าโทรศัพท์', '', 166, 5, 5, 0, 0, 0, 0, 0),
(170, '5311-04', 'ค่าสึกหรอรถ', '', 166, 5, 5, 0, 48000, 0, 0, 0),
(171, '5311-05', 'ค่าคอมมิชชั่น', '', 166, 5, 5, 0, 150000, 0, 0, 0),
(172, '5312-00', 'โบนัส', '', 165, 4, 5, 0, 0, 0, 0, 0),
(173, '5313-00', 'เงินสมทบประกันสังคมและกองทุนอื่น', '', 165, 4, 5, 1, 0, 0, 0, 0),
(174, '5313-01', 'เงินสมทบกองทุนประกันสังคม', '', 173, 5, 5, 0, 14565, 0, 0, 0),
(175, '5313-02', 'เงินสมทบกองทุนทดแทน', '', 173, 5, 5, 0, 740, 0, 0, 0),
(176, '5313-03', 'ค่ากองทุนสำรองเลี้ยงชีพ', '', 173, 5, 5, 0, 0, 0, 0, 0),
(177, '5319-00', 'ค่าสวัสดิการอื่น', '', 165, 4, 5, 1, 0, 0, 0, 0),
(178, '5319-01', 'ค่าเบี้ยประกันชีวิตและอุบัติเหตุ', '', 177, 5, 5, 0, 0, 0, 0, 0),
(179, '5319-02', 'ค่ารักษาพยาบาล', '', 177, 5, 5, 0, 0, 0, 0, 0),
(180, '5319-03', 'ค่าตรวจสุขภาพพนักงาน', '', 177, 5, 5, 0, 0, 0, 0, 0),
(181, '5319-09', 'ค่าสวัสดิการอื่น ๆ', '', 177, 5, 5, 0, 0, 0, 0, 0),
(182, '5320-00', 'ค่าใช้จ่ายสำนักงาน', '', 164, 3, 5, 1, 0, 0, 0, 0),
(183, '5321-00', 'ค่าเช่า', '', 182, 4, 5, 1, 0, 0, 0, 0),
(184, '5321-01', 'ค่าเช่าสำนักงาน', '', 183, 5, 5, 0, 35000, 0, 0, 0),
(185, '5321-02', 'ค่าเช่าห้องเอกสาร', '', 183, 5, 5, 0, 0, 0, 0, 0),
(186, '5322-00', 'ค่ารักษาความปลอดภัยและความสะอาด', '', 182, 4, 5, 1, 0, 0, 0, 0),
(187, '5322-01', 'ค่ายามรักษาความปลอดภัย', '', 186, 5, 5, 0, 0, 0, 0, 0),
(188, '5322-02', 'ค่ารักษาความสะอาด', '', 186, 5, 5, 0, 0, 0, 0, 0),
(189, '5322-03', 'ค่าใช้ัจ่ายส่วนกลาง', '', 186, 5, 5, 0, 0, 0, 0, 0),
(190, '5323-00', 'ค่าเครื่องเขียนและวัสดุสำนักงาน', '', 182, 4, 5, 1, 0, 0, 0, 0),
(191, '5323-01', 'ค่าเครื่องเขียนแบบพิมพ์', '', 190, 5, 5, 0, 8650, 0, 0, 0),
(192, '5323-02', 'วัสดุสิ้นเปลือง', '', 190, 5, 5, 0, 1060, 0, 0, 0),
(193, '5324-00', 'ค่าอบรมและสมาชิกวรสาร', '', 182, 4, 5, 1, 0, 0, 0, 0),
(194, '5324-01', 'ค่าวารสารและสมาชิก', '', 193, 5, 5, 0, 0, 0, 0, 0),
(195, '5324-02', 'ค่าอบรมสัมนา', '', 193, 5, 5, 0, 0, 0, 0, 0),
(196, '5325-00', 'ค่าซ่อมแซมและค่าบำรุงรักษาอื่นๆ', '', 182, 4, 5, 1, 0, 0, 0, 0),
(197, '5325-02', 'ค่าซ่อมแซมบำรุงรักษา', '', 196, 5, 5, 0, 0, 0, 0, 0),
(200, '5330-00', 'ค่าสาธารณูปโภคและสื่อสาร', '', 164, 3, 5, 1, 0, 0, 0, 0),
(201, '5331-00', 'ค่าไฟฟ้าและน้ำประปา', '', 200, 4, 5, 1, 0, 0, 0, 0),
(202, '5331-01', 'ค่าไฟฟ้า', '', 201, 5, 5, 0, 0, 0, 0, 0),
(203, '5331-02', 'ค่าน้ำประปา', '', 201, 5, 5, 0, 0, 0, 0, 0),
(204, '5332-00', 'ค่าโทรศัพท์และการสื่อสารอื่น', '', 200, 4, 5, 1, 0, 0, 0, 0),
(205, '5332-01', 'ค่าติดต่อสื่อสาร', '', 204, 5, 5, 0, 15481.5, 0, 0, 0),
(206, '5332-02', 'ค่าไปรษณีย์', '', 204, 5, 5, 0, 470, 0, 0, 0),
(207, '5340-00', 'ค่าเสื่อมราคา', '', 164, 3, 5, 1, 0, 0, 0, 0),
(208, '5340-02', 'ค่าเสื่อมราคา-อาคาร', '', 207, 4, 5, 0, 0, 0, 0, 0),
(209, '5340-03', 'ค่าเสื่อมราคา-อุปกรณ์สำนักงาน', '', 207, 4, 5, 0, 2780.34, 0, 0, 0),
(210, '5340-04', 'ค่าเสื่อมราคา-เครื่องตกแต่งสำนักงาน', '', 207, 4, 5, 0, 728.86, 0, 0, 0),
(211, '5340-05', 'ค่าเสื่อมราคา-ยานพาหนะ', '', 207, 4, 5, 0, 0, 0, 0, 0),
(212, '5340-06', 'ค่าเสื่อมราคา-เครื่องจักร', '', 207, 4, 5, 0, 0, 0, 0, 0),
(213, '5350-00', 'ค่าเบี้ยประกันภัย', '', 164, 3, 5, 1, 0, 0, 0, 0),
(214, '5350-03', 'ค่าเบี้ยประกัน', '', 213, 4, 5, 0, 0, 0, 0, 0),
(215, '5360-00', 'ค่าภาษีและค่าธรรมเนียม', '', 164, 3, 5, 1, 0, 0, 0, 0),
(216, '5361-00', 'ค่าภาษีและค่าธรรมเนียม', '', 215, 4, 5, 1, 0, 0, 0, 0),
(217, '5361-01', 'ภาษีบำรุงท้องที่และภาษีโรงเรือน', '', 216, 5, 5, 0, 0, 0, 0, 0),
(218, '5361-02', 'ค่าภาษียานพาหนะ', '', 216, 5, 5, 0, 0, 0, 0, 0),
(219, '5361-03', 'ภาษีป้าย', '', 216, 5, 5, 0, 0, 0, 0, 0),
(220, '5361-04', 'ค่าอากร', '', 216, 5, 5, 0, 0, 0, 0, 0),
(221, '5362-00', 'ค่าธรรมเนียมธนาคารและอื่น', '', 215, 4, 5, 1, 0, 0, 0, 0),
(222, '5362-01', 'ค่าธรรมเนียมธนาคาร', '', 221, 5, 5, 0, 7265, 0, 0, 0),
(223, '5362-02', 'ค่าธรรมเนียมหนังสือค้ำประกัน', '', 221, 5, 5, 0, 0, 0, 0, 0),
(224, '5362-03', 'ค่าธรรมเนียมอื่น', '', 221, 5, 5, 0, 12400, 0, 0, 0),
(225, '5363-00', 'ค่าตรวจสอบบัญชีและปรึกษากฏหมาย', '', 215, 4, 5, 1, 0, 0, 0, 0),
(226, '5363-01', 'ค่าตรวจสอบบัญชีและปรึกษากฏหมาย', '', 225, 5, 5, 0, 0, 0, 0, 0),
(227, '5363-02', 'ค่าที่ปรึกษา', '', 225, 5, 5, 0, 0, 0, 0, 0),
(228, '5370-00', 'ค่าใช้จ่ายอื่น ๆ', '', 164, 3, 5, 1, 0, 0, 0, 0),
(229, '5371-00', 'ค่าใช้จ่ายเบ็ดเตล็ด', '', 228, 4, 5, 1, 0, 0, 0, 0),
(230, '5371-01', 'ค่าใช้จ่ายเบ็ดเตล็ด', '', 229, 5, 5, 0, 0, 0, 0, 0),
(231, '5371-02', 'ส่วนลดจากการจ่าย(รับ)เงินสด', '', 229, 5, 5, 0, 0.55, 0, 0, 0),
(232, '5374-00', 'ค่าบริจาคการกุศล', '', 228, 4, 5, 1, 0, 0, 0, 0),
(233, '5374-01', 'ค่าบริจาคการกุศล', '', 232, 5, 5, 0, 0, 0, 0, 0),
(234, '5375-00', 'ขาดเกินจากการตรวจนับสินค้า', '', 228, 4, 5, 1, 0, 0, 0, 0),
(235, '5375-01', 'ขาดเกินจากการตรวจนับสินค้า', '', 234, 5, 5, 0, 0, 0, 0, 0),
(236, '5375-02', 'ค่าใช้จ่ายการคืนสินค้า', '', 234, 5, 5, 0, 0, 0, 0, 0),
(237, '5380-00', 'หนี้สูญ', '', 164, 3, 5, 1, 0, 0, 0, 0),
(238, '5380-01', 'หนี้สงสัยจะสูญ', '', 237, 4, 5, 0, 0, 0, 0, 0),
(239, '5390-00', 'ค่าใช้จ่ายต้องห้าม', '', 164, 3, 5, 1, 0, 0, 0, 0),
(240, '5390-01', 'ภาษีซื้อไม่ขอคืน', '', 239, 4, 5, 0, 0, 0, 0, 0),
(241, '5390-02', 'ภาษีซื้อขอคืนไม่ได้', '', 239, 4, 5, 0, 0, 0, 0, 0),
(242, '5390-03', 'เบี้ยปรับเงินเพิ่ม', '', 239, 4, 5, 0, 0, 0, 0, 0),
(243, '5390-04', 'กำไร(ขาดทุน)จากการตัดจำหน่ายทรัพย์สิน', '', 239, 4, 5, 0, 0, 0, 0, 0),
(244, '5390-06', 'ค่าใช้จ่ายไม่ถือเป็นรายจ่าย', '', 239, 4, 5, 0, 0, 0, 0, 0),
(245, '5800-00', 'กำไร(ขาดทุน)อัตราแลกเปลี่ยน', '', 164, 3, 5, 0, 17606.8, 0, 0, 0),
(246, '5900-00', 'ดอกเบี้ยจ่าย', '', 164, 3, 5, 1, 0, 0, 0, 0),
(247, '5900-01', 'ดอกเบี้ยจ่าย', '', 246, 4, 5, 0, 0, 0, 0, 0),
(248, '5900-02', 'ดอกเบี้ยจ่าย-O.D.', '', 246, 4, 5, 0, 0, 0, 0, 0),
(249, '6000-00', 'ภาษีภาษีเงินได้นิติบุคคล', '', 0, 1, 5, 0, 0, 0, 0, 0),
(250, '9999-99', 'บัญชีพัก', '', 0, 1, 2, 0, 0, 0, 0, 0),
(252, '5311-06', 'ค่าเงินชดเชยเลิกจ้าง', '', 166, 5, 5, 0, 79200, 0, 0, 0),
(253, '5332-03', 'ค่าใช้จ่ายด้าน IT', '', 204, 5, 5, 0, 8180.82, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_account_group`
--

CREATE TABLE `tb_account_group` (
  `account_group_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงกลุ่มบัญชี',
  `account_group_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'กลุ่มบัญชี'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_account_group`
--

INSERT INTO `tb_account_group` (`account_group_id`, `account_group_name`) VALUES
(1, 'ทรัพย์สิน'),
(2, 'หนี้สิน'),
(3, 'ทุน'),
(4, 'รายได้'),
(5, 'ค่าใช้จ่าย'),
(0, 'อื่นๆ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_account_setting`
--

CREATE TABLE `tb_account_setting` (
  `account_setting_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงการตั้งค่าบัญชี',
  `account_setting_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อการตั้งบัญชี',
  `account_group_id` int(11) NOT NULL COMMENT 'กลุ่มบัญชี',
  `account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชี'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_account_setting`
--

INSERT INTO `tb_account_setting` (`account_setting_id`, `account_setting_name`, `account_group_id`, `account_id`) VALUES
(1, 'บัญชีกำไรสะสม', 0, 105),
(2, 'บัญชีพัก', 0, 250),
(9, 'ภาษีซื้อ', 1, 40),
(8, 'ภาษีนิติบุคคลจ่ายล่วงหน้า', 1, 45),
(7, 'สินค้า', 1, 26),
(6, 'เช็ครับล่วงหน้า', 1, 16),
(5, 'ลูกหนี้', 1, 15),
(4, 'เงินสดย่อย', 1, 5),
(3, 'เงินสด', 1, 4),
(10, 'ภาษีซื้อยังไม่ถึงกำหนด', 1, 42),
(11, 'เงินมัดจำจ่ายล่วงหน้า', 1, 46),
(12, 'เจ้าหนี้', 2, 72),
(13, 'เช็คจ่ายล่วงหน้า', 2, 75),
(14, 'ภาษีหัก ณ ที่จ่ายค้างจ่าย', 2, 84),
(15, 'ภาษีขาย', 2, 90),
(16, 'ภาษีขาย-รอเรียกเก็บ', 2, 91),
(17, 'เงินมัดจำรับล่วงหน้า', 2, 87),
(18, 'ขายสด', 4, 110),
(19, 'ขายเชื่อ', 4, 110),
(20, 'รับคืน', 4, 125),
(21, 'ส่วนลดจ่าย', 4, 112),
(22, 'ดอกเบี้ยรับ', 4, 115),
(23, 'รายได้ธนาคาร', 4, 118),
(24, 'รายได้อื่นๆ', 4, 118),
(25, 'ต้นทุนขาย', 5, 121),
(26, 'ซื้อสินค้า', 5, 123),
(27, 'ส่งคืน', 5, 125),
(28, 'ส่วนลดรับ', 5, 124),
(29, 'ดอกเบี้ยจ่าย', 5, 246),
(30, 'ค่าใช้จ่ายธนาคาร', 5, 81),
(31, 'หนี้สูญ', 5, 237),
(32, 'ค่าใช้จ่ายอื่นๆ', 5, 228),
(33, 'ภาษีซื้อไม่ขอคืน', 5, 41),
(34, 'ภาษีขอคืนไม่ได้', 5, 241);

-- --------------------------------------------------------

--
-- Table structure for table `tb_amphur`
--

CREATE TABLE `tb_amphur` (
  `AMPHUR_ID` int(5) NOT NULL,
  `AMPHUR_CODE` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `AMPHUR_NAME` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `POSTCODE` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `GEO_ID` int(5) NOT NULL DEFAULT '0',
  `PROVINCE_ID` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ตารางอำเภอ';

--
-- Dumping data for table `tb_amphur`
--

INSERT INTO `tb_amphur` (`AMPHUR_ID`, `AMPHUR_CODE`, `AMPHUR_NAME`, `POSTCODE`, `GEO_ID`, `PROVINCE_ID`) VALUES
(1, '1001', 'เขตพระนคร   ', '10200', 2, 1),
(2, '1002', 'เขตดุสิต   ', '10300', 2, 1),
(3, '1003', 'เขตหนองจอก   ', '10530', 2, 1),
(4, '1004', 'เขตบางรัก   ', '10500', 2, 1),
(5, '1005', 'เขตบางเขน   ', '10220', 2, 1),
(6, '1006', 'เขตบางกะปิ   ', '10240', 2, 1),
(7, '1007', 'เขตปทุมวัน   ', '10330', 2, 1),
(8, '1008', 'เขตป้อมปราบศัตรูพ่าย   ', '10100', 2, 1),
(9, '1009', 'เขตพระโขนง   ', '10260', 2, 1),
(10, '1010', 'เขตมีนบุรี   ', '10510', 2, 1),
(11, '1011', 'เขตลาดกระบัง   ', '10520', 2, 1),
(12, '1012', 'เขตยานนาวา   ', '10120', 2, 1),
(13, '1013', 'เขตสัมพันธวงศ์   ', '10100', 2, 1),
(14, '1014', 'เขตพญาไท   ', '10400', 2, 1),
(15, '1015', 'เขตธนบุรี   ', '10600', 2, 1),
(16, '1016', 'เขตบางกอกใหญ่   ', '10600', 2, 1),
(17, '1017', 'เขตห้วยขวาง   ', '10310', 2, 1),
(18, '1018', 'เขตคลองสาน   ', '10600', 2, 1),
(19, '1019', 'เขตตลิ่งชัน   ', '10170', 2, 1),
(20, '1020', 'เขตบางกอกน้อย   ', '10700', 2, 1),
(21, '1021', 'เขตบางขุนเทียน   ', '10150', 2, 1),
(22, '1022', 'เขตภาษีเจริญ   ', '10160', 2, 1),
(23, '1023', 'เขตหนองแขม   ', '10160', 2, 1),
(24, '1024', 'เขตราษฎร์บูรณะ   ', '10140', 2, 1),
(25, '1025', 'เขตบางพลัด   ', '10700', 2, 1),
(26, '1026', 'เขตดินแดง   ', '10400', 2, 1),
(27, '1027', 'เขตบึงกุ่ม   ', '10240', 2, 1),
(28, '1028', 'เขตสาทร   ', '10120', 2, 1),
(29, '1029', 'เขตบางซื่อ   ', '10800', 2, 1),
(30, '1030', 'เขตจตุจักร   ', '10900', 2, 1),
(31, '1031', 'เขตบางคอแหลม   ', '10120', 2, 1),
(32, '1032', 'เขตประเวศ   ', '10250', 2, 1),
(33, '1033', 'เขตคลองเตย   ', '10110', 2, 1),
(34, '1034', 'เขตสวนหลวง   ', '10250', 2, 1),
(35, '1035', 'เขตจอมทอง   ', '10150', 2, 1),
(36, '1036', 'เขตดอนเมือง   ', '10210', 2, 1),
(37, '1037', 'เขตราชเทวี   ', '10400', 2, 1),
(38, '1038', 'เขตลาดพร้าว   ', '10230', 2, 1),
(39, '1039', 'เขตวัฒนา   ', '10110', 2, 1),
(40, '1040', 'เขตบางแค   ', '10160', 2, 1),
(41, '1041', 'เขตหลักสี่   ', '10210', 2, 1),
(42, '1042', 'เขตสายไหม   ', '10220', 2, 1),
(43, '1043', 'เขตคันนายาว   ', '10230', 2, 1),
(44, '1044', 'เขตสะพานสูง   ', '10240', 2, 1),
(45, '1045', 'เขตวังทองหลาง   ', '10310', 2, 1),
(46, '1046', 'เขตคลองสามวา   ', '10510', 2, 1),
(47, '1047', 'เขตบางนา   ', '10260', 2, 1),
(48, '1048', 'เขตทวีวัฒนา   ', '10170', 2, 1),
(49, '1049', 'เขตทุ่งครุ   ', '10140', 2, 1),
(50, '1050', 'เขตบางบอน   ', '10150', 2, 1),
(52, '1101', 'เมืองสมุทรปราการ   ', '10270', 2, 2),
(53, '1102', 'บางบ่อ   ', '10560', 2, 2),
(54, '1103', 'บางพลี   ', '10540', 2, 2),
(55, '1104', 'พระประแดง   ', '10130', 2, 2),
(56, '1105', 'พระสมุทรเจดีย์   ', '10290', 2, 2),
(57, '1106', 'บางเสาธง   ', '10540', 2, 2),
(58, '1201', 'เมืองนนทบุรี   ', '11000', 2, 3),
(59, '1202', 'บางกรวย   ', '11130', 2, 3),
(60, '1203', 'บางใหญ่   ', '11140', 2, 3),
(61, '1204', 'บางบัวทอง   ', '11110', 2, 3),
(62, '1205', 'ไทรน้อย   ', '11150', 2, 3),
(63, '1206', 'ปากเกร็ด   ', '11120', 2, 3),
(66, '1301', 'เมืองปทุมธานี   ', '12000', 2, 4),
(67, '1302', 'คลองหลวง   ', '12120', 2, 4),
(68, '1303', 'ธัญบุรี   ', '12110', 2, 4),
(69, '1304', 'หนองเสือ   ', '12170', 2, 4),
(70, '1305', 'ลาดหลุมแก้ว   ', '12140', 2, 4),
(71, '1306', 'ลำลูกกา   ', '12150', 2, 4),
(72, '1307', 'สามโคก   ', '12160', 2, 4),
(74, '1401', 'พระนครศรีอยุธยา   ', '13000', 2, 5),
(75, '1402', 'ท่าเรือ   ', '13130', 2, 5),
(76, '1403', 'นครหลวง   ', '13260', 2, 5),
(77, '1404', 'บางไทร   ', '13190', 2, 5),
(78, '1405', 'บางบาล   ', '13250', 2, 5),
(79, '1406', 'บางปะอิน   ', '13160', 2, 5),
(80, '1407', 'บางปะหัน   ', '13220', 2, 5),
(81, '1408', 'ผักไห่   ', '13120', 2, 5),
(82, '1409', 'ภาชี   ', '13140', 2, 5),
(83, '1410', 'ลาดบัวหลวง   ', '13230', 2, 5),
(84, '1411', 'วังน้อย   ', '13170', 2, 5),
(85, '1412', 'เสนา   ', '13110', 2, 5),
(86, '1413', 'บางซ้าย   ', '13270', 2, 5),
(87, '1414', 'อุทัย   ', '13210', 2, 5),
(88, '1415', 'มหาราช   ', '13150', 2, 5),
(89, '1416', 'บ้านแพรก   ', '13240', 2, 5),
(90, '1501', 'เมืองอ่างทอง   ', '14000', 2, 6),
(91, '1502', 'ไชโย   ', '14140', 2, 6),
(92, '1503', 'ป่าโมก   ', '14130', 2, 6),
(93, '1504', 'โพธิ์ทอง   ', '14120', 2, 6),
(94, '1505', 'แสวงหา   ', '14150', 2, 6),
(95, '1506', 'วิเศษชัยชาญ   ', '14110', 2, 6),
(96, '1507', 'สามโก้   ', '14160', 2, 6),
(97, '1601', 'เมืองลพบุรี   ', '15000', 2, 7),
(98, '1602', 'พัฒนานิคม   ', '15140', 2, 7),
(99, '1603', 'โคกสำโรง   ', '15120', 2, 7),
(100, '1604', 'ชัยบาดาล   ', '15130', 2, 7),
(101, '1605', 'ท่าวุ้ง   ', '15150', 2, 7),
(102, '1606', 'บ้านหมี่   ', '15110', 2, 7),
(103, '1607', 'ท่าหลวง   ', '15230', 2, 7),
(104, '1608', 'สระโบสถ์   ', '15240', 2, 7),
(105, '1609', 'โคกเจริญ   ', '15250', 2, 7),
(106, '1610', 'ลำสนธิ   ', '15190', 2, 7),
(107, '1611', 'หนองม่วง   ', '15170', 2, 7),
(109, '1701', 'เมืองสิงห์บุรี   ', '16000', 2, 8),
(110, '1702', 'บางระจัน   ', '16130', 2, 8),
(111, '1703', 'ค่ายบางระจัน   ', '16150', 2, 8),
(112, '1704', 'พรหมบุรี   ', '16120', 2, 8),
(113, '1705', 'ท่าช้าง   ', '16140', 2, 8),
(114, '1706', 'อินทร์บุรี   ', '16110', 2, 8),
(115, '1801', 'เมืองชัยนาท   ', '17000', 2, 9),
(116, '1802', 'มโนรมย์   ', '17110', 2, 9),
(117, '1803', 'วัดสิงห์   ', '17120', 2, 9),
(118, '1804', 'สรรพยา   ', '17150', 2, 9),
(119, '1805', 'สรรคบุรี   ', '17140', 2, 9),
(120, '1806', 'หันคา   ', '17130', 2, 9),
(121, '1807', 'หนองมะโมง   ', '17120', 2, 9),
(122, '1808', 'เนินขาม   ', '17130', 2, 9),
(123, '1901', 'เมืองสระบุรี   ', '18000', 2, 10),
(124, '1902', 'แก่งคอย   ', '18110', 2, 10),
(125, '1903', 'หนองแค   ', '18140', 2, 10),
(126, '1904', 'วิหารแดง   ', '18150', 2, 10),
(127, '1905', 'หนองแซง   ', '18170', 2, 10),
(128, '1906', 'บ้านหมอ   ', '18130', 2, 10),
(129, '1907', 'ดอนพุด   ', '18210', 2, 10),
(130, '1908', 'หนองโดน   ', '18190', 2, 10),
(131, '1909', 'พระพุทธบาท   ', '18120', 2, 10),
(132, '1910', 'เสาไห้   ', '18160', 2, 10),
(133, '1911', 'มวกเหล็ก   ', '18180', 2, 10),
(134, '1912', 'วังม่วง   ', '18220', 2, 10),
(135, '1913', 'เฉลิมพระเกียรติ', '00000', 2, 10),
(136, '2001', 'เมืองชลบุรี   ', '20000', 5, 11),
(137, '2002', 'บ้านบึง   ', '20170', 5, 11),
(138, '2003', 'หนองใหญ่   ', '20190', 5, 11),
(139, '2004', 'บางละมุง   ', '20150', 5, 11),
(140, '2005', 'พานทอง   ', '20160', 5, 11),
(141, '2006', 'พนัสนิคม   ', '20140', 5, 11),
(142, '2007', 'ศรีราชา   ', '20110', 5, 11),
(143, '2008', 'เกาะสีชัง   ', '20120', 5, 11),
(144, '2009', 'สัตหีบ   ', '20180', 5, 11),
(145, '2010', 'บ่อทอง   ', '20270', 5, 11),
(146, '2011', 'เกาะจันทร์   ', '20240', 5, 11),
(147, '2051', 'สัตหีบ (สาขาตำบลบางเสร่)*   ', '20250', 5, 11),
(148, '2072', 'ท้องถิ่นเทศบาลเมืองหนองปรือ*   ', '00000', 5, 11),
(149, '2093', 'เทศบาลตำบลแหลมฉบัง*   ', '00000', 5, 11),
(150, '2099', 'เทศบาลเมืองชลบุรี*   ', '00000', 5, 11),
(151, '2101', 'เมืองระยอง   ', '21000', 5, 12),
(152, '2102', 'บ้านฉาง   ', '21130', 5, 12),
(153, '2103', 'แกลง   ', '21110', 5, 12),
(154, '2104', 'วังจันทร์   ', '21210', 5, 12),
(155, '2105', 'บ้านค่าย   ', '21120', 5, 12),
(156, '2106', 'ปลวกแดง   ', '21140', 5, 12),
(157, '2107', 'เขาชะเมา   ', '21110', 5, 12),
(158, '2108', 'นิคมพัฒนา   ', '21180', 5, 12),
(159, '2151', 'สาขาตำบลมาบข่า*   ', '00000', 5, 12),
(160, '2201', 'เมืองจันทบุรี   ', '22000', 5, 13),
(161, '2202', 'ขลุง   ', '22110', 5, 13),
(162, '2203', 'ท่าใหม่   ', '22120', 5, 13),
(163, '2204', 'โป่งน้ำร้อน   ', '22140', 5, 13),
(164, '2205', 'มะขาม   ', '22150', 5, 13),
(165, '2206', 'แหลมสิงห์   ', '22130', 5, 13),
(166, '2207', 'สอยดาว   ', '22180', 5, 13),
(167, '2208', 'แก่งหางแมว   ', '22160', 5, 13),
(168, '2209', 'นายายอาม   ', '22160', 5, 13),
(169, '2210', 'เขาคิชฌกูฏ   ', '22210', 5, 13),
(170, '2281', '*กิ่ง อ.กำพุธ  จ.จันทบุรี   ', '00000', 5, 13),
(171, '2301', 'เมืองตราด   ', '23000', 5, 14),
(172, '2302', 'คลองใหญ่   ', '23110', 5, 14),
(173, '2303', 'เขาสมิง   ', '23130', 5, 14),
(174, '2304', 'บ่อไร่   ', '23140', 5, 14),
(175, '2305', 'แหลมงอบ   ', '23120', 5, 14),
(176, '2306', 'เกาะกูด   ', '23000', 5, 14),
(177, '2307', 'เกาะช้าง   ', '23170', 5, 14),
(178, '2401', 'เมืองฉะเชิงเทรา   ', '24000', 5, 15),
(179, '2402', 'บางคล้า   ', '24110', 5, 15),
(180, '2403', 'บางน้ำเปรี้ยว   ', '24150', 5, 15),
(181, '2404', 'บางปะกง   ', '24130', 5, 15),
(182, '2405', 'บ้านโพธิ์   ', '24140', 5, 15),
(183, '2406', 'พนมสารคาม   ', '24120', 5, 15),
(184, '2407', 'ราชสาส์น   ', '24120', 5, 15),
(185, '2408', 'สนามชัยเขต   ', '24160', 5, 15),
(186, '2409', 'แปลงยาว   ', '24190', 5, 15),
(187, '2410', 'ท่าตะเกียบ   ', '24160', 5, 15),
(188, '2411', 'คลองเขื่อน   ', '24000', 5, 15),
(189, '2501', 'เมืองปราจีนบุรี   ', '25000', 5, 16),
(190, '2502', 'กบินทร์บุรี   ', '25110', 5, 16),
(191, '2503', 'นาดี   ', '25220', 5, 16),
(192, '2504', '*สระแก้ว   ', '00000', 5, 16),
(193, '2505', '*วังน้ำเย็น   ', '00000', 5, 16),
(194, '2506', 'บ้านสร้าง   ', '25150', 5, 16),
(195, '2507', 'ประจันตคาม   ', '25130', 5, 16),
(196, '2508', 'ศรีมหาโพธิ   ', '25140', 5, 16),
(197, '2509', 'ศรีมโหสถ   ', '25190', 5, 16),
(198, '2510', '*อรัญประเทศ   ', '00000', 5, 16),
(199, '2511', '*ตาพระยา   ', '00000', 5, 16),
(200, '2512', '*วัฒนานคร   ', '00000', 5, 16),
(201, '2513', '*คลองหาด   ', '00000', 5, 16),
(202, '2601', 'เมืองนครนายก   ', '26000', 2, 17),
(203, '2602', 'ปากพลี   ', '26130', 2, 17),
(204, '2603', 'บ้านนา   ', '26110', 2, 17),
(205, '2604', 'องครักษ์   ', '26120', 2, 17),
(206, '2701', 'เมืองสระแก้ว   ', '27000', 5, 18),
(207, '2702', 'คลองหาด   ', '27260', 5, 18),
(208, '2703', 'ตาพระยา   ', '27180', 5, 18),
(209, '2704', 'วังน้ำเย็น   ', '27210', 5, 18),
(210, '2705', 'วัฒนานคร   ', '27160', 5, 18),
(211, '2706', 'อรัญประเทศ   ', '27120', 5, 18),
(212, '2707', 'เขาฉกรรจ์   ', '27000', 5, 18),
(213, '2708', 'โคกสูง   ', '27120', 5, 18),
(214, '2709', 'วังสมบูรณ์   ', '27250', 5, 18),
(215, '3001', 'เมืองนครราชสีมา   ', '30000', 3, 19),
(216, '3002', 'ครบุรี   ', '30250', 3, 19),
(217, '3003', 'เสิงสาง   ', '30330', 3, 19),
(218, '3004', 'คง   ', '30260', 3, 19),
(219, '3005', 'บ้านเหลื่อม   ', '30350', 3, 19),
(220, '3006', 'จักราช   ', '30230', 3, 19),
(221, '3007', 'โชคชัย   ', '30190', 3, 19),
(222, '3008', 'ด่านขุนทด   ', '30210', 3, 19),
(223, '3009', 'โนนไทย   ', '30220', 3, 19),
(224, '3010', 'โนนสูง   ', '30160', 3, 19),
(225, '3011', 'ขามสะแกแสง   ', '30290', 3, 19),
(226, '3012', 'บัวใหญ่   ', '30120', 3, 19),
(227, '3013', 'ประทาย   ', '30180', 3, 19),
(228, '3014', 'ปักธงชัย   ', '30150', 3, 19),
(229, '3015', 'พิมาย   ', '30110', 3, 19),
(230, '3016', 'ห้วยแถลง   ', '30240', 3, 19),
(231, '3017', 'ชุมพวง   ', '30270', 3, 19),
(232, '3018', 'สูงเนิน   ', '30170', 3, 19),
(233, '3019', 'ขามทะเลสอ   ', '30280', 3, 19),
(234, '3020', 'สีคิ้ว   ', '30140', 3, 19),
(235, '3021', 'ปากช่อง   ', '30130', 3, 19),
(236, '3022', 'หนองบุญมาก   ', '30410', 3, 19),
(237, '3023', 'แก้งสนามนาง   ', '30440', 3, 19),
(238, '3024', 'โนนแดง   ', '30360', 3, 19),
(239, '3025', 'วังน้ำเขียว   ', '30370', 3, 19),
(240, '3026', 'เทพารักษ์   ', '30210', 3, 19),
(241, '3027', 'เมืองยาง   ', '30270', 3, 19),
(242, '3028', 'พระทองคำ   ', '30220', 3, 19),
(243, '3029', 'ลำทะเมนชัย   ', '30270', 3, 19),
(244, '3030', 'บัวลาย   ', '30120', 3, 19),
(245, '3031', 'สีดา   ', '30430', 3, 19),
(246, '3032', 'เฉลิมพระเกียรติ', '18000', 3, 19),
(247, '3049', 'ท้องถิ่นเทศบาลตำบลโพธิ์กลาง*   ', '00000', 3, 19),
(248, '3051', 'สาขาตำบลมะค่า-พลสงคราม*   ', '00000', 3, 19),
(249, '3081', '*โนนลาว   ', '00000', 3, 19),
(250, '3101', 'เมืองบุรีรัมย์   ', '31000', 3, 20),
(251, '3102', 'คูเมือง   ', '31190', 3, 20),
(252, '3103', 'กระสัง', '31160', 3, 20),
(253, '3104', 'นางรอง   ', '31110', 3, 20),
(254, '3105', 'หนองกี่   ', '31210', 3, 20),
(255, '3106', 'ละหานทราย   ', '31170', 3, 20),
(256, '3107', 'ประโคนชัย   ', '31140', 3, 20),
(257, '3108', 'บ้านกรวด   ', '31180', 3, 20),
(258, '3109', 'พุทไธสง   ', '31120', 3, 20),
(259, '3110', 'ลำปลายมาศ   ', '31130', 3, 20),
(260, '3111', 'สตึก   ', '31150', 3, 20),
(261, '3112', 'ปะคำ   ', '31220', 3, 20),
(262, '3113', 'นาโพธิ์   ', '31230', 3, 20),
(263, '3114', 'หนองหงส์   ', '31240', 3, 20),
(264, '3115', 'พลับพลาชัย   ', '31250', 3, 20),
(265, '3116', 'ห้วยราช   ', '31000', 3, 20),
(266, '3117', 'โนนสุวรรณ   ', '31110', 3, 20),
(267, '3118', 'ชำนิ   ', '31110', 3, 20),
(268, '3119', 'บ้านใหม่ไชยพจน์   ', '31120', 3, 20),
(269, '3120', 'โนนดินแดง   ', '31260', 3, 20),
(270, '3121', 'บ้านด่าน   ', '31000', 3, 20),
(271, '3122', 'แคนดง   ', '31150', 3, 20),
(272, '3123', 'เฉลิมพระเกียรติ', '00000', 3, 20),
(273, '3201', 'เมืองสุรินทร์   ', '32000', 3, 21),
(274, '3202', 'ชุมพลบุรี   ', '32190', 3, 21),
(275, '3203', 'ท่าตูม   ', '32120', 3, 21),
(276, '3204', 'จอมพระ   ', '32180', 3, 21),
(277, '3205', 'ปราสาท   ', '32140', 3, 21),
(278, '3206', 'กาบเชิง   ', '32210', 3, 21),
(279, '3207', 'รัตนบุรี   ', '32130', 3, 21),
(280, '3208', 'สนม   ', '32160', 3, 21),
(281, '3209', 'ศีขรภูมิ   ', '32110', 3, 21),
(282, '3210', 'สังขะ   ', '32150', 3, 21),
(283, '3211', 'ลำดวน   ', '32220', 3, 21),
(284, '3212', 'สำโรงทาบ   ', '32170', 3, 21),
(285, '3213', 'บัวเชด   ', '32230', 3, 21),
(286, '3214', 'พนมดงรัก   ', '32140', 3, 21),
(287, '3215', 'ศรีณรงค์   ', '32150', 3, 21),
(288, '3216', 'เขวาสินรินทร์   ', '32000', 3, 21),
(289, '3217', 'โนนนารายณ์   ', '32130', 3, 21),
(290, '3301', 'เมืองศรีสะเกษ   ', '33000', 3, 22),
(291, '3302', 'ยางชุมน้อย   ', '33190', 3, 22),
(292, '3303', 'กันทรารมย์   ', '33130', 3, 22),
(293, '3304', 'กันทรลักษ์   ', '33110', 3, 22),
(294, '3305', 'ขุขันธ์   ', '33140', 3, 22),
(295, '3306', 'ไพรบึง   ', '33180', 3, 22),
(296, '3307', 'ปรางค์กู่   ', '33170', 3, 22),
(297, '3308', 'ขุนหาญ   ', '33150', 3, 22),
(298, '3309', 'ราษีไศล   ', '33160', 3, 22),
(299, '3310', 'อุทุมพรพิสัย   ', '33120', 3, 22),
(300, '3311', 'บึงบูรพ์   ', '33220', 3, 22),
(301, '3312', 'ห้วยทับทัน   ', '33210', 3, 22),
(302, '3313', 'โนนคูณ   ', '33250', 3, 22),
(303, '3314', 'ศรีรัตนะ   ', '33240', 3, 22),
(304, '3315', 'น้ำเกลี้ยง   ', '33130', 3, 22),
(305, '3316', 'วังหิน   ', '33270', 3, 22),
(306, '3317', 'ภูสิงห์   ', '33140', 3, 22),
(307, '3318', 'เมืองจันทร์   ', '33120', 3, 22),
(308, '3319', 'เบญจลักษ์   ', '33110', 3, 22),
(309, '3320', 'พยุห์   ', '33230', 3, 22),
(310, '3321', 'โพธิ์ศรีสุวรรณ   ', '33120', 3, 22),
(311, '3322', 'ศิลาลาด   ', '33160', 3, 22),
(312, '3401', 'เมืองอุบลราชธานี   ', '34000', 3, 23),
(313, '3402', 'ศรีเมืองใหม่   ', '34250', 3, 23),
(314, '3403', 'โขงเจียม   ', '34220', 3, 23),
(315, '3404', 'เขื่องใน   ', '34150', 3, 23),
(316, '3405', 'เขมราฐ   ', '34170', 3, 23),
(317, '3406', '*ชานุมาน   ', '00000', 3, 23),
(318, '3407', 'เดชอุดม   ', '34160', 3, 23),
(319, '3408', 'นาจะหลวย   ', '34280', 3, 23),
(320, '3409', 'น้ำยืน   ', '34260', 3, 23),
(321, '3410', 'บุณฑริก   ', '34230', 3, 23),
(322, '3411', 'ตระการพืชผล   ', '34130', 3, 23),
(323, '3412', 'กุดข้าวปุ้น   ', '34270', 3, 23),
(324, '3413', '*พนา   ', '00000', 3, 23),
(325, '3414', 'ม่วงสามสิบ   ', '34140', 3, 23),
(326, '3415', 'วารินชำราบ   ', '34190', 3, 23),
(327, '3416', '*อำนาจเจริญ   ', '00000', 3, 23),
(328, '3417', '*เสนางคนิคม   ', '00000', 3, 23),
(329, '3418', '*หัวตะพาน   ', '00000', 3, 23),
(330, '3419', 'พิบูลมังสาหาร   ', '34110', 3, 23),
(331, '3420', 'ตาลสุม   ', '34330', 3, 23),
(332, '3421', 'โพธิ์ไทร   ', '34340', 3, 23),
(333, '3422', 'สำโรง   ', '34360', 3, 23),
(334, '3423', '*กิ่งอำเภอลืออำนาจ   ', '00000', 3, 23),
(335, '3424', 'ดอนมดแดง   ', '34000', 3, 23),
(336, '3425', 'สิรินธร   ', '34350', 3, 23),
(337, '3426', 'ทุ่งศรีอุดม   ', '34160', 3, 23),
(338, '3427', '*ปทุมราชวงศา   ', '00000', 3, 23),
(339, '3428', '*กิ่งอำเภอศรีหลักชัย   ', '00000', 3, 23),
(340, '3429', 'นาเยีย   ', '34160', 3, 23),
(341, '3430', 'นาตาล   ', '34170', 3, 23),
(342, '3431', 'เหล่าเสือโก้ก   ', '34000', 3, 23),
(343, '3432', 'สว่างวีระวงศ์   ', '34190', 3, 23),
(344, '3433', 'น้ำขุ่น   ', '34260', 3, 23),
(345, '3481', '*อ.สุวรรณวารี  จ.อุบลราชธานี   ', '00000', 3, 23),
(346, '3501', 'เมืองยโสธร   ', '35000', 3, 24),
(347, '3502', 'ทรายมูล   ', '35170', 3, 24),
(348, '3503', 'กุดชุม   ', '35140', 3, 24),
(349, '3504', 'คำเขื่อนแก้ว   ', '35110', 3, 24),
(350, '3505', 'ป่าติ้ว   ', '35150', 3, 24),
(351, '3506', 'มหาชนะชัย   ', '35130', 3, 24),
(352, '3507', 'ค้อวัง   ', '35160', 3, 24),
(353, '3508', 'เลิงนกทา   ', '35120', 3, 24),
(354, '3509', 'ไทยเจริญ   ', '35120', 3, 24),
(355, '3601', 'เมืองชัยภูมิ   ', '36000', 3, 25),
(356, '3602', 'บ้านเขว้า   ', '36170', 3, 25),
(357, '3603', 'คอนสวรรค์   ', '36140', 3, 25),
(358, '3604', 'เกษตรสมบูรณ์   ', '36120', 3, 25),
(359, '3605', 'หนองบัวแดง   ', '36210', 3, 25),
(360, '3606', 'จัตุรัส   ', '36130', 3, 25),
(361, '3607', 'บำเหน็จณรงค์   ', '36160', 3, 25),
(362, '3608', 'หนองบัวระเหว   ', '36250', 3, 25),
(363, '3609', 'เทพสถิต   ', '36230', 3, 25),
(364, '3610', 'ภูเขียว   ', '36110', 3, 25),
(365, '3611', 'บ้านแท่น   ', '36190', 3, 25),
(366, '3612', 'แก้งคร้อ   ', '36150', 3, 25),
(367, '3613', 'คอนสาร   ', '36180', 3, 25),
(368, '3614', 'ภักดีชุมพล   ', '36260', 3, 25),
(369, '3615', 'เนินสง่า   ', '36130', 3, 25),
(370, '3616', 'ซับใหญ่   ', '36130', 3, 25),
(371, '3651', 'เมืองชัยภูมิ (สาขาตำบลโนนสำราญ)*   ', '00000', 3, 25),
(372, '3652', 'สาขาตำบลบ้านหว่าเฒ่า*   ', '00000', 3, 25),
(373, '3653', 'หนองบัวแดง (สาขาตำบลวังชมภู)*   ', '00000', 3, 25),
(374, '3654', 'กิ่งอำเภอซับใหญ่ (สาขาตำบลซับใหญ่)*   ', '00000', 3, 25),
(375, '3655', 'สาขาตำบลโคกเพชร*   ', '00000', 3, 25),
(376, '3656', 'เทพสถิต (สาขาตำบลนายางกลัก)*   ', '00000', 3, 25),
(377, '3657', 'บ้านแท่น (สาขาตำบลบ้านเต่า)*   ', '00000', 3, 25),
(378, '3658', 'แก้งคร้อ (สาขาตำบลท่ามะไฟหวาน)*   ', '00000', 3, 25),
(379, '3659', 'คอนสาร (สาขาตำบลโนนคูณ)*   ', '00000', 3, 25),
(380, '3701', 'เมืองอำนาจเจริญ   ', '37000', 3, 26),
(381, '3702', 'ชานุมาน   ', '37210', 3, 26),
(382, '3703', 'ปทุมราชวงศา   ', '37110', 3, 26),
(383, '3704', 'พนา   ', '37180', 3, 26),
(384, '3705', 'เสนางคนิคม   ', '37290', 3, 26),
(385, '3706', 'หัวตะพาน   ', '37240', 3, 26),
(386, '3707', 'ลืออำนาจ   ', '37000', 3, 26),
(387, '3901', 'เมืองหนองบัวลำภู   ', '39000', 3, 27),
(388, '3902', 'นากลาง   ', '39170', 3, 27),
(389, '3903', 'โนนสัง   ', '39140', 3, 27),
(390, '3904', 'ศรีบุญเรือง   ', '39180', 3, 27),
(391, '3905', 'สุวรรณคูหา   ', '39270', 3, 27),
(392, '3906', 'นาวัง   ', '39170', 3, 27),
(393, '4001', 'เมืองขอนแก่น   ', '40000', 3, 28),
(394, '4002', 'บ้านฝาง   ', '40270', 3, 28),
(395, '4003', 'พระยืน   ', '40320', 3, 28),
(396, '4004', 'หนองเรือ   ', '40210', 3, 28),
(397, '4005', 'ชุมแพ   ', '40130', 3, 28),
(398, '4006', 'สีชมพู   ', '40220', 3, 28),
(399, '4007', 'น้ำพอง   ', '40140', 3, 28),
(400, '4008', 'อุบลรัตน์   ', '40250', 3, 28),
(401, '4009', 'กระนวน   ', '40170', 3, 28),
(402, '4010', 'บ้านไผ่   ', '40110', 3, 28),
(403, '4011', 'เปือยน้อย   ', '40340', 3, 28),
(404, '4012', 'พล   ', '40120', 3, 28),
(405, '4013', 'แวงใหญ่   ', '40330', 3, 28),
(406, '4014', 'แวงน้อย   ', '40230', 3, 28),
(407, '4015', 'หนองสองห้อง   ', '40190', 3, 28),
(408, '4016', 'ภูเวียง   ', '40150', 3, 28),
(409, '4017', 'มัญจาคีรี   ', '40160', 3, 28),
(410, '4018', 'ชนบท   ', '40180', 3, 28),
(411, '4019', 'เขาสวนกวาง   ', '40280', 3, 28),
(412, '4020', 'ภูผาม่าน   ', '40350', 3, 28),
(413, '4021', 'ซำสูง   ', '40170', 3, 28),
(414, '4022', 'โคกโพธิ์ไชย   ', '40160', 3, 28),
(415, '4023', 'หนองนาคำ   ', '40150', 3, 28),
(416, '4024', 'บ้านแฮด   ', '40110', 3, 28),
(417, '4025', 'โนนศิลา   ', '00000', 3, 28),
(418, '4029', 'เวียงเก่า   ', '40150', 3, 28),
(419, '4068', 'ท้องถิ่นเทศบาลตำบลบ้านเป็ด*   ', '00000', 3, 28),
(420, '4098', 'เทศบาลตำบลเมืองพล*   ', '00000', 3, 28),
(421, '4101', 'เมืองอุดรธานี   ', '41000', 3, 29),
(422, '4102', 'กุดจับ   ', '41250', 3, 29),
(423, '4103', 'หนองวัวซอ   ', '41220', 3, 29),
(424, '4104', 'กุมภวาปี   ', '41110', 3, 29),
(425, '4105', 'โนนสะอาด   ', '41240', 3, 29),
(426, '4106', 'หนองหาน   ', '41130', 3, 29),
(427, '4107', 'ทุ่งฝน   ', '41310', 3, 29),
(428, '4108', 'ไชยวาน   ', '41290', 3, 29),
(429, '4109', 'ศรีธาตุ   ', '41230', 3, 29),
(430, '4110', 'วังสามหมอ   ', '41280', 3, 29),
(431, '4111', 'บ้านดุง   ', '41190', 3, 29),
(432, '4112', '*หนองบัวลำภู   ', '00000', 3, 29),
(433, '4113', '*ศรีบุญเรือง   ', '00000', 3, 29),
(434, '4114', '*นากลาง   ', '00000', 3, 29),
(435, '4115', '*สุวรรณคูหา   ', '00000', 3, 29),
(436, '4116', '*โนนสัง   ', '00000', 3, 29),
(437, '4117', 'บ้านผือ   ', '41160', 3, 29),
(438, '4118', 'น้ำโสม   ', '41210', 3, 29),
(439, '4119', 'เพ็ญ   ', '41150', 3, 29),
(440, '4120', 'สร้างคอม   ', '41260', 3, 29),
(441, '4121', 'หนองแสง   ', '41340', 3, 29),
(442, '4122', 'นายูง   ', '41380', 3, 29),
(443, '4123', 'พิบูลย์รักษ์   ', '41130', 3, 29),
(444, '4124', 'กู่แก้ว   ', '41130', 3, 29),
(445, '4125', 'ประจักษ์ศิลปาคม   ', '41110', 3, 29),
(446, '4201', 'เมืองเลย   ', '42000', 3, 30),
(447, '4202', 'นาด้วง   ', '42210', 3, 30),
(448, '4203', 'เชียงคาน   ', '42110', 3, 30),
(449, '4204', 'ปากชม   ', '42150', 3, 30),
(450, '4205', 'ด่านซ้าย   ', '42120', 3, 30),
(451, '4206', 'นาแห้ว   ', '42170', 3, 30),
(452, '4207', 'ภูเรือ   ', '42160', 3, 30),
(453, '4208', 'ท่าลี่   ', '42140', 3, 30),
(454, '4209', 'วังสะพุง   ', '42130', 3, 30),
(455, '4210', 'ภูกระดึง   ', '42180', 3, 30),
(456, '4211', 'ภูหลวง   ', '42230', 3, 30),
(457, '4212', 'ผาขาว   ', '42240', 3, 30),
(458, '4213', 'เอราวัณ   ', '42220', 3, 30),
(459, '4214', 'หนองหิน   ', '42190', 3, 30),
(460, '4301', 'เมืองหนองคาย   ', '43000', 3, 31),
(461, '4302', 'ท่าบ่อ   ', '43110', 3, 31),
(462, '4303', 'เมืองบึงกาฬ   ', '38000', 3, 77),
(463, '4304', 'พรเจริญ   ', '38180', 3, 77),
(464, '4305', 'โพนพิสัย   ', '43120', 3, 31),
(465, '4306', 'โซ่พิสัย   ', '38170', 3, 77),
(466, '4307', 'ศรีเชียงใหม่   ', '43130', 3, 31),
(467, '4308', 'สังคม   ', '43160', 3, 31),
(468, '4309', 'เซกา   ', '38150', 3, 77),
(469, '4310', 'ปากคาด   ', '38190', 3, 77),
(470, '4311', 'บึงโขงหลง   ', '38220', 3, 77),
(471, '4312', 'ศรีวิไล   ', '38210', 3, 77),
(472, '4313', 'บุ่งคล้า   ', '38000', 3, 77),
(473, '4314', 'สระใคร   ', '43100', 3, 31),
(474, '4315', 'เฝ้าไร่   ', '43120', 3, 31),
(475, '4316', 'รัตนวาปี   ', '43120', 3, 31),
(476, '4317', 'โพธิ์ตาก   ', '43130', 3, 31),
(477, '4401', 'เมืองมหาสารคาม   ', '44000', 3, 32),
(478, '4402', 'แกดำ   ', '44190', 3, 32),
(479, '4403', 'โกสุมพิสัย   ', '44140', 3, 32),
(480, '4404', 'กันทรวิชัย   ', '44150', 3, 32),
(481, '4405', 'เชียงยืน   ', '44160', 3, 32),
(482, '4406', 'บรบือ   ', '44130', 3, 32),
(483, '4407', 'นาเชือก   ', '44170', 3, 32),
(484, '4408', 'พยัคฆภูมิพิสัย   ', '44110', 3, 32),
(485, '4409', 'วาปีปทุม   ', '44120', 3, 32),
(486, '4410', 'นาดูน   ', '44180', 3, 32),
(487, '4411', 'ยางสีสุราช   ', '44210', 3, 32),
(488, '4412', 'กุดรัง   ', '44130', 3, 32),
(489, '4413', 'ชื่นชม   ', '44160', 3, 32),
(490, '4481', '*หลุบ   ', '00000', 3, 32),
(491, '4501', 'เมืองร้อยเอ็ด   ', '45000', 3, 33),
(492, '4502', 'เกษตรวิสัย   ', '45150', 3, 33),
(493, '4503', 'ปทุมรัตต์   ', '45190', 3, 33),
(494, '4504', 'จตุรพักตรพิมาน   ', '45180', 3, 33),
(495, '4505', 'ธวัชบุรี   ', '45170', 3, 33),
(496, '4506', 'พนมไพร   ', '45140', 3, 33),
(497, '4507', 'โพนทอง   ', '45110', 3, 33),
(498, '4508', 'โพธิ์ชัย   ', '45230', 3, 33),
(499, '4509', 'หนองพอก   ', '45210', 3, 33),
(500, '4510', 'เสลภูมิ   ', '45120', 3, 33),
(501, '4511', 'สุวรรณภูมิ   ', '45130', 3, 33),
(502, '4512', 'เมืองสรวง   ', '45220', 3, 33),
(503, '4513', 'โพนทราย   ', '45240', 3, 33),
(504, '4514', 'อาจสามารถ   ', '45160', 3, 33),
(505, '4515', 'เมยวดี   ', '45250', 3, 33),
(506, '4516', 'ศรีสมเด็จ   ', '45260', 3, 33),
(507, '4517', 'จังหาร   ', '45270', 3, 33),
(508, '4518', 'เชียงขวัญ   ', '45000', 3, 33),
(509, '4519', 'หนองฮี   ', '45140', 3, 33),
(510, '4520', 'ทุ่งเขาหลวง   ', '45170', 3, 33),
(511, '4601', 'เมืองกาฬสินธุ์   ', '46000', 3, 34),
(512, '4602', 'นามน   ', '46230', 3, 34),
(513, '4603', 'กมลาไสย   ', '46130', 3, 34),
(514, '4604', 'ร่องคำ   ', '46210', 3, 34),
(515, '4605', 'กุฉินารายณ์   ', '46110', 3, 34),
(516, '4606', 'เขาวง   ', '46160', 3, 34),
(517, '4607', 'ยางตลาด   ', '46120', 3, 34),
(518, '4608', 'ห้วยเม็ก   ', '46170', 3, 34),
(519, '4609', 'สหัสขันธ์   ', '46140', 3, 34),
(520, '4610', 'คำม่วง   ', '46180', 3, 34),
(521, '4611', 'ท่าคันโท   ', '46190', 3, 34),
(522, '4612', 'หนองกุงศรี   ', '46220', 3, 34),
(523, '4613', 'สมเด็จ   ', '46150', 3, 34),
(524, '4614', 'ห้วยผึ้ง   ', '46240', 3, 34),
(525, '4615', 'สามชัย   ', '46180', 3, 34),
(526, '4616', 'นาคู   ', '46160', 3, 34),
(527, '4617', 'ดอนจาน   ', '46000', 3, 34),
(528, '4618', 'ฆ้องชัย   ', '46130', 3, 34),
(529, '4701', 'เมืองสกลนคร   ', '47000', 3, 35),
(530, '4702', 'กุสุมาลย์   ', '47210', 3, 35),
(531, '4703', 'กุดบาก   ', '47180', 3, 35),
(532, '4704', 'พรรณานิคม   ', '47130', 3, 35),
(533, '4705', 'พังโคน   ', '47160', 3, 35),
(534, '4706', 'วาริชภูมิ   ', '47150', 3, 35),
(535, '4707', 'นิคมน้ำอูน   ', '47270', 3, 35),
(536, '4708', 'วานรนิวาส   ', '47120', 3, 35),
(537, '4709', 'คำตากล้า   ', '47250', 3, 35),
(538, '4710', 'บ้านม่วง   ', '47140', 3, 35),
(539, '4711', 'อากาศอำนวย   ', '47170', 3, 35),
(540, '4712', 'สว่างแดนดิน   ', '47110', 3, 35),
(541, '4713', 'ส่องดาว   ', '47190', 3, 35),
(542, '4714', 'เต่างอย   ', '47260', 3, 35),
(543, '4715', 'โคกศรีสุพรรณ   ', '47280', 3, 35),
(544, '4716', 'เจริญศิลป์   ', '47290', 3, 35),
(545, '4717', 'โพนนาแก้ว   ', '47230', 3, 35),
(546, '4718', 'ภูพาน   ', '47180', 3, 35),
(547, '4751', 'วานรนิวาส (สาขาตำบลกุดเรือคำ)*   ', '00000', 3, 35),
(548, '4781', '*อ.บ้านหัน  จ.สกลนคร   ', '00000', 3, 35),
(549, '4801', 'เมืองนครพนม   ', '48000', 3, 36),
(550, '4802', 'ปลาปาก   ', '48160', 3, 36),
(551, '4803', 'ท่าอุเทน   ', '48120', 3, 36),
(552, '4804', 'บ้านแพง   ', '48140', 3, 36),
(553, '4805', 'ธาตุพนม   ', '48110', 3, 36),
(554, '4806', 'เรณูนคร   ', '48170', 3, 36),
(555, '4807', 'นาแก   ', '48130', 3, 36),
(556, '4808', 'ศรีสงคราม   ', '48150', 3, 36),
(557, '4809', 'นาหว้า   ', '48180', 3, 36),
(558, '4810', 'โพนสวรรค์   ', '48190', 3, 36),
(559, '4811', 'นาทม   ', '48140', 3, 36),
(560, '4812', 'วังยาง   ', '48130', 3, 36),
(561, '4901', 'เมืองมุกดาหาร   ', '49000', 3, 37),
(562, '4902', 'นิคมคำสร้อย   ', '49130', 3, 37),
(563, '4903', 'ดอนตาล   ', '49120', 3, 37),
(564, '4904', 'ดงหลวง   ', '49140', 3, 37),
(565, '4905', 'คำชะอี   ', '49110', 3, 37),
(566, '4906', 'หว้านใหญ่   ', '49150', 3, 37),
(567, '4907', 'หนองสูง   ', '49160', 3, 37),
(568, '5001', 'เมืองเชียงใหม่   ', '50000', 1, 38),
(569, '5002', 'จอมทอง   ', '50160', 1, 38),
(570, '5003', 'แม่แจ่ม   ', '50270', 1, 38),
(571, '5004', 'เชียงดาว   ', '50170', 1, 38),
(572, '5005', 'ดอยสะเก็ด   ', '50220', 1, 38),
(573, '5006', 'แม่แตง   ', '50150', 1, 38),
(574, '5007', 'แม่ริม   ', '50180', 1, 38),
(575, '5008', 'สะเมิง   ', '50250', 1, 38),
(576, '5009', 'ฝาง   ', '50110', 1, 38),
(577, '5010', 'แม่อาย   ', '50280', 1, 38),
(578, '5011', 'พร้าว   ', '50190', 1, 38),
(579, '5012', 'สันป่าตอง   ', '50120', 1, 38),
(580, '5013', 'สันกำแพง   ', '50130', 1, 38),
(581, '5014', 'สันทราย   ', '50210', 1, 38),
(582, '5015', 'หางดง   ', '50230', 1, 38),
(583, '5016', 'ฮอด   ', '50240', 1, 38),
(584, '5017', 'ดอยเต่า   ', '50260', 1, 38),
(585, '5018', 'อมก๋อย   ', '50310', 1, 38),
(586, '5019', 'สารภี   ', '50140', 1, 38),
(587, '5020', 'เวียงแหง   ', '50350', 1, 38),
(588, '5021', 'ไชยปราการ   ', '50320', 1, 38),
(589, '5022', 'แม่วาง   ', '50360', 1, 38),
(590, '5023', 'แม่ออน   ', '50130', 1, 38),
(591, '5024', 'ดอยหล่อ   ', '50160', 1, 38),
(592, '5051', 'เทศบาลนครเชียงใหม่ (สาขาแขวงกาลวิละ*   ', '00000', 1, 38),
(593, '5052', 'เทศบาลนครเชียงใหม่ (สาขาแขวงศรีวิชั*   ', '00000', 1, 38),
(594, '5053', 'เทศบาลนครเชียงใหม่ (สาขาเม็งราย*   ', '00000', 1, 38),
(595, '5101', 'เมืองลำพูน   ', '51000', 1, 39),
(596, '5102', 'แม่ทา   ', '51140', 1, 39),
(597, '5103', 'บ้านโฮ่ง   ', '51130', 1, 39),
(598, '5104', 'ลี้   ', '51110', 1, 39),
(599, '5105', 'ทุ่งหัวช้าง   ', '51160', 1, 39),
(600, '5106', 'ป่าซาง   ', '51120', 1, 39),
(601, '5107', 'บ้านธิ   ', '51180', 1, 39),
(602, '5108', 'เวียงหนองล่อง   ', '51120', 1, 39),
(603, '5201', 'เมืองลำปาง   ', '52000', 1, 40),
(604, '5202', 'แม่เมาะ   ', '52220', 1, 40),
(605, '5203', 'เกาะคา   ', '52130', 1, 40),
(606, '5204', 'เสริมงาม   ', '52210', 1, 40),
(607, '5205', 'งาว   ', '52110', 1, 40),
(608, '5206', 'แจ้ห่ม   ', '52120', 1, 40),
(609, '5207', 'วังเหนือ   ', '52140', 1, 40),
(610, '5208', 'เถิน   ', '52160', 1, 40),
(611, '5209', 'แม่พริก   ', '52180', 1, 40),
(612, '5210', 'แม่ทะ   ', '52150', 1, 40),
(613, '5211', 'สบปราบ   ', '52170', 1, 40),
(614, '5212', 'ห้างฉัตร   ', '52190', 1, 40),
(615, '5213', 'เมืองปาน   ', '52240', 1, 40),
(616, '5301', 'เมืองอุตรดิตถ์   ', '53000', 1, 41),
(617, '5302', 'ตรอน   ', '53140', 1, 41),
(618, '5303', 'ท่าปลา   ', '53150', 1, 41),
(619, '5304', 'น้ำปาด   ', '53110', 1, 41),
(620, '5305', 'ฟากท่า   ', '53160', 1, 41),
(621, '5306', 'บ้านโคก   ', '53180', 1, 41),
(622, '5307', 'พิชัย   ', '53120', 1, 41),
(623, '5308', 'ลับแล   ', '53130', 1, 41),
(624, '5309', 'ทองแสนขัน   ', '53230', 1, 41),
(625, '5401', 'เมืองแพร่   ', '54000', 1, 42),
(626, '5402', 'ร้องกวาง   ', '54140', 1, 42),
(627, '5403', 'ลอง   ', '54150', 1, 42),
(628, '5404', 'สูงเม่น   ', '54130', 1, 42),
(629, '5405', 'เด่นชัย   ', '54110', 1, 42),
(630, '5406', 'สอง   ', '54120', 1, 42),
(631, '5407', 'วังชิ้น   ', '54160', 1, 42),
(632, '5408', 'หนองม่วงไข่   ', '54170', 1, 42),
(633, '5501', 'เมืองน่าน   ', '55000', 1, 43),
(634, '5502', 'แม่จริม   ', '55170', 1, 43),
(635, '5503', 'บ้านหลวง   ', '55190', 1, 43),
(636, '5504', 'นาน้อย   ', '55150', 1, 43),
(637, '5505', 'ปัว   ', '55120', 1, 43),
(638, '5506', 'ท่าวังผา   ', '55140', 1, 43),
(639, '5507', 'เวียงสา   ', '55110', 1, 43),
(640, '5508', 'ทุ่งช้าง   ', '55130', 1, 43),
(641, '5509', 'เชียงกลาง   ', '55160', 1, 43),
(642, '5510', 'นาหมื่น   ', '55180', 1, 43),
(643, '5511', 'สันติสุข   ', '55210', 1, 43),
(644, '5512', 'บ่อเกลือ   ', '55220', 1, 43),
(645, '5513', 'สองแคว   ', '55160', 1, 43),
(646, '5514', 'ภูเพียง   ', '55000', 1, 43),
(647, '5515', 'เฉลิมพระเกียรติ', '00000', 1, 43),
(648, '5601', 'เมืองพะเยา   ', '56000', 1, 44),
(649, '5602', 'จุน   ', '56150', 1, 44),
(650, '5603', 'เชียงคำ   ', '56110', 1, 44),
(651, '5604', 'เชียงม่วน   ', '56160', 1, 44),
(652, '5605', 'ดอกคำใต้   ', '56120', 1, 44),
(653, '5606', 'ปง   ', '56140', 1, 44),
(654, '5607', 'แม่ใจ   ', '56130', 1, 44),
(655, '5608', 'ภูซาง   ', '56110', 1, 44),
(656, '5609', 'ภูกามยาว   ', '56000', 1, 44),
(657, '5701', 'เมืองเชียงราย   ', '57000', 1, 45),
(658, '5702', 'เวียงชัย   ', '57210', 1, 45),
(659, '5703', 'เชียงของ   ', '57140', 1, 45),
(660, '5704', 'เทิง   ', '57160', 1, 45),
(661, '5705', 'พาน   ', '57120', 1, 45),
(662, '5706', 'ป่าแดด   ', '57190', 1, 45),
(663, '5707', 'แม่จัน   ', '57110', 1, 45),
(664, '5708', 'เชียงแสน   ', '57150', 1, 45),
(665, '5709', 'แม่สาย   ', '57130', 1, 45),
(666, '5710', 'แม่สรวย   ', '57180', 1, 45),
(667, '5711', 'เวียงป่าเป้า   ', '57170', 1, 45),
(668, '5712', 'พญาเม็งราย   ', '57290', 1, 45),
(669, '5713', 'เวียงแก่น   ', '57310', 1, 45),
(670, '5714', 'ขุนตาล   ', '57340', 1, 45),
(671, '5715', 'แม่ฟ้าหลวง   ', '57240', 1, 45),
(672, '5716', 'แม่ลาว   ', '57250', 1, 45),
(673, '5717', 'เวียงเชียงรุ้ง   ', '57210', 1, 45),
(674, '5718', 'ดอยหลวง   ', '57110', 1, 45),
(675, '5801', 'เมืองแม่ฮ่องสอน   ', '58000', 1, 46),
(676, '5802', 'ขุนยวม   ', '58140', 1, 46),
(677, '5803', 'ปาย   ', '58130', 1, 46),
(678, '5804', 'แม่สะเรียง   ', '58110', 1, 46),
(679, '5805', 'แม่ลาน้อย   ', '58120', 1, 46),
(680, '5806', 'สบเมย   ', '58110', 1, 46),
(681, '5807', 'ปางมะผ้า   ', '58150', 1, 46),
(682, '5881', '*อ.ม่วยต่อ  จ.แม่ฮ่องสอน   ', '00000', 1, 46),
(683, '6001', 'เมืองนครสวรรค์   ', '60000', 2, 47),
(684, '6002', 'โกรกพระ   ', '60170', 2, 47),
(685, '6003', 'ชุมแสง   ', '60120', 2, 47),
(686, '6004', 'หนองบัว   ', '60110', 2, 47),
(687, '6005', 'บรรพตพิสัย   ', '60180', 2, 47),
(688, '6006', 'เก้าเลี้ยว   ', '60230', 2, 47),
(689, '6007', 'ตาคลี   ', '60140', 2, 47),
(690, '6008', 'ท่าตะโก   ', '60160', 2, 47),
(691, '6009', 'ไพศาลี   ', '60220', 2, 47),
(692, '6010', 'พยุหะคีรี   ', '60130', 2, 47),
(693, '6011', 'ลาดยาว   ', '60150', 2, 47),
(694, '6012', 'ตากฟ้า   ', '60190', 2, 47),
(695, '6013', 'แม่วงก์   ', '60150', 2, 47),
(696, '6014', 'แม่เปิน   ', '60150', 2, 47),
(697, '6015', 'ชุมตาบง   ', '60150', 2, 47),
(698, '6051', 'สาขาตำบลห้วยน้ำหอม*   ', '00000', 2, 47),
(699, '6052', 'กิ่งอำเภอชุมตาบง (สาขาตำบลชุมตาบง)*   ', '00000', 2, 47),
(700, '6053', 'แม่วงก์ (สาขาตำบลแม่เล่ย์)*   ', '00000', 2, 47),
(701, '6101', 'เมืองอุทัยธานี   ', '61000', 2, 48),
(702, '6102', 'ทัพทัน   ', '61120', 2, 48),
(703, '6103', 'สว่างอารมณ์   ', '61150', 2, 48),
(704, '6104', 'หนองฉาง   ', '61110', 2, 48),
(705, '6105', 'หนองขาหย่าง   ', '61130', 2, 48),
(706, '6106', 'บ้านไร่   ', '61140', 2, 48),
(707, '6107', 'ลานสัก   ', '61160', 2, 48),
(708, '6108', 'ห้วยคต   ', '61170', 2, 48),
(709, '6201', 'เมืองกำแพงเพชร   ', '62000', 2, 49),
(710, '6202', 'ไทรงาม   ', '62150', 2, 49),
(711, '6203', 'คลองลาน   ', '62180', 2, 49),
(712, '6204', 'ขาณุวรลักษบุรี   ', '62130', 2, 49),
(713, '6205', 'คลองขลุง   ', '62120', 2, 49),
(714, '6206', 'พรานกระต่าย   ', '62110', 2, 49),
(715, '6207', 'ลานกระบือ   ', '62170', 2, 49),
(716, '6208', 'ทรายทองวัฒนา   ', '62190', 2, 49),
(717, '6209', 'ปางศิลาทอง   ', '62120', 2, 49),
(718, '6210', 'บึงสามัคคี   ', '62210', 2, 49),
(719, '6211', 'โกสัมพีนคร   ', '62000', 2, 49),
(720, '6301', 'เมืองตาก   ', '63000', 4, 50),
(721, '6302', 'บ้านตาก   ', '63120', 4, 50),
(722, '6303', 'สามเงา   ', '63130', 4, 50),
(723, '6304', 'แม่ระมาด   ', '63140', 4, 50),
(724, '6305', 'ท่าสองยาง   ', '63150', 4, 50),
(725, '6306', 'แม่สอด   ', '63110', 4, 50),
(726, '6307', 'พบพระ   ', '63160', 4, 50),
(727, '6308', 'อุ้มผาง   ', '63170', 4, 50),
(728, '6309', 'วังเจ้า   ', '63000', 4, 50),
(729, '6381', '*กิ่ง อ.ท่าปุย  จ.ตาก   ', '00000', 4, 50),
(730, '6401', 'เมืองสุโขทัย   ', '64000', 2, 51),
(731, '6402', 'บ้านด่านลานหอย   ', '64140', 2, 51),
(732, '6403', 'คีรีมาศ   ', '64160', 2, 51),
(733, '6404', 'กงไกรลาศ   ', '64170', 2, 51),
(734, '6405', 'ศรีสัชนาลัย   ', '64130', 2, 51),
(735, '6406', 'ศรีสำโรง   ', '64120', 2, 51),
(736, '6407', 'สวรรคโลก   ', '64110', 2, 51),
(737, '6408', 'ศรีนคร   ', '64180', 2, 51),
(738, '6409', 'ทุ่งเสลี่ยม   ', '64150', 2, 51),
(739, '6501', 'เมืองพิษณุโลก   ', '65000', 2, 52),
(740, '6502', 'นครไทย   ', '65120', 2, 52),
(741, '6503', 'ชาติตระการ   ', '65170', 2, 52),
(742, '6504', 'บางระกำ   ', '65140', 2, 52),
(743, '6505', 'บางกระทุ่ม   ', '65110', 2, 52),
(744, '6506', 'พรหมพิราม   ', '65150', 2, 52),
(745, '6507', 'วัดโบสถ์   ', '65160', 2, 52),
(746, '6508', 'วังทอง   ', '65130', 2, 52),
(747, '6509', 'เนินมะปราง   ', '65190', 2, 52),
(748, '6601', 'เมืองพิจิตร   ', '66000', 2, 53),
(749, '6602', 'วังทรายพูน   ', '66180', 2, 53),
(750, '6603', 'โพธิ์ประทับช้าง   ', '66190', 2, 53),
(751, '6604', 'ตะพานหิน   ', '66110', 2, 53),
(752, '6605', 'บางมูลนาก   ', '66120', 2, 53),
(753, '6606', 'โพทะเล   ', '66130', 2, 53),
(754, '6607', 'สามง่าม   ', '66140', 2, 53),
(755, '6608', 'ทับคล้อ   ', '66150', 2, 53),
(756, '6609', 'สากเหล็ก   ', '66160', 2, 53),
(757, '6610', 'บึงนาราง   ', '66130', 2, 53),
(758, '6611', 'ดงเจริญ   ', '66210', 2, 53),
(759, '6612', 'วชิรบารมี   ', '66140', 2, 53),
(760, '6701', 'เมืองเพชรบูรณ์   ', '67000', 2, 54),
(761, '6702', 'ชนแดน   ', '67150', 2, 54),
(762, '6703', 'หล่มสัก   ', '67110', 2, 54),
(763, '6704', 'หล่มเก่า   ', '67120', 2, 54),
(764, '6705', 'วิเชียรบุรี   ', '67130', 2, 54),
(765, '6706', 'ศรีเทพ   ', '67170', 2, 54),
(766, '6707', 'หนองไผ่   ', '67140', 2, 54),
(767, '6708', 'บึงสามพัน   ', '67160', 2, 54),
(768, '6709', 'น้ำหนาว   ', '67260', 2, 54),
(769, '6710', 'วังโป่ง   ', '67240', 2, 54),
(770, '6711', 'เขาค้อ   ', '67270', 2, 54),
(771, '7001', 'เมืองราชบุรี   ', '70000', 4, 55),
(772, '7002', 'จอมบึง   ', '70150', 4, 55),
(773, '7003', 'สวนผึ้ง   ', '70180', 4, 55),
(774, '7004', 'ดำเนินสะดวก   ', '70130', 4, 55),
(775, '7005', 'บ้านโป่ง   ', '70110', 4, 55),
(776, '7006', 'บางแพ   ', '70160', 4, 55),
(777, '7007', 'โพธาราม   ', '70120', 4, 55),
(778, '7008', 'ปากท่อ   ', '70140', 4, 55),
(779, '7009', 'วัดเพลง   ', '70170', 4, 55),
(780, '7010', 'บ้านคา   ', '70180', 4, 55),
(781, '7074', 'ท้องถิ่นเทศบาลตำบลบ้านฆ้อง   ', '00000', 4, 55),
(782, '7101', 'เมืองกาญจนบุรี   ', '71000', 4, 56),
(783, '7102', 'ไทรโยค   ', '71150', 4, 56),
(784, '7103', 'บ่อพลอย   ', '71160', 4, 56),
(785, '7104', 'ศรีสวัสดิ์   ', '71250', 4, 56),
(786, '7105', 'ท่ามะกา   ', '71120', 4, 56),
(787, '7106', 'ท่าม่วง   ', '71110', 4, 56),
(788, '7107', 'ทองผาภูมิ   ', '71180', 4, 56),
(789, '7108', 'สังขละบุรี   ', '71240', 4, 56),
(790, '7109', 'พนมทวน   ', '71140', 4, 56),
(791, '7110', 'เลาขวัญ   ', '71210', 4, 56),
(792, '7111', 'ด่านมะขามเตี้ย   ', '71260', 4, 56),
(793, '7112', 'หนองปรือ   ', '71220', 4, 56),
(794, '7113', 'ห้วยกระเจา   ', '71170', 4, 56),
(795, '7151', 'สาขาตำบลท่ากระดาน*   ', '00000', 4, 56),
(796, '7181', '*บ้านทวน  จ.กาญจนบุรี   ', '00000', 4, 56),
(797, '7201', 'เมืองสุพรรณบุรี   ', '72000', 2, 57),
(798, '7202', 'เดิมบางนางบวช   ', '72120', 2, 57),
(799, '7203', 'ด่านช้าง   ', '72180', 2, 57),
(800, '7204', 'บางปลาม้า   ', '72150', 2, 57),
(801, '7205', 'ศรีประจันต์   ', '72140', 2, 57),
(802, '7206', 'ดอนเจดีย์   ', '72170', 2, 57),
(803, '7207', 'สองพี่น้อง   ', '72110', 2, 57),
(804, '7208', 'สามชุก   ', '72130', 2, 57),
(805, '7209', 'อู่ทอง   ', '72160', 2, 57),
(806, '7210', 'หนองหญ้าไซ   ', '72240', 2, 57),
(807, '7301', 'เมืองนครปฐม   ', '73000', 2, 58),
(808, '7302', 'กำแพงแสน   ', '73140', 2, 58),
(809, '7303', 'นครชัยศรี   ', '73120', 2, 58),
(810, '7304', 'ดอนตูม   ', '73150', 2, 58),
(811, '7305', 'บางเลน   ', '73130', 2, 58),
(812, '7306', 'สามพราน   ', '73110', 2, 58),
(813, '7307', 'พุทธมณฑล   ', '73170', 2, 58),
(814, '7401', 'เมืองสมุทรสาคร   ', '74000', 2, 59),
(815, '7402', 'กระทุ่มแบน   ', '74110', 2, 59),
(816, '7403', 'บ้านแพ้ว   ', '74120', 2, 59),
(817, '7501', 'เมืองสมุทรสงคราม   ', '75000', 2, 60),
(818, '7502', 'บางคนที   ', '75120', 2, 60),
(819, '7503', 'อัมพวา   ', '75110', 2, 60),
(820, '7601', 'เมืองเพชรบุรี   ', '76000', 4, 61),
(821, '7602', 'เขาย้อย   ', '76140', 4, 61),
(822, '7603', 'หนองหญ้าปล้อง   ', '76160', 4, 61),
(823, '7604', 'ชะอำ   ', '76120', 4, 61),
(824, '7605', 'ท่ายาง   ', '76130', 4, 61),
(825, '7606', 'บ้านลาด   ', '76150', 4, 61),
(826, '7607', 'บ้านแหลม   ', '76110', 4, 61),
(827, '7608', 'แก่งกระจาน   ', '76170', 4, 61),
(828, '7701', 'เมืองประจวบคีรีขันธ์   ', '77000', 4, 62),
(829, '7702', 'กุยบุรี   ', '77150', 4, 62),
(830, '7703', 'ทับสะแก   ', '77130', 4, 62),
(831, '7704', 'บางสะพาน   ', '77140', 4, 62),
(832, '7705', 'บางสะพานน้อย   ', '77170', 4, 62),
(833, '7706', 'ปราณบุรี   ', '77120', 4, 62),
(834, '7707', 'หัวหิน   ', '77110', 4, 62),
(835, '7708', 'สามร้อยยอด   ', '77180', 4, 62),
(836, '8001', 'เมืองนครศรีธรรมราช   ', '80000', 6, 63),
(837, '8002', 'พรหมคีรี   ', '80320', 6, 63),
(838, '8003', 'ลานสกา   ', '80230', 6, 63),
(839, '8004', 'ฉวาง   ', '80150', 6, 63),
(840, '8005', 'พิปูน   ', '80270', 6, 63),
(841, '8006', 'เชียรใหญ่   ', '80190', 6, 63),
(842, '8007', 'ชะอวด   ', '80180', 6, 63),
(843, '8008', 'ท่าศาลา   ', '80160', 6, 63),
(844, '8009', 'ทุ่งสง   ', '80110', 6, 63),
(845, '8010', 'นาบอน   ', '80220', 6, 63),
(846, '8011', 'ทุ่งใหญ่   ', '80240', 6, 63),
(847, '8012', 'ปากพนัง   ', '80140', 6, 63),
(848, '8013', 'ร่อนพิบูลย์   ', '80130', 6, 63),
(849, '8014', 'สิชล   ', '80120', 6, 63),
(850, '8015', 'ขนอม   ', '80210', 6, 63),
(851, '8016', 'หัวไทร   ', '80170', 6, 63),
(852, '8017', 'บางขัน   ', '80360', 6, 63),
(853, '8018', 'ถ้ำพรรณรา   ', '80260', 6, 63),
(854, '8019', 'จุฬาภรณ์   ', '80130', 6, 63),
(855, '8020', 'พระพรหม   ', '80000', 6, 63),
(856, '8021', 'นบพิตำ   ', '80160', 6, 63),
(857, '8022', 'ช้างกลาง   ', '80250', 6, 63),
(858, '8023', 'เฉลิมพระเกียรติ', '00000', 6, 63),
(859, '8051', 'เชียรใหญ่ (สาขาตำบลเสือหึง)*   ', '00000', 6, 63),
(860, '8052', 'สาขาตำบลสวนหลวง**   ', '00000', 6, 63),
(861, '8053', 'ร่อนพิบูลย์ (สาขาตำบลหินตก)*   ', '00000', 6, 63),
(862, '8054', 'หัวไทร (สาขาตำบลควนชะลิก)*   ', '00000', 6, 63),
(863, '8055', 'ทุ่งสง (สาขาตำบลกะปาง)*   ', '00000', 6, 63),
(864, '8101', 'เมืองกระบี่   ', '81000', 6, 64),
(865, '8102', 'เขาพนม   ', '81140', 6, 64),
(866, '8103', 'เกาะลันตา   ', '81150', 6, 64),
(867, '8104', 'คลองท่อม   ', '81120', 6, 64),
(868, '8105', 'อ่าวลึก   ', '81110', 6, 64),
(869, '8106', 'ปลายพระยา   ', '81160', 6, 64),
(870, '8107', 'ลำทับ   ', '81120', 6, 64),
(871, '8108', 'เหนือคลอง   ', '81130', 6, 64),
(872, '8201', 'เมืองพังงา   ', '82000', 6, 65),
(873, '8202', 'เกาะยาว   ', '82160', 6, 65),
(874, '8203', 'กะปง   ', '82170', 6, 65),
(875, '8204', 'ตะกั่วทุ่ง   ', '82130', 6, 65),
(876, '8205', 'ตะกั่วป่า   ', '82110', 6, 65),
(877, '8206', 'คุระบุรี   ', '82150', 6, 65),
(878, '8207', 'ทับปุด   ', '82180', 6, 65),
(879, '8208', 'ท้ายเหมือง   ', '82120', 6, 65),
(880, '8301', 'เมืองภูเก็ต   ', '83000', 6, 66),
(881, '8302', 'กะทู้   ', '83120', 6, 66),
(882, '8303', 'ถลาง   ', '83110', 6, 66),
(883, '8381', '*ทุ่งคา   ', '00000', 6, 66),
(884, '8401', 'เมืองสุราษฎร์ธานี   ', '84000', 6, 67),
(885, '8402', 'กาญจนดิษฐ์   ', '84160', 6, 67),
(886, '8403', 'ดอนสัก   ', '84220', 6, 67),
(887, '8404', 'เกาะสมุย   ', '84140', 6, 67),
(888, '8405', 'เกาะพะงัน   ', '84280', 6, 67),
(889, '8406', 'ไชยา   ', '84110', 6, 67),
(890, '8407', 'ท่าชนะ   ', '84170', 6, 67),
(891, '8408', 'คีรีรัฐนิคม   ', '84180', 6, 67),
(892, '8409', 'บ้านตาขุน   ', '84230', 6, 67),
(893, '8410', 'พนม   ', '84250', 6, 67),
(894, '8411', 'ท่าฉาง   ', '84150', 6, 67),
(895, '8412', 'บ้านนาสาร   ', '84120', 6, 67),
(896, '8413', 'บ้านนาเดิม   ', '84240', 6, 67),
(897, '8414', 'เคียนซา   ', '84260', 6, 67),
(898, '8415', 'เวียงสระ   ', '84190', 6, 67),
(899, '8416', 'พระแสง   ', '84210', 6, 67),
(900, '8417', 'พุนพิน   ', '84130', 6, 67),
(901, '8418', 'ชัยบุรี   ', '84350', 6, 67),
(902, '8419', 'วิภาวดี   ', '84180', 6, 67),
(903, '8451', 'เกาะพงัน (สาขาตำบลเกาะเต่า)*   ', '00000', 6, 67),
(904, '8481', '*อ.บ้านดอน  จ.สุราษฎร์ธานี   ', '00000', 6, 67),
(905, '8501', 'เมืองระนอง   ', '85000', 6, 68),
(906, '8502', 'ละอุ่น   ', '85130', 6, 68),
(907, '8503', 'กะเปอร์   ', '85120', 6, 68),
(908, '8504', 'กระบุรี   ', '85110', 6, 68),
(909, '8505', 'สุขสำราญ   ', '85120', 6, 68),
(910, '8601', 'เมืองชุมพร   ', '86000', 6, 69),
(911, '8602', 'ท่าแซะ   ', '86140', 6, 69),
(912, '8603', 'ปะทิว   ', '86160', 6, 69),
(913, '8604', 'หลังสวน   ', '86110', 6, 69),
(914, '8605', 'ละแม   ', '86170', 6, 69),
(915, '8606', 'พะโต๊ะ   ', '86180', 6, 69),
(916, '8607', 'สวี   ', '86130', 6, 69),
(917, '8608', 'ทุ่งตะโก   ', '86220', 6, 69),
(918, '9001', 'เมืองสงขลา   ', '90000', 6, 70),
(919, '9002', 'สทิงพระ   ', '90190', 6, 70),
(920, '9003', 'จะนะ   ', '90130', 6, 70),
(921, '9004', 'นาทวี   ', '90160', 6, 70),
(922, '9005', 'เทพา   ', '90150', 6, 70),
(923, '9006', 'สะบ้าย้อย   ', '90210', 6, 70),
(924, '9007', 'ระโนด   ', '90140', 6, 70),
(925, '9008', 'กระแสสินธุ์   ', '90270', 6, 70),
(926, '9009', 'รัตภูมิ   ', '90180', 6, 70),
(927, '9010', 'สะเดา   ', '90120', 6, 70),
(928, '9011', 'หาดใหญ่   ', '90110', 6, 70),
(929, '9012', 'นาหม่อม   ', '90310', 6, 70),
(930, '9013', 'ควนเนียง   ', '90220', 6, 70),
(931, '9014', 'บางกล่ำ   ', '90110', 6, 70),
(932, '9015', 'สิงหนคร   ', '90280', 6, 70),
(933, '9016', 'คลองหอยโข่ง   ', '90230', 6, 70),
(934, '9077', 'ท้องถิ่นเทศบาลตำบลสำนักขาม   ', '00000', 6, 70),
(935, '9096', 'เทศบาลตำบลบ้านพรุ*   ', '00000', 6, 70),
(936, '9101', 'เมืองสตูล   ', '91000', 6, 71),
(937, '9102', 'ควนโดน   ', '91160', 6, 71),
(938, '9103', 'ควนกาหลง   ', '91130', 6, 71),
(939, '9104', 'ท่าแพ   ', '91150', 6, 71),
(940, '9105', 'ละงู   ', '91110', 6, 71),
(941, '9106', 'ทุ่งหว้า   ', '91120', 6, 71),
(942, '9107', 'มะนัง   ', '91130', 6, 71),
(943, '9201', 'เมืองตรัง   ', '92000', 6, 72),
(944, '9202', 'กันตัง   ', '92110', 6, 72),
(945, '9203', 'ย่านตาขาว   ', '92140', 6, 72),
(946, '9204', 'ปะเหลียน   ', '92120', 6, 72),
(947, '9205', 'สิเกา   ', '92150', 6, 72),
(948, '9206', 'ห้วยยอด   ', '92130', 6, 72),
(949, '9207', 'วังวิเศษ   ', '92220', 6, 72),
(950, '9208', 'นาโยง   ', '92170', 6, 72),
(951, '9209', 'รัษฎา   ', '92160', 6, 72),
(952, '9210', 'หาดสำราญ   ', '92120', 6, 72),
(953, '9251', 'อำเภอเมืองตรัง(สาขาคลองเต็ง)**   ', '00000', 6, 72),
(954, '9301', 'เมืองพัทลุง   ', '93000', 6, 73),
(955, '9302', 'กงหรา   ', '93180', 6, 73),
(956, '9303', 'เขาชัยสน   ', '93130', 6, 73),
(957, '9304', 'ตะโหมด   ', '93160', 6, 73),
(958, '9305', 'ควนขนุน   ', '93110', 6, 73),
(959, '9306', 'ปากพะยูน   ', '93120', 6, 73),
(960, '9307', 'ศรีบรรพต   ', '93190', 6, 73),
(961, '9308', 'ป่าบอน   ', '93170', 6, 73),
(962, '9309', 'บางแก้ว   ', '93140', 6, 73),
(963, '9310', 'ป่าพะยอม   ', '93110', 6, 73),
(964, '9311', 'ศรีนครินทร์   ', '93000', 6, 73),
(965, '9401', 'เมืองปัตตานี   ', '94000', 6, 74),
(966, '9402', 'โคกโพธิ์   ', '94120', 6, 74),
(967, '9403', 'หนองจิก   ', '94170', 6, 74),
(968, '9404', 'ปะนาเระ   ', '94130', 6, 74),
(969, '9405', 'มายอ   ', '94140', 6, 74),
(970, '9406', 'ทุ่งยางแดง   ', '94140', 6, 74),
(971, '9407', 'สายบุรี   ', '94110', 6, 74),
(972, '9408', 'ไม้แก่น   ', '94220', 6, 74),
(973, '9409', 'ยะหริ่ง   ', '94150', 6, 74),
(974, '9410', 'ยะรัง   ', '94160', 6, 74),
(975, '9411', 'กะพ้อ   ', '94230', 6, 74),
(976, '9412', 'แม่ลาน   ', '94180', 6, 74),
(977, '9501', 'เมืองยะลา   ', '95000', 6, 75),
(978, '9502', 'เบตง   ', '95110', 6, 75),
(979, '9503', 'บันนังสตา   ', '95130', 6, 75),
(980, '9504', 'ธารโต   ', '95150', 6, 75),
(981, '9505', 'ยะหา   ', '95120', 6, 75),
(982, '9506', 'รามัน   ', '95140', 6, 75),
(983, '9507', 'กาบัง   ', '95120', 6, 75),
(984, '9508', 'กรงปินัง   ', '95000', 6, 75),
(985, '9601', 'เมืองนราธิวาส   ', '96000', 6, 76),
(986, '9602', 'ตากใบ   ', '96110', 6, 76),
(987, '9603', 'บาเจาะ   ', '96170', 6, 76),
(988, '9604', 'ยี่งอ   ', '96180', 6, 76),
(989, '9605', 'ระแงะ   ', '96130', 6, 76),
(990, '9606', 'รือเสาะ   ', '96150', 6, 76),
(991, '9607', 'ศรีสาคร   ', '96210', 6, 76),
(992, '9608', 'แว้ง   ', '96160', 6, 76),
(993, '9609', 'สุคิริน   ', '96190', 6, 76),
(994, '9610', 'สุไหงโก-ลก   ', '96120', 6, 76),
(995, '9611', 'สุไหงปาดี   ', '96140', 6, 76),
(996, '9612', 'จะแนะ   ', '96220', 6, 76),
(997, '9613', 'เจาะไอร้อง   ', '96130', 6, 76),
(998, '9681', '*อ.บางนรา  จ.นราธิวาส   ', '00000', 6, 76);

-- --------------------------------------------------------

--
-- Table structure for table `tb_bank`
--

CREATE TABLE `tb_bank` (
  `bank_id` int(11) NOT NULL,
  `bank_code` varchar(50) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_detail` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_bank`
--

INSERT INTO `tb_bank` (`bank_id`, `bank_code`, `bank_name`, `bank_detail`) VALUES
(1, '001', 'ธนาคารแห่งประเทศไทย', ''),
(2, '002', 'ธนาคารกรุงเทพ', ''),
(3, '004', 'ธนาคารกสิกรไทย', ''),
(4, '006', 'ธนาคารกรุงไทย', ''),
(5, '011', 'ธนาคารทหารไทย', ''),
(6, '014', 'ธนาคารไทยพาณิชย์', ''),
(7, '025', 'ธนาคารกรุงศรีอยุธยา', ''),
(8, '069', 'ธนาคารเกียรตินาคิน', ''),
(9, '022', 'ธนาคารซีไอเอ็มบีไทย', ''),
(10, '067', 'ธนาคารทิสโก้', ''),
(11, '065', 'ธนาคารธนชาต', ''),
(12, '024', 'ธนาคารยูโอบี', ''),
(13, '020', 'ธนาคารสแตนดาร์ดชาร์เตอร์ด (ไทย)', ''),
(14, '071', 'ธนาคารไทยเครดิตเพื่อรายย่อย', ''),
(15, '073', 'ธนาคารแลนด์ แอนด์ เฮาส์', ''),
(16, '070', 'ธนาคารไอซีบีซี (ไทย)', ''),
(17, '098', 'ธนาคารพัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย', ''),
(18, '034', 'ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร', ''),
(19, '035', 'ธนาคารเพื่อการส่งออกและนำเข้าแห่งประเทศไทย', ''),
(20, '030', 'ธนาคารออมสิน', ''),
(21, '033', 'ธนาคารอาคารสงเคราะห์', ''),
(22, '066', 'ธนาคารอิสลามแห่งประเทศไทย', '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_bank_account`
--

CREATE TABLE `tb_bank_account` (
  `bank_account_id` int(11) NOT NULL,
  `bank_account_code` varchar(100) NOT NULL,
  `bank_account_name` varchar(200) NOT NULL,
  `bank_account_branch` varchar(200) NOT NULL,
  `bank_account_number` varchar(100) NOT NULL,
  `bank_account_title` varchar(100) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_billing_note`
--

CREATE TABLE `tb_billing_note` (
  `billing_note_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบวางบิล',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'ผู้ออกใบวางบิล',
  `billing_note_code` varchar(100) NOT NULL COMMENT 'หมายเลขใบวางบิล',
  `billing_note_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบวางบิล',
  `billing_note_name` varchar(100) NOT NULL COMMENT 'ชื่อบริษัท',
  `billing_note_address` text NOT NULL COMMENT 'ที่อยู่',
  `billing_note_tax` varchar(50) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `billing_note_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `billing_note_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `billing_note_sent_name` int(11) NOT NULL COMMENT 'ชื่อผู้วางบิล',
  `billing_note_recieve_name` int(11) NOT NULL COMMENT 'ชื่อผู้รับวางบิล',
  `billing_note_total` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `billing_note_total_text` text NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางใบวางบิล';

-- --------------------------------------------------------

--
-- Table structure for table `tb_billing_note_list`
--

CREATE TABLE `tb_billing_note_list` (
  `billing_note_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบวางบิล',
  `billing_note_id` int(11) NOT NULL COMMENT 'รหัสรายการใบวางบิล',
  `invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสใบกำกับภาษี',
  `billing_note_list_amount` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `billing_note_list_paid` double NOT NULL COMMENT 'จำนวนเงินที่จ่าย',
  `billing_note_list_balance` double NOT NULL COMMENT 'ยอดคงเหลือ',
  `billing_note_list_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_check`
--

CREATE TABLE `tb_check` (
  `check_id` int(11) NOT NULL,
  `check_code` varchar(100) NOT NULL,
  `check_date_write` varchar(50) NOT NULL COMMENT 'วันที่ออกเช็ค',
  `check_date_recieve` varchar(50) NOT NULL COMMENT 'วันที่รับเช็ค',
  `bank_account_id` int(11) NOT NULL COMMENT 'บัญชีรับเช็ค',
  `customer_id` int(11) NOT NULL COMMENT 'ผู้สั่งจ่าย',
  `check_remark` varchar(500) NOT NULL COMMENT 'หมายเหตุ',
  `check_total` double NOT NULL COMMENT 'จำนวนเงิน',
  `check_status` int(11) NOT NULL COMMENT 'สถานะเช็ค',
  `check_date_pass` varchar(50) NOT NULL COMMENT 'วันที่ผ่านเช็ค',
  `check_date_deposit` varchar(50) NOT NULL COMMENT 'วันที่นำฝาก',
  `bank_id` int(11) NOT NULL COMMENT 'เช็คธนาคาร',
  `bank_branch` varchar(100) NOT NULL COMMENT 'ธนาคารสาขา',
  `bank_deposit_id` int(11) NOT NULL,
  `check_profit_total` double NOT NULL COMMENT 'ยอดที่ตัดเป็นรายได้',
  `check_number_deposit` varchar(100) NOT NULL COMMENT 'เลขที่นำฝากเช็ค',
  `check_fee` double NOT NULL COMMENT 'ค่าทำเนียม',
  `check_type` int(11) NOT NULL COMMENT '0=เช็คได้รับจากลูกค้าล่วงหน้า 1=เช็ครับยกยอดมา',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_check_pay`
--

CREATE TABLE `tb_check_pay` (
  `check_pay_id` int(11) NOT NULL,
  `check_pay_code` varchar(100) NOT NULL,
  `check_pay_date_write` varchar(50) NOT NULL COMMENT 'วันที่ออกเช็ค',
  `check_pay_date` varchar(50) NOT NULL COMMENT 'วันที่รับเช็ค',
  `bank_account_id` int(11) NOT NULL COMMENT 'สั่งจ่ายจากบัญชี',
  `supplier_id` int(11) NOT NULL COMMENT 'ผู้สั่งจ่าย',
  `check_pay_remark` varchar(500) NOT NULL COMMENT 'หมายเหตุ',
  `check_pay_total` double NOT NULL COMMENT 'จำนวนเงิน',
  `check_pay_status` int(11) NOT NULL COMMENT 'สถานะเช็ค',
  `check_pay_date_pass` varchar(50) NOT NULL COMMENT 'วันที่ผ่านเช็ค',
  `check_pay_type` int(11) NOT NULL COMMENT '0=เช็คได้รับจากลูกค้าล่วงหน้า 1=เช็ครับยกยอดมา',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_company`
--

CREATE TABLE `tb_company` (
  `company_id` int(11) NOT NULL,
  `company_name_th` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_name_en` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_address_1` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `company_address_2` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_address_3` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_tax` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_tel` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_fax` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_branch` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_image` text COLLATE utf8_unicode_ci NOT NULL,
  `company_image_rectangle` text COLLATE utf8_unicode_ci NOT NULL,
  `company_vat_type` int(11) NOT NULL,
  `company_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company_email_smtp` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_email_port` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `company_email_user` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_email_password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastupdate` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `updateby` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_company`
--

INSERT INTO `tb_company` (`company_id`, `company_name_th`, `company_name_en`, `company_address_1`, `company_address_2`, `company_address_3`, `company_tax`, `company_tel`, `company_fax`, `company_branch`, `company_image`, `company_image_rectangle`, `company_vat_type`, `company_email`, `company_email_smtp`, `company_email_port`, `company_email_user`, `company_email_password`, `lastupdate`, `updateby`) VALUES
(1, 'บริษัท พาร์ทเนอร์ชิพส์ จำกัด', 'PARTNER CHIPS CO.,LTD.', '2/27 Bangna Complex Office Tower, 7th Floor, ', 'Soi Bangna-Trad 25, Bangna-Trad Rd., ', 'Bangna Nua, Bangna, Bangkok 10260', '0105561003185', '02-399-2784', '02-399-2327', 'สำนักงานใหญ่', '01-09-2018 12:38:47pc logo.png', '20-08-2018 03:21:00arno-rectangle.jpg', 1, 'watcharit@partnerchips.co.th', '', '', '', '', '2018-11-30 15:42:21', 4);

-- --------------------------------------------------------

--
-- Table structure for table `tb_credit_note`
--

CREATE TABLE `tb_credit_note` (
  `credit_note_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบลดหนี้',
  `credit_note_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทใบลดหนี้',
  `invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสใบขายสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'ผู้ออกใบลดหนี้',
  `credit_note_code` varchar(50) NOT NULL COMMENT 'หมายเลขใบหนี้',
  `credit_note_total_old` double NOT NULL COMMENT 'มูลค่าใบกำกับเดิม',
  `credit_note_total` double NOT NULL COMMENT 'มูลค่าที่ถูกต้อง',
  `credit_note_total_price` double NOT NULL COMMENT 'ราคารวม',
  `credit_note_vat` double NOT NULL COMMENT 'ภาษีมูลค่าเพิ่ม %',
  `credit_note_vat_price` double NOT NULL COMMENT 'ราคาภาษีมูลค่าเพิ่ม',
  `credit_note_net_price` double NOT NULL COMMENT 'ราคารวมภาษีมูลค่าเพิ่ม',
  `credit_note_net_price_text` text NOT NULL,
  `credit_note_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบลดหนี้',
  `credit_note_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `credit_note_name` varchar(100) NOT NULL COMMENT 'ชื่อลูกค้า',
  `credit_note_address` text NOT NULL COMMENT 'ที่อยู่ลูกค้า',
  `credit_note_tax` varchar(20) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `credit_note_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `credit_note_term` varchar(50) NOT NULL COMMENT 'เงือนไขการชาระเงิน',
  `credit_note_due` varchar(50) NOT NULL COMMENT 'กำหนดการชำระเงิน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางใบลดหนี้';

-- --------------------------------------------------------

--
-- Table structure for table `tb_credit_note_list`
--

CREATE TABLE `tb_credit_note_list` (
  `credit_note_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบลดหนี้',
  `credit_note_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบลดหนี้',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `credit_note_list_product_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `credit_note_list_product_detail` varchar(100) NOT NULL COMMENT 'รายละเอียดสินค้า',
  `credit_note_list_qty` int(11) NOT NULL COMMENT 'จำนวน',
  `credit_note_list_price` double NOT NULL COMMENT 'จำนวนเงิน',
  `credit_note_list_total` double NOT NULL COMMENT 'ราคารวม',
  `credit_note_list_remark` text NOT NULL COMMENT 'สาเหตุ',
  `invoice_customer_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบกำกับภาษีลูกค้า',
  `stock_group_id` int(11) NOT NULL COMMENT 'ลงคลังสินค้า',
  `credit_note_list_cost` double NOT NULL COMMENT 'ต้นทุนที่แท้จริง',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางรายการใบลดหนี้';

-- --------------------------------------------------------

--
-- Table structure for table `tb_credit_note_type`
--

CREATE TABLE `tb_credit_note_type` (
  `credit_note_type_id` int(11) NOT NULL,
  `credit_note_type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_credit_purchasing`
--

CREATE TABLE `tb_credit_purchasing` (
  `credit_purchasing_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `credit_purchasing_code` varchar(50) NOT NULL,
  `credit_purchasing_date` varchar(50) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `credit_purchasing_credit_day` int(11) NOT NULL,
  `credit_purchasing_credit_date` varchar(50) NOT NULL,
  `credit_purchasing_delivery_by` varchar(50) NOT NULL,
  `credit_purchasing_total` double NOT NULL,
  `credit_purchasing_discount` double NOT NULL,
  `credit_purchasing_discount_type` int(11) NOT NULL,
  `credit_purchasing_vat` float NOT NULL,
  `credit_purchasing_vat_type` int(11) NOT NULL,
  `credit_purchasing_vat_value` double NOT NULL,
  `credit_purchasing_net` double NOT NULL,
  `credit_purchasing_remark` varchar(500) NOT NULL,
  `addby` int(11) NOT NULL,
  `adddate` varchar(50) NOT NULL,
  `updateby` int(11) NOT NULL,
  `lastupdate` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_credit_purchasing_list`
--

CREATE TABLE `tb_credit_purchasing_list` (
  `credit_purchasing_list_id` int(11) NOT NULL,
  `credit_purchasing_id` int(11) NOT NULL,
  `credit_purchasing_list_code` varchar(50) NOT NULL,
  `credit_purchasing_list_name` varchar(200) NOT NULL,
  `stock_group_id` int(11) NOT NULL,
  `credit_purchasing_list_qty` int(11) NOT NULL,
  `credit_purchasing_list_unit` varchar(50) NOT NULL,
  `credit_purchasing_list_price` double NOT NULL,
  `credit_purchasing_list_discount` double NOT NULL,
  `credit_purchasing_list_discount_type` int(11) NOT NULL,
  `credit_purchasing_list_total` decimal(10,0) NOT NULL,
  `addby` int(11) NOT NULL,
  `adddate` varchar(50) NOT NULL,
  `updateby` int(11) NOT NULL,
  `lastupdate` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_currency`
--

CREATE TABLE `tb_currency` (
  `currency_id` int(11) NOT NULL,
  `currency_country` varchar(50) NOT NULL,
  `currency_name` varchar(50) NOT NULL,
  `currency_code` varchar(100) NOT NULL,
  `currency_sign` varchar(100) NOT NULL,
  `currency_thousand` varchar(10) NOT NULL,
  `currency_decimal` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางสกุลเงิน';

--
-- Dumping data for table `tb_currency`
--

INSERT INTO `tb_currency` (`currency_id`, `currency_country`, `currency_name`, `currency_code`, `currency_sign`, `currency_thousand`, `currency_decimal`) VALUES
(1, 'Albania', 'Leke', 'ALL', 'Lek', ',', '.'),
(2, 'America', 'Dollars', 'USD', '$', ',', '.'),
(3, 'Afghanistan', 'Afghanis', 'AF', '؋', ',', '.'),
(4, 'Argentina', 'Pesos', 'ARS', '$', ',', '.'),
(5, 'Aruba', 'Guilders', 'AWG', 'ƒ', ',', '.'),
(6, 'Australia', 'Dollars', 'AUD', '$', ',', '.'),
(7, 'Azerbaijan', 'New Manats', 'AZ', 'ман', ',', '.'),
(8, 'Bahamas', 'Dollars', 'BSD', '$', ',', '.'),
(9, 'Barbados', 'Dollars', 'BBD', '$', ',', '.'),
(10, 'Belarus', 'Rubles', 'BYR', 'p.', ',', '.'),
(11, 'Belgium', 'Euro', 'EUR', '€', ',', '.'),
(12, 'Beliz', 'Dollars', 'BZD', 'BZ$', ',', '.'),
(13, 'Bermuda', 'Dollars', 'BMD', '$', ',', '.'),
(14, 'Bolivia', 'Bolivianos', 'BOB', '$b', ',', '.'),
(15, 'Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM', ',', '.'),
(16, 'Botswana', 'Pula\'s', 'BWP', 'P', ',', '.'),
(17, 'Bulgaria', 'Leva', 'BG', 'лв', ',', '.'),
(18, 'Brazil', 'Reais', 'BRL', 'R$', ',', '.'),
(19, 'Britain (United Kingdom)', 'Pounds', 'GBP', '£', ',', '.'),
(20, 'Brunei Darussalam', 'Dollars', 'BND', '$', ',', '.'),
(21, 'Cambodia', 'Riels', 'KHR', '៛', ',', '.'),
(22, 'Canada', 'Dollars', 'CAD', '$', ',', '.'),
(23, 'Cayman Islands', 'Dollars', 'KYD', '$', ',', '.'),
(24, 'Chile', 'Pesos', 'CLP', '$', ',', '.'),
(25, 'China', 'Yuan Renminbi', 'CNY', '¥', ',', '.'),
(26, 'Colombia', 'Pesos', 'COP', '$', ',', '.'),
(27, 'Costa Rica', 'Colón', 'CRC', '₡', ',', '.'),
(28, 'Croatia', 'Kuna', 'HRK', 'kn', ',', '.'),
(29, 'Cuba', 'Pesos', 'CUP', '₱', ',', '.'),
(30, 'Cyprus', 'Euro', 'EUR', '€', ',', '.'),
(31, 'Czech Republic', 'Koruny', 'CZK', 'Kč', ',', '.'),
(32, 'Denmark', 'Kroner', 'DKK', 'kr', ',', '.'),
(33, 'Dominican Republic', 'Pesos', 'DOP ', 'RD$', ',', '.'),
(34, 'East Caribbean', 'Dollars', 'XCD', '$', ',', '.'),
(35, 'Egypt', 'Pounds', 'EGP', '£', ',', '.'),
(36, 'El Salvador', 'Colones', 'SVC', '$', ',', '.'),
(37, 'England (United Kingdom)', 'Pounds', 'GBP', '£', ',', '.'),
(38, 'Euro', 'Euro', 'EUR', '€', ',', '.'),
(39, 'Falkland Islands', 'Pounds', 'FKP', '£', ',', '.'),
(40, 'Fiji', 'Dollars', 'FJD', '$', ',', '.'),
(41, 'France', 'Euro', 'EUR', '€', ',', '.'),
(42, 'Ghana', 'Cedis', 'GHC', '¢', ',', '.'),
(43, 'Gibraltar', 'Pounds', 'GIP', '£', ',', '.'),
(44, 'Greece', 'Euro', 'EUR', '€', ',', '.'),
(45, 'Guatemala', 'Quetzales', 'GTQ', 'Q', ',', '.'),
(46, 'Guernsey', 'Pounds', 'GGP', '£', ',', '.'),
(47, 'Guyana', 'Dollars', 'GYD', '$', ',', '.'),
(48, 'Holland (Netherlands)', 'Euro', 'EUR', '€', ',', '.'),
(49, 'Honduras', 'Lempiras', 'HNL', 'L', ',', '.'),
(50, 'Hong Kong', 'Dollars', 'HKD', '$', ',', '.'),
(51, 'Hungary', 'Forint', 'HUF', 'Ft', ',', '.'),
(52, 'Iceland', 'Kronur', 'ISK', 'kr', ',', '.'),
(53, 'India', 'Rupees', 'INR', 'Rp', ',', '.'),
(54, 'Indonesia', 'Rupiahs', 'IDR', 'Rp', ',', '.'),
(55, 'Iran', 'Rials', 'IRR', '﷼', ',', '.'),
(56, 'Ireland', 'Euro', 'EUR', '€', ',', '.'),
(57, 'Isle of Man', 'Pounds', 'IMP', '£', ',', '.'),
(58, 'Israel', 'New Shekels', 'ILS', '₪', ',', '.'),
(59, 'Italy', 'Euro', 'EUR', '€', ',', '.'),
(60, 'Jamaica', 'Dollars', 'JMD', 'J$', ',', '.'),
(61, 'Japan', 'Yen', 'JPY', '¥', ',', '.'),
(62, 'Jersey', 'Pounds', 'JEP', '£', ',', '.'),
(63, 'Kazakhstan', 'Tenge', 'KZT', 'лв', ',', '.'),
(64, 'Korea (North)', 'Won', 'KPW', '₩', ',', '.'),
(65, 'Korea (South)', 'Won', 'KRW', '₩', ',', '.'),
(66, 'Kyrgyzstan', 'Soms', 'KGS', 'лв', ',', '.'),
(67, 'Laos', 'Kips', 'LAK', '₭', ',', '.'),
(68, 'Latvia', 'Lati', 'LVL', 'Ls', ',', '.'),
(69, 'Lebanon', 'Pounds', 'LBP', '£', ',', '.'),
(70, 'Liberia', 'Dollars', 'LRD', '$', ',', '.'),
(71, 'Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF', ',', '.'),
(72, 'Lithuania', 'Litai', 'LTL', 'Lt', ',', '.'),
(73, 'Luxembourg', 'Euro', 'EUR', '€', ',', '.'),
(74, 'Macedonia', 'Denars', 'MKD', 'ден', ',', '.'),
(75, 'Malaysia', 'Ringgits', 'MYR', 'RM', ',', '.'),
(76, 'Malta', 'Euro', 'EUR', '€', ',', '.'),
(77, 'Mauritius', 'Rupees', 'MUR', '₨', ',', '.'),
(78, 'Mexico', 'Pesos', 'MX', '$', ',', '.'),
(79, 'Mongolia', 'Tugriks', 'MNT', '₮', ',', '.'),
(80, 'Mozambique', 'Meticais', 'MZ', 'MT', ',', '.'),
(81, 'Namibia', 'Dollars', 'NAD', '$', ',', '.'),
(82, 'Nepal', 'Rupees', 'NPR', '₨', ',', '.'),
(83, 'Netherlands Antilles', 'Guilders', 'ANG', 'ƒ', ',', '.'),
(84, 'Netherlands', 'Euro', 'EUR', '€', ',', '.'),
(85, 'New Zealand', 'Dollars', 'NZD', '$', ',', '.'),
(86, 'Nicaragua', 'Cordobas', 'NIO', 'C$', ',', '.'),
(87, 'Nigeria', 'Nairas', 'NG', '₦', ',', '.'),
(88, 'North Korea', 'Won', 'KPW', '₩', ',', '.'),
(89, 'Norway', 'Krone', 'NOK', 'kr', ',', '.'),
(90, 'Oman', 'Rials', 'OMR', '﷼', ',', '.'),
(91, 'Pakistan', 'Rupees', 'PKR', '₨', ',', '.'),
(92, 'Panama', 'Balboa', 'PAB', 'B/.', ',', '.'),
(93, 'Paraguay', 'Guarani', 'PYG', 'Gs', ',', '.'),
(94, 'Peru', 'Nuevos Soles', 'PE', 'S/.', ',', '.'),
(95, 'Philippines', 'Pesos', 'PHP', 'Php', ',', '.'),
(96, 'Poland', 'Zlotych', 'PL', 'zł', ',', '.'),
(97, 'Qatar', 'Rials', 'QAR', '﷼', ',', '.'),
(98, 'Romania', 'New Lei', 'RO', 'lei', ',', '.'),
(99, 'Russia', 'Rubles', 'RUB', 'руб', ',', '.'),
(100, 'Saint Helena', 'Pounds', 'SHP', '£', ',', '.'),
(101, 'Saudi Arabia', 'Riyals', 'SAR', '﷼', ',', '.'),
(102, 'Serbia', 'Dinars', 'RSD', 'Дин.', ',', '.'),
(103, 'Seychelles', 'Rupees', 'SCR', '₨', ',', '.'),
(104, 'Singapore', 'Dollars', 'SGD', '$', ',', '.'),
(105, 'Slovenia', 'Euro', 'EUR', '€', ',', '.'),
(106, 'Solomon Islands', 'Dollars', 'SBD', '$', ',', '.'),
(107, 'Somalia', 'Shillings', 'SOS', 'S', ',', '.'),
(108, 'South Africa', 'Rand', 'ZAR', 'R', ',', '.'),
(109, 'South Korea', 'Won', 'KRW', '₩', ',', '.'),
(110, 'Spain', 'Euro', 'EUR', '€', ',', '.'),
(111, 'Sri Lanka', 'Rupees', 'LKR', '₨', ',', '.'),
(112, 'Sweden', 'Kronor', 'SEK', 'kr', ',', '.'),
(113, 'Switzerland', 'Francs', 'CHF', 'CHF', ',', '.'),
(114, 'Suriname', 'Dollars', 'SRD', '$', ',', '.'),
(115, 'Syria', 'Pounds', 'SYP', '£', ',', '.'),
(116, 'Taiwan', 'New Dollars', 'TWD', 'NT$', ',', '.'),
(117, 'Thailand', 'Baht', 'THB', '฿', ',', '.'),
(118, 'Trinidad and Tobago', 'Dollars', 'TTD', 'TT$', ',', '.'),
(119, 'Turkey', 'Lira', 'TRY', 'TL', ',', '.'),
(120, 'Turkey', 'Liras', 'TRL', '£', ',', '.'),
(121, 'Tuvalu', 'Dollars', 'TVD', '$', ',', '.'),
(122, 'Ukraine', 'Hryvnia', 'UAH', '₴', ',', '.'),
(123, 'United Kingdom', 'Pounds', 'GBP', '£', ',', '.'),
(124, 'United States of America', 'Dollars', 'USD', '$', ',', '.'),
(125, 'Uruguay', 'Pesos', 'UYU', '$U', ',', '.'),
(126, 'Uzbekistan', 'Sums', 'UZS', 'лв', ',', '.'),
(127, 'Vatican City', 'Euro', 'EUR', '€', ',', '.'),
(128, 'Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs', ',', '.'),
(129, 'Vietnam', 'Dong', 'VND', '₫', ',', '.'),
(130, 'Yemen', 'Rials', 'YER', '﷼', ',', '.'),
(131, 'Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$', ',', '.');

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer`
--

CREATE TABLE `tb_customer` (
  `customer_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `customer_code` varchar(20) NOT NULL COMMENT 'รหัสลูกค้า (ใช้แสดง)',
  `customer_name_th` varchar(200) NOT NULL COMMENT 'ชื่อลูกค้าไทย',
  `customer_name_en` varchar(200) NOT NULL COMMENT 'ชื่อลูกค้าภาษาอังกฤษ',
  `customer_type` varchar(100) NOT NULL COMMENT 'ประเภทบริษัท',
  `customer_tax` varchar(20) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `customer_address_1` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย 1',
  `customer_address_2` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย 2',
  `customer_address_3` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย 3',
  `customer_zipcode` varchar(10) NOT NULL COMMENT 'เลขไปรษณีย์',
  `customer_tel` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `customer_fax` varchar(50) NOT NULL COMMENT 'เบอร์แฟค',
  `customer_email` varchar(200) NOT NULL COMMENT 'อีเมล',
  `customer_domestic` varchar(20) NOT NULL COMMENT 'บริษัทของประเทศ',
  `customer_remark` text NOT NULL COMMENT 'รายละเอียด',
  `customer_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `customer_zone` varchar(50) NOT NULL COMMENT 'เขตการขาย',
  `credit_day` varchar(11) NOT NULL COMMENT 'เครดิตการจ่าย',
  `condition_pay` varchar(100) NOT NULL COMMENT 'เงื่อนไขการชำระเงิน',
  `pay_limit` float NOT NULL COMMENT 'วงเงินอนุมัติ',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `vat_type` int(11) NOT NULL COMMENT 'ประเภทภาษีมูลค่าเพิ่ม',
  `vat` float NOT NULL COMMENT 'ภาษีมูลค่าเพิ่ม',
  `currency_id` int(11) NOT NULL COMMENT 'สกุลเงิน',
  `customer_logo` varchar(200) NOT NULL COMMENT 'รูปลูกค้า',
  `bill_shift` int(11) NOT NULL,
  `invoice_shift` int(11) NOT NULL,
  `date_bill` int(11) NOT NULL DEFAULT '30',
  `date_invoice` int(11) NOT NULL DEFAULT '25',
  `customer_end_user` int(11) NOT NULL COMMENT 'เป็น end user ของใคร',
  `sale_id` int(11) NOT NULL,
  `customer_type_id` int(11) NOT NULL COMMENT 'ประเภทลูกค้า 0 = Low , 1 = medium, 2 = High, 3 = End user, 4 = Special',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_account`
--

CREATE TABLE `tb_customer_account` (
  `customer_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชีลูกค้า',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `customer_account_no` varchar(50) NOT NULL COMMENT 'เลขที่บัญชี',
  `customer_account_name` varchar(100) NOT NULL COMMENT 'ชื่อบัญชี',
  `customer_account_bank` varchar(100) NOT NULL COMMENT 'ธนาคาร',
  `customer_account_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `customer_account_detail` text NOT NULL COMMENT 'รายละเอียดบัญชีเพิ่มเติ่ม',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางบัญชีธนาคารของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_contact`
--

CREATE TABLE `tb_customer_contact` (
  `customer_contact_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ติดต่อ',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `customer_contact_name` varchar(100) NOT NULL COMMENT 'ชื่อผู้ติดต่อ',
  `customer_contact_position` varchar(100) NOT NULL COMMENT 'ตำแหน่ง',
  `customer_contact_tel` varchar(100) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `customer_contact_email` varchar(100) NOT NULL COMMENT 'อีเมล',
  `customer_contact_detail` text NOT NULL COMMENT 'รายละเอียดเพิ่มเติ่ม',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางผู้ติดต่อของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_holiday`
--

CREATE TABLE `tb_customer_holiday` (
  `customer_holiday_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `holiday_id` int(11) NOT NULL,
  `customer_holiday_name` varchar(100) NOT NULL,
  `customer_holiday_date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางวันหยุดของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_logistic`
--

CREATE TABLE `tb_customer_logistic` (
  `customer_logistic_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลักษณะการจัดส่ง',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `customer_logistic_name` varchar(100) NOT NULL COMMENT 'ชื่อการจัดส่ง',
  `customer_logistic_detail` text NOT NULL COMMENT 'รายละอียดการจัดส่ง',
  `customer_logistic_lead_time` varchar(50) NOT NULL COMMENT 'ระยะเวลาในการจัดส่ง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางการจัดส่งของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_purchase_order`
--

CREATE TABLE `tb_customer_purchase_order` (
  `customer_purchase_order_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิง PO ของลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `customer_purchase_order_code_gen` varchar(200) NOT NULL COMMENT 'หมายเลขรับเข้าใบสั่งซื้อ',
  `customer_purchase_order_code` varchar(50) NOT NULL COMMENT 'เลขที่ใบสั่งซื้อของลูกค้า',
  `customer_purchase_order_credit_term` int(11) NOT NULL COMMENT 'ชำระเงินของลูกค้าภายในกี่วัน',
  `customer_purchase_order_delivery_term` varchar(100) NOT NULL COMMENT 'ประเภทวันส่งสินค้า',
  `customer_purchase_order_delivery_by` varchar(100) NOT NULL COMMENT 'ส่งสินค้าโดย',
  `customer_purchase_order_date` varchar(50) NOT NULL COMMENT 'วันที่สั่งซื้อสินค้าของลูกค้า',
  `customer_purchase_order_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุใบสั่งซื้อของลูกค้า',
  `customer_purchase_order_status` varchar(50) NOT NULL COMMENT 'สถานะใบสั่งซื้อของลูกค้า',
  `customer_purchase_order_file` varchar(100) NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มใบสั่งซื้อสินค้าของลูกค้า',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มใบสั่งซื้อสินค้าของลูกค้า',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขใบสั่งซื้อสินค้าของลูกค้า',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขใบสั่งซื้อสินค้าของลูกค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางใบสั่งซื้อของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_purchase_order_list`
--

CREATE TABLE `tb_customer_purchase_order_list` (
  `customer_purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้าของลูกค้า',
  `customer_purchase_order_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้าของลูกค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `end_user_id` int(11) NOT NULL COMMENT 'เอาไปขายให้ใคร',
  `customer_purchase_order_product_name` varchar(50) NOT NULL COMMENT 'ชื่อสินค้าของลูกค้า',
  `customer_purchase_order_product_detail` varchar(50) NOT NULL COMMENT 'รายละเอียดสินค้าของลูกค้า',
  `customer_purchase_order_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้าของลูกค้า',
  `customer_purchase_order_list_price` double NOT NULL COMMENT 'ราคาสินค้าต่อชิ้นของลูกค้า',
  `customer_purchase_order_list_price_sum` double NOT NULL COMMENT 'ราคาสินค้ารวมของลูกค้า',
  `customer_purchase_order_list_delivery_min` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุดให้ลูกค้า',
  `customer_purchase_order_list_delivery_max` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าช้าที่สุดให้ลูกค้า',
  `customer_purchase_order_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `delivery_note_customer_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบยืมผู้ซื้อสิ้นค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มรายการสั่งซื้อสินค้าของลูกค้า',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มรายการสั่งซื้อสินค้าของลูกค้า',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขรายการสั่งซื้อสินค้าของลูกค้า',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขรายการสั่งซื้อสินค้าของลูกค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางรายการใบสั่งซื้อของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_purchase_order_list_detail`
--

CREATE TABLE `tb_customer_purchase_order_list_detail` (
  `customer_purchase_order_list_detail_id` int(11) NOT NULL,
  `customer_purchase_order_list_id` int(11) DEFAULT '0',
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `stock_hold_id` int(11) NOT NULL DEFAULT '0',
  `stock_group_id` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) NOT NULL DEFAULT '0',
  `purchase_order_list_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางรายละเอียดของรายการใบสั่งซื้อของลูกค้า';

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer_type`
--

CREATE TABLE `tb_customer_type` (
  `customer_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทลูกค้า',
  `customer_type_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อประเภทลูกค้า'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_customer_type`
--

INSERT INTO `tb_customer_type` (`customer_type_id`, `customer_type_name`) VALUES
(0, 'Low'),
(1, 'Medium'),
(2, 'Big'),
(3, 'KA'),
(4, 'Agent/Trade'),
(5, 'Dealer'),
(6, 'Premium');

-- --------------------------------------------------------

--
-- Table structure for table `tb_debit_note`
--

CREATE TABLE `tb_debit_note` (
  `debit_note_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบเพิ่มหนี้',
  `invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสใบขายสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'ผู้ออกใบเพิ่มหนี้',
  `debit_note_code` varchar(50) NOT NULL COMMENT 'หมายเลขใบหนี้',
  `debit_note_total_old` double NOT NULL COMMENT 'มูลค่าใบกำกับเดิม',
  `debit_note_total` double NOT NULL COMMENT 'มูลค่าที่ถูกต้อง',
  `debit_note_total_price` double NOT NULL COMMENT 'ราคารวม',
  `debit_note_vat` double NOT NULL COMMENT 'ภาษีมูลค่าเพิ่ม %',
  `debit_note_vat_price` double NOT NULL COMMENT 'ราคาภาษีมูลค่าเพิ่ม',
  `debit_note_net_price` double NOT NULL COMMENT 'ราคารวมภาษีมูลค่าเพิ่ม',
  `debit_note_net_price_text` text NOT NULL,
  `debit_note_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบเพิ่มหนี้',
  `debit_note_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `debit_note_name` varchar(100) NOT NULL COMMENT 'ชื่อลูกค้า',
  `debit_note_address` text NOT NULL COMMENT 'ที่อยู่ลูกค้า',
  `debit_note_tax` varchar(20) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `debit_note_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `debit_note_term` varchar(50) NOT NULL COMMENT 'เงือนไขการชาระเงิน',
  `debit_note_due` varchar(50) NOT NULL COMMENT 'กำหนดการชำระเงิน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_debit_note_list`
--

CREATE TABLE `tb_debit_note_list` (
  `debit_note_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบเพิ่มหนี้',
  `debit_note_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบเพิ่มหนี้',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `debit_note_list_product_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `debit_note_list_product_detail` varchar(100) NOT NULL COMMENT 'รายละเอียดสินค้า',
  `debit_note_list_qty` int(11) NOT NULL COMMENT 'จำนวน',
  `debit_note_list_price` double NOT NULL COMMENT 'จำนวนเงิน',
  `debit_note_list_total` double NOT NULL COMMENT 'ราคารวม',
  `debit_note_list_remark` text NOT NULL COMMENT 'สาเหตุ',
  `invoice_customer_list_id` int(11) NOT NULL,
  `stock_group_id` int(11) NOT NULL COMMENT 'ลงคลังสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_delivery_note_customer`
--

CREATE TABLE `tb_delivery_note_customer` (
  `delivery_note_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบยืมผู้ซื้อสิ้นค้า',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสผู้ซื้อ',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานที่ยืม',
  `employee_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้ยืม',
  `contact_name` varchar(200) NOT NULL COMMENT 'ชื่อผู้ให้ยืม',
  `contact_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้ให้ยืม',
  `delivery_note_customer_code` varchar(20) NOT NULL COMMENT 'หมายเลขใบยืม',
  `delivery_note_customer_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบยืม',
  `delivery_note_customer_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `delivery_note_customer_file` varchar(200) NOT NULL COMMENT 'ไฟล์ที่เกี่ยวข้อง',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_delivery_note_customer_list`
--

CREATE TABLE `tb_delivery_note_customer_list` (
  `delivery_note_customer_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบยืมผู้ซื้อสิ้นค้า',
  `delivery_note_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบยืมผู้ซื้อสิ้นค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `delivery_note_customer_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `delivery_note_customer_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้า',
  `stock_group_id` int(11) NOT NULL COMMENT 'ดึงสินค้าจากคลังไหน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_delivery_note_supplier`
--

CREATE TABLE `tb_delivery_note_supplier` (
  `delivery_note_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบยืมผู้ซื้อสิ้นค้า',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานที่ยืม',
  `employee_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้ยืม',
  `contact_name` varchar(200) NOT NULL COMMENT 'ชื่อผู้ให้ยืม',
  `contact_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้ให้ยืม',
  `delivery_note_supplier_code` varchar(20) NOT NULL COMMENT 'หมายเลขใบยืม',
  `delivery_note_supplier_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบยืม',
  `delivery_note_supplier_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `delivery_note_supplier_file` varchar(200) NOT NULL COMMENT 'ไฟล์ที่เกี่ยวข้อง',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_delivery_note_supplier_list`
--

CREATE TABLE `tb_delivery_note_supplier_list` (
  `delivery_note_supplier_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบยืมผู้ขายสิ้นค้า',
  `delivery_note_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบยืมผู้ขายสิ้นค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `delivery_note_supplier_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `delivery_note_supplier_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้า',
  `request_test_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบร้องขอสินค้าทดลอง',
  `stock_group_id` int(11) NOT NULL COMMENT 'ดึงสินค้าจากคลังไหน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_district`
--

CREATE TABLE `tb_district` (
  `DISTRICT_ID` int(5) NOT NULL,
  `DISTRICT_CODE` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `DISTRICT_NAME` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `AMPHUR_ID` int(5) NOT NULL DEFAULT '0',
  `PROVINCE_ID` int(5) NOT NULL DEFAULT '0',
  `GEO_ID` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_district`
--

INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(1, '100101', 'พระบรมมหาราชวัง', 1, 1, 2),
(2, '100102', 'วังบูรพาภิรมย์   ', 1, 1, 2),
(3, '100103', 'วัดราชบพิธ   ', 1, 1, 2),
(4, '100104', 'สำราญราษฎร์   ', 1, 1, 2),
(5, '100105', 'ศาลเจ้าพ่อเสือ   ', 1, 1, 2),
(6, '100106', 'เสาชิงช้า   ', 1, 1, 2),
(7, '100107', 'บวรนิเวศ   ', 1, 1, 2),
(8, '100108', 'ตลาดยอด   ', 1, 1, 2),
(9, '100109', 'ชนะสงคราม   ', 1, 1, 2),
(10, '100110', 'บ้านพานถม   ', 1, 1, 2),
(11, '100111', 'บางขุนพรหม   ', 1, 1, 2),
(12, '100112', 'วัดสามพระยา   ', 1, 1, 2),
(13, '100201', 'ดุสิต   ', 2, 1, 2),
(14, '100202', 'วชิรพยาบาล   ', 2, 1, 2),
(15, '100203', 'สวนจิตรลดา   ', 2, 1, 2),
(16, '100204', 'สี่แยกมหานาค   ', 2, 1, 2),
(17, '100205', '*บางซื่อ   ', 2, 1, 2),
(18, '100206', 'ถนนนครไชยศรี   ', 2, 1, 2),
(19, '100299', '*สามเสนใน   ', 2, 1, 2),
(20, '100301', 'กระทุ่มราย   ', 3, 1, 2),
(21, '100302', 'หนองจอก   ', 3, 1, 2),
(22, '100303', 'คลองสิบ   ', 3, 1, 2),
(23, '100304', 'คลองสิบสอง   ', 3, 1, 2),
(24, '100305', 'โคกแฝด   ', 3, 1, 2),
(25, '100306', 'คู้ฝั่งเหนือ   ', 3, 1, 2),
(26, '100307', 'ลำผักชี   ', 3, 1, 2),
(27, '100308', 'ลำต้อยติ่ง   ', 3, 1, 2),
(28, '100401', 'มหาพฤฒาราม   ', 4, 1, 2),
(29, '100402', 'สีลม   ', 4, 1, 2),
(30, '100403', 'สุริยวงศ์   ', 4, 1, 2),
(31, '100404', 'บางรัก   ', 4, 1, 2),
(32, '100405', 'สี่พระยา   ', 4, 1, 2),
(33, '100501', '*ลาดยาว   ', 5, 1, 2),
(34, '100502', 'อนุสาวรีย์   ', 5, 1, 2),
(35, '100503', 'คลองถนน*   ', 5, 1, 2),
(36, '100504', '*ตลาดบางเขน   ', 5, 1, 2),
(37, '100505', '*สีกัน   ', 5, 1, 2),
(38, '100506', 'สายไหม*   ', 5, 1, 2),
(39, '100507', '*ทุ่งสองห้อง   ', 5, 1, 2),
(40, '100508', 'ท่าแร้ง   ', 5, 1, 2),
(41, '100509', 'ออเงิน*   ', 5, 1, 2),
(42, '100599', '*บางเขน   ', 5, 1, 2),
(43, '100601', 'คลองจั่น   ', 6, 1, 2),
(44, '100602', 'วังทองหลาง*   ', 6, 1, 2),
(45, '100603', '*ลาดพร้าว   ', 6, 1, 2),
(46, '100604', '*คลองกุ่ม   ', 6, 1, 2),
(47, '100605', '*สะพานสูง   ', 6, 1, 2),
(48, '100606', '*คันนายาว   ', 6, 1, 2),
(49, '100607', '*จรเข้บัว   ', 6, 1, 2),
(50, '100608', 'หัวหมาก   ', 6, 1, 2),
(51, '100701', 'รองเมือง   ', 7, 1, 2),
(52, '100702', 'วังใหม่   ', 7, 1, 2),
(53, '100703', 'ปทุมวัน   ', 7, 1, 2),
(54, '100704', 'ลุมพินี   ', 7, 1, 2),
(55, '100801', 'ป้อมปราบ   ', 8, 1, 2),
(56, '100802', 'วัดเทพศิรินทร์   ', 8, 1, 2),
(57, '100803', 'คลองมหานาค   ', 8, 1, 2),
(58, '100804', 'บ้านบาตร   ', 8, 1, 2),
(59, '100805', 'วัดโสมนัส   ', 8, 1, 2),
(60, '100899', '*นางเลิ้ง   ', 8, 1, 2),
(61, '100901', '*คลองเตย   ', 9, 1, 2),
(62, '100902', '*คลองตัน   ', 9, 1, 2),
(63, '100903', '*พระโขนง   ', 9, 1, 2),
(64, '100904', 'บางนา*   ', 9, 1, 2),
(65, '100905', 'บางจาก   ', 9, 1, 2),
(66, '100906', '*สวนหลวง   ', 9, 1, 2),
(67, '100907', '*หนองบอน   ', 9, 1, 2),
(68, '100908', '*ประเวศ   ', 9, 1, 2),
(69, '100909', '*ดอกไม้   ', 9, 1, 2),
(70, '100997', '*พระโขนง   ', 9, 1, 2),
(71, '100998', '*คลองตัน   ', 9, 1, 2),
(72, '100999', '*คลองเตย   ', 9, 1, 2),
(73, '101001', 'มีนบุรี   ', 10, 1, 2),
(74, '101002', 'แสนแสบ   ', 10, 1, 2),
(75, '101003', 'บางชัน*   ', 10, 1, 2),
(76, '101004', 'ทรายกองดิน*   ', 10, 1, 2),
(77, '101005', 'ทรายกองดินใต้*   ', 10, 1, 2),
(78, '101006', 'สามวาตะวันออก*   ', 10, 1, 2),
(79, '101007', 'สามวาตะวันตก*   ', 10, 1, 2),
(80, '101101', 'ลาดกระบัง   ', 11, 1, 2),
(81, '101102', 'คลองสองต้นนุ่น   ', 11, 1, 2),
(82, '101103', 'คลองสามประเวศ   ', 11, 1, 2),
(83, '101104', 'ลำปลาทิว   ', 11, 1, 2),
(84, '101105', 'ทับยาว   ', 11, 1, 2),
(85, '101106', 'ขุมทอง   ', 11, 1, 2),
(86, '101201', '*ทุ่งวัดดอน   ', 12, 1, 2),
(87, '101202', '*ยานนาวา   ', 12, 1, 2),
(88, '101203', 'ช่องนนทรี   ', 12, 1, 2),
(89, '101204', 'บางโพงพาง   ', 12, 1, 2),
(90, '101205', '*วัดพระยาไกร   ', 12, 1, 2),
(91, '101206', '*บางโคล่   ', 12, 1, 2),
(92, '101207', '*บางคอแหลม   ', 12, 1, 2),
(93, '101208', '*ทุ่งมหาเมฆ   ', 12, 1, 2),
(94, '101298', '*บางโคล่   ', 12, 1, 2),
(95, '101299', '*บางคอแหลม   ', 12, 1, 2),
(96, '101301', 'จักรวรรดิ   ', 13, 1, 2),
(97, '101302', 'สัมพันธวงศ์   ', 13, 1, 2),
(98, '101303', 'ตลาดน้อย   ', 13, 1, 2),
(99, '101401', 'สามเสนใน   ', 14, 1, 2),
(100, '101402', '*ถนนเพชรบุรี   ', 14, 1, 2),
(101, '101403', '*ทุ่งพญาไท   ', 14, 1, 2),
(102, '101404', '*มักกะสัน   ', 14, 1, 2),
(103, '101405', '*ถนนพญาไท   ', 14, 1, 2),
(104, '101499', '*ทุ่งพญาไท   ', 14, 1, 2),
(105, '101501', 'วัดกัลยาณ์   ', 15, 1, 2),
(106, '101502', 'หิรัญรูจี   ', 15, 1, 2),
(107, '101503', 'บางยี่เรือ   ', 15, 1, 2),
(108, '101504', 'บุคคโล   ', 15, 1, 2),
(109, '101505', 'ตลาดพลู   ', 15, 1, 2),
(110, '101506', 'ดาวคะนอง   ', 15, 1, 2),
(111, '101507', 'สำเหร่   ', 15, 1, 2),
(112, '101599', '*คลองสาน   ', 15, 1, 2),
(113, '101601', 'วัดอรุณ   ', 16, 1, 2),
(114, '101602', 'วัดท่าพระ   ', 16, 1, 2),
(115, '101701', 'ห้วยขวาง   ', 17, 1, 2),
(116, '101702', 'บางกะปิ   ', 17, 1, 2),
(117, '101703', '*ดินแดง   ', 17, 1, 2),
(118, '101704', 'สามเสนนอก   ', 17, 1, 2),
(119, '101801', 'สมเด็จเจ้าพระยา   ', 18, 1, 2),
(120, '101802', 'คลองสาน   ', 18, 1, 2),
(121, '101803', 'บางลำภูล่าง   ', 18, 1, 2),
(122, '101804', 'คลองต้นไทร   ', 18, 1, 2),
(123, '101901', 'คลองชักพระ   ', 19, 1, 2),
(124, '101902', 'ตลิ่งชัน   ', 19, 1, 2),
(125, '101903', 'ฉิมพลี   ', 19, 1, 2),
(126, '101904', 'บางพรม   ', 19, 1, 2),
(127, '101905', 'บางระมาด   ', 19, 1, 2),
(128, '101906', 'ทวีวัฒนา*   ', 19, 1, 2),
(129, '101907', 'บางเชือกหนัง   ', 19, 1, 2),
(130, '101908', 'ศาลาธรรมสพน์*   ', 19, 1, 2),
(131, '102001', '*บางพลัด   ', 20, 1, 2),
(132, '102002', '*บางบำหรุ   ', 20, 1, 2),
(133, '102003', '*บางอ้อ   ', 20, 1, 2),
(134, '102004', 'ศิริราช   ', 20, 1, 2),
(135, '102005', 'บ้านช่างหล่อ   ', 20, 1, 2),
(136, '102006', 'บางขุนนนท์   ', 20, 1, 2),
(137, '102007', 'บางขุนศรี   ', 20, 1, 2),
(138, '102008', '*บางยี่ขัน   ', 20, 1, 2),
(139, '102009', 'อรุณอมรินทร์   ', 20, 1, 2),
(140, '102097', '*บางยี่ขัน   ', 20, 1, 2),
(141, '102098', '*บางบำหรุ   ', 20, 1, 2),
(142, '102099', '*บางอ้อ   ', 20, 1, 2),
(143, '102101', '*บางค้อ   ', 21, 1, 2),
(144, '102102', '*จอมทอง   ', 21, 1, 2),
(145, '102103', '*บางขุนเทียน   ', 21, 1, 2),
(146, '102104', 'บางบอน*   ', 21, 1, 2),
(147, '102105', 'ท่าข้าม   ', 21, 1, 2),
(148, '102106', '*บางมด   ', 21, 1, 2),
(149, '102107', 'แสมดำ   ', 21, 1, 2),
(150, '102201', 'บางหว้า   ', 22, 1, 2),
(151, '102202', 'บางด้วน   ', 22, 1, 2),
(152, '102203', 'บางแค   ', 22, 1, 2),
(153, '102204', 'บางแคเหนือ   ', 22, 1, 2),
(154, '102205', 'บางไผ่   ', 22, 1, 2),
(155, '102206', 'บางจาก   ', 22, 1, 2),
(156, '102207', 'บางแวก   ', 22, 1, 2),
(157, '102208', 'คลองขวาง   ', 22, 1, 2),
(158, '102209', 'ปากคลองภาษีเจริญ   ', 22, 1, 2),
(159, '102210', 'คูหาสวรรค์   ', 22, 1, 2),
(160, '102301', 'หลักสอง*   ', 23, 1, 2),
(161, '102302', 'หนองแขม   ', 23, 1, 2),
(162, '102303', 'หนองค้างพลู   ', 23, 1, 2),
(163, '102401', 'ราษฎร์บูรณะ   ', 24, 1, 2),
(164, '102402', 'บางปะกอก   ', 24, 1, 2),
(165, '102403', 'บางมด*   ', 24, 1, 2),
(166, '102404', 'ทุ่งครุ*   ', 24, 1, 2),
(167, '102501', 'บางพลัด   ', 25, 1, 2),
(168, '102502', 'บางอ้อ   ', 25, 1, 2),
(169, '102503', 'บางบำหรุ   ', 25, 1, 2),
(170, '102504', 'บางยี่ขัน   ', 25, 1, 2),
(171, '102601', 'ดินแดง   ', 26, 1, 2),
(172, '102701', 'คลองกุ่ม   ', 27, 1, 2),
(173, '102702', 'สะพานสูง   ', 27, 1, 2),
(174, '102703', 'คันนายาว   ', 27, 1, 2),
(175, '102801', 'ทุ่งวัดดอน   ', 28, 1, 2),
(176, '102802', 'ยานนาวา   ', 28, 1, 2),
(177, '102803', 'ทุ่งมหาเมฆ   ', 28, 1, 2),
(178, '102901', 'บางซื่อ   ', 29, 1, 2),
(179, '103001', 'ลาดยาว   ', 30, 1, 2),
(180, '103002', 'เสนานิคม   ', 30, 1, 2),
(181, '103003', 'จันทรเกษม   ', 30, 1, 2),
(182, '103004', 'จอมพล   ', 30, 1, 2),
(183, '103005', 'จตุจักร   ', 30, 1, 2),
(184, '103101', 'บางคอแหลม   ', 31, 1, 2),
(185, '103102', 'วัดพระยาไกร   ', 31, 1, 2),
(186, '103103', 'บางโคล่   ', 31, 1, 2),
(187, '103201', 'ประเวศ   ', 32, 1, 2),
(188, '103202', 'หนองบอน   ', 32, 1, 2),
(189, '103203', 'ดอกไม้   ', 32, 1, 2),
(190, '103204', 'สวนหลวง   ', 32, 1, 2),
(191, '103301', 'คลองเตย   ', 33, 1, 2),
(192, '103302', 'คลองตัน   ', 33, 1, 2),
(193, '103303', 'พระโขนง   ', 33, 1, 2),
(194, '103304', 'คลองเตยเหนือ   ', 33, 1, 2),
(195, '103305', 'คลองตันเหนือ   ', 33, 1, 2),
(196, '103306', 'พระโขนงเหนือ   ', 33, 1, 2),
(197, '103401', 'สวนหลวง   ', 34, 1, 2),
(198, '103501', 'บางขุนเทียน   ', 35, 1, 2),
(199, '103502', 'บางค้อ   ', 35, 1, 2),
(200, '103503', 'บางมด   ', 35, 1, 2),
(201, '103504', 'จอมทอง   ', 35, 1, 2),
(202, '103601', 'ตลาดบางเขน*   ', 36, 1, 2),
(203, '103602', 'สีกัน   ', 36, 1, 2),
(204, '103603', 'ทุ่งสองห้อง*   ', 36, 1, 2),
(205, '103701', 'ทุ่งพญาไท   ', 37, 1, 2),
(206, '103702', 'ถนนพญาไท   ', 37, 1, 2),
(207, '103703', 'ถนนเพชรบุรี   ', 37, 1, 2),
(208, '103704', 'มักกะสัน   ', 37, 1, 2),
(209, '103801', 'ลาดพร้าว   ', 38, 1, 2),
(210, '103802', 'จรเข้บัว   ', 38, 1, 2),
(211, '103901', 'คลองเตยเหนือ   ', 39, 1, 2),
(212, '103902', 'คลองตันเหนือ   ', 39, 1, 2),
(213, '103903', 'พระโขนงเหนือ   ', 39, 1, 2),
(214, '104001', 'บางแค   ', 40, 1, 2),
(215, '104002', 'บางแคเหนือ   ', 40, 1, 2),
(216, '104003', 'บางไผ่   ', 40, 1, 2),
(217, '104004', 'หลักสอง   ', 40, 1, 2),
(218, '104101', 'ทุ่งสองห้อง   ', 41, 1, 2),
(219, '104102', 'ตลาดบางเขน   ', 41, 1, 2),
(220, '104201', 'สายไหม   ', 42, 1, 2),
(221, '104202', 'ออเงิน   ', 42, 1, 2),
(222, '104203', 'คลองถนน   ', 42, 1, 2),
(223, '104301', 'คันนายาว   ', 43, 1, 2),
(224, '104401', 'สะพานสูง   ', 44, 1, 2),
(225, '104501', 'วังทองหลาง   ', 45, 1, 2),
(226, '104601', 'สามวาตะวันตก   ', 46, 1, 2),
(227, '104602', 'สามวาตะวันออก   ', 46, 1, 2),
(228, '104603', 'บางชัน   ', 46, 1, 2),
(229, '104604', 'ทรายกองดิน   ', 46, 1, 2),
(230, '104605', 'ทรายกองดินใต้   ', 46, 1, 2),
(231, '104701', 'บางนา   ', 47, 1, 2),
(232, '104801', 'ทวีวัฒนา   ', 48, 1, 2),
(233, '104802', 'ศาลาธรรมสพน์   ', 48, 1, 2),
(234, '104901', 'บางมด   ', 49, 1, 2),
(235, '104902', 'ทุ่งครุ   ', 49, 1, 2),
(236, '105001', 'บางบอน   ', 50, 1, 2),
(237, '110101', 'ปากน้ำ   ', 52, 2, 2),
(238, '110102', 'สำโรงเหนือ   ', 52, 2, 2),
(239, '110103', 'บางเมือง   ', 52, 2, 2),
(240, '110104', 'ท้ายบ้าน   ', 52, 2, 2),
(241, '110105', '*นาเกลือ   ', 52, 2, 2),
(242, '110106', '*แหลมฟ้าผ่า   ', 52, 2, 2),
(243, '110107', '*ในคลองบางปลากด   ', 52, 2, 2),
(244, '110108', 'บางปูใหม่   ', 52, 2, 2),
(245, '110109', '*ปากคลองบางปลากด   ', 52, 2, 2),
(246, '110110', 'แพรกษา   ', 52, 2, 2),
(247, '110111', 'บางโปรง   ', 52, 2, 2),
(248, '110112', 'บางปู   ', 52, 2, 2),
(249, '110113', 'บางด้วน   ', 52, 2, 2),
(250, '110114', 'บางเมืองใหม่   ', 52, 2, 2),
(251, '110115', 'เทพารักษ์   ', 52, 2, 2),
(252, '110116', 'ท้ายบ้านใหม่   ', 52, 2, 2),
(253, '110117', 'แพรกษาใหม่   ', 52, 2, 2),
(254, '110194', '*บางปูเก่า   ', 52, 2, 2),
(255, '110195', '*ในคลองบางปลากด   ', 52, 2, 2),
(256, '110196', '*ปากคลองบางปลากด   ', 52, 2, 2),
(257, '110197', '*แหลมฟ้าผ่า   ', 52, 2, 2),
(258, '110198', '*บ้านคลองสวน   ', 52, 2, 2),
(259, '110199', '*นาเกลือ   ', 52, 2, 2),
(260, '110201', 'บางบ่อ   ', 53, 2, 2),
(261, '110202', 'บ้านระกาศ   ', 53, 2, 2),
(262, '110203', 'บางพลีน้อย   ', 53, 2, 2),
(263, '110204', 'บางเพรียง   ', 53, 2, 2),
(264, '110205', 'คลองด่าน   ', 53, 2, 2),
(265, '110206', 'คลองสวน   ', 53, 2, 2),
(266, '110207', 'เปร็ง   ', 53, 2, 2),
(267, '110208', 'คลองนิยมยาตรา   ', 53, 2, 2),
(268, '110209', 'คลองนิยม*   ', 53, 2, 2),
(269, '110301', 'บางพลีใหญ่   ', 54, 2, 2),
(270, '110302', 'บางแก้ว   ', 54, 2, 2),
(271, '110303', 'บางปลา   ', 54, 2, 2),
(272, '110304', 'บางโฉลง   ', 54, 2, 2),
(273, '110305', '*บางเสาธง   ', 54, 2, 2),
(274, '110306', '*ศรีษะจรเข้ใหญ่   ', 54, 2, 2),
(275, '110307', '*ศรีษะจรเข้น้อย   ', 54, 2, 2),
(276, '110308', 'ราชาเทวะ   ', 54, 2, 2),
(277, '110309', 'หนองปรือ   ', 54, 2, 2),
(278, '110401', 'ตลาด   ', 55, 2, 2),
(279, '110402', 'บางพึ่ง   ', 55, 2, 2),
(280, '110403', 'บางจาก   ', 55, 2, 2),
(281, '110404', 'บางครุ   ', 55, 2, 2),
(282, '110405', 'บางหญ้าแพรก   ', 55, 2, 2),
(283, '110406', 'บางหัวเสือ   ', 55, 2, 2),
(284, '110407', 'สำโรงใต้   ', 55, 2, 2),
(285, '110408', 'บางยอ   ', 55, 2, 2),
(286, '110409', 'บางกะเจ้า   ', 55, 2, 2),
(287, '110410', 'บางน้ำผึ้ง   ', 55, 2, 2),
(288, '110411', 'บางกระสอบ   ', 55, 2, 2),
(289, '110412', 'บางกอบัว   ', 55, 2, 2),
(290, '110413', 'ทรงคนอง   ', 55, 2, 2),
(291, '110414', 'สำโรง   ', 55, 2, 2),
(292, '110415', 'สำโรงกลาง   ', 55, 2, 2),
(293, '110501', 'นาเกลือ   ', 56, 2, 2),
(294, '110502', 'บ้านคลองสวน   ', 56, 2, 2),
(295, '110503', 'แหลมฟ้าผ่า   ', 56, 2, 2),
(296, '110504', 'ปากคลองบางปลากด   ', 56, 2, 2),
(297, '110505', 'ในคลองบางปลากด   ', 56, 2, 2),
(298, '110601', 'บางเสาธง   ', 57, 2, 2),
(299, '110602', 'ศีรษะจรเข้น้อย   ', 57, 2, 2),
(300, '110603', 'ศีรษะจรเข้ใหญ่   ', 57, 2, 2),
(301, '120101', 'สวนใหญ่   ', 58, 3, 2),
(302, '120102', 'ตลาดขวัญ   ', 58, 3, 2),
(303, '120103', 'บางเขน   ', 58, 3, 2),
(304, '120104', 'บางกระสอ   ', 58, 3, 2),
(305, '120105', 'ท่าทราย   ', 58, 3, 2),
(306, '120106', 'บางไผ่   ', 58, 3, 2),
(307, '120107', 'บางศรีเมือง   ', 58, 3, 2),
(308, '120108', 'บางกร่าง   ', 58, 3, 2),
(309, '120109', 'ไทรม้า   ', 58, 3, 2),
(310, '120110', 'บางรักน้อย   ', 58, 3, 2),
(311, '120201', 'วัดชลอ   ', 59, 3, 2),
(312, '120202', 'บางกรวย   ', 59, 3, 2),
(313, '120203', 'บางสีทอง   ', 59, 3, 2),
(314, '120204', 'บางขนุน   ', 59, 3, 2),
(315, '120205', 'บางขุนกอง   ', 59, 3, 2),
(316, '120206', 'บางคูเวียง   ', 59, 3, 2),
(317, '120207', 'มหาสวัสดิ์   ', 59, 3, 2),
(318, '120208', 'ปลายบาง   ', 59, 3, 2),
(319, '120209', 'ศาลากลาง   ', 59, 3, 2),
(320, '120301', 'บางม่วง   ', 60, 3, 2),
(321, '120302', 'บางแม่นาง   ', 60, 3, 2),
(322, '120303', 'บางเลน   ', 60, 3, 2),
(323, '120304', 'เสาธงหิน   ', 60, 3, 2),
(324, '120305', 'บางใหญ่   ', 60, 3, 2),
(325, '120306', 'บ้านใหม่   ', 60, 3, 2),
(326, '120401', 'โสนลอย   ', 61, 3, 2),
(327, '120402', 'บางบัวทอง   ', 61, 3, 2),
(328, '120403', 'บางรักใหญ่   ', 61, 3, 2),
(329, '120404', 'บางคูรัด   ', 61, 3, 2),
(330, '120405', 'ละหาร   ', 61, 3, 2),
(331, '120406', 'ลำโพ   ', 61, 3, 2),
(332, '120407', 'พิมลราช   ', 61, 3, 2),
(333, '120408', 'บางรักพัฒนา   ', 61, 3, 2),
(334, '120501', 'ไทรน้อย   ', 62, 3, 2),
(335, '120502', 'ราษฎร์นิยม   ', 62, 3, 2),
(336, '120503', 'หนองเพรางาย   ', 62, 3, 2),
(337, '120504', 'ไทรใหญ่   ', 62, 3, 2),
(338, '120505', 'ขุนศรี   ', 62, 3, 2),
(339, '120506', 'คลองขวาง   ', 62, 3, 2),
(340, '120507', 'ทวีวัฒนา   ', 62, 3, 2),
(341, '120601', 'ปากเกร็ด   ', 63, 3, 2),
(342, '120602', 'บางตลาด   ', 63, 3, 2),
(343, '120603', 'บ้านใหม่   ', 63, 3, 2),
(344, '120604', 'บางพูด   ', 63, 3, 2),
(345, '120605', 'บางตะไนย์   ', 63, 3, 2),
(346, '120606', 'คลองพระอุดม   ', 63, 3, 2),
(347, '120607', 'ท่าอิฐ   ', 63, 3, 2),
(348, '120608', 'เกาะเกร็ด   ', 63, 3, 2),
(349, '120609', 'อ้อมเกร็ด   ', 63, 3, 2),
(350, '120610', 'คลองข่อย   ', 63, 3, 2),
(351, '120611', 'บางพลับ   ', 63, 3, 2),
(352, '120612', 'คลองเกลือ   ', 63, 3, 2),
(353, '130101', 'บางปรอก   ', 66, 4, 2),
(354, '130102', 'บ้านใหม่   ', 66, 4, 2),
(355, '130103', 'บ้านกลาง   ', 66, 4, 2),
(356, '130104', 'บ้านฉาง   ', 66, 4, 2),
(357, '130105', 'บ้านกระแชง   ', 66, 4, 2),
(358, '130106', 'บางขะแยง   ', 66, 4, 2),
(359, '130107', 'บางคูวัด   ', 66, 4, 2),
(360, '130108', 'บางหลวง   ', 66, 4, 2),
(361, '130109', 'บางเดื่อ   ', 66, 4, 2),
(362, '130110', 'บางพูด   ', 66, 4, 2),
(363, '130111', 'บางพูน   ', 66, 4, 2),
(364, '130112', 'บางกะดี   ', 66, 4, 2),
(365, '130113', 'สวนพริกไทย   ', 66, 4, 2),
(366, '130114', 'หลักหก   ', 66, 4, 2),
(367, '130201', 'คลองหนึ่ง   ', 67, 4, 2),
(368, '130202', 'คลองสอง   ', 67, 4, 2),
(369, '130203', 'คลองสาม   ', 67, 4, 2),
(370, '130204', 'คลองสี่   ', 67, 4, 2),
(371, '130205', 'คลองห้า   ', 67, 4, 2),
(372, '130206', 'คลองหก   ', 67, 4, 2),
(373, '130207', 'คลองเจ็ด   ', 67, 4, 2),
(374, '130301', 'ประชาธิปัตย์   ', 68, 4, 2),
(375, '130302', 'บึงยี่โถ   ', 68, 4, 2),
(376, '130303', 'รังสิต   ', 68, 4, 2),
(377, '130304', 'ลำผักกูด   ', 68, 4, 2),
(378, '130305', 'บึงสนั่น   ', 68, 4, 2),
(379, '130306', 'บึงน้ำรักษ์   ', 68, 4, 2),
(380, '130401', 'บึงบา   ', 69, 4, 2),
(381, '130402', 'บึงบอน   ', 69, 4, 2),
(382, '130403', 'บึงกาสาม   ', 69, 4, 2),
(383, '130404', 'บึงชำอ้อ   ', 69, 4, 2),
(384, '130405', 'หนองสามวัง   ', 69, 4, 2),
(385, '130406', 'ศาลาครุ   ', 69, 4, 2),
(386, '130407', 'นพรัตน์   ', 69, 4, 2),
(387, '130501', 'ระแหง   ', 70, 4, 2),
(388, '130502', 'ลาดหลุมแก้ว   ', 70, 4, 2),
(389, '130503', 'คูบางหลวง   ', 70, 4, 2),
(390, '130504', 'คูขวาง   ', 70, 4, 2),
(391, '130505', 'คลองพระอุดม   ', 70, 4, 2),
(392, '130506', 'บ่อเงิน   ', 70, 4, 2),
(393, '130507', 'หน้าไม้   ', 70, 4, 2),
(394, '130601', 'คูคต   ', 71, 4, 2),
(395, '130602', 'ลาดสวาย   ', 71, 4, 2),
(396, '130603', 'บึงคำพร้อย   ', 71, 4, 2),
(397, '130604', 'ลำลูกกา   ', 71, 4, 2),
(398, '130605', 'บึงทองหลาง   ', 71, 4, 2),
(399, '130606', 'ลำไทร   ', 71, 4, 2),
(400, '130607', 'บึงคอไห   ', 71, 4, 2),
(401, '130608', 'พืชอุดม   ', 71, 4, 2),
(402, '130701', 'บางเตย   ', 72, 4, 2),
(403, '130702', 'คลองควาย   ', 72, 4, 2),
(404, '130703', 'สามโคก   ', 72, 4, 2),
(405, '130704', 'กระแชง   ', 72, 4, 2),
(406, '130705', 'บางโพธิ์เหนือ   ', 72, 4, 2),
(407, '130706', 'เชียงรากใหญ่   ', 72, 4, 2),
(408, '130707', 'บ้านปทุม   ', 72, 4, 2),
(409, '130708', 'บ้านงิ้ว   ', 72, 4, 2),
(410, '130709', 'เชียงรากน้อย   ', 72, 4, 2),
(411, '130710', 'บางกระบือ   ', 72, 4, 2),
(412, '130711', 'ท้ายเกาะ   ', 72, 4, 2),
(413, '140101', 'ประตูชัย   ', 74, 5, 2),
(414, '140102', 'กะมัง   ', 74, 5, 2),
(415, '140103', 'หอรัตนไชย   ', 74, 5, 2),
(416, '140104', 'หัวรอ   ', 74, 5, 2),
(417, '140105', 'ท่าวาสุกรี   ', 74, 5, 2),
(418, '140106', 'ไผ่ลิง   ', 74, 5, 2),
(419, '140107', 'ปากกราน   ', 74, 5, 2),
(420, '140108', 'ภูเขาทอง   ', 74, 5, 2),
(421, '140109', 'สำเภาล่ม   ', 74, 5, 2),
(422, '140110', 'สวนพริก   ', 74, 5, 2),
(423, '140111', 'คลองตะเคียน   ', 74, 5, 2),
(424, '140112', 'วัดตูม   ', 74, 5, 2),
(425, '140113', 'หันตรา   ', 74, 5, 2),
(426, '140114', 'ลุมพลี   ', 74, 5, 2),
(427, '140115', 'บ้านใหม่   ', 74, 5, 2),
(428, '140116', 'บ้านเกาะ   ', 74, 5, 2),
(429, '140117', 'คลองสวนพลู   ', 74, 5, 2),
(430, '140118', 'คลองสระบัว   ', 74, 5, 2),
(431, '140119', 'เกาะเรียน   ', 74, 5, 2),
(432, '140120', 'บ้านป้อม   ', 74, 5, 2),
(433, '140121', 'บ้านรุน   ', 74, 5, 2),
(434, '140199', '*จำปา   ', 74, 5, 2),
(435, '140201', 'ท่าเรือ   ', 75, 5, 2),
(436, '140202', 'จำปา   ', 75, 5, 2),
(437, '140203', 'ท่าหลวง   ', 75, 5, 2),
(438, '140204', 'บ้านร่อม   ', 75, 5, 2),
(439, '140205', 'ศาลาลอย   ', 75, 5, 2),
(440, '140206', 'วังแดง   ', 75, 5, 2),
(441, '140207', 'โพธิ์เอน   ', 75, 5, 2),
(442, '140208', 'ปากท่า   ', 75, 5, 2),
(443, '140209', 'หนองขนาก   ', 75, 5, 2),
(444, '140210', 'ท่าเจ้าสนุก   ', 75, 5, 2),
(445, '140301', 'นครหลวง   ', 76, 5, 2),
(446, '140302', 'ท่าช้าง   ', 76, 5, 2),
(447, '140303', 'บ่อโพง   ', 76, 5, 2),
(448, '140304', 'บ้านชุ้ง   ', 76, 5, 2),
(449, '140305', 'ปากจั่น   ', 76, 5, 2),
(450, '140306', 'บางระกำ   ', 76, 5, 2),
(451, '140307', 'บางพระครู   ', 76, 5, 2),
(452, '140308', 'แม่ลา   ', 76, 5, 2),
(453, '140309', 'หนองปลิง   ', 76, 5, 2),
(454, '140310', 'คลองสะแก   ', 76, 5, 2),
(455, '140311', 'สามไถ   ', 76, 5, 2),
(456, '140312', 'พระนอน   ', 76, 5, 2),
(457, '140401', 'บางไทร   ', 77, 5, 2),
(458, '140402', 'บางพลี   ', 77, 5, 2),
(459, '140403', 'สนามชัย   ', 77, 5, 2),
(460, '140404', 'บ้านแป้ง   ', 77, 5, 2),
(461, '140405', 'หน้าไม้   ', 77, 5, 2),
(462, '140406', 'บางยี่โท   ', 77, 5, 2),
(463, '140407', 'แคออก   ', 77, 5, 2),
(464, '140408', 'แคตก   ', 77, 5, 2),
(465, '140409', 'ช่างเหล็ก   ', 77, 5, 2),
(466, '140410', 'กระแชง   ', 77, 5, 2),
(467, '140411', 'บ้านกลึง   ', 77, 5, 2),
(468, '140412', 'ช้างน้อย   ', 77, 5, 2),
(469, '140413', 'ห่อหมก   ', 77, 5, 2),
(470, '140414', 'ไผ่พระ   ', 77, 5, 2),
(471, '140415', 'กกแก้วบูรพา   ', 77, 5, 2),
(472, '140416', 'ไม้ตรา   ', 77, 5, 2),
(473, '140417', 'บ้านม้า   ', 77, 5, 2),
(474, '140418', 'บ้านเกาะ   ', 77, 5, 2),
(475, '140419', 'ราชคราม   ', 77, 5, 2),
(476, '140420', 'ช้างใหญ่   ', 77, 5, 2),
(477, '140421', 'โพแตง   ', 77, 5, 2),
(478, '140422', 'เชียงรากน้อย   ', 77, 5, 2),
(479, '140423', 'โคกช้าง   ', 77, 5, 2),
(480, '140501', 'บางบาล   ', 78, 5, 2),
(481, '140502', 'วัดยม   ', 78, 5, 2),
(482, '140503', 'ไทรน้อย   ', 78, 5, 2),
(483, '140504', 'สะพานไทย   ', 78, 5, 2),
(484, '140505', 'มหาพราหมณ์   ', 78, 5, 2),
(485, '140506', 'กบเจา   ', 78, 5, 2),
(486, '140507', 'บ้านคลัง   ', 78, 5, 2),
(487, '140508', 'พระขาว   ', 78, 5, 2),
(488, '140509', 'น้ำเต้า   ', 78, 5, 2),
(489, '140510', 'ทางช้าง   ', 78, 5, 2),
(490, '140511', 'วัดตะกู   ', 78, 5, 2),
(491, '140512', 'บางหลวง   ', 78, 5, 2),
(492, '140513', 'บางหลวงโดด   ', 78, 5, 2),
(493, '140514', 'บางหัก   ', 78, 5, 2),
(494, '140515', 'บางชะนี   ', 78, 5, 2),
(495, '140516', 'บ้านกุ่ม   ', 78, 5, 2),
(496, '140601', 'บ้านเลน   ', 79, 5, 2),
(497, '140602', 'เชียงรากน้อย   ', 79, 5, 2),
(498, '140603', 'บ้านโพ   ', 79, 5, 2),
(499, '140604', 'บ้านกรด   ', 79, 5, 2),
(500, '140605', 'บางกระสั้น   ', 79, 5, 2),
(501, '140606', 'คลองจิก   ', 79, 5, 2),
(502, '140607', 'บ้านหว้า   ', 79, 5, 2),
(503, '140608', 'วัดยม   ', 79, 5, 2),
(504, '140609', 'บางประแดง   ', 79, 5, 2),
(505, '140610', 'สามเรือน   ', 79, 5, 2),
(506, '140611', 'เกาะเกิด   ', 79, 5, 2),
(507, '140612', 'บ้านพลับ   ', 79, 5, 2),
(508, '140613', 'บ้านแป้ง   ', 79, 5, 2),
(509, '140614', 'คุ้งลาน   ', 79, 5, 2),
(510, '140615', 'ตลิ่งชัน   ', 79, 5, 2),
(511, '140616', 'บ้านสร้าง   ', 79, 5, 2),
(512, '140617', 'ตลาดเกรียบ   ', 79, 5, 2),
(513, '140618', 'ขนอนหลวง   ', 79, 5, 2),
(514, '140701', 'บางปะหัน   ', 80, 5, 2),
(515, '140702', 'ขยาย   ', 80, 5, 2),
(516, '140703', 'บางเดื่อ   ', 80, 5, 2),
(517, '140704', 'เสาธง   ', 80, 5, 2),
(518, '140705', 'ทางกลาง   ', 80, 5, 2),
(519, '140706', 'บางเพลิง   ', 80, 5, 2),
(520, '140707', 'หันสัง   ', 80, 5, 2),
(521, '140708', 'บางนางร้า   ', 80, 5, 2),
(522, '140709', 'ตานิม   ', 80, 5, 2),
(523, '140710', 'ทับน้ำ   ', 80, 5, 2),
(524, '140711', 'บ้านม้า   ', 80, 5, 2),
(525, '140712', 'ขวัญเมือง   ', 80, 5, 2),
(526, '140713', 'บ้านลี่   ', 80, 5, 2),
(527, '140714', 'โพธิ์สามต้น   ', 80, 5, 2),
(528, '140715', 'พุทเลา   ', 80, 5, 2),
(529, '140716', 'ตาลเอน   ', 80, 5, 2),
(530, '140717', 'บ้านขล้อ   ', 80, 5, 2),
(531, '140801', 'ผักไห่   ', 81, 5, 2),
(532, '140802', 'อมฤต   ', 81, 5, 2),
(533, '140803', 'บ้านแค   ', 81, 5, 2),
(534, '140804', 'ลาดน้ำเค็ม   ', 81, 5, 2),
(535, '140805', 'ตาลาน   ', 81, 5, 2),
(536, '140806', 'ท่าดินแดง   ', 81, 5, 2),
(537, '140807', 'ดอนลาน   ', 81, 5, 2),
(538, '140808', 'นาคู   ', 81, 5, 2),
(539, '140809', 'กุฎี   ', 81, 5, 2),
(540, '140810', 'ลำตะเคียน   ', 81, 5, 2),
(541, '140811', 'โคกช้าง   ', 81, 5, 2),
(542, '140812', 'จักราช   ', 81, 5, 2),
(543, '140813', 'หนองน้ำใหญ่   ', 81, 5, 2),
(544, '140814', 'ลาดชิด   ', 81, 5, 2),
(545, '140815', 'หน้าโคก   ', 81, 5, 2),
(546, '140816', 'บ้านใหญ่   ', 81, 5, 2),
(547, '140901', 'ภาชี   ', 82, 5, 2),
(548, '140902', 'โคกม่วง   ', 82, 5, 2),
(549, '140903', 'ระโสม   ', 82, 5, 2),
(550, '140904', 'หนองน้ำใส   ', 82, 5, 2),
(551, '140905', 'ดอนหญ้านาง   ', 82, 5, 2),
(552, '140906', 'ไผ่ล้อม   ', 82, 5, 2),
(553, '140907', 'กระจิว   ', 82, 5, 2),
(554, '140908', 'พระแก้ว   ', 82, 5, 2),
(555, '141001', 'ลาดบัวหลวง   ', 83, 5, 2),
(556, '141002', 'หลักชัย   ', 83, 5, 2),
(557, '141003', 'สามเมือง   ', 83, 5, 2),
(558, '141004', 'พระยาบันลือ   ', 83, 5, 2),
(559, '141005', 'สิงหนาท   ', 83, 5, 2),
(560, '141006', 'คู้สลอด   ', 83, 5, 2),
(561, '141007', 'คลองพระยาบันลือ   ', 83, 5, 2),
(562, '141101', 'ลำตาเสา   ', 84, 5, 2),
(563, '141102', 'บ่อตาโล่   ', 84, 5, 2),
(564, '141103', 'วังน้อย   ', 84, 5, 2),
(565, '141104', 'ลำไทร   ', 84, 5, 2),
(566, '141105', 'สนับทึบ   ', 84, 5, 2),
(567, '141106', 'พยอม   ', 84, 5, 2),
(568, '141107', 'หันตะเภา   ', 84, 5, 2),
(569, '141108', 'วังจุฬา   ', 84, 5, 2),
(570, '141109', 'ข้าวงาม   ', 84, 5, 2),
(571, '141110', 'ชะแมบ   ', 84, 5, 2),
(572, '141201', 'เสนา   ', 85, 5, 2),
(573, '141202', 'บ้านแพน   ', 85, 5, 2),
(574, '141203', 'เจ้าเจ็ด   ', 85, 5, 2),
(575, '141204', 'สามกอ   ', 85, 5, 2),
(576, '141205', 'บางนมโค   ', 85, 5, 2),
(577, '141206', 'หัวเวียง   ', 85, 5, 2),
(578, '141207', 'มารวิชัย   ', 85, 5, 2),
(579, '141208', 'บ้านโพธิ์   ', 85, 5, 2),
(580, '141209', 'รางจรเข้   ', 85, 5, 2),
(581, '141210', 'บ้านกระทุ่ม   ', 85, 5, 2),
(582, '141211', 'บ้านแถว   ', 85, 5, 2),
(583, '141212', 'ชายนา   ', 85, 5, 2),
(584, '141213', 'สามตุ่ม   ', 85, 5, 2),
(585, '141214', 'ลาดงา   ', 85, 5, 2),
(586, '141215', 'ดอนทอง   ', 85, 5, 2),
(587, '141216', 'บ้านหลวง   ', 85, 5, 2),
(588, '141217', 'เจ้าเสด็จ   ', 85, 5, 2),
(589, '141301', 'บางซ้าย   ', 86, 5, 2),
(590, '141302', 'แก้วฟ้า   ', 86, 5, 2),
(591, '141303', 'เต่าเล่า   ', 86, 5, 2),
(592, '141304', 'ปลายกลัด   ', 86, 5, 2),
(593, '141305', 'เทพมงคล   ', 86, 5, 2),
(594, '141306', 'วังพัฒนา   ', 86, 5, 2),
(595, '141401', 'คานหาม   ', 87, 5, 2),
(596, '141402', 'บ้านช้าง   ', 87, 5, 2),
(597, '141403', 'สามบัณฑิต   ', 87, 5, 2),
(598, '141404', 'บ้านหีบ   ', 87, 5, 2),
(599, '141405', 'หนองไม้ซุง   ', 87, 5, 2),
(600, '141406', 'อุทัย   ', 87, 5, 2),
(601, '141407', 'เสนา   ', 87, 5, 2),
(602, '141408', 'หนองน้ำส้ม   ', 87, 5, 2),
(603, '141409', 'โพสาวหาญ   ', 87, 5, 2),
(604, '141410', 'ธนู   ', 87, 5, 2),
(605, '141411', 'ข้าวเม่า   ', 87, 5, 2),
(606, '141501', 'หัวไผ่   ', 88, 5, 2),
(607, '141502', 'กะทุ่ม   ', 88, 5, 2),
(608, '141503', 'มหาราช   ', 88, 5, 2),
(609, '141504', 'น้ำเต้า   ', 88, 5, 2),
(610, '141505', 'บางนา   ', 88, 5, 2),
(611, '141506', 'โรงช้าง   ', 88, 5, 2),
(612, '141507', 'เจ้าปลุก   ', 88, 5, 2),
(613, '141508', 'พิตเพียน   ', 88, 5, 2),
(614, '141509', 'บ้านนา   ', 88, 5, 2),
(615, '141510', 'บ้านขวาง   ', 88, 5, 2),
(616, '141511', 'ท่าตอ   ', 88, 5, 2),
(617, '141512', 'บ้านใหม่   ', 88, 5, 2),
(618, '141601', 'บ้านแพรก   ', 89, 5, 2),
(619, '141602', 'บ้านใหม่   ', 89, 5, 2),
(620, '141603', 'สำพะเนียง   ', 89, 5, 2),
(621, '141604', 'คลองน้อย   ', 89, 5, 2),
(622, '141605', 'สองห้อง   ', 89, 5, 2),
(623, '150101', 'ตลาดหลวง   ', 90, 6, 2),
(624, '150102', 'บางแก้ว   ', 90, 6, 2),
(625, '150103', 'ศาลาแดง   ', 90, 6, 2),
(626, '150104', 'ป่างิ้ว   ', 90, 6, 2),
(627, '150105', 'บ้านแห   ', 90, 6, 2),
(628, '150106', 'ตลาดกรวด   ', 90, 6, 2),
(629, '150107', 'มหาดไทย   ', 90, 6, 2),
(630, '150108', 'บ้านอิฐ   ', 90, 6, 2),
(631, '150109', 'หัวไผ่   ', 90, 6, 2),
(632, '150110', 'จำปาหล่อ   ', 90, 6, 2),
(633, '150111', 'โพสะ   ', 90, 6, 2),
(634, '150112', 'บ้านรี   ', 90, 6, 2),
(635, '150113', 'คลองวัว   ', 90, 6, 2),
(636, '150114', 'ย่านซื่อ   ', 90, 6, 2),
(637, '150201', 'จรเข้ร้อง   ', 91, 6, 2),
(638, '150202', 'ไชยภูมิ   ', 91, 6, 2),
(639, '150203', 'ชัยฤทธิ์   ', 91, 6, 2),
(640, '150204', 'เทวราช   ', 91, 6, 2),
(641, '150205', 'ราชสถิตย์   ', 91, 6, 2),
(642, '150206', 'ไชโย   ', 91, 6, 2),
(643, '150207', 'หลักฟ้า   ', 91, 6, 2),
(644, '150208', 'ชะไว   ', 91, 6, 2),
(645, '150209', 'ตรีณรงค์   ', 91, 6, 2),
(646, '150301', 'บางปลากด   ', 92, 6, 2),
(647, '150302', 'ป่าโมก   ', 92, 6, 2),
(648, '150303', 'สายทอง   ', 92, 6, 2),
(649, '150304', 'โรงช้าง   ', 92, 6, 2),
(650, '150305', 'บางเสด็จ   ', 92, 6, 2),
(651, '150306', 'นรสิงห์   ', 92, 6, 2),
(652, '150307', 'เอกราช   ', 92, 6, 2),
(653, '150308', 'โผงเผง   ', 92, 6, 2),
(654, '150401', 'อ่างแก้ว   ', 93, 6, 2),
(655, '150402', 'อินทประมูล   ', 93, 6, 2),
(656, '150403', 'บางพลับ   ', 93, 6, 2),
(657, '150404', 'หนองแม่ไก่   ', 93, 6, 2),
(658, '150405', 'รำมะสัก   ', 93, 6, 2),
(659, '150406', 'บางระกำ   ', 93, 6, 2),
(660, '150407', 'โพธิ์รังนก   ', 93, 6, 2),
(661, '150408', 'องครักษ์   ', 93, 6, 2),
(662, '150409', 'โคกพุทรา   ', 93, 6, 2),
(663, '150410', 'ยางช้าย   ', 93, 6, 2),
(664, '150411', 'บ่อแร่   ', 93, 6, 2),
(665, '150412', 'ทางพระ   ', 93, 6, 2),
(666, '150413', 'สามง่าม   ', 93, 6, 2),
(667, '150414', 'บางเจ้าฉ่า   ', 93, 6, 2),
(668, '150415', 'คำหยาด   ', 93, 6, 2),
(669, '150501', 'แสวงหา   ', 94, 6, 2),
(670, '150502', 'ศรีพราน   ', 94, 6, 2),
(671, '150503', 'บ้านพราน   ', 94, 6, 2),
(672, '150504', 'วังน้ำเย็น   ', 94, 6, 2),
(673, '150505', 'สีบัวทอง   ', 94, 6, 2),
(674, '150506', 'ห้วยไผ่   ', 94, 6, 2),
(675, '150507', 'จำลอง   ', 94, 6, 2),
(676, '150601', 'ไผ่จำศิล   ', 95, 6, 2),
(677, '150602', 'ศาลเจ้าโรงทอง   ', 95, 6, 2),
(678, '150603', 'ไผ่ดำพัฒนา   ', 95, 6, 2),
(679, '150604', 'สาวร้องไห้   ', 95, 6, 2),
(680, '150605', 'ท่าช้าง   ', 95, 6, 2),
(681, '150606', 'ยี่ล้น   ', 95, 6, 2),
(682, '150607', 'บางจัก   ', 95, 6, 2),
(683, '150608', 'ห้วยคันแหลน   ', 95, 6, 2),
(684, '150609', 'คลองขนาก   ', 95, 6, 2),
(685, '150610', 'ไผ่วง   ', 95, 6, 2),
(686, '150611', 'สี่ร้อย   ', 95, 6, 2),
(687, '150612', 'ม่วงเตี้ย   ', 95, 6, 2),
(688, '150613', 'หัวตะพาน   ', 95, 6, 2),
(689, '150614', 'หลักแก้ว   ', 95, 6, 2),
(690, '150615', 'ตลาดใหม่   ', 95, 6, 2),
(691, '150701', 'สามโก้   ', 96, 6, 2),
(692, '150702', 'ราษฎรพัฒนา   ', 96, 6, 2),
(693, '150703', 'อบทม   ', 96, 6, 2),
(694, '150704', 'โพธิ์ม่วงพันธ์   ', 96, 6, 2),
(695, '150705', 'มงคลธรรมนิมิต   ', 96, 6, 2),
(696, '160101', 'ทะเลชุบศร   ', 97, 7, 2),
(697, '160102', 'ท่าหิน   ', 97, 7, 2),
(698, '160103', 'กกโก   ', 97, 7, 2),
(699, '160104', 'โก่งธนู   ', 97, 7, 2),
(700, '160105', 'เขาพระงาม   ', 97, 7, 2),
(701, '160106', 'เขาสามยอด   ', 97, 7, 2),
(702, '160107', 'โคกกะเทียม   ', 97, 7, 2),
(703, '160108', 'โคกลำพาน   ', 97, 7, 2),
(704, '160109', 'โคกตูม   ', 97, 7, 2),
(705, '160110', 'งิ้วราย   ', 97, 7, 2),
(706, '160111', 'ดอนโพธิ์   ', 97, 7, 2),
(707, '160112', 'ตะลุง   ', 97, 7, 2),
(708, '160113', '*ทะเลชุบศร   ', 97, 7, 2),
(709, '160114', 'ท่าแค   ', 97, 7, 2),
(710, '160115', 'ท่าศาลา   ', 97, 7, 2),
(711, '160116', 'นิคมสร้างตนเอง   ', 97, 7, 2),
(712, '160117', 'บางขันหมาก   ', 97, 7, 2),
(713, '160118', 'บ้านข่อย   ', 97, 7, 2),
(714, '160119', 'ท้ายตลาด   ', 97, 7, 2),
(715, '160120', 'ป่าตาล   ', 97, 7, 2),
(716, '160121', 'พรหมมาสตร์   ', 97, 7, 2),
(717, '160122', 'โพธิ์เก้าต้น   ', 97, 7, 2),
(718, '160123', 'โพธิ์ตรุ   ', 97, 7, 2),
(719, '160124', 'สี่คลอง   ', 97, 7, 2),
(720, '160125', 'ถนนใหญ่   ', 97, 7, 2),
(721, '160201', 'พัฒนานิคม   ', 98, 7, 2),
(722, '160202', 'ช่องสาริกา   ', 98, 7, 2),
(723, '160203', 'มะนาวหวาน   ', 98, 7, 2),
(724, '160204', 'ดีลัง   ', 98, 7, 2),
(725, '160205', 'โคกสลุง   ', 98, 7, 2),
(726, '160206', 'ชอนน้อย   ', 98, 7, 2),
(727, '160207', 'หนองบัว   ', 98, 7, 2),
(728, '160208', 'ห้วยขุนราม   ', 98, 7, 2),
(729, '160209', 'น้ำสุด   ', 98, 7, 2),
(730, '160301', 'โคกสำโรง   ', 99, 7, 2),
(731, '160302', 'เกาะแก้ว   ', 99, 7, 2),
(732, '160303', 'ถลุงเหล็ก   ', 99, 7, 2),
(733, '160304', 'หลุมข้าว   ', 99, 7, 2),
(734, '160305', 'ห้วยโป่ง   ', 99, 7, 2),
(735, '160306', 'คลองเกตุ   ', 99, 7, 2),
(736, '160307', 'สะแกราบ   ', 99, 7, 2),
(737, '160308', 'เพนียด   ', 99, 7, 2),
(738, '160309', 'วังเพลิง   ', 99, 7, 2),
(739, '160310', 'ดงมะรุม   ', 99, 7, 2),
(740, '160311', '*ชอนสารเดช   ', 99, 7, 2),
(741, '160312', '*หนองม่วง   ', 99, 7, 2),
(742, '160313', '*บ่อทอง   ', 99, 7, 2),
(743, '160314', '*ยางโทน   ', 99, 7, 2),
(744, '160315', '*ชอนสมบูรณ์   ', 99, 7, 2),
(745, '160316', '*โคกเจริญ   ', 99, 7, 2),
(746, '160317', '*ยางราก   ', 99, 7, 2),
(747, '160318', 'วังขอนขว้าง   ', 99, 7, 2),
(748, '160319', '*ดงดินแดง   ', 99, 7, 2),
(749, '160320', 'วังจั่น   ', 99, 7, 2),
(750, '160321', '*หนองมะค่า   ', 99, 7, 2),
(751, '160322', 'หนองแขม   ', 99, 7, 2),
(752, '160323', '*วังทอง   ', 99, 7, 2),
(753, '160389', '*ชอนสารเดช   ', 99, 7, 2),
(754, '160390', '*ยางโทน   ', 99, 7, 2),
(755, '160391', '*ชอนสมบูรณ์   ', 99, 7, 2),
(756, '160392', '*ดงดินแดง   ', 99, 7, 2),
(757, '160393', '*บ่อทอง   ', 99, 7, 2),
(758, '160394', '*หนองม่วง   ', 99, 7, 2),
(759, '160395', '*ยางราก   ', 99, 7, 2),
(760, '160396', '*โคกเจริญ   ', 99, 7, 2),
(761, '160397', '*ทุ่งท่าช้าง   ', 99, 7, 2),
(762, '160398', '*มหาโพธิ์   ', 99, 7, 2),
(763, '160399', '*สระโบสถ์   ', 99, 7, 2),
(764, '160401', 'ลำนารายณ์   ', 100, 7, 2),
(765, '160402', 'ชัยนารายณ์   ', 100, 7, 2),
(766, '160403', 'ศิลาทิพย์   ', 100, 7, 2),
(767, '160404', 'ห้วยหิน   ', 100, 7, 2),
(768, '160405', 'ม่วงค่อม   ', 100, 7, 2),
(769, '160406', 'บัวชุม   ', 100, 7, 2),
(770, '160407', 'ท่าดินดำ   ', 100, 7, 2),
(771, '160408', 'มะกอกหวาน   ', 100, 7, 2),
(772, '160409', 'ซับตะเคียน   ', 100, 7, 2),
(773, '160410', 'นาโสม   ', 100, 7, 2),
(774, '160411', 'หนองยายโต๊ะ   ', 100, 7, 2),
(775, '160412', 'เกาะรัง   ', 100, 7, 2),
(776, '160413', '*หนองรี   ', 100, 7, 2),
(777, '160414', 'ท่ามะนาว   ', 100, 7, 2),
(778, '160415', '*กุดตาเพชร   ', 100, 7, 2),
(779, '160416', '*ลำสนธิ   ', 100, 7, 2),
(780, '160417', 'นิคมลำนารายณ์   ', 100, 7, 2),
(781, '160418', 'ชัยบาดาล   ', 100, 7, 2),
(782, '160419', 'บ้านใหม่สามัคคี   ', 100, 7, 2),
(783, '160420', '*ซับสมบูรณ์   ', 100, 7, 2),
(784, '160421', '*เขารวก   ', 100, 7, 2),
(785, '160422', 'เขาแหลม   ', 100, 7, 2),
(786, '160492', '*เขาฉกรรจ์   ', 100, 7, 2),
(787, '160493', '*กุดตาเพชร   ', 100, 7, 2),
(788, '160494', '*หนองรี   ', 100, 7, 2),
(789, '160495', '*ลำสนธิ   ', 100, 7, 2),
(790, '160496', '*หนองผักแว่น   ', 100, 7, 2),
(791, '160497', '*ซับจำปา   ', 100, 7, 2),
(792, '160498', '*แก่งผักกูด   ', 100, 7, 2),
(793, '160499', '*ท่าหลวง   ', 100, 7, 2),
(794, '160501', 'ท่าวุ้ง   ', 101, 7, 2),
(795, '160502', 'บางคู้   ', 101, 7, 2),
(796, '160503', 'โพตลาดแก้ว   ', 101, 7, 2),
(797, '160504', 'บางลี่   ', 101, 7, 2),
(798, '160505', 'บางงา   ', 101, 7, 2),
(799, '160506', 'โคกสลุด   ', 101, 7, 2),
(800, '160507', 'เขาสมอคอน   ', 101, 7, 2),
(801, '160508', 'หัวสำโรง   ', 101, 7, 2),
(802, '160509', 'ลาดสาลี่   ', 101, 7, 2),
(803, '160510', 'บ้านเบิก   ', 101, 7, 2),
(804, '160511', 'มุจลินท์   ', 101, 7, 2),
(805, '160601', 'ไผ่ใหญ่   ', 102, 7, 2),
(806, '160602', 'บ้านทราย   ', 102, 7, 2),
(807, '160603', 'บ้านกล้วย   ', 102, 7, 2),
(808, '160604', 'ดงพลับ   ', 102, 7, 2),
(809, '160605', 'บ้านชี   ', 102, 7, 2),
(810, '160606', 'พุคา   ', 102, 7, 2),
(811, '160607', 'หินปัก   ', 102, 7, 2),
(812, '160608', 'บางพึ่ง   ', 102, 7, 2),
(813, '160609', 'หนองทรายขาว   ', 102, 7, 2),
(814, '160610', 'บางกะพี้   ', 102, 7, 2),
(815, '160611', 'หนองเต่า   ', 102, 7, 2),
(816, '160612', 'โพนทอง   ', 102, 7, 2),
(817, '160613', 'บางขาม   ', 102, 7, 2),
(818, '160614', 'ดอนดึง   ', 102, 7, 2),
(819, '160615', 'ชอนม่วง   ', 102, 7, 2),
(820, '160616', 'หนองกระเบียน   ', 102, 7, 2),
(821, '160617', 'สายห้วยแก้ว   ', 102, 7, 2),
(822, '160618', 'มหาสอน   ', 102, 7, 2),
(823, '160619', 'บ้านหมี่   ', 102, 7, 2),
(824, '160620', 'เชียงงา   ', 102, 7, 2),
(825, '160621', 'หนองเมือง   ', 102, 7, 2),
(826, '160622', 'สนามแจง   ', 102, 7, 2),
(827, '160701', 'ท่าหลวง   ', 103, 7, 2),
(828, '160702', 'แก่งผักกูด   ', 103, 7, 2),
(829, '160703', 'ซับจำปา   ', 103, 7, 2),
(830, '160704', 'หนองผักแว่น   ', 103, 7, 2),
(831, '160705', 'ทะเลวังวัด   ', 103, 7, 2),
(832, '160706', 'หัวลำ   ', 103, 7, 2),
(833, '160801', 'สระโบสถ์   ', 104, 7, 2),
(834, '160802', 'มหาโพธิ   ', 104, 7, 2),
(835, '160803', 'ทุ่งท่าช้าง   ', 104, 7, 2),
(836, '160804', 'ห้วยใหญ่   ', 104, 7, 2),
(837, '160805', 'นิยมชัย   ', 104, 7, 2),
(838, '160901', 'โคกเจริญ   ', 105, 7, 2),
(839, '160902', 'ยางราก   ', 105, 7, 2),
(840, '160903', 'หนองมะค่า   ', 105, 7, 2),
(841, '160904', 'วังทอง   ', 105, 7, 2),
(842, '160905', 'โคกแสมสาร   ', 105, 7, 2),
(843, '161001', 'ลำสนธิ   ', 106, 7, 2),
(844, '161002', 'ซับสมบูรณ์   ', 106, 7, 2),
(845, '161003', 'หนองรี   ', 106, 7, 2),
(846, '161004', 'กุดตาเพชร   ', 106, 7, 2),
(847, '161005', 'เขารวก   ', 106, 7, 2),
(848, '161006', 'เขาน้อย   ', 106, 7, 2),
(849, '161101', 'หนองม่วง   ', 107, 7, 2),
(850, '161102', 'บ่อทอง   ', 107, 7, 2),
(851, '161103', 'ดงดินแดง   ', 107, 7, 2),
(852, '161104', 'ชอนสมบูรณ์   ', 107, 7, 2),
(853, '161105', 'ยางโทน   ', 107, 7, 2),
(854, '161106', 'ชอนสารเดช   ', 107, 7, 2),
(855, '170101', 'บางพุทรา   ', 109, 8, 2),
(856, '170102', 'บางมัญ   ', 109, 8, 2),
(857, '170103', 'โพกรวม   ', 109, 8, 2),
(858, '170104', 'ม่วงหมู่   ', 109, 8, 2),
(859, '170105', 'หัวไผ่   ', 109, 8, 2),
(860, '170106', 'ต้นโพธิ์   ', 109, 8, 2),
(861, '170107', 'จักรสีห์   ', 109, 8, 2),
(862, '170108', 'บางกระบือ   ', 109, 8, 2),
(863, '170201', 'สิงห์   ', 110, 8, 2),
(864, '170202', 'ไม้ดัด   ', 110, 8, 2),
(865, '170203', 'เชิงกลัด   ', 110, 8, 2),
(866, '170204', 'โพชนไก่   ', 110, 8, 2),
(867, '170205', 'แม่ลา   ', 110, 8, 2),
(868, '170206', 'บ้านจ่า   ', 110, 8, 2),
(869, '170207', 'พักทัน   ', 110, 8, 2),
(870, '170208', 'สระแจง   ', 110, 8, 2),
(871, '170301', 'โพทะเล   ', 111, 8, 2),
(872, '170302', 'บางระจัน   ', 111, 8, 2),
(873, '170303', 'โพสังโฆ   ', 111, 8, 2),
(874, '170304', 'ท่าข้าม   ', 111, 8, 2),
(875, '170305', 'คอทราย   ', 111, 8, 2),
(876, '170306', 'หนองกระทุ่ม   ', 111, 8, 2),
(877, '170401', 'พระงาม   ', 112, 8, 2),
(878, '170402', 'พรหมบุรี   ', 112, 8, 2),
(879, '170403', 'บางน้ำเชี่ยว   ', 112, 8, 2),
(880, '170404', 'บ้านหม้อ   ', 112, 8, 2),
(881, '170405', 'บ้านแป้ง   ', 112, 8, 2),
(882, '170406', 'หัวป่า   ', 112, 8, 2),
(883, '170407', 'โรงช้าง   ', 112, 8, 2),
(884, '170501', 'ถอนสมอ   ', 113, 8, 2),
(885, '170502', 'โพประจักษ์   ', 113, 8, 2),
(886, '170503', 'วิหารขาว   ', 113, 8, 2),
(887, '170504', 'พิกุลทอง   ', 113, 8, 2),
(888, '170601', 'อินทร์บุรี   ', 114, 8, 2),
(889, '170602', 'ประศุก   ', 114, 8, 2),
(890, '170603', 'ทับยา   ', 114, 8, 2),
(891, '170604', 'งิ้วราย   ', 114, 8, 2),
(892, '170605', 'ชีน้ำร้าย   ', 114, 8, 2),
(893, '170606', 'ท่างาม   ', 114, 8, 2),
(894, '170607', 'น้ำตาล   ', 114, 8, 2),
(895, '170608', 'ทองเอน   ', 114, 8, 2),
(896, '170609', 'ห้วยชัน   ', 114, 8, 2),
(897, '170610', 'โพธิ์ชัย   ', 114, 8, 2),
(898, '180101', 'ในเมือง   ', 115, 9, 2),
(899, '180102', 'บ้านกล้วย   ', 115, 9, 2),
(900, '180103', 'ท่าชัย   ', 115, 9, 2),
(901, '180104', 'ชัยนาท   ', 115, 9, 2),
(902, '180105', 'เขาท่าพระ   ', 115, 9, 2),
(903, '180106', 'หาดท่าเสา   ', 115, 9, 2),
(904, '180107', 'ธรรมามูล   ', 115, 9, 2),
(905, '180108', 'เสือโฮก   ', 115, 9, 2),
(906, '180109', 'นางลือ   ', 115, 9, 2),
(907, '180201', 'คุ้งสำเภา   ', 116, 9, 2),
(908, '180202', 'วัดโคก   ', 116, 9, 2),
(909, '180203', 'ศิลาดาน   ', 116, 9, 2),
(910, '180204', 'ท่าฉนวน   ', 116, 9, 2),
(911, '180205', 'หางน้ำสาคร   ', 116, 9, 2),
(912, '180206', 'ไร่พัฒนา   ', 116, 9, 2),
(913, '180207', 'อู่ตะเภา   ', 116, 9, 2),
(914, '180301', 'วัดสิงห์   ', 117, 9, 2),
(915, '180302', 'มะขามเฒ่า   ', 117, 9, 2),
(916, '180303', 'หนองน้อย   ', 117, 9, 2),
(917, '180304', 'หนองบัว   ', 117, 9, 2),
(918, '180305', 'หนองมะโมง*   ', 117, 9, 2),
(919, '180306', 'หนองขุ่น   ', 117, 9, 2),
(920, '180307', 'บ่อแร่   ', 117, 9, 2),
(921, '180308', 'กุดจอก*   ', 117, 9, 2),
(922, '180309', 'วังตะเคียน*   ', 117, 9, 2),
(923, '180310', 'สะพานหิน*   ', 117, 9, 2),
(924, '180311', 'วังหมัน   ', 117, 9, 2),
(925, '180401', 'สรรพยา   ', 118, 9, 2),
(926, '180402', 'ตลุก   ', 118, 9, 2),
(927, '180403', 'เขาแก้ว   ', 118, 9, 2),
(928, '180404', 'โพนางดำตก   ', 118, 9, 2),
(929, '180405', 'โพนางดำออก   ', 118, 9, 2),
(930, '180406', 'บางหลวง   ', 118, 9, 2),
(931, '180407', 'หาดอาษา   ', 118, 9, 2),
(932, '180501', 'แพรกศรีราชา   ', 119, 9, 2),
(933, '180502', 'เที่ยงแท้   ', 119, 9, 2),
(934, '180503', 'ห้วยกรด   ', 119, 9, 2),
(935, '180504', 'โพงาม   ', 119, 9, 2),
(936, '180505', 'บางขุด   ', 119, 9, 2),
(937, '180506', 'ดงคอน   ', 119, 9, 2),
(938, '180507', 'ดอนกำ   ', 119, 9, 2),
(939, '180508', 'ห้วยกรดพัฒนา   ', 119, 9, 2),
(940, '180601', 'หันคา   ', 120, 9, 2),
(941, '180602', 'บ้านเชี่ยน   ', 120, 9, 2),
(942, '180603', 'เนินขาม*   ', 120, 9, 2),
(943, '180604', 'สุขเดือนห้า*   ', 120, 9, 2),
(944, '180605', 'ไพรนกยูง   ', 120, 9, 2),
(945, '180606', 'หนองแซง   ', 120, 9, 2),
(946, '180607', 'ห้วยงู   ', 120, 9, 2),
(947, '180608', 'วังไก่เถื่อน   ', 120, 9, 2),
(948, '180609', 'เด่นใหญ่   ', 120, 9, 2),
(949, '180610', 'กะบกเตี้ย*   ', 120, 9, 2),
(950, '180611', 'สามง่ามท่าโบสถ์   ', 120, 9, 2),
(951, '180701', 'หนองมะโมง   ', 121, 9, 2),
(952, '180702', 'วังตะเคียน   ', 121, 9, 2),
(953, '180703', 'สะพานหิน   ', 121, 9, 2),
(954, '180704', 'กุดจอก   ', 121, 9, 2),
(955, '180801', 'เนินขาม   ', 122, 9, 2),
(956, '180802', 'กะบกเตี้ย   ', 122, 9, 2),
(957, '180803', 'สุขเดือนห้า   ', 122, 9, 2),
(958, '190101', 'ปากเพรียว   ', 123, 10, 2),
(959, '190102', 'หน้าพระลาน*   ', 123, 10, 2),
(960, '190103', 'พุแค*   ', 123, 10, 2),
(961, '190104', 'ห้วยบง*   ', 123, 10, 2),
(962, '190105', 'ดาวเรือง   ', 123, 10, 2),
(963, '190106', 'นาโฉง   ', 123, 10, 2),
(964, '190107', 'โคกสว่าง   ', 123, 10, 2),
(965, '190108', 'หนองโน   ', 123, 10, 2),
(966, '190109', 'หนองยาว   ', 123, 10, 2),
(967, '190110', 'ปากข้าวสาร   ', 123, 10, 2),
(968, '190111', 'หนองปลาไหล   ', 123, 10, 2),
(969, '190112', 'กุดนกเปล้า   ', 123, 10, 2),
(970, '190113', 'ตลิ่งชัน   ', 123, 10, 2),
(971, '190114', 'ตะกุด   ', 123, 10, 2),
(972, '190115', 'บ้านแก้ง*   ', 123, 10, 2),
(973, '190116', 'ผึ้งรวง*   ', 123, 10, 2),
(974, '190117', 'เขาดินพัฒนา*   ', 123, 10, 2),
(975, '190201', 'แก่งคอย   ', 124, 10, 2),
(976, '190202', 'ทับกวาง   ', 124, 10, 2),
(977, '190203', 'ตาลเดี่ยว   ', 124, 10, 2),
(978, '190204', 'ห้วยแห้ง   ', 124, 10, 2),
(979, '190205', 'ท่าคล้อ   ', 124, 10, 2),
(980, '190206', 'หินซ้อน   ', 124, 10, 2),
(981, '190207', 'บ้านธาตุ   ', 124, 10, 2),
(982, '190208', 'บ้านป่า   ', 124, 10, 2),
(983, '190209', 'ท่าตูม   ', 124, 10, 2),
(984, '190210', 'ชะอม   ', 124, 10, 2),
(985, '190211', 'สองคอน   ', 124, 10, 2),
(986, '190212', 'เตาปูน   ', 124, 10, 2),
(987, '190213', 'ชำผักแพว   ', 124, 10, 2),
(988, '190215', 'ท่ามะปราง   ', 124, 10, 2),
(989, '190301', 'หนองแค   ', 125, 10, 2),
(990, '190302', 'กุ่มหัก   ', 125, 10, 2),
(991, '190303', 'คชสิทธิ์   ', 125, 10, 2),
(992, '190304', 'โคกตูม   ', 125, 10, 2),
(993, '190305', 'โคกแย้   ', 125, 10, 2),
(994, '190306', 'บัวลอย   ', 125, 10, 2),
(995, '190307', 'ไผ่ต่ำ   ', 125, 10, 2),
(996, '190308', 'โพนทอง   ', 125, 10, 2),
(997, '190309', 'ห้วยขมิ้น   ', 125, 10, 2),
(998, '190310', 'ห้วยทราย   ', 125, 10, 2),
(999, '190311', 'หนองไข่น้ำ   ', 125, 10, 2),
(1000, '190312', 'หนองแขม   ', 125, 10, 2),
(1001, '190313', 'หนองจิก   ', 125, 10, 2),
(1002, '190314', 'หนองจรเข้   ', 125, 10, 2),
(1003, '190315', 'หนองนาก   ', 125, 10, 2),
(1004, '190316', 'หนองปลาหมอ   ', 125, 10, 2),
(1005, '190317', 'หนองปลิง   ', 125, 10, 2),
(1006, '190318', 'หนองโรง   ', 125, 10, 2),
(1007, '190401', 'หนองหมู   ', 126, 10, 2),
(1008, '190402', 'บ้านลำ   ', 126, 10, 2),
(1009, '190403', 'คลองเรือ   ', 126, 10, 2),
(1010, '190404', 'วิหารแดง   ', 126, 10, 2),
(1011, '190405', 'หนองสรวง   ', 126, 10, 2),
(1012, '190406', 'เจริญธรรม   ', 126, 10, 2),
(1013, '190501', 'หนองแซง   ', 127, 10, 2),
(1014, '190502', 'หนองควายโซ   ', 127, 10, 2),
(1015, '190503', 'หนองหัวโพ   ', 127, 10, 2),
(1016, '190504', 'หนองสีดา   ', 127, 10, 2),
(1017, '190505', 'หนองกบ   ', 127, 10, 2),
(1018, '190506', 'ไก่เส่า   ', 127, 10, 2),
(1019, '190507', 'โคกสะอาด   ', 127, 10, 2),
(1020, '190508', 'ม่วงหวาน   ', 127, 10, 2),
(1021, '190509', 'เขาดิน   ', 127, 10, 2),
(1022, '190601', 'บ้านหมอ   ', 128, 10, 2),
(1023, '190602', 'บางโขมด   ', 128, 10, 2),
(1024, '190603', 'สร่างโศก   ', 128, 10, 2),
(1025, '190604', 'ตลาดน้อย   ', 128, 10, 2),
(1026, '190605', 'หรเทพ   ', 128, 10, 2),
(1027, '190606', 'โคกใหญ่   ', 128, 10, 2),
(1028, '190607', 'ไผ่ขวาง   ', 128, 10, 2),
(1029, '190608', 'บ้านครัว   ', 128, 10, 2),
(1030, '190609', 'หนองบัว   ', 128, 10, 2),
(1031, '190696', '*ดงตะงาว   ', 128, 10, 2),
(1032, '190697', '*บ้านหลวง   ', 128, 10, 2),
(1033, '190698', '*ไผ่หลิ่ว   ', 128, 10, 2),
(1034, '190699', '*ดอนพุด   ', 128, 10, 2),
(1035, '190701', 'ดอนพุด   ', 129, 10, 2),
(1036, '190702', 'ไผ่หลิ่ว   ', 129, 10, 2),
(1037, '190703', 'บ้านหลวง   ', 129, 10, 2),
(1038, '190704', 'ดงตะงาว   ', 129, 10, 2),
(1039, '190801', 'หนองโดน   ', 130, 10, 2),
(1040, '190802', 'บ้านกลับ   ', 130, 10, 2),
(1041, '190803', 'ดอนทอง   ', 130, 10, 2),
(1042, '190804', 'บ้านโปร่ง   ', 130, 10, 2),
(1043, '190901', 'พระพุทธบาท   ', 131, 10, 2),
(1044, '190902', 'ขุนโขลน   ', 131, 10, 2),
(1045, '190903', 'ธารเกษม   ', 131, 10, 2),
(1046, '190904', 'นายาว   ', 131, 10, 2),
(1047, '190905', 'พุคำจาน   ', 131, 10, 2),
(1048, '190906', 'เขาวง   ', 131, 10, 2),
(1049, '190907', 'ห้วยป่าหวาย   ', 131, 10, 2),
(1050, '190908', 'พุกร่าง   ', 131, 10, 2),
(1051, '190909', 'หนองแก   ', 131, 10, 2),
(1052, '191001', 'เสาไห้   ', 132, 10, 2),
(1053, '191002', 'บ้านยาง   ', 132, 10, 2),
(1054, '191003', 'หัวปลวก   ', 132, 10, 2),
(1055, '191004', 'งิ้วงาม   ', 132, 10, 2),
(1056, '191005', 'ศาลารีไทย   ', 132, 10, 2),
(1057, '191006', 'ต้นตาล   ', 132, 10, 2),
(1058, '191007', 'ท่าช้าง   ', 132, 10, 2),
(1059, '191008', 'พระยาทด   ', 132, 10, 2),
(1060, '191009', 'ม่วงงาม   ', 132, 10, 2),
(1061, '191010', 'เริงราง   ', 132, 10, 2),
(1062, '191011', 'เมืองเก่า   ', 132, 10, 2),
(1063, '191012', 'สวนดอกไม้   ', 132, 10, 2),
(1064, '191101', 'มวกเหล็ก   ', 133, 10, 2),
(1065, '191102', 'มิตรภาพ   ', 133, 10, 2),
(1066, '191103', '*แสลงพัน   ', 133, 10, 2),
(1067, '191104', 'หนองย่างเสือ   ', 133, 10, 2),
(1068, '191105', 'ลำสมพุง   ', 133, 10, 2),
(1069, '191106', '*คำพราน   ', 133, 10, 2),
(1070, '191107', 'ลำพญากลาง   ', 133, 10, 2),
(1071, '191108', '*วังม่วง   ', 133, 10, 2),
(1072, '191109', 'ซับสนุ่น   ', 133, 10, 2),
(1073, '191201', 'แสลงพัน   ', 134, 10, 2),
(1074, '191202', 'คำพราน   ', 134, 10, 2),
(1075, '191203', 'วังม่วง   ', 134, 10, 2),
(1076, '191301', 'เขาดินพัฒนา   ', 135, 10, 2),
(1077, '191302', 'บ้านแก้ง   ', 135, 10, 2),
(1078, '191303', 'ผึ้งรวง   ', 135, 10, 2),
(1079, '191304', 'พุแค   ', 135, 10, 2),
(1080, '191305', 'ห้วยบง   ', 135, 10, 2),
(1081, '191306', 'หน้าพระลาน   ', 135, 10, 2),
(1082, '200101', 'บางปลาสร้อย   ', 136, 11, 5),
(1083, '200102', 'มะขามหย่ง   ', 136, 11, 5),
(1084, '200103', 'บ้านโขด   ', 136, 11, 5),
(1085, '200104', 'แสนสุข   ', 136, 11, 5),
(1086, '200105', 'บ้านสวน   ', 136, 11, 5),
(1087, '200106', 'หนองรี   ', 136, 11, 5),
(1088, '200107', 'นาป่า   ', 136, 11, 5),
(1089, '200108', 'หนองข้างคอก   ', 136, 11, 5),
(1090, '200109', 'ดอนหัวฬ่อ   ', 136, 11, 5),
(1091, '200110', 'หนองไม้แดง   ', 136, 11, 5),
(1092, '200111', 'บางทราย   ', 136, 11, 5),
(1093, '200112', 'คลองตำหรุ   ', 136, 11, 5),
(1094, '200113', 'เหมือง   ', 136, 11, 5),
(1095, '200114', 'บ้านปึก   ', 136, 11, 5),
(1096, '200115', 'ห้วยกะปิ   ', 136, 11, 5),
(1097, '200116', 'เสม็ด   ', 136, 11, 5),
(1098, '200117', 'อ่างศิลา   ', 136, 11, 5),
(1099, '200118', 'สำนักบก   ', 136, 11, 5),
(1100, '200199', 'เทศบาลเมืองชลบุรี*   ', 136, 11, 5),
(1101, '200201', 'บ้านบึง   ', 137, 11, 5),
(1102, '200202', 'คลองกิ่ว   ', 137, 11, 5),
(1103, '200203', 'มาบไผ่   ', 137, 11, 5),
(1104, '200204', 'หนองซ้ำซาก   ', 137, 11, 5),
(1105, '200205', 'หนองบอนแดง   ', 137, 11, 5),
(1106, '200206', 'หนองชาก   ', 137, 11, 5),
(1107, '200207', 'หนองอิรุณ   ', 137, 11, 5),
(1108, '200208', 'หนองไผ่แก้ว   ', 137, 11, 5),
(1109, '200297', '*หนองเสือช้าง   ', 137, 11, 5),
(1110, '200298', '*คลองพลู   ', 137, 11, 5),
(1111, '200299', '*หนองใหญ่   ', 137, 11, 5),
(1112, '200301', 'หนองใหญ่   ', 138, 11, 5),
(1113, '200302', 'คลองพลู   ', 138, 11, 5),
(1114, '200303', 'หนองเสือช้าง   ', 138, 11, 5),
(1115, '200304', 'ห้างสูง   ', 138, 11, 5),
(1116, '200305', 'เขาซก   ', 138, 11, 5),
(1117, '200401', 'บางละมุง   ', 139, 11, 5),
(1118, '200402', 'หนองปรือ   ', 139, 11, 5),
(1119, '200403', 'หนองปลาไหล   ', 139, 11, 5),
(1120, '200404', 'โป่ง   ', 139, 11, 5),
(1121, '200405', 'เขาไม้แก้ว   ', 139, 11, 5),
(1122, '200406', 'ห้วยใหญ่   ', 139, 11, 5),
(1123, '200407', 'ตะเคียนเตี้ย   ', 139, 11, 5),
(1124, '200408', 'นาเกลือ   ', 139, 11, 5),
(1125, '200501', 'พานทอง   ', 140, 11, 5),
(1126, '200502', 'หนองตำลึง   ', 140, 11, 5),
(1127, '200503', 'มาบโป่ง   ', 140, 11, 5),
(1128, '200504', 'หนองกะขะ   ', 140, 11, 5),
(1129, '200505', 'หนองหงษ์   ', 140, 11, 5),
(1130, '200506', 'โคกขี้หนอน   ', 140, 11, 5),
(1131, '200507', 'บ้านเก่า   ', 140, 11, 5),
(1132, '200508', 'หน้าประดู่   ', 140, 11, 5),
(1133, '200509', 'บางนาง   ', 140, 11, 5),
(1134, '200510', 'เกาะลอย   ', 140, 11, 5),
(1135, '200511', 'บางหัก   ', 140, 11, 5),
(1136, '200601', 'พนัสนิคม   ', 141, 11, 5),
(1137, '200602', 'หน้าพระธาตุ   ', 141, 11, 5),
(1138, '200603', 'วัดหลวง   ', 141, 11, 5),
(1139, '200604', 'บ้านเซิด   ', 141, 11, 5),
(1140, '200605', 'นาเริก   ', 141, 11, 5),
(1141, '200606', 'หมอนนาง   ', 141, 11, 5),
(1142, '200607', 'สระสี่เหลี่ยม   ', 141, 11, 5),
(1143, '200608', 'วัดโบสถ์   ', 141, 11, 5),
(1144, '200609', 'กุฎโง้ง   ', 141, 11, 5),
(1145, '200610', 'หัวถนน   ', 141, 11, 5),
(1146, '200611', 'ท่าข้าม   ', 141, 11, 5),
(1147, '200612', 'ท่าบุญมี**   ', 141, 11, 5),
(1148, '200613', 'หนองปรือ   ', 141, 11, 5),
(1149, '200614', 'หนองขยาด   ', 141, 11, 5),
(1150, '200615', 'ทุ่งขวาง   ', 141, 11, 5),
(1151, '200616', 'หนองเหียง   ', 141, 11, 5),
(1152, '200617', 'นาวังหิน   ', 141, 11, 5),
(1153, '200618', 'บ้านช้าง   ', 141, 11, 5),
(1154, '200619', 'เกาะจันทร์**   ', 141, 11, 5),
(1155, '200620', 'โคกเพลาะ   ', 141, 11, 5),
(1156, '200621', 'ไร่หลักทอง   ', 141, 11, 5),
(1157, '200622', 'นามะตูม   ', 141, 11, 5),
(1158, '200623', '*บ้านเซิด   ', 141, 11, 5),
(1159, '200696', '*พูนพัฒนาทรัพย์   ', 141, 11, 5),
(1160, '200697', '*บ่อกวางทอง   ', 141, 11, 5),
(1161, '200698', '*วัดสุวรรณ   ', 141, 11, 5),
(1162, '200699', '*บ่อทอง   ', 141, 11, 5),
(1163, '200701', 'ศรีราชา   ', 142, 11, 5),
(1164, '200702', 'สุรศักดิ์   ', 142, 11, 5),
(1165, '200703', 'ทุ่งสุขลา   ', 142, 11, 5),
(1166, '200704', 'บึง   ', 142, 11, 5),
(1167, '200705', 'หนองขาม   ', 142, 11, 5),
(1168, '200706', 'เขาคันทรง   ', 142, 11, 5),
(1169, '200707', 'บางพระ   ', 142, 11, 5),
(1170, '200708', 'บ่อวิน   ', 142, 11, 5),
(1171, '200799', '*ท่าเทววงษ์   ', 142, 11, 5),
(1172, '200801', 'ท่าเทววงษ์   ', 143, 11, 5),
(1173, '200901', 'สัตหีบ   ', 144, 11, 5),
(1174, '200902', 'นาจอมเทียน   ', 144, 11, 5),
(1175, '200903', 'พลูตาหลวง   ', 144, 11, 5),
(1176, '200904', 'บางเสร่   ', 144, 11, 5),
(1177, '200905', 'แสมสาร   ', 144, 11, 5),
(1178, '201001', 'บ่อทอง   ', 145, 11, 5),
(1179, '201002', 'วัดสุวรรณ   ', 145, 11, 5),
(1180, '201003', 'บ่อกวางทอง   ', 145, 11, 5),
(1181, '201004', 'ธาตุทอง   ', 145, 11, 5),
(1182, '201005', 'เกษตรสุวรรณ   ', 145, 11, 5),
(1183, '201006', 'พลวงทอง   ', 145, 11, 5),
(1184, '201101', 'เกาะจันทร์   ', 146, 11, 5),
(1185, '201102', 'ท่าบุญมี   ', 146, 11, 5),
(1186, '207201', 'หนองปรือ*   ', 148, 11, 5),
(1187, '210101', 'ท่าประดู่   ', 151, 12, 5),
(1188, '210102', 'เชิงเนิน   ', 151, 12, 5),
(1189, '210103', 'ตะพง   ', 151, 12, 5),
(1190, '210104', 'ปากน้ำ   ', 151, 12, 5),
(1191, '210105', 'เพ   ', 151, 12, 5),
(1192, '210106', 'แกลง   ', 151, 12, 5),
(1193, '210107', 'บ้านแลง   ', 151, 12, 5),
(1194, '210108', 'นาตาขวัญ   ', 151, 12, 5),
(1195, '210109', 'เนินพระ   ', 151, 12, 5),
(1196, '210110', 'กะเฉด   ', 151, 12, 5),
(1197, '210111', 'ทับมา   ', 151, 12, 5),
(1198, '210112', 'น้ำคอก   ', 151, 12, 5),
(1199, '210113', 'ห้วยโป่ง   ', 151, 12, 5),
(1200, '210114', 'มาบตาพุด   ', 151, 12, 5),
(1201, '210115', 'สำนักทอง   ', 151, 12, 5),
(1202, '210198', '*สำนักท้อน   ', 151, 12, 5),
(1203, '210199', '*พลา   ', 151, 12, 5),
(1204, '210201', 'สำนักท้อน   ', 152, 12, 5),
(1205, '210202', 'พลา   ', 152, 12, 5),
(1206, '210203', 'บ้านฉาง   ', 152, 12, 5),
(1207, '210301', 'ทางเกวียน   ', 153, 12, 5),
(1208, '210302', 'วังหว้า   ', 153, 12, 5),
(1209, '210303', 'ชากโดน   ', 153, 12, 5),
(1210, '210304', 'เนินฆ้อ   ', 153, 12, 5),
(1211, '210305', 'กร่ำ   ', 153, 12, 5),
(1212, '210306', 'ชากพง   ', 153, 12, 5),
(1213, '210307', 'กระแสบน   ', 153, 12, 5),
(1214, '210308', 'บ้านนา   ', 153, 12, 5),
(1215, '210309', 'ทุ่งควายกิน   ', 153, 12, 5),
(1216, '210310', 'กองดิน   ', 153, 12, 5),
(1217, '210311', 'คลองปูน   ', 153, 12, 5),
(1218, '210312', 'พังราด   ', 153, 12, 5),
(1219, '210313', 'ปากน้ำกระแส   ', 153, 12, 5),
(1220, '210314', '*น้ำเป็น   ', 153, 12, 5),
(1221, '210315', '*ชำฆ้อ   ', 153, 12, 5),
(1222, '210316', '*ห้วยทับมอญ   ', 153, 12, 5),
(1223, '210317', 'ห้วยยาง   ', 153, 12, 5),
(1224, '210318', 'สองสลึง   ', 153, 12, 5),
(1225, '210319', '*เขาน้อย   ', 153, 12, 5),
(1226, '210398', '*ชุมแสง   ', 153, 12, 5),
(1227, '210399', '*วังจันทร์   ', 153, 12, 5),
(1228, '210401', 'วังจันทร์   ', 154, 12, 5);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(1229, '210402', 'ชุมแสง   ', 154, 12, 5),
(1230, '210403', 'ป่ายุบใน   ', 154, 12, 5),
(1231, '210404', 'พลงตาเอี่ยม   ', 154, 12, 5),
(1232, '210501', 'บ้านค่าย   ', 155, 12, 5),
(1233, '210502', 'หนองละลอก   ', 155, 12, 5),
(1234, '210503', 'หนองตะพาน   ', 155, 12, 5),
(1235, '210504', 'ตาขัน   ', 155, 12, 5),
(1236, '210505', 'บางบุตร   ', 155, 12, 5),
(1237, '210506', 'หนองบัว   ', 155, 12, 5),
(1238, '210507', 'ชากบก   ', 155, 12, 5),
(1239, '210508', 'มาบข่า*   ', 155, 12, 5),
(1240, '210509', 'พนานิคม*   ', 155, 12, 5),
(1241, '210510', 'นิคมพัฒนา*   ', 155, 12, 5),
(1242, '210511', 'มะขามคู่*   ', 155, 12, 5),
(1243, '210594', '*หนองไร่   ', 155, 12, 5),
(1244, '210595', '*มาบยางพร   ', 155, 12, 5),
(1245, '210596', '*แม่น้ำคู้   ', 155, 12, 5),
(1246, '210597', '*ละหาร   ', 155, 12, 5),
(1247, '210598', '*ตาสิทธิ์   ', 155, 12, 5),
(1248, '210599', '*ปลวกแดง   ', 155, 12, 5),
(1249, '210601', 'ปลวกแดง   ', 156, 12, 5),
(1250, '210602', 'ตาสิทธิ์   ', 156, 12, 5),
(1251, '210603', 'ละหาร   ', 156, 12, 5),
(1252, '210604', 'แม่น้ำคู้   ', 156, 12, 5),
(1253, '210605', 'มาบยางพร   ', 156, 12, 5),
(1254, '210606', 'หนองไร่   ', 156, 12, 5),
(1255, '210701', 'น้ำเป็น   ', 157, 12, 5),
(1256, '210702', 'ห้วยทับมอญ   ', 157, 12, 5),
(1257, '210703', 'ชำฆ้อ   ', 157, 12, 5),
(1258, '210704', 'เขาน้อย   ', 157, 12, 5),
(1259, '210801', 'นิคมพัฒนา   ', 158, 12, 5),
(1260, '210802', 'มาบข่า   ', 158, 12, 5),
(1261, '210803', 'พนานิคม   ', 158, 12, 5),
(1262, '210804', 'มะขามคู่   ', 158, 12, 5),
(1263, '220101', 'ตลาด   ', 160, 13, 5),
(1264, '220102', 'วัดใหม่   ', 160, 13, 5),
(1265, '220103', 'คลองนารายณ์   ', 160, 13, 5),
(1266, '220104', 'เกาะขวาง   ', 160, 13, 5),
(1267, '220105', 'คมบาง   ', 160, 13, 5),
(1268, '220106', 'ท่าช้าง   ', 160, 13, 5),
(1269, '220107', 'จันทนิมิต   ', 160, 13, 5),
(1270, '220108', 'บางกะจะ   ', 160, 13, 5),
(1271, '220109', 'แสลง   ', 160, 13, 5),
(1272, '220110', 'หนองบัว   ', 160, 13, 5),
(1273, '220111', 'พลับพลา   ', 160, 13, 5),
(1274, '220201', 'ขลุง   ', 161, 13, 5),
(1275, '220202', 'บ่อ   ', 161, 13, 5),
(1276, '220203', 'เกวียนหัก   ', 161, 13, 5),
(1277, '220204', 'ตะปอน   ', 161, 13, 5),
(1278, '220205', 'บางชัน   ', 161, 13, 5),
(1279, '220206', 'วันยาว   ', 161, 13, 5),
(1280, '220207', 'ซึ้ง   ', 161, 13, 5),
(1281, '220208', 'มาบไพ   ', 161, 13, 5),
(1282, '220209', 'วังสรรพรส   ', 161, 13, 5),
(1283, '220210', 'ตรอกนอง   ', 161, 13, 5),
(1284, '220211', 'ตกพรม   ', 161, 13, 5),
(1285, '220212', 'บ่อเวฬุ   ', 161, 13, 5),
(1286, '220301', 'ท่าใหม่   ', 162, 13, 5),
(1287, '220302', 'ยายร้า   ', 162, 13, 5),
(1288, '220303', 'สีพยา   ', 162, 13, 5),
(1289, '220304', 'บ่อพุ   ', 162, 13, 5),
(1290, '220305', 'พลอยแหวน   ', 162, 13, 5),
(1291, '220306', 'เขาวัว   ', 162, 13, 5),
(1292, '220307', 'เขาบายศรี   ', 162, 13, 5),
(1293, '220308', 'สองพี่น้อง   ', 162, 13, 5),
(1294, '220309', 'ทุ่งเบญจา   ', 162, 13, 5),
(1295, '220310', '*วังโตนด   ', 162, 13, 5),
(1296, '220311', 'รำพัน   ', 162, 13, 5),
(1297, '220312', 'โขมง   ', 162, 13, 5),
(1298, '220313', 'ตะกาดเง้า   ', 162, 13, 5),
(1299, '220314', 'คลองขุด   ', 162, 13, 5),
(1300, '220315', '*กระแจะ   ', 162, 13, 5),
(1301, '220316', '*สนามไชย   ', 162, 13, 5),
(1302, '220317', '*ช้างข้าม   ', 162, 13, 5),
(1303, '220318', '*นายายอาม   ', 162, 13, 5),
(1304, '220319', '*แก่งหางแมว   ', 162, 13, 5),
(1305, '220320', '*สามพี่น้อง   ', 162, 13, 5),
(1306, '220321', '*เขาวงกต   ', 162, 13, 5),
(1307, '220322', '*พวา   ', 162, 13, 5),
(1308, '220323', '*ขุนซ่อง   ', 162, 13, 5),
(1309, '220324', 'เขาแก้ว   ', 162, 13, 5),
(1310, '220394', '*กระแจะ   ', 162, 13, 5),
(1311, '220395', '*สนามไช   ', 162, 13, 5),
(1312, '220396', '*ช้างข้าม   ', 162, 13, 5),
(1313, '220397', '*วังโตนด   ', 162, 13, 5),
(1314, '220398', '*นายายอาม   ', 162, 13, 5),
(1315, '220399', '*แก่งหางแมว   ', 162, 13, 5),
(1316, '220401', 'ทับไทร   ', 163, 13, 5),
(1317, '220402', 'โป่งน้ำร้อน   ', 163, 13, 5),
(1318, '220403', '*ทรายขาว   ', 163, 13, 5),
(1319, '220404', 'หนองตาคง   ', 163, 13, 5),
(1320, '220405', '*ปะตง   ', 163, 13, 5),
(1321, '220406', '*ทุ่งขนาน   ', 163, 13, 5),
(1322, '220407', '*สะตอน   ', 163, 13, 5),
(1323, '220408', '*ทับช้าง   ', 163, 13, 5),
(1324, '220409', 'เทพนิมิต   ', 163, 13, 5),
(1325, '220410', 'คลองใหญ่   ', 163, 13, 5),
(1326, '220501', 'มะขาม   ', 164, 13, 5),
(1327, '220502', 'ท่าหลวง   ', 164, 13, 5),
(1328, '220503', 'ปัถวี   ', 164, 13, 5),
(1329, '220504', 'วังแซ้ม   ', 164, 13, 5),
(1330, '220505', '*พลวง   ', 164, 13, 5),
(1331, '220506', 'ฉมัน   ', 164, 13, 5),
(1332, '220507', '*ตะเคียนทอง   ', 164, 13, 5),
(1333, '220508', 'อ่างคีรี   ', 164, 13, 5),
(1334, '220509', '*คลองพลู   ', 164, 13, 5),
(1335, '220510', '*ซากไทย   ', 164, 13, 5),
(1336, '220601', 'ปากน้ำแหลมสิงห์   ', 165, 13, 5),
(1337, '220602', 'เกาะเปริด   ', 165, 13, 5),
(1338, '220603', 'หนองชิ่ม   ', 165, 13, 5),
(1339, '220604', 'พลิ้ว   ', 165, 13, 5),
(1340, '220605', 'คลองน้ำเค็ม   ', 165, 13, 5),
(1341, '220606', 'บางสระเก้า   ', 165, 13, 5),
(1342, '220607', 'บางกะไชย   ', 165, 13, 5),
(1343, '220701', 'ปะตง   ', 166, 13, 5),
(1344, '220702', 'ทุ่งขนาน   ', 166, 13, 5),
(1345, '220703', 'ทับช้าง   ', 166, 13, 5),
(1346, '220704', 'ทรายขาว   ', 166, 13, 5),
(1347, '220705', 'สะตอน   ', 166, 13, 5),
(1348, '220801', 'แก่งหางแมว   ', 167, 13, 5),
(1349, '220802', 'ขุนซ่อง   ', 167, 13, 5),
(1350, '220803', 'สามพี่น้อง   ', 167, 13, 5),
(1351, '220804', 'พวา   ', 167, 13, 5),
(1352, '220805', 'เขาวงกต   ', 167, 13, 5),
(1353, '220901', 'นายายอาม   ', 168, 13, 5),
(1354, '220902', 'วังโตนด   ', 168, 13, 5),
(1355, '220903', 'กระแจะ   ', 168, 13, 5),
(1356, '220904', 'สนามไชย   ', 168, 13, 5),
(1357, '220905', 'ช้างข้าม   ', 168, 13, 5),
(1358, '220906', 'วังใหม่   ', 168, 13, 5),
(1359, '221001', 'ชากไทย   ', 169, 13, 5),
(1360, '221002', 'พลวง   ', 169, 13, 5),
(1361, '221003', 'ตะเคียนทอง   ', 169, 13, 5),
(1362, '221004', 'คลองพลู   ', 169, 13, 5),
(1363, '221005', 'จันทเขลม   ', 169, 13, 5),
(1364, '230101', 'บางพระ   ', 171, 14, 5),
(1365, '230102', 'หนองเสม็ด   ', 171, 14, 5),
(1366, '230103', 'หนองโสน   ', 171, 14, 5),
(1367, '230104', 'หนองคันทรง   ', 171, 14, 5),
(1368, '230105', 'ห้วงน้ำขาว   ', 171, 14, 5),
(1369, '230106', 'อ่าวใหญ่   ', 171, 14, 5),
(1370, '230107', 'วังกระแจะ   ', 171, 14, 5),
(1371, '230108', 'ห้วยแร้ง   ', 171, 14, 5),
(1372, '230109', 'เนินทราย   ', 171, 14, 5),
(1373, '230110', 'ท่าพริก   ', 171, 14, 5),
(1374, '230111', 'ท่ากุ่ม   ', 171, 14, 5),
(1375, '230112', 'ตะกาง   ', 171, 14, 5),
(1376, '230113', 'ชำราก   ', 171, 14, 5),
(1377, '230114', 'แหลมกลัด   ', 171, 14, 5),
(1378, '230201', 'คลองใหญ่   ', 172, 14, 5),
(1379, '230202', 'ไม้รูด   ', 172, 14, 5),
(1380, '230203', 'หาดเล็ก   ', 172, 14, 5),
(1381, '230301', 'เขาสมิง   ', 173, 14, 5),
(1382, '230302', 'แสนตุ้ง   ', 173, 14, 5),
(1383, '230303', 'วังตะเคียน   ', 173, 14, 5),
(1384, '230304', 'ท่าโสม   ', 173, 14, 5),
(1385, '230305', 'สะตอ   ', 173, 14, 5),
(1386, '230306', 'ประณีต   ', 173, 14, 5),
(1387, '230307', 'เทพนิมิต   ', 173, 14, 5),
(1388, '230308', 'ทุ่งนนทรี   ', 173, 14, 5),
(1389, '230395', '*บ่อไร่   ', 173, 14, 5),
(1390, '230396', '*ด่านชุมพล   ', 173, 14, 5),
(1391, '230397', '*หนองบอน   ', 173, 14, 5),
(1392, '230398', '*ช้างทูน   ', 173, 14, 5),
(1393, '230399', '*บ่อพลอย   ', 173, 14, 5),
(1394, '230401', 'บ่อพลอย   ', 174, 14, 5),
(1395, '230402', 'ช้างทูน   ', 174, 14, 5),
(1396, '230403', 'ด่านชุมพล   ', 174, 14, 5),
(1397, '230404', 'หนองบอน   ', 174, 14, 5),
(1398, '230405', 'นนทรีย์   ', 174, 14, 5),
(1399, '230501', 'แหลมงอบ   ', 175, 14, 5),
(1400, '230502', 'น้ำเชี่ยว   ', 175, 14, 5),
(1401, '230503', 'บางปิด   ', 175, 14, 5),
(1402, '230504', '*เกาะช้าง   ', 175, 14, 5),
(1403, '230505', '*เกาะหมาก   ', 175, 14, 5),
(1404, '230506', '*เกาะกูด   ', 175, 14, 5),
(1405, '230507', 'คลองใหญ่   ', 175, 14, 5),
(1406, '230508', '*เกาะช้างใต้   ', 175, 14, 5),
(1407, '230601', 'เกาะหมาก   ', 176, 14, 5),
(1408, '230602', 'เกาะกูด   ', 176, 14, 5),
(1409, '230701', 'เกาะช้าง   ', 177, 14, 5),
(1410, '230702', 'เกาะช้างใต้   ', 177, 14, 5),
(1411, '240101', 'หน้าเมือง   ', 178, 15, 5),
(1412, '240102', 'ท่าไข่   ', 178, 15, 5),
(1413, '240103', 'บ้านใหม่   ', 178, 15, 5),
(1414, '240104', 'คลองนา   ', 178, 15, 5),
(1415, '240105', 'บางตีนเป็ด   ', 178, 15, 5),
(1416, '240106', 'บางไผ่   ', 178, 15, 5),
(1417, '240107', 'คลองจุกกระเฌอ   ', 178, 15, 5),
(1418, '240108', 'บางแก้ว   ', 178, 15, 5),
(1419, '240109', 'บางขวัญ   ', 178, 15, 5),
(1420, '240110', 'คลองนครเนื่องเขต   ', 178, 15, 5),
(1421, '240111', 'วังตะเคียน   ', 178, 15, 5),
(1422, '240112', 'โสธร   ', 178, 15, 5),
(1423, '240113', 'บางพระ   ', 178, 15, 5),
(1424, '240114', 'บางกะไห   ', 178, 15, 5),
(1425, '240115', 'หนามแดง   ', 178, 15, 5),
(1426, '240116', 'คลองเปรง   ', 178, 15, 5),
(1427, '240117', 'คลองอุดมชลจร   ', 178, 15, 5),
(1428, '240118', 'คลองหลวงแพ่ง   ', 178, 15, 5),
(1429, '240119', 'บางเตย   ', 178, 15, 5),
(1430, '240201', 'บางคล้า   ', 179, 15, 5),
(1431, '240202', '*ก้อนแก้ว   ', 179, 15, 5),
(1432, '240203', '*คลองเขื่อน   ', 179, 15, 5),
(1433, '240204', 'บางสวน   ', 179, 15, 5),
(1434, '240205', '*บางเล่า   ', 179, 15, 5),
(1435, '240206', '*บางโรง   ', 179, 15, 5),
(1436, '240207', '*บางตลาด   ', 179, 15, 5),
(1437, '240208', 'บางกระเจ็ด   ', 179, 15, 5),
(1438, '240209', 'ปากน้ำ   ', 179, 15, 5),
(1439, '240210', 'ท่าทองหลาง   ', 179, 15, 5),
(1440, '240211', 'สาวชะโงก   ', 179, 15, 5),
(1441, '240212', 'เสม็ดเหนือ   ', 179, 15, 5),
(1442, '240213', 'เสม็ดใต้   ', 179, 15, 5),
(1443, '240214', 'หัวไทร   ', 179, 15, 5),
(1444, '240301', 'บางน้ำเปรี้ยว   ', 180, 15, 5),
(1445, '240302', 'บางขนาก   ', 180, 15, 5),
(1446, '240303', 'สิงโตทอง   ', 180, 15, 5),
(1447, '240304', 'หมอนทอง   ', 180, 15, 5),
(1448, '240305', 'บึงน้ำรักษ์   ', 180, 15, 5),
(1449, '240306', 'ดอนเกาะกา   ', 180, 15, 5),
(1450, '240307', 'โยธะกา   ', 180, 15, 5),
(1451, '240308', 'ดอนฉิมพลี   ', 180, 15, 5),
(1452, '240309', 'ศาลาแดง   ', 180, 15, 5),
(1453, '240310', 'โพรงอากาศ   ', 180, 15, 5),
(1454, '240401', 'บางปะกง   ', 181, 15, 5),
(1455, '240402', 'ท่าสะอ้าน   ', 181, 15, 5),
(1456, '240403', 'บางวัว   ', 181, 15, 5),
(1457, '240404', 'บางสมัคร   ', 181, 15, 5),
(1458, '240405', 'บางผึ้ง   ', 181, 15, 5),
(1459, '240406', 'บางเกลือ   ', 181, 15, 5),
(1460, '240407', 'สองคลอง   ', 181, 15, 5),
(1461, '240408', 'หนองจอก   ', 181, 15, 5),
(1462, '240409', 'พิมพา   ', 181, 15, 5),
(1463, '240410', 'ท่าข้าม   ', 181, 15, 5),
(1464, '240411', 'หอมศีล   ', 181, 15, 5),
(1465, '240412', 'เขาดิน   ', 181, 15, 5),
(1466, '240501', 'บ้านโพธิ์   ', 182, 15, 5),
(1467, '240502', 'เกาะไร่   ', 182, 15, 5),
(1468, '240503', 'คลองขุด   ', 182, 15, 5),
(1469, '240504', 'คลองบ้านโพธิ์   ', 182, 15, 5),
(1470, '240505', 'คลองประเวศ   ', 182, 15, 5),
(1471, '240506', 'ดอนทราย   ', 182, 15, 5),
(1472, '240507', 'เทพราช   ', 182, 15, 5),
(1473, '240508', 'ท่าพลับ   ', 182, 15, 5),
(1474, '240509', 'หนองตีนนก   ', 182, 15, 5),
(1475, '240510', 'หนองบัว   ', 182, 15, 5),
(1476, '240511', 'บางซ่อน   ', 182, 15, 5),
(1477, '240512', 'บางกรูด   ', 182, 15, 5),
(1478, '240513', 'แหลมประดู่   ', 182, 15, 5),
(1479, '240514', 'ลาดขวาง   ', 182, 15, 5),
(1480, '240515', 'สนามจันทร์   ', 182, 15, 5),
(1481, '240516', 'แสนภูดาษ   ', 182, 15, 5),
(1482, '240517', 'สิบเอ็ดศอก   ', 182, 15, 5),
(1483, '240601', 'เกาะขนุน   ', 183, 15, 5),
(1484, '240602', 'บ้านซ่อง   ', 183, 15, 5),
(1485, '240603', 'พนมสารคาม   ', 183, 15, 5),
(1486, '240604', 'เมืองเก่า   ', 183, 15, 5),
(1487, '240605', 'หนองยาว   ', 183, 15, 5),
(1488, '240606', 'ท่าถ่าน   ', 183, 15, 5),
(1489, '240607', 'หนองแหน   ', 183, 15, 5),
(1490, '240608', 'เขาหินซ้อน   ', 183, 15, 5),
(1491, '240701', 'บางคา   ', 184, 15, 5),
(1492, '240702', 'เมืองใหม่   ', 184, 15, 5),
(1493, '240703', 'ดงน้อย   ', 184, 15, 5),
(1494, '240801', 'คู้ยายหมี   ', 185, 15, 5),
(1495, '240802', 'ท่ากระดาน   ', 185, 15, 5),
(1496, '240803', 'ทุ่งพระยา   ', 185, 15, 5),
(1497, '240804', '*ท่าตะเกียบ   ', 185, 15, 5),
(1498, '240805', 'ลาดกระทิง   ', 185, 15, 5),
(1499, '240806', '*คลองตะเกรา   ', 185, 15, 5),
(1500, '240901', 'แปลงยาว   ', 186, 15, 5),
(1501, '240902', 'วังเย็น   ', 186, 15, 5),
(1502, '240903', 'หัวสำโรง   ', 186, 15, 5),
(1503, '240904', 'หนองไม้แก่น   ', 186, 15, 5),
(1504, '241001', 'ท่าตะเกียบ   ', 187, 15, 5),
(1505, '241002', 'คลองตะเกรา   ', 187, 15, 5),
(1506, '241101', 'ก้อนแก้ว   ', 188, 15, 5),
(1507, '241102', 'คลองเขื่อน   ', 188, 15, 5),
(1508, '241103', 'บางเล่า   ', 188, 15, 5),
(1509, '241104', 'บางโรง   ', 188, 15, 5),
(1510, '241105', 'บางตลาด   ', 188, 15, 5),
(1511, '250101', 'หน้าเมือง   ', 189, 16, 5),
(1512, '250102', 'รอบเมือง   ', 189, 16, 5),
(1513, '250103', 'วัดโบสถ์   ', 189, 16, 5),
(1514, '250104', 'บางเดชะ   ', 189, 16, 5),
(1515, '250105', 'ท่างาม   ', 189, 16, 5),
(1516, '250106', 'บางบริบูรณ์   ', 189, 16, 5),
(1517, '250107', 'ดงพระราม   ', 189, 16, 5),
(1518, '250108', 'บ้านพระ   ', 189, 16, 5),
(1519, '250109', 'โคกไม้ลาย   ', 189, 16, 5),
(1520, '250110', 'ไม้เค็ด   ', 189, 16, 5),
(1521, '250111', 'ดงขี้เหล็ก   ', 189, 16, 5),
(1522, '250112', 'เนินหอม   ', 189, 16, 5),
(1523, '250113', 'โนนห้อม   ', 189, 16, 5),
(1524, '250201', 'กบินทร์   ', 190, 16, 5),
(1525, '250202', 'เมืองเก่า   ', 190, 16, 5),
(1526, '250203', 'วังดาล   ', 190, 16, 5),
(1527, '250204', 'นนทรี   ', 190, 16, 5),
(1528, '250205', 'ย่านรี   ', 190, 16, 5),
(1529, '250206', 'วังตะเคียน   ', 190, 16, 5),
(1530, '250207', 'หาดนางแก้ว   ', 190, 16, 5),
(1531, '250208', 'ลาดตะเคียน   ', 190, 16, 5),
(1532, '250209', 'บ้านนา   ', 190, 16, 5),
(1533, '250210', 'บ่อทอง   ', 190, 16, 5),
(1534, '250211', 'หนองกี่   ', 190, 16, 5),
(1535, '250212', 'นาแขม   ', 190, 16, 5),
(1536, '250213', 'เขาไม้แก้ว   ', 190, 16, 5),
(1537, '250214', 'วังท่าช้าง   ', 190, 16, 5),
(1538, '250296', '*สะพานหิน   ', 190, 16, 5),
(1539, '250297', '*นาดี   ', 190, 16, 5),
(1540, '250298', '*ลำพันตา   ', 190, 16, 5),
(1541, '250299', '*ทุ่งโพธิ์   ', 190, 16, 5),
(1542, '250301', 'นาดี   ', 191, 16, 5),
(1543, '250302', 'สำพันตา   ', 191, 16, 5),
(1544, '250303', 'สะพานหิน   ', 191, 16, 5),
(1545, '250304', 'ทุ่งโพธิ์   ', 191, 16, 5),
(1546, '250305', 'แก่งดินสอ   ', 191, 16, 5),
(1547, '250306', 'บุพราหมณ์   ', 191, 16, 5),
(1548, '250401', '*สระแก้ว   ', 192, 16, 5),
(1549, '250402', '*บ้านแก้ง   ', 192, 16, 5),
(1550, '250403', '*ศาลาลำดวน   ', 192, 16, 5),
(1551, '250404', '*โคกปี่ฆ้อง   ', 192, 16, 5),
(1552, '250405', '*ท่าแยก   ', 192, 16, 5),
(1553, '250406', '*ท่าเกษม   ', 192, 16, 5),
(1554, '250407', '*เขาฉกรรจ์   ', 192, 16, 5),
(1555, '250408', '*สระขวัญ   ', 192, 16, 5),
(1556, '250409', '*หนองหว้า   ', 192, 16, 5),
(1557, '250410', '*พระเพลิง   ', 192, 16, 5),
(1558, '250411', '*หนองบอน   ', 192, 16, 5),
(1559, '250412', '*เขาสามสิบ   ', 192, 16, 5),
(1560, '250497', '*ตาหลังใน   ', 192, 16, 5),
(1561, '250498', '*วังสมบูรณ์   ', 192, 16, 5),
(1562, '250499', '*วังน้ำเย็น   ', 192, 16, 5),
(1563, '250501', '*วังน้ำเย็น   ', 193, 16, 5),
(1564, '250502', '*วังสมบูรณ์   ', 193, 16, 5),
(1565, '250503', '*ตาหลังใน   ', 193, 16, 5),
(1566, '250504', '*วังใหม่   ', 193, 16, 5),
(1567, '250505', '*คลองหินปูน   ', 193, 16, 5),
(1568, '250506', '*ทุ่งมหาเจริญ   ', 193, 16, 5),
(1569, '250601', 'บ้านสร้าง   ', 194, 16, 5),
(1570, '250602', 'บางกระเบา   ', 194, 16, 5),
(1571, '250603', 'บางเตย   ', 194, 16, 5),
(1572, '250604', 'บางยาง   ', 194, 16, 5),
(1573, '250605', 'บางแตน   ', 194, 16, 5),
(1574, '250606', 'บางพลวง   ', 194, 16, 5),
(1575, '250607', 'บางปลาร้า   ', 194, 16, 5),
(1576, '250608', 'บางขาม   ', 194, 16, 5),
(1577, '250609', 'กระทุ่มแพ้ว   ', 194, 16, 5),
(1578, '250701', 'ประจันตคาม   ', 195, 16, 5),
(1579, '250702', 'เกาะลอย   ', 195, 16, 5),
(1580, '250703', 'บ้านหอย   ', 195, 16, 5),
(1581, '250704', 'หนองแสง   ', 195, 16, 5),
(1582, '250705', 'ดงบัง   ', 195, 16, 5),
(1583, '250706', 'คำโตนด   ', 195, 16, 5),
(1584, '250707', 'บุฝ้าย   ', 195, 16, 5),
(1585, '250708', 'หนองแก้ว   ', 195, 16, 5),
(1586, '250709', 'โพธิ์งาม   ', 195, 16, 5),
(1587, '250801', 'ศรีมหาโพธิ   ', 196, 16, 5),
(1588, '250802', 'สัมพันธ์   ', 196, 16, 5),
(1589, '250803', 'บ้านทาม   ', 196, 16, 5),
(1590, '250804', 'ท่าตูม   ', 196, 16, 5),
(1591, '250805', 'บางกุ้ง   ', 196, 16, 5),
(1592, '250806', 'ดงกระทงยาม   ', 196, 16, 5),
(1593, '250807', 'หนองโพรง   ', 196, 16, 5),
(1594, '250808', 'หัวหว้า   ', 196, 16, 5),
(1595, '250809', 'หาดยาง   ', 196, 16, 5),
(1596, '250810', 'กรอกสมบูรณ์   ', 196, 16, 5),
(1597, '250896', '*คู้ลำพัน   ', 196, 16, 5),
(1598, '250897', '*โคกปีบ   ', 196, 16, 5),
(1599, '250898', '*โคกไทย   ', 196, 16, 5),
(1600, '250899', '*ไผ่ชะเลือด   ', 196, 16, 5),
(1601, '250901', 'โคกปีบ   ', 197, 16, 5),
(1602, '250902', 'โคกไทย   ', 197, 16, 5),
(1603, '250903', 'คู้ลำพัน   ', 197, 16, 5),
(1604, '250904', 'ไผ่ชะเลือด   ', 197, 16, 5),
(1605, '251001', '*อรัญประเทศ   ', 198, 16, 5),
(1606, '251002', '*เมืองไผ่   ', 198, 16, 5),
(1607, '251003', '*หันทราย   ', 198, 16, 5),
(1608, '251004', '*คลองน้ำใส   ', 198, 16, 5),
(1609, '251005', '*ท่าข้าม   ', 198, 16, 5),
(1610, '251006', '*ป่าไร่   ', 198, 16, 5),
(1611, '251007', '*ทับพริก   ', 198, 16, 5),
(1612, '251008', '*บ้านใหม่หนองไทร   ', 198, 16, 5),
(1613, '251009', '*ผ่านศึก   ', 198, 16, 5),
(1614, '251010', '*หนองสังข์   ', 198, 16, 5),
(1615, '251011', '*คลองทับจันทร์   ', 198, 16, 5),
(1616, '251012', '*ฟากห้วย   ', 198, 16, 5),
(1617, '251013', '*บ้านด่าน   ', 198, 16, 5),
(1618, '251101', '*ตาพระยา   ', 199, 16, 5),
(1619, '251102', '*ทัพเสด็จ   ', 199, 16, 5),
(1620, '251103', '*โคกสูง   ', 199, 16, 5),
(1621, '251104', '*หนองแวง   ', 199, 16, 5),
(1622, '251105', '*หนองม่วง   ', 199, 16, 5),
(1623, '251106', '*ทัพราช   ', 199, 16, 5),
(1624, '251107', '*ทัพไทย   ', 199, 16, 5),
(1625, '251108', '*โนนหมากมุ่น   ', 199, 16, 5),
(1626, '251109', '*โคคลาน   ', 199, 16, 5),
(1627, '251201', '*วัฒนานคร   ', 200, 16, 5),
(1628, '251202', '*ท่าเกวียน   ', 200, 16, 5),
(1629, '251203', '*ซับมะกรูด   ', 200, 16, 5),
(1630, '251204', '*ผักขะ   ', 200, 16, 5),
(1631, '251205', '*โนนหมากเค็ง   ', 200, 16, 5),
(1632, '251206', '*หนองน้ำใส   ', 200, 16, 5),
(1633, '251207', '*ช่องกุ่ม   ', 200, 16, 5),
(1634, '251208', '*หนองแวง   ', 200, 16, 5),
(1635, '251209', '*ไทยอุดม   ', 200, 16, 5),
(1636, '251210', '*ไทรเดี่ยว   ', 200, 16, 5),
(1637, '251211', '*คลองหาด   ', 200, 16, 5),
(1638, '251212', '*แซร์ออ   ', 200, 16, 5),
(1639, '251213', '*หนองหมากฝ้าย   ', 200, 16, 5),
(1640, '251214', '*หนองตะเคียนบอน   ', 200, 16, 5),
(1641, '251215', '*ห้วยโจด   ', 200, 16, 5),
(1642, '251301', '*คลองหาด   ', 201, 16, 5),
(1643, '251302', '*ไทยอุดม   ', 201, 16, 5),
(1644, '251303', '*ซับมะกรูด   ', 201, 16, 5),
(1645, '251304', '*ไทรเดี่ยว   ', 201, 16, 5),
(1646, '251305', '*คลองไก่เถื่อน   ', 201, 16, 5),
(1647, '251306', '*เบญจขร   ', 201, 16, 5),
(1648, '251307', '*ไทรทอง   ', 201, 16, 5),
(1649, '260101', 'นครนายก   ', 202, 17, 2),
(1650, '260102', 'ท่าช้าง   ', 202, 17, 2),
(1651, '260103', 'บ้านใหญ่   ', 202, 17, 2),
(1652, '260104', 'วังกระโจม   ', 202, 17, 2),
(1653, '260105', 'ท่าทราย   ', 202, 17, 2),
(1654, '260106', 'ดอนยอ   ', 202, 17, 2),
(1655, '260107', 'ศรีจุฬา   ', 202, 17, 2),
(1656, '260108', 'ดงละคร   ', 202, 17, 2),
(1657, '260109', 'ศรีนาวา   ', 202, 17, 2),
(1658, '260110', 'สาริกา   ', 202, 17, 2),
(1659, '260111', 'หินตั้ง   ', 202, 17, 2),
(1660, '260112', 'เขาพระ   ', 202, 17, 2),
(1661, '260113', 'พรหมณี   ', 202, 17, 2),
(1662, '260201', 'เกาะหวาย   ', 203, 17, 2),
(1663, '260202', 'เกาะโพธิ์   ', 203, 17, 2),
(1664, '260203', 'ปากพลี   ', 203, 17, 2),
(1665, '260204', 'โคกกรวด   ', 203, 17, 2),
(1666, '260205', 'ท่าเรือ   ', 203, 17, 2),
(1667, '260206', 'หนองแสง   ', 203, 17, 2),
(1668, '260207', 'นาหินลาด   ', 203, 17, 2),
(1669, '260301', 'บ้านนา   ', 204, 17, 2),
(1670, '260302', 'บ้านพร้าว   ', 204, 17, 2),
(1671, '260303', 'บ้านพริก   ', 204, 17, 2),
(1672, '260304', 'อาษา   ', 204, 17, 2),
(1673, '260305', 'ทองหลาง   ', 204, 17, 2),
(1674, '260306', 'บางอ้อ   ', 204, 17, 2),
(1675, '260307', 'พิกุลออก   ', 204, 17, 2),
(1676, '260308', 'ป่าขะ   ', 204, 17, 2),
(1677, '260309', 'เขาเพิ่ม   ', 204, 17, 2),
(1678, '260310', 'ศรีกะอาง   ', 204, 17, 2),
(1679, '260401', 'พระอาจารย์   ', 205, 17, 2),
(1680, '260402', 'บึงศาล   ', 205, 17, 2),
(1681, '260403', 'ศีรษะกระบือ   ', 205, 17, 2),
(1682, '260404', 'โพธิ์แทน   ', 205, 17, 2),
(1683, '260405', 'บางสมบูรณ์   ', 205, 17, 2),
(1684, '260406', 'ทรายมูล   ', 205, 17, 2),
(1685, '260407', 'บางปลากด   ', 205, 17, 2),
(1686, '260408', 'บางลูกเสือ   ', 205, 17, 2),
(1687, '260409', 'องครักษ์   ', 205, 17, 2),
(1688, '260410', 'ชุมพล   ', 205, 17, 2),
(1689, '260411', 'คลองใหญ่   ', 205, 17, 2),
(1690, '270101', 'สระแก้ว   ', 206, 18, 5),
(1691, '270102', 'บ้านแก้ง   ', 206, 18, 5),
(1692, '270103', 'ศาลาลำดวน   ', 206, 18, 5),
(1693, '270104', 'โคกปี่ฆ้อง   ', 206, 18, 5),
(1694, '270105', 'ท่าแยก   ', 206, 18, 5),
(1695, '270106', 'ท่าเกษม   ', 206, 18, 5),
(1696, '270107', '*เขาฉกรรจ์   ', 206, 18, 5),
(1697, '270108', 'สระขวัญ   ', 206, 18, 5),
(1698, '270109', '*หนองหว้า   ', 206, 18, 5),
(1699, '270110', '*พระเพลิง   ', 206, 18, 5),
(1700, '270111', 'หนองบอน   ', 206, 18, 5),
(1701, '270112', '*เขาสามสิบ   ', 206, 18, 5),
(1702, '270201', 'คลองหาด   ', 207, 18, 5),
(1703, '270202', 'ไทยอุดม   ', 207, 18, 5),
(1704, '270203', 'ซับมะกรูด   ', 207, 18, 5),
(1705, '270204', 'ไทรเดี่ยว   ', 207, 18, 5),
(1706, '270205', 'คลองไก่เถื่อน   ', 207, 18, 5),
(1707, '270206', 'เบญจขร   ', 207, 18, 5),
(1708, '270207', 'ไทรทอง   ', 207, 18, 5),
(1709, '270301', 'ตาพระยา   ', 208, 18, 5),
(1710, '270302', 'ทัพเสด็จ   ', 208, 18, 5),
(1711, '270303', 'โคกสูง*   ', 208, 18, 5),
(1712, '270304', 'หนองแวง*   ', 208, 18, 5),
(1713, '270305', 'หนองม่วง*   ', 208, 18, 5),
(1714, '270306', 'ทัพราช   ', 208, 18, 5),
(1715, '270307', 'ทัพไทย   ', 208, 18, 5),
(1716, '270308', 'โนนหมากมุ่น*   ', 208, 18, 5),
(1717, '270309', 'โคคลาน   ', 208, 18, 5),
(1718, '270401', 'วังน้ำเย็น   ', 209, 18, 5),
(1719, '270402', 'วังสมบูรณ์*   ', 209, 18, 5),
(1720, '270403', 'ตาหลังใน   ', 209, 18, 5),
(1721, '270404', 'วังใหม่*   ', 209, 18, 5),
(1722, '270405', 'คลองหินปูน   ', 209, 18, 5),
(1723, '270406', 'ทุ่งมหาเจริญ   ', 209, 18, 5),
(1724, '270407', 'วังทอง*   ', 209, 18, 5),
(1725, '270501', 'วัฒนานคร   ', 210, 18, 5),
(1726, '270502', 'ท่าเกวียน   ', 210, 18, 5),
(1727, '270503', 'ผักขะ   ', 210, 18, 5),
(1728, '270504', 'โนนหมากเค็ง   ', 210, 18, 5),
(1729, '270505', 'หนองน้ำใส   ', 210, 18, 5),
(1730, '270506', 'ช่องกุ่ม   ', 210, 18, 5),
(1731, '270507', 'หนองแวง   ', 210, 18, 5),
(1732, '270508', 'แซร์ออ   ', 210, 18, 5),
(1733, '270509', 'หนองหมากฝ้าย   ', 210, 18, 5),
(1734, '270510', 'หนองตะเคียนบอน   ', 210, 18, 5),
(1735, '270511', 'ห้วยโจด   ', 210, 18, 5),
(1736, '270601', 'อรัญประเทศ   ', 211, 18, 5),
(1737, '270602', 'เมืองไผ่   ', 211, 18, 5),
(1738, '270603', 'หันทราย   ', 211, 18, 5),
(1739, '270604', 'คลองน้ำใส   ', 211, 18, 5),
(1740, '270605', 'ท่าข้าม   ', 211, 18, 5),
(1741, '270606', 'ป่าไร่   ', 211, 18, 5),
(1742, '270607', 'ทับพริก   ', 211, 18, 5),
(1743, '270608', 'บ้านใหม่หนองไทร   ', 211, 18, 5),
(1744, '270609', 'ผ่านศึก   ', 211, 18, 5),
(1745, '270610', 'หนองสังข์   ', 211, 18, 5),
(1746, '270611', 'คลองทับจันทร์   ', 211, 18, 5),
(1747, '270612', 'ฟากห้วย   ', 211, 18, 5),
(1748, '270613', 'บ้านด่าน   ', 211, 18, 5),
(1749, '270701', 'เขาฉกรรจ์   ', 212, 18, 5),
(1750, '270702', 'หนองหว้า   ', 212, 18, 5),
(1751, '270703', 'พระเพลิง   ', 212, 18, 5),
(1752, '270704', 'เขาสามสิบ   ', 212, 18, 5),
(1753, '270801', 'โคกสูง   ', 213, 18, 5),
(1754, '270802', 'หนองม่วง   ', 213, 18, 5),
(1755, '270803', 'หนองแวง   ', 213, 18, 5),
(1756, '270804', 'โนนหมากมุ่น   ', 213, 18, 5),
(1757, '270901', 'วังสมบูรณ์   ', 214, 18, 5),
(1758, '270902', 'วังใหม่   ', 214, 18, 5),
(1759, '270903', 'วังทอง   ', 214, 18, 5),
(1760, '300101', 'ในเมือง   ', 215, 19, 3),
(1761, '300102', 'โพธิ์กลาง   ', 215, 19, 3),
(1762, '300103', 'หนองจะบก   ', 215, 19, 3),
(1763, '300104', 'โคกสูง   ', 215, 19, 3),
(1764, '300105', 'มะเริง   ', 215, 19, 3),
(1765, '300106', 'หนองระเวียง   ', 215, 19, 3),
(1766, '300107', 'ปรุใหญ่   ', 215, 19, 3),
(1767, '300108', 'หมื่นไวย   ', 215, 19, 3),
(1768, '300109', 'พลกรัง   ', 215, 19, 3),
(1769, '300110', 'หนองไผ่ล้อม   ', 215, 19, 3),
(1770, '300111', 'หัวทะเล   ', 215, 19, 3),
(1771, '300112', 'บ้านเกาะ   ', 215, 19, 3),
(1772, '300113', 'บ้านใหม่   ', 215, 19, 3),
(1773, '300114', 'พุดซา   ', 215, 19, 3),
(1774, '300115', 'บ้านโพธิ์   ', 215, 19, 3),
(1775, '300116', 'จอหอ   ', 215, 19, 3),
(1776, '300117', 'โคกกรวด   ', 215, 19, 3),
(1777, '300118', 'ไชยมงคล   ', 215, 19, 3),
(1778, '300119', 'หนองบัวศาลา   ', 215, 19, 3),
(1779, '300120', 'สุรนารี   ', 215, 19, 3),
(1780, '300121', 'สีมุม   ', 215, 19, 3),
(1781, '300122', 'ตลาด   ', 215, 19, 3),
(1782, '300123', 'พะเนา   ', 215, 19, 3),
(1783, '300124', 'หนองกระทุ่ม   ', 215, 19, 3),
(1784, '300125', 'หนองไข่น้ำ   ', 215, 19, 3),
(1785, '300201', 'แชะ   ', 216, 19, 3),
(1786, '300202', 'เฉลียง   ', 216, 19, 3),
(1787, '300203', 'ครบุรี   ', 216, 19, 3),
(1788, '300204', 'โคกกระชาย   ', 216, 19, 3),
(1789, '300205', 'จระเข้หิน   ', 216, 19, 3),
(1790, '300206', 'มาบตะโกเอน   ', 216, 19, 3),
(1791, '300207', 'อรพิมพ์   ', 216, 19, 3),
(1792, '300208', 'บ้านใหม่   ', 216, 19, 3),
(1793, '300209', 'ลำเพียก   ', 216, 19, 3),
(1794, '300210', 'ครบุรีใต้   ', 216, 19, 3),
(1795, '300211', 'ตะแบกบาน   ', 216, 19, 3),
(1796, '300212', 'สระว่านพระยา   ', 216, 19, 3),
(1797, '300301', 'เสิงสาง   ', 217, 19, 3),
(1798, '300302', 'สระตะเคียน   ', 217, 19, 3),
(1799, '300303', 'โนนสมบูรณ์   ', 217, 19, 3),
(1800, '300304', 'กุดโบสถ์   ', 217, 19, 3),
(1801, '300305', 'สุขไพบูลย์   ', 217, 19, 3),
(1802, '300306', 'บ้านราษฎร์   ', 217, 19, 3),
(1803, '300401', 'เมืองคง   ', 218, 19, 3),
(1804, '300402', 'คูขาด   ', 218, 19, 3),
(1805, '300403', 'เทพาลัย   ', 218, 19, 3),
(1806, '300404', 'ตาจั่น   ', 218, 19, 3),
(1807, '300405', 'บ้านปรางค์   ', 218, 19, 3),
(1808, '300406', 'หนองมะนาว   ', 218, 19, 3),
(1809, '300407', 'หนองบัว   ', 218, 19, 3),
(1810, '300408', 'โนนเต็ง   ', 218, 19, 3),
(1811, '300409', 'ดอนใหญ่   ', 218, 19, 3),
(1812, '300410', 'ขามสมบูรณ์   ', 218, 19, 3),
(1813, '300501', 'บ้านเหลื่อม   ', 219, 19, 3),
(1814, '300502', 'วังโพธิ์   ', 219, 19, 3),
(1815, '300503', 'โคกกระเบื้อง   ', 219, 19, 3),
(1816, '300504', 'ช่อระกา   ', 219, 19, 3),
(1817, '300601', 'จักราช   ', 220, 19, 3),
(1818, '300602', 'ท่าช้าง   ', 220, 19, 3),
(1819, '300603', 'ทองหลาง   ', 220, 19, 3),
(1820, '300604', 'สีสุก   ', 220, 19, 3),
(1821, '300605', 'หนองขาม   ', 220, 19, 3),
(1822, '300606', 'หนองงูเหลือม   ', 220, 19, 3),
(1823, '300607', 'หนองพลวง   ', 220, 19, 3),
(1824, '300608', 'หนองยาง   ', 220, 19, 3),
(1825, '300609', 'พระพุทธ   ', 220, 19, 3),
(1826, '300610', 'ศรีละกอ   ', 220, 19, 3),
(1827, '300611', 'คลองเมือง   ', 220, 19, 3),
(1828, '300612', 'ช้างทอง   ', 220, 19, 3),
(1829, '300613', 'หินโคน   ', 220, 19, 3),
(1830, '300701', 'กระโทก   ', 221, 19, 3),
(1831, '300702', 'พลับพลา   ', 221, 19, 3),
(1832, '300703', 'ท่าอ่าง   ', 221, 19, 3),
(1833, '300704', 'ทุ่งอรุณ   ', 221, 19, 3),
(1834, '300705', 'ท่าลาดขาว   ', 221, 19, 3),
(1835, '300706', 'ท่าจะหลุง   ', 221, 19, 3),
(1836, '300707', 'ท่าเยี่ยม   ', 221, 19, 3),
(1837, '300708', 'โชคชัย   ', 221, 19, 3),
(1838, '300709', 'ละลมใหม่พัฒนา   ', 221, 19, 3),
(1839, '300710', 'ด่านเกวียน   ', 221, 19, 3),
(1840, '300801', 'กุดพิมาน   ', 222, 19, 3),
(1841, '300802', 'ด่านขุนทด   ', 222, 19, 3),
(1842, '300803', 'ด่านนอก   ', 222, 19, 3),
(1843, '300804', 'ด่านใน   ', 222, 19, 3),
(1844, '300805', 'ตะเคียน   ', 222, 19, 3),
(1845, '300806', 'บ้านเก่า   ', 222, 19, 3),
(1846, '300807', 'บ้านแปรง   ', 222, 19, 3),
(1847, '300808', 'พันชนะ   ', 222, 19, 3),
(1848, '300809', 'สระจรเข้   ', 222, 19, 3),
(1849, '300810', 'หนองกราด   ', 222, 19, 3),
(1850, '300811', 'หนองบัวตะเกียด   ', 222, 19, 3),
(1851, '300812', 'หนองบัวละคร   ', 222, 19, 3),
(1852, '300813', 'หินดาด   ', 222, 19, 3),
(1853, '300814', '*สำนักตะคร้อ   ', 222, 19, 3),
(1854, '300815', 'ห้วยบง   ', 222, 19, 3),
(1855, '300816', '*หนองแวง   ', 222, 19, 3),
(1856, '300817', 'โนนเมืองพัฒนา   ', 222, 19, 3),
(1857, '300818', 'หนองไทร   ', 222, 19, 3),
(1858, '300819', '*บึงปรือ   ', 222, 19, 3),
(1859, '300901', 'โนนไทย   ', 223, 19, 3),
(1860, '300902', 'ด่านจาก   ', 223, 19, 3),
(1861, '300903', 'กำปัง   ', 223, 19, 3),
(1862, '300904', 'สำโรง   ', 223, 19, 3),
(1863, '300905', 'ค้างพลู   ', 223, 19, 3),
(1864, '300906', 'บ้านวัง   ', 223, 19, 3),
(1865, '300907', 'บัลลังก์   ', 223, 19, 3),
(1866, '300908', 'สายออ   ', 223, 19, 3),
(1867, '300909', 'ถนนโพธิ์   ', 223, 19, 3),
(1868, '300910', 'พังเทียม   ', 223, 19, 3),
(1869, '300911', 'สระพระ   ', 223, 19, 3),
(1870, '300912', 'ทัพรั้ง   ', 223, 19, 3),
(1871, '300913', 'หนองหอย   ', 223, 19, 3),
(1872, '300914', 'มะค่า   ', 223, 19, 3),
(1873, '300915', 'มาบกราด   ', 223, 19, 3),
(1874, '301001', 'โนนสูง   ', 224, 19, 3),
(1875, '301002', 'ใหม่   ', 224, 19, 3),
(1876, '301003', 'โตนด   ', 224, 19, 3),
(1877, '301004', 'บิง   ', 224, 19, 3),
(1878, '301005', 'ดอนชมพู   ', 224, 19, 3),
(1879, '301006', 'ธารปราสาท   ', 224, 19, 3),
(1880, '301007', 'หลุมข้าว   ', 224, 19, 3),
(1881, '301008', 'มะค่า   ', 224, 19, 3),
(1882, '301009', 'พลสงคราม   ', 224, 19, 3),
(1883, '301010', 'จันอัด   ', 224, 19, 3),
(1884, '301011', 'ขามเฒ่า   ', 224, 19, 3),
(1885, '301012', 'ด่านคล้า   ', 224, 19, 3),
(1886, '301013', 'ลำคอหงษ์   ', 224, 19, 3),
(1887, '301014', 'เมืองปราสาท   ', 224, 19, 3),
(1888, '301015', 'ดอนหวาย   ', 224, 19, 3),
(1889, '301016', 'ลำมูล   ', 224, 19, 3),
(1890, '301101', 'ขามสะแกแสง   ', 225, 19, 3),
(1891, '301102', 'โนนเมือง   ', 225, 19, 3),
(1892, '301103', 'เมืองนาท   ', 225, 19, 3),
(1893, '301104', 'ชีวึก   ', 225, 19, 3),
(1894, '301105', 'พะงาด   ', 225, 19, 3),
(1895, '301106', 'หนองหัวฟาน   ', 225, 19, 3),
(1896, '301107', 'เมืองเกษตร   ', 225, 19, 3),
(1897, '301201', 'บัวใหญ่   ', 226, 19, 3),
(1898, '301203', 'ห้วยยาง   ', 226, 19, 3),
(1899, '301204', 'เสมาใหญ่   ', 226, 19, 3),
(1900, '301205', '*บึงพะไล   ', 226, 19, 3),
(1901, '301206', 'ดอนตะหนิน   ', 226, 19, 3),
(1902, '301207', 'หนองบัวสะอาด   ', 226, 19, 3),
(1903, '301208', 'โนนทองหลาง   ', 226, 19, 3),
(1904, '301209', 'หนองหว้า   ', 226, 19, 3),
(1905, '301210', 'บัวลาย   ', 226, 19, 3),
(1906, '301211', 'สีดา   ', 226, 19, 3),
(1907, '301212', 'โพนทอง   ', 226, 19, 3),
(1908, '301213', '*แก้งสนามนาง   ', 226, 19, 3),
(1909, '301214', 'กุดจอก   ', 226, 19, 3),
(1910, '301215', 'ด่านช้าง   ', 226, 19, 3),
(1911, '301216', 'โนนจาน   ', 226, 19, 3),
(1912, '301217', '*สีสุก   ', 226, 19, 3),
(1913, '301218', 'สามเมือง   ', 226, 19, 3),
(1914, '301219', '*โนนสำราญ   ', 226, 19, 3),
(1915, '301220', 'ขุนทอง   ', 226, 19, 3),
(1916, '301221', 'หนองตาดใหญ่   ', 226, 19, 3),
(1917, '301222', 'เมืองพะไล   ', 226, 19, 3),
(1918, '301223', 'โนนประดู่   ', 226, 19, 3),
(1919, '301224', 'หนองแจ้งใหญ่   ', 226, 19, 3),
(1920, '301301', 'ประทาย   ', 227, 19, 3),
(1921, '301302', '*โนนแดง   ', 227, 19, 3),
(1922, '301303', 'กระทุ่มราย   ', 227, 19, 3),
(1923, '301304', 'วังไม้แดง   ', 227, 19, 3),
(1924, '301305', '*วังหิน   ', 227, 19, 3),
(1925, '301306', 'ตลาดไทร   ', 227, 19, 3),
(1926, '301307', 'หนองพลวง   ', 227, 19, 3),
(1927, '301308', 'หนองค่าย   ', 227, 19, 3),
(1928, '301309', 'หันห้วยทราย   ', 227, 19, 3),
(1929, '301310', 'ดอนมัน   ', 227, 19, 3),
(1930, '301311', '*โนนตาเถร   ', 227, 19, 3),
(1931, '301312', '*สำพะเนียง   ', 227, 19, 3),
(1932, '301313', 'นางรำ   ', 227, 19, 3),
(1933, '301314', 'โนนเพ็ด   ', 227, 19, 3),
(1934, '301315', 'ทุ่งสว่าง   ', 227, 19, 3),
(1935, '301316', '*ดอนยาวใหญ่   ', 227, 19, 3),
(1936, '301317', 'โคกกลาง   ', 227, 19, 3),
(1937, '301318', 'เมืองโดน   ', 227, 19, 3),
(1938, '301401', 'เมืองปัก   ', 228, 19, 3),
(1939, '301402', 'ตะคุ   ', 228, 19, 3),
(1940, '301403', 'โคกไทย   ', 228, 19, 3),
(1941, '301404', 'สำโรง   ', 228, 19, 3),
(1942, '301405', 'ตะขบ   ', 228, 19, 3),
(1943, '301406', 'นกออก   ', 228, 19, 3),
(1944, '301407', 'ดอน   ', 228, 19, 3),
(1945, '301408', '*วังน้ำเขียว   ', 228, 19, 3),
(1946, '301409', 'ตูม   ', 228, 19, 3),
(1947, '301410', 'งิ้ว   ', 228, 19, 3),
(1948, '301411', 'สะแกราช   ', 228, 19, 3),
(1949, '301412', 'ลำนางแก้ว   ', 228, 19, 3),
(1950, '301413', '*วังหมี   ', 228, 19, 3),
(1951, '301414', '*ระเริง   ', 228, 19, 3),
(1952, '301415', '*อุดมทรัพย์   ', 228, 19, 3),
(1953, '301416', 'ภูหลวง   ', 228, 19, 3),
(1954, '301417', 'ธงชัยเหนือ   ', 228, 19, 3),
(1955, '301418', 'สุขเกษม   ', 228, 19, 3),
(1956, '301419', 'เกษมทรัพย์   ', 228, 19, 3),
(1957, '301420', 'บ่อปลาทอง   ', 228, 19, 3),
(1958, '301501', 'ในเมือง   ', 229, 19, 3),
(1959, '301502', 'สัมฤทธิ์   ', 229, 19, 3),
(1960, '301503', 'โบสถ์   ', 229, 19, 3),
(1961, '301504', 'กระเบื้องใหญ่   ', 229, 19, 3),
(1962, '301505', 'ท่าหลวง   ', 229, 19, 3),
(1963, '301506', 'รังกาใหญ่   ', 229, 19, 3),
(1964, '301507', 'ชีวาน   ', 229, 19, 3),
(1965, '301508', 'นิคมสร้างตนเอง   ', 229, 19, 3),
(1966, '301509', 'กระชอน   ', 229, 19, 3),
(1967, '301510', 'ดงใหญ่   ', 229, 19, 3),
(1968, '301511', 'ธารละหลอด   ', 229, 19, 3),
(1969, '301512', 'หนองระเวียง   ', 229, 19, 3),
(1970, '301601', 'ห้วยแถลง   ', 230, 19, 3),
(1971, '301602', 'ทับสวาย   ', 230, 19, 3),
(1972, '301603', 'เมืองพลับพลา   ', 230, 19, 3),
(1973, '301604', 'หลุ่งตะเคียน   ', 230, 19, 3),
(1974, '301605', 'หินดาด   ', 230, 19, 3),
(1975, '301606', 'งิ้ว   ', 230, 19, 3),
(1976, '301607', 'กงรถ   ', 230, 19, 3),
(1977, '301608', 'หลุ่งประดู่   ', 230, 19, 3),
(1978, '301609', 'ตะโก   ', 230, 19, 3),
(1979, '301610', 'ห้วยแคน   ', 230, 19, 3),
(1980, '301701', 'ชุมพวง   ', 231, 19, 3),
(1981, '301702', 'ประสุข   ', 231, 19, 3),
(1982, '301703', 'ท่าลาด   ', 231, 19, 3),
(1983, '301704', 'สาหร่าย   ', 231, 19, 3),
(1984, '301705', 'ตลาดไทร   ', 231, 19, 3),
(1985, '301706', 'ช่องแมว   ', 231, 19, 3),
(1986, '301707', 'ขุย   ', 231, 19, 3),
(1987, '301708', '*กระเบื้องนอก   ', 231, 19, 3),
(1988, '301709', '*เมืองยาง   ', 231, 19, 3),
(1989, '301710', 'โนนรัง   ', 231, 19, 3),
(1990, '301711', 'บ้านยาง   ', 231, 19, 3),
(1991, '301712', '*ละหานปลาค้าว   ', 231, 19, 3),
(1992, '301713', '*โนนอุดม   ', 231, 19, 3),
(1993, '301714', 'หนองหลัก   ', 231, 19, 3),
(1994, '301715', 'ไพล   ', 231, 19, 3),
(1995, '301716', 'โนนตูม   ', 231, 19, 3),
(1996, '301717', 'โนนยอ   ', 231, 19, 3),
(1997, '301801', 'สูงเนิน   ', 232, 19, 3),
(1998, '301802', 'เสมา   ', 232, 19, 3),
(1999, '301803', 'โคราช   ', 232, 19, 3),
(2000, '301804', 'บุ่งขี้เหล็ก   ', 232, 19, 3),
(2001, '301805', 'โนนค่า   ', 232, 19, 3),
(2002, '301806', 'โค้งยาง   ', 232, 19, 3),
(2003, '301807', 'มะเกลือเก่า   ', 232, 19, 3),
(2004, '301808', 'มะเกลือใหม่   ', 232, 19, 3),
(2005, '301809', 'นากลาง   ', 232, 19, 3),
(2006, '301810', 'หนองตะไก้   ', 232, 19, 3),
(2007, '301811', 'กุดจิก   ', 232, 19, 3),
(2008, '301901', 'ขามทะเลสอ   ', 233, 19, 3),
(2009, '301902', 'โป่งแดง   ', 233, 19, 3),
(2010, '301903', 'พันดุง   ', 233, 19, 3),
(2011, '301904', 'หนองสรวง   ', 233, 19, 3),
(2012, '301905', 'บึงอ้อ   ', 233, 19, 3),
(2013, '302001', 'สีคิ้ว   ', 234, 19, 3),
(2014, '302002', 'บ้านหัน   ', 234, 19, 3),
(2015, '302003', 'กฤษณา   ', 234, 19, 3),
(2016, '302004', 'ลาดบัวขาว   ', 234, 19, 3),
(2017, '302005', 'หนองหญ้าขาว   ', 234, 19, 3),
(2018, '302006', 'กุดน้อย   ', 234, 19, 3),
(2019, '302007', 'หนองน้ำใส   ', 234, 19, 3),
(2020, '302008', 'วังโรงใหญ่   ', 234, 19, 3),
(2021, '302009', 'มิตรภาพ   ', 234, 19, 3),
(2022, '302010', 'คลองไผ่   ', 234, 19, 3),
(2023, '302011', 'ดอนเมือง   ', 234, 19, 3),
(2024, '302012', 'หนองบัวน้อย   ', 234, 19, 3),
(2025, '302101', 'ปากช่อง   ', 235, 19, 3),
(2026, '302102', 'กลางดง   ', 235, 19, 3),
(2027, '302103', 'จันทึก   ', 235, 19, 3),
(2028, '302104', 'วังกะทะ   ', 235, 19, 3),
(2029, '302105', 'หมูสี   ', 235, 19, 3),
(2030, '302106', 'หนองสาหร่าย   ', 235, 19, 3),
(2031, '302107', 'ขนงพระ   ', 235, 19, 3),
(2032, '302108', 'โป่งตาลอง   ', 235, 19, 3),
(2033, '302109', 'คลองม่วง   ', 235, 19, 3),
(2034, '302110', 'หนองน้ำแดง   ', 235, 19, 3),
(2035, '302111', 'วังไทร   ', 235, 19, 3),
(2036, '302112', 'พญาเย็น   ', 235, 19, 3),
(2037, '302201', 'หนองบุนนาก   ', 236, 19, 3),
(2038, '302202', 'สารภี   ', 236, 19, 3),
(2039, '302203', 'ไทยเจริญ   ', 236, 19, 3),
(2040, '302204', 'หนองหัวแรต   ', 236, 19, 3),
(2041, '302205', 'แหลมทอง   ', 236, 19, 3),
(2042, '302206', 'หนองตะไก้   ', 236, 19, 3),
(2043, '302207', 'ลุงเขว้า   ', 236, 19, 3),
(2044, '302208', 'หนองไม้ไผ่   ', 236, 19, 3),
(2045, '302209', 'บ้านใหม่   ', 236, 19, 3),
(2046, '302301', 'แก้งสนามนาง   ', 237, 19, 3),
(2047, '302302', 'โนนสำราญ   ', 237, 19, 3),
(2048, '302303', 'บึงพะไล   ', 237, 19, 3),
(2049, '302304', 'สีสุก   ', 237, 19, 3),
(2050, '302305', 'บึงสำโรง   ', 237, 19, 3),
(2051, '302401', 'โนนแดง   ', 238, 19, 3),
(2052, '302402', 'โนนตาเถร   ', 238, 19, 3),
(2053, '302403', 'สำพะเนียง   ', 238, 19, 3),
(2054, '302404', 'วังหิน   ', 238, 19, 3),
(2055, '302405', 'ดอนยาวใหญ่   ', 238, 19, 3),
(2056, '302501', 'วังน้ำเขียว   ', 239, 19, 3),
(2057, '302502', 'วังหมี   ', 239, 19, 3),
(2058, '302503', 'ระเริง   ', 239, 19, 3),
(2059, '302504', 'อุดมทรัพย์   ', 239, 19, 3),
(2060, '302505', 'ไทยสามัคคี   ', 239, 19, 3),
(2061, '302601', 'สำนักตะคร้อ   ', 240, 19, 3),
(2062, '302602', 'หนองแวง   ', 240, 19, 3),
(2063, '302603', 'บึงปรือ   ', 240, 19, 3),
(2064, '302604', 'วังยายทอง   ', 240, 19, 3),
(2065, '302701', 'เมืองยาง   ', 241, 19, 3),
(2066, '302702', 'กระเบื้องนอก   ', 241, 19, 3),
(2067, '302703', 'ละหานปลาค้าว   ', 241, 19, 3),
(2068, '302704', 'โนนอุดม   ', 241, 19, 3),
(2069, '302801', 'สระพระ   ', 242, 19, 3),
(2070, '302802', 'มาบกราด   ', 242, 19, 3),
(2071, '302803', 'พังเทียม   ', 242, 19, 3),
(2072, '302804', 'ทัพรั้ง   ', 242, 19, 3),
(2073, '302805', 'หนองหอย   ', 242, 19, 3),
(2074, '302901', 'ขุย   ', 243, 19, 3),
(2075, '302902', 'บ้านยาง   ', 243, 19, 3),
(2076, '302903', 'ช่องแมว   ', 243, 19, 3),
(2077, '302904', 'ไพล   ', 243, 19, 3),
(2078, '303001', 'เมืองพะไล   ', 244, 19, 3),
(2079, '303002', 'โนนจาน   ', 244, 19, 3),
(2080, '303003', 'บัวลาย   ', 244, 19, 3),
(2081, '303004', 'หนองหว้า   ', 244, 19, 3),
(2082, '303101', 'สีดา   ', 245, 19, 3),
(2083, '303102', 'โพนทอง   ', 245, 19, 3),
(2084, '303103', 'โนนประดู่   ', 245, 19, 3),
(2085, '303104', 'สามเมือง   ', 245, 19, 3),
(2086, '303105', 'หนองตาดใหญ่   ', 245, 19, 3),
(2087, '303201', 'ช้างทอง   ', 246, 19, 3),
(2088, '303202', 'ท่าช้าง   ', 246, 19, 3),
(2089, '303203', 'พระพุทธ   ', 246, 19, 3),
(2090, '303204', 'หนองงูเหลือม   ', 246, 19, 3),
(2091, '303205', 'หนองยาง   ', 246, 19, 3),
(2092, '310101', 'ในเมือง   ', 250, 20, 3),
(2093, '310102', 'อิสาณ   ', 250, 20, 3),
(2094, '310103', 'เสม็ด   ', 250, 20, 3),
(2095, '310104', 'บ้านบัว   ', 250, 20, 3),
(2096, '310105', 'สะแกโพรง   ', 250, 20, 3),
(2097, '310106', 'สวายจีก   ', 250, 20, 3),
(2098, '310107', '*ห้วยราช   ', 250, 20, 3),
(2099, '310108', 'บ้านยาง   ', 250, 20, 3),
(2100, '310109', 'บ้านด่าน*   ', 250, 20, 3),
(2101, '310110', '*สามแวง   ', 250, 20, 3),
(2102, '310111', 'ปราสาท*   ', 250, 20, 3),
(2103, '310112', 'พระครู   ', 250, 20, 3),
(2104, '310113', 'ถลุงเหล็ก   ', 250, 20, 3),
(2105, '310114', 'หนองตาด   ', 250, 20, 3),
(2106, '310115', 'โนนขวาง*   ', 250, 20, 3),
(2107, '310116', '*ตาเสา   ', 250, 20, 3),
(2108, '310117', 'ลุมปุ๊ก   ', 250, 20, 3),
(2109, '310118', 'สองห้อง   ', 250, 20, 3),
(2110, '310119', 'บัวทอง   ', 250, 20, 3),
(2111, '310120', 'ชุมเห็ด   ', 250, 20, 3),
(2112, '310121', '*สนวน   ', 250, 20, 3),
(2113, '310122', 'หลักเขต   ', 250, 20, 3),
(2114, '310123', 'วังเหนือ*   ', 250, 20, 3),
(2115, '310124', '*บ้านตะโก   ', 250, 20, 3),
(2116, '310125', 'สะแกซำ   ', 250, 20, 3),
(2117, '310126', 'กลันทา   ', 250, 20, 3),
(2118, '310127', 'กระสัง   ', 250, 20, 3),
(2119, '310128', 'เมืองฝาง   ', 250, 20, 3),
(2120, '310198', '*ปะเคียบ   ', 250, 20, 3),
(2121, '310199', '*ห้วยราช   ', 250, 20, 3),
(2122, '310201', 'คูเมือง   ', 251, 20, 3),
(2123, '310202', 'ปะเคียบ   ', 251, 20, 3),
(2124, '310203', 'บ้านแพ   ', 251, 20, 3),
(2125, '310204', 'พรสำราญ   ', 251, 20, 3),
(2126, '310205', 'หินเหล็กไฟ   ', 251, 20, 3),
(2127, '310206', 'ตูมใหญ่   ', 251, 20, 3),
(2128, '310207', 'หนองขมาร   ', 251, 20, 3),
(2129, '310301', 'กระสัง', 252, 20, 3),
(2130, '310302', 'ลำดวน   ', 252, 20, 3),
(2131, '310303', 'สองชั้น   ', 252, 20, 3),
(2132, '310304', 'สูงเนิน   ', 252, 20, 3),
(2133, '310305', 'หนองเต็ง   ', 252, 20, 3),
(2134, '310306', 'เมืองไผ่   ', 252, 20, 3),
(2135, '310307', 'ชุมแสง   ', 252, 20, 3),
(2136, '310308', 'บ้านปรือ   ', 252, 20, 3),
(2137, '310309', 'ห้วยสำราญ   ', 252, 20, 3),
(2138, '310310', 'กันทรารมย์   ', 252, 20, 3),
(2139, '310311', 'ศรีภูมิ   ', 252, 20, 3),
(2140, '310401', 'นางรอง   ', 253, 20, 3),
(2141, '310402', 'ตาเป๊ก*   ', 253, 20, 3),
(2142, '310403', 'สะเดา   ', 253, 20, 3),
(2143, '310404', '*ชำนิ   ', 253, 20, 3),
(2144, '310405', 'ชุมแสง   ', 253, 20, 3),
(2145, '310406', 'หนองโบสถ์   ', 253, 20, 3),
(2146, '310407', '*หนองปล่อง   ', 253, 20, 3),
(2147, '310408', 'หนองกง   ', 253, 20, 3),
(2148, '310409', '*ทุ่งจังหัน   ', 253, 20, 3),
(2149, '310410', '*เมืองยาง   ', 253, 20, 3),
(2150, '310411', 'เจริญสุข*   ', 253, 20, 3),
(2151, '310412', '*โนนสุวรรณ   ', 253, 20, 3),
(2152, '310413', 'ถนนหัก   ', 253, 20, 3),
(2153, '310414', 'หนองไทร   ', 253, 20, 3),
(2154, '310415', 'ก้านเหลือง   ', 253, 20, 3),
(2155, '310416', 'บ้านสิงห์   ', 253, 20, 3),
(2156, '310417', 'ลำไทรโยง   ', 253, 20, 3),
(2157, '310418', 'ทรัพย์พระยา   ', 253, 20, 3),
(2158, '310419', 'อีสานเขต*   ', 253, 20, 3),
(2159, '310420', '*ดงอีจาน   ', 253, 20, 3),
(2160, '310421', '*โกรกแก้ว   ', 253, 20, 3),
(2161, '310422', '*ช่อผกา   ', 253, 20, 3),
(2162, '310423', '*ละลวด   ', 253, 20, 3),
(2163, '310424', 'หนองยายพิมพ์   ', 253, 20, 3),
(2164, '310425', 'หัวถนน   ', 253, 20, 3),
(2165, '310426', 'ทุ่งแสงทอง   ', 253, 20, 3),
(2166, '310427', 'หนองโสน   ', 253, 20, 3),
(2167, '310494', '*หนองปล่อง   ', 253, 20, 3),
(2168, '310495', '*ชำนิ   ', 253, 20, 3),
(2169, '310496', '*ดอนอะราง   ', 253, 20, 3),
(2170, '310497', '*เมืองไผ่   ', 253, 20, 3),
(2171, '310498', '*เย้ยปราสาท   ', 253, 20, 3),
(2172, '310499', '*หนองกี่   ', 253, 20, 3),
(2173, '310501', 'หนองกี่   ', 254, 20, 3),
(2174, '310502', 'เย้ยปราสาท   ', 254, 20, 3),
(2175, '310503', 'เมืองไผ่   ', 254, 20, 3),
(2176, '310504', 'ดอนอะราง   ', 254, 20, 3),
(2177, '310505', 'โคกสว่าง   ', 254, 20, 3),
(2178, '310506', 'ทุ่งกระตาดพัฒนา   ', 254, 20, 3),
(2179, '310507', 'ทุ่งกระเต็น   ', 254, 20, 3),
(2180, '310508', 'ท่าโพธิ์ชัย   ', 254, 20, 3),
(2181, '310509', 'โคกสูง   ', 254, 20, 3),
(2182, '310510', 'บุกระสัง   ', 254, 20, 3),
(2183, '310601', 'ละหานทราย   ', 255, 20, 3),
(2184, '310602', 'ถาวร*   ', 255, 20, 3),
(2185, '310603', 'ตาจง   ', 255, 20, 3),
(2186, '310604', 'สำโรงใหม่   ', 255, 20, 3),
(2187, '310605', '*โนนดินแดง   ', 255, 20, 3),
(2188, '310606', 'ยายแย้มวัฒนา*   ', 255, 20, 3),
(2189, '310607', 'หนองแวง   ', 255, 20, 3),
(2190, '310608', '*ลำนางรอง   ', 255, 20, 3),
(2191, '310609', '*ส้มป่อย   ', 255, 20, 3),
(2192, '310610', 'หนองตะครอง   ', 255, 20, 3),
(2193, '310611', 'โคกว่าน   ', 255, 20, 3),
(2194, '310699', '*ไทยเจริญ   ', 255, 20, 3),
(2195, '310701', 'ประโคนชัย   ', 256, 20, 3),
(2196, '310702', 'แสลงโทน   ', 256, 20, 3),
(2197, '310703', 'บ้านไทร   ', 256, 20, 3),
(2198, '310704', '*จันดุม   ', 256, 20, 3),
(2199, '310705', 'ละเวี้ย   ', 256, 20, 3),
(2200, '310706', 'จรเข้มาก   ', 256, 20, 3),
(2201, '310707', 'ปังกู   ', 256, 20, 3),
(2202, '310708', 'โคกย่าง   ', 256, 20, 3),
(2203, '310709', '*โคกขมิ้น   ', 256, 20, 3),
(2204, '310710', 'โคกม้า   ', 256, 20, 3),
(2205, '310711', '*ป่าชัน   ', 256, 20, 3),
(2206, '310712', '*สะเดา   ', 256, 20, 3),
(2207, '310713', 'ไพศาล   ', 256, 20, 3),
(2208, '310714', 'ตะโกตาพิ   ', 256, 20, 3),
(2209, '310715', 'เขาคอก   ', 256, 20, 3),
(2210, '310716', 'หนองบอน   ', 256, 20, 3),
(2211, '310717', '*สำโรง   ', 256, 20, 3),
(2212, '310718', 'โคกมะขาม   ', 256, 20, 3),
(2213, '310719', 'โคกตูม   ', 256, 20, 3),
(2214, '310720', 'ประทัดบุ   ', 256, 20, 3),
(2215, '310721', 'สี่เหลี่ยม   ', 256, 20, 3),
(2216, '310797', '*ป่าชัน   ', 256, 20, 3),
(2217, '310798', '*โคกขมิ้น   ', 256, 20, 3),
(2218, '310799', '*จันดุม   ', 256, 20, 3),
(2219, '310801', 'บ้านกรวด   ', 257, 20, 3),
(2220, '310802', 'โนนเจริญ   ', 257, 20, 3),
(2221, '310803', 'หนองไม้งาม   ', 257, 20, 3),
(2222, '310804', 'ปราสาท   ', 257, 20, 3),
(2223, '310805', 'สายตะกู   ', 257, 20, 3),
(2224, '310806', 'หินลาด   ', 257, 20, 3),
(2225, '310807', 'บึงเจริญ   ', 257, 20, 3),
(2226, '310808', 'จันทบเพชร   ', 257, 20, 3),
(2227, '310809', 'เขาดินเหนือ   ', 257, 20, 3),
(2228, '310901', 'พุทไธสง   ', 258, 20, 3),
(2229, '310902', 'มะเฟือง   ', 258, 20, 3),
(2230, '310903', 'บ้านจาน   ', 258, 20, 3),
(2231, '310904', '*หนองแวง   ', 258, 20, 3),
(2232, '310905', '*ทองหลาง   ', 258, 20, 3),
(2233, '310906', 'บ้านเป้า   ', 258, 20, 3),
(2234, '310907', 'บ้านแวง   ', 258, 20, 3),
(2235, '310908', '*บ้านแดงใหญ่   ', 258, 20, 3),
(2236, '310909', 'บ้านยาง   ', 258, 20, 3),
(2237, '310910', 'หายโศก   ', 258, 20, 3),
(2238, '310911', '*กู่สวนแตง   ', 258, 20, 3),
(2239, '310912', '*หนองเยือง   ', 258, 20, 3),
(2240, '311001', 'ลำปลายมาศ   ', 259, 20, 3),
(2241, '311002', 'หนองคู   ', 259, 20, 3),
(2242, '311003', 'แสลงพัน   ', 259, 20, 3),
(2243, '311004', 'ทะเมนชัย   ', 259, 20, 3),
(2244, '311005', 'ตลาดโพธิ์   ', 259, 20, 3),
(2245, '311006', 'หนองกะทิง   ', 259, 20, 3),
(2246, '311007', 'โคกกลาง   ', 259, 20, 3),
(2247, '311008', 'โคกสะอาด   ', 259, 20, 3),
(2248, '311009', 'เมืองแฝก   ', 259, 20, 3),
(2249, '311010', 'บ้านยาง   ', 259, 20, 3),
(2250, '311011', 'ผไทรินทร์   ', 259, 20, 3),
(2251, '311012', 'โคกล่าม   ', 259, 20, 3),
(2252, '311013', 'หินโคน   ', 259, 20, 3),
(2253, '311014', 'หนองบัวโคก   ', 259, 20, 3),
(2254, '311015', 'บุโพธิ์   ', 259, 20, 3),
(2255, '311016', 'หนองโดน   ', 259, 20, 3),
(2256, '311097', '*ไทยสามัคคี   ', 259, 20, 3),
(2257, '311098', '*ห้วยหิน   ', 259, 20, 3),
(2258, '311099', '*สระแก้ว   ', 259, 20, 3),
(2259, '311101', 'สตึก   ', 260, 20, 3),
(2260, '311102', 'นิคม   ', 260, 20, 3),
(2261, '311103', 'ทุ่งวัง   ', 260, 20, 3),
(2262, '311104', 'เมืองแก   ', 260, 20, 3),
(2263, '311105', 'หนองใหญ่   ', 260, 20, 3),
(2264, '311106', 'ร่อนทอง   ', 260, 20, 3),
(2265, '311107', 'แคนดง*   ', 260, 20, 3),
(2266, '311108', 'ดงพลอง*   ', 260, 20, 3),
(2267, '311109', 'ดอนมนต์   ', 260, 20, 3),
(2268, '311110', 'ชุมแสง   ', 260, 20, 3),
(2269, '311111', 'ท่าม่วง   ', 260, 20, 3),
(2270, '311112', 'สะแก   ', 260, 20, 3),
(2271, '311113', 'สระบัว*   ', 260, 20, 3),
(2272, '311114', 'สนามชัย   ', 260, 20, 3),
(2273, '311115', 'กระสัง   ', 260, 20, 3),
(2274, '311116', 'หัวฝาย*   ', 260, 20, 3),
(2275, '311201', 'ปะคำ   ', 261, 20, 3),
(2276, '311202', 'ไทยเจริญ   ', 261, 20, 3),
(2277, '311203', 'หนองบัว   ', 261, 20, 3),
(2278, '311204', 'โคกมะม่วง   ', 261, 20, 3),
(2279, '311205', 'หูทำนบ   ', 261, 20, 3),
(2280, '311301', 'นาโพธิ์   ', 262, 20, 3),
(2281, '311302', 'บ้านคู   ', 262, 20, 3),
(2282, '311303', 'บ้านดู่   ', 262, 20, 3),
(2283, '311304', 'ดอนกอก   ', 262, 20, 3),
(2284, '311305', 'ศรีสว่าง   ', 262, 20, 3),
(2285, '311401', 'สระแก้ว   ', 263, 20, 3),
(2286, '311402', 'ห้วยหิน   ', 263, 20, 3),
(2287, '311403', 'ไทยสามัคคี   ', 263, 20, 3),
(2288, '311404', 'หนองชัยศรี   ', 263, 20, 3),
(2289, '311405', 'เสาเดียว   ', 263, 20, 3),
(2290, '311406', 'เมืองฝ้าย   ', 263, 20, 3),
(2291, '311407', 'สระทอง   ', 263, 20, 3),
(2292, '311501', 'จันดุม   ', 264, 20, 3),
(2293, '311502', 'โคกขมิ้น   ', 264, 20, 3),
(2294, '311503', 'ป่าชัน   ', 264, 20, 3),
(2295, '311504', 'สะเดา   ', 264, 20, 3),
(2296, '311505', 'สำโรง   ', 264, 20, 3),
(2297, '311601', 'ห้วยราช   ', 265, 20, 3),
(2298, '311602', 'สามแวง   ', 265, 20, 3),
(2299, '311603', 'ตาเสา   ', 265, 20, 3),
(2300, '311604', 'บ้านตะโก   ', 265, 20, 3),
(2301, '311605', 'สนวน   ', 265, 20, 3),
(2302, '311606', 'โคกเหล็ก   ', 265, 20, 3),
(2303, '311607', 'เมืองโพธิ์   ', 265, 20, 3),
(2304, '311608', 'ห้วยราชา   ', 265, 20, 3),
(2305, '311701', 'โนนสุวรรณ   ', 266, 20, 3),
(2306, '311702', 'ทุ่งจังหัน   ', 266, 20, 3),
(2307, '311703', 'โกรกแก้ว   ', 266, 20, 3),
(2308, '311704', 'ดงอีจาน   ', 266, 20, 3),
(2309, '311801', 'ชำนิ   ', 267, 20, 3),
(2310, '311802', 'หนองปล่อง   ', 267, 20, 3),
(2311, '311803', 'เมืองยาง   ', 267, 20, 3),
(2312, '311804', 'ช่อผกา   ', 267, 20, 3),
(2313, '311805', 'ละลวด   ', 267, 20, 3),
(2314, '311806', 'โคกสนวน   ', 267, 20, 3),
(2315, '311901', 'หนองแวง   ', 268, 20, 3),
(2316, '311902', 'ทองหลาง   ', 268, 20, 3),
(2317, '311903', 'แดงใหญ่   ', 268, 20, 3),
(2318, '311904', 'กู่สวนแตง   ', 268, 20, 3),
(2319, '311905', 'หนองเยือง   ', 268, 20, 3),
(2320, '312001', 'โนนดินแดง   ', 269, 20, 3),
(2321, '312002', 'ส้มป่อย   ', 269, 20, 3),
(2322, '312003', 'ลำนางรอง   ', 269, 20, 3),
(2323, '312101', 'บ้านด่าน   ', 270, 20, 3),
(2324, '312102', 'ปราสาท   ', 270, 20, 3),
(2325, '312103', 'วังเหนือ   ', 270, 20, 3),
(2326, '312104', 'โนนขวาง   ', 270, 20, 3),
(2327, '312201', 'แคนดง   ', 271, 20, 3),
(2328, '312202', 'ดงพลอง   ', 271, 20, 3),
(2329, '312203', 'สระบัว   ', 271, 20, 3),
(2330, '312204', 'หัวฝาย   ', 271, 20, 3),
(2331, '312301', 'เจริญสุข   ', 272, 20, 3),
(2332, '312302', 'ตาเป๊ก   ', 272, 20, 3),
(2333, '312303', 'อีสานเขต   ', 272, 20, 3),
(2334, '312304', 'ถาวร   ', 272, 20, 3),
(2335, '312305', 'ยายแย้มวัฒนา   ', 272, 20, 3),
(2336, '320101', 'ในเมือง   ', 273, 21, 3),
(2337, '320102', 'ตั้งใจ   ', 273, 21, 3),
(2338, '320103', 'เพี้ยราม   ', 273, 21, 3),
(2339, '320104', 'นาดี   ', 273, 21, 3),
(2340, '320105', 'ท่าสว่าง   ', 273, 21, 3),
(2341, '320106', 'สลักได   ', 273, 21, 3),
(2342, '320107', 'ตาอ็อง   ', 273, 21, 3),
(2343, '320108', 'ตากูก*   ', 273, 21, 3),
(2344, '320109', 'สำโรง   ', 273, 21, 3),
(2345, '320110', 'แกใหญ่   ', 273, 21, 3),
(2346, '320111', 'นอกเมือง   ', 273, 21, 3),
(2347, '320112', 'คอโค   ', 273, 21, 3),
(2348, '320113', 'สวาย   ', 273, 21, 3),
(2349, '320114', 'เฉนียง   ', 273, 21, 3),
(2350, '320115', 'บึง*   ', 273, 21, 3),
(2351, '320116', 'เทนมีย์   ', 273, 21, 3),
(2352, '320117', 'เขวาสินรินทร์*   ', 273, 21, 3),
(2353, '320118', 'นาบัว   ', 273, 21, 3),
(2354, '320119', 'เมืองที   ', 273, 21, 3),
(2355, '320120', 'ราม   ', 273, 21, 3),
(2356, '320121', 'บุฤาษี   ', 273, 21, 3),
(2357, '320122', 'ตระแสง   ', 273, 21, 3),
(2358, '320123', 'บ้านแร่*   ', 273, 21, 3),
(2359, '320124', 'ปราสาททอง*   ', 273, 21, 3),
(2360, '320125', 'แสลงพันธ์   ', 273, 21, 3),
(2361, '320126', 'กาเกาะ   ', 273, 21, 3),
(2362, '320201', 'ชุมพลบุรี   ', 274, 21, 3),
(2363, '320202', 'นาหนองไผ่   ', 274, 21, 3),
(2364, '320203', 'ไพรขลา   ', 274, 21, 3),
(2365, '320204', 'ศรีณรงค์   ', 274, 21, 3),
(2366, '320205', 'ยะวึก   ', 274, 21, 3),
(2367, '320206', 'เมืองบัว   ', 274, 21, 3),
(2368, '320207', 'สระขุด   ', 274, 21, 3),
(2369, '320208', 'กระเบื้อง   ', 274, 21, 3),
(2370, '320209', 'หนองเรือ   ', 274, 21, 3),
(2371, '320301', 'ท่าตูม   ', 275, 21, 3),
(2372, '320302', 'กระโพ   ', 275, 21, 3),
(2373, '320303', 'พรมเทพ   ', 275, 21, 3),
(2374, '320304', 'โพนครก   ', 275, 21, 3),
(2375, '320305', 'เมืองแก   ', 275, 21, 3),
(2376, '320306', 'บะ   ', 275, 21, 3),
(2377, '320307', 'หนองบัว   ', 275, 21, 3),
(2378, '320308', 'บัวโคก   ', 275, 21, 3),
(2379, '320309', 'หนองเมธี   ', 275, 21, 3),
(2380, '320310', 'ทุ่งกุลา   ', 275, 21, 3),
(2381, '320401', 'จอมพระ   ', 276, 21, 3),
(2382, '320402', 'เมืองลีง   ', 276, 21, 3),
(2383, '320403', 'กระหาด   ', 276, 21, 3),
(2384, '320404', 'บุแกรง   ', 276, 21, 3),
(2385, '320405', 'หนองสนิท   ', 276, 21, 3),
(2386, '320406', 'บ้านผือ   ', 276, 21, 3),
(2387, '320407', 'ลุ่มระวี   ', 276, 21, 3),
(2388, '320408', 'ชุมแสง   ', 276, 21, 3),
(2389, '320409', 'เป็นสุข   ', 276, 21, 3),
(2390, '320501', 'กังแอน   ', 277, 21, 3),
(2391, '320502', 'ทมอ   ', 277, 21, 3),
(2392, '320503', 'ไพล   ', 277, 21, 3),
(2393, '320504', 'ปรือ   ', 277, 21, 3),
(2394, '320505', 'ทุ่งมน   ', 277, 21, 3),
(2395, '320506', 'ตาเบา   ', 277, 21, 3);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(2396, '320507', 'หนองใหญ่   ', 277, 21, 3),
(2397, '320508', 'โคกยาง   ', 277, 21, 3),
(2398, '320509', 'โคกสะอาด   ', 277, 21, 3),
(2399, '320510', 'บ้านไทร   ', 277, 21, 3),
(2400, '320511', 'โชคนาสาม   ', 277, 21, 3),
(2401, '320512', 'เชื้อเพลิง   ', 277, 21, 3),
(2402, '320513', 'ปราสาททนง   ', 277, 21, 3),
(2403, '320514', 'ตานี   ', 277, 21, 3),
(2404, '320515', 'บ้านพลวง   ', 277, 21, 3),
(2405, '320516', 'กันตวจระมวล   ', 277, 21, 3),
(2406, '320517', 'สมุด   ', 277, 21, 3),
(2407, '320518', 'ประทัดบุ   ', 277, 21, 3),
(2408, '320595', '*ด่าน   ', 277, 21, 3),
(2409, '320596', '*คูตัน   ', 277, 21, 3),
(2410, '320597', '*โคกกลาง   ', 277, 21, 3),
(2411, '320598', '*บักได   ', 277, 21, 3),
(2412, '320599', '*กาบเชิง   ', 277, 21, 3),
(2413, '320601', 'กาบเชิง   ', 278, 21, 3),
(2414, '320602', '*บักได   ', 278, 21, 3),
(2415, '320603', '*โคกกลาง   ', 278, 21, 3),
(2416, '320604', 'คูตัน   ', 278, 21, 3),
(2417, '320605', 'ด่าน   ', 278, 21, 3),
(2418, '320606', 'แนงมุด   ', 278, 21, 3),
(2419, '320607', 'โคกตะเคียน   ', 278, 21, 3),
(2420, '320608', '*ตาเมียง   ', 278, 21, 3),
(2421, '320609', '*จีกแดก   ', 278, 21, 3),
(2422, '320610', 'ตะเคียน   ', 278, 21, 3),
(2423, '320701', 'รัตนบุรี   ', 279, 21, 3),
(2424, '320702', 'ธาตุ   ', 279, 21, 3),
(2425, '320703', 'แก   ', 279, 21, 3),
(2426, '320704', 'ดอนแรด   ', 279, 21, 3),
(2427, '320705', 'หนองบัวทอง   ', 279, 21, 3),
(2428, '320706', 'หนองบัวบาน   ', 279, 21, 3),
(2429, '320707', 'หนองหลวง*   ', 279, 21, 3),
(2430, '320708', 'หนองเทพ*   ', 279, 21, 3),
(2431, '320709', 'ไผ่   ', 279, 21, 3),
(2432, '320710', 'โนน*   ', 279, 21, 3),
(2433, '320711', 'เบิด   ', 279, 21, 3),
(2434, '320712', 'ระเวียง*   ', 279, 21, 3),
(2435, '320713', 'น้ำเขียว   ', 279, 21, 3),
(2436, '320714', 'กุดขาคีม   ', 279, 21, 3),
(2437, '320715', 'ยางสว่าง   ', 279, 21, 3),
(2438, '320716', 'ทับใหญ่   ', 279, 21, 3),
(2439, '320717', 'คำผง*   ', 279, 21, 3),
(2440, '320795', '*สนม   ', 279, 21, 3),
(2441, '320796', '*หนองระฆัง   ', 279, 21, 3),
(2442, '320797', '*นานวน   ', 279, 21, 3),
(2443, '320798', '*โพนโก   ', 279, 21, 3),
(2444, '320799', '*แคน   ', 279, 21, 3),
(2445, '320801', 'สนม   ', 280, 21, 3),
(2446, '320802', 'โพนโก   ', 280, 21, 3),
(2447, '320803', 'หนองระฆัง   ', 280, 21, 3),
(2448, '320804', 'นานวน   ', 280, 21, 3),
(2449, '320805', 'แคน   ', 280, 21, 3),
(2450, '320806', 'หัวงัว   ', 280, 21, 3),
(2451, '320807', 'หนองอียอ   ', 280, 21, 3),
(2452, '320901', 'ระแงง   ', 281, 21, 3),
(2453, '320902', 'ตรึม   ', 281, 21, 3),
(2454, '320903', 'จารพัต   ', 281, 21, 3),
(2455, '320904', 'ยาง   ', 281, 21, 3),
(2456, '320905', 'แตล   ', 281, 21, 3),
(2457, '320906', 'หนองบัว   ', 281, 21, 3),
(2458, '320907', 'คาละแมะ   ', 281, 21, 3),
(2459, '320908', 'หนองเหล็ก   ', 281, 21, 3),
(2460, '320909', 'หนองขวาว   ', 281, 21, 3),
(2461, '320910', 'ช่างปี่   ', 281, 21, 3),
(2462, '320911', 'กุดหวาย   ', 281, 21, 3),
(2463, '320912', 'ขวาวใหญ่   ', 281, 21, 3),
(2464, '320913', 'นารุ่ง   ', 281, 21, 3),
(2465, '320914', 'ตรมไพร   ', 281, 21, 3),
(2466, '320915', 'ผักไหม   ', 281, 21, 3),
(2467, '321001', 'สังขะ   ', 282, 21, 3),
(2468, '321002', 'ขอนแตก   ', 282, 21, 3),
(2469, '321003', '*ณรงค์   ', 282, 21, 3),
(2470, '321004', '*แจนแวน   ', 282, 21, 3),
(2471, '321005', '*ตรวจ   ', 282, 21, 3),
(2472, '321006', 'ดม   ', 282, 21, 3),
(2473, '321007', 'พระแก้ว   ', 282, 21, 3),
(2474, '321008', 'บ้านจารย์   ', 282, 21, 3),
(2475, '321009', 'กระเทียม   ', 282, 21, 3),
(2476, '321010', 'สะกาด   ', 282, 21, 3),
(2477, '321011', 'ตาตุม   ', 282, 21, 3),
(2478, '321012', 'ทับทัน   ', 282, 21, 3),
(2479, '321013', 'ตาคง   ', 282, 21, 3),
(2480, '321014', '*ศรีสุข   ', 282, 21, 3),
(2481, '321015', 'บ้านชบ   ', 282, 21, 3),
(2482, '321016', '*หนองแวง   ', 282, 21, 3),
(2483, '321017', 'เทพรักษา   ', 282, 21, 3),
(2484, '321093', '*คูตัน   ', 282, 21, 3),
(2485, '321094', '*ด่าน   ', 282, 21, 3),
(2486, '321101', 'ลำดวน   ', 283, 21, 3),
(2487, '321102', 'โชคเหนือ   ', 283, 21, 3),
(2488, '321103', 'อู่โลก   ', 283, 21, 3),
(2489, '321104', 'ตรำดม   ', 283, 21, 3),
(2490, '321105', 'ตระเปียงเตีย   ', 283, 21, 3),
(2491, '321201', 'สำโรงทาบ   ', 284, 21, 3),
(2492, '321202', 'หนองไผ่ล้อม   ', 284, 21, 3),
(2493, '321203', 'กระออม   ', 284, 21, 3),
(2494, '321204', 'หนองฮะ   ', 284, 21, 3),
(2495, '321205', 'ศรีสุข   ', 284, 21, 3),
(2496, '321206', 'เกาะแก้ว   ', 284, 21, 3),
(2497, '321207', 'หมื่นศรี   ', 284, 21, 3),
(2498, '321208', 'เสม็จ   ', 284, 21, 3),
(2499, '321209', 'สะโน   ', 284, 21, 3),
(2500, '321210', 'ประดู่   ', 284, 21, 3),
(2501, '321301', 'บัวเชด   ', 285, 21, 3),
(2502, '321302', 'สะเดา   ', 285, 21, 3),
(2503, '321303', 'จรัส   ', 285, 21, 3),
(2504, '321304', 'ตาวัง   ', 285, 21, 3),
(2505, '321305', 'อาโพน   ', 285, 21, 3),
(2506, '321306', 'สำเภาลูน   ', 285, 21, 3),
(2507, '321401', 'บักได   ', 286, 21, 3),
(2508, '321402', 'โคกกลาง   ', 286, 21, 3),
(2509, '321403', 'จีกแดก   ', 286, 21, 3),
(2510, '321404', 'ตาเมียง   ', 286, 21, 3),
(2511, '321501', 'ณรงค์   ', 287, 21, 3),
(2512, '321502', 'แจนแวน   ', 287, 21, 3),
(2513, '321503', 'ตรวจ   ', 287, 21, 3),
(2514, '321504', 'หนองแวง   ', 287, 21, 3),
(2515, '321505', 'ศรีสุข   ', 287, 21, 3),
(2516, '321601', 'เขวาสินรินทร์   ', 288, 21, 3),
(2517, '321602', 'บึง   ', 288, 21, 3),
(2518, '321603', 'ตากูก   ', 288, 21, 3),
(2519, '321604', 'ปราสาททอง   ', 288, 21, 3),
(2520, '321605', 'บ้านแร่   ', 288, 21, 3),
(2521, '321701', 'หนองหลวง   ', 289, 21, 3),
(2522, '321702', 'คำผง   ', 289, 21, 3),
(2523, '321703', 'โนน   ', 289, 21, 3),
(2524, '321704', 'ระเวียง   ', 289, 21, 3),
(2525, '321705', 'หนองเทพ   ', 289, 21, 3),
(2526, '330101', 'เมืองเหนือ   ', 290, 22, 3),
(2527, '330102', 'เมืองใต้   ', 290, 22, 3),
(2528, '330103', 'คูซอด   ', 290, 22, 3),
(2529, '330104', 'ซำ   ', 290, 22, 3),
(2530, '330105', 'จาน   ', 290, 22, 3),
(2531, '330106', 'ตะดอบ   ', 290, 22, 3),
(2532, '330107', 'หนองครก   ', 290, 22, 3),
(2533, '330108', '*โนนเพ็ก   ', 290, 22, 3),
(2534, '330109', '*พรหมสวัสดิ์   ', 290, 22, 3),
(2535, '330110', '*พยุห์   ', 290, 22, 3),
(2536, '330111', 'โพนข่า   ', 290, 22, 3),
(2537, '330112', 'โพนค้อ   ', 290, 22, 3),
(2538, '330113', '*ธาตุ   ', 290, 22, 3),
(2539, '330114', '*ตำแย   ', 290, 22, 3),
(2540, '330115', 'โพนเขวา   ', 290, 22, 3),
(2541, '330116', 'หญ้าปล้อง   ', 290, 22, 3),
(2542, '330117', '*บุสูง   ', 290, 22, 3),
(2543, '330118', 'ทุ่ม   ', 290, 22, 3),
(2544, '330119', 'หนองไฮ   ', 290, 22, 3),
(2545, '330120', '*ดวนใหญ่   ', 290, 22, 3),
(2546, '330121', 'หนองแก้ว   ', 290, 22, 3),
(2547, '330122', 'น้ำคำ   ', 290, 22, 3),
(2548, '330123', 'โพธิ์   ', 290, 22, 3),
(2549, '330124', 'หมากเขียบ   ', 290, 22, 3),
(2550, '330125', '*บ่อแก้ว   ', 290, 22, 3),
(2551, '330126', '*ศรีสำราญ   ', 290, 22, 3),
(2552, '330127', 'หนองไผ่   ', 290, 22, 3),
(2553, '330128', '*หนองค้า   ', 290, 22, 3),
(2554, '330196', '*ดวนใหญ่   ', 290, 22, 3),
(2555, '330197', '*ธาตุ   ', 290, 22, 3),
(2556, '330198', '*บุสูง   ', 290, 22, 3),
(2557, '330199', '*คอนกาม   ', 290, 22, 3),
(2558, '330201', 'ยางชุมน้อย   ', 291, 22, 3),
(2559, '330202', 'ลิ้นฟ้า   ', 291, 22, 3),
(2560, '330203', 'คอนกาม   ', 291, 22, 3),
(2561, '330204', 'โนนคูณ   ', 291, 22, 3),
(2562, '330205', 'กุดเมืองฮาม   ', 291, 22, 3),
(2563, '330206', 'บึงบอน   ', 291, 22, 3),
(2564, '330207', 'ยางชุมใหญ่   ', 291, 22, 3),
(2565, '330301', 'ดูน   ', 292, 22, 3),
(2566, '330302', 'โนนสัง   ', 292, 22, 3),
(2567, '330303', 'หนองหัวช้าง   ', 292, 22, 3),
(2568, '330304', 'ยาง   ', 292, 22, 3),
(2569, '330305', 'หนองแวง   ', 292, 22, 3),
(2570, '330306', 'หนองแก้ว   ', 292, 22, 3),
(2571, '330307', 'ทาม   ', 292, 22, 3),
(2572, '330308', 'ละทาย   ', 292, 22, 3),
(2573, '330309', 'เมืองน้อย   ', 292, 22, 3),
(2574, '330310', 'อีปาด   ', 292, 22, 3),
(2575, '330311', 'บัวน้อย   ', 292, 22, 3),
(2576, '330312', 'หนองบัว   ', 292, 22, 3),
(2577, '330313', 'ดู่   ', 292, 22, 3),
(2578, '330314', 'ผักแพว   ', 292, 22, 3),
(2579, '330315', 'จาน   ', 292, 22, 3),
(2580, '330316', '*ตองบิด   ', 292, 22, 3),
(2581, '330317', '*ละเอาะ   ', 292, 22, 3),
(2582, '330318', '*น้ำเกลี้ยง   ', 292, 22, 3),
(2583, '330319', '*เขิน   ', 292, 22, 3),
(2584, '330320', 'คำเนียม   ', 292, 22, 3),
(2585, '330394', '*ตองปิด   ', 292, 22, 3),
(2586, '330395', '*ละเอาะ   ', 292, 22, 3),
(2587, '330396', '*หนองกุง   ', 292, 22, 3),
(2588, '330397', '*โพธิ์   ', 292, 22, 3),
(2589, '330398', '*บก   ', 292, 22, 3),
(2590, '330399', '*โนนค้อ   ', 292, 22, 3),
(2591, '330401', 'บึงมะลู   ', 293, 22, 3),
(2592, '330402', 'กุดเสลา   ', 293, 22, 3),
(2593, '330403', 'เมือง   ', 293, 22, 3),
(2594, '330404', '*หนองหว้า   ', 293, 22, 3),
(2595, '330405', 'สังเม็ก   ', 293, 22, 3),
(2596, '330406', 'น้ำอ้อม   ', 293, 22, 3),
(2597, '330407', 'ละลาย   ', 293, 22, 3),
(2598, '330408', 'รุง   ', 293, 22, 3),
(2599, '330409', 'ตระกาจ   ', 293, 22, 3),
(2600, '330410', '*เสียว   ', 293, 22, 3),
(2601, '330411', 'จานใหญ่   ', 293, 22, 3),
(2602, '330412', 'ภูเงิน   ', 293, 22, 3),
(2603, '330413', 'ชำ   ', 293, 22, 3),
(2604, '330414', 'กระแชง   ', 293, 22, 3),
(2605, '330415', 'โนนสำราญ   ', 293, 22, 3),
(2606, '330416', 'หนองหญ้าลาด   ', 293, 22, 3),
(2607, '330417', '*หนองงูเหลือม   ', 293, 22, 3),
(2608, '330418', '*ท่าคล้อ   ', 293, 22, 3),
(2609, '330419', 'เสาธงชัย   ', 293, 22, 3),
(2610, '330420', 'ขนุน   ', 293, 22, 3),
(2611, '330421', 'สวนกล้วย   ', 293, 22, 3),
(2612, '330422', '*หนองฮาง   ', 293, 22, 3),
(2613, '330423', 'เวียงเหนือ   ', 293, 22, 3),
(2614, '330424', 'ทุ่งใหญ่   ', 293, 22, 3),
(2615, '330425', 'ภูผาหมอก   ', 293, 22, 3),
(2616, '330496', '*สระเยาว์   ', 293, 22, 3),
(2617, '330497', '*พิงพวย   ', 293, 22, 3),
(2618, '330498', '*ศรีแก้ว   ', 293, 22, 3),
(2619, '330499', '*ตูม   ', 293, 22, 3),
(2620, '330501', 'กันทรารมย์   ', 294, 22, 3),
(2621, '330502', 'จะกง   ', 294, 22, 3),
(2622, '330503', 'ใจดี   ', 294, 22, 3),
(2623, '330504', 'ดองกำเม็ด   ', 294, 22, 3),
(2624, '330505', 'โสน   ', 294, 22, 3),
(2625, '330506', 'ปรือใหญ่   ', 294, 22, 3),
(2626, '330507', 'สะเดาใหญ่   ', 294, 22, 3),
(2627, '330508', 'ตาอุด   ', 294, 22, 3),
(2628, '330509', 'ห้วยเหนือ   ', 294, 22, 3),
(2629, '330510', 'ห้วยใต้   ', 294, 22, 3),
(2630, '330511', 'หัวเสือ   ', 294, 22, 3),
(2631, '330512', '*ละลม   ', 294, 22, 3),
(2632, '330513', 'ตะเคียน   ', 294, 22, 3),
(2633, '330514', '*โคกตาล   ', 294, 22, 3),
(2634, '330515', 'นิคมพัฒนา   ', 294, 22, 3),
(2635, '330516', '*ห้วยตามอญ   ', 294, 22, 3),
(2636, '330517', 'โคกเพชร   ', 294, 22, 3),
(2637, '330518', 'ปราสาท   ', 294, 22, 3),
(2638, '330519', '*ตะเคียนราม   ', 294, 22, 3),
(2639, '330520', '*ห้วยติ๊กชู   ', 294, 22, 3),
(2640, '330521', 'สำโรงตาเจ็น   ', 294, 22, 3),
(2641, '330522', 'ห้วยสำราญ   ', 294, 22, 3),
(2642, '330523', '*ดงรัก   ', 294, 22, 3),
(2643, '330524', 'กฤษณา   ', 294, 22, 3),
(2644, '330525', 'ลมศักดิ์   ', 294, 22, 3),
(2645, '330526', 'หนองฉลอง   ', 294, 22, 3),
(2646, '330527', 'ศรีตระกูล   ', 294, 22, 3),
(2647, '330528', 'ศรีสะอาด   ', 294, 22, 3),
(2648, '330599', '*ละลม   ', 294, 22, 3),
(2649, '330601', 'ไพรบึง   ', 295, 22, 3),
(2650, '330602', 'ดินแดง   ', 295, 22, 3),
(2651, '330603', 'ปราสาทเยอ   ', 295, 22, 3),
(2652, '330604', 'สำโรงพลัน   ', 295, 22, 3),
(2653, '330605', 'สุขสวัสดิ์   ', 295, 22, 3),
(2654, '330606', 'โนนปูน   ', 295, 22, 3),
(2655, '330701', 'พิมาย   ', 296, 22, 3),
(2656, '330702', 'กู่   ', 296, 22, 3),
(2657, '330703', 'หนองเชียงทูน   ', 296, 22, 3),
(2658, '330704', 'ตูม   ', 296, 22, 3),
(2659, '330705', 'สมอ   ', 296, 22, 3),
(2660, '330706', 'โพธิ์ศรี   ', 296, 22, 3),
(2661, '330707', 'สำโรงปราสาท   ', 296, 22, 3),
(2662, '330708', 'ดู่   ', 296, 22, 3),
(2663, '330709', 'สวาย   ', 296, 22, 3),
(2664, '330710', 'พิมายเหนือ   ', 296, 22, 3),
(2665, '330801', 'สิ   ', 297, 22, 3),
(2666, '330802', 'บักดอง   ', 297, 22, 3),
(2667, '330803', 'พราน   ', 297, 22, 3),
(2668, '330804', 'โพธิ์วงศ์   ', 297, 22, 3),
(2669, '330805', 'ไพร   ', 297, 22, 3),
(2670, '330806', 'กระหวัน   ', 297, 22, 3),
(2671, '330807', 'ขุนหาญ   ', 297, 22, 3),
(2672, '330808', 'โนนสูง   ', 297, 22, 3),
(2673, '330809', 'กันทรอม   ', 297, 22, 3),
(2674, '330810', 'ภูฝ้าย   ', 297, 22, 3),
(2675, '330811', 'โพธิ์กระสังข์   ', 297, 22, 3),
(2676, '330812', 'ห้วยจันทร์   ', 297, 22, 3),
(2677, '330901', 'เมืองคง   ', 298, 22, 3),
(2678, '330902', 'เมืองแคน   ', 298, 22, 3),
(2679, '330903', 'หนองแค   ', 298, 22, 3),
(2680, '330904', 'กุง*   ', 298, 22, 3),
(2681, '330905', 'คลีกลิ้ง*   ', 298, 22, 3),
(2682, '330906', 'จิกสังข์ทอง   ', 298, 22, 3),
(2683, '330907', 'ด่าน   ', 298, 22, 3),
(2684, '330908', 'ดู่   ', 298, 22, 3),
(2685, '330909', 'หนองอึ่ง   ', 298, 22, 3),
(2686, '330910', 'บัวหุ่ง   ', 298, 22, 3),
(2687, '330911', 'ไผ่   ', 298, 22, 3),
(2688, '330912', 'ส้มป่อย   ', 298, 22, 3),
(2689, '330913', 'หนองหมี   ', 298, 22, 3),
(2690, '330914', 'หว้านคำ   ', 298, 22, 3),
(2691, '330915', 'สร้างปี่   ', 298, 22, 3),
(2692, '330916', 'โจดม่วง*   ', 298, 22, 3),
(2693, '330917', 'หนองบัวดง*   ', 298, 22, 3),
(2694, '331001', 'กำแพง   ', 299, 22, 3),
(2695, '331002', 'อี่หล่ำ   ', 299, 22, 3),
(2696, '331003', 'ก้านเหลือง   ', 299, 22, 3),
(2697, '331004', 'ทุ่งไชย   ', 299, 22, 3),
(2698, '331005', 'สำโรง   ', 299, 22, 3),
(2699, '331006', 'แขม   ', 299, 22, 3),
(2700, '331007', 'หนองไฮ   ', 299, 22, 3),
(2701, '331008', 'ขะยูง   ', 299, 22, 3),
(2702, '331009', '*ตาโกน   ', 299, 22, 3),
(2703, '331010', 'ตาเกษ   ', 299, 22, 3),
(2704, '331011', 'หัวช้าง   ', 299, 22, 3),
(2705, '331012', 'รังแร้ง   ', 299, 22, 3),
(2706, '331013', '*เมืองจันทร์   ', 299, 22, 3),
(2707, '331014', 'แต้   ', 299, 22, 3),
(2708, '331015', 'แข้   ', 299, 22, 3),
(2709, '331016', 'โพธิ์ชัย   ', 299, 22, 3),
(2710, '331017', 'ปะอาว   ', 299, 22, 3),
(2711, '331018', 'หนองห้าง   ', 299, 22, 3),
(2712, '331019', '*โดด   ', 299, 22, 3),
(2713, '331020', '*เสียว   ', 299, 22, 3),
(2714, '331021', '*หนองม้า   ', 299, 22, 3),
(2715, '331022', 'สระกำแพงใหญ่   ', 299, 22, 3),
(2716, '331023', '*หนองใหญ่   ', 299, 22, 3),
(2717, '331024', 'โคกหล่าม   ', 299, 22, 3),
(2718, '331025', 'โคกจาน   ', 299, 22, 3),
(2719, '331026', '*ผือใหญ่   ', 299, 22, 3),
(2720, '331027', '*อีเซ   ', 299, 22, 3),
(2721, '331096', '*ผักไหม   ', 299, 22, 3),
(2722, '331097', '*กล้วยกว้าง   ', 299, 22, 3),
(2723, '331098', '*ห้วยทับทัน   ', 299, 22, 3),
(2724, '331099', '*เป๊าะ   ', 299, 22, 3),
(2725, '331101', 'เป๊าะ   ', 300, 22, 3),
(2726, '331102', 'บึงบูรพ์   ', 300, 22, 3),
(2727, '331201', 'ห้วยทับทัน   ', 301, 22, 3),
(2728, '331202', 'เมืองหลวง   ', 301, 22, 3),
(2729, '331203', 'กล้วยกว้าง   ', 301, 22, 3),
(2730, '331204', 'ผักไหม   ', 301, 22, 3),
(2731, '331205', 'จานแสนไชย   ', 301, 22, 3),
(2732, '331206', 'ปราสาท   ', 301, 22, 3),
(2733, '331301', 'โนนค้อ   ', 302, 22, 3),
(2734, '331302', 'บก   ', 302, 22, 3),
(2735, '331303', 'โพธิ์   ', 302, 22, 3),
(2736, '331304', 'หนองกุง   ', 302, 22, 3),
(2737, '331305', 'เหล่ากวาง   ', 302, 22, 3),
(2738, '331401', 'ศรีแก้ว   ', 303, 22, 3),
(2739, '331402', 'พิงพวย   ', 303, 22, 3),
(2740, '331403', 'สระเยาว์   ', 303, 22, 3),
(2741, '331404', 'ตูม   ', 303, 22, 3),
(2742, '331405', 'เสื่องข้าว   ', 303, 22, 3),
(2743, '331406', 'ศรีโนนงาม   ', 303, 22, 3),
(2744, '331407', 'สะพุง   ', 303, 22, 3),
(2745, '331501', 'น้ำเกลี้ยง   ', 304, 22, 3),
(2746, '331502', 'ละเอาะ   ', 304, 22, 3),
(2747, '331503', 'ตองปิด   ', 304, 22, 3),
(2748, '331504', 'เขิน   ', 304, 22, 3),
(2749, '331505', 'รุ่งระวี   ', 304, 22, 3),
(2750, '331506', 'คูบ   ', 304, 22, 3),
(2751, '331601', 'บุสูง   ', 305, 22, 3),
(2752, '331602', 'ธาตุ   ', 305, 22, 3),
(2753, '331603', 'ดวนใหญ่   ', 305, 22, 3),
(2754, '331604', 'บ่อแก้ว   ', 305, 22, 3),
(2755, '331605', 'ศรีสำราญ   ', 305, 22, 3),
(2756, '331606', 'ทุ่งสว่าง   ', 305, 22, 3),
(2757, '331607', 'วังหิน   ', 305, 22, 3),
(2758, '331608', 'โพนยาง   ', 305, 22, 3),
(2759, '331701', 'โคกตาล   ', 306, 22, 3),
(2760, '331702', 'ห้วยตามอญ   ', 306, 22, 3),
(2761, '331703', 'ห้วยตึ๊กชู   ', 306, 22, 3),
(2762, '331704', 'ละลม   ', 306, 22, 3),
(2763, '331705', 'ตะเคียนราม   ', 306, 22, 3),
(2764, '331706', 'ดงรัก   ', 306, 22, 3),
(2765, '331707', 'ไพรพัฒนา   ', 306, 22, 3),
(2766, '331801', 'เมืองจันทร์   ', 307, 22, 3),
(2767, '331802', 'ตาโกน   ', 307, 22, 3),
(2768, '331803', 'หนองใหญ่   ', 307, 22, 3),
(2769, '331901', 'เสียว   ', 308, 22, 3),
(2770, '331902', 'หนองหว้า   ', 308, 22, 3),
(2771, '331903', 'หนองงูเหลือม   ', 308, 22, 3),
(2772, '331904', 'หนองฮาง   ', 308, 22, 3),
(2773, '331905', 'ท่าคล้อ   ', 308, 22, 3),
(2774, '332001', 'พยุห์   ', 309, 22, 3),
(2775, '332002', 'พรหมสวัสดิ์   ', 309, 22, 3),
(2776, '332003', 'ตำแย   ', 309, 22, 3),
(2777, '332004', 'โนนเพ็ก   ', 309, 22, 3),
(2778, '332005', 'หนองค้า   ', 309, 22, 3),
(2779, '332101', 'โดด   ', 310, 22, 3),
(2780, '332102', 'เสียว   ', 310, 22, 3),
(2781, '332103', 'หนองม้า   ', 310, 22, 3),
(2782, '332104', 'ผือใหญ่   ', 310, 22, 3),
(2783, '332105', 'อีเซ   ', 310, 22, 3),
(2784, '332201', 'กุง   ', 311, 22, 3),
(2785, '332202', 'คลีกลิ้ง   ', 311, 22, 3),
(2786, '332203', 'หนองบัวดง   ', 311, 22, 3),
(2787, '332204', 'โจดม่วง   ', 311, 22, 3),
(2788, '340101', 'ในเมือง   ', 312, 23, 3),
(2789, '340102', '*โพนเมือง   ', 312, 23, 3),
(2790, '340103', '*ท่าเมือง   ', 312, 23, 3),
(2791, '340104', 'หัวเรือ   ', 312, 23, 3),
(2792, '340105', 'หนองขอน   ', 312, 23, 3),
(2793, '340106', '*ดอนมดแดง   ', 312, 23, 3),
(2794, '340107', 'ปทุม   ', 312, 23, 3),
(2795, '340108', 'ขามใหญ่   ', 312, 23, 3),
(2796, '340109', 'แจระแม   ', 312, 23, 3),
(2797, '340110', '*คำไฮใหญ่   ', 312, 23, 3),
(2798, '340111', 'หนองบ่อ   ', 312, 23, 3),
(2799, '340112', 'ไร่น้อย   ', 312, 23, 3),
(2800, '340113', 'กระโสบ   ', 312, 23, 3),
(2801, '340114', '*เหล่าแดง   ', 312, 23, 3),
(2802, '340115', '*เหล่าเสือโก้ก   ', 312, 23, 3),
(2803, '340116', 'กุดลาด   ', 312, 23, 3),
(2804, '340117', '*หนองบก   ', 312, 23, 3),
(2805, '340118', '*แพงใหญ่   ', 312, 23, 3),
(2806, '340119', 'ขี้เหล็ก   ', 312, 23, 3),
(2807, '340120', 'ปะอาว   ', 312, 23, 3),
(2808, '340201', 'นาคำ   ', 313, 23, 3),
(2809, '340202', 'แก้งกอก   ', 313, 23, 3),
(2810, '340203', 'เอือดใหญ่   ', 313, 23, 3),
(2811, '340204', 'วาริน   ', 313, 23, 3),
(2812, '340205', 'ลาดควาย   ', 313, 23, 3),
(2813, '340206', 'สงยาง   ', 313, 23, 3),
(2814, '340207', 'ตะบ่าย   ', 313, 23, 3),
(2815, '340208', 'คำไหล   ', 313, 23, 3),
(2816, '340209', 'หนามแท่ง   ', 313, 23, 3),
(2817, '340210', 'นาเลิน   ', 313, 23, 3),
(2818, '340211', 'ดอนใหญ่   ', 313, 23, 3),
(2819, '340301', 'โขงเจียม   ', 314, 23, 3),
(2820, '340302', 'ห้วยยาง   ', 314, 23, 3),
(2821, '340303', 'นาโพธิ์กลาง   ', 314, 23, 3),
(2822, '340304', 'หนองแสงใหญ่   ', 314, 23, 3),
(2823, '340305', 'ห้วยไผ่   ', 314, 23, 3),
(2824, '340306', 'คำเขื่อนแก้ว   ', 314, 23, 3),
(2825, '340401', 'เขื่องใน   ', 315, 23, 3),
(2826, '340402', 'สร้างถ่อ   ', 315, 23, 3),
(2827, '340403', 'ค้อทอง   ', 315, 23, 3),
(2828, '340404', 'ก่อเอ้   ', 315, 23, 3),
(2829, '340405', 'หัวดอน   ', 315, 23, 3),
(2830, '340406', 'ชีทวน   ', 315, 23, 3),
(2831, '340407', 'ท่าไห   ', 315, 23, 3),
(2832, '340408', 'นาคำใหญ่   ', 315, 23, 3),
(2833, '340409', 'แดงหม้อ   ', 315, 23, 3),
(2834, '340410', 'ธาตุน้อย   ', 315, 23, 3),
(2835, '340411', 'บ้านไทย   ', 315, 23, 3),
(2836, '340412', 'บ้านกอก   ', 315, 23, 3),
(2837, '340413', 'กลางใหญ่   ', 315, 23, 3),
(2838, '340414', 'โนนรัง   ', 315, 23, 3),
(2839, '340415', 'ยางขี้นก   ', 315, 23, 3),
(2840, '340416', 'ศรีสุข   ', 315, 23, 3),
(2841, '340417', 'สหธาตุ   ', 315, 23, 3),
(2842, '340418', 'หนองเหล่า   ', 315, 23, 3),
(2843, '340501', 'เขมราฐ   ', 316, 23, 3),
(2844, '340502', '*กองโพน   ', 316, 23, 3),
(2845, '340503', 'ขามป้อม   ', 316, 23, 3),
(2846, '340504', 'เจียด   ', 316, 23, 3),
(2847, '340505', '*พังเคน   ', 316, 23, 3),
(2848, '340506', '*นาตาล   ', 316, 23, 3),
(2849, '340507', 'หนองผือ   ', 316, 23, 3),
(2850, '340508', 'นาแวง   ', 316, 23, 3),
(2851, '340509', '*พะลาน   ', 316, 23, 3),
(2852, '340510', 'แก้งเหนือ   ', 316, 23, 3),
(2853, '340511', 'หนองนกทา   ', 316, 23, 3),
(2854, '340512', 'หนองสิม   ', 316, 23, 3),
(2855, '340513', 'หัวนา   ', 316, 23, 3),
(2856, '340601', '*ชานุมาน   ', 317, 23, 3),
(2857, '340602', '*โคกสาร   ', 317, 23, 3),
(2858, '340603', '*คำเขื่อนแก้ว   ', 317, 23, 3),
(2859, '340604', '*หนองข่า   ', 317, 23, 3),
(2860, '340605', '*คำโพน   ', 317, 23, 3),
(2861, '340606', '*โคกก่ง   ', 317, 23, 3),
(2862, '340607', '*ป่าก่อ   ', 317, 23, 3),
(2863, '340701', 'เมืองเดช   ', 318, 23, 3),
(2864, '340702', 'นาส่วง   ', 318, 23, 3),
(2865, '340703', '*นาเยีย   ', 318, 23, 3),
(2866, '340704', 'นาเจริญ   ', 318, 23, 3),
(2867, '340705', '*นาเรือง   ', 318, 23, 3),
(2868, '340706', 'ทุ่งเทิง   ', 318, 23, 3),
(2869, '340707', '*หนองอ้ม   ', 318, 23, 3),
(2870, '340708', 'สมสะอาด   ', 318, 23, 3),
(2871, '340709', 'กุดประทาย   ', 318, 23, 3),
(2872, '340710', 'ตบหู   ', 318, 23, 3),
(2873, '340711', 'กลาง   ', 318, 23, 3),
(2874, '340712', 'แก้ง   ', 318, 23, 3),
(2875, '340713', 'ท่าโพธิ์ศรี   ', 318, 23, 3),
(2876, '340714', '*นาเกษม   ', 318, 23, 3),
(2877, '340715', 'บัวงาม   ', 318, 23, 3),
(2878, '340716', 'คำครั่ง   ', 318, 23, 3),
(2879, '340717', 'นากระแซง   ', 318, 23, 3),
(2880, '340718', '*กุดเรือ   ', 318, 23, 3),
(2881, '340719', '*นาดี   ', 318, 23, 3),
(2882, '340720', 'โพนงาม   ', 318, 23, 3),
(2883, '340721', 'ป่าโมง   ', 318, 23, 3),
(2884, '340722', '*โคกชำแระ   ', 318, 23, 3),
(2885, '340723', 'โนนสมบูรณ์   ', 318, 23, 3),
(2886, '340801', 'นาจะหลวย   ', 319, 23, 3),
(2887, '340802', 'โนนสมบูรณ์   ', 319, 23, 3),
(2888, '340803', 'พรสวรรค์   ', 319, 23, 3),
(2889, '340804', 'บ้านตูม   ', 319, 23, 3),
(2890, '340805', 'โสกแสง   ', 319, 23, 3),
(2891, '340806', 'โนนสวรรค์   ', 319, 23, 3),
(2892, '340901', 'โซง   ', 320, 23, 3),
(2893, '340902', 'ตาเกา*   ', 320, 23, 3),
(2894, '340903', 'ยาง   ', 320, 23, 3),
(2895, '340904', 'โดมประดิษฐ์   ', 320, 23, 3),
(2896, '340905', 'ขี้เหล็ก*   ', 320, 23, 3),
(2897, '340906', 'บุเปือย   ', 320, 23, 3),
(2898, '340907', 'สีวิเชียร   ', 320, 23, 3),
(2899, '340908', 'ไพบูลย์*   ', 320, 23, 3),
(2900, '340909', 'ยางใหญ่   ', 320, 23, 3),
(2901, '340910', 'โคกสะอาด*   ', 320, 23, 3),
(2902, '340911', 'เก่าขาม   ', 320, 23, 3),
(2903, '341001', 'โพนงาม   ', 321, 23, 3),
(2904, '341002', 'ห้วยข่า   ', 321, 23, 3),
(2905, '341003', 'คอแลน   ', 321, 23, 3),
(2906, '341004', 'นาโพธิ์   ', 321, 23, 3),
(2907, '341005', 'หนองสะโน   ', 321, 23, 3),
(2908, '341006', 'โนนค้อ   ', 321, 23, 3),
(2909, '341007', 'บัวงาม   ', 321, 23, 3),
(2910, '341008', 'บ้านแมด   ', 321, 23, 3),
(2911, '341101', 'ขุหลุ   ', 322, 23, 3),
(2912, '341102', 'กระเดียน   ', 322, 23, 3),
(2913, '341103', 'เกษม   ', 322, 23, 3),
(2914, '341104', 'กุศกร   ', 322, 23, 3),
(2915, '341105', 'ขามเปี้ย   ', 322, 23, 3),
(2916, '341106', 'คอนสาย   ', 322, 23, 3),
(2917, '341107', 'โคกจาน   ', 322, 23, 3),
(2918, '341108', 'นาพิน   ', 322, 23, 3),
(2919, '341109', 'นาสะไม   ', 322, 23, 3),
(2920, '341110', 'โนนกุง   ', 322, 23, 3),
(2921, '341111', 'ตระการ   ', 322, 23, 3),
(2922, '341112', 'ตากแดด   ', 322, 23, 3),
(2923, '341113', 'ไหล่ทุ่ง   ', 322, 23, 3),
(2924, '341114', 'เป้า   ', 322, 23, 3),
(2925, '341115', 'เซเป็ด   ', 322, 23, 3),
(2926, '341116', 'สะพือ   ', 322, 23, 3),
(2927, '341117', 'หนองเต่า   ', 322, 23, 3),
(2928, '341118', 'ถ้ำแข้   ', 322, 23, 3),
(2929, '341119', 'ท่าหลวง   ', 322, 23, 3),
(2930, '341120', 'ห้วยฝ้ายพัฒนา   ', 322, 23, 3),
(2931, '341121', 'กุดยาลวน   ', 322, 23, 3),
(2932, '341122', 'บ้านแดง   ', 322, 23, 3),
(2933, '341123', 'คำเจริญ   ', 322, 23, 3),
(2934, '341201', 'ข้าวปุ้น   ', 323, 23, 3),
(2935, '341202', 'โนนสวาง   ', 323, 23, 3),
(2936, '341203', 'แก่งเค็ง   ', 323, 23, 3),
(2937, '341204', 'กาบิน   ', 323, 23, 3),
(2938, '341205', 'หนองทันน้ำ   ', 323, 23, 3),
(2939, '341301', '*พนา   ', 324, 23, 3),
(2940, '341302', '*จานลาน   ', 324, 23, 3),
(2941, '341303', '*ไม้กลอน   ', 324, 23, 3),
(2942, '341304', '*ลือ   ', 324, 23, 3),
(2943, '341305', '*ห้วย   ', 324, 23, 3),
(2944, '341306', '*นาหว้า   ', 324, 23, 3),
(2945, '341307', '*พระเหลา   ', 324, 23, 3),
(2946, '341308', '*นาป่าแซง   ', 324, 23, 3),
(2947, '341401', 'ม่วงสามสิบ   ', 325, 23, 3),
(2948, '341402', 'เหล่าบก   ', 325, 23, 3),
(2949, '341403', 'ดุมใหญ่   ', 325, 23, 3),
(2950, '341404', 'หนองช้างใหญ่   ', 325, 23, 3),
(2951, '341405', 'หนองเมือง   ', 325, 23, 3),
(2952, '341406', 'เตย   ', 325, 23, 3),
(2953, '341407', 'ยางสักกระโพหลุ่ม   ', 325, 23, 3),
(2954, '341408', 'หนองไข่นก   ', 325, 23, 3),
(2955, '341409', 'หนองเหล่า   ', 325, 23, 3),
(2956, '341410', 'หนองฮาง   ', 325, 23, 3),
(2957, '341411', 'ยางโยภาพ   ', 325, 23, 3),
(2958, '341412', 'ไผ่ใหญ่   ', 325, 23, 3),
(2959, '341413', 'นาเลิง   ', 325, 23, 3),
(2960, '341414', 'โพนแพง   ', 325, 23, 3),
(2961, '341501', 'วารินชำราบ   ', 326, 23, 3),
(2962, '341502', 'ธาตุ   ', 326, 23, 3),
(2963, '341503', '*ท่าช้าง   ', 326, 23, 3),
(2964, '341504', 'ท่าลาด   ', 326, 23, 3),
(2965, '341505', 'โนนโหนน   ', 326, 23, 3),
(2966, '341506', '*โนนกาเล็น   ', 326, 23, 3),
(2967, '341507', 'คูเมือง   ', 326, 23, 3),
(2968, '341508', 'สระสมิง   ', 326, 23, 3),
(2969, '341509', '*ค้อน้อย   ', 326, 23, 3),
(2970, '341510', 'คำน้ำแซบ   ', 326, 23, 3),
(2971, '341511', 'บุ่งหวาย   ', 326, 23, 3),
(2972, '341512', '*หนองไฮ   ', 326, 23, 3),
(2973, '341513', '*สำโรง   ', 326, 23, 3),
(2974, '341514', '*สว่าง   ', 326, 23, 3),
(2975, '341515', 'คำขวาง   ', 326, 23, 3),
(2976, '341516', 'โพธิ์ใหญ่   ', 326, 23, 3),
(2977, '341517', '*โคกก่อง   ', 326, 23, 3),
(2978, '341518', 'แสนสุข   ', 326, 23, 3),
(2979, '341519', '*โคกสว่าง   ', 326, 23, 3),
(2980, '341520', 'หนองกินเพล   ', 326, 23, 3),
(2981, '341521', 'โนนผึ้ง   ', 326, 23, 3),
(2982, '341522', 'เมืองศรีไค   ', 326, 23, 3),
(2983, '341523', '*บุ่งมะแลง   ', 326, 23, 3),
(2984, '341524', 'ห้วยขะยูง   ', 326, 23, 3),
(2985, '341525', '*แก่งโดม   ', 326, 23, 3),
(2986, '341526', 'บุ่งไหม   ', 326, 23, 3),
(2987, '341601', '*บุ่ง   ', 327, 23, 3),
(2988, '341602', '*ไก่คำ   ', 327, 23, 3),
(2989, '341603', '*นาจิก   ', 327, 23, 3),
(2990, '341604', '*ดงมะยาง   ', 327, 23, 3),
(2991, '341605', '*อำนาจ   ', 327, 23, 3),
(2992, '341606', '*เปือย   ', 327, 23, 3),
(2993, '341607', '*ดงบัง   ', 327, 23, 3),
(2994, '341608', '*ไร่ขี   ', 327, 23, 3),
(2995, '341609', '*ปลาค้าว   ', 327, 23, 3),
(2996, '341610', '*เหล่าพรวน   ', 327, 23, 3),
(2997, '341611', '*สร้างนกทา   ', 327, 23, 3),
(2998, '341612', '*คิ่มใหญ่   ', 327, 23, 3),
(2999, '341613', '*นาผือ   ', 327, 23, 3),
(3000, '341614', '*น้ำปลีก   ', 327, 23, 3),
(3001, '341615', '*นาวัง   ', 327, 23, 3),
(3002, '341616', '*นาหมอม้า   ', 327, 23, 3),
(3003, '341617', '*โนนโพธิ์   ', 327, 23, 3),
(3004, '341618', '*โนนหนามแท่ง   ', 327, 23, 3),
(3005, '341619', '*ห้วยไร่   ', 327, 23, 3),
(3006, '341620', '*หนองมะแซว   ', 327, 23, 3),
(3007, '341621', '*แมด   ', 327, 23, 3),
(3008, '341622', '*กุดปลาดุก   ', 327, 23, 3),
(3009, '341623', '*โนนงาม   ', 327, 23, 3),
(3010, '341701', '*เสนางคนิคม   ', 328, 23, 3),
(3011, '341702', '*โพนทอง   ', 328, 23, 3),
(3012, '341703', '*ไร่สีสุก   ', 328, 23, 3),
(3013, '341704', '*นาเวียง   ', 328, 23, 3),
(3014, '341705', '*หนองไฮ   ', 328, 23, 3),
(3015, '341706', '*หนองสามสี   ', 328, 23, 3),
(3016, '341801', '*หัวตะพาน   ', 329, 23, 3),
(3017, '341802', '*คำพระ   ', 329, 23, 3),
(3018, '341803', '*เค็งใหญ่   ', 329, 23, 3),
(3019, '341804', '*หนองแก้ว   ', 329, 23, 3),
(3020, '341805', '*โพนเมืองน้อย   ', 329, 23, 3),
(3021, '341806', '*สร้างถ่อน้อย   ', 329, 23, 3),
(3022, '341807', '*จิกดู่   ', 329, 23, 3),
(3023, '341808', '*รัตนวารี   ', 329, 23, 3),
(3024, '341901', 'พิบูล   ', 330, 23, 3),
(3025, '341902', 'กุดชมภู   ', 330, 23, 3),
(3026, '341903', '*คันไร่   ', 330, 23, 3),
(3027, '341904', 'ดอนจิก   ', 330, 23, 3),
(3028, '341905', 'ทรายมูล   ', 330, 23, 3),
(3029, '341906', 'นาโพธิ์   ', 330, 23, 3),
(3030, '341907', 'โนนกลาง   ', 330, 23, 3),
(3031, '341908', '*ฝางคำ   ', 330, 23, 3),
(3032, '341909', 'โพธิ์ไทร   ', 330, 23, 3),
(3033, '341910', 'โพธิ์ศรี   ', 330, 23, 3),
(3034, '341911', 'ระเว   ', 330, 23, 3),
(3035, '341912', 'ไร่ใต้   ', 330, 23, 3),
(3036, '341913', 'หนองบัวฮี   ', 330, 23, 3),
(3037, '341914', 'อ่างศิลา   ', 330, 23, 3),
(3038, '341915', '*นิคมสร้างตนเองฯ   ', 330, 23, 3),
(3039, '341916', '*ช่องเม็ก   ', 330, 23, 3),
(3040, '341917', '*โนนก่อ   ', 330, 23, 3),
(3041, '341918', 'โนนกาหลง   ', 330, 23, 3),
(3042, '341919', 'บ้านแขม   ', 330, 23, 3),
(3043, '342001', 'ตาลสุม   ', 331, 23, 3),
(3044, '342002', 'สำโรง   ', 331, 23, 3),
(3045, '342003', 'จิกเทิง   ', 331, 23, 3),
(3046, '342004', 'หนองกุง   ', 331, 23, 3),
(3047, '342005', 'นาคาย   ', 331, 23, 3),
(3048, '342006', 'คำหว้า   ', 331, 23, 3),
(3049, '342101', 'โพธิ์ไทร   ', 332, 23, 3),
(3050, '342102', 'ม่วงใหญ่   ', 332, 23, 3),
(3051, '342103', 'สำโรง   ', 332, 23, 3),
(3052, '342104', 'สองคอน   ', 332, 23, 3),
(3053, '342105', 'สารภี   ', 332, 23, 3),
(3054, '342106', 'เหล่างาม   ', 332, 23, 3),
(3055, '342201', 'สำโรง   ', 333, 23, 3),
(3056, '342202', 'โคกก่อง   ', 333, 23, 3),
(3057, '342203', 'หนองไฮ   ', 333, 23, 3),
(3058, '342204', 'ค้อน้อย   ', 333, 23, 3),
(3059, '342205', 'โนนกาเล็น   ', 333, 23, 3),
(3060, '342206', 'โคกสว่าง   ', 333, 23, 3),
(3061, '342207', 'โนนกลาง   ', 333, 23, 3),
(3062, '342208', 'บอน   ', 333, 23, 3),
(3063, '342209', 'ขามป้อม   ', 333, 23, 3),
(3064, '342301', '*อำนาจ   ', 334, 23, 3),
(3065, '342302', '*ดงมะยาง   ', 334, 23, 3),
(3066, '342303', '*เปือย   ', 334, 23, 3),
(3067, '342304', '*ดงบัง   ', 334, 23, 3),
(3068, '342305', '*ไร่ขี   ', 334, 23, 3),
(3069, '342306', '*แมด   ', 334, 23, 3),
(3070, '342401', 'ดอนมดแดง   ', 335, 23, 3),
(3071, '342402', 'เหล่าแดง   ', 335, 23, 3),
(3072, '342403', 'ท่าเมือง   ', 335, 23, 3),
(3073, '342404', 'คำไฮใหญ่   ', 335, 23, 3),
(3074, '342501', 'คันไร่   ', 336, 23, 3),
(3075, '342502', 'ช่องเม็ก   ', 336, 23, 3),
(3076, '342503', 'โนนก่อ   ', 336, 23, 3),
(3077, '342504', 'นิคมสร้างตนเองลำโดมน้อย   ', 336, 23, 3),
(3078, '342505', 'ฝางคำ   ', 336, 23, 3),
(3079, '342506', 'คำเขื่อนแก้ว   ', 336, 23, 3),
(3080, '342601', '*ทุ่งเทิง   ', 337, 23, 3),
(3081, '342602', 'หนองอ้ม   ', 337, 23, 3),
(3082, '342603', 'นาเกษม   ', 337, 23, 3),
(3083, '342604', 'กุดเรือ   ', 337, 23, 3),
(3084, '342605', 'โคกชำแระ   ', 337, 23, 3),
(3085, '342606', 'นาห่อม   ', 337, 23, 3),
(3086, '342701', '*หนองข่า   ', 338, 23, 3),
(3087, '342702', '*คำโพน   ', 338, 23, 3),
(3088, '342703', '*นาหว้า   ', 338, 23, 3),
(3089, '342704', '*ลือ   ', 338, 23, 3),
(3090, '342705', '*ห้วย   ', 338, 23, 3),
(3091, '342706', '*โนนงาม   ', 338, 23, 3),
(3092, '342707', '*นาป่าแซง   ', 338, 23, 3),
(3093, '342901', 'นาเยีย   ', 340, 23, 3),
(3094, '342902', 'นาดี   ', 340, 23, 3),
(3095, '342903', 'นาเรือง   ', 340, 23, 3),
(3096, '343001', 'นาตาล   ', 341, 23, 3),
(3097, '343002', 'พะลาน   ', 341, 23, 3),
(3098, '343003', 'กองโพน   ', 341, 23, 3),
(3099, '343004', 'พังเคน   ', 341, 23, 3),
(3100, '343101', 'เหล่าเสือโก้ก   ', 342, 23, 3),
(3101, '343102', 'โพนเมือง   ', 342, 23, 3),
(3102, '343103', 'แพงใหญ่   ', 342, 23, 3),
(3103, '343104', 'หนองบก   ', 342, 23, 3),
(3104, '343201', 'แก่งโดม   ', 343, 23, 3),
(3105, '343202', 'ท่าช้าง   ', 343, 23, 3),
(3106, '343203', 'บุ่งมะแลง   ', 343, 23, 3),
(3107, '343204', 'สว่าง   ', 343, 23, 3),
(3108, '343301', 'ตาเกา   ', 344, 23, 3),
(3109, '343302', 'ไพบูลย์   ', 344, 23, 3),
(3110, '343303', 'ขี้เหล็ก   ', 344, 23, 3),
(3111, '343304', 'โคกสะอาด   ', 344, 23, 3),
(3112, '350101', 'ในเมือง   ', 346, 24, 3),
(3113, '350102', 'น้ำคำใหญ่   ', 346, 24, 3),
(3114, '350103', 'ตาดทอง   ', 346, 24, 3),
(3115, '350104', 'สำราญ   ', 346, 24, 3),
(3116, '350105', 'ค้อเหนือ   ', 346, 24, 3),
(3117, '350106', 'ดู่ทุ่ง   ', 346, 24, 3),
(3118, '350107', 'เดิด   ', 346, 24, 3),
(3119, '350108', 'ขั้นไดใหญ่   ', 346, 24, 3),
(3120, '350109', 'ทุ่งแต้   ', 346, 24, 3),
(3121, '350110', 'สิงห์   ', 346, 24, 3),
(3122, '350111', 'นาสะไมย์   ', 346, 24, 3),
(3123, '350112', 'เขื่องคำ   ', 346, 24, 3),
(3124, '350113', 'หนองหิน   ', 346, 24, 3),
(3125, '350114', 'หนองคู   ', 346, 24, 3),
(3126, '350115', 'ขุมเงิน   ', 346, 24, 3),
(3127, '350116', 'ทุ่งนางโอก   ', 346, 24, 3),
(3128, '350117', 'หนองเรือ   ', 346, 24, 3),
(3129, '350118', 'หนองเป็ด   ', 346, 24, 3),
(3130, '350196', '*นาเวียง   ', 346, 24, 3),
(3131, '350197', '*ดงมะไฟ   ', 346, 24, 3),
(3132, '350198', '*ดู่ลาย   ', 346, 24, 3),
(3133, '350199', '*ทรายมูล   ', 346, 24, 3),
(3134, '350201', 'ทรายมูล   ', 347, 24, 3),
(3135, '350202', 'ดู่ลาด   ', 347, 24, 3),
(3136, '350203', 'ดงมะไฟ   ', 347, 24, 3),
(3137, '350204', 'นาเวียง   ', 347, 24, 3),
(3138, '350205', 'ไผ่   ', 347, 24, 3),
(3139, '350301', 'กุดชุม   ', 348, 24, 3),
(3140, '350302', 'โนนเปือย   ', 348, 24, 3),
(3141, '350303', 'กำแมด   ', 348, 24, 3),
(3142, '350304', 'นาโส่   ', 348, 24, 3),
(3143, '350305', 'ห้วยแก้ง   ', 348, 24, 3),
(3144, '350306', 'หนองหมี   ', 348, 24, 3),
(3145, '350307', 'โพนงาม   ', 348, 24, 3),
(3146, '350308', 'คำน้ำสร้าง   ', 348, 24, 3),
(3147, '350309', 'หนองแหน   ', 348, 24, 3),
(3148, '350401', 'ลุมพุก   ', 349, 24, 3),
(3149, '350402', 'ย่อ   ', 349, 24, 3),
(3150, '350403', 'สงเปือย   ', 349, 24, 3),
(3151, '350404', 'โพนทัน   ', 349, 24, 3),
(3152, '350405', 'ทุ่งมน   ', 349, 24, 3),
(3153, '350406', 'นาคำ   ', 349, 24, 3),
(3154, '350407', 'ดงแคนใหญ่   ', 349, 24, 3),
(3155, '350408', 'กู่จาน   ', 349, 24, 3),
(3156, '350409', 'นาแก   ', 349, 24, 3),
(3157, '350410', 'กุดกุง   ', 349, 24, 3),
(3158, '350411', 'เหล่าไฮ   ', 349, 24, 3),
(3159, '350412', 'แคนน้อย   ', 349, 24, 3),
(3160, '350413', 'ดงเจริญ   ', 349, 24, 3),
(3161, '350501', 'โพธิ์ไทร   ', 350, 24, 3),
(3162, '350502', 'กระจาย   ', 350, 24, 3),
(3163, '350503', 'โคกนาโก   ', 350, 24, 3),
(3164, '350504', 'เชียงเพ็ง   ', 350, 24, 3),
(3165, '350505', 'ศรีฐาน   ', 350, 24, 3),
(3166, '350601', 'ฟ้าหยาด   ', 351, 24, 3),
(3167, '350602', 'หัวเมือง   ', 351, 24, 3),
(3168, '350603', 'คูเมือง   ', 351, 24, 3),
(3169, '350604', 'ผือฮี   ', 351, 24, 3),
(3170, '350605', 'บากเรือ   ', 351, 24, 3),
(3171, '350606', 'ม่วง   ', 351, 24, 3),
(3172, '350607', 'โนนทราย   ', 351, 24, 3),
(3173, '350608', 'บึงแก   ', 351, 24, 3),
(3174, '350609', 'พระเสาร์   ', 351, 24, 3),
(3175, '350610', 'สงยาง   ', 351, 24, 3),
(3176, '350696', '*ค้อวัง   ', 351, 24, 3),
(3177, '350697', '*น้ำอ้อม   ', 351, 24, 3),
(3178, '350698', '*กุดน้ำใส   ', 351, 24, 3),
(3179, '350699', '*ฟ้าห่วน   ', 351, 24, 3),
(3180, '350701', 'ฟ้าห่วน   ', 352, 24, 3),
(3181, '350702', 'กุดน้ำใส   ', 352, 24, 3),
(3182, '350703', 'น้ำอ้อม   ', 352, 24, 3),
(3183, '350704', 'ค้อวัง   ', 352, 24, 3),
(3184, '350801', '*น้ำคำ   ', 353, 24, 3),
(3185, '350802', 'บุ่งค้า   ', 353, 24, 3),
(3186, '350803', 'สวาท   ', 353, 24, 3),
(3187, '350804', '*ส้มผ่อ   ', 353, 24, 3),
(3188, '350805', 'ห้องแซง   ', 353, 24, 3),
(3189, '350806', 'สามัคคี   ', 353, 24, 3),
(3190, '350807', 'กุดเชียงหมี   ', 353, 24, 3),
(3191, '350808', '*คำเตย   ', 353, 24, 3),
(3192, '350809', '*คำไผ่   ', 353, 24, 3),
(3193, '350810', 'สามแยก   ', 353, 24, 3),
(3194, '350811', 'กุดแห่   ', 353, 24, 3),
(3195, '350812', 'โคกสำราญ   ', 353, 24, 3),
(3196, '350813', 'สร้างมิ่ง   ', 353, 24, 3),
(3197, '350814', 'ศรีแก้ว   ', 353, 24, 3),
(3198, '350815', '*ไทยเจริญ   ', 353, 24, 3),
(3199, '350895', '*ไทยเจริญ   ', 353, 24, 3),
(3200, '350896', '*คำไผ่   ', 353, 24, 3),
(3201, '350897', '*คำเตย   ', 353, 24, 3),
(3202, '350898', '*ส้มผ่อ   ', 353, 24, 3),
(3203, '350899', '*น้ำคำ   ', 353, 24, 3),
(3204, '350901', 'ไทยเจริญ   ', 354, 24, 3),
(3205, '350902', 'น้ำคำ   ', 354, 24, 3),
(3206, '350903', 'ส้มผ่อ   ', 354, 24, 3),
(3207, '350904', 'คำเตย   ', 354, 24, 3),
(3208, '350905', 'คำไผ่   ', 354, 24, 3),
(3209, '360101', 'ในเมือง   ', 355, 25, 3),
(3210, '360102', 'รอบเมือง   ', 355, 25, 3),
(3211, '360103', 'โพนทอง   ', 355, 25, 3),
(3212, '360104', 'นาฝาย   ', 355, 25, 3),
(3213, '360105', 'บ้านค่าย   ', 355, 25, 3),
(3214, '360106', 'กุดตุ้ม   ', 355, 25, 3),
(3215, '360107', 'ชีลอง   ', 355, 25, 3),
(3216, '360108', 'บ้านเล่า   ', 355, 25, 3),
(3217, '360109', 'นาเสียว   ', 355, 25, 3),
(3218, '360110', 'หนองนาแซง   ', 355, 25, 3),
(3219, '360111', 'ลาดใหญ่   ', 355, 25, 3),
(3220, '360112', 'หนองไผ่   ', 355, 25, 3),
(3221, '360113', 'ท่าหินโงม   ', 355, 25, 3),
(3222, '360114', 'ห้วยต้อน   ', 355, 25, 3),
(3223, '360115', 'ห้วยบง   ', 355, 25, 3),
(3224, '360116', 'โนนสำราญ   ', 355, 25, 3),
(3225, '360117', 'โคกสูง   ', 355, 25, 3),
(3226, '360118', 'บุ่งคล้า   ', 355, 25, 3),
(3227, '360119', 'ซับสีทอง   ', 355, 25, 3),
(3228, '360198', '*เจาทอง   ', 355, 25, 3),
(3229, '360199', '*บ้านเจียง   ', 355, 25, 3),
(3230, '360201', 'บ้านเขว้า   ', 356, 25, 3),
(3231, '360202', 'ตลาดแร้ง   ', 356, 25, 3),
(3232, '360203', 'ลุ่มลำชี   ', 356, 25, 3),
(3233, '360204', 'ชีบน   ', 356, 25, 3),
(3234, '360205', 'ภูแลนคา   ', 356, 25, 3),
(3235, '360206', 'โนนแดง   ', 356, 25, 3),
(3236, '360301', 'คอนสวรรค์   ', 357, 25, 3),
(3237, '360302', 'ยางหวาย   ', 357, 25, 3),
(3238, '360303', 'ช่องสามหมอ   ', 357, 25, 3),
(3239, '360304', 'โนนสะอาด   ', 357, 25, 3),
(3240, '360305', 'ห้วยไร่   ', 357, 25, 3),
(3241, '360306', 'บ้านโสก   ', 357, 25, 3),
(3242, '360307', 'โคกมั่งงอย   ', 357, 25, 3),
(3243, '360308', 'หนองขาม   ', 357, 25, 3),
(3244, '360309', 'ศรีสำราญ   ', 357, 25, 3),
(3245, '360401', 'บ้านยาง   ', 358, 25, 3),
(3246, '360402', 'บ้านหัน   ', 358, 25, 3),
(3247, '360403', 'บ้านเดื่อ   ', 358, 25, 3),
(3248, '360404', 'บ้านเป้า   ', 358, 25, 3),
(3249, '360405', 'กุดเลาะ   ', 358, 25, 3),
(3250, '360406', 'โนนกอก   ', 358, 25, 3),
(3251, '360407', 'สระโพนทอง   ', 358, 25, 3),
(3252, '360408', 'หนองข่า   ', 358, 25, 3),
(3253, '360409', 'หนองโพนงาม   ', 358, 25, 3),
(3254, '360410', 'บ้านบัว   ', 358, 25, 3),
(3255, '360411', 'ซับสีทอง*   ', 358, 25, 3),
(3256, '360412', 'โนนทอง   ', 358, 25, 3),
(3257, '360501', 'หนองบัวแดง   ', 359, 25, 3),
(3258, '360502', 'กุดชุมแสง   ', 359, 25, 3),
(3259, '360503', 'ถ้ำวัวแดง   ', 359, 25, 3),
(3260, '360504', 'นางแดด   ', 359, 25, 3),
(3261, '360505', '*บ้านเจียง   ', 359, 25, 3),
(3262, '360506', '*เจาทอง   ', 359, 25, 3),
(3263, '360507', 'หนองแวง   ', 359, 25, 3),
(3264, '360508', 'คูเมือง   ', 359, 25, 3),
(3265, '360509', 'ท่าใหญ่   ', 359, 25, 3),
(3266, '360510', '*วังทอง   ', 359, 25, 3),
(3267, '360511', 'วังชมภู   ', 359, 25, 3),
(3268, '360598', '*เจาทอง   ', 359, 25, 3),
(3269, '360599', '*บ้านเจียง   ', 359, 25, 3),
(3270, '360601', 'บ้านกอก   ', 360, 25, 3),
(3271, '360602', 'หนองบัวบาน   ', 360, 25, 3),
(3272, '360603', 'บ้านขาม   ', 360, 25, 3),
(3273, '360604', '*หนองฉิม   ', 360, 25, 3),
(3274, '360605', 'กุดน้ำใส   ', 360, 25, 3),
(3275, '360606', 'หนองโดน   ', 360, 25, 3),
(3276, '360607', 'ละหาน   ', 360, 25, 3),
(3277, '360608', '*ตาเนิน   ', 360, 25, 3),
(3278, '360609', '*กะฮาด   ', 360, 25, 3),
(3279, '360610', 'หนองบัวใหญ่   ', 360, 25, 3),
(3280, '360611', 'หนองบัวโคก   ', 360, 25, 3),
(3281, '360612', 'ท่ากูบ*   ', 360, 25, 3),
(3282, '360613', 'ส้มป่อย   ', 360, 25, 3),
(3283, '360614', 'ซับใหญ่*   ', 360, 25, 3),
(3284, '360615', '*รังงาม   ', 360, 25, 3),
(3285, '360616', 'ตะโกทอง*   ', 360, 25, 3),
(3286, '360701', 'บ้านชวน   ', 361, 25, 3),
(3287, '360702', 'บ้านเพชร   ', 361, 25, 3),
(3288, '360703', 'บ้านตาล   ', 361, 25, 3),
(3289, '360704', 'หัวทะเล   ', 361, 25, 3),
(3290, '360705', 'โคกเริงรมย์   ', 361, 25, 3),
(3291, '360706', 'เกาะมะนาว   ', 361, 25, 3),
(3292, '360707', 'โคกเพชรพัฒนา   ', 361, 25, 3),
(3293, '360796', '*บ้านไร่   ', 361, 25, 3),
(3294, '360797', '*นายางกลัก   ', 361, 25, 3),
(3295, '360798', '*ห้วยยายจิ๋ว   ', 361, 25, 3),
(3296, '360799', '*วะตะแบก   ', 361, 25, 3),
(3297, '360801', 'หนองบัวระเหว   ', 362, 25, 3),
(3298, '360802', 'วังตะเฆ่   ', 362, 25, 3),
(3299, '360803', 'ห้วยแย้   ', 362, 25, 3),
(3300, '360804', 'โคกสะอาด   ', 362, 25, 3),
(3301, '360805', 'โสกปลาดุก   ', 362, 25, 3),
(3302, '360901', 'วะตะแบก   ', 363, 25, 3),
(3303, '360902', 'ห้วยยายจิ๋ว   ', 363, 25, 3),
(3304, '360903', 'นายางกลัก   ', 363, 25, 3),
(3305, '360904', 'บ้านไร่   ', 363, 25, 3),
(3306, '360905', 'โป่งนก   ', 363, 25, 3),
(3307, '361001', 'ผักปัง   ', 364, 25, 3),
(3308, '361002', 'กวางโจน   ', 364, 25, 3),
(3309, '361003', 'หนองคอนไทย   ', 364, 25, 3),
(3310, '361004', 'บ้านแก้ง   ', 364, 25, 3),
(3311, '361005', 'กุดยม   ', 364, 25, 3),
(3312, '361006', 'บ้านเพชร   ', 364, 25, 3),
(3313, '361007', 'โคกสะอาด   ', 364, 25, 3),
(3314, '361008', 'หนองตูม   ', 364, 25, 3),
(3315, '361009', 'โอโล   ', 364, 25, 3),
(3316, '361010', 'ธาตุทอง   ', 364, 25, 3),
(3317, '361011', 'บ้านดอน   ', 364, 25, 3),
(3318, '361101', 'บ้านแท่น   ', 365, 25, 3),
(3319, '361102', 'สามสวน   ', 365, 25, 3),
(3320, '361103', 'สระพัง   ', 365, 25, 3),
(3321, '361104', 'บ้านเต่า   ', 365, 25, 3),
(3322, '361105', 'หนองคู   ', 365, 25, 3),
(3323, '361201', 'ช่องสามหมอ   ', 366, 25, 3),
(3324, '361202', 'หนองขาม   ', 366, 25, 3),
(3325, '361203', 'นาหนองทุ่ม   ', 366, 25, 3),
(3326, '361204', 'บ้านแก้ง   ', 366, 25, 3),
(3327, '361205', 'หนองสังข์   ', 366, 25, 3),
(3328, '361206', 'หลุบคา   ', 366, 25, 3),
(3329, '361207', 'โคกกุง   ', 366, 25, 3),
(3330, '361208', 'เก่าย่าดี   ', 366, 25, 3),
(3331, '361209', 'ท่ามะไฟหวาน   ', 366, 25, 3),
(3332, '361210', 'หนองไผ่   ', 366, 25, 3),
(3333, '361301', 'คอนสาร   ', 367, 25, 3),
(3334, '361302', 'ทุ่งพระ   ', 367, 25, 3),
(3335, '361303', 'โนนคูณ   ', 367, 25, 3),
(3336, '361304', 'ห้วยยาง   ', 367, 25, 3),
(3337, '361305', 'ทุ่งลุยลาย   ', 367, 25, 3),
(3338, '361306', 'ดงบัง   ', 367, 25, 3),
(3339, '361307', 'ทุ่งนาเลา   ', 367, 25, 3),
(3340, '361308', 'ดงกลาง   ', 367, 25, 3),
(3341, '361401', 'บ้านเจียง   ', 368, 25, 3),
(3342, '361402', 'เจาทอง   ', 368, 25, 3),
(3343, '361403', 'วังทอง   ', 368, 25, 3),
(3344, '361404', 'แหลมทอง   ', 368, 25, 3),
(3345, '361501', 'หนองฉิม   ', 369, 25, 3),
(3346, '361502', 'ตาเนิน   ', 369, 25, 3),
(3347, '361503', 'กะฮาด   ', 369, 25, 3),
(3348, '361504', 'รังงาม   ', 369, 25, 3),
(3349, '361601', 'ซับใหญ่   ', 370, 25, 3),
(3350, '361602', 'ท่ากูบ   ', 370, 25, 3),
(3351, '361603', 'ตะโกทอง   ', 370, 25, 3),
(3352, '370101', 'บุ่ง   ', 380, 26, 3),
(3353, '370102', 'ไก่คำ   ', 380, 26, 3),
(3354, '370103', 'นาจิก   ', 380, 26, 3),
(3355, '370104', 'ปลาค้าว   ', 380, 26, 3),
(3356, '370105', 'เหล่าพรวน   ', 380, 26, 3),
(3357, '370106', 'สร้างนกทา   ', 380, 26, 3),
(3358, '370107', 'คึมใหญ่   ', 380, 26, 3),
(3359, '370108', 'นาผือ   ', 380, 26, 3),
(3360, '370109', 'น้ำปลีก   ', 380, 26, 3),
(3361, '370110', 'นาวัง   ', 380, 26, 3),
(3362, '370111', 'นาหมอม้า   ', 380, 26, 3),
(3363, '370112', 'โนนโพธิ์   ', 380, 26, 3),
(3364, '370113', 'โนนหนามแท่ง   ', 380, 26, 3),
(3365, '370114', 'ห้วยไร่   ', 380, 26, 3),
(3366, '370115', 'หนองมะแซว   ', 380, 26, 3),
(3367, '370116', 'กุดปลาดุก   ', 380, 26, 3),
(3368, '370117', 'ดอนเมย   ', 380, 26, 3),
(3369, '370118', 'นายม   ', 380, 26, 3),
(3370, '370119', 'นาแต้   ', 380, 26, 3),
(3371, '370190', '*โพนทอง   ', 380, 26, 3),
(3372, '370191', '*ดงมะยาง   ', 380, 26, 3),
(3373, '370192', '*เปือย   ', 380, 26, 3),
(3374, '370193', '*หนองไฮ   ', 380, 26, 3),
(3375, '370194', '*นาเวียง   ', 380, 26, 3),
(3376, '370195', '*ไร่ขี   ', 380, 26, 3),
(3377, '370196', '*ไร่สีสุก   ', 380, 26, 3),
(3378, '370197', '*เสนางคนิคม   ', 380, 26, 3),
(3379, '370198', '*อำนาจ   ', 380, 26, 3),
(3380, '370199', '*ดงบัง   ', 380, 26, 3),
(3381, '370201', 'ชานุมาน   ', 381, 26, 3),
(3382, '370202', 'โคกสาร   ', 381, 26, 3),
(3383, '370203', 'คำเขื่อนแก้ว   ', 381, 26, 3),
(3384, '370204', 'โคกก่ง   ', 381, 26, 3),
(3385, '370205', 'ป่าก่อ   ', 381, 26, 3),
(3386, '370299', '*หนองข่า   ', 381, 26, 3),
(3387, '370301', 'หนองข่า   ', 382, 26, 3),
(3388, '370302', 'คำโพน   ', 382, 26, 3),
(3389, '370303', 'นาหว้า   ', 382, 26, 3),
(3390, '370304', 'ลือ   ', 382, 26, 3),
(3391, '370305', 'ห้วย   ', 382, 26, 3),
(3392, '370306', 'โนนงาม   ', 382, 26, 3),
(3393, '370307', 'นาป่าแซง   ', 382, 26, 3),
(3394, '370401', 'พนา   ', 383, 26, 3),
(3395, '370402', 'จานลาน   ', 383, 26, 3),
(3396, '370403', 'ไม้กลอน   ', 383, 26, 3),
(3397, '370404', 'พระเหลา   ', 383, 26, 3),
(3398, '370497', '*นาหว้า   ', 383, 26, 3),
(3399, '370498', '*ลือ   ', 383, 26, 3),
(3400, '370499', '*ห้วย   ', 383, 26, 3),
(3401, '370501', 'เสนางคนิคม   ', 384, 26, 3),
(3402, '370502', 'โพนทอง   ', 384, 26, 3),
(3403, '370503', 'ไร่สีสุก   ', 384, 26, 3),
(3404, '370504', 'นาเวียง   ', 384, 26, 3),
(3405, '370505', 'หนองไฮ   ', 384, 26, 3),
(3406, '370506', 'หนองสามสี   ', 384, 26, 3),
(3407, '370601', 'หัวตะพาน   ', 385, 26, 3),
(3408, '370602', 'คำพระ   ', 385, 26, 3),
(3409, '370603', 'เค็งใหญ่   ', 385, 26, 3),
(3410, '370604', 'หนองแก้ว   ', 385, 26, 3),
(3411, '370605', 'โพนเมืองน้อย   ', 385, 26, 3),
(3412, '370606', 'สร้างถ่อน้อย   ', 385, 26, 3),
(3413, '370607', 'จิกดู่   ', 385, 26, 3),
(3414, '370608', 'รัตนวารี   ', 385, 26, 3),
(3415, '370701', 'อำนาจ   ', 386, 26, 3),
(3416, '370702', 'ดงมะยาง   ', 386, 26, 3),
(3417, '370703', 'เปือย   ', 386, 26, 3),
(3418, '370704', 'ดงบัง   ', 386, 26, 3),
(3419, '370705', 'ไร่ขี   ', 386, 26, 3),
(3420, '370706', 'แมด   ', 386, 26, 3),
(3421, '370707', 'โคกกลาง   ', 386, 26, 3),
(3422, '390101', 'หนองบัว   ', 387, 27, 3),
(3423, '390102', 'หนองภัยศูนย์   ', 387, 27, 3),
(3424, '390103', 'โพธิ์ชัย   ', 387, 27, 3),
(3425, '390104', 'หนองสวรรค์   ', 387, 27, 3),
(3426, '390105', 'หัวนา   ', 387, 27, 3),
(3427, '390106', 'บ้านขาม   ', 387, 27, 3),
(3428, '390107', 'นามะเฟือง   ', 387, 27, 3),
(3429, '390108', 'บ้านพร้าว   ', 387, 27, 3),
(3430, '390109', 'โนนขมิ้น   ', 387, 27, 3),
(3431, '390110', 'ลำภู   ', 387, 27, 3),
(3432, '390111', 'กุดจิก   ', 387, 27, 3),
(3433, '390112', 'โนนทัน   ', 387, 27, 3),
(3434, '390113', 'นาคำไฮ   ', 387, 27, 3),
(3435, '390114', 'ป่าไม้งาม   ', 387, 27, 3),
(3436, '390115', 'หนองหว้า   ', 387, 27, 3),
(3437, '390201', 'นากลาง   ', 388, 27, 3),
(3438, '390202', 'ด่านช้าง   ', 388, 27, 3),
(3439, '390203', '*นาเหล่า   ', 388, 27, 3),
(3440, '390204', '*นาแก   ', 388, 27, 3),
(3441, '390205', 'กุดดินจี่   ', 388, 27, 3),
(3442, '390206', 'ฝั่งแดง   ', 388, 27, 3),
(3443, '390207', 'เก่ากลอย   ', 388, 27, 3),
(3444, '390208', '*วังทอง   ', 388, 27, 3),
(3445, '390209', 'โนนเมือง   ', 388, 27, 3),
(3446, '390210', 'อุทัยสวรรค์   ', 388, 27, 3),
(3447, '390211', 'ดงสวรรค์   ', 388, 27, 3),
(3448, '390212', '*วังปลาป้อม   ', 388, 27, 3),
(3449, '390213', 'กุดแห่   ', 388, 27, 3),
(3450, '390214', '*เทพคีรี   ', 388, 27, 3),
(3451, '390215', 'โนนภูทอง*   ', 388, 27, 3),
(3452, '390296', '*นาดี   ', 388, 27, 3),
(3453, '390297', '*นาสี   ', 388, 27, 3),
(3454, '390298', '*บ้านโคก   ', 388, 27, 3),
(3455, '390299', '*โคกนาเหล่า   ', 388, 27, 3),
(3456, '390301', 'โนนสัง   ', 389, 27, 3),
(3457, '390302', 'บ้านถิ่น   ', 389, 27, 3),
(3458, '390303', 'หนองเรือ   ', 389, 27, 3),
(3459, '390304', 'กุดดู่   ', 389, 27, 3),
(3460, '390305', 'บ้านค้อ   ', 389, 27, 3),
(3461, '390306', 'โนนเมือง   ', 389, 27, 3),
(3462, '390307', 'โคกใหญ่   ', 389, 27, 3),
(3463, '390308', 'โคกม่วง   ', 389, 27, 3),
(3464, '390309', 'นิคมพัฒนา   ', 389, 27, 3),
(3465, '390310', 'ปางกู่   ', 389, 27, 3),
(3466, '390401', 'เมืองใหม่   ', 390, 27, 3),
(3467, '390402', 'ศรีบุญเรือง   ', 390, 27, 3),
(3468, '390403', 'หนองบัวใต้   ', 390, 27, 3),
(3469, '390404', 'กุดสะเทียน   ', 390, 27, 3),
(3470, '390405', 'นากอก   ', 390, 27, 3),
(3471, '390406', 'โนนสะอาด   ', 390, 27, 3),
(3472, '390407', 'ยางหล่อ   ', 390, 27, 3),
(3473, '390408', 'โนนม่วง   ', 390, 27, 3),
(3474, '390409', 'หนองกุงแก้ว   ', 390, 27, 3),
(3475, '390410', 'หนองแก   ', 390, 27, 3),
(3476, '390411', 'ทรายทอง   ', 390, 27, 3),
(3477, '390412', 'หันนางาม   ', 390, 27, 3),
(3478, '390501', 'นาสี   ', 391, 27, 3),
(3479, '390502', 'บ้านโคก   ', 391, 27, 3),
(3480, '390503', 'นาดี   ', 391, 27, 3),
(3481, '390504', 'นาด่าน   ', 391, 27, 3),
(3482, '390505', 'ดงมะไฟ   ', 391, 27, 3),
(3483, '390506', 'สุวรรณคูหา   ', 391, 27, 3),
(3484, '390507', 'บุญทัน   ', 391, 27, 3),
(3485, '390508', 'กุดผึ้ง   ', 391, 27, 3),
(3486, '390601', 'นาเหล่า   ', 392, 27, 3),
(3487, '390602', 'นาแก   ', 392, 27, 3),
(3488, '390603', 'วังทอง   ', 392, 27, 3),
(3489, '390604', 'วังปลาป้อม   ', 392, 27, 3),
(3490, '390605', 'เทพคีรี   ', 392, 27, 3),
(3491, '400101', 'ในเมือง   ', 393, 28, 3),
(3492, '400102', 'สำราญ   ', 393, 28, 3),
(3493, '400103', 'โคกสี   ', 393, 28, 3),
(3494, '400104', 'ท่าพระ   ', 393, 28, 3),
(3495, '400105', 'บ้านทุ่ม   ', 393, 28, 3),
(3496, '400106', 'เมืองเก่า   ', 393, 28, 3),
(3497, '400107', 'พระลับ   ', 393, 28, 3),
(3498, '400108', 'สาวะถี   ', 393, 28, 3),
(3499, '400109', 'บ้านหว้า   ', 393, 28, 3),
(3500, '400110', 'บ้านค้อ   ', 393, 28, 3),
(3501, '400111', 'แดงใหญ่   ', 393, 28, 3),
(3502, '400112', 'ดอนช้าง   ', 393, 28, 3),
(3503, '400113', 'ดอนหัน   ', 393, 28, 3),
(3504, '400114', 'ศิลา   ', 393, 28, 3),
(3505, '400115', 'บ้านเป็ด   ', 393, 28, 3),
(3506, '400116', 'หนองตูม   ', 393, 28, 3),
(3507, '400117', 'บึงเนียม   ', 393, 28, 3),
(3508, '400118', 'โนนท่อน   ', 393, 28, 3),
(3509, '400198', '*บ้านโต้น   ', 393, 28, 3),
(3510, '400199', '*หนองบัว   ', 393, 28, 3),
(3511, '400201', 'หนองบัว   ', 394, 28, 3),
(3512, '400202', 'ป่าหวายนั่ง   ', 394, 28, 3),
(3513, '400203', 'โนนฆ้อง   ', 394, 28, 3),
(3514, '400204', 'บ้านเหล่า   ', 394, 28, 3),
(3515, '400205', 'ป่ามะนาว   ', 394, 28, 3),
(3516, '400206', 'บ้านฝาง   ', 394, 28, 3),
(3517, '400207', 'โคกงาม   ', 394, 28, 3),
(3518, '400301', 'พระยืน   ', 395, 28, 3),
(3519, '400302', 'พระบุ   ', 395, 28, 3),
(3520, '400303', 'บ้านโต้น   ', 395, 28, 3),
(3521, '400304', 'หนองแวง   ', 395, 28, 3),
(3522, '400305', 'ขามป้อม   ', 395, 28, 3),
(3523, '400401', 'หนองเรือ   ', 396, 28, 3),
(3524, '400402', 'บ้านเม็ง   ', 396, 28, 3),
(3525, '400403', 'บ้านกง   ', 396, 28, 3),
(3526, '400404', 'ยางคำ   ', 396, 28, 3),
(3527, '400405', 'จระเข้   ', 396, 28, 3),
(3528, '400406', 'โนนทอง   ', 396, 28, 3),
(3529, '400407', 'กุดกว้าง   ', 396, 28, 3),
(3530, '400408', 'โนนทัน   ', 396, 28, 3),
(3531, '400409', 'โนนสะอาด   ', 396, 28, 3),
(3532, '400410', 'บ้านผือ   ', 396, 28, 3),
(3533, '400501', 'ชุมแพ   ', 397, 28, 3),
(3534, '400502', 'โนนหัน   ', 397, 28, 3),
(3535, '400503', 'นาหนองทุ่ม   ', 397, 28, 3),
(3536, '400504', 'โนนอุดม   ', 397, 28, 3),
(3537, '400505', 'ขัวเรียง   ', 397, 28, 3),
(3538, '400506', 'หนองไผ่   ', 397, 28, 3),
(3539, '400507', 'ไชยสอ   ', 397, 28, 3),
(3540, '400508', 'วังหินลาด   ', 397, 28, 3),
(3541, '400509', 'นาเพียง   ', 397, 28, 3),
(3542, '400510', 'หนองเขียด   ', 397, 28, 3),
(3543, '400511', 'หนองเสาเล้า   ', 397, 28, 3),
(3544, '400512', 'โนนสะอาด   ', 397, 28, 3),
(3545, '400601', 'สีชมพู   ', 398, 28, 3),
(3546, '400602', 'ศรีสุข   ', 398, 28, 3),
(3547, '400603', 'นาจาน   ', 398, 28, 3),
(3548, '400604', 'วังเพิ่ม   ', 398, 28, 3),
(3549, '400605', 'ซำยาง   ', 398, 28, 3),
(3550, '400606', 'หนองแดง   ', 398, 28, 3),
(3551, '400607', 'ดงลาน   ', 398, 28, 3),
(3552, '400608', 'บริบูรณ์   ', 398, 28, 3),
(3553, '400609', 'บ้านใหม่   ', 398, 28, 3),
(3554, '400610', 'ภูห่าน   ', 398, 28, 3),
(3555, '400701', 'น้ำพอง   ', 399, 28, 3),
(3556, '400702', 'วังชัย   ', 399, 28, 3),
(3557, '400703', 'หนองกุง   ', 399, 28, 3),
(3558, '400704', 'บัวใหญ่   ', 399, 28, 3),
(3559, '400705', 'สะอาด   ', 399, 28, 3),
(3560, '400706', 'ม่วงหวาน   ', 399, 28, 3),
(3561, '400707', 'บ้านขาม   ', 399, 28, 3),
(3562, '400708', 'บัวเงิน   ', 399, 28, 3),
(3563, '400709', 'ทรายมูล   ', 399, 28, 3),
(3564, '400710', 'ท่ากระเสริม   ', 399, 28, 3),
(3565, '400711', 'พังทุย   ', 399, 28, 3),
(3566, '400712', 'กุดน้ำใส   ', 399, 28, 3),
(3567, '400801', 'โคกสูง   ', 400, 28, 3),
(3568, '400802', 'บ้านดง   ', 400, 28, 3),
(3569, '400803', 'เขื่อนอุบลรัตน์   ', 400, 28, 3),
(3570, '400804', 'นาคำ   ', 400, 28, 3),
(3571, '400805', 'ศรีสุขสำราญ   ', 400, 28, 3),
(3572, '400806', 'ทุ่งโป่ง   ', 400, 28, 3),
(3573, '400901', 'หนองโก   ', 401, 28, 3),
(3574, '400902', 'หนองกุงใหญ่   ', 401, 28, 3),
(3575, '400903', '*กระนวน   ', 401, 28, 3),
(3576, '400904', '*บ้านโนน   ', 401, 28, 3),
(3577, '400905', 'ห้วยโจด   ', 401, 28, 3),
(3578, '400906', 'ห้วยยาง   ', 401, 28, 3),
(3579, '400907', 'บ้านฝาง   ', 401, 28, 3),
(3580, '400908', '*คำแมด   ', 401, 28, 3);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(3581, '400909', 'ดูนสาด   ', 401, 28, 3),
(3582, '400910', 'หนองโน   ', 401, 28, 3),
(3583, '400911', 'น้ำอ้อม   ', 401, 28, 3),
(3584, '400912', 'หัวนาคำ   ', 401, 28, 3),
(3585, '400913', '*คูคำ   ', 401, 28, 3),
(3586, '400914', '*ห้วยเตย   ', 401, 28, 3),
(3587, '401001', 'บ้านไผ่   ', 402, 28, 3),
(3588, '401002', 'ในเมือง   ', 402, 28, 3),
(3589, '401003', '*บ้านแฮด   ', 402, 28, 3),
(3590, '401004', '*โคกสำราญ   ', 402, 28, 3),
(3591, '401005', 'เมืองเพีย   ', 402, 28, 3),
(3592, '401006', 'เปือยใหญ่*   ', 402, 28, 3),
(3593, '401007', 'โนนศิลา*   ', 402, 28, 3),
(3594, '401008', 'บ้านหัน*   ', 402, 28, 3),
(3595, '401009', 'บ้านลาน   ', 402, 28, 3),
(3596, '401010', 'แคนเหนือ   ', 402, 28, 3),
(3597, '401011', 'ภูเหล็ก   ', 402, 28, 3),
(3598, '401012', '*หนองแซง   ', 402, 28, 3),
(3599, '401013', 'ป่าปอ   ', 402, 28, 3),
(3600, '401014', 'หินตั้ง   ', 402, 28, 3),
(3601, '401015', '*โนนสมบูรณ์   ', 402, 28, 3),
(3602, '401016', 'หนองน้ำใส   ', 402, 28, 3),
(3603, '401017', 'หัวหนอง   ', 402, 28, 3),
(3604, '401018', '*บ้านแฮด   ', 402, 28, 3),
(3605, '401019', 'โนนแดง*   ', 402, 28, 3),
(3606, '401020', 'หนองปลาหมอ*   ', 402, 28, 3),
(3607, '401096', '*สระแก้ว   ', 402, 28, 3),
(3608, '401097', '*ขามป้อม   ', 402, 28, 3),
(3609, '401098', '*วังม่วง   ', 402, 28, 3),
(3610, '401099', '*เปือยน้อย   ', 402, 28, 3),
(3611, '401101', 'เปือยน้อย   ', 403, 28, 3),
(3612, '401102', 'วังม่วง   ', 403, 28, 3),
(3613, '401103', 'ขามป้อม   ', 403, 28, 3),
(3614, '401104', 'สระแก้ว   ', 403, 28, 3),
(3615, '401201', 'เมืองพล   ', 404, 28, 3),
(3616, '401203', 'โจดหนองแก   ', 404, 28, 3),
(3617, '401204', 'เก่างิ้ว   ', 404, 28, 3),
(3618, '401205', 'หนองมะเขือ   ', 404, 28, 3),
(3619, '401206', 'หนองแวงโสกพระ   ', 404, 28, 3),
(3620, '401207', 'เพ็กใหญ่   ', 404, 28, 3),
(3621, '401208', 'โคกสง่า   ', 404, 28, 3),
(3622, '401209', 'หนองแวงนางเบ้า   ', 404, 28, 3),
(3623, '401210', 'ลอมคอม   ', 404, 28, 3),
(3624, '401211', 'โนนข่า   ', 404, 28, 3),
(3625, '401212', 'โสกนกเต็น   ', 404, 28, 3),
(3626, '401213', 'หัวทุ่ง   ', 404, 28, 3),
(3627, '401290', '*ทางขวาง   ', 404, 28, 3),
(3628, '401291', '*ท่าวัด   ', 404, 28, 3),
(3629, '401292', '*ท่านางแมว   ', 404, 28, 3),
(3630, '401293', '*แวงน้อย   ', 404, 28, 3),
(3631, '401294', '*ก้านเหลือง   ', 404, 28, 3),
(3632, '401295', '*ละหารนา   ', 404, 28, 3),
(3633, '401296', '*แวงใหญ่   ', 404, 28, 3),
(3634, '401297', '*โนนทอง   ', 404, 28, 3),
(3635, '401298', '*ใหม่นาเพียง   ', 404, 28, 3),
(3636, '401299', '*คอนฉิม   ', 404, 28, 3),
(3637, '401301', 'คอนฉิม   ', 405, 28, 3),
(3638, '401302', 'ใหม่นาเพียง   ', 405, 28, 3),
(3639, '401303', 'โนนทอง   ', 405, 28, 3),
(3640, '401304', 'แวงใหญ่   ', 405, 28, 3),
(3641, '401305', 'โนนสะอาด   ', 405, 28, 3),
(3642, '401401', 'แวงน้อย   ', 406, 28, 3),
(3643, '401402', 'ก้านเหลือง   ', 406, 28, 3),
(3644, '401403', 'ท่านางแนว   ', 406, 28, 3),
(3645, '401404', 'ละหานนา   ', 406, 28, 3),
(3646, '401405', 'ท่าวัด   ', 406, 28, 3),
(3647, '401406', 'ทางขวาง   ', 406, 28, 3),
(3648, '401501', 'หนองสองห้อง   ', 407, 28, 3),
(3649, '401502', 'คึมชาด   ', 407, 28, 3),
(3650, '401503', 'โนนธาตุ   ', 407, 28, 3),
(3651, '401504', 'ตะกั่วป่า   ', 407, 28, 3),
(3652, '401505', 'สำโรง   ', 407, 28, 3),
(3653, '401506', 'หนองเม็ก   ', 407, 28, 3),
(3654, '401507', 'ดอนดู่   ', 407, 28, 3),
(3655, '401508', 'ดงเค็ง   ', 407, 28, 3),
(3656, '401509', 'หันโจด   ', 407, 28, 3),
(3657, '401510', 'ดอนดั่ง   ', 407, 28, 3),
(3658, '401511', 'วังหิน   ', 407, 28, 3),
(3659, '401512', 'หนองไผ่ล้อม   ', 407, 28, 3),
(3660, '401601', 'บ้านเรือ   ', 408, 28, 3),
(3661, '401602', 'ในเมือง*   ', 408, 28, 3),
(3662, '401603', '*บ้านโคก   ', 408, 28, 3),
(3663, '401604', 'หว้าทอง   ', 408, 28, 3),
(3664, '401605', 'กุดขอนแก่น   ', 408, 28, 3),
(3665, '401606', 'นาชุมแสง   ', 408, 28, 3),
(3666, '401607', 'นาหว้า   ', 408, 28, 3),
(3667, '401608', 'เขาน้อย*   ', 408, 28, 3),
(3668, '401609', '*กุดธาตุ   ', 408, 28, 3),
(3669, '401610', 'หนองกุงธนสาร   ', 408, 28, 3),
(3670, '401611', '*ขนวน   ', 408, 28, 3),
(3671, '401612', 'หนองกุงเซิน   ', 408, 28, 3),
(3672, '401613', 'สงเปือย   ', 408, 28, 3),
(3673, '401614', 'ทุ่งชมพู   ', 408, 28, 3),
(3674, '401615', 'เมืองเก่าพัฒนา*   ', 408, 28, 3),
(3675, '401616', 'ดินดำ   ', 408, 28, 3),
(3676, '401617', 'ภูเวียง   ', 408, 28, 3),
(3677, '401701', 'กุดเค้า   ', 409, 28, 3),
(3678, '401702', 'สวนหม่อน   ', 409, 28, 3),
(3679, '401703', 'หนองแปน   ', 409, 28, 3),
(3680, '401704', 'โพนเพ็ก   ', 409, 28, 3),
(3681, '401705', 'คำแคน   ', 409, 28, 3),
(3682, '401706', 'นาข่า   ', 409, 28, 3),
(3683, '401707', 'นางาม   ', 409, 28, 3),
(3684, '401708', '*บ้านโคก   ', 409, 28, 3),
(3685, '401709', '*โพธิ์ไชย   ', 409, 28, 3),
(3686, '401710', 'ท่าศาลา   ', 409, 28, 3),
(3687, '401711', '*ซับสมบูรณ์   ', 409, 28, 3),
(3688, '401712', '*นาแพง   ', 409, 28, 3),
(3689, '401801', 'ชนบท   ', 410, 28, 3),
(3690, '401802', 'กุดเพียขอม   ', 410, 28, 3),
(3691, '401803', 'วังแสง   ', 410, 28, 3),
(3692, '401804', 'ห้วยแก   ', 410, 28, 3),
(3693, '401805', 'บ้านแท่น   ', 410, 28, 3),
(3694, '401806', 'ศรีบุญเรือง   ', 410, 28, 3),
(3695, '401807', 'โนนพะยอม   ', 410, 28, 3),
(3696, '401808', 'ปอแดง   ', 410, 28, 3),
(3697, '401901', 'เขาสวนกวาง   ', 411, 28, 3),
(3698, '401902', 'ดงเมืองแอม   ', 411, 28, 3),
(3699, '401903', 'นางิ้ว   ', 411, 28, 3),
(3700, '401904', 'โนนสมบูรณ์   ', 411, 28, 3),
(3701, '401905', 'คำม่วง   ', 411, 28, 3),
(3702, '402001', 'โนนคอม   ', 412, 28, 3),
(3703, '402002', 'นาฝาย   ', 412, 28, 3),
(3704, '402003', 'ภูผาม่าน   ', 412, 28, 3),
(3705, '402004', 'วังสวาบ   ', 412, 28, 3),
(3706, '402005', 'ห้วยม่วง   ', 412, 28, 3),
(3707, '402101', 'กระนวน   ', 413, 28, 3),
(3708, '402102', 'คำแมด   ', 413, 28, 3),
(3709, '402103', 'บ้านโนน   ', 413, 28, 3),
(3710, '402104', 'คูคำ   ', 413, 28, 3),
(3711, '402105', 'ห้วยเตย   ', 413, 28, 3),
(3712, '402201', 'บ้านโคก   ', 414, 28, 3),
(3713, '402202', 'โพธิ์ไชย   ', 414, 28, 3),
(3714, '402203', 'ซับสมบูรณ์   ', 414, 28, 3),
(3715, '402204', 'นาแพง   ', 414, 28, 3),
(3716, '402301', 'กุดธาตุ   ', 415, 28, 3),
(3717, '402302', 'บ้านโคก   ', 415, 28, 3),
(3718, '402303', 'ขนวน   ', 415, 28, 3),
(3719, '402401', 'บ้านแฮด   ', 416, 28, 3),
(3720, '402402', 'โคกสำราญ   ', 416, 28, 3),
(3721, '402403', 'โนนสมบูรณ์   ', 416, 28, 3),
(3722, '402404', 'หนองแซง   ', 416, 28, 3),
(3723, '402501', 'โนนศิลา   ', 417, 28, 3),
(3724, '402502', 'หนองปลาหมอ   ', 417, 28, 3),
(3725, '402503', 'บ้านหัน   ', 417, 28, 3),
(3726, '402504', 'เปือยใหญ่   ', 417, 28, 3),
(3727, '402505', 'โนนแดง   ', 417, 28, 3),
(3728, '402901', 'ในเมือง   ', 418, 28, 3),
(3729, '402902', 'เมืองเก่าพัฒนา   ', 418, 28, 3),
(3730, '402903', 'เขาน้อย   ', 418, 28, 3),
(3731, '406801', 'บ้านเป็ด*   ', 419, 28, 3),
(3732, '410101', 'หมากแข้ง   ', 421, 29, 3),
(3733, '410102', 'นิคมสงเคราะห์   ', 421, 29, 3),
(3734, '410103', 'บ้านขาว   ', 421, 29, 3),
(3735, '410104', 'หนองบัว   ', 421, 29, 3),
(3736, '410105', 'บ้านตาด   ', 421, 29, 3),
(3737, '410106', 'โนนสูง   ', 421, 29, 3),
(3738, '410107', 'หมูม่น   ', 421, 29, 3),
(3739, '410108', 'เชียงยืน   ', 421, 29, 3),
(3740, '410109', 'หนองนาคำ   ', 421, 29, 3),
(3741, '410110', 'กุดสระ   ', 421, 29, 3),
(3742, '410111', 'นาดี   ', 421, 29, 3),
(3743, '410112', 'บ้านเลื่อม   ', 421, 29, 3),
(3744, '410113', 'เชียงพิณ   ', 421, 29, 3),
(3745, '410114', 'สามพร้าว   ', 421, 29, 3),
(3746, '410115', 'หนองไฮ   ', 421, 29, 3),
(3747, '410116', 'นาข่า   ', 421, 29, 3),
(3748, '410117', 'บ้านจั่น   ', 421, 29, 3),
(3749, '410118', 'หนองขอนกว้าง   ', 421, 29, 3),
(3750, '410119', 'โคกสะอาด   ', 421, 29, 3),
(3751, '410120', 'นากว้าง   ', 421, 29, 3),
(3752, '410121', 'หนองไผ่   ', 421, 29, 3),
(3753, '410190', '*ขอนยูง   ', 421, 29, 3),
(3754, '410191', '*ปะโค   ', 421, 29, 3),
(3755, '410194', '*หนองหว้า   ', 421, 29, 3),
(3756, '410195', '*ขอนยูง   ', 421, 29, 3),
(3757, '410196', '*ปะโค   ', 421, 29, 3),
(3758, '410197', '*เชียงเพ็ง   ', 421, 29, 3),
(3759, '410198', '*กุดจับ   ', 421, 29, 3),
(3760, '410199', '*หนองปุ   ', 421, 29, 3),
(3761, '410201', 'กุดจับ   ', 422, 29, 3),
(3762, '410202', 'ปะโค   ', 422, 29, 3),
(3763, '410203', 'ขอนยูง   ', 422, 29, 3),
(3764, '410204', 'เชียงเพ็ง   ', 422, 29, 3),
(3765, '410205', 'สร้างก่อ   ', 422, 29, 3),
(3766, '410206', 'เมืองเพีย   ', 422, 29, 3),
(3767, '410207', 'ตาลเลียน   ', 422, 29, 3),
(3768, '410301', 'หมากหญ้า   ', 423, 29, 3),
(3769, '410302', 'หนองอ้อ   ', 423, 29, 3),
(3770, '410303', 'อูบมุง   ', 423, 29, 3),
(3771, '410304', 'กุดหมากไฟ   ', 423, 29, 3),
(3772, '410305', 'น้ำพ่น   ', 423, 29, 3),
(3773, '410306', 'หนองบัวบาน   ', 423, 29, 3),
(3774, '410307', 'โนนหวาย   ', 423, 29, 3),
(3775, '410308', 'หนองวัวซอ   ', 423, 29, 3),
(3776, '410401', 'ตูมใต้   ', 424, 29, 3),
(3777, '410402', 'พันดอน   ', 424, 29, 3),
(3778, '410403', 'เวียงคำ   ', 424, 29, 3),
(3779, '410404', 'แชแล   ', 424, 29, 3),
(3780, '410405', 'อุ่มจาน*   ', 424, 29, 3),
(3781, '410406', 'เชียงแหว   ', 424, 29, 3),
(3782, '410407', 'ห้วยเกิ้ง   ', 424, 29, 3),
(3783, '410408', 'ห้วยสามพาด*   ', 424, 29, 3),
(3784, '410409', 'เสอเพลอ   ', 424, 29, 3),
(3785, '410410', 'สีออ   ', 424, 29, 3),
(3786, '410411', 'ปะโค   ', 424, 29, 3),
(3787, '410412', 'นาม่วง*   ', 424, 29, 3),
(3788, '410413', 'ผาสุก   ', 424, 29, 3),
(3789, '410414', 'ท่าลี่   ', 424, 29, 3),
(3790, '410415', 'กุมภวาปี   ', 424, 29, 3),
(3791, '410416', 'หนองหว้า   ', 424, 29, 3),
(3792, '410495', '*โนนสะอาด   ', 424, 29, 3),
(3793, '410496', '*โพธิ์ศรีสำราญ   ', 424, 29, 3),
(3794, '410497', '*บุ่งแก้ว   ', 424, 29, 3),
(3795, '410498', '*หนองแสง   ', 424, 29, 3),
(3796, '410499', '*แสงสว่าง   ', 424, 29, 3),
(3797, '410501', 'โนนสะอาด   ', 425, 29, 3),
(3798, '410502', 'บุ่งแก้ว   ', 425, 29, 3),
(3799, '410503', 'โพธิ์ศรีสำราญ   ', 425, 29, 3),
(3800, '410504', 'ทมนางาม   ', 425, 29, 3),
(3801, '410505', 'หนองกุงศรี   ', 425, 29, 3),
(3802, '410506', 'โคกกลาง   ', 425, 29, 3),
(3803, '410601', 'หนองหาน   ', 426, 29, 3),
(3804, '410602', 'หนองเม็ก   ', 426, 29, 3),
(3805, '410603', '*คอนสาย   ', 426, 29, 3),
(3806, '410604', '*บ้านจีต   ', 426, 29, 3),
(3807, '410605', 'พังงู   ', 426, 29, 3),
(3808, '410606', 'สะแบง   ', 426, 29, 3),
(3809, '410607', 'สร้อยพร้าว   ', 426, 29, 3),
(3810, '410608', '*บ้านแดง   ', 426, 29, 3),
(3811, '410609', 'บ้านเชียง   ', 426, 29, 3),
(3812, '410610', 'บ้านยา   ', 426, 29, 3),
(3813, '410611', 'โพนงาม   ', 426, 29, 3),
(3814, '410612', 'ผักตบ   ', 426, 29, 3),
(3815, '410613', '*ดอนกลอย   ', 426, 29, 3),
(3816, '410614', 'หนองไผ่   ', 426, 29, 3),
(3817, '410615', '*นาทราย   ', 426, 29, 3),
(3818, '410616', '*ค้อใหญ่   ', 426, 29, 3),
(3819, '410617', 'ดอนหายโศก   ', 426, 29, 3),
(3820, '410618', 'หนองสระปลา   ', 426, 29, 3),
(3821, '410619', '*โนนทองอินทร์   ', 426, 29, 3),
(3822, '410694', '*หนองหลัก   ', 426, 29, 3),
(3823, '410695', '*บ้านแดง   ', 426, 29, 3),
(3824, '410696', '*ทุ่งใหญ่   ', 426, 29, 3),
(3825, '410697', '*ทุ่งฝน   ', 426, 29, 3),
(3826, '410698', '*โพนสูง   ', 426, 29, 3),
(3827, '410699', '*ไชยวาน   ', 426, 29, 3),
(3828, '410701', 'ทุ่งฝน   ', 427, 29, 3),
(3829, '410702', 'ทุ่งใหญ่   ', 427, 29, 3),
(3830, '410703', 'นาชุมแสง   ', 427, 29, 3),
(3831, '410704', 'นาทม   ', 427, 29, 3),
(3832, '410801', 'ไชยวาน   ', 428, 29, 3),
(3833, '410802', 'หนองหลัก   ', 428, 29, 3),
(3834, '410803', 'คำเลาะ   ', 428, 29, 3),
(3835, '410804', 'โพนสูง   ', 428, 29, 3),
(3836, '410901', 'ศรีธาตุ   ', 429, 29, 3),
(3837, '410902', 'จำปี   ', 429, 29, 3),
(3838, '410903', 'บ้านโปร่ง   ', 429, 29, 3),
(3839, '410904', 'หัวนาคำ   ', 429, 29, 3),
(3840, '410905', 'หนองนกเขียน   ', 429, 29, 3),
(3841, '410906', 'นายูง   ', 429, 29, 3),
(3842, '410907', 'ตาดทอง   ', 429, 29, 3),
(3843, '411001', 'หนองกุงทับม้า   ', 430, 29, 3),
(3844, '411002', 'หนองหญ้าไซ   ', 430, 29, 3),
(3845, '411003', 'บะยาว   ', 430, 29, 3),
(3846, '411004', 'ผาสุก   ', 430, 29, 3),
(3847, '411005', 'คำโคกสูง   ', 430, 29, 3),
(3848, '411006', 'วังสามหมอ   ', 430, 29, 3),
(3849, '411101', 'ศรีสุทโธ   ', 431, 29, 3),
(3850, '411102', 'บ้านดุง   ', 431, 29, 3),
(3851, '411103', 'ดงเย็น   ', 431, 29, 3),
(3852, '411104', 'โพนสูง   ', 431, 29, 3),
(3853, '411105', 'อ้อมกอ   ', 431, 29, 3),
(3854, '411106', 'บ้านจันทน์   ', 431, 29, 3),
(3855, '411107', 'บ้านชัย   ', 431, 29, 3),
(3856, '411108', 'นาไหม   ', 431, 29, 3),
(3857, '411109', 'ถ่อนนาลับ   ', 431, 29, 3),
(3858, '411110', 'วังทอง   ', 431, 29, 3),
(3859, '411111', 'บ้านม่วง   ', 431, 29, 3),
(3860, '411112', 'บ้านตาด   ', 431, 29, 3),
(3861, '411113', 'นาคำ   ', 431, 29, 3),
(3862, '411201', '*หนองบัว   ', 432, 29, 3),
(3863, '411202', '*หนองภัยศูนย์   ', 432, 29, 3),
(3864, '411203', '*โพธิ์ชัย   ', 432, 29, 3),
(3865, '411204', '*หนองสวรรค์   ', 432, 29, 3),
(3866, '411205', '*หัวนา   ', 432, 29, 3),
(3867, '411206', '*บ้านขาม   ', 432, 29, 3),
(3868, '411207', '*นามะเฟือง   ', 432, 29, 3),
(3869, '411208', '*บ้านพร้าว   ', 432, 29, 3),
(3870, '411209', '*โนนขมิ้น   ', 432, 29, 3),
(3871, '411210', '*ลำภู   ', 432, 29, 3),
(3872, '411211', '*กุดจิก   ', 432, 29, 3),
(3873, '411212', '*โนนทัน   ', 432, 29, 3),
(3874, '411213', '*นาคำไฮ   ', 432, 29, 3),
(3875, '411214', '*ป่าไม้งาม   ', 432, 29, 3),
(3876, '411215', '*หนองหว้า   ', 432, 29, 3),
(3877, '411301', '*เมืองใหม่   ', 433, 29, 3),
(3878, '411302', '*ศรีบุญเรือง   ', 433, 29, 3),
(3879, '411303', '*หนองบัวใต้   ', 433, 29, 3),
(3880, '411304', '*กุดสะเทียน   ', 433, 29, 3),
(3881, '411305', '*นากอก   ', 433, 29, 3),
(3882, '411306', '*โนนสะอาด   ', 433, 29, 3),
(3883, '411307', '*ยางหล่อ   ', 433, 29, 3),
(3884, '411308', '*โนนม่วง   ', 433, 29, 3),
(3885, '411309', '*หนองกุงแก้ว   ', 433, 29, 3),
(3886, '411310', '*หนองแก   ', 433, 29, 3),
(3887, '411311', '*ทรายทอง   ', 433, 29, 3),
(3888, '411312', '*หันนางาม   ', 433, 29, 3),
(3889, '411401', '*นากลาง   ', 434, 29, 3),
(3890, '411402', '*ด่านช้าง   ', 434, 29, 3),
(3891, '411403', '*นาเหล่า   ', 434, 29, 3),
(3892, '411404', '*นาแก   ', 434, 29, 3),
(3893, '411405', '*กุดดินจี่   ', 434, 29, 3),
(3894, '411406', '*ฝั่งแดง   ', 434, 29, 3),
(3895, '411407', '*เก่ากลอย   ', 434, 29, 3),
(3896, '411408', '*วังทอง   ', 434, 29, 3),
(3897, '411409', '*โนนเมือง   ', 434, 29, 3),
(3898, '411410', '*อุทัยสวรรค์   ', 434, 29, 3),
(3899, '411411', '*ดงสวรรค์   ', 434, 29, 3),
(3900, '411412', '*วังปลาป้อม   ', 434, 29, 3),
(3901, '411413', '*กุดแห่   ', 434, 29, 3),
(3902, '411414', '*เทพคีรี   ', 434, 29, 3),
(3903, '411415', '*โนนภูทอง   ', 434, 29, 3),
(3904, '411493', '*บุญทัน   ', 434, 29, 3),
(3905, '411494', '*สุวรรณคูหา   ', 434, 29, 3),
(3906, '411495', '*ดงมะไฟ   ', 434, 29, 3),
(3907, '411496', '*นาด่าน   ', 434, 29, 3),
(3908, '411497', '*นาดี   ', 434, 29, 3),
(3909, '411498', '*บ้านโคก   ', 434, 29, 3),
(3910, '411499', '*นาสี   ', 434, 29, 3),
(3911, '411501', '*นาสี   ', 435, 29, 3),
(3912, '411502', '*บ้านโคก   ', 435, 29, 3),
(3913, '411503', '*นาดี   ', 435, 29, 3),
(3914, '411504', '*นาด่าน   ', 435, 29, 3),
(3915, '411505', '*ดงมะไฟ   ', 435, 29, 3),
(3916, '411506', '*สุวรรณคูหา   ', 435, 29, 3),
(3917, '411507', '*บุญทัน   ', 435, 29, 3),
(3918, '411601', '*โนนสัง   ', 436, 29, 3),
(3919, '411602', '*บ้านถิ่น   ', 436, 29, 3),
(3920, '411603', '*หนองเรือ   ', 436, 29, 3),
(3921, '411604', '*กุดดู่   ', 436, 29, 3),
(3922, '411605', '*บ้านค้อ   ', 436, 29, 3),
(3923, '411606', '*โนนเมือง   ', 436, 29, 3),
(3924, '411607', '*โคกใหญ่   ', 436, 29, 3),
(3925, '411608', '*โคกม่วง   ', 436, 29, 3),
(3926, '411609', '*นิคมพัฒนา   ', 436, 29, 3),
(3927, '411610', '*ปางกู่   ', 436, 29, 3),
(3928, '411701', 'บ้านผือ   ', 437, 29, 3),
(3929, '411702', 'หายโศก   ', 437, 29, 3),
(3930, '411703', 'เขือน้ำ   ', 437, 29, 3),
(3931, '411704', 'คำบง   ', 437, 29, 3),
(3932, '411705', 'โนนทอง   ', 437, 29, 3),
(3933, '411706', 'ข้าวสาร   ', 437, 29, 3),
(3934, '411707', 'จำปาโมง   ', 437, 29, 3),
(3935, '411708', 'กลางใหญ่   ', 437, 29, 3),
(3936, '411709', 'เมืองพาน   ', 437, 29, 3),
(3937, '411710', 'คำด้วง   ', 437, 29, 3),
(3938, '411711', 'หนองหัวคู   ', 437, 29, 3),
(3939, '411712', 'บ้านค้อ   ', 437, 29, 3),
(3940, '411713', 'หนองแวง   ', 437, 29, 3),
(3941, '411799', '*บ้านเม็ก   ', 437, 29, 3),
(3942, '411801', 'นางัว   ', 438, 29, 3),
(3943, '411802', 'น้ำโสม   ', 438, 29, 3),
(3944, '411803', '*นายูง   ', 438, 29, 3),
(3945, '411804', '*นาแค   ', 438, 29, 3),
(3946, '411805', 'หนองแวง   ', 438, 29, 3),
(3947, '411806', 'บ้านหยวก   ', 438, 29, 3),
(3948, '411807', 'โสมเยี่ยม   ', 438, 29, 3),
(3949, '411808', '*โนนทอง   ', 438, 29, 3),
(3950, '411809', '*บ้านก้อง   ', 438, 29, 3),
(3951, '411810', 'ศรีสำราญ   ', 438, 29, 3),
(3952, '411811', '*ทุบกุง   ', 438, 29, 3),
(3953, '411812', 'สามัคคี   ', 438, 29, 3),
(3954, '411898', '*นาแค   ', 438, 29, 3),
(3955, '411899', '*นายูง   ', 438, 29, 3),
(3956, '411901', 'เพ็ญ   ', 439, 29, 3),
(3957, '411902', 'บ้านธาตุ   ', 439, 29, 3),
(3958, '411903', 'นาพู่   ', 439, 29, 3),
(3959, '411904', 'เชียงหวาง   ', 439, 29, 3),
(3960, '411905', 'สุมเส้า   ', 439, 29, 3),
(3961, '411906', 'นาบัว   ', 439, 29, 3),
(3962, '411907', 'บ้านเหล่า   ', 439, 29, 3),
(3963, '411908', 'จอมศรี   ', 439, 29, 3),
(3964, '411909', 'เตาไห   ', 439, 29, 3),
(3965, '411910', 'โคกกลาง   ', 439, 29, 3),
(3966, '411911', 'สร้างแป้น   ', 439, 29, 3),
(3967, '411997', '*เชียงดา   ', 439, 29, 3),
(3968, '411998', '*บ้านยวด   ', 439, 29, 3),
(3969, '411999', '*สร้างคอม   ', 439, 29, 3),
(3970, '412001', 'สร้างคอม   ', 440, 29, 3),
(3971, '412002', 'เชียงดา   ', 440, 29, 3),
(3972, '412003', 'บ้านยวด   ', 440, 29, 3),
(3973, '412004', 'บ้านโคก   ', 440, 29, 3),
(3974, '412005', 'นาสะอาด   ', 440, 29, 3),
(3975, '412006', 'บ้านหินโงม   ', 440, 29, 3),
(3976, '412101', 'หนองแสง   ', 441, 29, 3),
(3977, '412102', 'แสงสว่าง   ', 441, 29, 3),
(3978, '412103', 'นาดี   ', 441, 29, 3),
(3979, '412104', 'ทับกุง   ', 441, 29, 3),
(3980, '412201', 'นายูง   ', 442, 29, 3),
(3981, '412202', 'บ้านก้อง   ', 442, 29, 3),
(3982, '412203', 'นาแค   ', 442, 29, 3),
(3983, '412204', 'โนนทอง   ', 442, 29, 3),
(3984, '412301', 'บ้านแดง   ', 443, 29, 3),
(3985, '412302', 'นาทราย   ', 443, 29, 3),
(3986, '412303', 'ดอนกลอย   ', 443, 29, 3),
(3987, '412401', 'บ้านจีต   ', 444, 29, 3),
(3988, '412402', 'โนนทองอินทร์   ', 444, 29, 3),
(3989, '412403', 'ค้อใหญ่   ', 444, 29, 3),
(3990, '412404', 'คอนสาย   ', 444, 29, 3),
(3991, '412501', 'นาม่วง   ', 445, 29, 3),
(3992, '412502', 'ห้วยสามพาด   ', 445, 29, 3),
(3993, '412503', 'อุ่มจาน   ', 445, 29, 3),
(3994, '420101', 'กุดป่อง   ', 446, 30, 3),
(3995, '420102', 'เมือง   ', 446, 30, 3),
(3996, '420103', 'นาอ้อ   ', 446, 30, 3),
(3997, '420104', 'กกดู่   ', 446, 30, 3),
(3998, '420105', 'น้ำหมาน   ', 446, 30, 3),
(3999, '420106', 'เสี้ยว   ', 446, 30, 3),
(4000, '420107', 'นาอาน   ', 446, 30, 3),
(4001, '420108', 'นาโป่ง   ', 446, 30, 3),
(4002, '420109', 'นาดินดำ   ', 446, 30, 3),
(4003, '420110', 'น้ำสวย   ', 446, 30, 3),
(4004, '420111', 'ชัยพฤกษ์   ', 446, 30, 3),
(4005, '420112', 'นาแขม   ', 446, 30, 3),
(4006, '420113', 'ศรีสองรัก   ', 446, 30, 3),
(4007, '420114', 'กกทอง   ', 446, 30, 3),
(4008, '420201', 'นาด้วง   ', 447, 30, 3),
(4009, '420202', 'นาดอกคำ   ', 447, 30, 3),
(4010, '420203', 'ท่าสะอาด   ', 447, 30, 3),
(4011, '420204', 'ท่าสวรรค์   ', 447, 30, 3),
(4012, '420301', 'เชียงคาน   ', 448, 30, 3),
(4013, '420302', 'ธาตุ   ', 448, 30, 3),
(4014, '420303', 'นาซ่าว   ', 448, 30, 3),
(4015, '420304', 'เขาแก้ว   ', 448, 30, 3),
(4016, '420305', 'ปากตม   ', 448, 30, 3),
(4017, '420306', 'บุฮม   ', 448, 30, 3),
(4018, '420307', 'จอมศรี   ', 448, 30, 3),
(4019, '420308', 'หาดทรายขาว   ', 448, 30, 3),
(4020, '420401', 'ปากชม   ', 449, 30, 3),
(4021, '420402', 'เชียงกลม   ', 449, 30, 3),
(4022, '420403', 'หาดคัมภีร์   ', 449, 30, 3),
(4023, '420404', 'ห้วยบ่อซืน   ', 449, 30, 3),
(4024, '420405', 'ห้วยพิชัย   ', 449, 30, 3),
(4025, '420406', 'ชมเจริญ   ', 449, 30, 3),
(4026, '420501', 'ด่านซ้าย   ', 450, 30, 3),
(4027, '420502', 'ปากหมัน   ', 450, 30, 3),
(4028, '420503', 'นาดี   ', 450, 30, 3),
(4029, '420504', 'โคกงาม   ', 450, 30, 3),
(4030, '420505', 'โพนสูง   ', 450, 30, 3),
(4031, '420506', 'อิปุ่ม   ', 450, 30, 3),
(4032, '420507', 'กกสะทอน   ', 450, 30, 3),
(4033, '420508', 'โป่ง   ', 450, 30, 3),
(4034, '420509', 'วังยาว   ', 450, 30, 3),
(4035, '420510', 'นาหอ   ', 450, 30, 3),
(4036, '420593', '*ร่องจิก   ', 450, 30, 3),
(4037, '420594', '*แสงภา   ', 450, 30, 3),
(4038, '420595', '*ปลาบ่า   ', 450, 30, 3),
(4039, '420596', '*นาพึ่ง   ', 450, 30, 3),
(4040, '420597', '*ท่าศาลา   ', 450, 30, 3),
(4041, '420598', '*หนองบัว   ', 450, 30, 3),
(4042, '420599', '*นาแห้ว   ', 450, 30, 3),
(4043, '420601', 'นาแห้ว   ', 451, 30, 3),
(4044, '420602', 'แสงภา   ', 451, 30, 3),
(4045, '420603', 'นาพึง   ', 451, 30, 3),
(4046, '420604', 'นามาลา   ', 451, 30, 3),
(4047, '420605', 'เหล่ากอหก   ', 451, 30, 3),
(4048, '420701', 'หนองบัว   ', 452, 30, 3),
(4049, '420702', 'ท่าศาลา   ', 452, 30, 3),
(4050, '420703', 'ร่องจิก   ', 452, 30, 3),
(4051, '420704', 'ปลาบ่า   ', 452, 30, 3),
(4052, '420705', 'ลาดค่าง   ', 452, 30, 3),
(4053, '420706', 'สานตม   ', 452, 30, 3),
(4054, '420801', 'ท่าลี่   ', 453, 30, 3),
(4055, '420802', 'หนองผือ   ', 453, 30, 3),
(4056, '420803', 'อาฮี   ', 453, 30, 3),
(4057, '420804', 'น้ำแคม   ', 453, 30, 3),
(4058, '420805', 'โคกใหญ่   ', 453, 30, 3),
(4059, '420806', 'น้ำทูน   ', 453, 30, 3),
(4060, '420901', 'วังสะพุง   ', 454, 30, 3),
(4061, '420902', 'ทรายขาว   ', 454, 30, 3),
(4062, '420903', 'หนองหญ้าปล้อง   ', 454, 30, 3),
(4063, '420904', 'หนองงิ้ว   ', 454, 30, 3),
(4064, '420905', 'ปากปวน   ', 454, 30, 3),
(4065, '420906', 'ผาน้อย   ', 454, 30, 3),
(4066, '420907', '*เอราวัณ   ', 454, 30, 3),
(4067, '420908', '*ผาอินทร์แปลง   ', 454, 30, 3),
(4068, '420909', '*ผาสามยอด   ', 454, 30, 3),
(4069, '420910', 'ผาบิ้ง   ', 454, 30, 3),
(4070, '420911', 'เขาหลวง   ', 454, 30, 3),
(4071, '420912', 'โคกขมิ้น   ', 454, 30, 3),
(4072, '420913', 'ศรีสงคราม   ', 454, 30, 3),
(4073, '420914', '*ทรัพย์ไพวัลย์   ', 454, 30, 3),
(4074, '420998', '*หนองคัน   ', 454, 30, 3),
(4075, '420999', '*ภูหอ   ', 454, 30, 3),
(4076, '421001', 'ศรีฐาน   ', 455, 30, 3),
(4077, '421002', 'ปวนพุ*   ', 455, 30, 3),
(4078, '421003', '*ท่าช้างคล้อง   ', 455, 30, 3),
(4079, '421004', '*ผาขาว   ', 455, 30, 3),
(4080, '421005', 'ผานกเค้า   ', 455, 30, 3),
(4081, '421006', '*โนนป่าซาง   ', 455, 30, 3),
(4082, '421007', 'ภูกระดึง   ', 455, 30, 3),
(4083, '421008', 'หนองหิน*   ', 455, 30, 3),
(4084, '421009', '*โนนปอแดง   ', 455, 30, 3),
(4085, '421010', 'ห้วยส้ม   ', 455, 30, 3),
(4086, '421011', 'ตาดข่า*   ', 455, 30, 3),
(4087, '421101', 'ภูหอ   ', 456, 30, 3),
(4088, '421102', 'หนองคัน   ', 456, 30, 3),
(4089, '421103', 'วังน้ำใส*   ', 456, 30, 3),
(4090, '421104', 'ห้วยสีเสียด   ', 456, 30, 3),
(4091, '421105', 'เลยวังไสย์   ', 456, 30, 3),
(4092, '421106', 'แก่งศรีภูมิ   ', 456, 30, 3),
(4093, '421201', 'ผาขาว   ', 457, 30, 3),
(4094, '421202', 'ท่าช้างคล้อง   ', 457, 30, 3),
(4095, '421203', 'โนนปอแดง   ', 457, 30, 3),
(4096, '421204', 'โนนป่าซาง   ', 457, 30, 3),
(4097, '421205', 'บ้านเพิ่ม   ', 457, 30, 3),
(4098, '421301', 'เอราวัณ   ', 458, 30, 3),
(4099, '421302', 'ผาอินทร์แปลง   ', 458, 30, 3),
(4100, '421303', 'ผาสามยอด   ', 458, 30, 3),
(4101, '421304', 'ทรัพย์ไพวัลย์   ', 458, 30, 3),
(4102, '421401', 'หนองหิน   ', 459, 30, 3),
(4103, '421402', 'ตาดข่า   ', 459, 30, 3),
(4104, '421403', 'ปวนพุ   ', 459, 30, 3),
(4105, '430101', 'ในเมือง   ', 460, 31, 3),
(4106, '430102', 'มีชัย   ', 460, 31, 3),
(4107, '430103', 'โพธิ์ชัย   ', 460, 31, 3),
(4108, '430104', 'กวนวัน   ', 460, 31, 3),
(4109, '430105', 'เวียงคุก   ', 460, 31, 3),
(4110, '430106', 'วัดธาตุ   ', 460, 31, 3),
(4111, '430107', 'หาดคำ   ', 460, 31, 3),
(4112, '430108', 'หินโงม   ', 460, 31, 3),
(4113, '430109', 'บ้านเดื่อ   ', 460, 31, 3),
(4114, '430110', 'ค่ายบกหวาน   ', 460, 31, 3),
(4115, '430111', 'สองห้อง   ', 460, 31, 3),
(4116, '430112', '*สระใคร   ', 460, 31, 3),
(4117, '430113', 'พระธาตุบังพวน   ', 460, 31, 3),
(4118, '430114', '*บ้านฝาง   ', 460, 31, 3),
(4119, '430115', '*คอกช้าง   ', 460, 31, 3),
(4120, '430116', 'หนองกอมเกาะ   ', 460, 31, 3),
(4121, '430117', 'ปะโค   ', 460, 31, 3),
(4122, '430118', 'เมืองหมี   ', 460, 31, 3),
(4123, '430119', 'สีกาย   ', 460, 31, 3),
(4124, '430201', 'ท่าบ่อ   ', 461, 31, 3),
(4125, '430202', 'น้ำโมง   ', 461, 31, 3),
(4126, '430203', 'กองนาง   ', 461, 31, 3),
(4127, '430204', 'โคกคอน   ', 461, 31, 3),
(4128, '430205', 'บ้านเดื่อ   ', 461, 31, 3),
(4129, '430206', 'บ้านถ่อน   ', 461, 31, 3),
(4130, '430207', 'บ้านว่าน   ', 461, 31, 3),
(4131, '430208', 'นาข่า   ', 461, 31, 3),
(4132, '430209', 'โพนสา   ', 461, 31, 3),
(4133, '430210', 'หนองนาง   ', 461, 31, 3),
(4134, '430301', 'บึงกาฬ   ', 462, 77, 3),
(4135, '430302', '*ชุมภูพร   ', 462, 77, 3),
(4136, '430303', 'โนนสมบูรณ์   ', 462, 77, 3),
(4137, '430304', 'หนองเข็ง   ', 462, 77, 3),
(4138, '430305', 'หอคำ   ', 462, 77, 3),
(4139, '430306', 'หนองเลิง   ', 462, 77, 3),
(4140, '430307', 'โคกก่อง   ', 462, 77, 3),
(4141, '430308', '*หนองเดิ่น   ', 462, 77, 3),
(4142, '430309', '*นาสะแบง   ', 462, 77, 3),
(4143, '430310', 'นาสวรรค์   ', 462, 77, 3),
(4144, '430311', 'ไคสี   ', 462, 77, 3),
(4145, '430312', 'โคกกว้าง*   ', 462, 77, 3),
(4146, '430313', '*ศรีวิไล   ', 462, 77, 3),
(4147, '430314', 'ชัยพร   ', 462, 77, 3),
(4148, '430315', '*นาแสง   ', 462, 77, 3),
(4149, '430316', 'วิศิษฐ์   ', 462, 77, 3),
(4150, '430317', '*บุ่งคล้า   ', 462, 77, 3),
(4151, '430318', 'คำนาดี   ', 462, 77, 3),
(4152, '430319', 'โป่งเปือย   ', 462, 77, 3),
(4153, '430401', 'ศรีชมภู   ', 463, 77, 3),
(4154, '430402', 'ดอนหญ้านาง   ', 463, 77, 3),
(4155, '430403', 'พรเจริญ   ', 463, 77, 3),
(4156, '430404', 'หนองหัวช้าง   ', 463, 77, 3),
(4157, '430405', 'วังชมภู   ', 463, 77, 3),
(4158, '430406', 'ป่าแฝก   ', 463, 77, 3),
(4159, '430407', 'ศรีสำราญ   ', 463, 77, 3),
(4160, '430501', 'จุมพล   ', 464, 31, 3),
(4161, '430502', 'วัดหลวง   ', 464, 31, 3),
(4162, '430503', 'กุดบง   ', 464, 31, 3),
(4163, '430504', 'ชุมช้าง   ', 464, 31, 3),
(4164, '430505', '*รัตนวาปี   ', 464, 31, 3),
(4165, '430506', 'ทุ่งหลวง   ', 464, 31, 3),
(4166, '430507', 'เหล่าต่างคำ   ', 464, 31, 3),
(4167, '430508', 'นาหนัง   ', 464, 31, 3),
(4168, '430509', 'เซิม   ', 464, 31, 3),
(4169, '430510', '*หนองหลวง   ', 464, 31, 3),
(4170, '430511', '*โพนแพง   ', 464, 31, 3),
(4171, '430512', '*เฝ้าไร่   ', 464, 31, 3),
(4172, '430513', 'บ้านโพธิ์   ', 464, 31, 3),
(4173, '430514', '*นาทับไฮ   ', 464, 31, 3),
(4174, '430515', '*วังหลวง   ', 464, 31, 3),
(4175, '430516', '*พระบาทนาสิงห์   ', 464, 31, 3),
(4176, '430517', '*อุดมพร   ', 464, 31, 3),
(4177, '430518', '*นาดี   ', 464, 31, 3),
(4178, '430520', '*บ้านต้อน   ', 464, 31, 3),
(4179, '430521', 'บ้านผือ   ', 464, 31, 3),
(4180, '430522', 'สร้างนางขาว   ', 464, 31, 3),
(4181, '430601', 'โซ่   ', 465, 77, 3),
(4182, '430602', 'หนองพันทา   ', 465, 77, 3),
(4183, '430603', 'ศรีชมภู   ', 465, 77, 3),
(4184, '430604', 'คำแก้ว   ', 465, 77, 3),
(4185, '430605', 'บัวตูม   ', 465, 77, 3),
(4186, '430606', 'ถ้ำเจริญ   ', 465, 77, 3),
(4187, '430607', 'เหล่าทอง   ', 465, 77, 3),
(4188, '430701', 'พานพร้าว   ', 466, 31, 3),
(4189, '430702', 'โพธิ์ตาก*   ', 466, 31, 3),
(4190, '430703', 'บ้านหม้อ   ', 466, 31, 3),
(4191, '430704', 'พระพุทธบาท   ', 466, 31, 3),
(4192, '430705', 'หนองปลาปาก   ', 466, 31, 3),
(4193, '430706', 'โพนทอง*   ', 466, 31, 3),
(4194, '430707', 'ด่านศรีสุข*   ', 466, 31, 3),
(4195, '430801', 'แก้งไก่   ', 467, 31, 3),
(4196, '430802', 'ผาตั้ง   ', 467, 31, 3),
(4197, '430803', 'บ้านม่วง   ', 467, 31, 3),
(4198, '430804', 'นางิ้ว   ', 467, 31, 3),
(4199, '430805', 'สังคม   ', 467, 31, 3),
(4200, '430901', 'เซกา   ', 468, 77, 3),
(4201, '430902', 'ซาง   ', 468, 77, 3),
(4202, '430903', 'ท่ากกแดง   ', 468, 77, 3),
(4203, '430904', '*โพธิ์หมากแข้ง   ', 468, 77, 3),
(4204, '430905', '*ดงบัง   ', 468, 77, 3),
(4205, '430906', 'บ้านต้อง   ', 468, 77, 3),
(4206, '430907', 'ป่งไฮ   ', 468, 77, 3),
(4207, '430908', 'น้ำจั้น   ', 468, 77, 3),
(4208, '430909', 'ท่าสะอาด   ', 468, 77, 3),
(4209, '430910', '*บึงโขงหลง   ', 468, 77, 3),
(4210, '430911', '*ท่าดอกคำ   ', 468, 77, 3),
(4211, '430912', 'หนองทุ่ม   ', 468, 77, 3),
(4212, '430913', 'โสกก่าม   ', 468, 77, 3),
(4213, '431001', 'ปากคาด   ', 469, 77, 3),
(4214, '431002', 'หนองยอง   ', 469, 77, 3),
(4215, '431003', 'นากั้ง   ', 469, 77, 3),
(4216, '431004', 'โนนศิลา   ', 469, 77, 3),
(4217, '431005', 'สมสนุก   ', 469, 77, 3),
(4218, '431006', 'นาดง   ', 469, 77, 3),
(4219, '431101', 'บึงโขงหลง   ', 470, 77, 3),
(4220, '431102', 'โพธิ์หมากแข้ง   ', 470, 77, 3),
(4221, '431103', 'ดงบัง   ', 470, 77, 3),
(4222, '431104', 'ท่าดอกคำ   ', 470, 77, 3),
(4223, '431201', 'ศรีวิไล   ', 471, 77, 3),
(4224, '431202', 'ชุมภูพร   ', 471, 77, 3),
(4225, '431203', 'นาแสง   ', 471, 77, 3),
(4226, '431204', 'นาสะแบง   ', 471, 77, 3),
(4227, '431205', 'นาสิงห์   ', 471, 77, 3),
(4228, '431301', 'บุ่งคล้า   ', 472, 77, 3),
(4229, '431302', 'หนองเดิ่น   ', 472, 77, 3),
(4230, '431303', 'โคกกว้าง   ', 472, 77, 3),
(4231, '431401', 'สระใคร   ', 473, 31, 3),
(4232, '431402', 'คอกช้าง   ', 473, 31, 3),
(4233, '431403', 'บ้านฝาง   ', 473, 31, 3),
(4234, '431501', 'เฝ้าไร่   ', 474, 31, 3),
(4235, '431502', 'นาดี   ', 474, 31, 3),
(4236, '431503', 'หนองหลวง   ', 474, 31, 3),
(4237, '431504', 'วังหลวง   ', 474, 31, 3),
(4238, '431505', 'อุดมพร   ', 474, 31, 3),
(4239, '431601', 'รัตนวาปี   ', 475, 31, 3),
(4240, '431602', 'นาทับไฮ   ', 475, 31, 3),
(4241, '431603', 'บ้านต้อน   ', 475, 31, 3),
(4242, '431604', 'พระบาทนาสิงห์   ', 475, 31, 3),
(4243, '431605', 'โพนแพง   ', 475, 31, 3),
(4244, '431701', 'โพธิ์ตาก   ', 476, 31, 3),
(4245, '431702', 'โพนทอง   ', 476, 31, 3),
(4246, '431703', 'ด่านศรีสุข   ', 476, 31, 3),
(4247, '440101', 'ตลาด   ', 477, 32, 3),
(4248, '440102', 'เขวา   ', 477, 32, 3),
(4249, '440103', 'ท่าตูม   ', 477, 32, 3),
(4250, '440104', 'แวงน่าง   ', 477, 32, 3),
(4251, '440105', 'โคกก่อ   ', 477, 32, 3),
(4252, '440106', 'ดอนหว่าน   ', 477, 32, 3),
(4253, '440107', 'เกิ้ง   ', 477, 32, 3),
(4254, '440108', 'แก่งเลิงจาน   ', 477, 32, 3),
(4255, '440109', 'ท่าสองคอน   ', 477, 32, 3),
(4256, '440110', 'ลาดพัฒนา   ', 477, 32, 3),
(4257, '440111', 'หนองปลิง   ', 477, 32, 3),
(4258, '440112', 'ห้วยแอ่ง   ', 477, 32, 3),
(4259, '440113', 'หนองโน   ', 477, 32, 3),
(4260, '440114', 'บัวค้อ   ', 477, 32, 3),
(4261, '440201', 'แกดำ   ', 478, 32, 3),
(4262, '440202', 'วังแสง   ', 478, 32, 3),
(4263, '440203', 'มิตรภาพ   ', 478, 32, 3),
(4264, '440204', 'หนองกุง   ', 478, 32, 3),
(4265, '440205', 'โนนภิบาล   ', 478, 32, 3),
(4266, '440301', 'หัวขวาง   ', 479, 32, 3),
(4267, '440302', 'ยางน้อย   ', 479, 32, 3),
(4268, '440303', 'วังยาว   ', 479, 32, 3),
(4269, '440304', 'เขวาไร่   ', 479, 32, 3),
(4270, '440305', 'แพง   ', 479, 32, 3),
(4271, '440306', 'แก้งแก   ', 479, 32, 3),
(4272, '440307', 'หนองเหล็ก   ', 479, 32, 3),
(4273, '440308', 'หนองบัว   ', 479, 32, 3),
(4274, '440309', 'เหล่า   ', 479, 32, 3),
(4275, '440310', 'เขื่อน   ', 479, 32, 3),
(4276, '440311', 'หนองบอน   ', 479, 32, 3),
(4277, '440312', 'โพนงาม   ', 479, 32, 3),
(4278, '440313', 'ยางท่าแจ้ง   ', 479, 32, 3),
(4279, '440314', 'แห่ใต้   ', 479, 32, 3),
(4280, '440315', 'หนองกุงสวรรค์   ', 479, 32, 3),
(4281, '440316', 'เลิงใต้   ', 479, 32, 3),
(4282, '440317', 'ดอนกลาง   ', 479, 32, 3),
(4283, '440401', 'โคกพระ   ', 480, 32, 3),
(4284, '440402', 'คันธารราษฎร์   ', 480, 32, 3),
(4285, '440403', 'มะค่า   ', 480, 32, 3),
(4286, '440404', 'ท่าขอนยาง   ', 480, 32, 3),
(4287, '440405', 'นาสีนวน   ', 480, 32, 3),
(4288, '440406', 'ขามเรียง   ', 480, 32, 3),
(4289, '440407', 'เขวาใหญ่   ', 480, 32, 3),
(4290, '440408', 'ศรีสุข   ', 480, 32, 3),
(4291, '440409', 'กุดใส้จ่อ   ', 480, 32, 3),
(4292, '440410', 'ขามเฒ่าพัฒนา   ', 480, 32, 3),
(4293, '440501', 'เชียงยืน   ', 481, 32, 3),
(4294, '440502', 'ชื่นชม*   ', 481, 32, 3),
(4295, '440503', 'หนองซอน   ', 481, 32, 3),
(4296, '440504', 'เหล่าดอกไม้*   ', 481, 32, 3),
(4297, '440505', 'ดอนเงิน   ', 481, 32, 3),
(4298, '440506', 'กู่ทอง   ', 481, 32, 3),
(4299, '440507', 'นาทอง   ', 481, 32, 3),
(4300, '440508', 'เสือเฒ่า   ', 481, 32, 3),
(4301, '440509', 'กุดปลาดุก*   ', 481, 32, 3),
(4302, '440510', 'หนองกุง*   ', 481, 32, 3),
(4303, '440511', 'โพนทอง   ', 481, 32, 3),
(4304, '440512', 'เหล่าบัวบาน   ', 481, 32, 3),
(4305, '440601', 'บรบือ   ', 482, 32, 3),
(4306, '440602', 'บ่อใหญ่   ', 482, 32, 3),
(4307, '440603', '*กุดรัง   ', 482, 32, 3),
(4308, '440604', 'วังไชย   ', 482, 32, 3),
(4309, '440605', 'หนองม่วง   ', 482, 32, 3),
(4310, '440606', 'กำพี้   ', 482, 32, 3),
(4311, '440607', 'โนนราษี   ', 482, 32, 3),
(4312, '440608', 'โนนแดง   ', 482, 32, 3),
(4313, '440609', '*เลิงแฝก   ', 482, 32, 3),
(4314, '440610', 'หนองจิก   ', 482, 32, 3),
(4315, '440611', 'บัวมาศ   ', 482, 32, 3),
(4316, '440612', '*นาโพธิ์   ', 482, 32, 3),
(4317, '440613', 'หนองคูขาด   ', 482, 32, 3),
(4318, '440614', '*หนองแวง   ', 482, 32, 3),
(4319, '440615', 'วังใหม่   ', 482, 32, 3),
(4320, '440616', 'ยาง   ', 482, 32, 3),
(4321, '440617', '*ห้วยเตย   ', 482, 32, 3),
(4322, '440618', 'หนองสิม   ', 482, 32, 3),
(4323, '440619', 'หนองโก   ', 482, 32, 3),
(4324, '440620', 'ดอนงัว   ', 482, 32, 3),
(4325, '440701', 'นาเชือก   ', 483, 32, 3),
(4326, '440702', 'สำโรง   ', 483, 32, 3),
(4327, '440703', 'หนองแดง   ', 483, 32, 3),
(4328, '440704', 'เขวาไร่   ', 483, 32, 3),
(4329, '440705', 'หนองโพธิ์   ', 483, 32, 3),
(4330, '440706', 'ปอพาน   ', 483, 32, 3),
(4331, '440707', 'หนองเม็ก   ', 483, 32, 3),
(4332, '440708', 'หนองเรือ   ', 483, 32, 3),
(4333, '440709', 'หนองกุง   ', 483, 32, 3),
(4334, '440710', 'สันป่าตอง   ', 483, 32, 3),
(4335, '440801', 'ปะหลาน   ', 484, 32, 3),
(4336, '440802', 'ก้ามปู   ', 484, 32, 3),
(4337, '440803', 'เวียงสะอาด   ', 484, 32, 3),
(4338, '440804', 'เม็กดำ   ', 484, 32, 3),
(4339, '440805', 'นาสีนวล   ', 484, 32, 3),
(4340, '440806', '*ดงเมือง   ', 484, 32, 3),
(4341, '440807', '*แวงดง   ', 484, 32, 3),
(4342, '440808', '*ขามเรียน   ', 484, 32, 3),
(4343, '440809', 'ราษฎร์เจริญ   ', 484, 32, 3),
(4344, '440810', 'หนองบัวแก้ว   ', 484, 32, 3),
(4345, '440811', '*นาภู   ', 484, 32, 3),
(4346, '440812', 'เมืองเตา   ', 484, 32, 3),
(4347, '440813', '*บ้านกู่   ', 484, 32, 3),
(4348, '440814', '*ยางสีสุราช   ', 484, 32, 3),
(4349, '440815', 'ลานสะแก   ', 484, 32, 3),
(4350, '440816', 'เวียงชัย   ', 484, 32, 3),
(4351, '440817', 'หนองบัว   ', 484, 32, 3),
(4352, '440818', 'ราษฎร์พัฒนา   ', 484, 32, 3),
(4353, '440819', 'เมืองเสือ   ', 484, 32, 3),
(4354, '440820', 'ภารแอ่น   ', 484, 32, 3),
(4355, '440901', 'หนองแสง   ', 485, 32, 3),
(4356, '440902', 'ขามป้อม   ', 485, 32, 3),
(4357, '440903', 'เสือโก้ก   ', 485, 32, 3),
(4358, '440904', 'ดงใหญ่   ', 485, 32, 3),
(4359, '440905', 'โพธิ์ชัย   ', 485, 32, 3),
(4360, '440906', 'หัวเรือ   ', 485, 32, 3),
(4361, '440907', 'แคน   ', 485, 32, 3),
(4362, '440908', 'งัวบา   ', 485, 32, 3),
(4363, '440909', 'นาข่า   ', 485, 32, 3),
(4364, '440910', 'บ้านหวาย   ', 485, 32, 3),
(4365, '440911', 'หนองไฮ   ', 485, 32, 3),
(4366, '440912', 'ประชาพัฒนา   ', 485, 32, 3),
(4367, '440913', 'หนองทุ่ม   ', 485, 32, 3),
(4368, '440914', 'หนองแสน   ', 485, 32, 3),
(4369, '440915', 'โคกสีทองหลาง   ', 485, 32, 3),
(4370, '440997', '*หนองไผ่   ', 485, 32, 3),
(4371, '440998', '*นาดูน   ', 485, 32, 3),
(4372, '440999', '*หนองคู   ', 485, 32, 3),
(4373, '441001', 'นาดูน   ', 486, 32, 3),
(4374, '441002', 'หนองไผ่   ', 486, 32, 3),
(4375, '441003', 'หนองคู   ', 486, 32, 3),
(4376, '441004', 'ดงบัง   ', 486, 32, 3),
(4377, '441005', 'ดงดวน   ', 486, 32, 3),
(4378, '441006', 'หัวดง   ', 486, 32, 3),
(4379, '441007', 'ดงยาง   ', 486, 32, 3),
(4380, '441008', 'กู่สันตรัตน์   ', 486, 32, 3),
(4381, '441009', 'พระธาตุ   ', 486, 32, 3),
(4382, '441101', 'ยางสีสุราช   ', 487, 32, 3),
(4383, '441102', 'นาภู   ', 487, 32, 3),
(4384, '441103', 'แวงดง   ', 487, 32, 3),
(4385, '441104', 'บ้านกู่   ', 487, 32, 3),
(4386, '441105', 'ดงเมือง   ', 487, 32, 3),
(4387, '441106', 'ขามเรียน   ', 487, 32, 3),
(4388, '441107', 'หนองบัวสันตุ   ', 487, 32, 3),
(4389, '441201', 'กุดรัง   ', 488, 32, 3),
(4390, '441202', 'นาโพธิ์   ', 488, 32, 3),
(4391, '441203', 'เลิงแฝก   ', 488, 32, 3),
(4392, '441204', 'หนองแวง   ', 488, 32, 3),
(4393, '441205', 'ห้วยเตย   ', 488, 32, 3),
(4394, '441301', 'ชื่นชม   ', 489, 32, 3),
(4395, '441302', 'กุดปลาดุก   ', 489, 32, 3),
(4396, '441303', 'เหล่าดอกไม้   ', 489, 32, 3),
(4397, '441304', 'หนองกุง   ', 489, 32, 3),
(4398, '450101', 'ในเมือง   ', 491, 33, 3),
(4399, '450102', 'รอบเมือง   ', 491, 33, 3),
(4400, '450103', 'เหนือเมือง   ', 491, 33, 3),
(4401, '450104', 'ขอนแก่น   ', 491, 33, 3),
(4402, '450105', 'นาโพธิ์   ', 491, 33, 3),
(4403, '450106', 'สะอาดสมบูรณ์   ', 491, 33, 3),
(4404, '450107', '*ปาฝา   ', 491, 33, 3),
(4405, '450108', 'สีแก้ว   ', 491, 33, 3),
(4406, '450109', 'ปอภาร  (ปอพาน)   ', 491, 33, 3),
(4407, '450110', 'โนนรัง   ', 491, 33, 3),
(4408, '450111', '*ดงสิงห์   ', 491, 33, 3),
(4409, '450112', '*สวนจิก   ', 491, 33, 3),
(4410, '450113', '*ม่วงลาด   ', 491, 33, 3),
(4411, '450114', '*โพธิ์ทอง   ', 491, 33, 3),
(4412, '450115', '*จังหาร   ', 491, 33, 3),
(4413, '450116', '*ดินดำ   ', 491, 33, 3),
(4414, '450117', 'หนองแก้ว   ', 491, 33, 3),
(4415, '450118', 'หนองแวง   ', 491, 33, 3),
(4416, '450119', '*ศรีสมเด็จ   ', 491, 33, 3),
(4417, '450120', 'ดงลาน   ', 491, 33, 3),
(4418, '450121', '*หนองใหญ่   ', 491, 33, 3),
(4419, '450122', '*เมืองเปลือย   ', 491, 33, 3),
(4420, '450123', 'แคนใหญ่   ', 491, 33, 3),
(4421, '450124', 'โนนตาล   ', 491, 33, 3),
(4422, '450125', 'เมืองทอง   ', 491, 33, 3),
(4423, '450191', '*ดงสิงห์   ', 491, 33, 3),
(4424, '450192', '*จังหาร   ', 491, 33, 3),
(4425, '450193', '*ม่วงลาด   ', 491, 33, 3),
(4426, '450194', '*ปาฝา   ', 491, 33, 3),
(4427, '450195', '*ดินดำ   ', 491, 33, 3),
(4428, '450196', '*สวนจิก   ', 491, 33, 3),
(4429, '450197', '*เมืองเปลือย   ', 491, 33, 3),
(4430, '450198', '*ศรีสมเด็จ   ', 491, 33, 3),
(4431, '450199', '*โพธิ์ทอง   ', 491, 33, 3),
(4432, '450201', 'เกษตรวิสัย   ', 492, 33, 3),
(4433, '450202', 'เมืองบัว   ', 492, 33, 3),
(4434, '450203', 'เหล่าหลวง   ', 492, 33, 3),
(4435, '450204', 'สิงห์โคก   ', 492, 33, 3),
(4436, '450205', 'ดงครั่งใหญ่   ', 492, 33, 3),
(4437, '450206', 'บ้านฝาง   ', 492, 33, 3),
(4438, '450207', 'หนองแวง   ', 492, 33, 3),
(4439, '450208', 'กำแพง   ', 492, 33, 3),
(4440, '450209', 'กู่กาสิงห์   ', 492, 33, 3),
(4441, '450210', 'น้ำอ้อม   ', 492, 33, 3),
(4442, '450211', 'โนนสว่าง   ', 492, 33, 3),
(4443, '450212', 'ทุ่งทอง   ', 492, 33, 3),
(4444, '450213', 'ดงครั่งน้อย   ', 492, 33, 3),
(4445, '450301', 'บัวแดง   ', 493, 33, 3),
(4446, '450302', 'ดอกล้ำ   ', 493, 33, 3),
(4447, '450303', 'หนองแคน   ', 493, 33, 3),
(4448, '450304', 'โพนสูง   ', 493, 33, 3),
(4449, '450305', 'โนนสวรรค์   ', 493, 33, 3),
(4450, '450306', 'สระบัว   ', 493, 33, 3),
(4451, '450307', 'โนนสง่า   ', 493, 33, 3),
(4452, '450308', 'ขี้เหล็ก   ', 493, 33, 3),
(4453, '450401', 'หัวช้าง   ', 494, 33, 3),
(4454, '450402', 'หนองผือ   ', 494, 33, 3),
(4455, '450403', 'เมืองหงส์   ', 494, 33, 3),
(4456, '450404', 'โคกล่าม   ', 494, 33, 3),
(4457, '450405', 'น้ำใส   ', 494, 33, 3),
(4458, '450406', 'ดงแดง   ', 494, 33, 3),
(4459, '450407', 'ดงกลาง   ', 494, 33, 3),
(4460, '450408', 'ป่าสังข์   ', 494, 33, 3),
(4461, '450409', 'อีง่อง   ', 494, 33, 3),
(4462, '450410', 'ลิ้นฟ้า   ', 494, 33, 3),
(4463, '450411', 'ดู่น้อย   ', 494, 33, 3),
(4464, '450412', 'ศรีโคตร   ', 494, 33, 3),
(4465, '450501', 'นิเวศน์   ', 495, 33, 3),
(4466, '450502', 'ธงธานี   ', 495, 33, 3),
(4467, '450503', 'หนองไผ่   ', 495, 33, 3),
(4468, '450504', 'ธวัชบุรี   ', 495, 33, 3),
(4469, '450505', '*หมูม้น   ', 495, 33, 3),
(4470, '450506', 'อุ่มเม้า   ', 495, 33, 3),
(4471, '450507', 'มะอึ   ', 495, 33, 3),
(4472, '450508', 'เหล่า*   ', 495, 33, 3),
(4473, '450509', 'มะบ้า*   ', 495, 33, 3),
(4474, '450510', 'เขวาทุ่ง   ', 495, 33, 3),
(4475, '450511', '*พระธาตุ   ', 495, 33, 3),
(4476, '450512', 'บึงงาม*   ', 495, 33, 3),
(4477, '450513', '*บ้านเขือง   ', 495, 33, 3),
(4478, '450514', '*พระเจ้า   ', 495, 33, 3),
(4479, '450515', 'ไพศาล   ', 495, 33, 3),
(4480, '450516', 'เทอดไทย*   ', 495, 33, 3),
(4481, '450517', 'เมืองน้อย   ', 495, 33, 3),
(4482, '450518', 'โนนศิลา*   ', 495, 33, 3),
(4483, '450519', '*เชียงขวัญ   ', 495, 33, 3),
(4484, '450520', 'บึงนคร   ', 495, 33, 3),
(4485, '450521', '*พลับพลา   ', 495, 33, 3),
(4486, '450522', 'ราชธานี   ', 495, 33, 3),
(4487, '450523', 'ทุ่งเขาหลวง*   ', 495, 33, 3),
(4488, '450524', 'หนองพอก   ', 495, 33, 3),
(4489, '450601', 'พนมไพร   ', 496, 33, 3),
(4490, '450602', 'แสนสุข   ', 496, 33, 3),
(4491, '450603', 'กุดน้ำใส   ', 496, 33, 3),
(4492, '450604', 'หนองทัพไทย   ', 496, 33, 3),
(4493, '450605', 'โพธิ์ใหญ่   ', 496, 33, 3),
(4494, '450606', 'วารีสวัสดิ์   ', 496, 33, 3),
(4495, '450607', 'โคกสว่าง   ', 496, 33, 3),
(4496, '450608', '*หนองฮี   ', 496, 33, 3),
(4497, '450609', '*เด่นราษฎร์   ', 496, 33, 3),
(4498, '450610', '*ดูกอึ่ง   ', 496, 33, 3),
(4499, '450611', 'โพธิ์ชัย   ', 496, 33, 3),
(4500, '450612', 'นานวล   ', 496, 33, 3),
(4501, '450613', 'คำไฮ   ', 496, 33, 3),
(4502, '450614', 'สระแก้ว   ', 496, 33, 3),
(4503, '450615', 'ค้อใหญ่   ', 496, 33, 3),
(4504, '450616', '*สาวแห   ', 496, 33, 3),
(4505, '450617', 'ชานุวรรณ   ', 496, 33, 3),
(4506, '450701', 'แวง   ', 497, 33, 3),
(4507, '450702', 'โคกกกม่วง   ', 497, 33, 3),
(4508, '450703', 'นาอุดม   ', 497, 33, 3),
(4509, '450704', 'สว่าง   ', 497, 33, 3),
(4510, '450705', 'หนองใหญ่   ', 497, 33, 3),
(4511, '450706', 'โพธิ์ทอง   ', 497, 33, 3),
(4512, '450707', 'โนนชัยศรี   ', 497, 33, 3),
(4513, '450708', 'โพธิ์ศรีสว่าง   ', 497, 33, 3),
(4514, '450709', 'อุ่มเม่า   ', 497, 33, 3),
(4515, '450710', 'คำนาดี   ', 497, 33, 3),
(4516, '450711', 'พรมสวรรค์   ', 497, 33, 3),
(4517, '450712', 'สระนกแก้ว   ', 497, 33, 3),
(4518, '450713', 'วังสามัคคี   ', 497, 33, 3),
(4519, '450714', 'โคกสูง   ', 497, 33, 3),
(4520, '450794', '*ชุมพร   ', 497, 33, 3),
(4521, '450795', '*เมยวดี   ', 497, 33, 3),
(4522, '450796', '*คำพอง   ', 497, 33, 3),
(4523, '450797', '*อัคคะคำ   ', 497, 33, 3),
(4524, '450798', '*เชียงใหม่   ', 497, 33, 3),
(4525, '450799', '*ขามเบี้ย   ', 497, 33, 3),
(4526, '450801', 'ขามเปี้ย   ', 498, 33, 3),
(4527, '450802', 'เชียงใหม่   ', 498, 33, 3),
(4528, '450803', 'บัวคำ   ', 498, 33, 3),
(4529, '450804', 'อัคคะคำ   ', 498, 33, 3),
(4530, '450805', 'สะอาด   ', 498, 33, 3),
(4531, '450806', 'คำพอุง   ', 498, 33, 3),
(4532, '450807', 'หนองตาไก้   ', 498, 33, 3),
(4533, '450808', 'ดอนโอง   ', 498, 33, 3),
(4534, '450809', 'โพธิ์ศรี   ', 498, 33, 3),
(4535, '450901', 'หนองพอก   ', 499, 33, 3),
(4536, '450902', 'บึงงาม   ', 499, 33, 3),
(4537, '450903', 'ภูเขาทอง   ', 499, 33, 3),
(4538, '450904', 'กกโพธิ์   ', 499, 33, 3),
(4539, '450905', 'โคกสว่าง   ', 499, 33, 3),
(4540, '450906', 'หนองขุ่นใหญ่   ', 499, 33, 3),
(4541, '450907', 'รอบเมือง   ', 499, 33, 3),
(4542, '450908', 'ผาน้ำย้อย   ', 499, 33, 3),
(4543, '450909', 'ท่าสีดา   ', 499, 33, 3),
(4544, '451001', 'กลาง   ', 500, 33, 3),
(4545, '451002', 'นางาม   ', 500, 33, 3),
(4546, '451003', 'เมืองไพร   ', 500, 33, 3),
(4547, '451004', 'นาแซง   ', 500, 33, 3),
(4548, '451005', 'นาเมือง   ', 500, 33, 3),
(4549, '451006', 'วังหลวง   ', 500, 33, 3),
(4550, '451007', 'ท่าม่วง   ', 500, 33, 3),
(4551, '451008', 'ขวาว   ', 500, 33, 3),
(4552, '451009', 'โพธิ์ทอง   ', 500, 33, 3),
(4553, '451010', 'ภูเงิน   ', 500, 33, 3),
(4554, '451011', 'เกาะแก้ว   ', 500, 33, 3),
(4555, '451012', 'นาเลิง   ', 500, 33, 3),
(4556, '451013', 'เหล่าน้อย   ', 500, 33, 3),
(4557, '451014', 'ศรีวิลัย   ', 500, 33, 3),
(4558, '451015', 'หนองหลวง   ', 500, 33, 3),
(4559, '451016', 'พรสวรรค์   ', 500, 33, 3),
(4560, '451017', 'ขวัญเมือง   ', 500, 33, 3),
(4561, '451018', 'บึงเกลือ   ', 500, 33, 3),
(4562, '451101', 'สระคู   ', 501, 33, 3),
(4563, '451102', 'ดอกไม้   ', 501, 33, 3),
(4564, '451103', 'นาใหญ่   ', 501, 33, 3),
(4565, '451104', 'หินกอง   ', 501, 33, 3),
(4566, '451105', 'เมืองทุ่ง   ', 501, 33, 3),
(4567, '451106', 'หัวโทน   ', 501, 33, 3),
(4568, '451107', 'บ่อพันขัน   ', 501, 33, 3),
(4569, '451108', 'ทุ่งหลวง   ', 501, 33, 3),
(4570, '451109', 'หัวช้าง   ', 501, 33, 3),
(4571, '451110', 'น้ำคำ   ', 501, 33, 3),
(4572, '451111', 'ห้วยหินลาด   ', 501, 33, 3),
(4573, '451112', 'ช้างเผือก   ', 501, 33, 3),
(4574, '451113', 'ทุ่งกุลา   ', 501, 33, 3),
(4575, '451114', 'ทุ่งศรีเมือง   ', 501, 33, 3),
(4576, '451115', 'จำปาขัน   ', 501, 33, 3),
(4577, '451201', 'หนองผือ   ', 502, 33, 3),
(4578, '451202', 'หนองหิน   ', 502, 33, 3),
(4579, '451203', 'คูเมือง   ', 502, 33, 3),
(4580, '451204', 'กกกุง   ', 502, 33, 3),
(4581, '451205', 'เมืองสรวง   ', 502, 33, 3),
(4582, '451301', 'โพนทราย   ', 503, 33, 3),
(4583, '451302', 'สามขา   ', 503, 33, 3),
(4584, '451303', 'ศรีสว่าง   ', 503, 33, 3),
(4585, '451304', 'ยางคำ   ', 503, 33, 3),
(4586, '451305', 'ท่าหาดยาว   ', 503, 33, 3),
(4587, '451401', 'อาจสามารถ   ', 504, 33, 3),
(4588, '451402', 'โพนเมือง   ', 504, 33, 3),
(4589, '451403', 'บ้านแจ้ง   ', 504, 33, 3),
(4590, '451404', 'หน่อม   ', 504, 33, 3),
(4591, '451405', 'หนองหมื่นถ่าน   ', 504, 33, 3),
(4592, '451406', 'หนองขาม   ', 504, 33, 3),
(4593, '451407', 'โหรา   ', 504, 33, 3),
(4594, '451408', 'หนองบัว   ', 504, 33, 3),
(4595, '451409', 'ขี้เหล็ก   ', 504, 33, 3),
(4596, '451410', 'บ้านดู่   ', 504, 33, 3),
(4597, '451501', 'เมยวดี   ', 505, 33, 3),
(4598, '451502', 'ชุมพร   ', 505, 33, 3),
(4599, '451503', 'บุ่งเลิศ   ', 505, 33, 3),
(4600, '451504', 'ชมสะอาด   ', 505, 33, 3),
(4601, '451601', 'โพธิ์ทอง   ', 506, 33, 3),
(4602, '451602', 'ศรีสมเด็จ   ', 506, 33, 3),
(4603, '451603', 'เมืองเปลือย   ', 506, 33, 3),
(4604, '451604', 'หนองใหญ่   ', 506, 33, 3),
(4605, '451605', 'สวนจิก   ', 506, 33, 3),
(4606, '451606', 'โพธิ์สัย   ', 506, 33, 3),
(4607, '451607', 'หนองแวงควง   ', 506, 33, 3),
(4608, '451608', 'บ้านบาก   ', 506, 33, 3),
(4609, '451701', 'ดินดำ   ', 507, 33, 3),
(4610, '451702', 'ปาฝา   ', 507, 33, 3),
(4611, '451703', 'ม่วงลาด   ', 507, 33, 3),
(4612, '451704', 'จังหาร   ', 507, 33, 3),
(4613, '451705', 'ดงสิงห์   ', 507, 33, 3),
(4614, '451706', 'ยางใหญ่   ', 507, 33, 3),
(4615, '451707', 'ผักแว่น   ', 507, 33, 3),
(4616, '451708', 'แสนชาติ   ', 507, 33, 3),
(4617, '451801', 'เชียงขวัญ   ', 508, 33, 3),
(4618, '451802', 'พลับพลา   ', 508, 33, 3),
(4619, '451803', 'พระธาตุ   ', 508, 33, 3),
(4620, '451804', 'พระเจ้า   ', 508, 33, 3),
(4621, '451805', 'หมูม้น   ', 508, 33, 3),
(4622, '451806', 'บ้านเขือง   ', 508, 33, 3),
(4623, '451901', 'หนองฮี   ', 509, 33, 3),
(4624, '451902', 'สาวแห   ', 509, 33, 3),
(4625, '451903', 'ดูกอึ่ง   ', 509, 33, 3),
(4626, '451904', 'เด่นราษฎร์   ', 509, 33, 3),
(4627, '452001', 'ทุ่งเขาหลวง   ', 510, 33, 3),
(4628, '452002', 'เทอดไทย   ', 510, 33, 3),
(4629, '452003', 'บึงงาม   ', 510, 33, 3),
(4630, '452004', 'มะบ้า   ', 510, 33, 3),
(4631, '452005', 'เหล่า   ', 510, 33, 3),
(4632, '460101', 'กาฬสินธุ์   ', 511, 34, 3),
(4633, '460102', 'เหนือ   ', 511, 34, 3),
(4634, '460103', 'หลุบ   ', 511, 34, 3),
(4635, '460104', 'ไผ่   ', 511, 34, 3),
(4636, '460105', 'ลำปาว   ', 511, 34, 3),
(4637, '460106', 'ลำพาน   ', 511, 34, 3),
(4638, '460107', 'เชียงเครือ   ', 511, 34, 3),
(4639, '460108', 'บึงวิชัย   ', 511, 34, 3),
(4640, '460109', 'ห้วยโพธิ์   ', 511, 34, 3),
(4641, '460110', 'ม่วงนา*   ', 511, 34, 3),
(4642, '460111', 'ภูปอ   ', 511, 34, 3),
(4643, '460112', 'ดงพยุง*   ', 511, 34, 3),
(4644, '460113', 'ภูดิน   ', 511, 34, 3),
(4645, '460114', 'ดอนจาน*   ', 511, 34, 3),
(4646, '460115', 'หนองกุง   ', 511, 34, 3),
(4647, '460116', 'กลางหมื่น   ', 511, 34, 3),
(4648, '460117', 'ขมิ้น   ', 511, 34, 3),
(4649, '460118', 'นาจำปา*   ', 511, 34, 3),
(4650, '460119', 'โพนทอง   ', 511, 34, 3),
(4651, '460120', 'นาจารย์   ', 511, 34, 3),
(4652, '460121', 'ลำคลอง   ', 511, 34, 3),
(4653, '460122', 'สะอาดไชยศรี*   ', 511, 34, 3),
(4654, '460198', 'นามน*   ', 511, 34, 3),
(4655, '460199', 'ยอดแกง*   ', 511, 34, 3),
(4656, '460201', 'นามน   ', 512, 34, 3),
(4657, '460202', 'ยอดแกง   ', 512, 34, 3),
(4658, '460203', 'สงเปลือย   ', 512, 34, 3),
(4659, '460204', 'หลักเหลี่ยม   ', 512, 34, 3),
(4660, '460205', 'หนองบัว   ', 512, 34, 3),
(4661, '460301', 'กมลาไสย   ', 513, 34, 3),
(4662, '460302', 'หลักเมือง   ', 513, 34, 3),
(4663, '460303', 'โพนงาม   ', 513, 34, 3),
(4664, '460304', 'ดงลิง   ', 513, 34, 3),
(4665, '460305', 'ธัญญา   ', 513, 34, 3),
(4666, '460306', 'กุดฆ้องชัย*   ', 513, 34, 3),
(4667, '460307', 'ลำชี*   ', 513, 34, 3),
(4668, '460308', 'หนองแปน   ', 513, 34, 3),
(4669, '460309', 'โคกสะอาด*   ', 513, 34, 3),
(4670, '460310', 'เจ้าท่า   ', 513, 34, 3),
(4671, '460311', 'โคกสมบูรณ์   ', 513, 34, 3),
(4672, '460312', 'โนนศิลา*   ', 513, 34, 3),
(4673, '460313', 'ฆ้องชัยพัฒนา*   ', 513, 34, 3),
(4674, '460401', 'ร่องคำ   ', 514, 34, 3),
(4675, '460402', 'สามัคคี   ', 514, 34, 3),
(4676, '460403', 'เหล่าอ้อย   ', 514, 34, 3),
(4677, '460501', 'บัวขาว   ', 515, 34, 3),
(4678, '460502', 'แจนแลน   ', 515, 34, 3),
(4679, '460503', 'เหล่าใหญ่   ', 515, 34, 3),
(4680, '460504', 'จุมจัง   ', 515, 34, 3),
(4681, '460505', 'เหล่าไฮงาม   ', 515, 34, 3),
(4682, '460506', 'กุดหว้า   ', 515, 34, 3),
(4683, '460507', 'สามขา   ', 515, 34, 3),
(4684, '460508', 'นาขาม   ', 515, 34, 3),
(4685, '460509', 'หนองห้าง   ', 515, 34, 3),
(4686, '460510', 'นาโก   ', 515, 34, 3),
(4687, '460511', 'สมสะอาด   ', 515, 34, 3),
(4688, '460512', 'กุดค้าว   ', 515, 34, 3),
(4689, '460601', 'คุ้มเก่า   ', 516, 34, 3),
(4690, '460602', 'สงเปลือย   ', 516, 34, 3),
(4691, '460603', 'หนองผือ   ', 516, 34, 3),
(4692, '460604', '*ภูแล่นช้าง   ', 516, 34, 3),
(4693, '460605', '*นาคู   ', 516, 34, 3),
(4694, '460606', 'กุดสิมคุ้มใหม่   ', 516, 34, 3),
(4695, '460607', '*บ่อแก้ว   ', 516, 34, 3),
(4696, '460608', 'สระพังทอง   ', 516, 34, 3),
(4697, '460609', '*สายนาวัง   ', 516, 34, 3),
(4698, '460610', '*โนนนาจาน   ', 516, 34, 3),
(4699, '460611', 'กุดปลาค้าว   ', 516, 34, 3),
(4700, '460701', 'ยางตลาด   ', 517, 34, 3),
(4701, '460702', 'หัวงัว   ', 517, 34, 3),
(4702, '460703', 'อุ่มเม่า   ', 517, 34, 3),
(4703, '460704', 'บัวบาน   ', 517, 34, 3),
(4704, '460705', 'เว่อ   ', 517, 34, 3),
(4705, '460706', 'อิตื้อ   ', 517, 34, 3),
(4706, '460707', 'หัวนาคำ   ', 517, 34, 3),
(4707, '460708', 'หนองอิเฒ่า   ', 517, 34, 3),
(4708, '460709', 'ดอนสมบูรณ์   ', 517, 34, 3),
(4709, '460710', 'นาเชือก   ', 517, 34, 3),
(4710, '460711', 'คลองขาม   ', 517, 34, 3),
(4711, '460712', 'เขาพระนอน   ', 517, 34, 3),
(4712, '460713', 'นาดี   ', 517, 34, 3),
(4713, '460714', 'โนนสูง   ', 517, 34, 3),
(4714, '460715', 'หนองตอกแป้น   ', 517, 34, 3),
(4715, '460801', 'ห้วยเม็ก   ', 518, 34, 3),
(4716, '460802', 'คำใหญ่   ', 518, 34, 3),
(4717, '460803', 'กุดโดน   ', 518, 34, 3),
(4718, '460804', 'บึงนาเรียง   ', 518, 34, 3),
(4719, '460805', 'หัวหิน   ', 518, 34, 3),
(4720, '460806', 'พิมูล   ', 518, 34, 3),
(4721, '460807', 'คำเหมือดแก้ว   ', 518, 34, 3),
(4722, '460808', 'โนนสะอาด   ', 518, 34, 3),
(4723, '460809', 'ทรายทอง   ', 518, 34, 3),
(4724, '460901', 'ภูสิงห์   ', 519, 34, 3),
(4725, '460902', 'สหัสขันธ์   ', 519, 34, 3),
(4726, '460903', 'นามะเขือ   ', 519, 34, 3),
(4727, '460904', 'โนนศิลา   ', 519, 34, 3),
(4728, '460905', 'นิคม   ', 519, 34, 3),
(4729, '460906', 'โนนแหลมทอง   ', 519, 34, 3),
(4730, '460907', 'โนนบุรี   ', 519, 34, 3),
(4731, '460908', 'โนนน้ำเกลี้ยง   ', 519, 34, 3),
(4732, '460996', '*หนองบัว   ', 519, 34, 3),
(4733, '460997', '*ทุ่งคลอง   ', 519, 34, 3),
(4734, '460998', '*สำราญ   ', 519, 34, 3),
(4735, '460999', '*โพน   ', 519, 34, 3),
(4736, '461001', 'ทุ่งคลอง   ', 520, 34, 3),
(4737, '461002', 'โพน   ', 520, 34, 3),
(4738, '461003', '*สำราญ   ', 520, 34, 3),
(4739, '461004', '*สำราญใต้   ', 520, 34, 3),
(4740, '461005', 'ดินจี่   ', 520, 34, 3),
(4741, '461006', 'นาบอน   ', 520, 34, 3),
(4742, '461007', 'นาทัน   ', 520, 34, 3),
(4743, '461008', '*คำสร้างเที่ยง   ', 520, 34, 3),
(4744, '461009', 'เนินยาง   ', 520, 34, 3),
(4745, '461010', '*หนองช้าง   ', 520, 34, 3),
(4746, '461101', 'ท่าคันโท   ', 521, 34, 3),
(4747, '461102', 'กุงเก่า   ', 521, 34, 3),
(4748, '461103', 'ยางอู้ม   ', 521, 34, 3),
(4749, '461104', 'กุดจิก   ', 521, 34, 3),
(4750, '461105', 'นาตาล   ', 521, 34, 3),
(4751, '461106', 'ดงสมบูรณ์   ', 521, 34, 3),
(4752, '461198', '*โคกเครือ   ', 521, 34, 3),
(4753, '461199', '*สหัสขันธ์   ', 521, 34, 3),
(4754, '461201', 'หนองกุงศรี   ', 522, 34, 3);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(4755, '461202', 'หนองบัว   ', 522, 34, 3),
(4756, '461203', 'โคกเครือ   ', 522, 34, 3),
(4757, '461204', 'หนองสรวง   ', 522, 34, 3),
(4758, '461205', 'เสาเล้า   ', 522, 34, 3),
(4759, '461206', 'หนองใหญ่   ', 522, 34, 3),
(4760, '461207', 'ดงมูล   ', 522, 34, 3),
(4761, '461208', 'ลำหนองแสน   ', 522, 34, 3),
(4762, '461209', 'หนองหิน   ', 522, 34, 3),
(4763, '461301', 'สมเด็จ   ', 523, 34, 3),
(4764, '461302', 'หนองแวง   ', 523, 34, 3),
(4765, '461303', 'แซงบาดาล   ', 523, 34, 3),
(4766, '461304', 'มหาไชย   ', 523, 34, 3),
(4767, '461305', 'หมูม่น   ', 523, 34, 3),
(4768, '461306', 'ผาเสวย   ', 523, 34, 3),
(4769, '461307', 'ศรีสมเด็จ   ', 523, 34, 3),
(4770, '461308', 'ลำห้วยหลัว   ', 523, 34, 3),
(4771, '461401', 'คำบง   ', 524, 34, 3),
(4772, '461402', 'ไค้นุ่น   ', 524, 34, 3),
(4773, '461403', 'นิคมห้วยผึ้ง   ', 524, 34, 3),
(4774, '461404', 'หนองอีบุตร   ', 524, 34, 3),
(4775, '461501', 'สำราญ   ', 525, 34, 3),
(4776, '461502', 'สำราญใต้   ', 525, 34, 3),
(4777, '461503', 'คำสร้างเที่ยง   ', 525, 34, 3),
(4778, '461504', 'หนองช้าง   ', 525, 34, 3),
(4779, '461601', 'นาคู   ', 526, 34, 3),
(4780, '461602', 'สายนาวัง   ', 526, 34, 3),
(4781, '461603', 'โนนนาจาน   ', 526, 34, 3),
(4782, '461604', 'บ่อแก้ว   ', 526, 34, 3),
(4783, '461605', 'ภูแล่นช้าง   ', 526, 34, 3),
(4784, '461701', 'ดอนจาน   ', 527, 34, 3),
(4785, '461702', 'สะอาดไชยศรี   ', 527, 34, 3),
(4786, '461703', 'ดงพยุง   ', 527, 34, 3),
(4787, '461704', 'ม่วงนา   ', 527, 34, 3),
(4788, '461705', 'นาจำปา   ', 527, 34, 3),
(4789, '461801', 'ฆ้องชัยพัฒนา   ', 528, 34, 3),
(4790, '461802', 'เหล่ากลาง   ', 528, 34, 3),
(4791, '461803', 'โคกสะอาด   ', 528, 34, 3),
(4792, '461804', 'โนนศิลาเลิง   ', 528, 34, 3),
(4793, '461805', 'ลำชี   ', 528, 34, 3),
(4794, '470101', 'ธาตุเชิงชุม   ', 529, 35, 3),
(4795, '470102', 'ขมิ้น   ', 529, 35, 3),
(4796, '470103', 'งิ้วด่อน   ', 529, 35, 3),
(4797, '470104', 'โนนหอม   ', 529, 35, 3),
(4798, '470105', '*นาตงวัฒนา   ', 529, 35, 3),
(4799, '470106', 'เชียงเครือ   ', 529, 35, 3),
(4800, '470107', 'ท่าแร่   ', 529, 35, 3),
(4801, '470108', '*บ้านโพน   ', 529, 35, 3),
(4802, '470109', 'ม่วงลาย   ', 529, 35, 3),
(4803, '470110', 'กกปลาซิว*   ', 529, 35, 3),
(4804, '470111', 'ดงชน   ', 529, 35, 3),
(4805, '470112', 'ห้วยยาง   ', 529, 35, 3),
(4806, '470113', 'พังขว้าง   ', 529, 35, 3),
(4807, '470114', '*นาแก้ว   ', 529, 35, 3),
(4808, '470115', 'ดงมะไฟ   ', 529, 35, 3),
(4809, '470116', 'ธาตุนาเวง   ', 529, 35, 3),
(4810, '470117', 'เหล่าปอแดง   ', 529, 35, 3),
(4811, '470118', 'หนองลาด   ', 529, 35, 3),
(4812, '470119', '*บ้านแป้น   ', 529, 35, 3),
(4813, '470120', 'ฮางโฮง   ', 529, 35, 3),
(4814, '470121', 'โคกก่อง   ', 529, 35, 3),
(4815, '470194', '*นาตงวัฒนา   ', 529, 35, 3),
(4816, '470195', '*นาแก้ว   ', 529, 35, 3),
(4817, '470196', '*บ้านโพน   ', 529, 35, 3),
(4818, '470197', '*เหล่าโพนค้อ   ', 529, 35, 3),
(4819, '470198', '*ตองโขบ   ', 529, 35, 3),
(4820, '470199', '*เต่างอย   ', 529, 35, 3),
(4821, '470201', 'กุสุมาลย์   ', 530, 35, 3),
(4822, '470202', 'นาโพธิ์   ', 530, 35, 3),
(4823, '470203', 'นาเพียง   ', 530, 35, 3),
(4824, '470204', 'โพธิไพศาล   ', 530, 35, 3),
(4825, '470205', 'อุ่มจาน   ', 530, 35, 3),
(4826, '470301', 'กุดบาก   ', 531, 35, 3),
(4827, '470302', '*โคกภู   ', 531, 35, 3),
(4828, '470303', 'นาม่อง   ', 531, 35, 3),
(4829, '470304', '*สร้างค้อ   ', 531, 35, 3),
(4830, '470305', 'กุดไห   ', 531, 35, 3),
(4831, '470306', '*หลุบเลา   ', 531, 35, 3),
(4832, '470401', 'พรรณา   ', 532, 35, 3),
(4833, '470402', 'วังยาง   ', 532, 35, 3),
(4834, '470403', 'พอกน้อย   ', 532, 35, 3),
(4835, '470404', 'นาหัวบ่อ   ', 532, 35, 3),
(4836, '470405', 'ไร่   ', 532, 35, 3),
(4837, '470406', 'ช้างมิ่ง   ', 532, 35, 3),
(4838, '470407', 'นาใน   ', 532, 35, 3),
(4839, '470408', 'สว่าง   ', 532, 35, 3),
(4840, '470409', 'บะฮี   ', 532, 35, 3),
(4841, '470410', 'เชิงชุม   ', 532, 35, 3),
(4842, '470501', 'พังโคน   ', 533, 35, 3),
(4843, '470502', 'ม่วงไข่   ', 533, 35, 3),
(4844, '470503', 'แร่   ', 533, 35, 3),
(4845, '470504', 'ไฮหย่อง   ', 533, 35, 3),
(4846, '470505', 'ต้นผึ้ง   ', 533, 35, 3),
(4847, '470597', '*คลองกระจัง   ', 533, 35, 3),
(4848, '470598', '*สระกรวด   ', 533, 35, 3),
(4849, '470601', 'วาริชภูมิ   ', 534, 35, 3),
(4850, '470602', 'ปลาโหล   ', 534, 35, 3),
(4851, '470603', 'หนองลาด   ', 534, 35, 3),
(4852, '470604', 'คำบ่อ   ', 534, 35, 3),
(4853, '470605', 'ค้อเขียว   ', 534, 35, 3),
(4854, '470701', 'นิคมน้ำอูน   ', 535, 35, 3),
(4855, '470702', 'หนองปลิง   ', 535, 35, 3),
(4856, '470703', 'หนองบัว   ', 535, 35, 3),
(4857, '470704', 'สุวรรณคาม   ', 535, 35, 3),
(4858, '470801', 'วานรนิวาส   ', 536, 35, 3),
(4859, '470802', 'เดื่อศรีคันไชย   ', 536, 35, 3),
(4860, '470803', 'ขัวก่าย   ', 536, 35, 3),
(4861, '470804', 'หนองสนม   ', 536, 35, 3),
(4862, '470805', 'คูสะคาม   ', 536, 35, 3),
(4863, '470806', 'ธาตุ   ', 536, 35, 3),
(4864, '470807', 'หนองแวง   ', 536, 35, 3),
(4865, '470808', 'ศรีวิชัย   ', 536, 35, 3),
(4866, '470809', 'นาซอ   ', 536, 35, 3),
(4867, '470810', 'อินทร์แปลง   ', 536, 35, 3),
(4868, '470811', 'นาคำ   ', 536, 35, 3),
(4869, '470812', 'คอนสวรรค์   ', 536, 35, 3),
(4870, '470813', 'กุดเรือคำ   ', 536, 35, 3),
(4871, '470814', 'หนองแวงใต้   ', 536, 35, 3),
(4872, '470901', 'คำตากล้า   ', 537, 35, 3),
(4873, '470902', 'หนองบัวสิม   ', 537, 35, 3),
(4874, '470903', 'นาแต้   ', 537, 35, 3),
(4875, '470904', 'แพด   ', 537, 35, 3),
(4876, '471001', 'ม่วง   ', 538, 35, 3),
(4877, '471002', 'มาย   ', 538, 35, 3),
(4878, '471003', 'ดงหม้อทอง   ', 538, 35, 3),
(4879, '471004', 'ดงเหนือ   ', 538, 35, 3),
(4880, '471005', 'ดงหม้อทองใต้   ', 538, 35, 3),
(4881, '471006', 'ห้วยหลัว   ', 538, 35, 3),
(4882, '471007', 'โนนสะอาด   ', 538, 35, 3),
(4883, '471008', 'หนองกวั่ง   ', 538, 35, 3),
(4884, '471009', 'บ่อแก้ว   ', 538, 35, 3),
(4885, '471101', 'อากาศ   ', 539, 35, 3),
(4886, '471102', 'โพนแพง   ', 539, 35, 3),
(4887, '471103', 'วาใหญ่   ', 539, 35, 3),
(4888, '471104', 'โพนงาม   ', 539, 35, 3),
(4889, '471105', 'ท่าก้อน   ', 539, 35, 3),
(4890, '471106', 'นาฮี   ', 539, 35, 3),
(4891, '471107', 'บะหว้า   ', 539, 35, 3),
(4892, '471108', 'สามัคคีพัฒนา   ', 539, 35, 3),
(4893, '471201', 'สว่างแดนดิน   ', 540, 35, 3),
(4894, '471202', '*บ้านเหล่า   ', 540, 35, 3),
(4895, '471203', 'คำสะอาด   ', 540, 35, 3),
(4896, '471204', 'บ้านต้าย   ', 540, 35, 3),
(4897, '471205', '*เจริญศิลป์   ', 540, 35, 3),
(4898, '471206', 'บงเหนือ   ', 540, 35, 3),
(4899, '471207', 'โพนสูง   ', 540, 35, 3),
(4900, '471208', 'โคกสี   ', 540, 35, 3),
(4901, '471209', '*ทุ่งแก   ', 540, 35, 3),
(4902, '471210', 'หนองหลวง   ', 540, 35, 3),
(4903, '471211', 'บงใต้   ', 540, 35, 3),
(4904, '471212', 'ค้อใต้   ', 540, 35, 3),
(4905, '471213', 'พันนา   ', 540, 35, 3),
(4906, '471214', 'แวง   ', 540, 35, 3),
(4907, '471215', 'ทรายมูล   ', 540, 35, 3),
(4908, '471216', 'ตาลโกน   ', 540, 35, 3),
(4909, '471217', 'ตาลเนิ้ง   ', 540, 35, 3),
(4910, '471218', '*โคกศิลา   ', 540, 35, 3),
(4911, '471219', '*หนองแปน   ', 540, 35, 3),
(4912, '471220', 'ธาตุทอง   ', 540, 35, 3),
(4913, '471221', 'บ้านถ่อน   ', 540, 35, 3),
(4914, '471301', 'ส่องดาว   ', 541, 35, 3),
(4915, '471302', 'ท่าศิลา   ', 541, 35, 3),
(4916, '471303', 'วัฒนา   ', 541, 35, 3),
(4917, '471304', 'ปทุมวาปี   ', 541, 35, 3),
(4918, '471401', 'เต่างอย   ', 542, 35, 3),
(4919, '471402', 'บึงทวาย   ', 542, 35, 3),
(4920, '471403', 'นาตาล   ', 542, 35, 3),
(4921, '471404', 'จันทร์เพ็ญ   ', 542, 35, 3),
(4922, '471501', 'ตองโขบ   ', 543, 35, 3),
(4923, '471502', 'เหล่าโพนค้อ   ', 543, 35, 3),
(4924, '471503', 'ด่านม่วงคำ   ', 543, 35, 3),
(4925, '471504', 'แมดนาท่ม   ', 543, 35, 3),
(4926, '471601', 'บ้านเหล่า   ', 544, 35, 3),
(4927, '471602', 'เจริญศิลป์   ', 544, 35, 3),
(4928, '471603', 'ทุ่งแก   ', 544, 35, 3),
(4929, '471604', 'โคกศิลา   ', 544, 35, 3),
(4930, '471605', 'หนองแปน   ', 544, 35, 3),
(4931, '471701', 'บ้านโพน   ', 545, 35, 3),
(4932, '471702', 'นาแก้ว   ', 545, 35, 3),
(4933, '471703', 'นาตงวัฒนา   ', 545, 35, 3),
(4934, '471704', 'บ้านแป้น   ', 545, 35, 3),
(4935, '471705', 'เชียงสือ   ', 545, 35, 3),
(4936, '471801', 'สร้างค้อ   ', 546, 35, 3),
(4937, '471802', 'หลุบเลา   ', 546, 35, 3),
(4938, '471803', 'โคกภู   ', 546, 35, 3),
(4939, '471804', 'กกปลาซิว   ', 546, 35, 3),
(4940, '480101', 'ในเมือง   ', 549, 36, 3),
(4941, '480102', 'หนองแสง   ', 549, 36, 3),
(4942, '480103', 'นาทราย   ', 549, 36, 3),
(4943, '480104', 'นาราชควาย   ', 549, 36, 3),
(4944, '480105', 'กุรุคุ   ', 549, 36, 3),
(4945, '480106', 'บ้านผึ้ง   ', 549, 36, 3),
(4946, '480107', 'อาจสามารถ   ', 549, 36, 3),
(4947, '480108', 'ขามเฒ่า   ', 549, 36, 3),
(4948, '480109', 'บ้านกลาง   ', 549, 36, 3),
(4949, '480110', 'ท่าค้อ   ', 549, 36, 3),
(4950, '480111', 'คำเตย   ', 549, 36, 3),
(4951, '480112', 'หนองญาติ   ', 549, 36, 3),
(4952, '480113', 'ดงขวาง   ', 549, 36, 3),
(4953, '480114', 'วังตามัว   ', 549, 36, 3),
(4954, '480115', 'โพธิ์ตาก   ', 549, 36, 3),
(4955, '480201', 'ปลาปาก   ', 550, 36, 3),
(4956, '480202', 'หนองฮี   ', 550, 36, 3),
(4957, '480203', 'กุตาไก้   ', 550, 36, 3),
(4958, '480204', 'โคกสว่าง   ', 550, 36, 3),
(4959, '480205', 'โคกสูง   ', 550, 36, 3),
(4960, '480206', 'มหาชัย   ', 550, 36, 3),
(4961, '480207', 'นามะเขือ   ', 550, 36, 3),
(4962, '480208', 'หนองเทาใหญ่   ', 550, 36, 3),
(4963, '480301', 'ท่าอุเทน   ', 551, 36, 3),
(4964, '480302', 'โนนตาล   ', 551, 36, 3),
(4965, '480303', 'ท่าจำปา   ', 551, 36, 3),
(4966, '480304', 'ไชยบุรี   ', 551, 36, 3),
(4967, '480305', 'พนอม   ', 551, 36, 3),
(4968, '480306', 'พะทาย   ', 551, 36, 3),
(4969, '480307', '*นาขมิ้น   ', 551, 36, 3),
(4970, '480308', '*โพนบก   ', 551, 36, 3),
(4971, '480309', '*โพนสวรรค์   ', 551, 36, 3),
(4972, '480310', '*บ้านค้อ   ', 551, 36, 3),
(4973, '480311', 'เวินพระบาท   ', 551, 36, 3),
(4974, '480312', 'รามราช   ', 551, 36, 3),
(4975, '480313', '*นาหัวบ่อ   ', 551, 36, 3),
(4976, '480314', 'หนองเทา   ', 551, 36, 3),
(4977, '480401', 'บ้านแพง   ', 552, 36, 3),
(4978, '480402', 'ไผ่ล้อม   ', 552, 36, 3),
(4979, '480403', 'โพนทอง   ', 552, 36, 3),
(4980, '480404', 'หนองแวง   ', 552, 36, 3),
(4981, '480405', '*นาทม   ', 552, 36, 3),
(4982, '480406', '*หนองซน   ', 552, 36, 3),
(4983, '480407', '*ดอนเตย   ', 552, 36, 3),
(4984, '480408', 'นางัว   ', 552, 36, 3),
(4985, '480409', 'นาเข   ', 552, 36, 3),
(4986, '480501', 'ธาตุพนม   ', 553, 36, 3),
(4987, '480502', 'ฝั่งแดง   ', 553, 36, 3),
(4988, '480503', 'โพนแพง   ', 553, 36, 3),
(4989, '480504', 'พระกลางทุ่ง   ', 553, 36, 3),
(4990, '480505', 'นาถ่อน   ', 553, 36, 3),
(4991, '480506', 'แสนพัน   ', 553, 36, 3),
(4992, '480507', 'ดอนนางหงส์   ', 553, 36, 3),
(4993, '480508', 'น้ำก่ำ   ', 553, 36, 3),
(4994, '480509', 'อุ่มเหม้า   ', 553, 36, 3),
(4995, '480510', 'นาหนาด   ', 553, 36, 3),
(4996, '480511', 'กุดฉิม   ', 553, 36, 3),
(4997, '480512', 'ธาตุพนมเหนือ   ', 553, 36, 3),
(4998, '480601', 'เรณู   ', 554, 36, 3),
(4999, '480602', 'โพนทอง   ', 554, 36, 3),
(5000, '480603', 'ท่าลาด   ', 554, 36, 3),
(5001, '480604', 'นางาม   ', 554, 36, 3),
(5002, '480605', 'โคกหินแฮ่   ', 554, 36, 3),
(5003, '480606', '*เรณูนคร   ', 554, 36, 3),
(5004, '480607', 'หนองย่างชิ้น   ', 554, 36, 3),
(5005, '480608', 'เรณูใต้   ', 554, 36, 3),
(5006, '480609', 'นาขาม   ', 554, 36, 3),
(5007, '480701', 'นาแก   ', 555, 36, 3),
(5008, '480702', 'พระซอง   ', 555, 36, 3),
(5009, '480703', 'หนองสังข์   ', 555, 36, 3),
(5010, '480704', 'นาคู่   ', 555, 36, 3),
(5011, '480705', 'พิมาน   ', 555, 36, 3),
(5012, '480706', 'พุ่มแก   ', 555, 36, 3),
(5013, '480707', 'ก้านเหลือง   ', 555, 36, 3),
(5014, '480708', 'หนองบ่อ   ', 555, 36, 3),
(5015, '480709', 'นาเลียง   ', 555, 36, 3),
(5016, '480710', 'โคกสี*   ', 555, 36, 3),
(5017, '480711', 'วังยาง*   ', 555, 36, 3),
(5018, '480712', 'บ้านแก้ง   ', 555, 36, 3),
(5019, '480713', 'คำพี้   ', 555, 36, 3),
(5020, '480714', 'ยอดชาด*   ', 555, 36, 3),
(5021, '480715', 'สีชมพู   ', 555, 36, 3),
(5022, '480716', 'หนองโพธิ์*   ', 555, 36, 3),
(5023, '480801', 'ศรีสงคราม   ', 556, 36, 3),
(5024, '480802', 'นาเดื่อ   ', 556, 36, 3),
(5025, '480803', 'บ้านเอื้อง   ', 556, 36, 3),
(5026, '480804', 'สามผง   ', 556, 36, 3),
(5027, '480805', 'ท่าบ่อสงคราม   ', 556, 36, 3),
(5028, '480806', 'บ้านข่า   ', 556, 36, 3),
(5029, '480807', 'นาคำ   ', 556, 36, 3),
(5030, '480808', 'โพนสว่าง   ', 556, 36, 3),
(5031, '480809', 'หาดแพง   ', 556, 36, 3),
(5032, '480901', 'นาหว้า   ', 557, 36, 3),
(5033, '480902', 'นางัว   ', 557, 36, 3),
(5034, '480903', 'บ้านเสียว   ', 557, 36, 3),
(5035, '480904', 'นาคูณใหญ่   ', 557, 36, 3),
(5036, '480905', 'เหล่าพัฒนา   ', 557, 36, 3),
(5037, '480906', 'ท่าเรือ   ', 557, 36, 3),
(5038, '481001', 'โพนสวรรค์   ', 558, 36, 3),
(5039, '481002', 'นาหัวบ่อ   ', 558, 36, 3),
(5040, '481003', 'นาขมิ้น   ', 558, 36, 3),
(5041, '481004', 'โพนบก   ', 558, 36, 3),
(5042, '481005', 'บ้านค้อ   ', 558, 36, 3),
(5043, '481006', 'โพนจาน   ', 558, 36, 3),
(5044, '481007', 'นาใน   ', 558, 36, 3),
(5045, '481101', 'นาทม   ', 559, 36, 3),
(5046, '481102', 'หนองซน   ', 559, 36, 3),
(5047, '481103', 'ดอนเตย   ', 559, 36, 3),
(5048, '481201', 'วังยาง   ', 560, 36, 3),
(5049, '481202', 'โคกสี   ', 560, 36, 3),
(5050, '481203', 'ยอดชาด   ', 560, 36, 3),
(5051, '481204', 'หนองโพธิ์   ', 560, 36, 3),
(5052, '490101', 'มุกดาหาร   ', 561, 37, 3),
(5053, '490102', 'ศรีบุญเรือง   ', 561, 37, 3),
(5054, '490103', 'บ้านโคก   ', 561, 37, 3),
(5055, '490104', 'บางทรายใหญ่   ', 561, 37, 3),
(5056, '490105', 'โพนทราย   ', 561, 37, 3),
(5057, '490106', 'ผึ่งแดด   ', 561, 37, 3),
(5058, '490107', 'นาโสก   ', 561, 37, 3),
(5059, '490108', 'นาสีนวน   ', 561, 37, 3),
(5060, '490109', 'คำป่าหลาย   ', 561, 37, 3),
(5061, '490110', 'คำอาฮวน   ', 561, 37, 3),
(5062, '490111', 'ดงเย็น   ', 561, 37, 3),
(5063, '490112', 'ดงมอน   ', 561, 37, 3),
(5064, '490113', 'กุดแข้   ', 561, 37, 3),
(5065, '490194', '*หนองแวง   ', 561, 37, 3),
(5066, '490195', '*กกแดง   ', 561, 37, 3),
(5067, '490196', '*นากอก   ', 561, 37, 3),
(5068, '490197', '*นำคมคำสร้อย   ', 561, 37, 3),
(5069, '490198', '*บางทรายน้อย   ', 561, 37, 3),
(5070, '490199', '*หว้านใหญ่   ', 561, 37, 3),
(5071, '490201', 'นิคมคำสร้อย   ', 562, 37, 3),
(5072, '490202', 'นากอก   ', 562, 37, 3),
(5073, '490203', 'หนองแวง   ', 562, 37, 3),
(5074, '490204', 'กกแดง   ', 562, 37, 3),
(5075, '490205', 'นาอุดม   ', 562, 37, 3),
(5076, '490206', 'โชคชัย   ', 562, 37, 3),
(5077, '490207', 'ร่มเกล้า   ', 562, 37, 3),
(5078, '490301', 'ดอนตาล   ', 563, 37, 3),
(5079, '490302', 'โพธิ์ไทร   ', 563, 37, 3),
(5080, '490303', 'ป่าไร่   ', 563, 37, 3),
(5081, '490304', 'เหล่าหมี   ', 563, 37, 3),
(5082, '490305', 'บ้านบาก   ', 563, 37, 3),
(5083, '490306', 'นาสะเม็ง   ', 563, 37, 3),
(5084, '490307', 'บ้านแก้ง   ', 563, 37, 3),
(5085, '490401', 'ดงหลวง   ', 564, 37, 3),
(5086, '490402', 'หนองบัว   ', 564, 37, 3),
(5087, '490403', 'กกตูม   ', 564, 37, 3),
(5088, '490404', 'หนองแคน   ', 564, 37, 3),
(5089, '490405', 'ชะโนดน้อย   ', 564, 37, 3),
(5090, '490406', 'พังแดง   ', 564, 37, 3),
(5091, '490501', '*หนองสูงใต้   ', 565, 37, 3),
(5092, '490502', '*หนองสูง   ', 565, 37, 3),
(5093, '490503', 'บ้านซ่ง   ', 565, 37, 3),
(5094, '490504', 'คำชะอี   ', 565, 37, 3),
(5095, '490505', 'หนองเอี่ยน   ', 565, 37, 3),
(5096, '490506', 'บ้านค้อ   ', 565, 37, 3),
(5097, '490507', 'บ้านเหล่า   ', 565, 37, 3),
(5098, '490508', 'โพนงาม   ', 565, 37, 3),
(5099, '490509', '*โนนยาง   ', 565, 37, 3),
(5100, '490510', '*บ้านเป้า   ', 565, 37, 3),
(5101, '490511', 'เหล่าสร้างถ่อ   ', 565, 37, 3),
(5102, '490512', 'คำบก   ', 565, 37, 3),
(5103, '490513', '*ภูวง   ', 565, 37, 3),
(5104, '490514', 'น้ำเที่ยง   ', 565, 37, 3),
(5105, '490597', '*หนองสูงใต้   ', 565, 37, 3),
(5106, '490598', '*บ้านเป้า   ', 565, 37, 3),
(5107, '490599', '*หนองสูง   ', 565, 37, 3),
(5108, '490601', 'หว้านใหญ่   ', 566, 37, 3),
(5109, '490602', 'ป่งขาม   ', 566, 37, 3),
(5110, '490603', 'บางทรายน้อย   ', 566, 37, 3),
(5111, '490604', 'ชะโนด   ', 566, 37, 3),
(5112, '490605', 'ดงหมู   ', 566, 37, 3),
(5113, '490701', 'หนองสูง   ', 567, 37, 3),
(5114, '490702', 'โนนยาง   ', 567, 37, 3),
(5115, '490703', 'ภูวง   ', 567, 37, 3),
(5116, '490704', 'บ้านเป้า   ', 567, 37, 3),
(5117, '490705', 'หนองสูงใต้   ', 567, 37, 3),
(5118, '490706', 'หนองสูงเหนือ   ', 567, 37, 3),
(5119, '500101', 'ศรีภูมิ   ', 568, 38, 1),
(5120, '500102', 'พระสิงห์   ', 568, 38, 1),
(5121, '500103', 'หายยา   ', 568, 38, 1),
(5122, '500104', 'ช้างม่อย   ', 568, 38, 1),
(5123, '500105', 'ช้างคลาน   ', 568, 38, 1),
(5124, '500106', 'วัดเกต   ', 568, 38, 1),
(5125, '500107', 'ช้างเผือก   ', 568, 38, 1),
(5126, '500108', 'สุเทพ   ', 568, 38, 1),
(5127, '500109', 'แม่เหียะ   ', 568, 38, 1),
(5128, '500110', 'ป่าแดด   ', 568, 38, 1),
(5129, '500111', 'หนองหอย   ', 568, 38, 1),
(5130, '500112', 'ท่าศาลา   ', 568, 38, 1),
(5131, '500113', 'หนองป่าครั่ง   ', 568, 38, 1),
(5132, '500114', 'ฟ้าฮ่าม   ', 568, 38, 1),
(5133, '500115', 'ป่าตัน   ', 568, 38, 1),
(5134, '500116', 'สันผีเสื้อ   ', 568, 38, 1),
(5135, '500201', '*ยางคราม   ', 569, 38, 1),
(5136, '500202', '*สองแคว   ', 569, 38, 1),
(5137, '500203', 'บ้านหลวง   ', 569, 38, 1),
(5138, '500204', 'ข่วงเปา   ', 569, 38, 1),
(5139, '500205', 'สบเตี๊ยะ   ', 569, 38, 1),
(5140, '500206', 'บ้านแปะ   ', 569, 38, 1),
(5141, '500207', 'ดอยแก้ว   ', 569, 38, 1),
(5142, '500208', '*ดอยหล่อ   ', 569, 38, 1),
(5143, '500209', 'แม่สอย   ', 569, 38, 1),
(5144, '500210', '*สันติสุข   ', 569, 38, 1),
(5145, '500301', 'ช่างเคิ่ง   ', 570, 38, 1),
(5146, '500302', 'ท่าผา   ', 570, 38, 1),
(5147, '500303', 'บ้านทับ   ', 570, 38, 1),
(5148, '500304', 'แม่ศึก   ', 570, 38, 1),
(5149, '500305', 'แม่นาจร   ', 570, 38, 1),
(5150, '500306', 'บ้านจันทร์   ', 570, 38, 1),
(5151, '500307', 'ปางหินฝน   ', 570, 38, 1),
(5152, '500308', 'กองแขก   ', 570, 38, 1),
(5153, '500309', 'แม่แดด   ', 570, 38, 1),
(5154, '500310', 'แจ่มหลวง   ', 570, 38, 1),
(5155, '500401', 'เชียงดาว   ', 571, 38, 1),
(5156, '500402', 'เมืองนะ   ', 571, 38, 1),
(5157, '500403', 'เมืองงาย   ', 571, 38, 1),
(5158, '500404', 'แม่นะ   ', 571, 38, 1),
(5159, '500405', 'เมืองคอง   ', 571, 38, 1),
(5160, '500406', 'ปิงโค้ง   ', 571, 38, 1),
(5161, '500407', 'ทุ่งข้าวพวง   ', 571, 38, 1),
(5162, '500501', 'เชิงดอย   ', 572, 38, 1),
(5163, '500502', 'สันปูเลย   ', 572, 38, 1),
(5164, '500503', 'ลวงเหนือ   ', 572, 38, 1),
(5165, '500504', 'ป่าป้อง   ', 572, 38, 1),
(5166, '500505', 'สง่าบ้าน   ', 572, 38, 1),
(5167, '500506', 'ป่าลาน   ', 572, 38, 1),
(5168, '500507', 'ตลาดขวัญ   ', 572, 38, 1),
(5169, '500508', 'สำราญราษฎร์   ', 572, 38, 1),
(5170, '500509', 'แม่คือ   ', 572, 38, 1),
(5171, '500510', 'ตลาดใหญ่   ', 572, 38, 1),
(5172, '500511', 'แม่ฮ้อยเงิน   ', 572, 38, 1),
(5173, '500512', 'แม่โป่ง   ', 572, 38, 1),
(5174, '500513', 'ป่าเมี่ยง   ', 572, 38, 1),
(5175, '500514', 'เทพเสด็จ   ', 572, 38, 1),
(5176, '500601', 'สันมหาพน   ', 573, 38, 1),
(5177, '500602', 'แม่แตง   ', 573, 38, 1),
(5178, '500603', 'ขี้เหล็ก   ', 573, 38, 1),
(5179, '500604', 'ช่อแล   ', 573, 38, 1),
(5180, '500605', 'แม่หอพระ   ', 573, 38, 1),
(5181, '500606', 'สบเปิง   ', 573, 38, 1),
(5182, '500607', 'บ้านเป้า   ', 573, 38, 1),
(5183, '500608', 'สันป่ายาง   ', 573, 38, 1),
(5184, '500609', 'ป่าแป๋   ', 573, 38, 1),
(5185, '500610', 'เมืองก๋าย   ', 573, 38, 1),
(5186, '500611', 'บ้านช้าง   ', 573, 38, 1),
(5187, '500612', 'กื้ดช้าง   ', 573, 38, 1),
(5188, '500613', 'อินทขิล   ', 573, 38, 1),
(5189, '500614', 'สมก๋าย   ', 573, 38, 1),
(5190, '500701', 'ริมใต้   ', 574, 38, 1),
(5191, '500702', 'ริมเหนือ   ', 574, 38, 1),
(5192, '500703', 'สันโป่ง   ', 574, 38, 1),
(5193, '500704', 'ขี้เหล็ก   ', 574, 38, 1),
(5194, '500705', 'สะลวง   ', 574, 38, 1),
(5195, '500706', 'ห้วยทราย   ', 574, 38, 1),
(5196, '500707', 'แม่แรม   ', 574, 38, 1),
(5197, '500708', 'โป่งแยง   ', 574, 38, 1),
(5198, '500709', 'แม่สา   ', 574, 38, 1),
(5199, '500710', 'ดอนแก้ว   ', 574, 38, 1),
(5200, '500711', 'เหมืองแก้ว   ', 574, 38, 1),
(5201, '500801', 'สะเมิงใต้   ', 575, 38, 1),
(5202, '500802', 'สะเมิงเหนือ   ', 575, 38, 1),
(5203, '500803', 'แม่สาบ   ', 575, 38, 1),
(5204, '500804', 'บ่อแก้ว   ', 575, 38, 1),
(5205, '500805', 'ยั้งเมิน   ', 575, 38, 1),
(5206, '500901', 'เวียง   ', 576, 38, 1),
(5207, '500902', '*ปงตำ   ', 576, 38, 1),
(5208, '500903', 'ม่อนปิ่น   ', 576, 38, 1),
(5209, '500904', 'แม่งอน   ', 576, 38, 1),
(5210, '500905', 'แม่สูน   ', 576, 38, 1),
(5211, '500906', 'สันทราย   ', 576, 38, 1),
(5212, '500907', '*ศรีดงเย็น   ', 576, 38, 1),
(5213, '500908', '*แม่ทะลบ   ', 576, 38, 1),
(5214, '500909', '*หนองบัว   ', 576, 38, 1),
(5215, '500910', 'แม่คะ   ', 576, 38, 1),
(5216, '500911', 'แม่ข่า   ', 576, 38, 1),
(5217, '500912', 'โป่งน้ำร้อน   ', 576, 38, 1),
(5218, '500995', 'แม่นาวาง*   ', 576, 38, 1),
(5219, '500996', 'แม่สาว*   ', 576, 38, 1),
(5220, '500997', 'แม่อาย*   ', 576, 38, 1),
(5221, '500998', '*ศรีดงเย็น   ', 576, 38, 1),
(5222, '500999', '*ปงตำ   ', 576, 38, 1),
(5223, '501001', 'แม่อาย   ', 577, 38, 1),
(5224, '501002', 'แม่สาว   ', 577, 38, 1),
(5225, '501003', 'สันต้นหมื้อ   ', 577, 38, 1),
(5226, '501004', 'แม่นาวาง   ', 577, 38, 1),
(5227, '501005', 'ท่าตอน   ', 577, 38, 1),
(5228, '501006', 'บ้านหลวง   ', 577, 38, 1),
(5229, '501007', 'มะลิกา   ', 577, 38, 1),
(5230, '501101', 'เวียง   ', 578, 38, 1),
(5231, '501102', 'ทุ่งหลวง   ', 578, 38, 1),
(5232, '501103', 'ป่าตุ้ม   ', 578, 38, 1),
(5233, '501104', 'ป่าไหน่   ', 578, 38, 1),
(5234, '501105', 'สันทราย   ', 578, 38, 1),
(5235, '501106', 'บ้านโป่ง   ', 578, 38, 1),
(5236, '501107', 'น้ำแพร่   ', 578, 38, 1),
(5237, '501108', 'เขื่อนผาก   ', 578, 38, 1),
(5238, '501109', 'แม่แวน   ', 578, 38, 1),
(5239, '501110', 'แม่ปั๋ง   ', 578, 38, 1),
(5240, '501111', 'โหล่งขอด   ', 578, 38, 1),
(5241, '501201', 'ยุหว่า   ', 579, 38, 1),
(5242, '501202', 'สันกลาง   ', 579, 38, 1),
(5243, '501203', 'ท่าวังพร้าว   ', 579, 38, 1),
(5244, '501204', 'มะขามหลวง   ', 579, 38, 1),
(5245, '501205', 'แม่ก๊า   ', 579, 38, 1),
(5246, '501206', 'บ้านแม   ', 579, 38, 1),
(5247, '501207', 'บ้านกลาง   ', 579, 38, 1),
(5248, '501208', 'ทุ่งสะโตก   ', 579, 38, 1),
(5249, '501209', '*ทุ่งปี้   ', 579, 38, 1),
(5250, '501210', 'ทุ่งต้อม   ', 579, 38, 1),
(5251, '501211', '*บ้านกาด   ', 579, 38, 1),
(5252, '501212', '*แม่วิน   ', 579, 38, 1),
(5253, '501213', '*ทุ่งรวงทอง   ', 579, 38, 1),
(5254, '501214', 'น้ำบ่อหลวง   ', 579, 38, 1),
(5255, '501215', 'มะขุนหวาน   ', 579, 38, 1),
(5256, '501301', 'สันกำแพง   ', 580, 38, 1),
(5257, '501302', 'ทรายมูล   ', 580, 38, 1),
(5258, '501303', 'ร้องวัวแดง   ', 580, 38, 1),
(5259, '501304', 'บวกค้าง   ', 580, 38, 1),
(5260, '501305', 'แช่ช้าง   ', 580, 38, 1),
(5261, '501306', 'ออนใต้   ', 580, 38, 1),
(5262, '501307', '*ออนเหนือ   ', 580, 38, 1),
(5263, '501308', '*บ้านสหกรณ์   ', 580, 38, 1),
(5264, '501309', '*ห้วยแก้ว   ', 580, 38, 1),
(5265, '501310', 'แม่ปูคา   ', 580, 38, 1),
(5266, '501311', 'ห้วยทราย   ', 580, 38, 1),
(5267, '501312', 'ต้นเปา   ', 580, 38, 1),
(5268, '501313', 'สันกลาง   ', 580, 38, 1),
(5269, '501314', '*แม่ทา   ', 580, 38, 1),
(5270, '501315', '*ทาเหนือ   ', 580, 38, 1),
(5271, '501316', '*ออนกลาง   ', 580, 38, 1),
(5272, '501397', '*แม่วิน   ', 580, 38, 1),
(5273, '501398', '*ทุ่งปี้   ', 580, 38, 1),
(5274, '501399', '*บ้านกาด   ', 580, 38, 1),
(5275, '501401', 'สันทรายหลวง   ', 581, 38, 1),
(5276, '501402', 'สันทรายน้อย   ', 581, 38, 1),
(5277, '501403', 'สันพระเนตร   ', 581, 38, 1),
(5278, '501404', 'สันนาเม็ง   ', 581, 38, 1),
(5279, '501405', 'สันป่าเปา   ', 581, 38, 1),
(5280, '501406', 'หนองแหย่ง   ', 581, 38, 1),
(5281, '501407', 'หนองจ๊อม   ', 581, 38, 1),
(5282, '501408', 'หนองหาร   ', 581, 38, 1),
(5283, '501409', 'แม่แฝก   ', 581, 38, 1),
(5284, '501410', 'แม่แฝกใหม่   ', 581, 38, 1),
(5285, '501411', 'เมืองเล็น   ', 581, 38, 1),
(5286, '501412', 'ป่าไผ่   ', 581, 38, 1),
(5287, '501501', 'หางดง   ', 582, 38, 1),
(5288, '501502', 'หนองแก๋ว   ', 582, 38, 1),
(5289, '501503', 'หารแก้ว   ', 582, 38, 1),
(5290, '501504', 'หนองตอง   ', 582, 38, 1),
(5291, '501505', 'ขุนคง   ', 582, 38, 1),
(5292, '501506', 'สบแม่ข่า   ', 582, 38, 1),
(5293, '501507', 'บ้านแหวน   ', 582, 38, 1),
(5294, '501508', 'สันผักหวาน   ', 582, 38, 1),
(5295, '501509', 'หนองควาย   ', 582, 38, 1),
(5296, '501510', 'บ้านปง   ', 582, 38, 1),
(5297, '501511', 'น้ำแพร่   ', 582, 38, 1),
(5298, '501601', 'หางดง   ', 583, 38, 1),
(5299, '501602', 'ฮอด   ', 583, 38, 1),
(5300, '501603', 'บ้านตาล   ', 583, 38, 1),
(5301, '501604', 'บ่อหลวง   ', 583, 38, 1),
(5302, '501605', 'บ่อสลี   ', 583, 38, 1),
(5303, '501606', 'นาคอเรือ   ', 583, 38, 1),
(5304, '501701', 'ดอยเต่า   ', 584, 38, 1),
(5305, '501702', 'ท่าเดื่อ   ', 584, 38, 1),
(5306, '501703', 'มืดกา   ', 584, 38, 1),
(5307, '501704', 'บ้านแอ่น   ', 584, 38, 1),
(5308, '501705', 'บงตัน   ', 584, 38, 1),
(5309, '501706', 'โปงทุ่ง   ', 584, 38, 1),
(5310, '501801', 'อมก๋อย   ', 585, 38, 1),
(5311, '501802', 'ยางเปียง   ', 585, 38, 1),
(5312, '501803', 'แม่ตื่น   ', 585, 38, 1),
(5313, '501804', 'ม่อนจอง   ', 585, 38, 1),
(5314, '501805', 'สบโขง   ', 585, 38, 1),
(5315, '501806', 'นาเกียน   ', 585, 38, 1),
(5316, '501901', 'ยางเนิ้ง   ', 586, 38, 1),
(5317, '501902', 'สารภี   ', 586, 38, 1),
(5318, '501903', 'ชมภู   ', 586, 38, 1),
(5319, '501904', 'ไชยสถาน   ', 586, 38, 1),
(5320, '501905', 'ขัวมุง   ', 586, 38, 1),
(5321, '501906', 'หนองแฝก   ', 586, 38, 1),
(5322, '501907', 'หนองผึ้ง   ', 586, 38, 1),
(5323, '501908', 'ท่ากว้าง   ', 586, 38, 1),
(5324, '501909', 'ดอนแก้ว   ', 586, 38, 1),
(5325, '501910', 'ท่าวังตาล   ', 586, 38, 1),
(5326, '501911', 'สันทราย   ', 586, 38, 1),
(5327, '501912', 'ป่าบง   ', 586, 38, 1),
(5328, '502001', 'เมืองแหง   ', 587, 38, 1),
(5329, '502002', 'เปียงหลวง   ', 587, 38, 1),
(5330, '502003', 'แสนไห   ', 587, 38, 1),
(5331, '502101', 'ปงตำ   ', 588, 38, 1),
(5332, '502102', 'ศรีดงเย็น   ', 588, 38, 1),
(5333, '502103', 'แม่ทะลบ   ', 588, 38, 1),
(5334, '502104', 'หนองบัว   ', 588, 38, 1),
(5335, '502201', 'บ้านกาด   ', 589, 38, 1),
(5336, '502202', 'ทุ่งปี้   ', 589, 38, 1),
(5337, '502203', 'ทุ่งรวงทอง   ', 589, 38, 1),
(5338, '502204', 'แม่วิน   ', 589, 38, 1),
(5339, '502205', 'ดอนเปา   ', 589, 38, 1),
(5340, '502301', 'ออนเหนือ   ', 590, 38, 1),
(5341, '502302', 'ออนกลาง   ', 590, 38, 1),
(5342, '502303', 'บ้านสหกรณ์   ', 590, 38, 1),
(5343, '502304', 'ห้วยแก้ว   ', 590, 38, 1),
(5344, '502305', 'แม่ทา   ', 590, 38, 1),
(5345, '502306', 'ทาเหนือ   ', 590, 38, 1),
(5346, '502401', 'ดอยหล่อ   ', 591, 38, 1),
(5347, '502402', 'สองแคว   ', 591, 38, 1),
(5348, '502403', 'ยางคราม   ', 591, 38, 1),
(5349, '502404', 'สันติสุข   ', 591, 38, 1),
(5350, '510101', 'ในเมือง   ', 595, 39, 1),
(5351, '510102', 'เหมืองง่า   ', 595, 39, 1),
(5352, '510103', 'อุโมงค์   ', 595, 39, 1),
(5353, '510104', 'หนองช้างคืน   ', 595, 39, 1),
(5354, '510105', 'ประตูป่า   ', 595, 39, 1),
(5355, '510106', 'ริมปิง   ', 595, 39, 1),
(5356, '510107', 'ต้นธง   ', 595, 39, 1),
(5357, '510108', 'บ้านแป้น   ', 595, 39, 1),
(5358, '510109', 'เหมืองจี้   ', 595, 39, 1),
(5359, '510110', 'ป่าสัก   ', 595, 39, 1),
(5360, '510111', 'เวียงยอง   ', 595, 39, 1),
(5361, '510112', 'บ้านกลาง   ', 595, 39, 1),
(5362, '510113', 'มะเขือแจ้   ', 595, 39, 1),
(5363, '510114', '*บ้านธิ   ', 595, 39, 1),
(5364, '510115', '*ห้วยยาบ   ', 595, 39, 1),
(5365, '510116', 'ศรีบัวบาน   ', 595, 39, 1),
(5366, '510117', 'หนองหนาม   ', 595, 39, 1),
(5367, '510198', '*ห้วยยาบ   ', 595, 39, 1),
(5368, '510199', '*บ้านธิ   ', 595, 39, 1),
(5369, '510201', 'ทาปลาดุก   ', 596, 39, 1),
(5370, '510202', 'ทาสบเส้า   ', 596, 39, 1),
(5371, '510203', 'ทากาศ   ', 596, 39, 1),
(5372, '510204', 'ทาขุมเงิน   ', 596, 39, 1),
(5373, '510205', 'ทาทุ่งหลวง   ', 596, 39, 1),
(5374, '510206', 'ทาแม่ลอบ   ', 596, 39, 1),
(5375, '510301', 'บ้านโฮ่ง   ', 597, 39, 1),
(5376, '510302', 'ป่าพลู   ', 597, 39, 1),
(5377, '510303', 'เหล่ายาว   ', 597, 39, 1),
(5378, '510304', 'ศรีเตี้ย   ', 597, 39, 1),
(5379, '510305', 'หนองปลาสะวาย   ', 597, 39, 1),
(5380, '510401', 'ลี้   ', 598, 39, 1),
(5381, '510402', 'แม่ตืน   ', 598, 39, 1),
(5382, '510403', 'นาทราย   ', 598, 39, 1),
(5383, '510404', 'ดงดำ   ', 598, 39, 1),
(5384, '510405', 'ก้อ   ', 598, 39, 1),
(5385, '510406', 'แม่ลาน   ', 598, 39, 1),
(5386, '510407', 'บ้านไผ่*   ', 598, 39, 1),
(5387, '510408', 'ป่าไผ่   ', 598, 39, 1),
(5388, '510409', 'ศรีวิชัย   ', 598, 39, 1),
(5389, '510498', '*บ้านปวง   ', 598, 39, 1),
(5390, '510499', '*ทุ่งหัวช้าง   ', 598, 39, 1),
(5391, '510501', 'ทุ่งหัวช้าง   ', 599, 39, 1),
(5392, '510502', 'บ้านปวง   ', 599, 39, 1),
(5393, '510503', 'ตะเคียนปม   ', 599, 39, 1),
(5394, '510601', 'ปากบ่อง   ', 600, 39, 1),
(5395, '510602', 'ป่าซาง   ', 600, 39, 1),
(5396, '510603', 'แม่แรง   ', 600, 39, 1),
(5397, '510604', 'ม่วงน้อย   ', 600, 39, 1),
(5398, '510605', 'บ้านเรือน   ', 600, 39, 1),
(5399, '510606', 'มะกอก   ', 600, 39, 1),
(5400, '510607', 'ท่าตุ้ม   ', 600, 39, 1),
(5401, '510608', 'น้ำดิบ   ', 600, 39, 1),
(5402, '510609', '*วังผาง   ', 600, 39, 1),
(5403, '510610', '*หนองล่อง   ', 600, 39, 1),
(5404, '510611', 'นครเจดีย์   ', 600, 39, 1),
(5405, '510612', '*หนองยวง   ', 600, 39, 1),
(5406, '510701', 'บ้านธิ   ', 601, 39, 1),
(5407, '510702', 'ห้วยยาบ   ', 601, 39, 1),
(5408, '510801', 'หนองล่อง   ', 602, 39, 1),
(5409, '510802', 'หนองยวง   ', 602, 39, 1),
(5410, '510803', 'วังผาง   ', 602, 39, 1),
(5411, '520101', 'เวียงเหนือ   ', 603, 40, 1),
(5412, '520102', 'หัวเวียง   ', 603, 40, 1),
(5413, '520103', 'สวนดอก   ', 603, 40, 1),
(5414, '520104', 'สบตุ๋ย   ', 603, 40, 1),
(5415, '520105', 'พระบาท   ', 603, 40, 1),
(5416, '520106', 'ชมพู   ', 603, 40, 1),
(5417, '520107', 'กล้วยแพะ   ', 603, 40, 1),
(5418, '520108', 'ปงแสนทอง   ', 603, 40, 1),
(5419, '520109', 'บ้านแลง   ', 603, 40, 1),
(5420, '520110', 'บ้านเสด็จ   ', 603, 40, 1),
(5421, '520111', 'พิชัย   ', 603, 40, 1),
(5422, '520112', 'ทุ่งฝาย   ', 603, 40, 1),
(5423, '520113', 'บ้านเอื้อม   ', 603, 40, 1),
(5424, '520114', 'บ้านเป้า   ', 603, 40, 1),
(5425, '520115', 'บ้านค่า   ', 603, 40, 1),
(5426, '520116', 'บ่อแฮ้ว   ', 603, 40, 1),
(5427, '520117', 'ต้นธงชัย   ', 603, 40, 1),
(5428, '520118', 'นิคมพัฒนา   ', 603, 40, 1),
(5429, '520119', 'บุญนาคพัฒนา   ', 603, 40, 1),
(5430, '520198', '*นาสัก   ', 603, 40, 1),
(5431, '520199', '*บ้านดง   ', 603, 40, 1),
(5432, '520201', 'บ้านดง   ', 604, 40, 1),
(5433, '520202', 'นาสัก   ', 604, 40, 1),
(5434, '520203', 'จางเหนือ   ', 604, 40, 1),
(5435, '520204', 'แม่เมาะ   ', 604, 40, 1),
(5436, '520205', 'สบป้าด   ', 604, 40, 1),
(5437, '520301', 'ลำปางหลวง   ', 605, 40, 1),
(5438, '520302', 'นาแก้ว   ', 605, 40, 1),
(5439, '520303', 'ไหล่หิน   ', 605, 40, 1),
(5440, '520304', 'วังพร้าว   ', 605, 40, 1),
(5441, '520305', 'ศาลา   ', 605, 40, 1),
(5442, '520306', 'เกาะคา   ', 605, 40, 1),
(5443, '520307', 'นาแส่ง   ', 605, 40, 1),
(5444, '520308', 'ท่าผา   ', 605, 40, 1),
(5445, '520309', 'ใหม่พัฒนา   ', 605, 40, 1),
(5446, '520401', 'ทุ่งงาม   ', 606, 40, 1),
(5447, '520402', 'เสริมขวา   ', 606, 40, 1),
(5448, '520403', 'เสริมซ้าย   ', 606, 40, 1),
(5449, '520404', 'เสริมกลาง   ', 606, 40, 1),
(5450, '520501', 'หลวงเหนือ   ', 607, 40, 1),
(5451, '520502', 'หลวงใต้   ', 607, 40, 1),
(5452, '520503', 'บ้านโป่ง   ', 607, 40, 1),
(5453, '520504', 'บ้านร้อง   ', 607, 40, 1),
(5454, '520505', 'ปงเตา   ', 607, 40, 1),
(5455, '520506', 'นาแก   ', 607, 40, 1),
(5456, '520507', 'บ้านอ้อน   ', 607, 40, 1),
(5457, '520508', 'บ้านแหง   ', 607, 40, 1),
(5458, '520509', 'บ้านหวด   ', 607, 40, 1),
(5459, '520510', 'แม่ตีบ   ', 607, 40, 1),
(5460, '520601', 'แจ้ห่ม   ', 608, 40, 1),
(5461, '520602', 'บ้านสา   ', 608, 40, 1),
(5462, '520603', 'ปงดอน   ', 608, 40, 1),
(5463, '520604', 'แม่สุก   ', 608, 40, 1),
(5464, '520605', 'เมืองมาย   ', 608, 40, 1),
(5465, '520606', 'ทุ่งผึ้ง   ', 608, 40, 1),
(5466, '520607', 'วิเชตนคร   ', 608, 40, 1),
(5467, '520696', '*แจ้ซ้อน   ', 608, 40, 1),
(5468, '520697', '*ทุ่งกว๋าว   ', 608, 40, 1),
(5469, '520698', '*บ้านขอ   ', 608, 40, 1),
(5470, '520699', '*เมืองปาน   ', 608, 40, 1),
(5471, '520701', 'ทุ่งฮั้ว   ', 609, 40, 1),
(5472, '520702', 'วังเหนือ   ', 609, 40, 1),
(5473, '520703', 'วังใต้   ', 609, 40, 1),
(5474, '520704', 'ร่องเคาะ   ', 609, 40, 1),
(5475, '520705', 'วังทอง   ', 609, 40, 1),
(5476, '520706', 'วังซ้าย   ', 609, 40, 1),
(5477, '520707', 'วังแก้ว   ', 609, 40, 1),
(5478, '520708', 'วังทรายคำ   ', 609, 40, 1),
(5479, '520801', 'ล้อมแรด   ', 610, 40, 1),
(5480, '520802', 'แม่วะ   ', 610, 40, 1),
(5481, '520803', 'แม่ปะ   ', 610, 40, 1),
(5482, '520804', 'แม่มอก   ', 610, 40, 1),
(5483, '520805', 'เวียงมอก   ', 610, 40, 1),
(5484, '520806', 'นาโป่ง   ', 610, 40, 1),
(5485, '520807', 'แม่ถอด   ', 610, 40, 1),
(5486, '520808', 'เถินบุรี   ', 610, 40, 1),
(5487, '520901', 'แม่พริก   ', 611, 40, 1),
(5488, '520902', 'ผาปัง   ', 611, 40, 1),
(5489, '520903', 'แม่ปุ   ', 611, 40, 1),
(5490, '520904', 'พระบาทวังตวง   ', 611, 40, 1),
(5491, '521001', 'แม่ทะ   ', 612, 40, 1),
(5492, '521002', 'นาครัว   ', 612, 40, 1),
(5493, '521003', 'ป่าตัน   ', 612, 40, 1),
(5494, '521004', 'บ้านกิ่ว   ', 612, 40, 1),
(5495, '521005', 'บ้านบอม   ', 612, 40, 1),
(5496, '521006', 'น้ำโจ้   ', 612, 40, 1),
(5497, '521007', 'ดอนไฟ   ', 612, 40, 1),
(5498, '521008', 'หัวเสือ   ', 612, 40, 1),
(5499, '521009', 'สบป้าด*   ', 612, 40, 1),
(5500, '521010', 'วังเงิน   ', 612, 40, 1),
(5501, '521011', 'สันดอนแก้ว   ', 612, 40, 1),
(5502, '521101', 'สบปราบ   ', 613, 40, 1),
(5503, '521102', 'สมัย   ', 613, 40, 1),
(5504, '521103', 'แม่กัวะ   ', 613, 40, 1),
(5505, '521104', 'นายาง   ', 613, 40, 1),
(5506, '521201', 'ห้างฉัตร   ', 614, 40, 1),
(5507, '521202', 'หนองหล่ม   ', 614, 40, 1),
(5508, '521203', 'เมืองยาว   ', 614, 40, 1),
(5509, '521204', 'ปงยางคก   ', 614, 40, 1),
(5510, '521205', 'เวียงตาล   ', 614, 40, 1),
(5511, '521206', 'แม่สัน   ', 614, 40, 1),
(5512, '521207', 'วอแก้ว   ', 614, 40, 1),
(5513, '521301', 'เมืองปาน   ', 615, 40, 1),
(5514, '521302', 'บ้านขอ   ', 615, 40, 1),
(5515, '521303', 'ทุ่งกว๋าว   ', 615, 40, 1),
(5516, '521304', 'แจ้ซ้อน   ', 615, 40, 1),
(5517, '521305', 'หัวเมือง   ', 615, 40, 1),
(5518, '530101', 'ท่าอิฐ   ', 616, 41, 1),
(5519, '530102', 'ท่าเสา   ', 616, 41, 1),
(5520, '530103', 'บ้านเกาะ   ', 616, 41, 1),
(5521, '530104', 'ป่าเซ่า   ', 616, 41, 1),
(5522, '530105', 'คุ้งตะเภา   ', 616, 41, 1),
(5523, '530106', 'วังกะพี้   ', 616, 41, 1),
(5524, '530107', 'หาดกรวด   ', 616, 41, 1),
(5525, '530108', 'น้ำริด   ', 616, 41, 1),
(5526, '530109', 'งิ้วงาม   ', 616, 41, 1),
(5527, '530110', 'บ้านด่านนาขาม   ', 616, 41, 1),
(5528, '530111', 'บ้านด่าน   ', 616, 41, 1),
(5529, '530112', 'ผาจุก   ', 616, 41, 1),
(5530, '530113', 'วังดิน   ', 616, 41, 1),
(5531, '530114', 'แสนตอ   ', 616, 41, 1),
(5532, '530115', 'หาดงิ้ว   ', 616, 41, 1),
(5533, '530116', 'ขุนฝาง   ', 616, 41, 1),
(5534, '530117', 'ถ้ำฉลอง   ', 616, 41, 1),
(5535, '530199', '*ร่วมจิตร   ', 616, 41, 1),
(5536, '530201', 'วังแดง   ', 617, 41, 1),
(5537, '530202', 'บ้านแก่ง   ', 617, 41, 1),
(5538, '530203', 'หาดสองแคว   ', 617, 41, 1),
(5539, '530204', 'น้ำอ่าง   ', 617, 41, 1),
(5540, '530205', 'ข่อยสูง   ', 617, 41, 1),
(5541, '530296', '*น้ำพี้   ', 617, 41, 1),
(5542, '530297', '*บ่อทอง   ', 617, 41, 1),
(5543, '530298', '*ผักขวง   ', 617, 41, 1),
(5544, '530299', '*ป่าคาย   ', 617, 41, 1),
(5545, '530301', 'ท่าปลา   ', 618, 41, 1),
(5546, '530302', 'หาดล้า   ', 618, 41, 1),
(5547, '530303', 'ผาเลือด   ', 618, 41, 1),
(5548, '530304', 'จริม   ', 618, 41, 1),
(5549, '530305', 'น้ำหมัน   ', 618, 41, 1),
(5550, '530306', 'ท่าแฝก   ', 618, 41, 1),
(5551, '530307', 'นางพญา   ', 618, 41, 1),
(5552, '530308', 'ร่วมจิต   ', 618, 41, 1),
(5553, '530401', 'แสนตอ   ', 619, 41, 1),
(5554, '530402', 'บ้านฝาย   ', 619, 41, 1),
(5555, '530403', 'เด่นเหล็ก   ', 619, 41, 1),
(5556, '530404', 'น้ำไคร้   ', 619, 41, 1),
(5557, '530405', 'น้ำไผ่   ', 619, 41, 1),
(5558, '530406', 'ห้วยมุ่น   ', 619, 41, 1),
(5559, '530501', 'ฟากท่า   ', 620, 41, 1),
(5560, '530502', 'สองคอน   ', 620, 41, 1),
(5561, '530503', 'บ้านเสี้ยว   ', 620, 41, 1),
(5562, '530504', 'สองห้อง   ', 620, 41, 1),
(5563, '530601', 'ม่วงเจ็ดต้น   ', 621, 41, 1),
(5564, '530602', 'บ้านโคก   ', 621, 41, 1),
(5565, '530603', 'นาขุม   ', 621, 41, 1),
(5566, '530604', 'บ่อเบี้ย   ', 621, 41, 1),
(5567, '530701', 'ในเมือง   ', 622, 41, 1),
(5568, '530702', 'บ้านดารา   ', 622, 41, 1),
(5569, '530703', 'ไร่อ้อย   ', 622, 41, 1),
(5570, '530704', 'ท่าสัก   ', 622, 41, 1),
(5571, '530705', 'คอรุม   ', 622, 41, 1),
(5572, '530706', 'บ้านหม้อ   ', 622, 41, 1),
(5573, '530707', 'ท่ามะเฟือง   ', 622, 41, 1),
(5574, '530708', 'บ้านโคน   ', 622, 41, 1),
(5575, '530709', 'พญาแมน   ', 622, 41, 1),
(5576, '530710', 'นาอิน   ', 622, 41, 1),
(5577, '530711', 'นายาง   ', 622, 41, 1),
(5578, '530801', 'ศรีพนมมาศ   ', 623, 41, 1),
(5579, '530802', 'แม่พูล   ', 623, 41, 1),
(5580, '530803', 'นานกกก   ', 623, 41, 1),
(5581, '530804', 'ฝายหลวง   ', 623, 41, 1),
(5582, '530805', 'ชัยจุมพล   ', 623, 41, 1),
(5583, '530806', 'ไผ่ล้อม   ', 623, 41, 1),
(5584, '530807', 'ทุ่งยั้ง   ', 623, 41, 1),
(5585, '530808', 'ด่านแม่คำมัน   ', 623, 41, 1),
(5586, '530899', '*ศรีพนมมาศ   ', 623, 41, 1),
(5587, '530901', 'ผักขวง   ', 624, 41, 1),
(5588, '530902', 'บ่อทอง   ', 624, 41, 1),
(5589, '530903', 'ป่าคาย   ', 624, 41, 1),
(5590, '530904', 'น้ำพี้   ', 624, 41, 1),
(5591, '540101', 'ในเวียง   ', 625, 42, 1),
(5592, '540102', 'นาจักร   ', 625, 42, 1),
(5593, '540103', 'น้ำชำ   ', 625, 42, 1),
(5594, '540104', 'ป่าแดง   ', 625, 42, 1),
(5595, '540105', 'ทุ่งโฮ้ง   ', 625, 42, 1),
(5596, '540106', 'เหมืองหม้อ   ', 625, 42, 1),
(5597, '540107', 'วังธง   ', 625, 42, 1),
(5598, '540108', 'แม่หล่าย   ', 625, 42, 1),
(5599, '540109', 'ห้วยม้า   ', 625, 42, 1),
(5600, '540110', 'ป่าแมต   ', 625, 42, 1),
(5601, '540111', 'บ้านถิ่น   ', 625, 42, 1),
(5602, '540112', 'สวนเขื่อน   ', 625, 42, 1),
(5603, '540113', 'วังหงส์   ', 625, 42, 1),
(5604, '540114', 'แม่คำมี   ', 625, 42, 1),
(5605, '540115', 'ทุ่งกวาว   ', 625, 42, 1),
(5606, '540116', 'ท่าข้าม   ', 625, 42, 1),
(5607, '540117', 'แม่ยม   ', 625, 42, 1),
(5608, '540118', 'ช่อแฮ   ', 625, 42, 1),
(5609, '540119', 'ร่องฟอง   ', 625, 42, 1),
(5610, '540120', 'กาญจนา   ', 625, 42, 1),
(5611, '540201', 'ร้องกวาง   ', 626, 42, 1),
(5612, '540202', '*หนองม่วงไข่   ', 626, 42, 1),
(5613, '540203', '*แม่คำมี   ', 626, 42, 1),
(5614, '540204', 'ร้องเข็ม   ', 626, 42, 1),
(5615, '540205', 'น้ำเลา   ', 626, 42, 1),
(5616, '540206', 'บ้านเวียง   ', 626, 42, 1),
(5617, '540207', 'ทุ่งศรี   ', 626, 42, 1),
(5618, '540208', 'แม่ยางตาล   ', 626, 42, 1),
(5619, '540209', 'แม่ยางฮ่อ   ', 626, 42, 1),
(5620, '540210', 'ไผ่โทน   ', 626, 42, 1),
(5621, '540211', '*น้ำรัด   ', 626, 42, 1),
(5622, '540212', '*วังหลวง   ', 626, 42, 1),
(5623, '540213', 'ห้วยโรง   ', 626, 42, 1),
(5624, '540214', 'แม่ทราย   ', 626, 42, 1),
(5625, '540215', 'แม่ยางร้อง   ', 626, 42, 1),
(5626, '540298', '*หนองม่วงไข่   ', 626, 42, 1),
(5627, '540299', '*แม่คำมี   ', 626, 42, 1),
(5628, '540301', 'ห้วยอ้อ   ', 627, 42, 1),
(5629, '540302', 'บ้านปิน   ', 627, 42, 1),
(5630, '540303', 'ต้าผามอก   ', 627, 42, 1),
(5631, '540304', 'เวียงต้า   ', 627, 42, 1),
(5632, '540305', 'ปากกาง   ', 627, 42, 1),
(5633, '540306', 'หัวทุ่ง   ', 627, 42, 1),
(5634, '540307', 'ทุ่งแล้ง   ', 627, 42, 1),
(5635, '540308', 'บ่อเหล็กลอง   ', 627, 42, 1),
(5636, '540309', 'แม่ปาน   ', 627, 42, 1),
(5637, '540401', 'สูงเม่น   ', 628, 42, 1),
(5638, '540402', 'น้ำชำ   ', 628, 42, 1),
(5639, '540403', 'หัวฝาย   ', 628, 42, 1),
(5640, '540404', 'ดอนมูล   ', 628, 42, 1),
(5641, '540405', 'บ้านเหล่า   ', 628, 42, 1),
(5642, '540406', 'บ้านกวาง   ', 628, 42, 1),
(5643, '540407', 'บ้านปง   ', 628, 42, 1),
(5644, '540408', 'บ้านกาศ   ', 628, 42, 1),
(5645, '540409', 'ร่องกาศ   ', 628, 42, 1),
(5646, '540410', 'สบสาย   ', 628, 42, 1),
(5647, '540411', 'เวียงทอง   ', 628, 42, 1),
(5648, '540412', 'พระหลวง   ', 628, 42, 1),
(5649, '540501', 'เด่นชัย   ', 629, 42, 1),
(5650, '540502', 'แม่จั๊วะ   ', 629, 42, 1),
(5651, '540503', 'ไทรย้อย   ', 629, 42, 1),
(5652, '540504', 'ห้วยไร่   ', 629, 42, 1),
(5653, '540505', 'ปงป่าหวาย   ', 629, 42, 1),
(5654, '540601', 'บ้านหนุน   ', 630, 42, 1),
(5655, '540602', 'บ้านกลาง   ', 630, 42, 1),
(5656, '540603', 'ห้วยหม้าย   ', 630, 42, 1),
(5657, '540604', 'เตาปูน   ', 630, 42, 1),
(5658, '540605', 'หัวเมือง   ', 630, 42, 1),
(5659, '540606', 'สะเอียบ   ', 630, 42, 1),
(5660, '540607', 'แดนชุมพล   ', 630, 42, 1),
(5661, '540608', 'ทุ่งน้าว   ', 630, 42, 1),
(5662, '540701', 'วังชิ้น   ', 631, 42, 1),
(5663, '540702', 'สรอย   ', 631, 42, 1),
(5664, '540703', 'แม่ป้าก   ', 631, 42, 1),
(5665, '540704', 'นาพูน   ', 631, 42, 1),
(5666, '540705', 'แม่พุง   ', 631, 42, 1),
(5667, '540706', 'ป่าสัก   ', 631, 42, 1),
(5668, '540707', 'แม่เกิ๋ง   ', 631, 42, 1),
(5669, '540801', 'แม่คำมี   ', 632, 42, 1),
(5670, '540802', 'หนองม่วงไข่   ', 632, 42, 1),
(5671, '540803', 'น้ำรัด   ', 632, 42, 1),
(5672, '540804', 'วังหลวง   ', 632, 42, 1),
(5673, '540805', 'ตำหนักธรรม   ', 632, 42, 1),
(5674, '540806', 'ทุ่งแค้ว   ', 632, 42, 1),
(5675, '550101', 'ในเวียง   ', 633, 43, 1),
(5676, '550102', 'บ่อ   ', 633, 43, 1),
(5677, '550103', 'ผาสิงห์   ', 633, 43, 1),
(5678, '550104', 'ไชยสถาน   ', 633, 43, 1),
(5679, '550105', 'ถืมตอง   ', 633, 43, 1),
(5680, '550106', 'เรือง   ', 633, 43, 1),
(5681, '550107', 'นาซาว   ', 633, 43, 1),
(5682, '550108', 'ดู่ใต้   ', 633, 43, 1),
(5683, '550109', 'กองควาย   ', 633, 43, 1),
(5684, '550110', 'ฝายแก้ว*   ', 633, 43, 1),
(5685, '550111', 'ม่วงตึ๊ด*   ', 633, 43, 1),
(5686, '550112', 'ท่าน้าว*   ', 633, 43, 1),
(5687, '550113', 'นาปัง*   ', 633, 43, 1),
(5688, '550114', 'เมืองจัง*   ', 633, 43, 1),
(5689, '550115', 'น้ำแก่น*   ', 633, 43, 1),
(5690, '550116', 'สวก   ', 633, 43, 1),
(5691, '550117', 'สะเนียน   ', 633, 43, 1),
(5692, '550118', 'น้ำเกี๋ยน*   ', 633, 43, 1),
(5693, '550196', '*ป่าคาหลวง   ', 633, 43, 1),
(5694, '550197', '*หมอเมือง   ', 633, 43, 1),
(5695, '550198', '*บ้านฟ้า   ', 633, 43, 1),
(5696, '550199', '*ดู่พงษ์   ', 633, 43, 1),
(5697, '550201', '*พงษ์   ', 634, 43, 1),
(5698, '550202', 'หนองแดง   ', 634, 43, 1),
(5699, '550203', 'หมอเมือง   ', 634, 43, 1),
(5700, '550204', 'น้ำพาง   ', 634, 43, 1),
(5701, '550205', 'น้ำปาย   ', 634, 43, 1),
(5702, '550206', 'แม่จริม   ', 634, 43, 1),
(5703, '550301', 'บ้านฟ้า   ', 635, 43, 1),
(5704, '550302', 'ป่าคาหลวง   ', 635, 43, 1),
(5705, '550303', 'สวด   ', 635, 43, 1),
(5706, '550304', 'บ้านพี้   ', 635, 43, 1),
(5707, '550401', 'นาน้อย   ', 636, 43, 1),
(5708, '550402', 'เชียงของ   ', 636, 43, 1),
(5709, '550403', 'ศรีษะเกษ   ', 636, 43, 1),
(5710, '550404', 'สถาน   ', 636, 43, 1),
(5711, '550405', 'สันทะ   ', 636, 43, 1),
(5712, '550406', 'บัวใหญ่   ', 636, 43, 1),
(5713, '550407', 'น้ำตก   ', 636, 43, 1),
(5714, '550501', 'ปัว   ', 637, 43, 1),
(5715, '550502', 'แงง   ', 637, 43, 1),
(5716, '550503', 'สถาน   ', 637, 43, 1),
(5717, '550504', 'ศิลาแลง   ', 637, 43, 1),
(5718, '550505', 'ศิลาเพชร   ', 637, 43, 1),
(5719, '550506', 'อวน   ', 637, 43, 1),
(5720, '550507', '*บ่อเกลือเหนือ   ', 637, 43, 1),
(5721, '550508', '*บ่อเกลือใต้   ', 637, 43, 1),
(5722, '550509', 'ไชยวัฒนา   ', 637, 43, 1),
(5723, '550510', 'เจดีย์ชัย   ', 637, 43, 1),
(5724, '550511', 'ภูคา   ', 637, 43, 1),
(5725, '550512', 'สกาด   ', 637, 43, 1),
(5726, '550513', 'ป่ากลาง   ', 637, 43, 1),
(5727, '550514', 'วรนคร   ', 637, 43, 1),
(5728, '550601', 'ริม   ', 638, 43, 1),
(5729, '550602', 'ป่าคา   ', 638, 43, 1),
(5730, '550603', 'ผาตอ   ', 638, 43, 1),
(5731, '550604', 'ยม   ', 638, 43, 1),
(5732, '550605', 'ตาลชุม   ', 638, 43, 1),
(5733, '550606', 'ศรีภูมิ   ', 638, 43, 1),
(5734, '550607', 'จอมพระ   ', 638, 43, 1),
(5735, '550608', 'แสนทอง   ', 638, 43, 1),
(5736, '550609', 'ท่าวังผา   ', 638, 43, 1),
(5737, '550610', 'ผาทอง   ', 638, 43, 1),
(5738, '550701', 'กลางเวียง   ', 639, 43, 1),
(5739, '550702', 'ขึ่ง   ', 639, 43, 1),
(5740, '550703', 'ไหล่น่าน   ', 639, 43, 1),
(5741, '550704', 'ตาลชุม   ', 639, 43, 1),
(5742, '550705', 'นาเหลือง   ', 639, 43, 1),
(5743, '550706', 'ส้าน   ', 639, 43, 1),
(5744, '550707', 'น้ำมวบ   ', 639, 43, 1),
(5745, '550708', 'น้ำปั้ว   ', 639, 43, 1),
(5746, '550709', 'ยาบหัวนา   ', 639, 43, 1),
(5747, '550710', 'ปงสนุก   ', 639, 43, 1),
(5748, '550711', 'อ่ายนาไลย   ', 639, 43, 1),
(5749, '550712', 'ส้านนาหนองใหม่   ', 639, 43, 1),
(5750, '550713', 'แม่ขะนิง   ', 639, 43, 1),
(5751, '550714', 'แม่สาคร   ', 639, 43, 1),
(5752, '550715', 'จอมจันทร์   ', 639, 43, 1),
(5753, '550716', 'แม่สา   ', 639, 43, 1),
(5754, '550717', 'ทุ่งศรีทอง   ', 639, 43, 1),
(5755, '550801', 'ปอน   ', 640, 43, 1),
(5756, '550802', 'งอบ   ', 640, 43, 1),
(5757, '550803', 'และ   ', 640, 43, 1),
(5758, '550804', 'ทุ่งช้าง   ', 640, 43, 1),
(5759, '550805', 'ห้วยโก๋น*   ', 640, 43, 1),
(5760, '550898', '*เปือ   ', 640, 43, 1),
(5761, '550899', '*เชียงกลาง   ', 640, 43, 1),
(5762, '550901', 'เชียงกลาง   ', 641, 43, 1),
(5763, '550902', 'เปือ   ', 641, 43, 1),
(5764, '550903', 'เชียงคาน   ', 641, 43, 1),
(5765, '550904', 'พระธาตุ   ', 641, 43, 1),
(5766, '550905', '*นนาไร่หลวง   ', 641, 43, 1),
(5767, '550906', '*ชชนแดน   ', 641, 43, 1),
(5768, '550907', '*ยยอด   ', 641, 43, 1),
(5769, '550908', 'พญาแก้ว   ', 641, 43, 1),
(5770, '550909', 'พระพุทธบาท   ', 641, 43, 1),
(5771, '550998', '*นาไร่หลวง   ', 641, 43, 1),
(5772, '550999', '*ยอด   ', 641, 43, 1),
(5773, '551001', 'นาทะนุง   ', 642, 43, 1),
(5774, '551002', 'บ่อแก้ว   ', 642, 43, 1),
(5775, '551003', 'เมืองลี   ', 642, 43, 1),
(5776, '551004', 'ปิงหลวง   ', 642, 43, 1),
(5777, '551101', 'ดู่พงษ์   ', 643, 43, 1),
(5778, '551102', 'ป่าแลวหลวง   ', 643, 43, 1),
(5779, '551103', 'พงษ์   ', 643, 43, 1),
(5780, '551201', 'บ่อเกลือเหนือ   ', 644, 43, 1),
(5781, '551202', 'บ่อเกลือใต้   ', 644, 43, 1),
(5782, '551203', 'ขุนน่าน*   ', 644, 43, 1),
(5783, '551204', 'ภูฟ้า   ', 644, 43, 1),
(5784, '551205', 'ดงพญา   ', 644, 43, 1),
(5785, '551301', 'นาไร่หลวง   ', 645, 43, 1),
(5786, '551302', 'ชนแดน   ', 645, 43, 1),
(5787, '551303', 'ยอด   ', 645, 43, 1),
(5788, '551401', 'ม่วงตึ๊ด   ', 646, 43, 1),
(5789, '551402', 'นาปัง   ', 646, 43, 1),
(5790, '551403', 'น้ำแก่น   ', 646, 43, 1),
(5791, '551404', 'น้ำเกี๋ยน   ', 646, 43, 1),
(5792, '551405', 'เมืองจัง   ', 646, 43, 1),
(5793, '551406', 'ท่าน้าว   ', 646, 43, 1),
(5794, '551407', 'ฝายแก้ว   ', 646, 43, 1),
(5795, '551501', 'ห้วยโก๋น   ', 647, 43, 1),
(5796, '551502', 'ขุนน่าน   ', 647, 43, 1),
(5797, '560101', 'เวียง   ', 648, 44, 1),
(5798, '560102', 'แม่ต๋ำ   ', 648, 44, 1),
(5799, '560103', 'ดงเจน*   ', 648, 44, 1),
(5800, '560104', 'แม่นาเรือ   ', 648, 44, 1),
(5801, '560105', 'บ้านตุ่น   ', 648, 44, 1),
(5802, '560106', 'บ้านต๊ำ   ', 648, 44, 1),
(5803, '560107', 'บ้านต๋อม   ', 648, 44, 1),
(5804, '560108', 'แม่ปืม   ', 648, 44, 1),
(5805, '560109', 'ห้วยแก้ว*   ', 648, 44, 1),
(5806, '560110', 'แม่กา   ', 648, 44, 1),
(5807, '560111', 'บ้านใหม่   ', 648, 44, 1),
(5808, '560112', 'จำป่าหวาย   ', 648, 44, 1),
(5809, '560113', 'ท่าวังทอง   ', 648, 44, 1),
(5810, '560114', 'แม่ใส   ', 648, 44, 1),
(5811, '560115', 'บ้านสาง   ', 648, 44, 1),
(5812, '560116', 'ท่าจำปี   ', 648, 44, 1),
(5813, '560117', 'แม่อิง*   ', 648, 44, 1),
(5814, '560118', 'สันป่าม่วง   ', 648, 44, 1),
(5815, '560201', 'ห้วยข้าวก่ำ   ', 649, 44, 1),
(5816, '560202', 'จุน   ', 649, 44, 1),
(5817, '560203', 'ลอ   ', 649, 44, 1),
(5818, '560204', 'หงส์หิน   ', 649, 44, 1),
(5819, '560205', 'ทุ่งรวงทอง   ', 649, 44, 1),
(5820, '560206', 'ห้วยยางขาม   ', 649, 44, 1),
(5821, '560207', 'พระธาตุขิงแกง   ', 649, 44, 1),
(5822, '560301', 'หย่วน   ', 650, 44, 1),
(5823, '560302', 'ทุ่งกล้วย*   ', 650, 44, 1),
(5824, '560303', 'สบบง*   ', 650, 44, 1),
(5825, '560304', 'เชียงแรง*   ', 650, 44, 1),
(5826, '560305', 'ภูซาง*   ', 650, 44, 1),
(5827, '560306', 'น้ำแวน   ', 650, 44, 1),
(5828, '560307', 'เวียง   ', 650, 44, 1),
(5829, '560308', 'ฝายกวาง   ', 650, 44, 1),
(5830, '560309', 'เจดีย์คำ   ', 650, 44, 1),
(5831, '560310', 'ร่มเย็น   ', 650, 44, 1),
(5832, '560311', 'เชียงบาน   ', 650, 44, 1),
(5833, '560312', 'แม่ลาว   ', 650, 44, 1),
(5834, '560313', 'อ่างทอง   ', 650, 44, 1),
(5835, '560314', 'ทุ่งผาสุข   ', 650, 44, 1),
(5836, '560315', 'ป่าสัก*   ', 650, 44, 1),
(5837, '560401', 'เชียงม่วน   ', 651, 44, 1),
(5838, '560402', 'บ้านมาง   ', 651, 44, 1),
(5839, '560403', 'สระ   ', 651, 44, 1),
(5840, '560501', 'ดอกคำใต้   ', 652, 44, 1),
(5841, '560502', 'ดอนศรีชุม   ', 652, 44, 1),
(5842, '560503', 'บ้านถ้ำ   ', 652, 44, 1),
(5843, '560504', 'บ้านปิน   ', 652, 44, 1),
(5844, '560505', 'ห้วยลาน   ', 652, 44, 1),
(5845, '560506', 'สันโค้ง   ', 652, 44, 1),
(5846, '560507', 'ป่าซาง   ', 652, 44, 1),
(5847, '560508', 'หนองหล่ม   ', 652, 44, 1),
(5848, '560509', 'ดงสุวรรณ   ', 652, 44, 1),
(5849, '560510', 'บุญเกิด   ', 652, 44, 1),
(5850, '560511', 'สว่างอารมณ์   ', 652, 44, 1),
(5851, '560512', 'คือเวียง   ', 652, 44, 1),
(5852, '560601', 'ปง   ', 653, 44, 1),
(5853, '560602', 'ควร   ', 653, 44, 1),
(5854, '560603', 'ออย   ', 653, 44, 1),
(5855, '560604', 'งิม   ', 653, 44, 1),
(5856, '560605', 'ผาช้างน้อย   ', 653, 44, 1),
(5857, '560606', 'นาปรัง   ', 653, 44, 1),
(5858, '560607', 'ขุนควร   ', 653, 44, 1),
(5859, '560701', 'แม่ใจ   ', 654, 44, 1),
(5860, '560702', 'ศรีถ้อย   ', 654, 44, 1),
(5861, '560703', 'แม่สุก   ', 654, 44, 1),
(5862, '560704', 'ป่าแฝก   ', 654, 44, 1),
(5863, '560705', 'บ้านเหล่า   ', 654, 44, 1),
(5864, '560706', 'เจริญราษฎร์   ', 654, 44, 1),
(5865, '560801', 'ภูซาง   ', 655, 44, 1),
(5866, '560802', 'ป่าสัก   ', 655, 44, 1),
(5867, '560803', 'ทุ่งกล้วย   ', 655, 44, 1),
(5868, '560804', 'เชียงแรง   ', 655, 44, 1),
(5869, '560805', 'สบบง   ', 655, 44, 1),
(5870, '560901', 'ห้วยแก้ว   ', 656, 44, 1),
(5871, '560902', 'ดงเจน   ', 656, 44, 1),
(5872, '560903', 'แม่อิง   ', 656, 44, 1),
(5873, '570101', 'เวียง   ', 657, 45, 1),
(5874, '570102', 'รอบเวียง   ', 657, 45, 1),
(5875, '570103', 'บ้านดู่   ', 657, 45, 1),
(5876, '570104', 'นางแล   ', 657, 45, 1),
(5877, '570105', 'แม่ข้าวต้ม   ', 657, 45, 1),
(5878, '570106', 'แม่ยาว   ', 657, 45, 1),
(5879, '570107', 'สันทราย   ', 657, 45, 1),
(5880, '570108', '*บัวสลี   ', 657, 45, 1),
(5881, '570109', '*ดงมะดะ   ', 657, 45, 1),
(5882, '570110', '*ป่าก่อดำ   ', 657, 45, 1),
(5883, '570111', 'แม่กรณ์   ', 657, 45, 1),
(5884, '570112', 'ห้วยชมภู   ', 657, 45, 1),
(5885, '570113', 'ห้วยสัก   ', 657, 45, 1),
(5886, '570114', 'ริมกก   ', 657, 45, 1),
(5887, '570115', 'ดอยลาน   ', 657, 45, 1),
(5888, '570116', 'ป่าอ้อดอนชัย   ', 657, 45, 1),
(5889, '570117', '*จอมหมอกแก้ว   ', 657, 45, 1),
(5890, '570118', 'ท่าสาย   ', 657, 45, 1),
(5891, '570119', '*โป่งแพร่   ', 657, 45, 1),
(5892, '570120', 'ดอยฮาง   ', 657, 45, 1),
(5893, '570121', 'ท่าสุด   ', 657, 45, 1),
(5894, '570192', '*ทุ่งก่อ   ', 657, 45, 1),
(5895, '570193', '*ป่าก่อดำ   ', 657, 45, 1),
(5896, '570194', '*ดงมะดะ   ', 657, 45, 1),
(5897, '570195', '*บัวสลี   ', 657, 45, 1),
(5898, '570196', '*เวียงเหนือ   ', 657, 45, 1),
(5899, '570197', '*ผางาม   ', 657, 45, 1),
(5900, '570198', '*เวียงชัย   ', 657, 45, 1),
(5901, '570199', '*ทุ่งก่อ   ', 657, 45, 1),
(5902, '570201', '*ทุ่งก่อ   ', 658, 45, 1),
(5903, '570202', 'เวียงชัย   ', 658, 45, 1),
(5904, '570203', 'ผางาม   ', 658, 45, 1),
(5905, '570204', 'เวียงเหนือ   ', 658, 45, 1),
(5906, '570205', '*ป่าซาง   ', 658, 45, 1),
(5907, '570206', 'ดอนศิลา   ', 658, 45, 1),
(5908, '570207', '*ดงมหาวัน   ', 658, 45, 1),
(5909, '570208', 'เมืองชุม   ', 658, 45, 1),
(5910, '570301', 'เวียง   ', 659, 45, 1),
(5911, '570302', 'สถาน   ', 659, 45, 1),
(5912, '570303', 'ครึ่ง   ', 659, 45, 1),
(5913, '570304', 'บุญเรือง   ', 659, 45, 1),
(5914, '570305', 'ห้วยซ้อ   ', 659, 45, 1),
(5915, '570306', '*ม่วงยาย   ', 659, 45, 1),
(5916, '570307', '*ปอ   ', 659, 45, 1),
(5917, '570308', 'ศรีดอนชัย   ', 659, 45, 1),
(5918, '570309', '*หล่ายงาว   ', 659, 45, 1),
(5919, '570310', 'ริมโขง   ', 659, 45, 1),
(5920, '570398', '*ปอ   ', 659, 45, 1),
(5921, '570399', '*ม่วงยาย   ', 659, 45, 1),
(5922, '570401', 'เวียง   ', 660, 45, 1),
(5923, '570402', 'งิ้ว   ', 660, 45, 1),
(5924, '570403', 'ปล้อง   ', 660, 45, 1),
(5925, '570404', 'แม่ลอย   ', 660, 45, 1),
(5926, '570405', 'เชียงเคี่ยน   ', 660, 45, 1),
(5927, '570406', '*ตต้า   ', 660, 45, 1),
(5928, '570407', '*ปป่าตาล   ', 660, 45, 1),
(5929, '570408', '*ยยางฮอม   ', 660, 45, 1),
(5930, '570409', 'ตับเต่า   ', 660, 45, 1),
(5931, '570410', 'หงาว   ', 660, 45, 1),
(5932, '570411', 'สันทรายงาม   ', 660, 45, 1),
(5933, '570412', 'ศรีดอนไชย   ', 660, 45, 1),
(5934, '570413', 'หนองแรด   ', 660, 45, 1),
(5935, '570495', '*แม่ลอย   ', 660, 45, 1);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(5936, '570496', '*ต้า   ', 660, 45, 1),
(5937, '570497', 'ยางฮอม*   ', 660, 45, 1),
(5938, '570498', '*แม่เปา   ', 660, 45, 1),
(5939, '570499', '*ป่าตาล   ', 660, 45, 1),
(5940, '570501', 'สันมะเค็ด   ', 661, 45, 1),
(5941, '570502', 'แม่อ้อ   ', 661, 45, 1),
(5942, '570503', 'ธารทอง   ', 661, 45, 1),
(5943, '570504', 'สันติสุข   ', 661, 45, 1),
(5944, '570505', 'ดอยงาม   ', 661, 45, 1),
(5945, '570506', 'หัวง้ม   ', 661, 45, 1),
(5946, '570507', 'เจริญเมือง   ', 661, 45, 1),
(5947, '570508', 'ป่าหุ่ง   ', 661, 45, 1),
(5948, '570509', 'ม่วงคำ   ', 661, 45, 1),
(5949, '570510', 'ทรายขาว   ', 661, 45, 1),
(5950, '570511', 'สันกลาง   ', 661, 45, 1),
(5951, '570512', 'แม่เย็น   ', 661, 45, 1),
(5952, '570513', 'เมืองพาน   ', 661, 45, 1),
(5953, '570514', 'ทานตะวัน   ', 661, 45, 1),
(5954, '570515', 'เวียงห้าว   ', 661, 45, 1),
(5955, '570597', '*ป่าแงะ   ', 661, 45, 1),
(5956, '570598', '*สันมะค่า   ', 661, 45, 1),
(5957, '570599', '*ป่าแดด   ', 661, 45, 1),
(5958, '570601', 'ป่าแดด   ', 662, 45, 1),
(5959, '570602', 'ป่าแงะ   ', 662, 45, 1),
(5960, '570603', 'สันมะค่า   ', 662, 45, 1),
(5961, '570605', 'โรงช้าง   ', 662, 45, 1),
(5962, '570606', 'ศรีโพธิ์เงิน   ', 662, 45, 1),
(5963, '570701', 'แม่จัน   ', 663, 45, 1),
(5964, '570702', 'จันจว้า   ', 663, 45, 1),
(5965, '570703', 'แม่คำ   ', 663, 45, 1),
(5966, '570704', 'ป่าซาง   ', 663, 45, 1),
(5967, '570705', 'สันทราย   ', 663, 45, 1),
(5968, '570706', 'ท่าข้าวเปลือก   ', 663, 45, 1),
(5969, '570707', 'ปงน้อย*   ', 663, 45, 1),
(5970, '570708', 'ป่าตึง   ', 663, 45, 1),
(5971, '570709', 'หนองป่าก่อ*   ', 663, 45, 1),
(5972, '570710', 'แม่ไร่   ', 663, 45, 1),
(5973, '570711', 'ศรีค้ำ   ', 663, 45, 1),
(5974, '570712', 'จันจว้าใต้   ', 663, 45, 1),
(5975, '570713', 'จอมสวรรค์   ', 663, 45, 1),
(5976, '570714', '*เเทอดไทย   ', 663, 45, 1),
(5977, '570715', '*แแม่สลองใน   ', 663, 45, 1),
(5978, '570716', '*แม่สลองนอก   ', 663, 45, 1),
(5979, '570717', 'โชคชัย*   ', 663, 45, 1),
(5980, '570801', 'เวียง   ', 664, 45, 1),
(5981, '570802', 'ป่าสัก   ', 664, 45, 1),
(5982, '570803', 'บ้านแซว   ', 664, 45, 1),
(5983, '570804', 'ศรีดอนมูล   ', 664, 45, 1),
(5984, '570805', 'แม่เงิน   ', 664, 45, 1),
(5985, '570806', 'โยนก   ', 664, 45, 1),
(5986, '570901', 'แม่สาย   ', 665, 45, 1),
(5987, '570902', 'ห้วยไคร้   ', 665, 45, 1),
(5988, '570903', 'เกาะช้าง   ', 665, 45, 1),
(5989, '570904', 'โป่งผา   ', 665, 45, 1),
(5990, '570905', 'ศรีเมืองชุม   ', 665, 45, 1),
(5991, '570906', 'เวียงพางคำ   ', 665, 45, 1),
(5992, '570908', 'บ้านด้าย   ', 665, 45, 1),
(5993, '570909', 'โป่งงาม   ', 665, 45, 1),
(5994, '571001', 'แม่สรวย   ', 666, 45, 1),
(5995, '571002', 'ป่าแดด   ', 666, 45, 1),
(5996, '571003', 'แม่พริก   ', 666, 45, 1),
(5997, '571004', 'ศรีถ้อย   ', 666, 45, 1),
(5998, '571005', 'ท่าก๊อ   ', 666, 45, 1),
(5999, '571006', 'วาวี   ', 666, 45, 1),
(6000, '571007', 'เจดีย์หลวง   ', 666, 45, 1),
(6001, '571101', 'สันสลี   ', 667, 45, 1),
(6002, '571102', 'เวียง   ', 667, 45, 1),
(6003, '571103', 'บ้านโป่ง   ', 667, 45, 1),
(6004, '571104', 'ป่างิ้ว   ', 667, 45, 1),
(6005, '571105', 'เวียงกาหลง   ', 667, 45, 1),
(6006, '571106', 'แม่เจดีย์   ', 667, 45, 1),
(6007, '571107', 'แม่เจดีย์ใหม่   ', 667, 45, 1),
(6008, '571108', 'เวียงกาหลง*   ', 667, 45, 1),
(6009, '571201', 'แม่เปา   ', 668, 45, 1),
(6010, '571202', 'แม่ต๋ำ   ', 668, 45, 1),
(6011, '571203', 'ไม้ยา   ', 668, 45, 1),
(6012, '571204', 'เม็งราย   ', 668, 45, 1),
(6013, '571205', 'ตาดควัน   ', 668, 45, 1),
(6014, '571301', 'ม่วงยาย   ', 669, 45, 1),
(6015, '571302', 'ปอ   ', 669, 45, 1),
(6016, '571303', 'หล่ายงาว   ', 669, 45, 1),
(6017, '571304', 'ท่าข้าม   ', 669, 45, 1),
(6018, '571401', 'ต้า   ', 670, 45, 1),
(6019, '571402', 'ป่าตาล   ', 670, 45, 1),
(6020, '571403', 'ยางฮอม   ', 670, 45, 1),
(6021, '571501', 'เทอดไทย   ', 671, 45, 1),
(6022, '571502', 'แม่สลองใน   ', 671, 45, 1),
(6023, '571503', 'แม่สลองนอก   ', 671, 45, 1),
(6024, '571504', 'แม่ฟ้าหลวง   ', 671, 45, 1),
(6025, '571601', 'ดงมะดะ   ', 672, 45, 1),
(6026, '571602', 'จอมหมอกแก้ว   ', 672, 45, 1),
(6027, '571603', 'บัวสลี   ', 672, 45, 1),
(6028, '571604', 'ป่าก่อดำ   ', 672, 45, 1),
(6029, '571605', 'โป่งแพร่   ', 672, 45, 1),
(6030, '571701', 'ทุ่งก่อ   ', 673, 45, 1),
(6031, '571702', 'ดงมหาวัน   ', 673, 45, 1),
(6032, '571703', 'ป่าซาง   ', 673, 45, 1),
(6033, '571801', 'ปงน้อย   ', 674, 45, 1),
(6034, '571802', 'โชคชัย   ', 674, 45, 1),
(6035, '571803', 'หนองป่าก่อ   ', 674, 45, 1),
(6036, '580101', 'จองคำ   ', 675, 46, 1),
(6037, '580102', 'ห้วยโป่ง   ', 675, 46, 1),
(6038, '580103', 'ผาบ่อง   ', 675, 46, 1),
(6039, '580104', 'ปางหมู   ', 675, 46, 1),
(6040, '580105', 'หมอกจำแป่   ', 675, 46, 1),
(6041, '580106', 'ห้วยผา   ', 675, 46, 1),
(6042, '580107', '*ปางมะผ้า   ', 675, 46, 1),
(6043, '580108', '*สบป่อง   ', 675, 46, 1),
(6044, '580109', 'ห้วยปูลิง   ', 675, 46, 1),
(6045, '580201', 'ขุนยวม   ', 676, 46, 1),
(6046, '580202', 'แม่เงา   ', 676, 46, 1),
(6047, '580203', 'เมืองปอน   ', 676, 46, 1),
(6048, '580204', 'แม่ยวมน้อย   ', 676, 46, 1),
(6049, '580205', 'แม่กิ๊   ', 676, 46, 1),
(6050, '580206', 'แม่อูคอ   ', 676, 46, 1),
(6051, '580301', 'เวียงใต้   ', 677, 46, 1),
(6052, '580302', 'เวียงเหนือ   ', 677, 46, 1),
(6053, '580303', 'แม่นาเติง   ', 677, 46, 1),
(6054, '580304', 'แม่ฮี้   ', 677, 46, 1),
(6055, '580305', 'ทุ่งยาว   ', 677, 46, 1),
(6056, '580306', 'เมืองแปง   ', 677, 46, 1),
(6057, '580307', 'โป่งสา   ', 677, 46, 1),
(6058, '580401', 'บ้านกาศ   ', 678, 46, 1),
(6059, '580402', 'แม่สะเรียง   ', 678, 46, 1),
(6060, '580403', 'แม่คง   ', 678, 46, 1),
(6061, '580404', 'แม่เหาะ   ', 678, 46, 1),
(6062, '580405', 'แม่ยวม   ', 678, 46, 1),
(6063, '580406', 'เสาหิน   ', 678, 46, 1),
(6064, '580408', 'ป่าแป๋   ', 678, 46, 1),
(6065, '580497', '*กองกอย   ', 678, 46, 1),
(6066, '580498', '*แม่คะตวน   ', 678, 46, 1),
(6067, '580499', '*สบเมย   ', 678, 46, 1),
(6068, '580501', 'แม่ลาน้อย   ', 679, 46, 1),
(6069, '580502', 'แม่ลาหลวง   ', 679, 46, 1),
(6070, '580503', 'ท่าผาปุ้ม   ', 679, 46, 1),
(6071, '580504', 'แม่โถ   ', 679, 46, 1),
(6072, '580505', 'ห้วยห้อม   ', 679, 46, 1),
(6073, '580506', 'แม่นาจาง   ', 679, 46, 1),
(6074, '580507', 'สันติคีรี   ', 679, 46, 1),
(6075, '580508', 'ขุนแม่ลาน้อย   ', 679, 46, 1),
(6076, '580601', 'สบเมย   ', 680, 46, 1),
(6077, '580602', 'แม่คะตวน   ', 680, 46, 1),
(6078, '580603', 'กองก๋อย   ', 680, 46, 1),
(6079, '580604', 'แม่สวด   ', 680, 46, 1),
(6080, '580605', 'ป่าโปง   ', 680, 46, 1),
(6081, '580606', 'แม่สามแลบ   ', 680, 46, 1),
(6082, '580701', 'สบป่อง   ', 681, 46, 1),
(6083, '580702', 'ปางมะผ้า   ', 681, 46, 1),
(6084, '580703', 'ถ้ำลอด   ', 681, 46, 1),
(6085, '580704', 'นาปู่ป้อม   ', 681, 46, 1),
(6086, '600101', 'ปากน้ำโพ   ', 683, 47, 2),
(6087, '600102', 'กลางแดด   ', 683, 47, 2),
(6088, '600103', 'เกรียงไกร   ', 683, 47, 2),
(6089, '600104', 'แควใหญ่   ', 683, 47, 2),
(6090, '600105', 'ตะเคียนเลื่อน   ', 683, 47, 2),
(6091, '600106', 'นครสวรรค์ตก   ', 683, 47, 2),
(6092, '600107', 'นครสวรรค์ออก   ', 683, 47, 2),
(6093, '600108', 'บางพระหลวง   ', 683, 47, 2),
(6094, '600109', 'บางม่วง   ', 683, 47, 2),
(6095, '600110', 'บ้านมะเกลือ   ', 683, 47, 2),
(6096, '600111', 'บ้านแก่ง   ', 683, 47, 2),
(6097, '600112', 'พระนอน   ', 683, 47, 2),
(6098, '600113', 'วัดไทร   ', 683, 47, 2),
(6099, '600114', 'หนองกรด   ', 683, 47, 2),
(6100, '600115', 'หนองกระโดน   ', 683, 47, 2),
(6101, '600116', 'หนองปลิง   ', 683, 47, 2),
(6102, '600117', 'บึงเสนาท   ', 683, 47, 2),
(6103, '600201', 'โกรกพระ   ', 684, 47, 2),
(6104, '600202', 'ยางตาล   ', 684, 47, 2),
(6105, '600203', 'บางมะฝ่อ   ', 684, 47, 2),
(6106, '600204', 'บางประมุง   ', 684, 47, 2),
(6107, '600205', 'นากลาง   ', 684, 47, 2),
(6108, '600206', 'ศาลาแดง   ', 684, 47, 2),
(6109, '600207', 'เนินกว้าว   ', 684, 47, 2),
(6110, '600208', 'เนินศาลา   ', 684, 47, 2),
(6111, '600209', 'หาดสูง   ', 684, 47, 2),
(6112, '600301', 'ชุมแสง   ', 685, 47, 2),
(6113, '600302', 'ทับกฤช   ', 685, 47, 2),
(6114, '600303', 'พิกุล   ', 685, 47, 2),
(6115, '600304', 'เกยไชย   ', 685, 47, 2),
(6116, '600305', 'ท่าไม้   ', 685, 47, 2),
(6117, '600306', 'บางเคียน   ', 685, 47, 2),
(6118, '600307', 'หนองกระเจา   ', 685, 47, 2),
(6119, '600308', 'พันลาน   ', 685, 47, 2),
(6120, '600309', 'โคกหม้อ   ', 685, 47, 2),
(6121, '600310', 'ไผ่สิงห์   ', 685, 47, 2),
(6122, '600311', 'ฆะมัง   ', 685, 47, 2),
(6123, '600312', 'ทับกฤชใต้   ', 685, 47, 2),
(6124, '600401', 'หนองบัว   ', 686, 47, 2),
(6125, '600402', 'หนองกลับ   ', 686, 47, 2),
(6126, '600403', 'ธารทหาร   ', 686, 47, 2),
(6127, '600404', 'ห้วยร่วม   ', 686, 47, 2),
(6128, '600405', 'ห้วยถั่วใต้   ', 686, 47, 2),
(6129, '600406', 'ห้วยถั่วเหนือ   ', 686, 47, 2),
(6130, '600407', 'ห้วยใหญ่   ', 686, 47, 2),
(6131, '600408', 'ทุ่งทอง   ', 686, 47, 2),
(6132, '600409', 'วังบ่อ   ', 686, 47, 2),
(6133, '600501', 'ท่างิ้ว   ', 687, 47, 2),
(6134, '600502', 'บางตาหงาย   ', 687, 47, 2),
(6135, '600503', 'หูกวาง   ', 687, 47, 2),
(6136, '600504', 'อ่างทอง   ', 687, 47, 2),
(6137, '600505', 'บ้านแดน   ', 687, 47, 2),
(6138, '600506', 'บางแก้ว   ', 687, 47, 2),
(6139, '600507', 'ตาขีด   ', 687, 47, 2),
(6140, '600508', 'ตาสัง   ', 687, 47, 2),
(6141, '600509', 'ด่านช้าง   ', 687, 47, 2),
(6142, '600510', 'หนองกรด   ', 687, 47, 2),
(6143, '600511', 'หนองตางู   ', 687, 47, 2),
(6144, '600512', 'บึงปลาทู   ', 687, 47, 2),
(6145, '600513', 'เจริญผล   ', 687, 47, 2),
(6146, '600601', 'มหาโพธิ   ', 688, 47, 2),
(6147, '600602', 'เก้าเลี้ยว   ', 688, 47, 2),
(6148, '600603', 'หนองเต่า   ', 688, 47, 2),
(6149, '600604', 'เขาดิน   ', 688, 47, 2),
(6150, '600605', 'หัวดง   ', 688, 47, 2),
(6151, '600701', 'ตาคลี   ', 689, 47, 2),
(6152, '600702', 'ช่องแค   ', 689, 47, 2),
(6153, '600703', 'จันเสน   ', 689, 47, 2),
(6154, '600704', 'ห้วยหอม   ', 689, 47, 2),
(6155, '600705', 'หัวหวาย   ', 689, 47, 2),
(6156, '600706', 'หนองโพ   ', 689, 47, 2),
(6157, '600707', 'หนองหม้อ   ', 689, 47, 2),
(6158, '600708', 'สร้อยทอง   ', 689, 47, 2),
(6159, '600709', 'ลาดทิพรส   ', 689, 47, 2),
(6160, '600710', 'พรหมนิมิต   ', 689, 47, 2),
(6161, '600801', 'ท่าตะโก   ', 690, 47, 2),
(6162, '600802', 'พนมรอก   ', 690, 47, 2),
(6163, '600803', 'หัวถนน   ', 690, 47, 2),
(6164, '600804', 'สายลำโพง   ', 690, 47, 2),
(6165, '600805', 'วังมหากร   ', 690, 47, 2),
(6166, '600806', 'ดอนคา   ', 690, 47, 2),
(6167, '600807', 'ทำนบ   ', 690, 47, 2),
(6168, '600808', 'วังใหญ่   ', 690, 47, 2),
(6169, '600809', 'พนมเศษ   ', 690, 47, 2),
(6170, '600810', 'หนองหลวง   ', 690, 47, 2),
(6171, '600901', 'โคกเดื่อ   ', 691, 47, 2),
(6172, '600902', 'สำโรงชัย   ', 691, 47, 2),
(6173, '600903', 'วังน้ำลัด   ', 691, 47, 2),
(6174, '600904', 'ตะคร้อ   ', 691, 47, 2),
(6175, '600905', 'โพธิ์ประสาท   ', 691, 47, 2),
(6176, '600906', 'วังข่อย   ', 691, 47, 2),
(6177, '600907', 'นาขอม   ', 691, 47, 2),
(6178, '600908', 'ไพศาลี   ', 691, 47, 2),
(6179, '601001', 'พยุหะ   ', 692, 47, 2),
(6180, '601002', 'เนินมะกอก   ', 692, 47, 2),
(6181, '601003', 'นิคมเขาบ่อแก้ว   ', 692, 47, 2),
(6182, '601004', 'ม่วงหัก   ', 692, 47, 2),
(6183, '601005', 'ยางขาว   ', 692, 47, 2),
(6184, '601006', 'ย่านมัทรี   ', 692, 47, 2),
(6185, '601007', 'เขาทอง   ', 692, 47, 2),
(6186, '601008', 'ท่าน้ำอ้อย   ', 692, 47, 2),
(6187, '601009', 'น้ำทรง   ', 692, 47, 2),
(6188, '601010', 'เขากะลา   ', 692, 47, 2),
(6189, '601011', 'สระทะเล   ', 692, 47, 2),
(6190, '601101', 'ลาดยาว   ', 693, 47, 2),
(6191, '601102', 'ห้วยน้ำหอม   ', 693, 47, 2),
(6192, '601103', 'วังม้า   ', 693, 47, 2),
(6193, '601104', 'วังเมือง   ', 693, 47, 2),
(6194, '601105', 'สร้อยละคร   ', 693, 47, 2),
(6195, '601106', 'มาบแก   ', 693, 47, 2),
(6196, '601107', 'หนองยาว   ', 693, 47, 2),
(6197, '601108', 'หนองนมวัว   ', 693, 47, 2),
(6198, '601109', 'บ้านไร่   ', 693, 47, 2),
(6199, '601110', 'เนินขี้เหล็ก   ', 693, 47, 2),
(6200, '601111', '*แแม่เล่ย์   ', 693, 47, 2),
(6201, '601112', '*แแม่วงก์   ', 693, 47, 2),
(6202, '601113', '*ววังซ่าน   ', 693, 47, 2),
(6203, '601114', '*เเขาชนกัน   ', 693, 47, 2),
(6204, '601115', '*ปปางสวรรค์   ', 693, 47, 2),
(6205, '601116', 'ศาลเจ้าไก่ต่อ   ', 693, 47, 2),
(6206, '601117', 'สระแก้ว   ', 693, 47, 2),
(6207, '601201', 'ตากฟ้า   ', 694, 47, 2),
(6208, '601202', 'ลำพยนต์   ', 694, 47, 2),
(6209, '601203', 'สุขสำราญ   ', 694, 47, 2),
(6210, '601204', 'หนองพิกุล   ', 694, 47, 2),
(6211, '601205', 'พุนกยูง   ', 694, 47, 2),
(6212, '601206', 'อุดมธัญญา   ', 694, 47, 2),
(6213, '601207', 'เขาชายธง   ', 694, 47, 2),
(6214, '601301', 'แม่วงก์   ', 695, 47, 2),
(6215, '601302', 'ห้วยน้ำหอม*   ', 695, 47, 2),
(6216, '601303', 'แม่เล่ย์   ', 695, 47, 2),
(6217, '601304', 'วังซ่าน   ', 695, 47, 2),
(6218, '601305', 'เขาชนกัน   ', 695, 47, 2),
(6219, '601306', 'ปางสวรรค์*   ', 695, 47, 2),
(6220, '601307', 'แม่เปิน*   ', 695, 47, 2),
(6221, '601308', 'ชุมตาบง*   ', 695, 47, 2),
(6222, '601401', 'แม่เปิน   ', 696, 47, 2),
(6223, '601501', 'ชุมตาบง   ', 697, 47, 2),
(6224, '601502', 'ปางสวรรค์   ', 697, 47, 2),
(6225, '610101', 'อุทัยใหม่   ', 701, 48, 2),
(6226, '610102', 'น้ำซึม   ', 701, 48, 2),
(6227, '610103', 'สะแกกรัง   ', 701, 48, 2),
(6228, '610104', 'ดอนขวาง   ', 701, 48, 2),
(6229, '610105', 'หาดทนง   ', 701, 48, 2),
(6230, '610106', 'เกาะเทโพ   ', 701, 48, 2),
(6231, '610107', 'ท่าซุง   ', 701, 48, 2),
(6232, '610108', 'หนองแก   ', 701, 48, 2),
(6233, '610109', 'โนนเหล็ก   ', 701, 48, 2),
(6234, '610110', 'หนองเต่า   ', 701, 48, 2),
(6235, '610111', 'หนองไผ่แบน   ', 701, 48, 2),
(6236, '610112', 'หนองพังค่า   ', 701, 48, 2),
(6237, '610113', 'ทุ่งใหญ่   ', 701, 48, 2),
(6238, '610114', 'เนินแจง   ', 701, 48, 2),
(6239, '610199', '*ข้าวเม่า   ', 701, 48, 2),
(6240, '610201', 'ทัพทัน   ', 702, 48, 2),
(6241, '610202', 'ทุ่งนาไทย   ', 702, 48, 2),
(6242, '610203', 'เขาขี้ฝอย   ', 702, 48, 2),
(6243, '610204', 'หนองหญ้าปล้อง   ', 702, 48, 2),
(6244, '610205', 'โคกหม้อ   ', 702, 48, 2),
(6245, '610206', 'หนองยายดา   ', 702, 48, 2),
(6246, '610207', 'หนองกลางดง   ', 702, 48, 2),
(6247, '610208', 'หนองกระทุ่ม   ', 702, 48, 2),
(6248, '610209', 'หนองสระ   ', 702, 48, 2),
(6249, '610210', 'ตลุกดู่   ', 702, 48, 2),
(6250, '610301', 'สว่างอารมณ์   ', 703, 48, 2),
(6251, '610302', 'หนองหลวง   ', 703, 48, 2),
(6252, '610303', 'พลวงสองนาง   ', 703, 48, 2),
(6253, '610304', 'ไผ่เขียว   ', 703, 48, 2),
(6254, '610305', 'บ่อยาง   ', 703, 48, 2),
(6255, '610401', 'หนองฉาง   ', 704, 48, 2),
(6256, '610402', 'หนองยาง   ', 704, 48, 2),
(6257, '610403', 'หนองนางนวล   ', 704, 48, 2),
(6258, '610404', 'หนองสรวง   ', 704, 48, 2),
(6259, '610405', 'บ้านเก่า   ', 704, 48, 2),
(6260, '610406', 'อุทัยเก่า   ', 704, 48, 2),
(6261, '610407', 'ทุ่งโพ   ', 704, 48, 2),
(6262, '610408', 'ทุ่งพง   ', 704, 48, 2),
(6263, '610409', 'เขาบางแกรก   ', 704, 48, 2),
(6264, '610410', 'เขากวางทอง   ', 704, 48, 2),
(6265, '610501', 'หนองขาหย่าง   ', 705, 48, 2),
(6266, '610502', 'หนองไผ่   ', 705, 48, 2),
(6267, '610503', 'ดอนกลอย   ', 705, 48, 2),
(6268, '610504', 'ห้วยรอบ   ', 705, 48, 2),
(6269, '610505', 'ทุ่งพึ่ง   ', 705, 48, 2),
(6270, '610506', 'ท่าโพ   ', 705, 48, 2),
(6271, '610507', 'หมกแถว   ', 705, 48, 2),
(6272, '610508', 'หลุมเข้า   ', 705, 48, 2),
(6273, '610509', 'ดงขวาง   ', 705, 48, 2),
(6274, '610601', 'บ้านไร่   ', 706, 48, 2),
(6275, '610602', 'ทัพหลวง   ', 706, 48, 2),
(6276, '610603', 'ห้วยแห้ง   ', 706, 48, 2),
(6277, '610604', 'คอกควาย   ', 706, 48, 2),
(6278, '610605', 'วังหิน   ', 706, 48, 2),
(6279, '610606', 'เมืองการุ้ง   ', 706, 48, 2),
(6280, '610607', 'แก่นมะกรูด   ', 706, 48, 2),
(6281, '610609', 'หนองจอก   ', 706, 48, 2),
(6282, '610610', 'หูช้าง   ', 706, 48, 2),
(6283, '610611', 'บ้านบึง   ', 706, 48, 2),
(6284, '610612', 'บ้านใหม่คลองเคียน   ', 706, 48, 2),
(6285, '610613', 'หนองบ่มกล้วย   ', 706, 48, 2),
(6286, '610614', 'เจ้าวัด   ', 706, 48, 2),
(6287, '610695', '*ห้วยคต   ', 706, 48, 2),
(6288, '610696', '*สุขฤทัย   ', 706, 48, 2),
(6289, '610697', '*ป่าอ้อ   ', 706, 48, 2),
(6290, '610698', '*ประดู่ยืน   ', 706, 48, 2),
(6291, '610699', '*ลานสัก   ', 706, 48, 2),
(6292, '610701', 'ลานสัก   ', 707, 48, 2),
(6293, '610702', 'ประดู่ยืน   ', 707, 48, 2),
(6294, '610703', 'ป่าอ้อ   ', 707, 48, 2),
(6295, '610704', 'ระบำ   ', 707, 48, 2),
(6296, '610705', 'น้ำรอบ   ', 707, 48, 2),
(6297, '610706', 'ทุ่งนางาม   ', 707, 48, 2),
(6298, '610801', 'สุขฤทัย   ', 708, 48, 2),
(6299, '610802', 'ทองหลาง   ', 708, 48, 2),
(6300, '610803', 'ห้วยคต   ', 708, 48, 2),
(6301, '620101', 'ในเมือง   ', 709, 49, 2),
(6302, '620102', 'ไตรตรึงษ์   ', 709, 49, 2),
(6303, '620103', 'อ่างทอง   ', 709, 49, 2),
(6304, '620104', 'นาบ่อคำ   ', 709, 49, 2),
(6305, '620105', 'นครชุม   ', 709, 49, 2),
(6306, '620106', 'ทรงธรรม   ', 709, 49, 2),
(6307, '620107', 'ลานดอกไม้   ', 709, 49, 2),
(6308, '620108', 'ลานดอกไม้ตก*   ', 709, 49, 2),
(6309, '620109', 'โกสัมพี*   ', 709, 49, 2),
(6310, '620110', 'หนองปลิง   ', 709, 49, 2),
(6311, '620111', 'คณฑี   ', 709, 49, 2),
(6312, '620112', 'นิคมทุ่งโพธิ์ทะเล   ', 709, 49, 2),
(6313, '620113', 'เทพนคร   ', 709, 49, 2),
(6314, '620114', 'วังทอง   ', 709, 49, 2),
(6315, '620115', 'ท่าขุนราม   ', 709, 49, 2),
(6316, '620116', 'เพชรชมภู*   ', 709, 49, 2),
(6317, '620117', 'คลองแม่ลาย   ', 709, 49, 2),
(6318, '620118', 'ธำมรงค์   ', 709, 49, 2),
(6319, '620119', 'สระแก้ว   ', 709, 49, 2),
(6320, '620197', '*หนองคล้า   ', 709, 49, 2),
(6321, '620198', '*โป่งน้ำร้อน   ', 709, 49, 2),
(6322, '620199', '*ไทรงาม   ', 709, 49, 2),
(6323, '620201', 'ไทรงาม   ', 710, 49, 2),
(6324, '620202', 'หนองคล้า   ', 710, 49, 2),
(6325, '620203', 'หนองทอง   ', 710, 49, 2),
(6326, '620204', 'หนองไม้กอง   ', 710, 49, 2),
(6327, '620205', 'มหาชัย   ', 710, 49, 2),
(6328, '620206', 'พานทอง   ', 710, 49, 2),
(6329, '620207', 'หนองแม่แตง   ', 710, 49, 2),
(6330, '620301', 'คลองน้ำไหล   ', 711, 49, 2),
(6331, '620302', 'โป่งน้ำร้อน   ', 711, 49, 2),
(6332, '620303', 'คลองลานพัฒนา   ', 711, 49, 2),
(6333, '620304', 'สักงาม   ', 711, 49, 2),
(6334, '620401', '*วังชะโอน   ', 712, 49, 2),
(6335, '620402', '*ระหาน   ', 712, 49, 2),
(6336, '620403', 'ยางสูง   ', 712, 49, 2),
(6337, '620404', 'ป่าพุทรา   ', 712, 49, 2),
(6338, '620405', 'แสนตอ   ', 712, 49, 2),
(6339, '620406', 'สลกบาตร   ', 712, 49, 2),
(6340, '620407', 'บ่อถ้ำ   ', 712, 49, 2),
(6341, '620408', 'ดอนแตง   ', 712, 49, 2),
(6342, '620409', 'วังชะพลู   ', 712, 49, 2),
(6343, '620410', 'โค้งไผ่   ', 712, 49, 2),
(6344, '620411', 'ปางมะค่า   ', 712, 49, 2),
(6345, '620412', 'วังหามแห   ', 712, 49, 2),
(6346, '620413', 'เกาะตาล   ', 712, 49, 2),
(6347, '620414', '*บึงสามัคคี   ', 712, 49, 2),
(6348, '620501', 'คลองขลุง   ', 713, 49, 2),
(6349, '620502', 'ท่ามะเขือ   ', 713, 49, 2),
(6350, '620503', '*ททุ่งทราย   ', 713, 49, 2),
(6351, '620504', 'ท่าพุทรา   ', 713, 49, 2),
(6352, '620505', 'แม่ลาด   ', 713, 49, 2),
(6353, '620506', 'วังยาง   ', 713, 49, 2),
(6354, '620507', 'วังแขม   ', 713, 49, 2),
(6355, '620508', 'หัวถนน   ', 713, 49, 2),
(6356, '620509', 'วังไทร   ', 713, 49, 2),
(6357, '620510', '*โพธิ์ทอง   ', 713, 49, 2),
(6358, '620511', '*ปางตาไว   ', 713, 49, 2),
(6359, '620512', '*ถถาวรวัฒนา   ', 713, 49, 2),
(6360, '620513', 'วังบัว   ', 713, 49, 2),
(6361, '620514', '*ทุ่งทอง   ', 713, 49, 2),
(6362, '620515', '*หินดาต   ', 713, 49, 2),
(6363, '620516', 'คลองสมบูรณ์   ', 713, 49, 2),
(6364, '620599', '*ทุ่งทราย   ', 713, 49, 2),
(6365, '620601', 'พรานกระต่าย   ', 714, 49, 2),
(6366, '620602', 'หนองหัววัว   ', 714, 49, 2),
(6367, '620603', 'ท่าไม้   ', 714, 49, 2),
(6368, '620604', 'วังควง   ', 714, 49, 2),
(6369, '620605', 'วังตะแบก   ', 714, 49, 2),
(6370, '620606', 'เขาคีริส   ', 714, 49, 2),
(6371, '620607', 'คุยบ้านโอง   ', 714, 49, 2),
(6372, '620608', 'คลองพิไกร   ', 714, 49, 2),
(6373, '620609', 'ถ้ำกระต่ายทอง   ', 714, 49, 2),
(6374, '620610', 'ห้วยยั้ง   ', 714, 49, 2),
(6375, '620701', 'ลานกระบือ   ', 715, 49, 2),
(6376, '620702', 'ช่องลม   ', 715, 49, 2),
(6377, '620703', 'หนองหลวง   ', 715, 49, 2),
(6378, '620704', 'โนนพลวง   ', 715, 49, 2),
(6379, '620705', 'ประชาสุขสันต์   ', 715, 49, 2),
(6380, '620706', 'บึงทับแรต   ', 715, 49, 2),
(6381, '620707', 'จันทิมา   ', 715, 49, 2),
(6382, '620801', 'ทุ่งทราย   ', 716, 49, 2),
(6383, '620802', 'ทุ่งทอง   ', 716, 49, 2),
(6384, '620803', 'ถาวรวัฒนา   ', 716, 49, 2),
(6385, '620901', 'โพธิ์ทอง   ', 717, 49, 2),
(6386, '620902', 'หินดาต   ', 717, 49, 2),
(6387, '620903', 'ปางตาไว   ', 717, 49, 2),
(6388, '621001', 'บึงสามัคคี   ', 718, 49, 2),
(6389, '621002', 'วังชะโอน   ', 718, 49, 2),
(6390, '621003', 'ระหาน   ', 718, 49, 2),
(6391, '621004', 'เทพนิมิต   ', 718, 49, 2),
(6392, '621101', 'โกสัมพี   ', 719, 49, 2),
(6393, '621102', 'เพชรชมภู   ', 719, 49, 2),
(6394, '621103', 'ลานดอกไม้ตก   ', 719, 49, 2),
(6395, '630101', 'ระแหง   ', 720, 50, 4),
(6396, '630102', 'หนองหลวง   ', 720, 50, 4),
(6397, '630103', 'เชียงเงิน   ', 720, 50, 4),
(6398, '630104', 'หัวเดียด   ', 720, 50, 4),
(6399, '630105', 'หนองบัวเหนือ   ', 720, 50, 4),
(6400, '630106', 'ไม้งาม   ', 720, 50, 4),
(6401, '630107', 'โป่งแดง   ', 720, 50, 4),
(6402, '630108', 'น้ำรึม   ', 720, 50, 4),
(6403, '630109', 'วังหิน   ', 720, 50, 4),
(6404, '630110', 'เชียงทอง*   ', 720, 50, 4),
(6405, '630111', 'แม่ท้อ   ', 720, 50, 4),
(6406, '630112', 'ป่ามะม่วง   ', 720, 50, 4),
(6407, '630113', 'หนองบัวใต้   ', 720, 50, 4),
(6408, '630114', 'วังประจบ   ', 720, 50, 4),
(6409, '630115', 'ตลุกกลางทุ่ง   ', 720, 50, 4),
(6410, '630116', 'นาโบสถ์*   ', 720, 50, 4),
(6411, '630117', 'ประดาง*   ', 720, 50, 4),
(6412, '630201', 'ตากออก   ', 721, 50, 4),
(6413, '630202', 'สมอโคน   ', 721, 50, 4),
(6414, '630203', 'แม่สลิด   ', 721, 50, 4),
(6415, '630204', 'ตากตก   ', 721, 50, 4),
(6416, '630205', 'เกาะตะเภา   ', 721, 50, 4),
(6417, '630206', 'ทุ่งกระเชาะ   ', 721, 50, 4),
(6418, '630207', 'ท้องฟ้า   ', 721, 50, 4),
(6419, '630301', 'สามเงา   ', 722, 50, 4),
(6420, '630302', 'วังหมัน   ', 722, 50, 4),
(6421, '630303', 'ยกกระบัตร   ', 722, 50, 4),
(6422, '630304', 'ย่านรี   ', 722, 50, 4),
(6423, '630305', 'บ้านนา   ', 722, 50, 4),
(6424, '630306', 'วังจันทร์   ', 722, 50, 4),
(6425, '630401', 'แม่ระมาด   ', 723, 50, 4),
(6426, '630402', 'แม่จะเรา   ', 723, 50, 4),
(6427, '630403', 'ขะเนจื้อ   ', 723, 50, 4),
(6428, '630404', 'แม่ตื่น   ', 723, 50, 4),
(6429, '630405', 'สามหมื่น   ', 723, 50, 4),
(6430, '630406', 'พระธาตุ   ', 723, 50, 4),
(6431, '630501', 'ท่าสองยาง   ', 724, 50, 4),
(6432, '630502', 'แม่ต้าน   ', 724, 50, 4),
(6433, '630503', 'แม่สอง   ', 724, 50, 4),
(6434, '630504', 'แม่หละ   ', 724, 50, 4),
(6435, '630505', 'แม่วะหลวง   ', 724, 50, 4),
(6436, '630506', 'แม่อุสุ   ', 724, 50, 4),
(6437, '630601', 'แม่สอด   ', 725, 50, 4),
(6438, '630602', 'แม่กุ   ', 725, 50, 4),
(6439, '630603', 'พะวอ   ', 725, 50, 4),
(6440, '630604', 'แม่ตาว   ', 725, 50, 4),
(6441, '630605', 'แม่กาษา   ', 725, 50, 4),
(6442, '630606', 'ท่าสายลวด   ', 725, 50, 4),
(6443, '630607', 'แม่ปะ   ', 725, 50, 4),
(6444, '630608', 'มหาวัน   ', 725, 50, 4),
(6445, '630609', 'ด่านแม่ละเมา   ', 725, 50, 4),
(6446, '630610', 'พระธาตุผาแดง   ', 725, 50, 4),
(6447, '630701', 'พบพระ   ', 726, 50, 4),
(6448, '630702', 'ช่องแคบ   ', 726, 50, 4),
(6449, '630703', 'คีรีราษฎร์   ', 726, 50, 4),
(6450, '630704', 'วาเล่ย์   ', 726, 50, 4),
(6451, '630705', 'รวมไทยพัฒนา   ', 726, 50, 4),
(6452, '630801', 'อุ้มผาง   ', 727, 50, 4),
(6453, '630802', 'หนองหลวง   ', 727, 50, 4),
(6454, '630803', 'โมโกร   ', 727, 50, 4),
(6455, '630804', 'แม่จัน   ', 727, 50, 4),
(6456, '630805', 'แม่ละมุ้ง   ', 727, 50, 4),
(6457, '630806', 'แม่กลอง   ', 727, 50, 4),
(6458, '630901', 'เชียงทอง   ', 728, 50, 4),
(6459, '630902', 'นาโบสถ์   ', 728, 50, 4),
(6460, '630903', 'ประดาง   ', 728, 50, 4),
(6461, '640101', 'ธานี   ', 730, 51, 2),
(6462, '640102', 'บ้านสวน   ', 730, 51, 2),
(6463, '640103', 'เมืองเก่า   ', 730, 51, 2),
(6464, '640104', 'ปากแคว   ', 730, 51, 2),
(6465, '640105', 'ยางซ้าย   ', 730, 51, 2),
(6466, '640106', 'บ้านกล้วย   ', 730, 51, 2),
(6467, '640107', 'บ้านหลุม   ', 730, 51, 2),
(6468, '640108', 'ตาลเตี้ย   ', 730, 51, 2),
(6469, '640109', 'ปากพระ   ', 730, 51, 2),
(6470, '640110', 'วังทองแดง   ', 730, 51, 2),
(6471, '640201', 'ลานหอย   ', 731, 51, 2),
(6472, '640202', 'บ้านด่าน   ', 731, 51, 2),
(6473, '640203', 'วังตะคร้อ   ', 731, 51, 2),
(6474, '640204', 'วังน้ำขาว   ', 731, 51, 2),
(6475, '640205', 'ตลิ่งชัน   ', 731, 51, 2),
(6476, '640206', 'หนองหญ้าปล้อง   ', 731, 51, 2),
(6477, '640207', 'วังลึก   ', 731, 51, 2),
(6478, '640301', 'โตนด   ', 732, 51, 2),
(6479, '640302', 'ทุ่งหลวง   ', 732, 51, 2),
(6480, '640303', 'บ้านป้อม   ', 732, 51, 2),
(6481, '640304', 'สามพวง   ', 732, 51, 2),
(6482, '640305', 'ศรีคีรีมาศ   ', 732, 51, 2),
(6483, '640306', 'หนองจิก   ', 732, 51, 2),
(6484, '640307', 'นาเชิงคีรี   ', 732, 51, 2),
(6485, '640308', 'หนองกระดิ่ง   ', 732, 51, 2),
(6486, '640309', 'บ้านน้ำพุ   ', 732, 51, 2),
(6487, '640310', 'ทุ่งยางเมือง   ', 732, 51, 2),
(6488, '640401', 'กง   ', 733, 51, 2),
(6489, '640402', 'บ้านกร่าง   ', 733, 51, 2),
(6490, '640403', 'ไกรนอก   ', 733, 51, 2),
(6491, '640404', 'ไกรกลาง   ', 733, 51, 2),
(6492, '640405', 'ไกรใน   ', 733, 51, 2),
(6493, '640406', 'ดงเดือย   ', 733, 51, 2),
(6494, '640407', 'ป่าแฝก   ', 733, 51, 2),
(6495, '640408', 'กกแรต   ', 733, 51, 2),
(6496, '640409', 'ท่าฉนวน   ', 733, 51, 2),
(6497, '640410', 'หนองตูม   ', 733, 51, 2),
(6498, '640411', 'บ้านใหม่สุขเกษม   ', 733, 51, 2),
(6499, '640501', 'หาดเสี้ยว   ', 734, 51, 2),
(6500, '640502', 'ป่างิ้ว   ', 734, 51, 2),
(6501, '640503', 'แม่สำ   ', 734, 51, 2),
(6502, '640504', 'แม่สิน   ', 734, 51, 2),
(6503, '640505', 'บ้านตึก   ', 734, 51, 2),
(6504, '640506', 'หนองอ้อ   ', 734, 51, 2),
(6505, '640507', 'ท่าชัย   ', 734, 51, 2),
(6506, '640508', 'ศรีสัชนาลัย   ', 734, 51, 2),
(6507, '640509', 'ดงคู่   ', 734, 51, 2),
(6508, '640510', 'บ้านแก่ง   ', 734, 51, 2),
(6509, '640511', 'สารจิตร   ', 734, 51, 2),
(6510, '640601', 'คลองตาล   ', 735, 51, 2),
(6511, '640602', 'วังลึก   ', 735, 51, 2),
(6512, '640603', 'สามเรือน   ', 735, 51, 2),
(6513, '640604', 'บ้านนา   ', 735, 51, 2),
(6514, '640605', 'วังทอง   ', 735, 51, 2),
(6515, '640606', 'นาขุนไกร   ', 735, 51, 2),
(6516, '640607', 'เกาะตาเลี้ยง   ', 735, 51, 2),
(6517, '640608', 'วัดเกาะ   ', 735, 51, 2),
(6518, '640609', 'บ้านไร่   ', 735, 51, 2),
(6519, '640610', 'ทับผึ้ง   ', 735, 51, 2),
(6520, '640611', 'บ้านซ่าน   ', 735, 51, 2),
(6521, '640612', 'วังใหญ่   ', 735, 51, 2),
(6522, '640613', 'ราวต้นจันทร์   ', 735, 51, 2),
(6523, '640701', 'เมืองสวรรคโลก   ', 736, 51, 2),
(6524, '640702', 'ในเมือง   ', 736, 51, 2),
(6525, '640703', 'คลองกระจง   ', 736, 51, 2),
(6526, '640704', 'วังพิณพาทย์   ', 736, 51, 2),
(6527, '640705', 'วังไม้ขอน   ', 736, 51, 2),
(6528, '640706', 'ย่านยาว   ', 736, 51, 2),
(6529, '640707', 'นาทุ่ง   ', 736, 51, 2),
(6530, '640708', 'คลองยาง   ', 736, 51, 2),
(6531, '640709', 'เมืองบางยม   ', 736, 51, 2),
(6532, '640710', 'ท่าทอง   ', 736, 51, 2),
(6533, '640711', 'ปากน้ำ   ', 736, 51, 2),
(6534, '640712', 'ป่ากุมเกาะ   ', 736, 51, 2),
(6535, '640713', 'เมืองบางขลัง   ', 736, 51, 2),
(6536, '640714', 'หนองกลับ   ', 736, 51, 2),
(6537, '640795', '*ประชาราษฎร์   ', 736, 51, 2),
(6538, '640796', '*คลองมะพลับ   ', 736, 51, 2),
(6539, '640797', '*น้ำขุม   ', 736, 51, 2),
(6540, '640798', '*นครเดิฐ   ', 736, 51, 2),
(6541, '640799', '*ศรีนคร   ', 736, 51, 2),
(6542, '640801', 'ศรีนคร   ', 737, 51, 2),
(6543, '640802', 'นครเดิฐ   ', 737, 51, 2),
(6544, '640803', 'น้ำขุม   ', 737, 51, 2),
(6545, '640804', 'คลองมะพลับ   ', 737, 51, 2),
(6546, '640805', 'หนองบัว   ', 737, 51, 2),
(6547, '640901', 'บ้านใหม่ไชยมงคล   ', 738, 51, 2),
(6548, '640902', 'ไทยชนะศึก   ', 738, 51, 2),
(6549, '640903', 'ทุ่งเสลี่ยม   ', 738, 51, 2),
(6550, '640904', 'กลางดง   ', 738, 51, 2),
(6551, '640905', 'เขาแก้วศรีสมบูรณ์   ', 738, 51, 2),
(6552, '650101', 'ในเมือง   ', 739, 52, 2),
(6553, '650102', 'วังน้ำคู้   ', 739, 52, 2),
(6554, '650103', 'วัดจันทร์   ', 739, 52, 2),
(6555, '650104', 'วัดพริก   ', 739, 52, 2),
(6556, '650105', 'ท่าทอง   ', 739, 52, 2),
(6557, '650106', 'ท่าโพธิ์   ', 739, 52, 2),
(6558, '650107', 'สมอแข   ', 739, 52, 2),
(6559, '650108', 'ดอนทอง   ', 739, 52, 2),
(6560, '650109', 'บ้านป่า   ', 739, 52, 2),
(6561, '650110', 'ปากโทก   ', 739, 52, 2),
(6562, '650111', 'หัวรอ   ', 739, 52, 2),
(6563, '650112', 'จอมทอง   ', 739, 52, 2),
(6564, '650113', 'บ้านกร่าง   ', 739, 52, 2),
(6565, '650114', 'บ้านคลอง   ', 739, 52, 2),
(6566, '650115', 'พลายชุมพล   ', 739, 52, 2),
(6567, '650116', 'มะขามสูง   ', 739, 52, 2),
(6568, '650117', 'อรัญญิก   ', 739, 52, 2),
(6569, '650118', 'บึงพระ   ', 739, 52, 2),
(6570, '650119', 'ไผ่ขอดอน   ', 739, 52, 2),
(6571, '650120', 'งิ้วงาม   ', 739, 52, 2),
(6572, '650201', 'นครไทย   ', 740, 52, 2),
(6573, '650202', 'หนองกะท้าว   ', 740, 52, 2),
(6574, '650203', 'บ้านแยง   ', 740, 52, 2),
(6575, '650204', 'เนินเพิ่ม   ', 740, 52, 2),
(6576, '650205', 'นาบัว   ', 740, 52, 2),
(6577, '650206', 'นครชุม   ', 740, 52, 2),
(6578, '650207', 'น้ำกุ่ม   ', 740, 52, 2),
(6579, '650208', 'ยางโกลน   ', 740, 52, 2),
(6580, '650209', 'บ่อโพธิ์   ', 740, 52, 2),
(6581, '650210', 'บ้านพร้าว   ', 740, 52, 2),
(6582, '650211', 'ห้วยเฮี้ย   ', 740, 52, 2),
(6583, '650301', 'ป่าแดง   ', 741, 52, 2),
(6584, '650302', 'ชาติตระการ   ', 741, 52, 2),
(6585, '650303', 'สวนเมี่ยง   ', 741, 52, 2),
(6586, '650304', 'บ้านดง   ', 741, 52, 2),
(6587, '650305', 'บ่อภาค   ', 741, 52, 2),
(6588, '650306', 'ท่าสะแก   ', 741, 52, 2),
(6589, '650401', 'บางระกำ   ', 742, 52, 2),
(6590, '650402', 'ปลักแรด   ', 742, 52, 2),
(6591, '650403', 'พันเสา   ', 742, 52, 2),
(6592, '650404', 'วังอิทก   ', 742, 52, 2),
(6593, '650405', 'บึงกอก   ', 742, 52, 2),
(6594, '650406', 'หนองกุลา   ', 742, 52, 2),
(6595, '650407', 'ชุมแสงสงคราม   ', 742, 52, 2),
(6596, '650408', 'นิคมพัฒนา   ', 742, 52, 2),
(6597, '650409', 'บ่อทอง   ', 742, 52, 2),
(6598, '650410', 'ท่านางงาม   ', 742, 52, 2),
(6599, '650411', 'คุยม่วง   ', 742, 52, 2),
(6600, '650501', 'บางกระทุ่ม   ', 743, 52, 2),
(6601, '650502', 'บ้านไร่   ', 743, 52, 2),
(6602, '650503', 'โคกสลุด   ', 743, 52, 2),
(6603, '650504', 'สนามคลี   ', 743, 52, 2),
(6604, '650505', 'ท่าตาล   ', 743, 52, 2),
(6605, '650506', 'ไผ่ล้อม   ', 743, 52, 2),
(6606, '650507', 'นครป่าหมาก   ', 743, 52, 2),
(6607, '650508', 'เนินกุ่ม   ', 743, 52, 2),
(6608, '650509', 'วัดตายม   ', 743, 52, 2),
(6609, '650601', 'พรหมพิราม   ', 744, 52, 2),
(6610, '650602', 'ท่าช้าง   ', 744, 52, 2),
(6611, '650603', 'วงฆ้อง   ', 744, 52, 2),
(6612, '650604', 'มะตูม   ', 744, 52, 2),
(6613, '650605', 'หอกลอง   ', 744, 52, 2),
(6614, '650606', 'ศรีภิรมย์   ', 744, 52, 2),
(6615, '650607', 'ตลุกเทียม   ', 744, 52, 2),
(6616, '650608', 'วังวน   ', 744, 52, 2),
(6617, '650609', 'หนองแขม   ', 744, 52, 2),
(6618, '650610', 'มะต้อง   ', 744, 52, 2),
(6619, '650611', 'ทับยายเชียง   ', 744, 52, 2),
(6620, '650612', 'ดงประคำ   ', 744, 52, 2),
(6621, '650701', 'วัดโบสถ์   ', 745, 52, 2),
(6622, '650702', 'ท่างาม   ', 745, 52, 2),
(6623, '650703', 'ท้อแท้   ', 745, 52, 2),
(6624, '650704', 'บ้านยาง   ', 745, 52, 2),
(6625, '650705', 'หินลาด   ', 745, 52, 2),
(6626, '650706', 'คันโช้ง   ', 745, 52, 2),
(6627, '650801', 'วังทอง   ', 746, 52, 2),
(6628, '650802', 'พันชาลี   ', 746, 52, 2),
(6629, '650803', 'แม่ระกา   ', 746, 52, 2),
(6630, '650804', 'บ้านกลาง   ', 746, 52, 2),
(6631, '650805', 'วังพิกุล   ', 746, 52, 2),
(6632, '650806', 'แก่งโสภา   ', 746, 52, 2),
(6633, '650807', 'ท่าหมื่นราม   ', 746, 52, 2),
(6634, '650808', 'วังนกแอ่น   ', 746, 52, 2),
(6635, '650809', 'หนองพระ   ', 746, 52, 2),
(6636, '650810', 'ชัยนาม   ', 746, 52, 2),
(6637, '650811', 'ดินทอง   ', 746, 52, 2),
(6638, '650895', '*บ้านน้อยซุ้มขี้เหล็ก   ', 746, 52, 2),
(6639, '650896', '*วังโพรง   ', 746, 52, 2),
(6640, '650897', '*ไทรย้อย   ', 746, 52, 2),
(6641, '650898', '*บ้านมุง   ', 746, 52, 2),
(6642, '650899', '*ชมพู   ', 746, 52, 2),
(6643, '650901', 'ชมพู   ', 747, 52, 2),
(6644, '650902', 'บ้านมุง   ', 747, 52, 2),
(6645, '650903', 'ไทรย้อย   ', 747, 52, 2),
(6646, '650904', 'วังโพรง   ', 747, 52, 2),
(6647, '650905', 'บ้านน้อยซุ้มขี้เหล็ก   ', 747, 52, 2),
(6648, '650906', 'เนินมะปราง   ', 747, 52, 2),
(6649, '650907', 'วังยาง   ', 747, 52, 2),
(6650, '650908', 'โคกแหลม   ', 747, 52, 2),
(6651, '660101', 'ในเมือง   ', 748, 53, 2),
(6652, '660102', 'ไผ่ขวาง   ', 748, 53, 2),
(6653, '660103', 'ย่านยาว   ', 748, 53, 2),
(6654, '660104', 'ท่าฬ่อ   ', 748, 53, 2),
(6655, '660105', 'ปากทาง   ', 748, 53, 2),
(6656, '660106', 'คลองคะเชนทร์   ', 748, 53, 2),
(6657, '660107', 'โรงช้าง   ', 748, 53, 2),
(6658, '660108', 'เมืองเก่า   ', 748, 53, 2),
(6659, '660109', 'ท่าหลวง   ', 748, 53, 2),
(6660, '660110', 'บ้านบุ่ง   ', 748, 53, 2),
(6661, '660111', 'ฆะมัง   ', 748, 53, 2),
(6662, '660112', 'ดงป่าคำ   ', 748, 53, 2),
(6663, '660113', 'หัวดง   ', 748, 53, 2),
(6664, '660114', '*หนองปล้อง   ', 748, 53, 2),
(6665, '660115', 'ป่ามะคาบ   ', 748, 53, 2),
(6666, '660116', '*สากเหล็ก   ', 748, 53, 2),
(6667, '660117', '*ท่าเยี่ยม   ', 748, 53, 2),
(6668, '660118', '*คลองทราย   ', 748, 53, 2),
(6669, '660119', 'สายคำโห้   ', 748, 53, 2),
(6670, '660120', 'ดงกลาง   ', 748, 53, 2),
(6671, '660192', '*ไผ่รอบ   ', 748, 53, 2),
(6672, '660193', '*วังจิก   ', 748, 53, 2),
(6673, '660194', '*โพธิ์ประทับช้าง   ', 748, 53, 2),
(6674, '660195', '*ไผ่ท่าโพ   ', 748, 53, 2),
(6675, '660196', '*วังจิก   ', 748, 53, 2),
(6676, '660197', '*หนองพระ   ', 748, 53, 2),
(6677, '660198', '*หนองปลาไหล   ', 748, 53, 2),
(6678, '660199', '*วังทรายพูน   ', 748, 53, 2),
(6679, '660201', 'วังทรายพูน   ', 749, 53, 2),
(6680, '660202', 'หนองปลาไหล   ', 749, 53, 2),
(6681, '660203', 'หนองพระ   ', 749, 53, 2),
(6682, '660204', 'หนองปล้อง   ', 749, 53, 2),
(6683, '660301', 'โพธิ์ประทับช้าง   ', 750, 53, 2),
(6684, '660302', 'ไผ่ท่าโพ   ', 750, 53, 2),
(6685, '660303', 'วังจิก   ', 750, 53, 2),
(6686, '660304', 'ไผ่รอบ   ', 750, 53, 2),
(6687, '660305', 'ดงเสือเหลือง   ', 750, 53, 2),
(6688, '660306', 'เนินสว่าง   ', 750, 53, 2),
(6689, '660307', 'ทุ่งใหญ่   ', 750, 53, 2),
(6690, '660401', 'ตะพานหิน   ', 751, 53, 2),
(6691, '660402', 'งิ้วราย   ', 751, 53, 2),
(6692, '660403', 'ห้วยเกตุ   ', 751, 53, 2),
(6693, '660404', 'ไทรโรงโขน   ', 751, 53, 2),
(6694, '660405', 'หนองพยอม   ', 751, 53, 2),
(6695, '660406', 'ทุ่งโพธิ์   ', 751, 53, 2),
(6696, '660407', 'ดงตะขบ   ', 751, 53, 2),
(6697, '660408', 'คลองคูณ   ', 751, 53, 2),
(6698, '660409', 'วังสำโรง   ', 751, 53, 2),
(6699, '660410', 'วังหว้า   ', 751, 53, 2),
(6700, '660411', 'วังหลุม   ', 751, 53, 2),
(6701, '660412', 'ทับหมัน   ', 751, 53, 2),
(6702, '660413', 'ไผ่หลวง   ', 751, 53, 2),
(6703, '660496', '*ท้ายทุ่ง   ', 751, 53, 2),
(6704, '660497', '*เขาเจ็ดลูก   ', 751, 53, 2),
(6705, '660498', '*เขาทราย   ', 751, 53, 2),
(6706, '660499', '*ทับคล้อ   ', 751, 53, 2),
(6707, '660501', 'บางมูลนาก   ', 752, 53, 2),
(6708, '660502', 'บางไผ่   ', 752, 53, 2),
(6709, '660503', 'หอไกร   ', 752, 53, 2),
(6710, '660504', 'เนินมะกอก   ', 752, 53, 2),
(6711, '660505', 'วังสำโรง   ', 752, 53, 2),
(6712, '660506', 'ภูมิ   ', 752, 53, 2),
(6713, '660507', 'วังกรด   ', 752, 53, 2),
(6714, '660508', 'ห้วยเขน   ', 752, 53, 2),
(6715, '660509', 'วังตะกู   ', 752, 53, 2),
(6716, '660510', 'สำนักขุนเณร*   ', 752, 53, 2),
(6717, '660511', 'ห้วยพุก*   ', 752, 53, 2),
(6718, '660512', 'ห้วยร่วม*   ', 752, 53, 2),
(6719, '660513', 'วังงิ้ว*   ', 752, 53, 2),
(6720, '660514', 'ลำประดา   ', 752, 53, 2),
(6721, '660515', 'วังงิ้วใต้*   ', 752, 53, 2),
(6722, '660601', 'โพทะเล   ', 753, 53, 2),
(6723, '660602', 'ท้ายน้ำ   ', 753, 53, 2),
(6724, '660603', 'ทะนง   ', 753, 53, 2),
(6725, '660604', 'ท่าบัว   ', 753, 53, 2),
(6726, '660605', 'ทุ่งน้อย   ', 753, 53, 2),
(6727, '660606', 'ท่าขมิ้น   ', 753, 53, 2),
(6728, '660607', 'ท่าเสา   ', 753, 53, 2),
(6729, '660608', 'บางคลาน   ', 753, 53, 2),
(6730, '660609', 'บางลาย*   ', 753, 53, 2),
(6731, '660610', 'บึงนาราง*   ', 753, 53, 2),
(6732, '660611', 'ท่านั่ง   ', 753, 53, 2),
(6733, '660612', 'บ้านน้อย   ', 753, 53, 2),
(6734, '660613', 'วัดขวาง   ', 753, 53, 2),
(6735, '660614', 'โพธิ์ไทรงาม*   ', 753, 53, 2),
(6736, '660615', 'แหลมรัง*   ', 753, 53, 2),
(6737, '660616', 'ห้วยแก้ว*   ', 753, 53, 2),
(6738, '660701', 'สามง่าม   ', 754, 53, 2),
(6739, '660702', 'กำแพงดิน   ', 754, 53, 2),
(6740, '660703', 'รังนก   ', 754, 53, 2),
(6741, '660704', 'หนองหลุม*   ', 754, 53, 2),
(6742, '660705', 'บ้านนา*   ', 754, 53, 2),
(6743, '660706', 'เนินปอ   ', 754, 53, 2),
(6744, '660707', 'หนองโสน   ', 754, 53, 2),
(6745, '660708', 'วังโมกข์*   ', 754, 53, 2),
(6746, '660709', 'บึงบัว*   ', 754, 53, 2),
(6747, '660801', 'ทับคล้อ   ', 755, 53, 2),
(6748, '660802', 'เขาทราย   ', 755, 53, 2),
(6749, '660803', 'เขาเจ็ดลูก   ', 755, 53, 2),
(6750, '660804', 'ท้ายทุ่ง   ', 755, 53, 2),
(6751, '660901', 'สากเหล็ก   ', 756, 53, 2),
(6752, '660902', 'ท่าเยี่ยม   ', 756, 53, 2),
(6753, '660903', 'คลองทราย   ', 756, 53, 2),
(6754, '660904', 'หนองหญ้าไทร   ', 756, 53, 2),
(6755, '660905', 'วังทับไทร   ', 756, 53, 2),
(6756, '661001', 'ห้วยแก้ว   ', 757, 53, 2),
(6757, '661002', 'โพธิ์ไทรงาม   ', 757, 53, 2),
(6758, '661003', 'แหลมรัง   ', 757, 53, 2),
(6759, '661004', 'บางลาย   ', 757, 53, 2),
(6760, '661005', 'บึงนาราง   ', 757, 53, 2),
(6761, '661101', 'วังงิ้วใต้   ', 758, 53, 2),
(6762, '661102', 'วังงิ้ว   ', 758, 53, 2),
(6763, '661103', 'ห้วยร่วม   ', 758, 53, 2),
(6764, '661104', 'ห้วยพุก   ', 758, 53, 2),
(6765, '661105', 'สำนักขุนเณร   ', 758, 53, 2),
(6766, '661201', 'บ้านนา   ', 759, 53, 2),
(6767, '661202', 'บึงบัว   ', 759, 53, 2),
(6768, '661203', 'วังโมกข์   ', 759, 53, 2),
(6769, '661204', 'หนองหลุม   ', 759, 53, 2),
(6770, '670101', 'ในเมือง   ', 760, 54, 2),
(6771, '670102', 'ตะเบาะ   ', 760, 54, 2),
(6772, '670103', 'บ้านโตก   ', 760, 54, 2),
(6773, '670104', 'สะเดียง   ', 760, 54, 2),
(6774, '670105', 'ป่าเลา   ', 760, 54, 2),
(6775, '670106', 'นางั่ว   ', 760, 54, 2),
(6776, '670107', 'ท่าพล   ', 760, 54, 2),
(6777, '670108', 'ดงมูลเหล็ก   ', 760, 54, 2),
(6778, '670109', 'บ้านโคก   ', 760, 54, 2),
(6779, '670110', 'ชอนไพร   ', 760, 54, 2),
(6780, '670111', 'นาป่า   ', 760, 54, 2),
(6781, '670112', 'นายม   ', 760, 54, 2),
(6782, '670113', 'วังชมภู   ', 760, 54, 2),
(6783, '670114', 'น้ำร้อน   ', 760, 54, 2),
(6784, '670115', 'ห้วยสะแก   ', 760, 54, 2),
(6785, '670116', 'ห้วยใหญ่   ', 760, 54, 2),
(6786, '670117', 'ระวิง   ', 760, 54, 2),
(6787, '670201', 'ชนแดน   ', 761, 54, 2),
(6788, '670202', 'ดงขุย   ', 761, 54, 2),
(6789, '670203', 'ท่าข้าม   ', 761, 54, 2),
(6790, '670204', 'พุทธบาท   ', 761, 54, 2),
(6791, '670205', 'ลาดแค   ', 761, 54, 2),
(6792, '670206', 'บ้านกล้วย   ', 761, 54, 2),
(6793, '670207', '*ซับเปิม   ', 761, 54, 2),
(6794, '670208', 'ซับพุทรา   ', 761, 54, 2),
(6795, '670209', 'ตะกุดไร   ', 761, 54, 2),
(6796, '670210', 'ศาลาลาย   ', 761, 54, 2),
(6797, '670298', '*ท้ายดง   ', 761, 54, 2),
(6798, '670299', '*วังโป่ง   ', 761, 54, 2),
(6799, '670301', 'หล่มสัก   ', 762, 54, 2),
(6800, '670302', 'วัดป่า   ', 762, 54, 2),
(6801, '670303', 'ตาลเดี่ยว   ', 762, 54, 2),
(6802, '670304', 'ฝายนาแซง   ', 762, 54, 2),
(6803, '670305', 'หนองสว่าง   ', 762, 54, 2),
(6804, '670306', 'น้ำเฮี้ย   ', 762, 54, 2),
(6805, '670307', 'สักหลง   ', 762, 54, 2),
(6806, '670308', 'ท่าอิบุญ   ', 762, 54, 2),
(6807, '670309', 'บ้านโสก   ', 762, 54, 2),
(6808, '670310', 'บ้านติ้ว   ', 762, 54, 2),
(6809, '670311', 'ห้วยไร่   ', 762, 54, 2),
(6810, '670312', 'น้ำก้อ   ', 762, 54, 2),
(6811, '670313', 'ปากช่อง   ', 762, 54, 2),
(6812, '670314', 'น้ำชุน   ', 762, 54, 2),
(6813, '670315', 'หนองไขว่   ', 762, 54, 2),
(6814, '670316', 'ลานบ่า   ', 762, 54, 2),
(6815, '670317', 'บุ่งคล้า   ', 762, 54, 2),
(6816, '670318', 'บุ่งน้ำเต้า   ', 762, 54, 2),
(6817, '670319', 'บ้านกลาง   ', 762, 54, 2),
(6818, '670320', 'ช้างตะลูด   ', 762, 54, 2),
(6819, '670321', 'บ้านไร่   ', 762, 54, 2),
(6820, '670322', 'ปากดุก   ', 762, 54, 2),
(6821, '670323', 'บ้านหวาย   ', 762, 54, 2),
(6822, '670399', '*แคมป์สน   ', 762, 54, 2),
(6823, '670401', 'หล่มเก่า   ', 763, 54, 2),
(6824, '670402', 'นาซำ   ', 763, 54, 2),
(6825, '670403', 'หินฮาว   ', 763, 54, 2),
(6826, '670404', 'บ้านเนิน   ', 763, 54, 2),
(6827, '670405', 'ศิลา   ', 763, 54, 2),
(6828, '670406', 'นาแซง   ', 763, 54, 2),
(6829, '670407', 'วังบาล   ', 763, 54, 2),
(6830, '670408', 'นาเกาะ   ', 763, 54, 2),
(6831, '670409', 'ตาดกลอย   ', 763, 54, 2),
(6832, '670499', '*น้ำหนาว   ', 763, 54, 2),
(6833, '670501', 'ท่าโรง   ', 764, 54, 2),
(6834, '670502', 'สระประดู่   ', 764, 54, 2),
(6835, '670503', 'สามแยก   ', 764, 54, 2),
(6836, '670504', 'โคกปรง   ', 764, 54, 2),
(6837, '670505', 'น้ำร้อน   ', 764, 54, 2),
(6838, '670506', 'บ่อรัง   ', 764, 54, 2),
(6839, '670507', 'พุเตย   ', 764, 54, 2),
(6840, '670508', 'พุขาม   ', 764, 54, 2),
(6841, '670509', 'ภูน้ำหยด   ', 764, 54, 2),
(6842, '670510', 'ซับสมบูรณ์   ', 764, 54, 2),
(6843, '670511', 'บึงกระจับ   ', 764, 54, 2),
(6844, '670512', 'วังใหญ่   ', 764, 54, 2),
(6845, '670513', 'ยางสาว   ', 764, 54, 2),
(6846, '670514', 'ซับน้อย   ', 764, 54, 2),
(6847, '670595', '*นาสนุ่น   ', 764, 54, 2),
(6848, '670597', '*คลองกระจัง   ', 764, 54, 2),
(6849, '670598', '*สระกรวด   ', 764, 54, 2),
(6850, '670599', '*ศรีเทพ   ', 764, 54, 2),
(6851, '670601', 'ศรีเทพ   ', 765, 54, 2),
(6852, '670602', 'สระกรวด   ', 765, 54, 2),
(6853, '670603', 'คลองกระจัง   ', 765, 54, 2),
(6854, '670604', 'นาสนุ่น   ', 765, 54, 2),
(6855, '670605', 'โคกสะอาด   ', 765, 54, 2),
(6856, '670606', 'หนองย่างทอย   ', 765, 54, 2),
(6857, '670607', 'ประดู่งาม   ', 765, 54, 2),
(6858, '670701', 'กองทูล   ', 766, 54, 2),
(6859, '670702', 'นาเฉลียง   ', 766, 54, 2),
(6860, '670703', 'บ้านโภชน์   ', 766, 54, 2),
(6861, '670704', 'ท่าแดง   ', 766, 54, 2),
(6862, '670705', 'เพชรละคร   ', 766, 54, 2),
(6863, '670706', 'บ่อไทย   ', 766, 54, 2),
(6864, '670707', 'ห้วยโป่ง   ', 766, 54, 2),
(6865, '670708', 'วังท่าดี   ', 766, 54, 2),
(6866, '670709', 'บัววัฒนา   ', 766, 54, 2),
(6867, '670710', 'หนองไผ่   ', 766, 54, 2),
(6868, '670711', 'วังโบสถ์   ', 766, 54, 2),
(6869, '670712', 'ยางงาม   ', 766, 54, 2),
(6870, '670713', 'ท่าด้วง   ', 766, 54, 2),
(6871, '670801', 'ซับสมอทอด   ', 767, 54, 2),
(6872, '670802', 'ซับไม้แดง   ', 767, 54, 2),
(6873, '670803', 'หนองแจง   ', 767, 54, 2),
(6874, '670804', 'กันจุ   ', 767, 54, 2),
(6875, '670805', 'วังพิกุล   ', 767, 54, 2),
(6876, '670806', 'พญาวัง   ', 767, 54, 2),
(6877, '670807', 'ศรีมงคล   ', 767, 54, 2),
(6878, '670808', 'สระแก้ว   ', 767, 54, 2),
(6879, '670809', 'บึงสามพัน   ', 767, 54, 2),
(6880, '670901', 'น้ำหนาว   ', 768, 54, 2),
(6881, '670902', 'หลักด่าน   ', 768, 54, 2),
(6882, '670903', 'วังกวาง   ', 768, 54, 2),
(6883, '670904', 'โคกมน   ', 768, 54, 2),
(6884, '671001', 'วังโป่ง   ', 769, 54, 2),
(6885, '671002', 'ท้ายดง   ', 769, 54, 2),
(6886, '671003', 'ซับเปิบ   ', 769, 54, 2),
(6887, '671004', 'วังหิน   ', 769, 54, 2),
(6888, '671005', 'วังศาล   ', 769, 54, 2),
(6889, '671101', 'ทุ่งสมอ   ', 770, 54, 2),
(6890, '671102', 'แคมป์สน   ', 770, 54, 2),
(6891, '671103', 'เขาค้อ   ', 770, 54, 2),
(6892, '671104', 'ริมสีม่วง   ', 770, 54, 2),
(6893, '671105', 'สะเดาะพง   ', 770, 54, 2),
(6894, '671106', 'หนองแม่นา   ', 770, 54, 2),
(6895, '671107', 'เข็กน้อย   ', 770, 54, 2),
(6896, '700101', 'หน้าเมือง   ', 771, 55, 4),
(6897, '700102', 'เจดีย์หัก   ', 771, 55, 4),
(6898, '700103', 'ดอนตะโก   ', 771, 55, 4),
(6899, '700104', 'หนองกลางนา   ', 771, 55, 4),
(6900, '700105', 'ห้วยไผ่   ', 771, 55, 4),
(6901, '700106', 'คุ้งน้ำวน   ', 771, 55, 4),
(6902, '700107', 'คุ้งกระถิน   ', 771, 55, 4),
(6903, '700108', 'อ่างทอง   ', 771, 55, 4),
(6904, '700109', 'โคกหม้อ   ', 771, 55, 4),
(6905, '700110', 'สามเรือน   ', 771, 55, 4),
(6906, '700111', 'พิกุลทอง   ', 771, 55, 4),
(6907, '700112', 'น้ำพุ   ', 771, 55, 4),
(6908, '700113', 'ดอนแร่   ', 771, 55, 4),
(6909, '700114', 'หินกอง   ', 771, 55, 4),
(6910, '700115', 'เขาแร้ง   ', 771, 55, 4),
(6911, '700116', 'เกาะพลับพลา   ', 771, 55, 4),
(6912, '700117', 'หลุมดิน   ', 771, 55, 4),
(6913, '700118', 'บางป่า   ', 771, 55, 4),
(6914, '700119', 'พงสวาย   ', 771, 55, 4),
(6915, '700120', 'คูบัว   ', 771, 55, 4),
(6916, '700121', 'ท่าราบ   ', 771, 55, 4),
(6917, '700122', 'บ้านไร่   ', 771, 55, 4),
(6918, '700201', 'จอมบึง   ', 772, 55, 4),
(6919, '700202', 'ปากช่อง   ', 772, 55, 4),
(6920, '700203', 'เบิกไพร   ', 772, 55, 4),
(6921, '700204', 'ด่านทับตะโก   ', 772, 55, 4),
(6922, '700205', 'แก้มอ้น   ', 772, 55, 4),
(6923, '700206', 'รางบัว   ', 772, 55, 4),
(6924, '700297', '*ป่าหวาย   ', 772, 55, 4),
(6925, '700298', '*บ้านผึ้ง   ', 772, 55, 4),
(6926, '700299', '*สวนผึ้ง   ', 772, 55, 4),
(6927, '700301', 'สวนผึ้ง   ', 773, 55, 4),
(6928, '700302', 'ป่าหวาย   ', 773, 55, 4),
(6929, '700303', 'บ้านบึง*   ', 773, 55, 4),
(6930, '700304', 'ท่าเคย   ', 773, 55, 4),
(6931, '700305', 'บ้านคา*   ', 773, 55, 4),
(6932, '700306', 'หนองพันจันทร์*   ', 773, 55, 4),
(6933, '700307', 'ตะนาวศรี   ', 773, 55, 4),
(6934, '700401', 'ดำเนินสะดวก   ', 774, 55, 4),
(6935, '700402', 'ประสาทสิทธิ์   ', 774, 55, 4),
(6936, '700403', 'ศรีสุราษฎร์   ', 774, 55, 4),
(6937, '700404', 'ตาหลวง   ', 774, 55, 4),
(6938, '700405', 'ดอนกรวย   ', 774, 55, 4),
(6939, '700406', 'ดอนคลัง   ', 774, 55, 4),
(6940, '700407', 'บัวงาม   ', 774, 55, 4),
(6941, '700408', 'บ้านไร่   ', 774, 55, 4),
(6942, '700409', 'แพงพวย   ', 774, 55, 4),
(6943, '700410', 'สี่หมื่น   ', 774, 55, 4),
(6944, '700411', 'ท่านัด   ', 774, 55, 4),
(6945, '700412', 'ขุนพิทักษ์   ', 774, 55, 4),
(6946, '700413', 'ดอนไผ่   ', 774, 55, 4),
(6947, '700501', 'บ้านโป่ง   ', 775, 55, 4),
(6948, '700502', 'ท่าผา   ', 775, 55, 4),
(6949, '700503', 'กรับใหญ่   ', 775, 55, 4),
(6950, '700504', 'ปากแรต   ', 775, 55, 4),
(6951, '700505', 'หนองกบ   ', 775, 55, 4),
(6952, '700506', 'หนองอ้อ   ', 775, 55, 4),
(6953, '700507', 'ดอนกระเบื้อง   ', 775, 55, 4),
(6954, '700508', 'สวนกล้วย   ', 775, 55, 4),
(6955, '700509', 'นครชุมน์   ', 775, 55, 4),
(6956, '700510', 'บ้านม่วง   ', 775, 55, 4),
(6957, '700511', 'คุ้งพยอม   ', 775, 55, 4),
(6958, '700512', 'หนองปลาหมอ   ', 775, 55, 4),
(6959, '700513', 'เขาขลุง   ', 775, 55, 4),
(6960, '700514', 'เบิกไพร   ', 775, 55, 4),
(6961, '700515', 'ลาดบัวขาว   ', 775, 55, 4),
(6962, '700601', 'บางแพ   ', 776, 55, 4),
(6963, '700602', 'วังเย็น   ', 776, 55, 4),
(6964, '700603', 'หัวโพ   ', 776, 55, 4),
(6965, '700604', 'วัดแก้ว   ', 776, 55, 4),
(6966, '700605', 'ดอนใหญ่   ', 776, 55, 4),
(6967, '700606', 'ดอนคา   ', 776, 55, 4),
(6968, '700607', 'โพหัก   ', 776, 55, 4),
(6969, '700701', 'โพธาราม   ', 777, 55, 4),
(6970, '700702', 'ดอนกระเบื้อง   ', 777, 55, 4),
(6971, '700703', 'หนองโพ   ', 777, 55, 4),
(6972, '700704', 'บ้านเลือก   ', 777, 55, 4),
(6973, '700705', 'คลองตาคต   ', 777, 55, 4),
(6974, '700706', 'บ้านฆ้อง   ', 777, 55, 4),
(6975, '700707', 'บ้านสิงห์   ', 777, 55, 4),
(6976, '700708', 'ดอนทราย   ', 777, 55, 4),
(6977, '700709', 'เจ็ดเสมียน   ', 777, 55, 4),
(6978, '700710', 'คลองข่อย   ', 777, 55, 4),
(6979, '700711', 'ชำแระ   ', 777, 55, 4),
(6980, '700712', 'สร้อยฟ้า   ', 777, 55, 4),
(6981, '700713', 'ท่าชุมพล   ', 777, 55, 4),
(6982, '700714', 'บางโตนด   ', 777, 55, 4),
(6983, '700715', 'เตาปูน   ', 777, 55, 4),
(6984, '700716', 'นางแก้ว   ', 777, 55, 4),
(6985, '700717', 'ธรรมเสน   ', 777, 55, 4),
(6986, '700718', 'เขาชะงุ้ม   ', 777, 55, 4),
(6987, '700719', 'หนองกวาง   ', 777, 55, 4),
(6988, '700801', 'ทุ่งหลวง   ', 778, 55, 4),
(6989, '700802', 'วังมะนาว   ', 778, 55, 4),
(6990, '700803', 'ดอนทราย   ', 778, 55, 4),
(6991, '700804', 'หนองกระทุ่ม   ', 778, 55, 4),
(6992, '700805', 'ปากท่อ   ', 778, 55, 4),
(6993, '700806', 'ป่าไก่   ', 778, 55, 4),
(6994, '700807', 'วัดยางงาม   ', 778, 55, 4),
(6995, '700808', 'อ่างหิน   ', 778, 55, 4),
(6996, '700809', 'บ่อกระดาน   ', 778, 55, 4),
(6997, '700810', 'ยางหัก   ', 778, 55, 4),
(6998, '700811', 'วันดาว   ', 778, 55, 4),
(6999, '700812', 'ห้วยยางโทน   ', 778, 55, 4),
(7000, '700901', 'เกาะศาลพระ   ', 779, 55, 4),
(7001, '700902', 'จอมประทัด   ', 779, 55, 4),
(7002, '700903', 'วัดเพลง   ', 779, 55, 4),
(7003, '701001', 'บ้านคา   ', 780, 55, 4),
(7004, '701002', 'บ้านบึง   ', 780, 55, 4),
(7005, '701003', 'หนองพันจันทร์   ', 780, 55, 4),
(7006, '710101', 'บ้านเหนือ   ', 782, 56, 4),
(7007, '710102', 'บ้านใต้   ', 782, 56, 4),
(7008, '710103', 'ปากแพรก   ', 782, 56, 4),
(7009, '710104', 'ท่ามะขาม   ', 782, 56, 4),
(7010, '710105', 'แก่งเสี้ยน   ', 782, 56, 4),
(7011, '710106', 'หนองบัว   ', 782, 56, 4),
(7012, '710107', 'ลาดหญ้า   ', 782, 56, 4),
(7013, '710108', 'วังด้ง   ', 782, 56, 4),
(7014, '710109', 'ช่องสะเดา   ', 782, 56, 4),
(7015, '710110', 'หนองหญ้า   ', 782, 56, 4),
(7016, '710111', 'เกาะสำโรง   ', 782, 56, 4),
(7017, '710112', '*ด่านมะขามเตี้ย   ', 782, 56, 4),
(7018, '710113', 'บ้านเก่า   ', 782, 56, 4),
(7019, '710114', '*จรเข้เผือก   ', 782, 56, 4),
(7020, '710115', '*กลอนโด   ', 782, 56, 4),
(7021, '710116', 'วังเย็น   ', 782, 56, 4),
(7022, '710201', 'ลุ่มสุ่ม   ', 783, 56, 4),
(7023, '710202', 'ท่าเสา   ', 783, 56, 4),
(7024, '710203', 'สิงห์   ', 783, 56, 4),
(7025, '710204', 'ไทรโยค   ', 783, 56, 4),
(7026, '710205', 'วังกระแจะ   ', 783, 56, 4),
(7027, '710206', 'ศรีมงคล   ', 783, 56, 4),
(7028, '710207', 'บ้องตี้   ', 783, 56, 4),
(7029, '710301', 'บ่อพลอย   ', 784, 56, 4),
(7030, '710302', 'หนองกุ่ม   ', 784, 56, 4),
(7031, '710303', 'หนองรี   ', 784, 56, 4),
(7032, '710304', '*หนองปรือ   ', 784, 56, 4),
(7033, '710305', 'หลุมรัง   ', 784, 56, 4),
(7034, '710306', '*หนองปลาไหล   ', 784, 56, 4),
(7035, '710307', '*สมเด็จเจริญ   ', 784, 56, 4),
(7036, '710308', 'ช่องด่าน   ', 784, 56, 4),
(7037, '710309', 'หนองกร่าง   ', 784, 56, 4),
(7038, '710401', 'นาสวน   ', 785, 56, 4),
(7039, '710402', 'ด่านแม่แฉลบ   ', 785, 56, 4),
(7040, '710403', 'หนองเป็ด   ', 785, 56, 4),
(7041, '710404', 'ท่ากระดาน   ', 785, 56, 4),
(7042, '710405', 'เขาโจด   ', 785, 56, 4),
(7043, '710406', 'แม่กระบุง   ', 785, 56, 4),
(7044, '710501', 'พงตึก   ', 786, 56, 4),
(7045, '710502', 'ยางม่วง   ', 786, 56, 4),
(7046, '710503', 'ดอนชะเอม   ', 786, 56, 4),
(7047, '710504', 'ท่าไม้   ', 786, 56, 4),
(7048, '710505', 'ตะคร้ำเอน   ', 786, 56, 4),
(7049, '710506', 'ท่ามะกา   ', 786, 56, 4),
(7050, '710507', 'ท่าเรือ   ', 786, 56, 4),
(7051, '710508', 'โคกตะบอง   ', 786, 56, 4),
(7052, '710509', 'ดอนขมิ้น   ', 786, 56, 4),
(7053, '710510', 'อุโลกสี่หมื่น   ', 786, 56, 4),
(7054, '710511', 'เขาสามสิบหาบ   ', 786, 56, 4),
(7055, '710512', 'พระแท่น   ', 786, 56, 4),
(7056, '710513', 'หวายเหนียว   ', 786, 56, 4),
(7057, '710514', 'แสนตอ   ', 786, 56, 4),
(7058, '710515', 'สนามแย้   ', 786, 56, 4),
(7059, '710516', 'ท่าเสา   ', 786, 56, 4),
(7060, '710517', 'หนองลาน   ', 786, 56, 4),
(7061, '710601', 'ท่าม่วง   ', 787, 56, 4),
(7062, '710602', 'วังขนาย   ', 787, 56, 4),
(7063, '710603', 'วังศาลา   ', 787, 56, 4),
(7064, '710604', 'ท่าล้อ   ', 787, 56, 4),
(7065, '710605', 'หนองขาว   ', 787, 56, 4),
(7066, '710606', 'ทุ่งทอง   ', 787, 56, 4),
(7067, '710607', 'เขาน้อย   ', 787, 56, 4),
(7068, '710608', 'ม่วงชุม   ', 787, 56, 4),
(7069, '710609', 'บ้านใหม่   ', 787, 56, 4),
(7070, '710610', 'พังตรุ   ', 787, 56, 4),
(7071, '710611', 'ท่าตะคร้อ   ', 787, 56, 4),
(7072, '710612', 'รางสาลี่   ', 787, 56, 4),
(7073, '710613', 'หนองตากยา   ', 787, 56, 4),
(7074, '710701', 'ท่าขนุน   ', 788, 56, 4),
(7075, '710702', 'ปิล๊อก   ', 788, 56, 4),
(7076, '710703', 'หินดาด   ', 788, 56, 4),
(7077, '710704', 'ลิ่นถิ่น   ', 788, 56, 4),
(7078, '710705', 'ชะแล   ', 788, 56, 4),
(7079, '710706', 'ห้วยเขย่ง   ', 788, 56, 4),
(7080, '710707', 'สหกรณ์นิคม   ', 788, 56, 4),
(7081, '710801', 'หนองลู   ', 789, 56, 4),
(7082, '710802', 'ปรังเผล   ', 789, 56, 4),
(7083, '710803', 'ไล่โว่   ', 789, 56, 4),
(7084, '710901', 'พนมทวน   ', 790, 56, 4),
(7085, '710902', 'หนองโรง   ', 790, 56, 4),
(7086, '710903', 'ทุ่งสมอ   ', 790, 56, 4),
(7087, '710904', 'ดอนเจดีย์   ', 790, 56, 4),
(7088, '710905', 'พังตรุ   ', 790, 56, 4),
(7089, '710906', 'รางหวาย   ', 790, 56, 4),
(7090, '710907', '*ดอนแสลบ   ', 790, 56, 4),
(7091, '710908', '*ห้วยกระเจา   ', 790, 56, 4),
(7092, '710909', '*สระลงเรือ   ', 790, 56, 4),
(7093, '710910', '*วังไผ่   ', 790, 56, 4),
(7094, '710911', 'หนองสาหร่าย   ', 790, 56, 4),
(7095, '710912', 'ดอนตาเพชร   ', 790, 56, 4),
(7096, '711001', 'เลาขวัญ   ', 791, 56, 4),
(7097, '711002', 'หนองโสน   ', 791, 56, 4),
(7098, '711003', 'หนองประดู่   ', 791, 56, 4),
(7099, '711004', 'หนองปลิง   ', 791, 56, 4),
(7100, '711005', 'หนองนกแก้ว   ', 791, 56, 4),
(7101, '711006', 'ทุ่งกระบ่ำ   ', 791, 56, 4),
(7102, '711007', 'หนองฝ้าย   ', 791, 56, 4),
(7103, '711101', 'ด่านมะขามเตี้ย   ', 792, 56, 4);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(7104, '711102', 'กลอนโด   ', 792, 56, 4),
(7105, '711103', 'จรเข้เผือก   ', 792, 56, 4),
(7106, '711104', 'หนองไผ่   ', 792, 56, 4),
(7107, '711201', 'หนองปรือ   ', 793, 56, 4),
(7108, '711202', 'หนองปลาไหล   ', 793, 56, 4),
(7109, '711203', 'สมเด็จเจริญ   ', 793, 56, 4),
(7110, '711301', 'ห้วยกระเจา   ', 794, 56, 4),
(7111, '711302', 'วังไผ่   ', 794, 56, 4),
(7112, '711303', 'ดอนแสลบ   ', 794, 56, 4),
(7113, '711304', 'สระลงเรือ   ', 794, 56, 4),
(7114, '720101', 'ท่าพี่เลี้ยง   ', 797, 57, 2),
(7115, '720102', 'รั้วใหญ่   ', 797, 57, 2),
(7116, '720103', 'ทับตีเหล็ก   ', 797, 57, 2),
(7117, '720104', 'ท่าระหัด   ', 797, 57, 2),
(7118, '720105', 'ไผ่ขวาง   ', 797, 57, 2),
(7119, '720106', 'โคกโคเฒ่า   ', 797, 57, 2),
(7120, '720107', 'ดอนตาล   ', 797, 57, 2),
(7121, '720108', 'ดอนมะสังข์   ', 797, 57, 2),
(7122, '720109', 'พิหารแดง   ', 797, 57, 2),
(7123, '720110', 'ดอนกำยาน   ', 797, 57, 2),
(7124, '720111', 'ดอนโพธิ์ทอง   ', 797, 57, 2),
(7125, '720112', 'บ้านโพธิ์   ', 797, 57, 2),
(7126, '720113', 'สระแก้ว   ', 797, 57, 2),
(7127, '720114', 'ตลิ่งชัน   ', 797, 57, 2),
(7128, '720115', 'บางกุ้ง   ', 797, 57, 2),
(7129, '720116', 'ศาลาขาว   ', 797, 57, 2),
(7130, '720117', 'สวนแตง   ', 797, 57, 2),
(7131, '720118', 'สนามชัย   ', 797, 57, 2),
(7132, '720119', 'โพธิ์พระยา   ', 797, 57, 2),
(7133, '720120', 'สนามคลี   ', 797, 57, 2),
(7134, '720201', 'เขาพระ   ', 798, 57, 2),
(7135, '720202', 'เดิมบาง   ', 798, 57, 2),
(7136, '720203', 'นางบวช   ', 798, 57, 2),
(7137, '720204', 'เขาดิน   ', 798, 57, 2),
(7138, '720205', 'ปากน้ำ   ', 798, 57, 2),
(7139, '720206', 'ทุ่งคลี   ', 798, 57, 2),
(7140, '720207', 'โคกช้าง   ', 798, 57, 2),
(7141, '720208', 'หัวเขา   ', 798, 57, 2),
(7142, '720209', 'หัวนา   ', 798, 57, 2),
(7143, '720210', 'บ่อกรุ   ', 798, 57, 2),
(7144, '720211', 'วังศรีราช   ', 798, 57, 2),
(7145, '720212', 'ป่าสะแก   ', 798, 57, 2),
(7146, '720213', 'ยางนอน   ', 798, 57, 2),
(7147, '720214', 'หนองกระทุ่ม   ', 798, 57, 2),
(7148, '720296', '*องค์พระ   ', 798, 57, 2),
(7149, '720297', '*ห้วยขมิ้น   ', 798, 57, 2),
(7150, '720298', '*ด่านช้าง   ', 798, 57, 2),
(7151, '720299', '*หนองมะค่าโมง   ', 798, 57, 2),
(7152, '720301', 'หนองมะค่าโมง   ', 799, 57, 2),
(7153, '720302', 'ด่านช้าง   ', 799, 57, 2),
(7154, '720303', 'ห้วยขมิ้น   ', 799, 57, 2),
(7155, '720304', 'องค์พระ   ', 799, 57, 2),
(7156, '720305', 'วังคัน   ', 799, 57, 2),
(7157, '720306', 'นิคมกระเสียว   ', 799, 57, 2),
(7158, '720307', 'วังยาว   ', 799, 57, 2),
(7159, '720401', 'โคกคราม   ', 800, 57, 2),
(7160, '720402', 'บางปลาม้า   ', 800, 57, 2),
(7161, '720403', 'ตะค่า   ', 800, 57, 2),
(7162, '720404', 'บางใหญ่   ', 800, 57, 2),
(7163, '720405', 'กฤษณา   ', 800, 57, 2),
(7164, '720406', 'สาลี   ', 800, 57, 2),
(7165, '720407', 'ไผ่กองดิน   ', 800, 57, 2),
(7166, '720408', 'องครักษ์   ', 800, 57, 2),
(7167, '720409', 'จรเข้ใหญ่   ', 800, 57, 2),
(7168, '720410', 'บ้านแหลม   ', 800, 57, 2),
(7169, '720411', 'มะขามล้ม   ', 800, 57, 2),
(7170, '720412', 'วังน้ำเย็น   ', 800, 57, 2),
(7171, '720413', 'วัดโบสถ์   ', 800, 57, 2),
(7172, '720414', 'วัดดาว   ', 800, 57, 2),
(7173, '720501', 'ศรีประจันต์   ', 801, 57, 2),
(7174, '720502', 'บ้านกร่าง   ', 801, 57, 2),
(7175, '720503', 'มดแดง   ', 801, 57, 2),
(7176, '720504', 'บางงาม   ', 801, 57, 2),
(7177, '720505', 'ดอนปรู   ', 801, 57, 2),
(7178, '720506', 'ปลายนา   ', 801, 57, 2),
(7179, '720507', 'วังหว้า   ', 801, 57, 2),
(7180, '720508', 'วังน้ำซับ   ', 801, 57, 2),
(7181, '720509', 'วังยาง   ', 801, 57, 2),
(7182, '720601', 'ดอนเจดีย์   ', 802, 57, 2),
(7183, '720602', 'หนองสาหร่าย   ', 802, 57, 2),
(7184, '720603', 'ไร่รถ   ', 802, 57, 2),
(7185, '720604', 'สระกระโจม   ', 802, 57, 2),
(7186, '720605', 'ทะเลบก   ', 802, 57, 2),
(7187, '720701', 'สองพี่น้อง   ', 803, 57, 2),
(7188, '720702', 'บางเลน   ', 803, 57, 2),
(7189, '720703', 'บางตาเถร   ', 803, 57, 2),
(7190, '720704', 'บางตะเคียน   ', 803, 57, 2),
(7191, '720705', 'บ้านกุ่ม   ', 803, 57, 2),
(7192, '720706', 'หัวโพธิ์   ', 803, 57, 2),
(7193, '720707', 'บางพลับ   ', 803, 57, 2),
(7194, '720708', 'เนินพระปรางค์   ', 803, 57, 2),
(7195, '720709', 'บ้านช้าง   ', 803, 57, 2),
(7196, '720710', 'ต้นตาล   ', 803, 57, 2),
(7197, '720711', 'ศรีสำราญ   ', 803, 57, 2),
(7198, '720712', 'ทุ่งคอก   ', 803, 57, 2),
(7199, '720713', 'หนองบ่อ   ', 803, 57, 2),
(7200, '720714', 'บ่อสุพรรณ   ', 803, 57, 2),
(7201, '720715', 'ดอนมะนาว   ', 803, 57, 2),
(7202, '720801', 'ย่านยาว   ', 804, 57, 2),
(7203, '720802', 'วังลึก   ', 804, 57, 2),
(7204, '720803', 'สามชุก   ', 804, 57, 2),
(7205, '720804', 'หนองผักนาก   ', 804, 57, 2),
(7206, '720805', 'บ้านสระ   ', 804, 57, 2),
(7207, '720806', 'หนองสะเดา   ', 804, 57, 2),
(7208, '720807', 'กระเสียว   ', 804, 57, 2),
(7209, '720896', '*แจงงาม   ', 804, 57, 2),
(7210, '720897', '*หนองโพธิ์   ', 804, 57, 2),
(7211, '720898', '*หนองราชวัตร   ', 804, 57, 2),
(7212, '720899', '*หนองหญ้าไซ   ', 804, 57, 2),
(7213, '720901', 'อู่ทอง   ', 805, 57, 2),
(7214, '720902', 'สระยายโสม   ', 805, 57, 2),
(7215, '720903', 'จรเข้สามพัน   ', 805, 57, 2),
(7216, '720904', 'บ้านดอน   ', 805, 57, 2),
(7217, '720905', 'ยุ้งทะลาย   ', 805, 57, 2),
(7218, '720906', 'ดอนมะเกลือ   ', 805, 57, 2),
(7219, '720907', 'หนองโอ่ง   ', 805, 57, 2),
(7220, '720908', 'ดอนคา   ', 805, 57, 2),
(7221, '720909', 'พลับพลาไชย   ', 805, 57, 2),
(7222, '720910', 'บ้านโข้ง   ', 805, 57, 2),
(7223, '720911', 'เจดีย์   ', 805, 57, 2),
(7224, '720912', 'สระพังลาน   ', 805, 57, 2),
(7225, '720913', 'กระจัน   ', 805, 57, 2),
(7226, '721001', 'หนองหญ้าไซ   ', 806, 57, 2),
(7227, '721002', 'หนองราชวัตร   ', 806, 57, 2),
(7228, '721003', 'หนองโพธิ์   ', 806, 57, 2),
(7229, '721004', 'แจงงาม   ', 806, 57, 2),
(7230, '721005', 'หนองขาม   ', 806, 57, 2),
(7231, '721006', 'ทัพหลวง   ', 806, 57, 2),
(7232, '730101', 'พระปฐมเจดีย์   ', 807, 58, 2),
(7233, '730102', 'บางแขม   ', 807, 58, 2),
(7234, '730103', 'พระประโทน   ', 807, 58, 2),
(7235, '730104', 'ธรรมศาลา   ', 807, 58, 2),
(7236, '730105', 'ตาก้อง   ', 807, 58, 2),
(7237, '730106', 'มาบแค   ', 807, 58, 2),
(7238, '730107', 'สนามจันทร์   ', 807, 58, 2),
(7239, '730108', 'ดอนยายหอม   ', 807, 58, 2),
(7240, '730109', 'ถนนขาด   ', 807, 58, 2),
(7241, '730110', 'บ่อพลับ   ', 807, 58, 2),
(7242, '730111', 'นครปฐม   ', 807, 58, 2),
(7243, '730112', 'วังตะกู   ', 807, 58, 2),
(7244, '730113', 'หนองปากโลง   ', 807, 58, 2),
(7245, '730114', 'สามควายเผือก   ', 807, 58, 2),
(7246, '730115', 'ทุ่งน้อย   ', 807, 58, 2),
(7247, '730116', 'หนองดินแดง   ', 807, 58, 2),
(7248, '730117', 'วังเย็น   ', 807, 58, 2),
(7249, '730118', 'โพรงมะเดื่อ   ', 807, 58, 2),
(7250, '730119', 'ลำพยา   ', 807, 58, 2),
(7251, '730120', 'สระกะเทียม   ', 807, 58, 2),
(7252, '730121', 'สวนป่าน   ', 807, 58, 2),
(7253, '730122', 'ห้วยจรเข้   ', 807, 58, 2),
(7254, '730123', 'ทัพหลวง   ', 807, 58, 2),
(7255, '730124', 'หนองงูเหลือม   ', 807, 58, 2),
(7256, '730125', 'บ้านยาง   ', 807, 58, 2),
(7257, '730201', 'ทุ่งกระพังโหม   ', 808, 58, 2),
(7258, '730202', 'กระตีบ   ', 808, 58, 2),
(7259, '730203', 'ทุ่งลูกนก   ', 808, 58, 2),
(7260, '730204', 'ห้วยขวาง   ', 808, 58, 2),
(7261, '730205', 'ทุ่งขวาง   ', 808, 58, 2),
(7262, '730206', 'สระสี่มุม   ', 808, 58, 2),
(7263, '730207', 'ทุ่งบัว   ', 808, 58, 2),
(7264, '730208', 'ดอนข่อย   ', 808, 58, 2),
(7265, '730209', 'สระพัฒนา   ', 808, 58, 2),
(7266, '730210', 'ห้วยหมอนทอง   ', 808, 58, 2),
(7267, '730211', 'ห้วยม่วง   ', 808, 58, 2),
(7268, '730212', 'กำแพงแสน   ', 808, 58, 2),
(7269, '730213', 'รางพิกุล   ', 808, 58, 2),
(7270, '730214', 'หนองกระทุ่ม   ', 808, 58, 2),
(7271, '730215', 'วังน้ำเขียว   ', 808, 58, 2),
(7272, '730301', 'นครชัยศรี   ', 809, 58, 2),
(7273, '730302', 'บางกระเบา   ', 809, 58, 2),
(7274, '730303', 'วัดแค   ', 809, 58, 2),
(7275, '730304', 'ท่าตำหนัก   ', 809, 58, 2),
(7276, '730305', 'บางแก้ว   ', 809, 58, 2),
(7277, '730306', 'ท่ากระชับ   ', 809, 58, 2),
(7278, '730307', 'ขุนแก้ว   ', 809, 58, 2),
(7279, '730308', 'ท่าพระยา   ', 809, 58, 2),
(7280, '730309', 'พะเนียด   ', 809, 58, 2),
(7281, '730310', 'บางระกำ   ', 809, 58, 2),
(7282, '730311', 'โคกพระเจดีย์   ', 809, 58, 2),
(7283, '730312', 'ศรีษะทอง   ', 809, 58, 2),
(7284, '730313', 'แหลมบัว   ', 809, 58, 2),
(7285, '730314', 'ศรีมหาโพธิ์   ', 809, 58, 2),
(7286, '730315', 'สัมปทวน   ', 809, 58, 2),
(7287, '730316', 'วัดสำโรง   ', 809, 58, 2),
(7288, '730317', 'ดอนแฝก   ', 809, 58, 2),
(7289, '730318', 'ห้วยพลู   ', 809, 58, 2),
(7290, '730319', 'วัดละมุด   ', 809, 58, 2),
(7291, '730320', 'บางพระ   ', 809, 58, 2),
(7292, '730321', 'บางแก้วฟ้า   ', 809, 58, 2),
(7293, '730322', 'ลานตากฟ้า   ', 809, 58, 2),
(7294, '730323', 'งิ้วราย   ', 809, 58, 2),
(7295, '730324', 'ไทยาวาส   ', 809, 58, 2),
(7296, '730325', '*ศาลายา   ', 809, 58, 2),
(7297, '730326', '*มหาสวัสดิ์   ', 809, 58, 2),
(7298, '730327', '*คลองโยง   ', 809, 58, 2),
(7299, '730397', '*มหาสวัสดิ์   ', 809, 58, 2),
(7300, '730398', '*คลองโยง   ', 809, 58, 2),
(7301, '730399', '*ศาลายา   ', 809, 58, 2),
(7302, '730401', 'สามง่าม   ', 810, 58, 2),
(7303, '730402', 'ห้วยพระ   ', 810, 58, 2),
(7304, '730403', 'ลำเหย   ', 810, 58, 2),
(7305, '730404', 'ดอนพุทรา   ', 810, 58, 2),
(7306, '730405', 'บ้านหลวง   ', 810, 58, 2),
(7307, '730406', 'ดอนรวก   ', 810, 58, 2),
(7308, '730407', 'ห้วยด้วน   ', 810, 58, 2),
(7309, '730408', 'ลำลูกบัว   ', 810, 58, 2),
(7310, '730501', 'บางเลน   ', 811, 58, 2),
(7311, '730502', 'บางปลา   ', 811, 58, 2),
(7312, '730503', 'บางหลวง   ', 811, 58, 2),
(7313, '730504', 'บางภาษี   ', 811, 58, 2),
(7314, '730505', 'บางระกำ   ', 811, 58, 2),
(7315, '730506', 'บางไทรป่า   ', 811, 58, 2),
(7316, '730507', 'หินมูล   ', 811, 58, 2),
(7317, '730508', 'ไทรงาม   ', 811, 58, 2),
(7318, '730509', 'ดอนตูม   ', 811, 58, 2),
(7319, '730510', 'นิลเพชร   ', 811, 58, 2),
(7320, '730511', 'บัวปากท่า   ', 811, 58, 2),
(7321, '730512', 'คลองนกกระทุง   ', 811, 58, 2),
(7322, '730513', 'นราภิรมย์   ', 811, 58, 2),
(7323, '730514', 'ลำพญา   ', 811, 58, 2),
(7324, '730515', 'ไผ่หูช้าง   ', 811, 58, 2),
(7325, '730601', 'ท่าข้าม   ', 812, 58, 2),
(7326, '730602', 'ทรงคนอง   ', 812, 58, 2),
(7327, '730603', 'หอมเกร็ด   ', 812, 58, 2),
(7328, '730604', 'บางกระทึก   ', 812, 58, 2),
(7329, '730605', 'บางเตย   ', 812, 58, 2),
(7330, '730606', 'สามพราน   ', 812, 58, 2),
(7331, '730607', 'บางช้าง   ', 812, 58, 2),
(7332, '730608', 'ไร่ขิง   ', 812, 58, 2),
(7333, '730609', 'ท่าตลาด   ', 812, 58, 2),
(7334, '730610', 'กระทุ่มล้ม   ', 812, 58, 2),
(7335, '730611', 'คลองใหม่   ', 812, 58, 2),
(7336, '730612', 'ตลาดจินดา   ', 812, 58, 2),
(7337, '730613', 'คลองจินดา   ', 812, 58, 2),
(7338, '730614', 'ยายชา   ', 812, 58, 2),
(7339, '730615', 'บ้านใหม่   ', 812, 58, 2),
(7340, '730616', 'อ้อมใหญ่   ', 812, 58, 2),
(7341, '730701', 'ศาลายา   ', 813, 58, 2),
(7342, '730702', 'คลองโยง   ', 813, 58, 2),
(7343, '730703', 'มหาสวัสดิ์   ', 813, 58, 2),
(7344, '740101', 'มหาชัย   ', 814, 59, 2),
(7345, '740102', 'ท่าฉลอม   ', 814, 59, 2),
(7346, '740103', 'โกรกกราก   ', 814, 59, 2),
(7347, '740104', 'บ้านบ่อ   ', 814, 59, 2),
(7348, '740105', 'บางโทรัด   ', 814, 59, 2),
(7349, '740106', 'กาหลง   ', 814, 59, 2),
(7350, '740107', 'นาโคก   ', 814, 59, 2),
(7351, '740108', 'ท่าจีน   ', 814, 59, 2),
(7352, '740109', 'นาดี   ', 814, 59, 2),
(7353, '740110', 'ท่าทราย   ', 814, 59, 2),
(7354, '740111', 'คอกกระบือ   ', 814, 59, 2),
(7355, '740112', 'บางน้ำจืด   ', 814, 59, 2),
(7356, '740113', 'พันท้ายนรสิงห์   ', 814, 59, 2),
(7357, '740114', 'โคกขาม   ', 814, 59, 2),
(7358, '740115', 'บ้านเกาะ   ', 814, 59, 2),
(7359, '740116', 'บางกระเจ้า   ', 814, 59, 2),
(7360, '740117', 'บางหญ้าแพรก   ', 814, 59, 2),
(7361, '740118', 'ชัยมงคล   ', 814, 59, 2),
(7362, '740201', 'ตลาดกระทุ่มแบน   ', 815, 59, 2),
(7363, '740202', 'อ้อมน้อย   ', 815, 59, 2),
(7364, '740203', 'ท่าไม้   ', 815, 59, 2),
(7365, '740204', 'สวนหลวง   ', 815, 59, 2),
(7366, '740205', 'บางยาง   ', 815, 59, 2),
(7367, '740206', 'คลองมะเดื่อ   ', 815, 59, 2),
(7368, '740207', 'หนองนกไข่   ', 815, 59, 2),
(7369, '740208', 'ดอนไก่ดี   ', 815, 59, 2),
(7370, '740209', 'แคราย   ', 815, 59, 2),
(7371, '740210', 'ท่าเสา   ', 815, 59, 2),
(7372, '740301', 'บ้านแพ้ว   ', 816, 59, 2),
(7373, '740302', 'หลักสาม   ', 816, 59, 2),
(7374, '740303', 'ยกกระบัตร   ', 816, 59, 2),
(7375, '740304', 'โรงเข้   ', 816, 59, 2),
(7376, '740305', 'หนองสองห้อง   ', 816, 59, 2),
(7377, '740306', 'หนองบัว   ', 816, 59, 2),
(7378, '740307', 'หลักสอง   ', 816, 59, 2),
(7379, '740308', 'เจ็ดริ้ว   ', 816, 59, 2),
(7380, '740309', 'คลองตัน   ', 816, 59, 2),
(7381, '740310', 'อำแพง   ', 816, 59, 2),
(7382, '740311', 'สวนส้ม   ', 816, 59, 2),
(7383, '740312', 'เกษตรพัฒนา   ', 816, 59, 2),
(7384, '750101', 'แม่กลอง   ', 817, 60, 2),
(7385, '750102', 'บางขันแตก   ', 817, 60, 2),
(7386, '750103', 'ลาดใหญ่   ', 817, 60, 2),
(7387, '750104', 'บ้านปรก   ', 817, 60, 2),
(7388, '750105', 'บางแก้ว   ', 817, 60, 2),
(7389, '750106', 'ท้ายหาด   ', 817, 60, 2),
(7390, '750107', 'แหลมใหญ่   ', 817, 60, 2),
(7391, '750108', 'คลองเขิน   ', 817, 60, 2),
(7392, '750109', 'คลองโคน   ', 817, 60, 2),
(7393, '750110', 'นางตะเคียน   ', 817, 60, 2),
(7394, '750111', 'บางจะเกร็ง   ', 817, 60, 2),
(7395, '750201', 'กระดังงา   ', 818, 60, 2),
(7396, '750202', 'บางสะแก   ', 818, 60, 2),
(7397, '750203', 'บางยี่รงค์   ', 818, 60, 2),
(7398, '750204', 'โรงหีบ   ', 818, 60, 2),
(7399, '750205', 'บางคนที   ', 818, 60, 2),
(7400, '750206', 'ดอนมะโนรา   ', 818, 60, 2),
(7401, '750207', 'บางพรม   ', 818, 60, 2),
(7402, '750208', 'บางกุ้ง   ', 818, 60, 2),
(7403, '750209', 'จอมปลวก   ', 818, 60, 2),
(7404, '750210', 'บางนกแขวก   ', 818, 60, 2),
(7405, '750211', 'ยายแพง   ', 818, 60, 2),
(7406, '750212', 'บางกระบือ   ', 818, 60, 2),
(7407, '750213', 'บ้านปราโมทย์   ', 818, 60, 2),
(7408, '750301', 'อัมพวา   ', 819, 60, 2),
(7409, '750302', 'สวนหลวง   ', 819, 60, 2),
(7410, '750303', 'ท่าคา   ', 819, 60, 2),
(7411, '750304', 'วัดประดู่   ', 819, 60, 2),
(7412, '750305', 'เหมืองใหม่   ', 819, 60, 2),
(7413, '750306', 'บางช้าง   ', 819, 60, 2),
(7414, '750307', 'แควอ้อม   ', 819, 60, 2),
(7415, '750308', 'ปลายโพงพาง   ', 819, 60, 2),
(7416, '750309', 'บางแค   ', 819, 60, 2),
(7417, '750310', 'แพรกหนามแดง   ', 819, 60, 2),
(7418, '750311', 'ยี่สาร   ', 819, 60, 2),
(7419, '750312', 'บางนางลี่   ', 819, 60, 2),
(7420, '760101', 'ท่าราบ   ', 820, 61, 4),
(7421, '760102', 'คลองกระแชง   ', 820, 61, 4),
(7422, '760103', 'บางจาน   ', 820, 61, 4),
(7423, '760104', 'นาพันสาม   ', 820, 61, 4),
(7424, '760105', 'ธงชัย   ', 820, 61, 4),
(7425, '760106', 'บ้านกุ่ม   ', 820, 61, 4),
(7426, '760107', 'หนองโสน   ', 820, 61, 4),
(7427, '760108', 'ไร่ส้ม   ', 820, 61, 4),
(7428, '760109', 'เวียงคอย   ', 820, 61, 4),
(7429, '760110', 'บางจาก   ', 820, 61, 4),
(7430, '760111', 'บ้านหม้อ   ', 820, 61, 4),
(7431, '760112', 'ต้นมะม่วง   ', 820, 61, 4),
(7432, '760113', 'ช่องสะแก   ', 820, 61, 4),
(7433, '760114', 'นาวุ้ง   ', 820, 61, 4),
(7434, '760115', 'สำมะโรง   ', 820, 61, 4),
(7435, '760116', 'โพพระ   ', 820, 61, 4),
(7436, '760117', 'หาดเจ้าสำราญ   ', 820, 61, 4),
(7437, '760118', 'หัวสะพาน   ', 820, 61, 4),
(7438, '760119', 'ต้นมะพร้าว   ', 820, 61, 4),
(7439, '760120', 'วังตะโก   ', 820, 61, 4),
(7440, '760121', 'โพไร่หวาน   ', 820, 61, 4),
(7441, '760122', 'ดอนยาง   ', 820, 61, 4),
(7442, '760123', 'หนองขนาน   ', 820, 61, 4),
(7443, '760124', 'หนองพลับ   ', 820, 61, 4),
(7444, '760199', '*มาตยาวงศ์   ', 820, 61, 4),
(7445, '760201', 'เขาย้อย   ', 821, 61, 4),
(7446, '760202', 'สระพัง   ', 821, 61, 4),
(7447, '760203', 'บางเค็ม   ', 821, 61, 4),
(7448, '760204', 'ทับคาง   ', 821, 61, 4),
(7449, '760205', 'หนองปลาไหล   ', 821, 61, 4),
(7450, '760206', 'หนองปรง   ', 821, 61, 4),
(7451, '760207', 'หนองชุมพล   ', 821, 61, 4),
(7452, '760208', 'ห้วยโรง   ', 821, 61, 4),
(7453, '760209', 'ห้วยท่าช้าง   ', 821, 61, 4),
(7454, '760210', 'หนองชุมพลเหนือ   ', 821, 61, 4),
(7455, '760297', '*ยางน้ำกลักใต้   ', 821, 61, 4),
(7456, '760298', '*ยางน้ำกลักเหนือ   ', 821, 61, 4),
(7457, '760299', '*หนองหญ้าปล้อง   ', 821, 61, 4),
(7458, '760301', 'หนองหญ้าปล้อง   ', 822, 61, 4),
(7459, '760302', 'ยางน้ำกลัดเหนือ   ', 822, 61, 4),
(7460, '760303', 'ยางน้ำกลัดใต้   ', 822, 61, 4),
(7461, '760304', 'ท่าตะคร้อ   ', 822, 61, 4),
(7462, '760401', 'ชะอำ   ', 823, 61, 4),
(7463, '760402', 'บางเก่า   ', 823, 61, 4),
(7464, '760403', 'นายาง   ', 823, 61, 4),
(7465, '760404', 'เขาใหญ่   ', 823, 61, 4),
(7466, '760405', 'หนองศาลา   ', 823, 61, 4),
(7467, '760406', 'ห้วยทรายเหนือ   ', 823, 61, 4),
(7468, '760407', 'ไร่ใหม่พัฒนา   ', 823, 61, 4),
(7469, '760408', 'สามพระยา   ', 823, 61, 4),
(7470, '760409', 'ดอนขุนห้วย   ', 823, 61, 4),
(7471, '760501', 'ท่ายาง   ', 824, 61, 4),
(7472, '760502', 'ท่าคอย   ', 824, 61, 4),
(7473, '760503', 'ยางหย่อง   ', 824, 61, 4),
(7474, '760504', 'หนองจอก   ', 824, 61, 4),
(7475, '760505', 'มาบปลาเค้า   ', 824, 61, 4),
(7476, '760506', 'ท่าไม้รวก   ', 824, 61, 4),
(7477, '760507', 'วังไคร้   ', 824, 61, 4),
(7478, '760508', '*วังจันทร์   ', 824, 61, 4),
(7479, '760509', '*สองพี่น้อง   ', 824, 61, 4),
(7480, '760510', '*แก่งกระจาน   ', 824, 61, 4),
(7481, '760511', 'กลัดหลวง   ', 824, 61, 4),
(7482, '760512', 'ปึกเตียน   ', 824, 61, 4),
(7483, '760513', 'เขากระปุก   ', 824, 61, 4),
(7484, '760514', 'ท่าแลง   ', 824, 61, 4),
(7485, '760515', 'บ้านในดง   ', 824, 61, 4),
(7486, '760594', '*สระปลาดู่   ', 824, 61, 4),
(7487, '760595', '*บางเมือง   ', 824, 61, 4),
(7488, '760596', '*นาไพร   ', 824, 61, 4),
(7489, '760597', '*วังจันทร์   ', 824, 61, 4),
(7490, '760598', '*สองพี่น้อง   ', 824, 61, 4),
(7491, '760599', '*แก่งกระจาน   ', 824, 61, 4),
(7492, '760601', 'บ้านลาด   ', 825, 61, 4),
(7493, '760602', 'บ้านหาด   ', 825, 61, 4),
(7494, '760603', 'บ้านทาน   ', 825, 61, 4),
(7495, '760604', 'ตำหรุ   ', 825, 61, 4),
(7496, '760605', 'สมอพลือ   ', 825, 61, 4),
(7497, '760606', 'ไร่มะขาม   ', 825, 61, 4),
(7498, '760607', 'ท่าเสน   ', 825, 61, 4),
(7499, '760608', 'หนองกระเจ็ด   ', 825, 61, 4),
(7500, '760609', 'หนองกะปุ   ', 825, 61, 4),
(7501, '760610', 'ลาดโพธิ์   ', 825, 61, 4),
(7502, '760611', 'สะพานไกร   ', 825, 61, 4),
(7503, '760612', 'ไร่โคก   ', 825, 61, 4),
(7504, '760613', 'โรงเข้   ', 825, 61, 4),
(7505, '760614', 'ไร่สะท้อน   ', 825, 61, 4),
(7506, '760615', 'ห้วยข้อง   ', 825, 61, 4),
(7507, '760616', 'ท่าช้าง   ', 825, 61, 4),
(7508, '760617', 'ถ้ำรงค์   ', 825, 61, 4),
(7509, '760618', 'ห้วยลึก   ', 825, 61, 4),
(7510, '760701', 'บ้านแหลม   ', 826, 61, 4),
(7511, '760702', 'บางขุนไทร   ', 826, 61, 4),
(7512, '760703', 'ปากทะเล   ', 826, 61, 4),
(7513, '760704', 'บางแก้ว   ', 826, 61, 4),
(7514, '760705', 'แหลมผักเบี้ย   ', 826, 61, 4),
(7515, '760706', 'บางตะบูน   ', 826, 61, 4),
(7516, '760707', 'บางตะบูนออก   ', 826, 61, 4),
(7517, '760708', 'บางครก   ', 826, 61, 4),
(7518, '760709', 'ท่าแร้ง   ', 826, 61, 4),
(7519, '760710', 'ท่าแร้งออก   ', 826, 61, 4),
(7520, '760801', 'แก่งกระจาน   ', 827, 61, 4),
(7521, '760802', 'สองพี่น้อง   ', 827, 61, 4),
(7522, '760803', 'วังจันทร์   ', 827, 61, 4),
(7523, '760804', 'ป่าเด็ง   ', 827, 61, 4),
(7524, '760805', 'พุสวรรค์   ', 827, 61, 4),
(7525, '760806', 'ห้วยแม่เพรียง   ', 827, 61, 4),
(7526, '770101', 'ประจวบคีรีขันธ์   ', 828, 62, 4),
(7527, '770102', 'เกาะหลัก   ', 828, 62, 4),
(7528, '770103', 'คลองวาฬ   ', 828, 62, 4),
(7529, '770104', 'ห้วยทราย   ', 828, 62, 4),
(7530, '770105', 'อ่าวน้อย   ', 828, 62, 4),
(7531, '770106', 'บ่อนอก   ', 828, 62, 4),
(7532, '770201', 'กุยบุรี   ', 829, 62, 4),
(7533, '770202', 'กุยเหนือ   ', 829, 62, 4),
(7534, '770203', 'เขาแดง   ', 829, 62, 4),
(7535, '770204', 'ดอนยายหนู   ', 829, 62, 4),
(7536, '770205', 'ไร่ใหม่*   ', 829, 62, 4),
(7537, '770206', 'สามกระทาย   ', 829, 62, 4),
(7538, '770207', 'หาดขาม   ', 829, 62, 4),
(7539, '770301', 'ทับสะแก   ', 830, 62, 4),
(7540, '770302', 'อ่างทอง   ', 830, 62, 4),
(7541, '770303', 'นาหูกวาง   ', 830, 62, 4),
(7542, '770304', 'เขาล้าน   ', 830, 62, 4),
(7543, '770305', 'ห้วยยาง   ', 830, 62, 4),
(7544, '770306', 'แสงอรุณ   ', 830, 62, 4),
(7545, '770401', 'กำเนิดนพคุณ   ', 831, 62, 4),
(7546, '770402', 'พงศ์ประศาสน์   ', 831, 62, 4),
(7547, '770403', 'ร่อนทอง   ', 831, 62, 4),
(7548, '770404', 'ธงชัย   ', 831, 62, 4),
(7549, '770405', 'ชัยเกษม   ', 831, 62, 4),
(7550, '770406', 'ทองมงคล   ', 831, 62, 4),
(7551, '770407', 'แม่รำพึง   ', 831, 62, 4),
(7552, '770501', 'ปากแพรก   ', 832, 62, 4),
(7553, '770502', 'บางสะพาน   ', 832, 62, 4),
(7554, '770503', 'ทรายทอง   ', 832, 62, 4),
(7555, '770504', 'ช้างแรก   ', 832, 62, 4),
(7556, '770505', 'ไชยราช   ', 832, 62, 4),
(7557, '770601', 'ปราณบุรี   ', 833, 62, 4),
(7558, '770602', 'เขาน้อย   ', 833, 62, 4),
(7559, '770603', '*ศิลาลอย   ', 833, 62, 4),
(7560, '770604', 'ปากน้ำปราณ   ', 833, 62, 4),
(7561, '770605', '*สามร้อยยอด   ', 833, 62, 4),
(7562, '770606', '*ไร่เก่า   ', 833, 62, 4),
(7563, '770607', 'หนองตาแต้ม   ', 833, 62, 4),
(7564, '770608', 'วังก์พง   ', 833, 62, 4),
(7565, '770609', 'เขาจ้าว   ', 833, 62, 4),
(7566, '770701', 'หัวหิน   ', 834, 62, 4),
(7567, '770702', 'หนองแก   ', 834, 62, 4),
(7568, '770703', 'หินเหล็กไฟ   ', 834, 62, 4),
(7569, '770704', 'หนองพลับ   ', 834, 62, 4),
(7570, '770705', 'ทับใต้   ', 834, 62, 4),
(7571, '770706', 'ห้วยสัตว์ใหญ่   ', 834, 62, 4),
(7572, '770707', 'บึงนคร   ', 834, 62, 4),
(7573, '770801', 'สามร้อยยอด   ', 835, 62, 4),
(7574, '770802', 'ศิลาลอย   ', 835, 62, 4),
(7575, '770803', 'ไร่เก่า   ', 835, 62, 4),
(7576, '770804', 'ศาลาลัย   ', 835, 62, 4),
(7577, '770805', 'ไร่ใหม่   ', 835, 62, 4),
(7578, '800101', 'ในเมือง   ', 836, 63, 6),
(7579, '800102', 'ท่าวัง   ', 836, 63, 6),
(7580, '800103', 'คลัง   ', 836, 63, 6),
(7581, '800104', '*นา   ', 836, 63, 6),
(7582, '800105', '*ศาลามีชัย   ', 836, 63, 6),
(7583, '800106', 'ท่าไร่   ', 836, 63, 6),
(7584, '800107', 'ปากนคร   ', 836, 63, 6),
(7585, '800108', 'นาทราย   ', 836, 63, 6),
(7586, '800109', '*นาพรุ   ', 836, 63, 6),
(7587, '800110', '*ช้างซ้าย   ', 836, 63, 6),
(7588, '800111', '*นาสาร   ', 836, 63, 6),
(7589, '800112', 'กำแพงเซา   ', 836, 63, 6),
(7590, '800113', 'ไชยมนตรี   ', 836, 63, 6),
(7591, '800114', 'มะม่วงสองต้น   ', 836, 63, 6),
(7592, '800115', 'นาเคียน   ', 836, 63, 6),
(7593, '800116', 'ท่างิ้ว   ', 836, 63, 6),
(7594, '800117', '*ท้ายสำเภา   ', 836, 63, 6),
(7595, '800118', 'โพธิ์เสด็จ   ', 836, 63, 6),
(7596, '800119', 'บางจาก   ', 836, 63, 6),
(7597, '800120', 'ปากพูน   ', 836, 63, 6),
(7598, '800121', 'ท่าซัก   ', 836, 63, 6),
(7599, '800122', 'ท่าเรือ   ', 836, 63, 6),
(7600, '800195', '*อินคีรี   ', 836, 63, 6),
(7601, '800196', '*พรหมโลก   ', 836, 63, 6),
(7602, '800197', '*ศาลามีชัย   ', 836, 63, 6),
(7603, '800198', '*นา   ', 836, 63, 6),
(7604, '800199', '*บ้านเกาะ   ', 836, 63, 6),
(7605, '800201', 'พรหมโลก   ', 837, 63, 6),
(7606, '800202', 'บ้านเกาะ   ', 837, 63, 6),
(7607, '800203', 'อินคีรี   ', 837, 63, 6),
(7608, '800204', 'ทอนหงส์   ', 837, 63, 6),
(7609, '800205', 'นาเรียง   ', 837, 63, 6),
(7610, '800301', 'เขาแก้ว   ', 838, 63, 6),
(7611, '800302', 'ลานสกา   ', 838, 63, 6),
(7612, '800303', 'ท่าดี   ', 838, 63, 6),
(7613, '800304', 'กำโลน   ', 838, 63, 6),
(7614, '800305', 'ขุนทะเล   ', 838, 63, 6),
(7615, '800401', 'ฉวาง   ', 839, 63, 6),
(7616, '800402', 'ช้างกลาง*   ', 839, 63, 6),
(7617, '800403', 'ละอาย   ', 839, 63, 6),
(7618, '800404', 'นาแว   ', 839, 63, 6),
(7619, '800405', 'ไม้เรียง   ', 839, 63, 6),
(7620, '800406', 'กะเปียด   ', 839, 63, 6),
(7621, '800407', 'นากะชะ   ', 839, 63, 6),
(7622, '800408', '*ถ้ำพรรณรา   ', 839, 63, 6),
(7623, '800409', 'ห้วยปริก   ', 839, 63, 6),
(7624, '800410', 'ไสหร้า   ', 839, 63, 6),
(7625, '800411', 'หลักช้าง*   ', 839, 63, 6),
(7626, '800412', 'สวนขัน*   ', 839, 63, 6),
(7627, '800413', '*คลองเส   ', 839, 63, 6),
(7628, '800414', '*ดุสิต   ', 839, 63, 6),
(7629, '800415', 'นาเขลียง   ', 839, 63, 6),
(7630, '800416', 'จันดี   ', 839, 63, 6),
(7631, '800501', 'พิปูน   ', 840, 63, 6),
(7632, '800502', 'กะทูน   ', 840, 63, 6),
(7633, '800503', 'เขาพระ   ', 840, 63, 6),
(7634, '800504', 'ยางค้อม   ', 840, 63, 6),
(7635, '800505', 'ควนกลาง   ', 840, 63, 6),
(7636, '800601', 'เชียรใหญ่   ', 841, 63, 6),
(7637, '800602', 'เชียรเขา*   ', 841, 63, 6),
(7638, '800603', 'ท่าขนาน   ', 841, 63, 6),
(7639, '800604', 'บ้านกลาง   ', 841, 63, 6),
(7640, '800605', 'บ้านเนิน   ', 841, 63, 6),
(7641, '800606', 'ไสหมาก   ', 841, 63, 6),
(7642, '800607', 'ท้องลำเจียก   ', 841, 63, 6),
(7643, '800608', 'ดอนตรอ*   ', 841, 63, 6),
(7644, '800609', 'สวนหลวง*   ', 841, 63, 6),
(7645, '800610', 'เสือหึง   ', 841, 63, 6),
(7646, '800611', 'การะเกด   ', 841, 63, 6),
(7647, '800612', 'เขาพระบาท   ', 841, 63, 6),
(7648, '800613', 'แม่เจ้าอยู่หัว   ', 841, 63, 6),
(7649, '800701', 'ชะอวด   ', 842, 63, 6),
(7650, '800702', 'ท่าเสม็ด   ', 842, 63, 6),
(7651, '800703', 'ท่าประจะ   ', 842, 63, 6),
(7652, '800704', 'เคร็ง   ', 842, 63, 6),
(7653, '800705', 'วังอ่าง   ', 842, 63, 6),
(7654, '800706', 'บ้านตูล   ', 842, 63, 6),
(7655, '800707', 'ขอนหาด   ', 842, 63, 6),
(7656, '800708', 'เกาะขันธ์   ', 842, 63, 6),
(7657, '800709', 'ควนหนองหงษ์   ', 842, 63, 6),
(7658, '800710', 'เขาพระทอง   ', 842, 63, 6),
(7659, '800711', 'นางหลง   ', 842, 63, 6),
(7660, '800712', '*บ้านควนมุด   ', 842, 63, 6),
(7661, '800713', '*บ้านชะอวด   ', 842, 63, 6),
(7662, '800801', 'ท่าศาลา   ', 843, 63, 6),
(7663, '800802', 'กลาย   ', 843, 63, 6),
(7664, '800803', 'ท่าขึ้น   ', 843, 63, 6),
(7665, '800804', 'หัวตะพาน   ', 843, 63, 6),
(7666, '800805', '*กะหรอ   ', 843, 63, 6),
(7667, '800806', 'สระแก้ว   ', 843, 63, 6),
(7668, '800807', 'โมคลาน   ', 843, 63, 6),
(7669, '800808', '*นบพิตำ   ', 843, 63, 6),
(7670, '800809', 'ไทยบุรี   ', 843, 63, 6),
(7671, '800810', 'ดอนตะโก   ', 843, 63, 6),
(7672, '800811', 'ตลิ่งชัน   ', 843, 63, 6),
(7673, '800812', '*กรุงชิง   ', 843, 63, 6),
(7674, '800813', 'โพธิ์ทอง   ', 843, 63, 6),
(7675, '800814', '*นาเหรง   ', 843, 63, 6),
(7676, '800901', 'ปากแพรก   ', 844, 63, 6),
(7677, '800902', 'ชะมาย   ', 844, 63, 6),
(7678, '800903', 'หนองหงส์   ', 844, 63, 6),
(7679, '800904', 'ควนกรด   ', 844, 63, 6),
(7680, '800905', 'นาไม้ไผ่   ', 844, 63, 6),
(7681, '800906', 'นาหลวงเสน   ', 844, 63, 6),
(7682, '800907', 'เขาโร   ', 844, 63, 6),
(7683, '800908', 'กะปาง   ', 844, 63, 6),
(7684, '800909', 'ที่วัง   ', 844, 63, 6),
(7685, '800910', 'น้ำตก   ', 844, 63, 6),
(7686, '800911', 'ถ้ำใหญ่   ', 844, 63, 6),
(7687, '800912', 'นาโพธิ์   ', 844, 63, 6),
(7688, '800913', 'เขาขาว   ', 844, 63, 6),
(7689, '800994', '*วังหิน   ', 844, 63, 6),
(7690, '800995', '*บ้านลำนาว   ', 844, 63, 6),
(7691, '800996', '*บางขัน   ', 844, 63, 6),
(7692, '800997', '*แก้วแสน   ', 844, 63, 6),
(7693, '800998', '*ทุ่งสง   ', 844, 63, 6),
(7694, '800999', '*นาบอน   ', 844, 63, 6),
(7695, '801001', 'นาบอน   ', 845, 63, 6),
(7696, '801002', 'ทุ่งสง   ', 845, 63, 6),
(7697, '801003', 'แก้วแสน   ', 845, 63, 6),
(7698, '801101', 'ท่ายาง   ', 846, 63, 6),
(7699, '801102', 'ทุ่งสัง   ', 846, 63, 6),
(7700, '801103', 'ทุ่งใหญ่   ', 846, 63, 6),
(7701, '801104', 'กุแหระ   ', 846, 63, 6),
(7702, '801105', 'ปริก   ', 846, 63, 6),
(7703, '801106', 'บางรูป   ', 846, 63, 6),
(7704, '801107', 'กรุงหยัน   ', 846, 63, 6),
(7705, '801201', 'ปากพนัง   ', 847, 63, 6),
(7706, '801202', 'คลองน้อย   ', 847, 63, 6),
(7707, '801203', 'ป่าระกำ   ', 847, 63, 6),
(7708, '801204', 'ชะเมา   ', 847, 63, 6),
(7709, '801205', 'คลองกระบือ   ', 847, 63, 6),
(7710, '801206', 'เกาะทวด   ', 847, 63, 6),
(7711, '801207', 'บ้านใหม่   ', 847, 63, 6),
(7712, '801208', 'หูล่อง   ', 847, 63, 6),
(7713, '801209', 'แหลมตะลุมพุก   ', 847, 63, 6),
(7714, '801210', 'ปากพนังฝั่งตะวันตก   ', 847, 63, 6),
(7715, '801211', 'บางศาลา   ', 847, 63, 6),
(7716, '801212', 'บางพระ   ', 847, 63, 6),
(7717, '801213', 'บางตะพง   ', 847, 63, 6),
(7718, '801214', 'ปากพนังฝั่งตะวันออก   ', 847, 63, 6),
(7719, '801215', 'บ้านเพิง   ', 847, 63, 6),
(7720, '801216', 'ท่าพยา   ', 847, 63, 6),
(7721, '801217', 'ปากแพรก   ', 847, 63, 6),
(7722, '801218', 'ขนาบนาก   ', 847, 63, 6),
(7723, '801301', 'ร่อนพิบูลย์   ', 848, 63, 6),
(7724, '801302', 'หินตก   ', 848, 63, 6),
(7725, '801303', 'เสาธง   ', 848, 63, 6),
(7726, '801304', 'ควนเกย   ', 848, 63, 6),
(7727, '801305', 'ควนพัง   ', 848, 63, 6),
(7728, '801306', 'ควนชุม   ', 848, 63, 6),
(7729, '801307', '*สามตำบล   ', 848, 63, 6),
(7730, '801308', 'ทางพูน*   ', 848, 63, 6),
(7731, '801309', '*นาหมอบุญ   ', 848, 63, 6),
(7732, '801310', '*ทุ่งโพธิ์   ', 848, 63, 6),
(7733, '801311', '*ควนหนองคว้า   ', 848, 63, 6),
(7734, '801401', 'สิชล   ', 849, 63, 6),
(7735, '801402', 'ทุ่งปรัง   ', 849, 63, 6),
(7736, '801403', 'ฉลอง   ', 849, 63, 6),
(7737, '801404', 'เสาเภา   ', 849, 63, 6),
(7738, '801405', 'เปลี่ยน   ', 849, 63, 6),
(7739, '801406', 'สี่ขีด   ', 849, 63, 6),
(7740, '801407', 'เทพราช   ', 849, 63, 6),
(7741, '801408', 'เขาน้อย   ', 849, 63, 6),
(7742, '801409', 'ทุ่งใส   ', 849, 63, 6),
(7743, '801501', 'ขนอม   ', 850, 63, 6),
(7744, '801502', 'ควนทอง   ', 850, 63, 6),
(7745, '801503', 'ท้องเนียน   ', 850, 63, 6),
(7746, '801601', 'หัวไทร   ', 851, 63, 6),
(7747, '801602', 'หน้าสตน   ', 851, 63, 6),
(7748, '801603', 'ทรายขาว   ', 851, 63, 6),
(7749, '801604', 'แหลม   ', 851, 63, 6),
(7750, '801605', 'เขาพังไกร   ', 851, 63, 6),
(7751, '801606', 'บ้านราม   ', 851, 63, 6),
(7752, '801607', 'บางนบ   ', 851, 63, 6),
(7753, '801608', 'ท่าซอม   ', 851, 63, 6),
(7754, '801609', 'ควนชะลิก   ', 851, 63, 6),
(7755, '801610', 'รามแก้ว   ', 851, 63, 6),
(7756, '801611', 'เกาะเพชร   ', 851, 63, 6),
(7757, '801701', 'บางขัน   ', 852, 63, 6),
(7758, '801702', 'บ้านลำนาว   ', 852, 63, 6),
(7759, '801703', 'วังหิน   ', 852, 63, 6),
(7760, '801704', 'บ้านนิคม   ', 852, 63, 6),
(7761, '801801', 'ถ้ำพรรณรา   ', 853, 63, 6),
(7762, '801802', 'คลองเส   ', 853, 63, 6),
(7763, '801803', 'ดุสิต   ', 853, 63, 6),
(7764, '801901', 'บ้านควนมุด   ', 854, 63, 6),
(7765, '801902', 'บ้านชะอวด   ', 854, 63, 6),
(7766, '801903', 'ควนหนองคว้า   ', 854, 63, 6),
(7767, '801904', 'ทุ่งโพธิ์   ', 854, 63, 6),
(7768, '801905', 'นาหมอบุญ   ', 854, 63, 6),
(7769, '801906', 'สามตำบล   ', 854, 63, 6),
(7770, '802001', 'นาพรุ   ', 855, 63, 6),
(7771, '802002', 'นาสาร   ', 855, 63, 6),
(7772, '802003', 'ท้ายสำเภา   ', 855, 63, 6),
(7773, '802004', 'ช้างซ้าย   ', 855, 63, 6),
(7774, '802101', 'นบพิตำ   ', 856, 63, 6),
(7775, '802102', 'กรุงชิง   ', 856, 63, 6),
(7776, '802103', 'กะหรอ   ', 856, 63, 6),
(7777, '802104', 'นาเหรง   ', 856, 63, 6),
(7778, '802201', 'ช้างกลาง   ', 857, 63, 6),
(7779, '802202', 'หลักช้าง   ', 857, 63, 6),
(7780, '802203', 'สวนขัน   ', 857, 63, 6),
(7781, '802301', 'เชียรเขา   ', 858, 63, 6),
(7782, '802302', 'ดอนตรอ   ', 858, 63, 6),
(7783, '802303', 'สวนหลวง   ', 858, 63, 6),
(7784, '802304', 'ทางพูน   ', 858, 63, 6),
(7785, '810101', 'ปากน้ำ   ', 864, 64, 6),
(7786, '810102', 'กระบี่ใหญ่   ', 864, 64, 6),
(7787, '810103', 'กระบี่น้อย   ', 864, 64, 6),
(7788, '810104', '*เกาะศรีบอยา   ', 864, 64, 6),
(7789, '810105', 'เขาคราม   ', 864, 64, 6),
(7790, '810106', 'เขาทอง   ', 864, 64, 6),
(7791, '810107', '*คลองขนาน   ', 864, 64, 6),
(7792, '810108', '*คลองเขม้า   ', 864, 64, 6),
(7793, '810109', '*โคกยาง   ', 864, 64, 6),
(7794, '810110', '*ตลิ่งชัน   ', 864, 64, 6),
(7795, '810111', 'ทับปริก   ', 864, 64, 6),
(7796, '810112', '*ปกาสัย   ', 864, 64, 6),
(7797, '810113', '*ห้วยยูง   ', 864, 64, 6),
(7798, '810114', '*เหนือคลอง   ', 864, 64, 6),
(7799, '810115', 'ไสไทย   ', 864, 64, 6),
(7800, '810116', 'อ่าวนาง   ', 864, 64, 6),
(7801, '810117', 'หนองทะเล   ', 864, 64, 6),
(7802, '810118', 'คลองประสงค์   ', 864, 64, 6),
(7803, '810192', '*เกาะศรีบายอ   ', 864, 64, 6),
(7804, '810193', '*คลองเขม้า   ', 864, 64, 6),
(7805, '810194', '*โคกยาง   ', 864, 64, 6),
(7806, '810195', '*ห้วยยูง   ', 864, 64, 6),
(7807, '810196', '*คลองขนาน   ', 864, 64, 6),
(7808, '810197', '*ตลิ่งชัน   ', 864, 64, 6),
(7809, '810198', '*ปกาสัย   ', 864, 64, 6),
(7810, '810199', '*เหนือคลอง   ', 864, 64, 6),
(7811, '810201', 'เขาพนม   ', 865, 64, 6),
(7812, '810202', 'เขาดิน   ', 865, 64, 6),
(7813, '810203', 'สินปุน   ', 865, 64, 6),
(7814, '810204', 'พรุเตียว   ', 865, 64, 6),
(7815, '810205', 'หน้าเขา   ', 865, 64, 6),
(7816, '810206', 'โคกหาร   ', 865, 64, 6),
(7817, '810301', 'เกาะลันตาใหญ่   ', 866, 64, 6),
(7818, '810302', 'เกาะลันตาน้อย   ', 866, 64, 6),
(7819, '810303', 'เกาะกลาง   ', 866, 64, 6),
(7820, '810304', 'คลองยาง   ', 866, 64, 6),
(7821, '810305', 'ศาลาด่าน   ', 866, 64, 6),
(7822, '810401', 'คลองท่อมใต้   ', 867, 64, 6),
(7823, '810402', 'คลองท่อมเหนือ   ', 867, 64, 6),
(7824, '810403', 'คลองพน   ', 867, 64, 6),
(7825, '810404', 'ทรายขาว   ', 867, 64, 6),
(7826, '810405', 'ห้วยน้ำขาว   ', 867, 64, 6),
(7827, '810406', 'พรุดินนา   ', 867, 64, 6),
(7828, '810407', 'เพหลา   ', 867, 64, 6),
(7829, '810499', 'ลำทับ*   ', 867, 64, 6),
(7830, '810501', 'อ่าวลึกใต้   ', 868, 64, 6),
(7831, '810502', 'แหลมสัก   ', 868, 64, 6),
(7832, '810503', 'นาเหนือ   ', 868, 64, 6),
(7833, '810504', 'คลองหิน   ', 868, 64, 6),
(7834, '810505', 'อ่าวลึกน้อย   ', 868, 64, 6),
(7835, '810506', 'อ่าวลึกเหนือ   ', 868, 64, 6),
(7836, '810507', 'เขาใหญ่   ', 868, 64, 6),
(7837, '810508', 'คลองยา   ', 868, 64, 6),
(7838, '810509', 'บ้านกลาง   ', 868, 64, 6),
(7839, '810597', '*เขาเขน   ', 868, 64, 6),
(7840, '810598', '*เขาต่อ   ', 868, 64, 6),
(7841, '810599', '*ปลายพระยา   ', 868, 64, 6),
(7842, '810601', 'ปลายพระยา   ', 869, 64, 6),
(7843, '810602', 'เขาเขน   ', 869, 64, 6),
(7844, '810603', 'เขาต่อ   ', 869, 64, 6),
(7845, '810604', 'คีรีวง   ', 869, 64, 6),
(7846, '810701', 'ลำทับ   ', 870, 64, 6),
(7847, '810702', 'ดินอุดม   ', 870, 64, 6),
(7848, '810703', 'ทุ่งไทรทอง   ', 870, 64, 6),
(7849, '810704', 'ดินแดง   ', 870, 64, 6),
(7850, '810801', 'เหนือคลอง   ', 871, 64, 6),
(7851, '810802', 'เกาะศรีบอยา   ', 871, 64, 6),
(7852, '810803', 'คลองขนาน   ', 871, 64, 6),
(7853, '810804', 'คลองเขม้า   ', 871, 64, 6),
(7854, '810805', 'โคกยาง   ', 871, 64, 6),
(7855, '810806', 'ตลิ่งชัน   ', 871, 64, 6),
(7856, '810807', 'ปกาสัย   ', 871, 64, 6),
(7857, '810808', 'ห้วยยูง   ', 871, 64, 6),
(7858, '820101', 'ท้ายช้าง   ', 872, 65, 6),
(7859, '820102', 'นบปริง   ', 872, 65, 6),
(7860, '820103', 'ถ้ำน้ำผุด   ', 872, 65, 6),
(7861, '820104', 'บางเตย   ', 872, 65, 6),
(7862, '820105', 'ตากแดด   ', 872, 65, 6),
(7863, '820106', 'สองแพรก   ', 872, 65, 6),
(7864, '820107', 'ทุ่งคาโงก   ', 872, 65, 6),
(7865, '820108', 'เกาะปันหยี   ', 872, 65, 6),
(7866, '820109', 'ป่ากอ   ', 872, 65, 6),
(7867, '820198', '*เกาะยาวใหญ่   ', 872, 65, 6),
(7868, '820199', '*เกาะยาวน้อย   ', 872, 65, 6),
(7869, '820201', 'เกาะยาวน้อย   ', 873, 65, 6),
(7870, '820202', 'เกาะยาวใหญ่   ', 873, 65, 6),
(7871, '820203', 'พรุใน   ', 873, 65, 6),
(7872, '820301', 'กะปง   ', 874, 65, 6),
(7873, '820302', 'ท่านา   ', 874, 65, 6),
(7874, '820303', 'เหมาะ   ', 874, 65, 6),
(7875, '820304', 'เหล   ', 874, 65, 6),
(7876, '820305', 'รมณีย์   ', 874, 65, 6),
(7877, '820401', 'ถ้ำ   ', 875, 65, 6),
(7878, '820402', 'กระโสม   ', 875, 65, 6),
(7879, '820403', 'กะไหล   ', 875, 65, 6),
(7880, '820404', 'ท่าอยู่   ', 875, 65, 6),
(7881, '820405', 'หล่อยูง   ', 875, 65, 6),
(7882, '820406', 'โคกกลอย   ', 875, 65, 6),
(7883, '820407', 'คลองเคียน   ', 875, 65, 6),
(7884, '820501', 'ตะกั่วป่า   ', 876, 65, 6),
(7885, '820502', 'บางนายสี   ', 876, 65, 6),
(7886, '820503', 'บางไทร   ', 876, 65, 6),
(7887, '820504', 'บางม่วง   ', 876, 65, 6),
(7888, '820505', 'ตำตัว   ', 876, 65, 6),
(7889, '820506', 'โคกเคียน   ', 876, 65, 6),
(7890, '820507', 'คึกคัก   ', 876, 65, 6),
(7891, '820508', 'เกาะคอเขา   ', 876, 65, 6),
(7892, '820601', 'คุระ   ', 877, 65, 6),
(7893, '820602', 'บางวัน   ', 877, 65, 6),
(7894, '820603', 'เกาะพระทอง   ', 877, 65, 6),
(7895, '820604', '*เกาะคอเขา   ', 877, 65, 6),
(7896, '820605', 'แม่นางขาว   ', 877, 65, 6),
(7897, '820701', 'ทับปุด   ', 878, 65, 6),
(7898, '820702', 'มะรุ่ย   ', 878, 65, 6),
(7899, '820703', 'บ่อแสน   ', 878, 65, 6),
(7900, '820704', 'ถ้ำทองหลาง   ', 878, 65, 6),
(7901, '820705', 'โคกเจริญ   ', 878, 65, 6),
(7902, '820706', 'บางเหรียง   ', 878, 65, 6),
(7903, '820801', 'ท้ายเหมือง   ', 879, 65, 6),
(7904, '820802', 'นาเตย   ', 879, 65, 6),
(7905, '820803', 'บางทอง   ', 879, 65, 6),
(7906, '820804', 'ทุ่งมะพร้าว   ', 879, 65, 6),
(7907, '820805', 'ลำภี   ', 879, 65, 6),
(7908, '820806', 'ลำแก่น   ', 879, 65, 6),
(7909, '830101', 'ตลาดใหญ่   ', 880, 66, 6),
(7910, '830102', 'ตลาดเหนือ   ', 880, 66, 6),
(7911, '830103', 'เกาะแก้ว   ', 880, 66, 6),
(7912, '830104', 'รัษฎา   ', 880, 66, 6),
(7913, '830105', 'วิชิต   ', 880, 66, 6),
(7914, '830106', 'ฉลอง   ', 880, 66, 6),
(7915, '830107', 'ราไวย์   ', 880, 66, 6),
(7916, '830108', 'กะรน   ', 880, 66, 6),
(7917, '830201', 'กะทู้   ', 881, 66, 6),
(7918, '830202', 'ป่าตอง   ', 881, 66, 6),
(7919, '830203', 'กมลา   ', 881, 66, 6),
(7920, '830301', 'เทพกระษัตรี   ', 882, 66, 6),
(7921, '830302', 'ศรีสุนทร   ', 882, 66, 6),
(7922, '830303', 'เชิงทะเล   ', 882, 66, 6),
(7923, '830304', 'ป่าคลอก   ', 882, 66, 6),
(7924, '830305', 'ไม้ขาว   ', 882, 66, 6),
(7925, '830306', 'สาคู   ', 882, 66, 6),
(7926, '840101', 'ตลาด   ', 884, 67, 6),
(7927, '840102', 'มะขามเตี้ย   ', 884, 67, 6),
(7928, '840103', 'วัดประดู่   ', 884, 67, 6),
(7929, '840104', 'ขุนทะเล   ', 884, 67, 6),
(7930, '840105', 'บางใบไม้   ', 884, 67, 6),
(7931, '840106', 'บางชนะ   ', 884, 67, 6),
(7932, '840107', 'คลองน้อย   ', 884, 67, 6),
(7933, '840108', 'บางไทร   ', 884, 67, 6),
(7934, '840109', 'บางโพธิ์   ', 884, 67, 6),
(7935, '840110', 'บางกุ้ง   ', 884, 67, 6),
(7936, '840111', 'คลองฉนาก   ', 884, 67, 6),
(7937, '840201', 'ท่าทองใหม่   ', 885, 67, 6),
(7938, '840202', 'ท่าทอง   ', 885, 67, 6),
(7939, '840203', 'กะแดะ   ', 885, 67, 6),
(7940, '840204', 'ทุ่งกง   ', 885, 67, 6),
(7941, '840205', 'กรูด   ', 885, 67, 6),
(7942, '840206', 'ช้างซ้าย   ', 885, 67, 6),
(7943, '840207', 'พลายวาส   ', 885, 67, 6),
(7944, '840208', 'ป่าร่อน   ', 885, 67, 6),
(7945, '840209', 'ตะเคียนทอง   ', 885, 67, 6),
(7946, '840210', 'ช้างขวา   ', 885, 67, 6),
(7947, '840211', 'ท่าอุแท   ', 885, 67, 6),
(7948, '840212', 'ทุ่งรัง   ', 885, 67, 6),
(7949, '840213', 'คลองสระ   ', 885, 67, 6),
(7950, '840301', 'ดอนสัก   ', 886, 67, 6),
(7951, '840302', 'ชลคราม   ', 886, 67, 6),
(7952, '840303', 'ไชยคราม   ', 886, 67, 6),
(7953, '840304', 'ปากแพรก   ', 886, 67, 6),
(7954, '840401', 'อ่างทอง   ', 887, 67, 6),
(7955, '840402', 'ลิปะน้อย   ', 887, 67, 6),
(7956, '840403', 'ตลิ่งงาม   ', 887, 67, 6),
(7957, '840404', 'หน้าเมือง   ', 887, 67, 6),
(7958, '840405', 'มะเร็ต   ', 887, 67, 6),
(7959, '840406', 'บ่อผุด   ', 887, 67, 6),
(7960, '840407', 'แม่น้ำ   ', 887, 67, 6),
(7961, '840501', 'เกาะพะงัน   ', 888, 67, 6),
(7962, '840502', 'บ้านใต้   ', 888, 67, 6),
(7963, '840503', 'เกาะเต่า   ', 888, 67, 6),
(7964, '840601', 'ตลาดไชยา   ', 889, 67, 6),
(7965, '840602', 'พุมเรียง   ', 889, 67, 6),
(7966, '840603', 'เลม็ด   ', 889, 67, 6),
(7967, '840604', 'เวียง   ', 889, 67, 6),
(7968, '840605', 'ทุ่ง   ', 889, 67, 6),
(7969, '840606', 'ป่าเว   ', 889, 67, 6),
(7970, '840607', 'ตะกรบ   ', 889, 67, 6),
(7971, '840608', 'โมถ่าย   ', 889, 67, 6),
(7972, '840609', 'ปากหมาก   ', 889, 67, 6),
(7973, '840701', 'ท่าชนะ   ', 890, 67, 6),
(7974, '840702', 'สมอทอง   ', 890, 67, 6),
(7975, '840703', 'ประสงค์   ', 890, 67, 6),
(7976, '840704', 'คันธุลี   ', 890, 67, 6),
(7977, '840705', 'วัง   ', 890, 67, 6),
(7978, '840706', 'คลองพา   ', 890, 67, 6),
(7979, '840801', 'ท่าขนอน   ', 891, 67, 6),
(7980, '840802', 'บ้านยาง   ', 891, 67, 6),
(7981, '840803', 'น้ำหัก   ', 891, 67, 6),
(7982, '840804', '*ตะกุกใต้   ', 891, 67, 6),
(7983, '840805', '*ตะกุกเหนือ   ', 891, 67, 6),
(7984, '840806', 'กะเปา   ', 891, 67, 6),
(7985, '840807', 'ท่ากระดาน   ', 891, 67, 6),
(7986, '840808', 'ย่านยาว   ', 891, 67, 6),
(7987, '840809', 'ถ้ำสิงขร   ', 891, 67, 6),
(7988, '840810', 'บ้านทำเนียบ   ', 891, 67, 6),
(7989, '840899', '*ตะกุดใต้   ', 891, 67, 6),
(7990, '840901', 'เขาวง   ', 892, 67, 6),
(7991, '840902', 'พะแสง   ', 892, 67, 6),
(7992, '840903', 'พรุไทย   ', 892, 67, 6),
(7993, '840904', 'เขาพัง   ', 892, 67, 6),
(7994, '840905', '*ไกรสร   ', 892, 67, 6),
(7995, '841001', 'พนม   ', 893, 67, 6),
(7996, '841002', 'ต้นยวน   ', 893, 67, 6),
(7997, '841003', 'คลองศก   ', 893, 67, 6),
(7998, '841004', 'พลูเถื่อน   ', 893, 67, 6),
(7999, '841005', 'พังกาญจน์   ', 893, 67, 6),
(8000, '841006', 'คลองชะอุ่น   ', 893, 67, 6),
(8001, '841101', 'ท่าฉาง   ', 894, 67, 6),
(8002, '841102', 'ท่าเคย   ', 894, 67, 6),
(8003, '841103', 'คลองไทร   ', 894, 67, 6),
(8004, '841104', 'เขาถ่าน   ', 894, 67, 6),
(8005, '841105', 'เสวียด   ', 894, 67, 6),
(8006, '841106', 'ปากฉลุย   ', 894, 67, 6),
(8007, '841201', 'นาสาร   ', 895, 67, 6),
(8008, '841202', 'พรุพี   ', 895, 67, 6),
(8009, '841203', 'ทุ่งเตา   ', 895, 67, 6),
(8010, '841204', 'ลำพูน   ', 895, 67, 6),
(8011, '841205', 'ท่าชี   ', 895, 67, 6),
(8012, '841206', 'ควนศรี   ', 895, 67, 6),
(8013, '841207', 'ควนสุบรรณ   ', 895, 67, 6),
(8014, '841208', 'คลองปราบ   ', 895, 67, 6),
(8015, '841209', 'น้ำพุ   ', 895, 67, 6),
(8016, '841210', 'ทุ่งเตาใหม่   ', 895, 67, 6),
(8017, '841211', 'เพิ่มพูนทรัพย์   ', 895, 67, 6),
(8018, '841298', '*ท่าเรือ   ', 895, 67, 6),
(8019, '841299', '*บ้านนา   ', 895, 67, 6),
(8020, '841301', 'บ้านนา   ', 896, 67, 6),
(8021, '841302', 'ท่าเรือ   ', 896, 67, 6),
(8022, '841303', 'ทรัพย์ทวี   ', 896, 67, 6),
(8023, '841304', 'นาใต้   ', 896, 67, 6),
(8024, '841401', 'เคียนซา   ', 897, 67, 6),
(8025, '841402', 'พ่วงพรมคร   ', 897, 67, 6),
(8026, '841403', 'เขาตอก   ', 897, 67, 6),
(8027, '841404', 'อรัญคามวารี   ', 897, 67, 6),
(8028, '841405', 'บ้านเสด็จ   ', 897, 67, 6),
(8029, '841501', 'เวียงสระ   ', 898, 67, 6),
(8030, '841502', 'บ้านส้อง   ', 898, 67, 6),
(8031, '841503', 'คลองฉนวน   ', 898, 67, 6),
(8032, '841504', 'ทุ่งหลวง   ', 898, 67, 6),
(8033, '841505', 'เขานิพันธ์   ', 898, 67, 6),
(8034, '841601', 'อิปัน   ', 899, 67, 6),
(8035, '841602', 'สินปุน   ', 899, 67, 6),
(8036, '841603', 'บางสวรรค์   ', 899, 67, 6),
(8037, '841604', 'ไทรขึง   ', 899, 67, 6),
(8038, '841605', 'สินเจริญ   ', 899, 67, 6),
(8039, '841606', 'ไทรโสภา   ', 899, 67, 6),
(8040, '841607', 'สาคู   ', 899, 67, 6),
(8041, '841698', '*ชัยบุรี   ', 899, 67, 6),
(8042, '841699', '*สองแพรก   ', 899, 67, 6),
(8043, '841701', 'ท่าข้าม   ', 900, 67, 6),
(8044, '841702', 'ท่าสะท้อน   ', 900, 67, 6),
(8045, '841703', 'ลีเล็ด   ', 900, 67, 6),
(8046, '841704', 'บางมะเดื่อ   ', 900, 67, 6),
(8047, '841705', 'บางเดือน   ', 900, 67, 6),
(8048, '841706', 'ท่าโรงช้าง   ', 900, 67, 6),
(8049, '841707', 'กรูด   ', 900, 67, 6),
(8050, '841708', 'พุนพิน   ', 900, 67, 6),
(8051, '841709', 'บางงอน   ', 900, 67, 6),
(8052, '841710', 'ศรีวิชัย   ', 900, 67, 6),
(8053, '841711', 'น้ำรอบ   ', 900, 67, 6),
(8054, '841712', 'มะลวน   ', 900, 67, 6),
(8055, '841713', 'หัวเตย   ', 900, 67, 6),
(8056, '841714', 'หนองไทร   ', 900, 67, 6),
(8057, '841715', 'เขาหัวควาย   ', 900, 67, 6),
(8058, '841716', 'ตะปาน   ', 900, 67, 6),
(8059, '841799', '*คลองไทร   ', 900, 67, 6),
(8060, '841801', 'สองแพรก   ', 901, 67, 6),
(8061, '841802', 'ชัยบุรี   ', 901, 67, 6),
(8062, '841803', 'คลองน้อย   ', 901, 67, 6),
(8063, '841804', 'ไทรทอง   ', 901, 67, 6),
(8064, '841901', 'ตะกุกใต้   ', 902, 67, 6),
(8065, '841902', 'ตะกุกเหนือ   ', 902, 67, 6),
(8066, '850101', 'เขานิเวศน์   ', 905, 68, 6),
(8067, '850102', 'ราชกรูด   ', 905, 68, 6),
(8068, '850103', 'หงาว   ', 905, 68, 6),
(8069, '850104', 'บางริ้น   ', 905, 68, 6),
(8070, '850105', 'ปากน้ำ   ', 905, 68, 6),
(8071, '850106', 'บางนอน   ', 905, 68, 6),
(8072, '850107', 'หาดส้มแป้น   ', 905, 68, 6),
(8073, '850108', 'ทรายแดง   ', 905, 68, 6),
(8074, '850109', 'เกาะพยาม   ', 905, 68, 6),
(8075, '850201', 'ละอุ่นใต้   ', 906, 68, 6),
(8076, '850202', 'ละอุ่นเหนือ   ', 906, 68, 6),
(8077, '850203', 'บางพระใต้   ', 906, 68, 6),
(8078, '850204', 'บางพระเหนือ   ', 906, 68, 6),
(8079, '850205', 'บางแก้ว   ', 906, 68, 6),
(8080, '850206', 'ในวงเหนือ   ', 906, 68, 6),
(8081, '850207', 'ในวงใต้   ', 906, 68, 6),
(8082, '850301', 'ม่วงกลวง   ', 907, 68, 6),
(8083, '850302', 'กะเปอร์   ', 907, 68, 6),
(8084, '850303', 'เชี่ยวเหลียง   ', 907, 68, 6),
(8085, '850304', 'บ้านนา   ', 907, 68, 6),
(8086, '850305', 'บางหิน   ', 907, 68, 6),
(8087, '850306', '*นาคา   ', 907, 68, 6),
(8088, '850307', '*กำพวน   ', 907, 68, 6),
(8089, '850401', 'น้ำจืด   ', 908, 68, 6),
(8090, '850402', 'น้ำจืดน้อย   ', 908, 68, 6),
(8091, '850403', 'มะมุ   ', 908, 68, 6),
(8092, '850404', 'ปากจั่น   ', 908, 68, 6),
(8093, '850405', 'ลำเลียง   ', 908, 68, 6),
(8094, '850406', 'จ.ป.ร.   ', 908, 68, 6),
(8095, '850407', 'บางใหญ่   ', 908, 68, 6),
(8096, '850501', 'นาคา   ', 909, 68, 6),
(8097, '850502', 'กำพวน   ', 909, 68, 6),
(8098, '860101', 'ท่าตะเภา   ', 910, 69, 6),
(8099, '860102', 'ปากน้ำ   ', 910, 69, 6),
(8100, '860103', 'ท่ายาง   ', 910, 69, 6),
(8101, '860104', 'บางหมาก   ', 910, 69, 6),
(8102, '860105', 'นาทุ่ง   ', 910, 69, 6),
(8103, '860106', 'นาชะอัง   ', 910, 69, 6),
(8104, '860107', 'ตากแดด   ', 910, 69, 6),
(8105, '860108', 'บางลึก   ', 910, 69, 6),
(8106, '860109', 'หาดพันไกร   ', 910, 69, 6),
(8107, '860110', 'วังไผ่   ', 910, 69, 6),
(8108, '860111', 'วังใหม่   ', 910, 69, 6),
(8109, '860112', 'บ้านนา   ', 910, 69, 6),
(8110, '860113', 'ขุนกระทิง   ', 910, 69, 6),
(8111, '860114', 'ทุ่งคา   ', 910, 69, 6),
(8112, '860115', 'วิสัยเหนือ   ', 910, 69, 6),
(8113, '860116', 'หาดทรายรี   ', 910, 69, 6),
(8114, '860117', 'ถ้ำสิงห์   ', 910, 69, 6),
(8115, '860201', 'ท่าแซะ   ', 911, 69, 6),
(8116, '860202', 'คุริง   ', 911, 69, 6),
(8117, '860203', 'สลุย   ', 911, 69, 6),
(8118, '860204', 'นากระตาม   ', 911, 69, 6),
(8119, '860205', 'รับร่อ   ', 911, 69, 6),
(8120, '860206', 'ท่าข้าม   ', 911, 69, 6),
(8121, '860207', 'หงษ์เจริญ   ', 911, 69, 6),
(8122, '860208', 'หินแก้ว   ', 911, 69, 6),
(8123, '860209', 'ทรัพย์อนันต์   ', 911, 69, 6),
(8124, '860210', 'สองพี่น้อง   ', 911, 69, 6),
(8125, '860301', 'บางสน   ', 912, 69, 6),
(8126, '860302', 'ทะเลทรัพย์   ', 912, 69, 6),
(8127, '860303', 'สะพลี   ', 912, 69, 6),
(8128, '860304', 'ชุมโค   ', 912, 69, 6),
(8129, '860305', 'ดอนยาง   ', 912, 69, 6),
(8130, '860306', 'ปากคลอง   ', 912, 69, 6),
(8131, '860307', 'เขาไชยราช   ', 912, 69, 6),
(8132, '860401', 'หลังสวน   ', 913, 69, 6),
(8133, '860402', 'ขันเงิน   ', 913, 69, 6),
(8134, '860403', 'ท่ามะพลา   ', 913, 69, 6),
(8135, '860404', 'นาขา   ', 913, 69, 6),
(8136, '860405', 'นาพญา   ', 913, 69, 6),
(8137, '860406', 'บ้านควน   ', 913, 69, 6),
(8138, '860407', 'บางมะพร้าว   ', 913, 69, 6),
(8139, '860408', 'บางน้ำจืด   ', 913, 69, 6),
(8140, '860409', 'ปากน้ำ   ', 913, 69, 6),
(8141, '860410', 'พ้อแดง   ', 913, 69, 6),
(8142, '860411', 'แหลมทราย   ', 913, 69, 6),
(8143, '860412', 'วังตะกอ   ', 913, 69, 6),
(8144, '860413', 'หาดยาย   ', 913, 69, 6),
(8145, '860501', 'ละแม   ', 914, 69, 6),
(8146, '860502', 'ทุ่งหลวง   ', 914, 69, 6),
(8147, '860503', 'สวนแตง   ', 914, 69, 6),
(8148, '860504', 'ทุ่งคาวัด   ', 914, 69, 6),
(8149, '860601', 'พะโต๊ะ   ', 915, 69, 6),
(8150, '860602', 'ปากทรง   ', 915, 69, 6),
(8151, '860603', 'ปังหวาน   ', 915, 69, 6),
(8152, '860604', 'พระรักษ์   ', 915, 69, 6),
(8153, '860701', 'นาโพธิ์   ', 916, 69, 6),
(8154, '860702', 'สวี   ', 916, 69, 6),
(8155, '860703', 'ทุ่งระยะ   ', 916, 69, 6),
(8156, '860704', 'ท่าหิน   ', 916, 69, 6),
(8157, '860705', 'ปากแพรก   ', 916, 69, 6),
(8158, '860706', 'ด่านสวี   ', 916, 69, 6),
(8159, '860707', 'ครน   ', 916, 69, 6),
(8160, '860708', 'วิสัยใต้   ', 916, 69, 6),
(8161, '860709', 'นาสัก   ', 916, 69, 6),
(8162, '860710', 'เขาทะลุ   ', 916, 69, 6),
(8163, '860711', 'เขาค่าย   ', 916, 69, 6),
(8164, '860801', 'ปากตะโก   ', 917, 69, 6),
(8165, '860802', 'ทุ่งตะไคร   ', 917, 69, 6),
(8166, '860803', 'ตะโก   ', 917, 69, 6),
(8167, '860804', 'ช่องไม้แก้ว   ', 917, 69, 6),
(8168, '900101', 'บ่อยาง   ', 918, 70, 6),
(8169, '900102', 'เขารูปช้าง   ', 918, 70, 6),
(8170, '900103', 'เกาะแต้ว   ', 918, 70, 6),
(8171, '900104', 'พะวง   ', 918, 70, 6),
(8172, '900105', 'ทุ่งหวัง   ', 918, 70, 6),
(8173, '900106', 'เกาะยอ   ', 918, 70, 6),
(8174, '900107', '*ชิงโค   ', 918, 70, 6),
(8175, '900108', '*สทิงหม้อ   ', 918, 70, 6),
(8176, '900109', '*ทำนบ   ', 918, 70, 6),
(8177, '900110', '*รำแดง   ', 918, 70, 6),
(8178, '900111', '*วัดขนุน   ', 918, 70, 6),
(8179, '900112', '*ชะแล้   ', 918, 70, 6),
(8180, '900113', '*ปากรอ   ', 918, 70, 6),
(8181, '900114', '*ป่าขาด   ', 918, 70, 6),
(8182, '900115', '*หัวเขา   ', 918, 70, 6),
(8183, '900116', '*บางเขียด   ', 918, 70, 6),
(8184, '900117', '*ม่วงงาม   ', 918, 70, 6),
(8185, '900188', '*ปากรอ   ', 918, 70, 6),
(8186, '900189', '*ทำนบ   ', 918, 70, 6),
(8187, '900190', '*ชลเจริญ   ', 918, 70, 6),
(8188, '900191', '*ม่วงงาม   ', 918, 70, 6),
(8189, '900192', '*หัวเขา   ', 918, 70, 6),
(8190, '900193', '*ชะแล้   ', 918, 70, 6),
(8191, '900194', '*วัดขนุน   ', 918, 70, 6),
(8192, '900195', '*สทิงหม้อ   ', 918, 70, 6),
(8193, '900196', '*บางเขียด   ', 918, 70, 6),
(8194, '900197', '*ป่าขาด   ', 918, 70, 6),
(8195, '900198', '*รำแดง   ', 918, 70, 6),
(8196, '900199', '*ชิงโค   ', 918, 70, 6),
(8197, '900201', 'จะทิ้งพระ   ', 919, 70, 6),
(8198, '900202', 'กระดังงา   ', 919, 70, 6),
(8199, '900203', 'สนามชัย   ', 919, 70, 6),
(8200, '900204', 'ดีหลวง   ', 919, 70, 6),
(8201, '900205', 'ชุมพล   ', 919, 70, 6),
(8202, '900206', 'คลองรี   ', 919, 70, 6),
(8203, '900207', 'คูขุด   ', 919, 70, 6),
(8204, '900208', 'ท่าหิน   ', 919, 70, 6),
(8205, '900209', 'วัดจันทร์   ', 919, 70, 6),
(8206, '900210', 'บ่อแดง   ', 919, 70, 6),
(8207, '900211', 'บ่อดาน   ', 919, 70, 6),
(8208, '900301', 'บ้านนา   ', 920, 70, 6),
(8209, '900302', 'ป่าชิง   ', 920, 70, 6),
(8210, '900303', 'สะพานไม้แก่น   ', 920, 70, 6),
(8211, '900304', 'สะกอม   ', 920, 70, 6),
(8212, '900305', 'นาหว้า   ', 920, 70, 6),
(8213, '900306', 'นาทับ   ', 920, 70, 6),
(8214, '900307', 'น้ำขาว   ', 920, 70, 6),
(8215, '900308', 'ขุนตัดหวาย   ', 920, 70, 6),
(8216, '900309', 'ท่าหมอไทร   ', 920, 70, 6),
(8217, '900310', 'จะโหนง   ', 920, 70, 6),
(8218, '900311', 'คู   ', 920, 70, 6),
(8219, '900312', 'แค   ', 920, 70, 6),
(8220, '900313', 'คลองเปียะ   ', 920, 70, 6),
(8221, '900314', 'ตลิ่งชัน   ', 920, 70, 6),
(8222, '900401', 'นาทวี   ', 921, 70, 6),
(8223, '900402', 'ฉาง   ', 921, 70, 6),
(8224, '900403', 'นาหมอศรี   ', 921, 70, 6),
(8225, '900404', 'คลองทราย   ', 921, 70, 6),
(8226, '900405', 'ปลักหนู   ', 921, 70, 6),
(8227, '900406', 'ท่าประดู่   ', 921, 70, 6),
(8228, '900407', 'สะท้อน   ', 921, 70, 6),
(8229, '900408', 'ทับช้าง   ', 921, 70, 6),
(8230, '900409', 'ประกอบ   ', 921, 70, 6),
(8231, '900410', 'คลองกวาง   ', 921, 70, 6),
(8232, '900501', 'เทพา   ', 922, 70, 6),
(8233, '900502', 'ปากบาง   ', 922, 70, 6),
(8234, '900503', 'เกาะสะบ้า   ', 922, 70, 6),
(8235, '900504', 'ลำไพล   ', 922, 70, 6),
(8236, '900505', 'ท่าม่วง   ', 922, 70, 6),
(8237, '900506', 'วังใหญ่   ', 922, 70, 6),
(8238, '900507', 'สะกอม   ', 922, 70, 6),
(8239, '900601', 'สะบ้าย้อย   ', 923, 70, 6),
(8240, '900602', 'ทุ่งพอ   ', 923, 70, 6),
(8241, '900603', 'เปียน   ', 923, 70, 6),
(8242, '900604', 'บ้านโหนด   ', 923, 70, 6),
(8243, '900605', 'จะแหน   ', 923, 70, 6),
(8244, '900606', 'คูหา   ', 923, 70, 6),
(8245, '900607', 'เขาแดง   ', 923, 70, 6),
(8246, '900608', 'บาโหย   ', 923, 70, 6),
(8247, '900609', 'ธารคีรี   ', 923, 70, 6),
(8248, '900701', 'ระโนด   ', 924, 70, 6),
(8249, '900702', 'คลองแดน   ', 924, 70, 6),
(8250, '900703', 'ตะเครียะ   ', 924, 70, 6),
(8251, '900704', 'ท่าบอน   ', 924, 70, 6),
(8252, '900705', 'บ้านใหม่   ', 924, 70, 6),
(8253, '900706', 'บ่อตรุ   ', 924, 70, 6),
(8254, '900707', 'ปากแตระ   ', 924, 70, 6),
(8255, '900708', 'พังยาง   ', 924, 70, 6),
(8256, '900709', 'ระวะ   ', 924, 70, 6),
(8257, '900710', 'วัดสน   ', 924, 70, 6),
(8258, '900711', 'บ้านขาว   ', 924, 70, 6),
(8259, '900712', 'แดนสงวน   ', 924, 70, 6),
(8260, '900797', '*เชิงแส   ', 924, 70, 6),
(8261, '900798', '*โรง   ', 924, 70, 6),
(8262, '900799', '*เกาะใหญ่   ', 924, 70, 6),
(8263, '900801', 'เกาะใหญ่   ', 925, 70, 6),
(8264, '900802', 'โรง   ', 925, 70, 6),
(8265, '900803', 'เชิงแส   ', 925, 70, 6),
(8266, '900804', 'กระแสสินธุ์   ', 925, 70, 6),
(8267, '900901', 'กำแพงเพชร   ', 926, 70, 6),
(8268, '900902', 'ท่าชะมวง   ', 926, 70, 6),
(8269, '900903', 'คูหาใต้   ', 926, 70, 6),
(8270, '900904', 'ควนรู   ', 926, 70, 6),
(8271, '900905', '*ควนโส   ', 926, 70, 6),
(8272, '900906', '*รัตภูมิ   ', 926, 70, 6),
(8273, '900907', '*บางเหรียง   ', 926, 70, 6),
(8274, '900908', '*ห้วยลึก   ', 926, 70, 6),
(8275, '900909', 'เขาพระ   ', 926, 70, 6),
(8276, '900996', '*บางเหรี่ยง   ', 926, 70, 6);
INSERT INTO `tb_district` (`DISTRICT_ID`, `DISTRICT_CODE`, `DISTRICT_NAME`, `AMPHUR_ID`, `PROVINCE_ID`, `GEO_ID`) VALUES
(8277, '900997', '*ห้วยลึก   ', 926, 70, 6),
(8278, '900998', '*ควนโส   ', 926, 70, 6),
(8279, '900999', '*รัตนภูมิ   ', 926, 70, 6),
(8280, '901001', 'สะเดา   ', 927, 70, 6),
(8281, '901002', 'ปริก   ', 927, 70, 6),
(8282, '901003', 'พังลา   ', 927, 70, 6),
(8283, '901004', 'สำนักแต้ว   ', 927, 70, 6),
(8284, '901005', 'ทุ่งหมอ   ', 927, 70, 6),
(8285, '901006', 'ท่าโพธิ์   ', 927, 70, 6),
(8286, '901007', 'ปาดังเบซาร์   ', 927, 70, 6),
(8287, '901008', 'สำนักขาม   ', 927, 70, 6),
(8288, '901009', 'เขามีเกียรติ   ', 927, 70, 6),
(8289, '901101', 'หาดใหญ่   ', 928, 70, 6),
(8290, '901102', 'ควนลัง   ', 928, 70, 6),
(8291, '901103', 'คูเต่า   ', 928, 70, 6),
(8292, '901104', 'คอหงส์   ', 928, 70, 6),
(8293, '901105', 'คลองแห   ', 928, 70, 6),
(8294, '901106', 'คลองหอยโข่ง*   ', 928, 70, 6),
(8295, '901107', 'คลองอู่ตะเภา   ', 928, 70, 6),
(8296, '901108', 'ฉลุง   ', 928, 70, 6),
(8297, '901109', 'ทุ่งลาน*   ', 928, 70, 6),
(8298, '901110', 'ท่าช้าง*   ', 928, 70, 6),
(8299, '901111', 'ทุ่งใหญ่   ', 928, 70, 6),
(8300, '901112', 'ทุ่งตำเสา   ', 928, 70, 6),
(8301, '901113', 'ท่าข้าม   ', 928, 70, 6),
(8302, '901114', 'น้ำน้อย   ', 928, 70, 6),
(8303, '901115', '*บางกล่ำ   ', 928, 70, 6),
(8304, '901116', 'บ้านพรุ   ', 928, 70, 6),
(8305, '901117', '*บ้านหาร   ', 928, 70, 6),
(8306, '901118', 'พะตง   ', 928, 70, 6),
(8307, '901119', '*แม่ทอม   ', 928, 70, 6),
(8308, '901121', '*โคกม่วง   ', 928, 70, 6),
(8309, '901190', '*ทุ่งลาน   ', 928, 70, 6),
(8310, '901191', '*คลองหอยโข่ง   ', 928, 70, 6),
(8311, '901192', '*บ้านหาร   ', 928, 70, 6),
(8312, '901193', '*แม่ทอม   ', 928, 70, 6),
(8313, '901194', '*ท่าช้าง   ', 928, 70, 6),
(8314, '901195', '*บางกล่ำ   ', 928, 70, 6),
(8315, '901196', '*คลองหรัง   ', 928, 70, 6),
(8316, '901197', '*ทุ่งขมิ้น   ', 928, 70, 6),
(8317, '901198', '*พิจิตร   ', 928, 70, 6),
(8318, '901199', '*นาหม่อม   ', 928, 70, 6),
(8319, '901201', 'นาหม่อม   ', 929, 70, 6),
(8320, '901202', 'พิจิตร   ', 929, 70, 6),
(8321, '901203', 'ทุ่งขมิ้น   ', 929, 70, 6),
(8322, '901204', 'คลองหรัง   ', 929, 70, 6),
(8323, '901301', 'รัตภูมิ   ', 930, 70, 6),
(8324, '901302', 'ควนโส   ', 930, 70, 6),
(8325, '901303', 'ห้วยลึก   ', 930, 70, 6),
(8326, '901304', 'บางเหรียง   ', 930, 70, 6),
(8327, '901401', 'บางกล่ำ   ', 931, 70, 6),
(8328, '901402', 'ท่าช้าง   ', 931, 70, 6),
(8329, '901403', 'แม่ทอม   ', 931, 70, 6),
(8330, '901404', 'บ้านหาร   ', 931, 70, 6),
(8331, '901501', 'ชิงโค   ', 932, 70, 6),
(8332, '901502', 'สทิงหม้อ   ', 932, 70, 6),
(8333, '901503', 'ทำนบ   ', 932, 70, 6),
(8334, '901504', 'รำแดง   ', 932, 70, 6),
(8335, '901505', 'วัดขนุน   ', 932, 70, 6),
(8336, '901506', 'ชะแล้   ', 932, 70, 6),
(8337, '901507', 'ปากรอ   ', 932, 70, 6),
(8338, '901508', 'ป่าขาด   ', 932, 70, 6),
(8339, '901509', 'หัวเขา   ', 932, 70, 6),
(8340, '901510', 'บางเขียด   ', 932, 70, 6),
(8341, '901511', 'ม่วงงาม   ', 932, 70, 6),
(8342, '901601', 'คลองหอยโข่ง   ', 933, 70, 6),
(8343, '901602', 'ทุ่งลาน   ', 933, 70, 6),
(8344, '901603', 'โคกม่วง   ', 933, 70, 6),
(8345, '901604', 'คลองหลา   ', 933, 70, 6),
(8346, '907701', 'สำนักขาม*   ', 934, 70, 6),
(8347, '910101', 'พิมาน   ', 936, 71, 6),
(8348, '910102', 'คลองขุด   ', 936, 71, 6),
(8349, '910103', 'ควนขัน   ', 936, 71, 6),
(8350, '910104', 'บ้านควน   ', 936, 71, 6),
(8351, '910105', 'ฉลุง   ', 936, 71, 6),
(8352, '910106', 'เกาะสาหร่าย   ', 936, 71, 6),
(8353, '910107', 'ตันหยงโป   ', 936, 71, 6),
(8354, '910108', 'เจ๊ะบิลัง   ', 936, 71, 6),
(8355, '910109', 'ตำมะลัง   ', 936, 71, 6),
(8356, '910110', 'ปูยู   ', 936, 71, 6),
(8357, '910111', 'ควนโพธิ์   ', 936, 71, 6),
(8358, '910112', 'เกตรี   ', 936, 71, 6),
(8359, '910199', '*ท่าแพ   ', 936, 71, 6),
(8360, '910201', 'ควนโดน   ', 937, 71, 6),
(8361, '910202', 'ควนสตอ   ', 937, 71, 6),
(8362, '910203', 'ย่านซื่อ   ', 937, 71, 6),
(8363, '910204', 'วังประจัน   ', 937, 71, 6),
(8364, '910301', 'ทุ่งนุ้ย   ', 938, 71, 6),
(8365, '910302', 'ควนกาหลง   ', 938, 71, 6),
(8366, '910303', 'อุใดเจริญ   ', 938, 71, 6),
(8367, '910304', 'นิคมพัฒนา*   ', 938, 71, 6),
(8368, '910305', 'ปาล์มพัฒนา*   ', 938, 71, 6),
(8369, '910401', 'ท่าแพ   ', 939, 71, 6),
(8370, '910402', 'แป-ระ   ', 939, 71, 6),
(8371, '910403', 'สาคร   ', 939, 71, 6),
(8372, '910404', 'ท่าเรือ   ', 939, 71, 6),
(8373, '910501', 'กำแพง   ', 940, 71, 6),
(8374, '910502', 'ละงู   ', 940, 71, 6),
(8375, '910503', 'เขาขาว   ', 940, 71, 6),
(8376, '910504', 'ปากน้ำ   ', 940, 71, 6),
(8377, '910505', 'น้ำผุด   ', 940, 71, 6),
(8378, '910506', 'แหลมสน   ', 940, 71, 6),
(8379, '910601', 'ทุ่งหว้า   ', 941, 71, 6),
(8380, '910602', 'นาทอน   ', 941, 71, 6),
(8381, '910603', 'ขอนคลาน   ', 941, 71, 6),
(8382, '910604', 'ทุ่งบุหลัง   ', 941, 71, 6),
(8383, '910605', 'ป่าแก่บ่อหิน   ', 941, 71, 6),
(8384, '910701', 'ปาล์มพัฒนา   ', 942, 71, 6),
(8385, '910702', 'นิคมพัฒนา   ', 942, 71, 6),
(8386, '920101', 'ทับเที่ยง   ', 943, 72, 6),
(8387, '920102', '*โคกสะบ้า   ', 943, 72, 6),
(8388, '920103', '*ละมอ   ', 943, 72, 6),
(8389, '920104', 'นาพละ   ', 943, 72, 6),
(8390, '920105', 'บ้านควน   ', 943, 72, 6),
(8391, '920106', 'นาบินหลา   ', 943, 72, 6),
(8392, '920107', 'ควนปริง   ', 943, 72, 6),
(8393, '920108', 'นาโยงใต้   ', 943, 72, 6),
(8394, '920109', 'บางรัก   ', 943, 72, 6),
(8395, '920110', 'โคกหล่อ   ', 943, 72, 6),
(8396, '920111', '*นาข้าวเสีย   ', 943, 72, 6),
(8397, '920112', '*นาหมื่นศรี   ', 943, 72, 6),
(8398, '920113', 'นาโต๊ะหมิง   ', 943, 72, 6),
(8399, '920114', 'หนองตรุด   ', 943, 72, 6),
(8400, '920115', 'น้ำผุด   ', 943, 72, 6),
(8401, '920116', '*นาโยงเหนือ   ', 943, 72, 6),
(8402, '920117', 'นาตาล่วง   ', 943, 72, 6),
(8403, '920118', 'บ้านโพธิ์   ', 943, 72, 6),
(8404, '920119', 'นาท่ามเหนือ   ', 943, 72, 6),
(8405, '920120', 'นาท่ามใต้   ', 943, 72, 6),
(8406, '920121', '*ช่อง   ', 943, 72, 6),
(8407, '920194', '*นาข้าวเสีย   ', 943, 72, 6),
(8408, '920195', '*โคกสะบ้า   ', 943, 72, 6),
(8409, '920196', '*ละมอ   ', 943, 72, 6),
(8410, '920197', '*นาหมื่นศรี   ', 943, 72, 6),
(8411, '920198', '*ช่อง   ', 943, 72, 6),
(8412, '920199', '*นาโยงเหนือ   ', 943, 72, 6),
(8413, '920201', 'กันตัง   ', 944, 72, 6),
(8414, '920202', 'ควนธานี   ', 944, 72, 6),
(8415, '920203', 'บางหมาก   ', 944, 72, 6),
(8416, '920204', 'บางเป้า   ', 944, 72, 6),
(8417, '920205', 'วังวน   ', 944, 72, 6),
(8418, '920206', 'กันตังใต้   ', 944, 72, 6),
(8419, '920207', 'โคกยาง   ', 944, 72, 6),
(8420, '920208', 'คลองลุ   ', 944, 72, 6),
(8421, '920209', 'ย่านซื่อ   ', 944, 72, 6),
(8422, '920210', 'บ่อน้ำร้อน   ', 944, 72, 6),
(8423, '920211', 'บางสัก   ', 944, 72, 6),
(8424, '920212', 'นาเกลือ   ', 944, 72, 6),
(8425, '920213', 'เกาะลิบง   ', 944, 72, 6),
(8426, '920214', 'คลองชีล้อม   ', 944, 72, 6),
(8427, '920301', 'ย่านตาขาว   ', 945, 72, 6),
(8428, '920302', 'หนองบ่อ   ', 945, 72, 6),
(8429, '920303', 'นาชุมเห็ด   ', 945, 72, 6),
(8430, '920304', 'ในควน   ', 945, 72, 6),
(8431, '920305', 'โพรงจระเข้   ', 945, 72, 6),
(8432, '920306', 'ทุ่งกระบือ   ', 945, 72, 6),
(8433, '920307', 'ทุ่งค่าย   ', 945, 72, 6),
(8434, '920308', 'เกาะเปียะ   ', 945, 72, 6),
(8435, '920401', 'ท่าข้าม   ', 946, 72, 6),
(8436, '920402', 'ทุ่งยาว   ', 946, 72, 6),
(8437, '920403', 'ปะเหลียน   ', 946, 72, 6),
(8438, '920404', 'บางด้วน   ', 946, 72, 6),
(8439, '920405', '*หาดสำราญ   ', 946, 72, 6),
(8440, '920406', '*ตะเสะ   ', 946, 72, 6),
(8441, '920407', 'บ้านนา   ', 946, 72, 6),
(8442, '920408', '*บ้าหวี   ', 946, 72, 6),
(8443, '920409', 'สุโสะ   ', 946, 72, 6),
(8444, '920410', 'ลิพัง   ', 946, 72, 6),
(8445, '920411', 'เกาะสุกร   ', 946, 72, 6),
(8446, '920412', 'ท่าพญา   ', 946, 72, 6),
(8447, '920413', 'แหลมสอม   ', 946, 72, 6),
(8448, '920501', 'บ่อหิน   ', 947, 72, 6),
(8449, '920502', 'เขาไม้แก้ว   ', 947, 72, 6),
(8450, '920503', 'กะลาเส   ', 947, 72, 6),
(8451, '920504', 'ไม้ฝาด   ', 947, 72, 6),
(8452, '920505', 'นาเมืองเพชร   ', 947, 72, 6),
(8453, '920595', '*ท่าสะบ้า   ', 947, 72, 6),
(8454, '920596', '*สิเกา   ', 947, 72, 6),
(8455, '920597', '*อ่าวตง   ', 947, 72, 6),
(8456, '920598', '*วังมะปราง   ', 947, 72, 6),
(8457, '920599', '*เขาวิเศษ   ', 947, 72, 6),
(8458, '920601', 'ห้วยยอด   ', 948, 72, 6),
(8459, '920602', 'หนองช้างแล่น   ', 948, 72, 6),
(8460, '920603', '*หนองปรือ   ', 948, 72, 6),
(8461, '920604', '*หนองบัว   ', 948, 72, 6),
(8462, '920605', 'บางดี   ', 948, 72, 6),
(8463, '920606', 'บางกุ้ง   ', 948, 72, 6),
(8464, '920607', 'เขากอบ   ', 948, 72, 6),
(8465, '920608', 'เขาขาว   ', 948, 72, 6),
(8466, '920609', 'เขาปูน   ', 948, 72, 6),
(8467, '920610', 'ปากแจ่ม   ', 948, 72, 6),
(8468, '920611', 'ปากคม   ', 948, 72, 6),
(8469, '920612', '*คลองปาง   ', 948, 72, 6),
(8470, '920613', '*ควนเมา   ', 948, 72, 6),
(8471, '920614', 'ท่างิ้ว   ', 948, 72, 6),
(8472, '920615', 'ลำภูรา   ', 948, 72, 6),
(8473, '920616', 'นาวง   ', 948, 72, 6),
(8474, '920617', 'ห้วยนาง   ', 948, 72, 6),
(8475, '920618', '*เขาไพร   ', 948, 72, 6),
(8476, '920619', 'ในเตา   ', 948, 72, 6),
(8477, '920620', 'ทุ่งต่อ   ', 948, 72, 6),
(8478, '920621', 'วังคีรี   ', 948, 72, 6),
(8479, '920696', '*หนองปรือ   ', 948, 72, 6),
(8480, '920697', '*หนองบัว   ', 948, 72, 6),
(8481, '920698', '*คลองปาง   ', 948, 72, 6),
(8482, '920699', '*ควนเมา   ', 948, 72, 6),
(8483, '920701', 'เขาวิเศษ   ', 949, 72, 6),
(8484, '920702', 'วังมะปราง   ', 949, 72, 6),
(8485, '920703', 'อ่าวตง   ', 949, 72, 6),
(8486, '920704', 'ท่าสะบ้า   ', 949, 72, 6),
(8487, '920705', 'วังมะปรางเหนือ   ', 949, 72, 6),
(8488, '920801', 'นาโยงเหนือ   ', 950, 72, 6),
(8489, '920802', 'ช่อง   ', 950, 72, 6),
(8490, '920803', 'ละมอ   ', 950, 72, 6),
(8491, '920804', 'โคกสะบ้า   ', 950, 72, 6),
(8492, '920805', 'นาหมื่นศรี   ', 950, 72, 6),
(8493, '920806', 'นาข้าวเสีย   ', 950, 72, 6),
(8494, '920901', 'ควนเมา   ', 951, 72, 6),
(8495, '920902', 'คลองปาง   ', 951, 72, 6),
(8496, '920903', 'หนองบัว   ', 951, 72, 6),
(8497, '920904', 'หนองปรือ   ', 951, 72, 6),
(8498, '920905', 'เขาไพร   ', 951, 72, 6),
(8499, '921001', 'หาดสำราญ   ', 952, 72, 6),
(8500, '921002', 'บ้าหวี   ', 952, 72, 6),
(8501, '921003', 'ตะเสะ   ', 952, 72, 6),
(8502, '930101', 'คูหาสวรรค์   ', 954, 73, 6),
(8503, '930102', 'บ้านนา*   ', 954, 73, 6),
(8504, '930103', 'เขาเจียก   ', 954, 73, 6),
(8505, '930104', 'ท่ามิหรำ   ', 954, 73, 6),
(8506, '930105', 'โคกชะงาย   ', 954, 73, 6),
(8507, '930106', 'นาท่อม   ', 954, 73, 6),
(8508, '930107', 'ปรางหมู่   ', 954, 73, 6),
(8509, '930108', 'ท่าแค   ', 954, 73, 6),
(8510, '930109', 'ลำปำ   ', 954, 73, 6),
(8511, '930110', 'ตำนาน   ', 954, 73, 6),
(8512, '930111', 'ควนมะพร้าว   ', 954, 73, 6),
(8513, '930112', 'ร่มเมือง   ', 954, 73, 6),
(8514, '930113', 'ชัยบุรี   ', 954, 73, 6),
(8515, '930114', 'นาโหนด   ', 954, 73, 6),
(8516, '930115', 'พญาขัน   ', 954, 73, 6),
(8517, '930116', 'ลำสินธุ์*   ', 954, 73, 6),
(8518, '930117', 'อ่างทอง*   ', 954, 73, 6),
(8519, '930118', 'ชุมพล*   ', 954, 73, 6),
(8520, '930201', 'กงหรา   ', 955, 73, 6),
(8521, '930202', 'ชะรัด   ', 955, 73, 6),
(8522, '930203', 'คลองเฉลิม   ', 955, 73, 6),
(8523, '930204', 'คลองทรายขาว   ', 955, 73, 6),
(8524, '930205', 'สมหวัง   ', 955, 73, 6),
(8525, '930301', 'เขาชัยสน   ', 956, 73, 6),
(8526, '930302', 'ควนขนุน   ', 956, 73, 6),
(8527, '930303', '*ท่ามะเดื่อ   ', 956, 73, 6),
(8528, '930304', '*นาปะขอ   ', 956, 73, 6),
(8529, '930305', 'จองถนน   ', 956, 73, 6),
(8530, '930306', 'หานโพธิ์   ', 956, 73, 6),
(8531, '930307', 'โคกม่วง   ', 956, 73, 6),
(8532, '930308', '*โคกสัก   ', 956, 73, 6),
(8533, '930395', '*นาปะขอ   ', 956, 73, 6),
(8534, '930396', '*คลองใหญ่   ', 956, 73, 6),
(8535, '930397', '*ตะโหมด   ', 956, 73, 6),
(8536, '930398', '*ท่ามะเดื่อ   ', 956, 73, 6),
(8537, '930399', '*แม่ขรี   ', 956, 73, 6),
(8538, '930401', 'แม่ขรี   ', 957, 73, 6),
(8539, '930402', 'ตะโหมด   ', 957, 73, 6),
(8540, '930403', 'คลองใหญ่   ', 957, 73, 6),
(8541, '930501', 'ควนขนุน   ', 958, 73, 6),
(8542, '930502', 'ทะเลน้อย   ', 958, 73, 6),
(8543, '930503', '*เกาะเต่า   ', 958, 73, 6),
(8544, '930504', 'นาขยาด   ', 958, 73, 6),
(8545, '930505', 'พนมวังก์   ', 958, 73, 6),
(8546, '930506', 'แหลมโตนด   ', 958, 73, 6),
(8547, '930507', '*ป่าพะยอม   ', 958, 73, 6),
(8548, '930508', 'ปันแต   ', 958, 73, 6),
(8549, '930509', 'โตนดด้วน   ', 958, 73, 6),
(8550, '930510', 'ดอนทราย   ', 958, 73, 6),
(8551, '930511', 'มะกอกเหนือ   ', 958, 73, 6),
(8552, '930512', 'พนางตุง   ', 958, 73, 6),
(8553, '930513', 'ชะมวง   ', 958, 73, 6),
(8554, '930514', '*บ้านพร้าว   ', 958, 73, 6),
(8555, '930515', '*ลานข่อย   ', 958, 73, 6),
(8556, '930516', 'แพรกหา   ', 958, 73, 6),
(8557, '930596', '*คำไผ่   ', 958, 73, 6),
(8558, '930597', '*คำเตย   ', 958, 73, 6),
(8559, '930598', '*ส้มผ่อ   ', 958, 73, 6),
(8560, '930599', '*ป่าพะยอม   ', 958, 73, 6),
(8561, '930601', 'ปากพะยูน   ', 959, 73, 6),
(8562, '930602', 'ดอนประดู่   ', 959, 73, 6),
(8563, '930603', 'เกาะนางคำ   ', 959, 73, 6),
(8564, '930604', 'เกาะหมาก   ', 959, 73, 6),
(8565, '930605', 'ฝาละมี   ', 959, 73, 6),
(8566, '930606', 'หารเทา   ', 959, 73, 6),
(8567, '930607', 'ดอนทราย   ', 959, 73, 6),
(8568, '930697', '*หนองแซง   ', 959, 73, 6),
(8569, '930698', '*โคกทราย   ', 959, 73, 6),
(8570, '930699', '*ป่าบอน   ', 959, 73, 6),
(8571, '930701', 'เขาย่า   ', 960, 73, 6),
(8572, '930702', 'เขาปู่   ', 960, 73, 6),
(8573, '930703', 'ตะแพน   ', 960, 73, 6),
(8574, '930801', 'ป่าบอน   ', 961, 73, 6),
(8575, '930802', 'โคกทราย   ', 961, 73, 6),
(8576, '930803', 'หนองธง   ', 961, 73, 6),
(8577, '930804', 'ทุ่งนารี   ', 961, 73, 6),
(8578, '930806', 'วังใหม่   ', 961, 73, 6),
(8579, '930901', 'ท่ามะเดื่อ   ', 962, 73, 6),
(8580, '930902', 'นาปะขอ   ', 962, 73, 6),
(8581, '930903', 'โคกสัก   ', 962, 73, 6),
(8582, '931001', 'ป่าพะยอม   ', 963, 73, 6),
(8583, '931002', 'ลานข่อย   ', 963, 73, 6),
(8584, '931003', 'เกาะเต่า   ', 963, 73, 6),
(8585, '931004', 'บ้านพร้าว   ', 963, 73, 6),
(8586, '931101', 'ชุมพล   ', 964, 73, 6),
(8587, '931102', 'บ้านนา   ', 964, 73, 6),
(8588, '931103', 'อ่างทอง   ', 964, 73, 6),
(8589, '931104', 'ลำสินธุ์   ', 964, 73, 6),
(8590, '940101', 'สะบารัง   ', 965, 74, 6),
(8591, '940102', 'อาเนาะรู   ', 965, 74, 6),
(8592, '940103', 'จะบังติกอ   ', 965, 74, 6),
(8593, '940104', 'บานา   ', 965, 74, 6),
(8594, '940105', 'ตันหยงลุโละ   ', 965, 74, 6),
(8595, '940106', 'คลองมานิง   ', 965, 74, 6),
(8596, '940107', 'กะมิยอ   ', 965, 74, 6),
(8597, '940108', 'บาราโหม   ', 965, 74, 6),
(8598, '940109', 'ปะกาฮะรัง   ', 965, 74, 6),
(8599, '940110', 'รูสะมิแล   ', 965, 74, 6),
(8600, '940111', 'ตะลุโบะ   ', 965, 74, 6),
(8601, '940112', 'บาราเฮาะ   ', 965, 74, 6),
(8602, '940113', 'ปุยุด   ', 965, 74, 6),
(8603, '940201', 'โคกโพธิ์   ', 966, 74, 6),
(8604, '940202', 'มะกรูด   ', 966, 74, 6),
(8605, '940203', 'บางโกระ   ', 966, 74, 6),
(8606, '940204', 'ป่าบอน   ', 966, 74, 6),
(8607, '940205', 'ทรายขาว   ', 966, 74, 6),
(8608, '940206', 'นาประดู่   ', 966, 74, 6),
(8609, '940207', 'ปากล่อ   ', 966, 74, 6),
(8610, '940208', 'ทุ่งพลา   ', 966, 74, 6),
(8611, '940209', '*แม่ลาน   ', 966, 74, 6),
(8612, '940210', '*ป่าไร่   ', 966, 74, 6),
(8613, '940211', 'ท่าเรือ   ', 966, 74, 6),
(8614, '940212', '*ม่วงเตี้ย   ', 966, 74, 6),
(8615, '940213', 'นาเกตุ   ', 966, 74, 6),
(8616, '940214', 'ควนโนรี   ', 966, 74, 6),
(8617, '940215', 'ช้างให้ตก   ', 966, 74, 6),
(8618, '940301', 'เกาะเปาะ   ', 967, 74, 6),
(8619, '940302', 'คอลอตันหยง   ', 967, 74, 6),
(8620, '940303', 'ดอนรัก   ', 967, 74, 6),
(8621, '940304', 'ดาโต๊ะ   ', 967, 74, 6),
(8622, '940305', 'ตุยง   ', 967, 74, 6),
(8623, '940306', 'ท่ากำชำ   ', 967, 74, 6),
(8624, '940307', 'บ่อทอง   ', 967, 74, 6),
(8625, '940308', 'บางเขา   ', 967, 74, 6),
(8626, '940309', 'บางตาวา   ', 967, 74, 6),
(8627, '940310', 'ปุโละปุโย   ', 967, 74, 6),
(8628, '940311', 'ยาบี   ', 967, 74, 6),
(8629, '940312', 'ลิปะสะโง   ', 967, 74, 6),
(8630, '940401', 'ปะนาเระ   ', 968, 74, 6),
(8631, '940402', 'ท่าข้าม   ', 968, 74, 6),
(8632, '940403', 'บ้านนอก   ', 968, 74, 6),
(8633, '940404', 'ดอน   ', 968, 74, 6),
(8634, '940405', 'ควน   ', 968, 74, 6),
(8635, '940406', 'ท่าน้ำ   ', 968, 74, 6),
(8636, '940407', 'คอกกระบือ   ', 968, 74, 6),
(8637, '940408', 'พ่อมิ่ง   ', 968, 74, 6),
(8638, '940409', 'บ้านกลาง   ', 968, 74, 6),
(8639, '940410', 'บ้านน้ำบ่อ   ', 968, 74, 6),
(8640, '940501', 'มายอ   ', 969, 74, 6),
(8641, '940502', 'ถนน   ', 969, 74, 6),
(8642, '940503', 'ตรัง   ', 969, 74, 6),
(8643, '940504', 'กระหวะ   ', 969, 74, 6),
(8644, '940505', 'ลุโบะยิไร   ', 969, 74, 6),
(8645, '940506', 'ลางา   ', 969, 74, 6),
(8646, '940507', 'กระเสาะ   ', 969, 74, 6),
(8647, '940508', 'เกาะจัน   ', 969, 74, 6),
(8648, '940509', 'ปะโด   ', 969, 74, 6),
(8649, '940510', 'สาคอบน   ', 969, 74, 6),
(8650, '940511', 'สาคอใต้   ', 969, 74, 6),
(8651, '940512', 'สะกำ   ', 969, 74, 6),
(8652, '940513', 'ปานัน   ', 969, 74, 6),
(8653, '940601', 'ตะโละแมะนา   ', 970, 74, 6),
(8654, '940602', 'พิเทน   ', 970, 74, 6),
(8655, '940603', 'น้ำดำ   ', 970, 74, 6),
(8656, '940604', 'ปากู   ', 970, 74, 6),
(8657, '940701', 'ตะลุบัน   ', 971, 74, 6),
(8658, '940702', 'ตะบิ้ง   ', 971, 74, 6),
(8659, '940703', 'ปะเสยะวอ   ', 971, 74, 6),
(8660, '940704', 'บางเก่า   ', 971, 74, 6),
(8661, '940705', 'บือเระ   ', 971, 74, 6),
(8662, '940706', 'เตราะบอน   ', 971, 74, 6),
(8663, '940707', 'กะดุนง   ', 971, 74, 6),
(8664, '940708', 'ละหาร   ', 971, 74, 6),
(8665, '940709', 'มะนังดาลำ   ', 971, 74, 6),
(8666, '940710', 'แป้น   ', 971, 74, 6),
(8667, '940711', 'ทุ่งคล้า   ', 971, 74, 6),
(8668, '940801', 'ไทรทอง   ', 972, 74, 6),
(8669, '940802', 'ไม้แก่น   ', 972, 74, 6),
(8670, '940803', 'ตะโละไกรทอง   ', 972, 74, 6),
(8671, '940804', 'ดอนทราย   ', 972, 74, 6),
(8672, '940901', 'ตะโละ   ', 973, 74, 6),
(8673, '940902', 'ตะโละกาโปร์   ', 973, 74, 6),
(8674, '940903', 'ตันหยงดาลอ   ', 973, 74, 6),
(8675, '940904', 'ตันหยงจึงงา   ', 973, 74, 6),
(8676, '940905', 'ตอหลัง   ', 973, 74, 6),
(8677, '940906', 'ตาแกะ   ', 973, 74, 6),
(8678, '940907', 'ตาลีอายร์   ', 973, 74, 6),
(8679, '940908', 'ยามู   ', 973, 74, 6),
(8680, '940909', 'บางปู   ', 973, 74, 6),
(8681, '940910', 'หนองแรต   ', 973, 74, 6),
(8682, '940911', 'ปิยามุมัง   ', 973, 74, 6),
(8683, '940912', 'ปุลากง   ', 973, 74, 6),
(8684, '940913', 'บาโลย   ', 973, 74, 6),
(8685, '940914', 'สาบัน   ', 973, 74, 6),
(8686, '940915', 'มะนังยง   ', 973, 74, 6),
(8687, '940916', 'ราตาปันยัง   ', 973, 74, 6),
(8688, '940917', 'จะรัง   ', 973, 74, 6),
(8689, '940918', 'แหลมโพธิ์   ', 973, 74, 6),
(8690, '941001', 'ยะรัง   ', 974, 74, 6),
(8691, '941002', 'สะดาวา   ', 974, 74, 6),
(8692, '941003', 'ประจัน   ', 974, 74, 6),
(8693, '941004', 'สะนอ   ', 974, 74, 6),
(8694, '941005', 'ระแว้ง   ', 974, 74, 6),
(8695, '941006', 'ปิตูมุดี   ', 974, 74, 6),
(8696, '941007', 'วัด   ', 974, 74, 6),
(8697, '941008', 'กระโด   ', 974, 74, 6),
(8698, '941009', 'คลองใหม่   ', 974, 74, 6),
(8699, '941010', 'เมาะมาวี   ', 974, 74, 6),
(8700, '941011', 'กอลำ   ', 974, 74, 6),
(8701, '941012', 'เขาตูม   ', 974, 74, 6),
(8702, '941101', 'กะรุบี   ', 975, 74, 6),
(8703, '941102', 'ตะโละดือรามัน   ', 975, 74, 6),
(8704, '941103', 'ปล่องหอย   ', 975, 74, 6),
(8705, '941201', 'แม่ลาน   ', 976, 74, 6),
(8706, '941202', 'ม่วงเตี้ย   ', 976, 74, 6),
(8707, '941203', 'ป่าไร่   ', 976, 74, 6),
(8708, '950101', 'สะเตง   ', 977, 75, 6),
(8709, '950102', 'บุดี   ', 977, 75, 6),
(8710, '950103', 'ยุโป   ', 977, 75, 6),
(8711, '950104', 'ลิดล   ', 977, 75, 6),
(8712, '950105', '*ปุโรง   ', 977, 75, 6),
(8713, '950106', 'ยะลา   ', 977, 75, 6),
(8714, '950107', '*สะเอะ   ', 977, 75, 6),
(8715, '950108', 'ท่าสาป   ', 977, 75, 6),
(8716, '950109', 'ลำใหม่   ', 977, 75, 6),
(8717, '950110', 'หน้าถ้ำ   ', 977, 75, 6),
(8718, '950111', 'ลำพะยา   ', 977, 75, 6),
(8719, '950112', 'เปาะเส้ง   ', 977, 75, 6),
(8720, '950113', '*กรงปินัง   ', 977, 75, 6),
(8721, '950114', 'พร่อน   ', 977, 75, 6),
(8722, '950115', 'บันนังสาเรง   ', 977, 75, 6),
(8723, '950116', 'สะเตงนอก   ', 977, 75, 6),
(8724, '950117', '*ห้วยกระทิง   ', 977, 75, 6),
(8725, '950118', 'ตาเซะ   ', 977, 75, 6),
(8726, '950201', 'เบตง   ', 978, 75, 6),
(8727, '950202', 'ยะรม   ', 978, 75, 6),
(8728, '950203', 'ตาเนาะแมเราะ   ', 978, 75, 6),
(8729, '950204', 'อัยเยอร์เวง   ', 978, 75, 6),
(8730, '950205', 'ธารน้ำทิพย์   ', 978, 75, 6),
(8731, '950301', 'บันนังสตา   ', 979, 75, 6),
(8732, '950302', 'บาเจาะ   ', 979, 75, 6),
(8733, '950303', 'ตาเนาะปูเต๊ะ   ', 979, 75, 6),
(8734, '950304', 'ถ้ำทะลุ   ', 979, 75, 6),
(8735, '950305', 'ตลิ่งชัน   ', 979, 75, 6),
(8736, '950306', 'เขื่อนบางลาง   ', 979, 75, 6),
(8737, '950397', '*แม่หวาด   ', 979, 75, 6),
(8738, '950398', '*บ้านแหร   ', 979, 75, 6),
(8739, '950399', '*ธารโต   ', 979, 75, 6),
(8740, '950401', 'ธารโต   ', 980, 75, 6),
(8741, '950402', 'บ้านแหร   ', 980, 75, 6),
(8742, '950403', 'แม่หวาด   ', 980, 75, 6),
(8743, '950404', 'คีรีเขต   ', 980, 75, 6),
(8744, '950501', 'ยะหา   ', 981, 75, 6),
(8745, '950502', 'ละแอ   ', 981, 75, 6),
(8746, '950503', 'ปะแต   ', 981, 75, 6),
(8747, '950504', 'บาโร๊ะ   ', 981, 75, 6),
(8748, '950505', '*กาบัง   ', 981, 75, 6),
(8749, '950506', 'ตาชี   ', 981, 75, 6),
(8750, '950507', 'บาโงยซิแน   ', 981, 75, 6),
(8751, '950508', 'กาตอง   ', 981, 75, 6),
(8752, '950509', '*บาละ   ', 981, 75, 6),
(8753, '950599', '*กาบัง   ', 981, 75, 6),
(8754, '950601', 'กายูบอเกาะ   ', 982, 75, 6),
(8755, '950602', 'กาลูปัง   ', 982, 75, 6),
(8756, '950603', 'กาลอ   ', 982, 75, 6),
(8757, '950604', 'กอตอตือร๊ะ   ', 982, 75, 6),
(8758, '950605', 'โกตาบารู   ', 982, 75, 6),
(8759, '950606', 'เกะรอ   ', 982, 75, 6),
(8760, '950607', 'จะกว๊ะ   ', 982, 75, 6),
(8761, '950608', 'ท่าธง   ', 982, 75, 6),
(8762, '950609', 'เนินงาม   ', 982, 75, 6),
(8763, '950610', 'บาลอ   ', 982, 75, 6),
(8764, '950611', 'บาโงย   ', 982, 75, 6),
(8765, '950612', 'บือมัง   ', 982, 75, 6),
(8766, '950613', 'ยะต๊ะ   ', 982, 75, 6),
(8767, '950614', 'วังพญา   ', 982, 75, 6),
(8768, '950615', 'อาซ่อง   ', 982, 75, 6),
(8769, '950616', 'ตะโล๊ะหะลอ   ', 982, 75, 6),
(8770, '950701', 'กาบัง   ', 983, 75, 6),
(8771, '950702', 'บาละ   ', 983, 75, 6),
(8772, '950801', 'กรงปินัง   ', 984, 75, 6),
(8773, '950802', 'สะเอะ   ', 984, 75, 6),
(8774, '950803', 'ห้วยกระทิง   ', 984, 75, 6),
(8775, '950804', 'ปุโรง   ', 984, 75, 6),
(8776, '960101', 'บางนาค   ', 985, 76, 6),
(8777, '960102', 'ลำภู   ', 985, 76, 6),
(8778, '960103', 'มะนังตายอ   ', 985, 76, 6),
(8779, '960104', 'บางปอ   ', 985, 76, 6),
(8780, '960105', 'กะลุวอ   ', 985, 76, 6),
(8781, '960106', 'กะลุวอเหนือ   ', 985, 76, 6),
(8782, '960107', 'โคกเคียน   ', 985, 76, 6),
(8783, '960201', 'เจ๊ะเห   ', 986, 76, 6),
(8784, '960202', 'ไพรวัน   ', 986, 76, 6),
(8785, '960203', 'พร่อน   ', 986, 76, 6),
(8786, '960204', 'ศาลาใหม่   ', 986, 76, 6),
(8787, '960205', 'บางขุนทอง   ', 986, 76, 6),
(8788, '960206', 'เกาะสะท้อน   ', 986, 76, 6),
(8789, '960207', 'นานาค   ', 986, 76, 6),
(8790, '960208', 'โฆษิต   ', 986, 76, 6),
(8791, '960301', 'บาเจาะ   ', 987, 76, 6),
(8792, '960302', 'ลุโบะสาวอ   ', 987, 76, 6),
(8793, '960303', 'กาเยาะมาตี   ', 987, 76, 6),
(8794, '960304', 'ปะลุกาสาเมาะ   ', 987, 76, 6),
(8795, '960305', 'บาเระเหนือ   ', 987, 76, 6),
(8796, '960306', 'บาเระใต้   ', 987, 76, 6),
(8797, '960401', 'ยี่งอ   ', 988, 76, 6),
(8798, '960402', 'ละหาร   ', 988, 76, 6),
(8799, '960403', 'จอเบาะ   ', 988, 76, 6),
(8800, '960404', 'ลุโบะบายะ   ', 988, 76, 6),
(8801, '960405', 'ลุโบะบือซา   ', 988, 76, 6),
(8802, '960406', 'ตะปอเยาะ   ', 988, 76, 6),
(8803, '960501', 'ตันหยงมัส   ', 989, 76, 6),
(8804, '960502', 'ตันหยงลิมอ   ', 989, 76, 6),
(8805, '960503', '*จวบ   ', 989, 76, 6),
(8806, '960504', '*มะรือโบตะวันออก   ', 989, 76, 6),
(8807, '960505', '*บูกิต   ', 989, 76, 6),
(8808, '960506', 'บองอ   ', 989, 76, 6),
(8809, '960507', 'กาลิซา   ', 989, 76, 6),
(8810, '960508', 'บาโงสะโต   ', 989, 76, 6),
(8811, '960509', 'เฉลิม   ', 989, 76, 6),
(8812, '960510', 'มะรือโบตก   ', 989, 76, 6),
(8813, '960598', '*ดุซงญอ   ', 989, 76, 6),
(8814, '960599', '*จะแนะ   ', 989, 76, 6),
(8815, '960601', 'รือเสาะ   ', 990, 76, 6),
(8816, '960602', 'สาวอ   ', 990, 76, 6),
(8817, '960603', 'เรียง   ', 990, 76, 6),
(8818, '960604', 'สามัคคี   ', 990, 76, 6),
(8819, '960605', 'บาตง   ', 990, 76, 6),
(8820, '960606', 'ลาโละ   ', 990, 76, 6),
(8821, '960607', 'รือเสาะออก   ', 990, 76, 6),
(8822, '960608', 'โคกสะตอ   ', 990, 76, 6),
(8823, '960609', 'สุวารี   ', 990, 76, 6),
(8824, '960698', '*ตะมะยูง   ', 990, 76, 6),
(8825, '960699', '*ชากอ   ', 990, 76, 6),
(8826, '960701', 'ซากอ   ', 991, 76, 6),
(8827, '960702', 'ตะมะยูง   ', 991, 76, 6),
(8828, '960703', 'ศรีสาคร   ', 991, 76, 6),
(8829, '960704', 'เชิงคีรี   ', 991, 76, 6),
(8830, '960705', 'กาหลง   ', 991, 76, 6),
(8831, '960706', 'ศรีบรรพต   ', 991, 76, 6),
(8832, '960801', 'แว้ง   ', 992, 76, 6),
(8833, '960802', 'กายูคละ   ', 992, 76, 6),
(8834, '960803', 'ฆอเลาะ   ', 992, 76, 6),
(8835, '960804', 'โละจูด   ', 992, 76, 6),
(8836, '960805', 'แม่ดง   ', 992, 76, 6),
(8837, '960806', 'เอราวัณ   ', 992, 76, 6),
(8838, '960899', '*มาโม   ', 992, 76, 6),
(8839, '960901', 'มาโมง   ', 993, 76, 6),
(8840, '960902', 'สุคิริน   ', 993, 76, 6),
(8841, '960903', 'เกียร์   ', 993, 76, 6),
(8842, '960904', 'ภูเขาทอง   ', 993, 76, 6),
(8843, '960905', 'ร่มไทร   ', 993, 76, 6),
(8844, '961001', 'สุไหงโก-ลก   ', 994, 76, 6),
(8845, '961002', 'ปาเสมัส   ', 994, 76, 6),
(8846, '961003', 'มูโนะ   ', 994, 76, 6),
(8847, '961004', 'ปูโยะ   ', 994, 76, 6),
(8848, '961101', 'ปะลุรู   ', 995, 76, 6),
(8849, '961102', 'สุไหงปาดี   ', 995, 76, 6),
(8850, '961103', 'โต๊ะเด็ง   ', 995, 76, 6),
(8851, '961104', 'สากอ   ', 995, 76, 6),
(8852, '961105', 'ริโก๋   ', 995, 76, 6),
(8853, '961106', 'กาวะ   ', 995, 76, 6),
(8854, '961201', 'จะแนะ   ', 996, 76, 6),
(8855, '961202', 'ดุซงญอ   ', 996, 76, 6),
(8856, '961203', 'ผดุงมาตร   ', 996, 76, 6),
(8857, '961204', 'ช้างเผือก   ', 996, 76, 6),
(8858, '961301', 'จวบ   ', 997, 76, 6),
(8859, '961302', 'บูกิต   ', 997, 76, 6),
(8860, '961303', 'มะรือโบออก   ', 997, 76, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tb_exchange_rate_baht`
--

CREATE TABLE `tb_exchange_rate_baht` (
  `exchange_rate_baht_id` int(11) NOT NULL COMMENT 'รหัสการแลกเปลี่ยนเงินบาท',
  `currency_id` int(11) NOT NULL COMMENT 'สกุลเงิน',
  `exchange_rate_baht_date` varchar(50) NOT NULL COMMENT 'วันที่',
  `exchange_rate_baht_value` double NOT NULL COMMENT 'ค่าเงินบาท'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_credit`
--

CREATE TABLE `tb_finance_credit` (
  `finance_credit_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบจ่ายชำระหนี้',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'ผู้ออกใบจ่ายชำระหนี้',
  `finance_credit_code` varchar(100) NOT NULL COMMENT 'หมายเลขใบจ่ายชำระหนี้',
  `finance_credit_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบจ่ายชำระหนี้',
  `finance_credit_date_pay` varchar(50) NOT NULL COMMENT 'วันที่จ่ายชำระหนี้',
  `finance_credit_name` varchar(100) NOT NULL COMMENT 'ชื่อบริษัท',
  `finance_credit_address` text NOT NULL COMMENT 'ที่อยู่',
  `finance_credit_tax` varchar(50) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `finance_credit_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `finance_credit_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `finance_credit_sent_name` int(11) NOT NULL COMMENT 'ชื่อผู้จ่ายชำระหนี้',
  `finance_credit_recieve_name` int(11) NOT NULL COMMENT 'ชื่อผู้จ่ายจ่ายชำระหนี้',
  `finance_credit_total` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `finance_credit_interest` double NOT NULL COMMENT 'ดอกเบี้ยจ่าย',
  `finance_credit_cash` double NOT NULL COMMENT 'เงินสด',
  `finance_credit_other_pay` double NOT NULL COMMENT 'ชำระโดยอื่นๆ',
  `finance_credit_tax_pay` double NOT NULL COMMENT 'ภาษีหัก ณ ที่จ่าย',
  `finance_credit_discount_cash` double NOT NULL COMMENT 'ส่วนลดเงินสด',
  `finance_credit_pay` double NOT NULL COMMENT 'ยอดจ่ายจริง',
  `finance_credit_total_text` text NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางใบจ่ายชำระหนี้';

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_credit_account`
--

CREATE TABLE `tb_finance_credit_account` (
  `finance_credit_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงวิธีจ่ายชำระหนี้',
  `finance_credit_account_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'หมายเลขวิธีจ่ายชำระหนี้',
  `finance_credit_account_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อ',
  `finance_credit_account_cheque` int(11) NOT NULL COMMENT 'ประเภทเช็ค 1=เช็ค',
  `bank_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดบัญชีธนาคาร',
  `account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชี',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_credit_list`
--

CREATE TABLE `tb_finance_credit_list` (
  `finance_credit_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบจ่ายชำระหนี้',
  `finance_credit_id` int(11) NOT NULL COMMENT 'รหัสรายการใบจ่ายชำระหนี้',
  `invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสใบกำกับภาษี',
  `finance_credit_list_recieve` varchar(100) NOT NULL COMMENT 'ใบรับวางบิล',
  `finance_credit_list_receipt` varchar(100) NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `finance_credit_list_amount` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `finance_credit_list_paid` double NOT NULL COMMENT 'จำนวนเงินที่จ่าย',
  `finance_credit_list_balance` double NOT NULL COMMENT 'ยอดคงเหลือ',
  `finance_credit_list_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_credit_pay`
--

CREATE TABLE `tb_finance_credit_pay` (
  `finance_credit_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการจ่ายค่าใช้จ่ายอื่นๆ',
  `check_pay_id` int(11) NOT NULL,
  `finance_credit_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบจ่ายเงิน',
  `finance_credit_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงวิธีการจ่ายเงิน',
  `finance_credit_pay_by` varchar(200) NOT NULL COMMENT 'จ่ายโดย',
  `finance_credit_pay_date` varchar(50) NOT NULL COMMENT 'ลงวันที่',
  `bank_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดบัญชีธนาคาร',
  `finance_credit_pay_bank` varchar(200) NOT NULL COMMENT 'ธนาคาร',
  `account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชี',
  `finance_credit_pay_value` double NOT NULL COMMENT 'จำนวนเงิน',
  `finance_credit_pay_balance` double NOT NULL COMMENT 'ยอดคงเหลือ',
  `finance_credit_pay_total` double NOT NULL COMMENT 'ยอดชำระ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_debit`
--

CREATE TABLE `tb_finance_debit` (
  `finance_debit_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบรับชำระหนี้',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'ผู้ออกใบรับชำระหนี้',
  `finance_debit_code` varchar(100) NOT NULL COMMENT 'หมายเลขใบรับชำระหนี้',
  `finance_debit_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบรับชำระหนี้',
  `finance_debit_date_pay` varchar(50) NOT NULL COMMENT 'วันที่รับชำระหนี้',
  `finance_debit_name` varchar(100) NOT NULL COMMENT 'ชื่อบริษัท',
  `finance_debit_address` text NOT NULL COMMENT 'ที่อยู่',
  `finance_debit_tax` varchar(50) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `finance_debit_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `finance_debit_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `finance_debit_sent_name` int(11) NOT NULL COMMENT 'ชื่อผู้รับชำระหนี้',
  `finance_debit_recieve_name` int(11) NOT NULL COMMENT 'ชื่อผู้รับรับชำระหนี้',
  `finance_debit_total` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `finance_debit_interest` double NOT NULL COMMENT 'ดอกเบี้ยจ่าย',
  `finance_debit_cash` double NOT NULL COMMENT 'เงินสด',
  `finance_debit_other_pay` double NOT NULL COMMENT 'ชำระโดยอื่นๆ',
  `finance_debit_tax_pay` double NOT NULL COMMENT 'ภาษีหัก ณ ที่จ่าย',
  `finance_debit_discount_cash` double NOT NULL COMMENT 'ส่วนลดเงินสด',
  `finance_debit_pay` double NOT NULL COMMENT 'ยอดจ่ายจริง',
  `finance_debit_total_text` text NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางใบรับชำระหนี้';

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_debit_account`
--

CREATE TABLE `tb_finance_debit_account` (
  `finance_debit_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงวิธีรับชำระหนี้',
  `finance_debit_account_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'หมายเลขวิธีรับชำระหนี้',
  `finance_debit_account_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อ',
  `finance_debit_account_cheque` int(11) NOT NULL COMMENT 'ประเภทเช็ค 1=เช็ค',
  `bank_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดบัญชีธนาคาร',
  `account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชี',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_debit_list`
--

CREATE TABLE `tb_finance_debit_list` (
  `finance_debit_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบรับชำระหนี้',
  `finance_debit_id` int(11) NOT NULL COMMENT 'รหัสรายการใบรับชำระหนี้',
  `billing_note_list_id` int(11) NOT NULL COMMENT 'รหัสใบวางบิล',
  `finance_debit_list_billing` varchar(100) NOT NULL COMMENT 'หมายเลขใบวางบิล',
  `finance_debit_list_receipt` varchar(100) NOT NULL COMMENT 'หมายเลขใบเสร็จ',
  `finance_debit_list_amount` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `finance_debit_list_paid` double NOT NULL COMMENT 'จำนวนเงินที่จ่าย',
  `finance_debit_list_balance` double NOT NULL COMMENT 'ยอดคงเหลือ',
  `finance_debit_list_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance_debit_pay`
--

CREATE TABLE `tb_finance_debit_pay` (
  `finance_debit_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการจ่ายค่าใช้จ่ายอื่นๆ',
  `check_id` int(11) NOT NULL,
  `finance_debit_id` int(11) NOT NULL,
  `finance_debit_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงวิธีการจ่ายเงิน',
  `finance_debit_pay_by` varchar(200) NOT NULL COMMENT 'จ่ายโดย',
  `finance_debit_pay_date` varchar(50) NOT NULL COMMENT 'ลงวันที่',
  `bank_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดบัญชีธนาคาร',
  `finance_debit_pay_bank` varchar(200) NOT NULL COMMENT 'ธนาคาร',
  `account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชี',
  `finance_debit_pay_value` double NOT NULL COMMENT 'จำนวนเงิน',
  `finance_debit_pay_balance` double NOT NULL COMMENT 'ยอดคงเหลือ',
  `finance_debit_pay_total` double NOT NULL COMMENT 'ยอดชำระ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_holiday`
--

CREATE TABLE `tb_holiday` (
  `holiday_id` int(11) NOT NULL,
  `holiday_name` varchar(100) NOT NULL,
  `all_week` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_invoice_customer`
--

CREATE TABLE `tb_invoice_customer` (
  `invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิง Invoice ผู้ซื้อ',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ออก Invoice',
  `invoice_customer_code` varchar(50) NOT NULL COMMENT 'หมายเลข Invoice ผู้ซื้อ',
  `invoice_customer_total_price` double NOT NULL COMMENT 'ราคารวม',
  `invoice_customer_vat` double NOT NULL COMMENT 'ค่า Vat',
  `invoice_customer_vat_price` double NOT NULL COMMENT 'ราคา Vat',
  `invoice_customer_net_price` double NOT NULL COMMENT 'ราคาสุทธิ',
  `invoice_customer_net_price_total` text NOT NULL,
  `invoice_customer_date` varchar(50) NOT NULL COMMENT 'วันที่เปิด Invoice',
  `invoice_customer_name` varchar(200) NOT NULL COMMENT 'ชื่อผู้ซื้อ',
  `invoice_customer_address` text NOT NULL COMMENT 'ที่อยู่ผู้ซื้อ',
  `invoice_customer_tax` varchar(20) NOT NULL COMMENT 'เลขผู้เสียภาษีผู้ซื้อ',
  `invoice_customer_branch` varchar(50) NOT NULL COMMENT 'สาขา',
  `invoice_customer_due` varchar(50) NOT NULL COMMENT 'กำหนดชำระ',
  `invoice_customer_due_day` int(11) NOT NULL COMMENT 'จำนวนวันเครดิต',
  `invoice_customer_term` varchar(100) NOT NULL COMMENT 'เงือนไขการชาระเงิน',
  `invoice_customer_rewrite_id` int(11) NOT NULL,
  `invoice_customer_close` int(11) DEFAULT '0',
  `invoice_customer_begin` int(11) NOT NULL COMMENT '1 = Invoice ลูกหนี้ยกยอดมา',
  `vat_section` varchar(10) NOT NULL COMMENT 'มูลค่าสินค้าขอคืนได้',
  `vat_section_add` varchar(10) NOT NULL COMMENT 'จำนวนภาษีขอคืนได้',
  `invoice_customer_total_price_non` double NOT NULL COMMENT 'มูลค่าสินค้าขอคืนไม่ได้',
  `invoice_customer_vat_price_non` double NOT NULL COMMENT 'จำนวนภาษีขอคืนไม่ได้',
  `invoice_customer_total_non` double NOT NULL COMMENT 'มูลค่าสินค้าหรือบริการอัตราศูนย์',
  `invoice_customer_description` text NOT NULL COMMENT 'คำอธิบาย',
  `invoice_customer_remark` text NOT NULL COMMENT 'รายละเอียด',
  `addby` int(11) NOT NULL COMMENT 'เพิ่มโดย',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'แก้ไขโดย',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันเวลาที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_invoice_customer_list`
--

CREATE TABLE `tb_invoice_customer_list` (
  `invoice_customer_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการ Invoice ผู้ซื้อ',
  `invoice_customer_id` int(11) NOT NULL,
  `invoice_customer_list_no` int(11) NOT NULL COMMENT 'ลำดับรายการ',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `invoice_customer_list_product_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `invoice_customer_list_product_detail` varchar(100) NOT NULL COMMENT 'รายละเอียดสินค้า',
  `invoice_customer_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `invoice_customer_list_price` double NOT NULL COMMENT 'ราคาสินค้า',
  `invoice_customer_list_total` double NOT NULL COMMENT 'ราคารวม',
  `invoice_customer_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `customer_purchase_order_list_id` int(11) NOT NULL,
  `stock_group_id` int(11) NOT NULL,
  `invoice_customer_list_cost` double NOT NULL COMMENT 'ต้นทุนที่แท้จริง',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_invoice_supplier`
--

CREATE TABLE `tb_invoice_supplier` (
  `invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิง Invoice ผู้ขาย',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้รับ Invoice',
  `invoice_supplier_code` varchar(50) NOT NULL COMMENT 'หมายเลข Invoice ผู้ซื้อ',
  `invoice_supplier_code_gen` varchar(20) NOT NULL,
  `invoice_supplier_total_price` double NOT NULL COMMENT 'ราคารวม',
  `invoice_supplier_vat` double NOT NULL COMMENT 'ค่า Vat',
  `invoice_supplier_vat_price` double NOT NULL COMMENT 'ราคา Vat',
  `invoice_supplier_net_price` double NOT NULL COMMENT 'ราคาสุทธิ',
  `invoice_supplier_net_price_text` text NOT NULL,
  `invoice_supplier_date` varchar(50) NOT NULL COMMENT 'วันที่เปิด Invoice',
  `invoice_supplier_date_recieve` varchar(50) NOT NULL,
  `invoice_supplier_name` varchar(200) NOT NULL COMMENT 'ชื่อผู้ขาย',
  `invoice_supplier_address` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย',
  `invoice_supplier_tax` varchar(20) NOT NULL COMMENT 'เลขผู้เสียภาษีผู้ขาย',
  `invoice_supplier_branch` varchar(50) NOT NULL COMMENT 'สาขา',
  `invoice_supplier_term` varchar(100) NOT NULL COMMENT 'เงือนไขการชาระเงิน',
  `invoice_supplier_due` varchar(50) NOT NULL COMMENT 'กำหนดชำระ',
  `invoice_supplier_due_day` int(11) NOT NULL COMMENT 'จำนวนวันเครดิต',
  `invoice_supplier_begin` int(11) NOT NULL COMMENT '1 = Invoice เจ้าหนี้ยกยอดมา',
  `import_duty` double NOT NULL COMMENT 'ภาษีนำเข้า',
  `freight_in` double NOT NULL COMMENT 'ค่าขนส่ง',
  `vat_section` varchar(10) NOT NULL COMMENT 'มูลค่าสินค้าขอคืนได้',
  `vat_section_add` varchar(10) NOT NULL COMMENT 'จำนวนภาษีขอคืนได้',
  `invoice_supplier_total_price_non` double NOT NULL COMMENT 'มูลค่าสินค้าขอคืนไม่ได้',
  `invoice_supplier_vat_price_non` double NOT NULL COMMENT 'จำนวนภาษีขอคืนไม่ได้',
  `invoice_supplier_total_non` double NOT NULL COMMENT 'มูลค่าสินค้าหรือบริการอัตราศูนย์',
  `invoice_supplier_description` text NOT NULL COMMENT 'คำอธิบาย',
  `invoice_supplier_remark` text NOT NULL COMMENT 'รายละเอียด',
  `addby` int(11) NOT NULL COMMENT 'เพิ่มโดย',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'แก้ไขโดย',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันเวลาที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_invoice_supplier_list`
--

CREATE TABLE `tb_invoice_supplier_list` (
  `invoice_supplier_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการ Invoice ผู้ขาย',
  `invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบกำกับภาษี',
  `invoice_supplier_list_no` int(11) NOT NULL COMMENT 'ลำดับรายการ',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `invoice_supplier_list_product_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `invoice_supplier_list_product_detail` varchar(100) NOT NULL COMMENT 'รายละเอียดสินค้า',
  `invoice_supplier_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `invoice_supplier_list_price` double NOT NULL COMMENT 'ราคาสินค้า',
  `invoice_supplier_list_total` double NOT NULL COMMENT 'ราคารวม',
  `invoice_supplier_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบสั่งซื้อสินค้า',
  `stock_group_id` int(11) NOT NULL COMMENT 'สินค้าลงคลังไหน',
  `invoice_supplier_list_cost` double NOT NULL COMMENT 'ต้นทุนที่แท้จริง',
  `invoice_supplier_list_cost_fix` double NOT NULL COMMENT 'ภาษีนำเข้า',
  `invoice_supplier_list_duty_percent` double NOT NULL COMMENT 'เปอร์เซนต์ภาษีนำเข้า',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_job`
--

CREATE TABLE `tb_job` (
  `job_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงงาน',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `job_code` varchar(50) NOT NULL COMMENT 'หมายเลขงาน',
  `job_name` varchar(200) NOT NULL COMMENT 'ชื่องาน',
  `job_cost` float NOT NULL COMMENT 'ต้นทุนต่อชิ้น',
  `job_price` float NOT NULL COMMENT 'ราคาต่อชิ้น',
  `job_production` float NOT NULL COMMENT 'จำนวนยอดการผลิตต่อเดือน',
  `job_remark` varchar(500) NOT NULL COMMENT 'หมายเหตุ',
  `job_drawing` varchar(200) NOT NULL COMMENT 'ภาพแบบ',
  `job_start` varchar(50) NOT NULL COMMENT 'วันที่เริ่มงาน',
  `job_end` varchar(50) NOT NULL COMMENT 'วันที่สิ้นสุดงาน',
  `job_active` int(11) NOT NULL COMMENT 'สถานะ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_job_operation`
--

CREATE TABLE `tb_job_operation` (
  `job_operation_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงขั้นตอนการผลิต',
  `job_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงงาน',
  `job_operation_no` int(11) NOT NULL COMMENT 'ลำดับขั้นตอนการผลิต',
  `job_operation_name` int(11) NOT NULL COMMENT 'ชื่อขั้นตอนการผลิต',
  `job_operation_remark` int(11) NOT NULL COMMENT 'หมายเหตุ',
  `job_operation_drawing` varchar(200) NOT NULL COMMENT 'ภาพแบบ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_job_operation_process`
--

CREATE TABLE `tb_job_operation_process` (
  `job_operation_process_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงกระบวนการผลิต',
  `job_operation_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงขั้นตอนการผลิต',
  `job_operation_process_no` int(11) NOT NULL COMMENT 'ลำดับกระบวนการ',
  `job_operation_process_name` varchar(200) NOT NULL COMMENT 'ชื่อกระบวนการ',
  `job_operation_process_remark` varchar(500) NOT NULL COMMENT 'หมายเหตุ',
  `job_operation_process_drawing` varchar(200) NOT NULL COMMENT 'ภาพแบบ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_job_operation_process_tool`
--

CREATE TABLE `tb_job_operation_process_tool` (
  `job_operation_process_tool_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเครื่องมือในกระบวนการผลิต',
  `job_operation_process_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงกระบวนการผลิต',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `job_operation_process_tool_toollife` varchar(100) NOT NULL COMMENT 'อายุการใช้งาน',
  `job_operation_process_tool_remark` varchar(500) NOT NULL COMMENT 'หมายเหตุ',
  `job_operation_process_tool_active` int(11) NOT NULL COMMENT 'สถานะ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal`
--

CREATE TABLE `tb_journal` (
  `journal_id` int(11) NOT NULL,
  `journal_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_journal`
--

INSERT INTO `tb_journal` (`journal_id`, `journal_name`) VALUES
(1, 'สมุดรายวันซื่อสินค้า'),
(2, 'สมุดรายวันส่งคืนสินค้าและจำนวนที่ได้ลด'),
(3, 'สมุดรายวันขายสินค้า'),
(4, 'สมุดรายวันรับคืนสินค้าและจำนวนที่ลดให้'),
(5, 'สมุดรายวันรับเงิน'),
(6, 'สมุดรายวันจ่ายเงิน'),
(7, 'สมุดรายวันทั่วไป');

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_cash_payment`
--

CREATE TABLE `tb_journal_cash_payment` (
  `journal_cash_payment_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันจ่ายเงิน',
  `finance_credit_id` int(11) NOT NULL,
  `journal_cash_payment_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันจ่ายเงิน',
  `journal_cash_payment_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันซื้อ',
  `journal_cash_payment_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_cash_payment_invoice`
--

CREATE TABLE `tb_journal_cash_payment_invoice` (
  `journal_cash_payment_invoice_id` int(11) NOT NULL,
  `journal_cash_payment_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `invoice_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_date` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vat_section` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vat_section_add` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `product_price` double NOT NULL,
  `product_vat` double NOT NULL,
  `product_price_non` double NOT NULL,
  `product_vat_non` double NOT NULL,
  `product_non` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_cash_payment_list`
--

CREATE TABLE `tb_journal_cash_payment_list` (
  `journal_cash_payment_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันจ่ายเงิน',
  `journal_cash_payment_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันจ่ายเงิน',
  `journal_cash_payment_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันจ่ายเงิน',
  `journal_cash_payment_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_cash_payment_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `finance_credit_pay_id` int(11) NOT NULL COMMENT '-1 = จ่ายชำระแบบเงินสด',
  `journal_cheque_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็ครับ',
  `journal_cheque_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็คจ่าย',
  `journal_invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `journal_invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_cash_receipt`
--

CREATE TABLE `tb_journal_cash_receipt` (
  `journal_cash_receipt_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันรับเงิน',
  `journal_cash_receipt_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันรับเงิน',
  `journal_cash_receipt_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันซื้อ',
  `journal_cash_receipt_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล',
  `finance_debit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_cash_receipt_list`
--

CREATE TABLE `tb_journal_cash_receipt_list` (
  `journal_cash_receipt_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันรับเงิน',
  `journal_cash_receipt_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันรับเงิน',
  `journal_cash_receipt_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันรับเงิน',
  `journal_cash_receipt_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_cash_receipt_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `finance_debit_pay_id` int(11) NOT NULL,
  `journal_cheque_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็ครับ',
  `journal_cheque_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็คจ่าย',
  `journal_invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `journal_invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_general`
--

CREATE TABLE `tb_journal_general` (
  `journal_general_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันทั่วไป',
  `journal_general_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันทั่วไป',
  `journal_general_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันทั่วไป',
  `journal_general_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_general_list`
--

CREATE TABLE `tb_journal_general_list` (
  `journal_general_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันทั่วไป',
  `journal_general_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันทั่วไป',
  `journal_general_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันทั่วไป',
  `journal_general_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_general_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `journal_cheque_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็ครับ',
  `journal_cheque_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็คจ่าย',
  `journal_invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `journal_invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_purchase`
--

CREATE TABLE `tb_journal_purchase` (
  `journal_purchase_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันซื้อ',
  `invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `journal_purchase_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันซื้อ',
  `journal_purchase_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันซื้อ',
  `journal_purchase_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_purchase_list`
--

CREATE TABLE `tb_journal_purchase_list` (
  `journal_purchase_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันซื้อ',
  `journal_purchase_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันซื้อ',
  `journal_purchase_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันซื้อ',
  `journal_purchase_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_purchase_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `journal_cheque_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็ครับ',
  `journal_cheque_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็คจ่าย',
  `journal_invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `journal_invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_purchase_return`
--

CREATE TABLE `tb_journal_purchase_return` (
  `journal_purchase_return_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันส่งคืนสินค้า',
  `journal_purchase_return_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันส่งคืนสินค้า',
  `journal_purchase_return_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันซื้อ',
  `journal_purchase_return_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_purchase_return_list`
--

CREATE TABLE `tb_journal_purchase_return_list` (
  `journal_purchase_return_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันส่งคืนสินค้า',
  `journal_purchase_return_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันส่งคืนสินค้า',
  `journal_purchase_return_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันส่งคืนสินค้า',
  `journal_purchase_return_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_purchase_return_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_sale`
--

CREATE TABLE `tb_journal_sale` (
  `journal_sale_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันขาย',
  `journal_sale_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันขาย',
  `invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `journal_sale_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันซื้อ',
  `journal_sale_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_sale_list`
--

CREATE TABLE `tb_journal_sale_list` (
  `journal_sale_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันขาย',
  `journal_sale_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันขาย',
  `journal_sale_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันขาย',
  `journal_sale_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_sale_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `journal_cheque_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็ครับ',
  `journal_cheque_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงเช็คจ่าย',
  `journal_invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `journal_invoice_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_sale_return`
--

CREATE TABLE `tb_journal_sale_return` (
  `journal_sale_return_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันรับคืนสินค้า',
  `journal_sale_return_code` varchar(50) NOT NULL COMMENT 'หมายเลขสมุดรายวันรับคืนสินค้า',
  `credit_note_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาใบลดหนี้',
  `journal_sale_return_name` varchar(500) NOT NULL COMMENT 'หัวข้อสมุดรายวันซื้อ',
  `journal_sale_return_date` varchar(50) NOT NULL COMMENT 'วันที่ออกสมดรายวัน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_journal_sale_return_list`
--

CREATE TABLE `tb_journal_sale_return_list` (
  `journal_sale_return_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการสมุดรายวันรับคืนสินค้า',
  `journal_sale_return_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสมุดรายวันรับคืนสินค้า',
  `journal_sale_return_list_name` varchar(500) NOT NULL COMMENT 'หัวข้อรายการสมุดรายวันรับคืนสินค้า',
  `journal_sale_return_list_debit` double NOT NULL COMMENT 'มูลค่ารับ',
  `journal_sale_return_list_credit` double NOT NULL COMMENT 'มูลค่าจ่าย',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_license`
--

CREATE TABLE `tb_license` (
  `license_id` int(11) NOT NULL,
  `license_name` varchar(100) NOT NULL,
  `license_admin_page` varchar(50) NOT NULL,
  `license_sale_employee_page` varchar(50) NOT NULL,
  `license_request_page` varchar(50) NOT NULL,
  `license_delivery_note_page` varchar(50) NOT NULL,
  `license_regrind_page` varchar(50) NOT NULL,
  `license_purchase_page` varchar(10) NOT NULL,
  `license_sale_page` varchar(10) NOT NULL,
  `license_inventery_page` varchar(10) NOT NULL,
  `license_account_page` varchar(10) NOT NULL,
  `license_report_page` varchar(10) NOT NULL,
  `license_manager_page` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_license`
--

INSERT INTO `tb_license` (`license_id`, `license_name`, `license_admin_page`, `license_sale_employee_page`, `license_request_page`, `license_delivery_note_page`, `license_regrind_page`, `license_purchase_page`, `license_sale_page`, `license_inventery_page`, `license_account_page`, `license_report_page`, `license_manager_page`) VALUES
(1, 'สิทธิ์การใช้งานที่ 1 (ผู้ดูแลระบบ)', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High'),
(3, 'สิทธิ์การใช้งาน Sales & Application Engineer', 'No', 'Low', 'Low', 'Low', 'Low', 'Low', 'Low', 'Low', 'No', 'No', 'No'),
(4, 'สิทธิ์การใช้งาน Sales & Application Manager', 'No', 'Medium', 'Medium', 'Medium', 'Medium', 'Low', 'Medium', 'Low', 'No', 'No', 'No'),
(5, 'สิทธิ์การใช้งาน Sales Coordinator', 'Medium', 'Medium', 'Medium', 'Medium', 'Medium', 'Medium', 'Medium', 'Medium', 'No', 'No', 'No'),
(6, 'สิทธิ์การใช้งาน Project and Application', 'No', 'No', 'Low', 'Low', 'Low', 'No', 'No', 'Low', 'No', 'No', 'No'),
(7, 'สิทธิ์การใช้งาน Account', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High'),
(8, 'สิทธิ์การใช้งาน Managing Director', 'Low', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High'),
(9, 'สิทธิ์การใช้งานอื่นๆ', 'No', 'No', 'No', 'No', 'No', 'No', 'No', 'Low', 'No', 'No', 'No'),
(10, 'สิทธิ์การใช้งานที่ 1 (ผู้ดูแลระบบ ไม่มีบัญชี)', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'High', 'No', 'No', 'High');

-- --------------------------------------------------------

--
-- Table structure for table `tb_main_setting`
--

CREATE TABLE `tb_main_setting` (
  `main_setting_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงองค์กร',
  `organize_name_th` varchar(200) NOT NULL COMMENT 'ชื่อองค์กรภาษาไทย',
  `organize_name_en` varchar(200) NOT NULL COMMENT 'ชื่อองค์กรภาษาอังกฤษ',
  `organize_tax` varchar(20) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `organize_address_1` text NOT NULL COMMENT 'ที่อยู่ 1',
  `organize_address_2` text NOT NULL COMMENT 'ที่อยู่ 2',
  `organize_address_3` text NOT NULL COMMENT 'ที่อยู่ 3',
  `organize_zipcode` varchar(10) NOT NULL COMMENT 'เลขไปรษณีย์',
  `organize_tel` varchar(100) NOT NULL COMMENT 'หมายเลขโทรศัพท์',
  `organize_email` varchar(200) NOT NULL COMMENT 'อีเมล์',
  `organize_fax` varchar(100) NOT NULL COMMENT 'แฟก',
  `organize_logo` varchar(200) NOT NULL COMMENT 'รูปองค์กร',
  `organize_them_color` varchar(10) NOT NULL COMMENT 'ลักษณะสีของเว็บ',
  `organize_enable` varchar(10) NOT NULL COMMENT 'เปิดใช้งาน',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_notification`
--

CREATE TABLE `tb_notification` (
  `notification_id` int(11) NOT NULL COMMENT 'รหัสการแจ้งเตือน',
  `user_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `notification_type` varchar(50) NOT NULL COMMENT 'ประเภทการแจ้งเตือน',
  `notification_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงหมายเลขเอกสารที่ใช้ระบุตามประเภทเอกสาร',
  `notification_seen` varchar(50) NOT NULL COMMENT 'สถานะการตรวจสอบ',
  `notification_date` varchar(50) NOT NULL COMMENT 'วันที่แจ้งเตือน',
  `notification_seen_date` varchar(50) NOT NULL COMMENT 'วันที่ตรวจสอบการแจ้งเตือน',
  `notification_detail` text NOT NULL COMMENT 'รายละเอียดการแจ้งเตือน',
  `notification_url` text NOT NULL COMMENT 'ลิงค์เพื่อตรวจสอบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='3';

-- --------------------------------------------------------

--
-- Table structure for table `tb_official_receipt`
--

CREATE TABLE `tb_official_receipt` (
  `official_receipt_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบวางบิล',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'ผู้ออกใบวางบิล',
  `official_receipt_code` varchar(100) NOT NULL COMMENT 'หมายเลขใบวางบิล',
  `official_receipt_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบวางบิล',
  `official_receipt_name` varchar(100) NOT NULL COMMENT 'ชื่อบริษัท',
  `official_receipt_address` text NOT NULL COMMENT 'ที่อยู่',
  `official_receipt_tax` varchar(50) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `official_receipt_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `official_receipt_sent_name` int(11) NOT NULL COMMENT 'ชื่อผู้วางบิล',
  `official_receipt_recieve_name` int(11) NOT NULL COMMENT 'ชื่อผู้รับวางบิล',
  `official_receipt_total` double NOT NULL COMMENT 'จำนวนเงินรวม',
  `official_receipt_total_text` text NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ตารางใบวางบิล';

-- --------------------------------------------------------

--
-- Table structure for table `tb_official_receipt_list`
--

CREATE TABLE `tb_official_receipt_list` (
  `official_receipt_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบวางบิล',
  `official_receipt_id` int(11) NOT NULL COMMENT 'รหัสรายการใบวางบิล',
  `billing_note_list_id` int(11) NOT NULL COMMENT 'รหัสใบวางบิล',
  `official_receipt_inv_amount` double NOT NULL COMMENT 'ยอดคงเหลือใบกำกับภาษี',
  `official_receipt_bal_amount` double NOT NULL COMMENT 'ยอดเงินที่จ่าย',
  `official_receipt_list_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_other_expense`
--

CREATE TABLE `tb_other_expense` (
  `other_expense_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบันทึกค่าใช้จ่ายอื่นๆ',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `other_expense_code` varchar(50) NOT NULL COMMENT 'หมายเลขบันทึกค่าใช้จ่ายอื่นๆ',
  `other_expense_date` varchar(50) NOT NULL COMMENT 'วันที่บันทึกค่าใช้จ่ายอื่นๆ',
  `other_expense_vat_type` int(11) NOT NULL COMMENT 'ประเภทภาษี',
  `other_expense_bill_code` varchar(50) NOT NULL COMMENT 'หมายเลขบิล',
  `other_expense_bill_date` varchar(50) NOT NULL COMMENT 'วันที่ออกบิล',
  `other_expense_remark` varchar(500) NOT NULL COMMENT 'หมายเหตุ',
  `other_expense_total` double NOT NULL COMMENT 'ยอดเงิน',
  `other_expense_vat` double NOT NULL COMMENT 'ภาษี',
  `other_expense_vat_value` double NOT NULL COMMENT 'เงินภาษี',
  `other_expense_net` double NOT NULL COMMENT 'จำนวนเงินสุทธิ',
  `other_expense_interest` double NOT NULL COMMENT 'ดอกเบี้ยจ่าย',
  `other_expense_cash` double NOT NULL COMMENT 'เงินสด',
  `other_expense_other_pay` double NOT NULL COMMENT 'ชำระโดยอื่นๆ',
  `other_expense_vat_pay` double NOT NULL COMMENT 'ภาษีหัก ณ ที่จ่าย',
  `other_expense_discount_cash` double NOT NULL COMMENT 'ส่วนลดเงินสด',
  `other_expense_pay` double NOT NULL COMMENT 'ยอดจ่ายจริง',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_other_expense_list`
--

CREATE TABLE `tb_other_expense_list` (
  `other_expense_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการค่าใช้จ่ายอื่นๆ',
  `other_expense_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงค่าใช้จ่ายอื่นๆ',
  `other_expense_list_code` varchar(50) NOT NULL COMMENT 'หรัสรายการ',
  `other_expense_list_name` varchar(200) NOT NULL COMMENT 'ชื่อรายการ',
  `other_expense_list_total` double NOT NULL COMMENT 'จำนวนเงิน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_other_expense_pay`
--

CREATE TABLE `tb_other_expense_pay` (
  `other_expense_pay_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการจ่ายค่าใช้จ่ายอื่นๆ',
  `other_expense_id` int(11) NOT NULL,
  `other_expense_pay_by` varchar(200) NOT NULL COMMENT 'จ่ายโดย',
  `other_expense_pay_date` varchar(50) NOT NULL COMMENT 'ลงวันที่',
  `other_expense_pay_bank` varchar(200) NOT NULL COMMENT 'ธนาคาร',
  `other_expense_pay_value` double NOT NULL COMMENT 'จำนวนเงิน',
  `other_expense_pay_balance` double NOT NULL COMMENT 'ยอดคงเหลือ',
  `other_expense_pay_total` double NOT NULL COMMENT 'ยอดชำระ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_paper`
--

CREATE TABLE `tb_paper` (
  `paper_id` int(11) NOT NULL,
  `paper_type_id` int(11) NOT NULL,
  `paper_code` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `paper_name_th` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `paper_name_en` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `journal_id` int(11) NOT NULL,
  `journal_description` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `paper_lock` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_paper`
--

INSERT INTO `tb_paper` (`paper_id`, `paper_type_id`, `paper_code`, `paper_name_th`, `paper_name_en`, `journal_id`, `journal_description`, `paper_lock`) VALUES
(1, 1, 'RT|year:2|month:2|number:3|', 'ใบสั่งสินค้าทดลอง', 'Request test', 0, '-', 0),
(2, 1, 'STR-|employee_name:2|year:2|month:2|number:3|', 'สินค้าทดสอบมาตรฐาน', 'Standard testing tool', 0, '', 0),
(3, 1, 'SPTR-|employee_name:2|year:2|month:2|number:3|', 'สินค้าทดลองพิเศษ', 'Special testing tool', 0, '', 0),
(4, 1, 'RTR-|employee_name:2|year:2|month:2|number:3|', 'สินค้าทดลองรีกายด์', 'Regrind testing tool', 0, '', 0),
(5, 2, 'SDN|year:2|month:2|number:3|', 'ใบยืมจากผู้ขาย', 'supplier delivery note', 0, '', 0),
(6, 2, 'CDN|year:2|month:2|number:3|', 'ใบยืมลูกค้า', 'customer delivery note', 0, '', 0),
(7, 3, 'RG|year:2|month:2|number:3|-|employee_name:2|', 'ใบส่งรีกายร์สินค้า', 'Send Regrind', 0, '', 0),
(8, 3, 'RGR|year:2|month:2|number:3|', 'ใบรับรีกายร์สินค้า', 'Receive Regrind', 0, '', 0),
(9, 4, 'PR|year:2|month:2|number:3|-|employee_name:2|', 'ร้องขอสั่งซื้อสินค้า', 'Purchase Request', 0, '', 0),
(10, 5, 'PO|year:2|month:2|number:3|', 'ใบสั่งซื้อ', 'Purchase Order', 0, '', 0),
(12, 6, 'RR|year:2|month:2|-|number:3|', 'ใบรับสินค้าภายในประเทศ', 'Supplier Invoice', 1, 'ซื้อเชื่อภายในประเทศจาก {supplier_name} ', 0),
(13, 6, 'RF|year:2|month:2|-|number:3|', 'ใบรับสินค้าภายนอกประเทศ', 'Supplier Invoice', 1, 'ซื้อเชื่อภายนอกประเทศจาก {supplier_name} ', 0),
(14, 7, '|customer_code:5|-|employee_name:2|-|year:2|month:2|number:3|', 'ใบเสนอราคา', 'Quotation', 0, '', 0),
(15, 8, 'PO|year:2|month:2|number:3|-|customer_code:5|', 'ใบสั่งซื้อลูกค้า', 'Customer purchase order', 0, '', 0),
(16, 9, 'INV|year:2|month:2|-|number:3|', 'ใบกำกับภาษี', 'Customer invoice', 3, 'ขายเชื่อให้ |customer_name|', 0),
(17, 9, 'CN|year:2|month:2|-|number:3|', 'ใบลดหนี้', 'Credit note', 4, 'ลดหนี้ขายเชื่อให้ {customer_name}', 0),
(18, 9, 'DN|year:2|month:2|-|number:3|', 'ใบเพิ่มหนี้', 'Debit note', 4, 'เพิ่มหนี้ขายเชื่อให้ {customer_name}', 0),
(19, 10, 'BN|year:2|month:2|-|number:3|', 'ใบวางบิล', 'Billing note', 0, '', 0),
(22, 11, 'RE|year:2|month:2|-|number:3|', 'ใบเสร็จ', 'Official receipt', 0, '', 0),
(23, 14, 'QR|year:2|month:2|-|number:3|', 'ทะเบียนเช็ครับ', 'Cheque receipt', 0, '', 0),
(20, 13, 'RE|year:2|month:2|-|number:3|', 'รับชำระหนี้', 'Reciept', 5, 'รับชำระหนี้จาก {customer_name}', 0),
(21, 13, 'PS|year:2|month:2|-|number:3|', 'จ่ายชำระหนี้', 'Payments', 6, 'จ่ายชำระหนี้ให้ {supplier_name}', 0),
(24, 14, 'QP|year:2|month:2|-|number:3|', 'ทะเบียนเช็คจ่าย', 'Cheque payment', 0, '', 0),
(25, 13, 'OE|year:2|month:2|-|number:3|', 'บันทึกค่าใช้จ่ายอื่นๆ', 'Other expense', 7, 'จ่ายเงิน {supplier_name}', 0),
(26, 15, 'JV|year:2|month:2|-|number:3|', 'สมุดรายวันทั่วไป', 'Journal general', 0, '', 0),
(27, 15, 'JP|year:2|month:2|-|number:3|', 'สมุดรายวันซื้อ', 'Journal purchase', 0, '', 0),
(28, 15, 'JS|year:2|month:2|-|number:3|', 'สมุดรายวันขาย', 'Journal sale', 0, '', 0),
(29, 15, 'RV|year:2|month:2|-|number:3|', 'สมุดรายวันรับเงิน', 'Journal cash receipt', 0, '', 0),
(30, 15, 'PV|year:2|month:2|-|number:3|', 'สมุดรายวันจ่ายเงิน', 'Journal cash payment', 0, '', 0),
(31, 15, 'JPR|year:2|month:2|-|number:3|', 'สมุดรายวันส่งคืนสินค้า', 'Journal purchase return', 0, '', 0),
(32, 15, 'JSR|year:2|month:2|-|number:3|', 'สมุดรายวันรับคืนสินค้า', 'Journal sale return', 0, '', 0),
(11, 5, 'LP|year:2|month:2|number:3|', 'ใบสั่งซื้อ (ภายในประเทศ)', 'Purchase Order', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_paper_lock`
--

CREATE TABLE `tb_paper_lock` (
  `paper_lock_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงงวด',
  `paper_lock_1` int(11) NOT NULL COMMENT 'ล็อกงวดของผู้กรอกข้อมูล',
  `paper_lock_2` int(11) NOT NULL COMMENT 'ล็อกงวดของบัญชี',
  `paper_lock_date` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'วันสิ้นสุดงวด'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_paper_lock`
--

INSERT INTO `tb_paper_lock` (`paper_lock_id`, `paper_lock_1`, `paper_lock_2`, `paper_lock_date`) VALUES
(1, 1, 0, '31-01-2018'),
(2, 1, 0, '28-02-2018'),
(3, 1, 0, '31-03-2018'),
(4, 1, 0, '30-04-2018'),
(5, 1, 0, '31-05-2018'),
(6, 1, 0, '30-06-2018'),
(7, 1, 0, '31-07-2018'),
(8, 1, 0, '31-08-2018'),
(9, 0, 0, '30-09-2018'),
(10, 0, 0, '31-10-2018'),
(11, 0, 0, '30-11-2018'),
(12, 0, 0, '31-12-2018'),
(13, 0, 0, '31-01-2019'),
(14, 0, 0, '28-02-2019'),
(15, 0, 0, '31-03-2019'),
(16, 0, 0, '30-04-2019'),
(17, 0, 0, '31-05-2019'),
(18, 0, 0, '30-06-2019'),
(19, 0, 0, '31-07-2019'),
(20, 0, 0, '31-08-2019'),
(21, 0, 0, '30-09-2019'),
(22, 0, 0, '31-10-2019'),
(23, 0, 0, '30-11-2019'),
(24, 0, 0, '31-12-2019');

-- --------------------------------------------------------

--
-- Table structure for table `tb_paper_type`
--

CREATE TABLE `tb_paper_type` (
  `paper_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทเอกสาร',
  `paper_type_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อประเภทเอกสาร'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_paper_type`
--

INSERT INTO `tb_paper_type` (`paper_type_id`, `paper_type_name`) VALUES
(1, 'เอกสารสินค้าทดลอง'),
(2, 'เอกสารใบยืม'),
(3, 'เอกสารรีกายด์'),
(4, 'เอกสารร้องขอสั่งซื้อ'),
(5, 'เอกสารสั่งซื้อ'),
(6, 'เอกสารซื้อสินค้า'),
(7, 'เอกสารเสนอราคา'),
(9, 'เอกสารขายสินค้า'),
(10, 'เอกสารวางบิล'),
(11, 'เอกสารใบเสร็จ'),
(12, 'เอกสารคลังสินค้า'),
(13, 'เอกสารการเงิน'),
(8, 'เอกสารลูกค้า'),
(14, 'เอกสารเช็ค'),
(15, 'เอกสารสมุดรายวัน');

-- --------------------------------------------------------

--
-- Table structure for table `tb_product`
--

CREATE TABLE `tb_product` (
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `product_code_first` varchar(20) NOT NULL COMMENT 'รหัสสินค้าด้านหน้า',
  `product_code` varchar(100) NOT NULL COMMENT 'รหัสสินค้าด้านหลัง',
  `product_name` varchar(100) NOT NULL COMMENT 'ชื่อสินค้า',
  `product_type` int(11) NOT NULL COMMENT 'ประเภทสินค้า (standard, special)',
  `product_group` int(11) NOT NULL COMMENT 'กลุ่มสินค้า (consumable,durable)',
  `product_unit` int(11) NOT NULL COMMENT 'หน่วยเรียกสินค้า',
  `product_barcode` varchar(100) NOT NULL COMMENT 'หมายเลขบาร์โค๊ต',
  `product_description` text NOT NULL COMMENT 'รายละเอียดสินค้า',
  `product_drawing` varchar(200) NOT NULL COMMENT 'แบบแปลนสินค้า',
  `product_logo` varchar(200) NOT NULL COMMENT 'รูปสินค้า',
  `product_status` varchar(100) NOT NULL COMMENT 'สถานะสินค้า',
  `product_category_id` int(11) NOT NULL COMMENT 'ประเภทสินค้า (บริการ,สินค้า)',
  `sale_account_id` int(11) NOT NULL COMMENT 'ดำเนินการในบัญชีเมื่อเกิดการขาย',
  `buy_account_id` int(11) NOT NULL COMMENT 'ดำเนินการในบัญชีเมื่อเกิดการซื้อ',
  `product_price_1` double NOT NULL COMMENT 'รายการราคาที่ 1 ',
  `product_price_2` double NOT NULL COMMENT 'รายการราคาที่ 2 ',
  `product_price_3` double NOT NULL COMMENT 'รายการราคาที่ 3 ',
  `product_price_4` double NOT NULL COMMENT 'รายการราคาที่ 4 ',
  `product_price_5` double NOT NULL COMMENT 'รายการราคาที่ 5 ',
  `product_price_6` double NOT NULL COMMENT 'รายการราคาที่ 6 ',
  `product_price_7` double NOT NULL COMMENT 'รายการราคาที่ 7 '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_category`
--

CREATE TABLE `tb_product_category` (
  `product_category_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทสินค้า (บริการ,สินค้า)	',
  `product_category_name` varchar(10) NOT NULL COMMENT 'ขื่อประเภทสินค้า (บริการ,สินค้า)	',
  `stock_event` int(11) NOT NULL COMMENT 'มีผลต่อคลังสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_product_category`
--

INSERT INTO `tb_product_category` (`product_category_id`, `product_category_name`, `stock_event`) VALUES
(1, 'สินค้า', 1),
(2, 'บริการ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_customer`
--

CREATE TABLE `tb_product_customer` (
  `product_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อสิ้นค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ซื้อ',
  `minimum_stock` int(11) NOT NULL COMMENT 'จำนวนสินค้าต่ำสุด',
  `safety_stock` int(11) NOT NULL COMMENT 'จำนวนสินค้าปลอดภัย',
  `product_status` varchar(50) NOT NULL COMMENT 'สถานะการสั่งซื้อ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มสินค้า',
  `adddate` varchar(50) NOT NULL COMMENT 'เวลาเพิ่มสินค้า',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขสิ้นค้า',
  `lastupdate` varchar(50) NOT NULL COMMENT 'เวลาแก้ไขสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_customer_price`
--

CREATE TABLE `tb_product_customer_price` (
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `product_price` double NOT NULL COMMENT 'ราคาขาย'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_group`
--

CREATE TABLE `tb_product_group` (
  `product_group_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงกลุ่มสินค้า',
  `product_group_name` varchar(100) NOT NULL COMMENT 'ชื่อกลุ่มสินค้า',
  `product_group_detail` varchar(200) NOT NULL COMMENT 'รายละเอียดกลุ่มสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_product_group`
--

INSERT INTO `tb_product_group` (`product_group_id`, `product_group_name`, `product_group_detail`) VALUES
(1, 'Durable', 'สินค้าจำพวกอาเบอร์'),
(2, 'Consumable', 'สินค้าใช้แล้วหมดไป'),
(3, 'Spare Part', 'สินค้าส่วนประกอบ'),
(4, 'Tool management', 'บริการบริหารจัดการ Tool');

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_supplier`
--

CREATE TABLE `tb_product_supplier` (
  `product_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขายสิ้นค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `product_buyprice` double NOT NULL COMMENT 'รายค้าซื้อ',
  `lead_time` int(11) NOT NULL COMMENT 'ระยะเวลาส่งของ',
  `product_supplier_status` varchar(50) NOT NULL COMMENT 'สถานะใช้งาน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'เวลาเพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'เวลาแก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_type`
--

CREATE TABLE `tb_product_type` (
  `product_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทสินค้า',
  `product_type_name` varchar(200) NOT NULL COMMENT 'ชื่อประเภทสินค้า',
  `product_type_first_char` varchar(50) NOT NULL COMMENT 'ตัวอักษรขึ้นต้น',
  `product_type_auto` int(11) NOT NULL COMMENT 'รันรหัสสินค้าอัตโนมัติ',
  `product_type_digit` int(11) NOT NULL COMMENT 'จำนวนหลักของรหัส',
  `product_type_detail` varchar(200) NOT NULL COMMENT 'รายละเอียดประเภท',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้อัพเดทข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่อัพเดทข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_product_type`
--

INSERT INTO `tb_product_type` (`product_type_id`, `product_type_name`, `product_type_first_char`, `product_type_auto`, `product_type_digit`, `product_type_detail`, `addby`, `adddate`, `updateby`, `lastupdate`) VALUES
(1, 'Standard Tool', '', 0, 0, 'Standard Tool buy from supplier.', 0, '', 0, ''),
(2, 'Special Tool', 'ARNO-', 1, 4, '-', 0, '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `tb_product_unit`
--

CREATE TABLE `tb_product_unit` (
  `product_unit_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงหน่วยนับ',
  `product_unit_name` varchar(100) NOT NULL COMMENT 'ชื่อหน่วยนับ',
  `product_unit_detail` varchar(200) NOT NULL COMMENT 'รายละเอียดหน่วยนับ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_product_unit`
--

INSERT INTO `tb_product_unit` (`product_unit_id`, `product_unit_name`, `product_unit_detail`) VALUES
(1, 'ชิ้น', '-'),
(2, 'กล่อง', '1 กล่อง เท่ากับ 10 ชิ้น'),
(3, 'บริการ', 'บริการ');

-- --------------------------------------------------------

--
-- Table structure for table `tb_province`
--

CREATE TABLE `tb_province` (
  `PROVINCE_ID` int(5) NOT NULL,
  `PROVINCE_CODE` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `PROVINCE_NAME` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `GEO_ID` int(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tb_province`
--

INSERT INTO `tb_province` (`PROVINCE_ID`, `PROVINCE_CODE`, `PROVINCE_NAME`, `GEO_ID`) VALUES
(1, '10', 'กรุงเทพมหานคร   ', 2),
(2, '11', 'สมุทรปราการ   ', 2),
(3, '12', 'นนทบุรี   ', 2),
(4, '13', 'ปทุมธานี   ', 2),
(5, '14', 'พระนครศรีอยุธยา   ', 2),
(6, '15', 'อ่างทอง   ', 2),
(7, '16', 'ลพบุรี   ', 2),
(8, '17', 'สิงห์บุรี   ', 2),
(9, '18', 'ชัยนาท   ', 2),
(10, '19', 'สระบุรี', 2),
(11, '20', 'ชลบุรี   ', 5),
(12, '21', 'ระยอง   ', 5),
(13, '22', 'จันทบุรี   ', 5),
(14, '23', 'ตราด   ', 5),
(15, '24', 'ฉะเชิงเทรา   ', 5),
(16, '25', 'ปราจีนบุรี   ', 5),
(17, '26', 'นครนายก   ', 2),
(18, '27', 'สระแก้ว   ', 5),
(19, '30', 'นครราชสีมา   ', 3),
(20, '31', 'บุรีรัมย์   ', 3),
(21, '32', 'สุรินทร์   ', 3),
(22, '33', 'ศรีสะเกษ   ', 3),
(23, '34', 'อุบลราชธานี   ', 3),
(24, '35', 'ยโสธร   ', 3),
(25, '36', 'ชัยภูมิ   ', 3),
(26, '37', 'อำนาจเจริญ   ', 3),
(27, '39', 'หนองบัวลำภู   ', 3),
(28, '40', 'ขอนแก่น   ', 3),
(29, '41', 'อุดรธานี   ', 3),
(30, '42', 'เลย   ', 3),
(31, '43', 'หนองคาย   ', 3),
(32, '44', 'มหาสารคาม   ', 3),
(33, '45', 'ร้อยเอ็ด   ', 3),
(34, '46', 'กาฬสินธุ์   ', 3),
(35, '47', 'สกลนคร   ', 3),
(36, '48', 'นครพนม   ', 3),
(37, '49', 'มุกดาหาร   ', 3),
(38, '50', 'เชียงใหม่   ', 1),
(39, '51', 'ลำพูน   ', 1),
(40, '52', 'ลำปาง   ', 1),
(41, '53', 'อุตรดิตถ์   ', 1),
(42, '54', 'แพร่   ', 1),
(43, '55', 'น่าน   ', 1),
(44, '56', 'พะเยา   ', 1),
(45, '57', 'เชียงราย   ', 1),
(46, '58', 'แม่ฮ่องสอน   ', 1),
(47, '60', 'นครสวรรค์   ', 2),
(48, '61', 'อุทัยธานี   ', 2),
(49, '62', 'กำแพงเพชร   ', 2),
(50, '63', 'ตาก   ', 4),
(51, '64', 'สุโขทัย   ', 2),
(52, '65', 'พิษณุโลก   ', 2),
(53, '66', 'พิจิตร   ', 2),
(54, '67', 'เพชรบูรณ์   ', 2),
(55, '70', 'ราชบุรี   ', 4),
(56, '71', 'กาญจนบุรี   ', 4),
(57, '72', 'สุพรรณบุรี   ', 2),
(58, '73', 'นครปฐม   ', 2),
(59, '74', 'สมุทรสาคร   ', 2),
(60, '75', 'สมุทรสงคราม   ', 2),
(61, '76', 'เพชรบุรี   ', 4),
(62, '77', 'ประจวบคีรีขันธ์   ', 4),
(63, '80', 'นครศรีธรรมราช   ', 6),
(64, '81', 'กระบี่   ', 6),
(65, '82', 'พังงา   ', 6),
(66, '83', 'ภูเก็ต   ', 6),
(67, '84', 'สุราษฎร์ธานี   ', 6),
(68, '85', 'ระนอง   ', 6),
(69, '86', 'ชุมพร   ', 6),
(70, '90', 'สงขลา   ', 6),
(71, '91', 'สตูล   ', 6),
(72, '92', 'ตรัง   ', 6),
(73, '93', 'พัทลุง   ', 6),
(74, '94', 'ปัตตานี   ', 6),
(75, '95', 'ยะลา   ', 6),
(76, '96', 'นราธิวาส   ', 6),
(77, '97', 'บึงกาฬ', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tb_purchase_order`
--

CREATE TABLE `tb_purchase_order` (
  `purchase_order_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิง PO',
  `purchase_order_rewrite_id` int(11) NOT NULL,
  `purchase_order_rewrite_no` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `purchase_order_accept_status` varchar(50) NOT NULL COMMENT 'สถานะการอนุมัติ',
  `purchase_order_accept_by` int(11) NOT NULL COMMENT 'อนุมัติโดยใคร',
  `purchase_order_accept_date` varchar(50) NOT NULL COMMENT 'วันที่อนุมัติ',
  `purchase_order_status` varchar(50) NOT NULL COMMENT 'สถานะใบสั่งซื้อ',
  `purchase_order_type` varchar(50) NOT NULL COMMENT 'ประเภทการสั่งซื่อ (STANDARD, TEST, BLANKED)',
  `purchase_order_code` varchar(50) NOT NULL COMMENT 'เลขที่ใบสั่งซื้อ',
  `purchase_order_credit_term` int(11) NOT NULL COMMENT 'ชำระเงินภายในกี่วัน',
  `purchase_order_delivery_term` varchar(100) NOT NULL COMMENT 'ประเภทวันส่งสินค้า',
  `purchase_order_delivery_by` varchar(100) NOT NULL COMMENT 'ส่งสินค้าโดย',
  `purchase_order_date` varchar(50) NOT NULL COMMENT 'วันที่สั่งซื้อสินค้า',
  `purchase_order_total_price` double NOT NULL,
  `purchase_order_vat` double NOT NULL,
  `purchase_order_vat_price` double NOT NULL,
  `purchase_order_net_price` double NOT NULL,
  `purchase_order_cancelled` int(11) NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มใบสั่งซื้อสินค้า',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มใบสั่งซื้อสินค้า',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขใบสั่งซื้อสินค้า',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขใบสั่งซื้อสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_purchase_order_list`
--

CREATE TABLE `tb_purchase_order_list` (
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้า',
  `purchase_order_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้า',
  `purchase_order_list_no` int(11) NOT NULL COMMENT 'ลำดับรายการ',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `purchase_order_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `purchase_order_list_price` double NOT NULL COMMENT 'ราคาสินค้าต่อชิ้น',
  `purchase_order_list_price_sum` double NOT NULL COMMENT 'ราคาสินค้ารวม',
  `purchase_order_list_delivery_min` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุด',
  `purchase_order_list_delivery_max` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าช้าที่สุด',
  `purchase_order_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `purchase_order_list_supplier_qty` int(11) NOT NULL COMMENT 'จำนวนที่ผู้ขายมี',
  `purchase_order_list_supplier_delivery_min` varchar(50) NOT NULL COMMENT 'วันที่จัดส่งเร็วที่สุด',
  `purchase_order_list_supplier_delivery_max` varchar(50) NOT NULL COMMENT 'วันที่จัดส่งช้าที่สุด',
  `purchase_order_list_supplier_remark` text NOT NULL COMMENT 'หมายเหตุของผู้จัดส่ง',
  `stock_group_id` int(11) NOT NULL COMMENT 'รหัสคลังสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มรายการสั่งซื้อสินค้า',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มรายการสั่งซื้อสินค้า',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขรายการสั่งซื้อสินค้า',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขรายการสั่งซื้อสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_purchase_order_list_detail`
--

CREATE TABLE `tb_purchase_order_list_detail` (
  `purchase_order_list_detail_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายละเอียดรายการใบสั่งซื้อสินค้า',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้า',
  `date_recieve` varchar(50) NOT NULL COMMENT 'วันที่ต้องการรับสินค้า',
  `qty_recieve` int(11) NOT NULL COMMENT 'จำนวนที่ต้องการ',
  `remark_recieve` text NOT NULL COMMENT 'หมายเหตุ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_purchase_request`
--

CREATE TABLE `tb_purchase_request` (
  `purchase_request_id` int(11) NOT NULL COMMENT 'รหัสใบ PR',
  `purchase_request_rewrite_id` int(11) NOT NULL COMMENT 'แก้ไขจากใบร้องการสั่งซื้อรหัส',
  `purchase_request_rewrite_no` int(11) NOT NULL,
  `purchase_request_alert` varchar(50) NOT NULL,
  `purchase_request_code` varchar(50) NOT NULL COMMENT 'เลขที่ PR เอาไว้แสดง',
  `purchase_request_type` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT 'รหัสลูกค้า',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานมราเปิด PR',
  `purchase_request_date` varchar(50) NOT NULL COMMENT 'วันที่เปิด PR',
  `purchase_request_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `purchase_request_accept_status` varchar(50) NOT NULL COMMENT 'สถานะการอนุมัติ',
  `purchase_request_accept_by` int(11) NOT NULL COMMENT 'อนุมัติโดยใคร',
  `purchase_request_accept_date` varchar(50) NOT NULL COMMENT 'วันที่อนุมัติ',
  `purchase_request_status` varchar(50) NOT NULL COMMENT 'สถานะใบ PR',
  `purchase_request_cancelled` int(11) NOT NULL COMMENT 'ยอกเลิกใบร้องขอการสั่งซื้อ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มใบ PR',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม PR',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไข PR',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข PR'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_purchase_request_list`
--

CREATE TABLE `tb_purchase_request_list` (
  `purchase_request_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบขายสินค้า',
  `purchase_request_id` int(11) NOT NULL COMMENT 'รหัสใบขายสินค้า',
  `purchase_request_list_no` int(11) NOT NULL COMMENT 'ลำดับรายการ',
  `stock_group_id` int(11) NOT NULL COMMENT 'คลังสินค้า',
  `supplier_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `purchase_request_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `purchase_request_list_delivery` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุด',
  `purchase_request_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มรายการขายสินค้า',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มรายการขายสินค้า',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขรายการขายสินค้า',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขรายการขายสินค้า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_quotation`
--

CREATE TABLE `tb_quotation` (
  `quotation_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบเสนอราคา',
  `quotation_rewrite_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบเสนอราคาที่เขียนใหม่',
  `quotation_rewrite_no` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงพนักงานที่เสนอราคา',
  `customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลูกค้า',
  `quotation_code` varchar(50) NOT NULL COMMENT 'หมายเลขใบเสนอราคา',
  `quotation_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบเสนอราคา',
  `quotation_contact_name` varchar(100) NOT NULL COMMENT 'ชื่อผู้ติดต่อ',
  `quotation_contact_tel` varchar(100) NOT NULL COMMENT 'เบอร์โทรผู้ติดต่อ',
  `quotation_contact_email` varchar(100) NOT NULL COMMENT 'อีเมลผู้ติดต่อ',
  `quotation_total` double NOT NULL COMMENT 'ราคารวม',
  `quotation_vat` double NOT NULL COMMENT 'ภาษี',
  `quotation_vat_price` double NOT NULL COMMENT 'จำนวนเงินภาษี',
  `quotation_vat_net` double NOT NULL COMMENT 'จำนวนเงินสุทธิ',
  `quotation_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `quotation_cancelled` int(11) NOT NULL COMMENT 'ยกเลิกใบสั่งซื้อ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_quotation_list`
--

CREATE TABLE `tb_quotation_list` (
  `quotation_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบเสนอราคา',
  `quotation_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบเสนอราคา',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `quotation_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `quotation_list_price` double NOT NULL COMMENT 'ราคาต่อชิ้น',
  `quotation_list_sum` double NOT NULL COMMENT 'ราคารวม',
  `quotation_list_discount` double NOT NULL COMMENT 'ส่วนลด',
  `quotation_list_discount_type` int(11) NOT NULL COMMENT 'ประเภทส่วนลด 0 = %, 1 = บาท',
  `quotation_list_total` double NOT NULL COMMENT 'ราคาสุทธิ',
  `quotation_list_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผูแก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_regrind_supplier`
--

CREATE TABLE `tb_regrind_supplier` (
  `regrind_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบรีกายร์ผู้ซื้อสิ้นค้า',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานที่ออกใบรีกายร์',
  `employee_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้ออกใบรีกายร์',
  `contact_name` varchar(200) NOT NULL COMMENT 'ชื่อผู้รับใบรีกายร์',
  `contact_signature` text NOT NULL COMMENT 'ลายเซนผู้รับใบรีกายร์',
  `regrind_supplier_code` varchar(20) NOT NULL COMMENT 'หมายเลขใบรีกายร์',
  `regrind_supplier_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบรีกายร์',
  `regrind_supplier_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `regrind_supplier_file` varchar(200) NOT NULL COMMENT 'ไฟล์ที่เกี่ยวข้องกับใบรีกายร์',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_regrind_supplier_list`
--

CREATE TABLE `tb_regrind_supplier_list` (
  `regrind_supplier_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบรีกายร์ผู้ขายสิ้นค้า',
  `regrind_supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบรีกายร์ผู้ขายสิ้นค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `regrind_supplier_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `regrind_supplier_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `stock_group_id` int(11) NOT NULL COMMENT 'เอาสินค้าเข้าคลังไหน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_regrind_supplier_receive`
--

CREATE TABLE `tb_regrind_supplier_receive` (
  `regrind_supplier_receive_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบรับรีกายร์ผู้ซื้อสิ้นค้า',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานที่รับใบรับรีกายร์',
  `employee_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้รับใบรับรีกายร์',
  `contact_name` varchar(200) NOT NULL COMMENT 'ชื่อผู้ส่งใบรับรีกายร์',
  `contact_signature` varchar(200) NOT NULL COMMENT 'ลายเซนผู้ส่งใบรับรีกายร์',
  `regrind_supplier_receive_code` varchar(20) NOT NULL COMMENT 'หมายเลขใบรับรีกายร์',
  `regrind_supplier_receive_date` varchar(50) NOT NULL COMMENT 'วันที่ออกใบรับรีกายร์',
  `regrind_supplier_receive_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `regrind_supplier_receive_file` varchar(200) NOT NULL COMMENT 'ไฟล์ที่เกี่ยวข้องกับใบรับรีกายร์',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_regrind_supplier_receive_list`
--

CREATE TABLE `tb_regrind_supplier_receive_list` (
  `regrind_supplier_receive_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการใบรับรีกายร์ผู้ขายสิ้นค้า',
  `regrind_supplier_receive_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบรับรีกายร์ผู้ขายสิ้นค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `regrind_supplier_receive_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `regrind_supplier_receive_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `regrind_supplier_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบส่งสินค้ารีกายด์',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้า',
  `stock_group_id` int(11) NOT NULL COMMENT 'เอาสินค้าเข้าคลังไหน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_regrind`
--

CREATE TABLE `tb_request_regrind` (
  `request_regrind_id` int(11) NOT NULL COMMENT 'รหัสใบร้องการสั่งซื้อสินค้าทดลองรีกายด์',
  `request_regrind_rewrite_id` int(11) NOT NULL COMMENT 'แก้ไขจากใบร้องการสั่งซื้อสินค้าทดลองรีกายด์รหัส',
  `request_regrind_rewrite_no` int(11) NOT NULL COMMENT 'แก้ไขเอกสารครั้งที่',
  `request_regrind_code` varchar(50) NOT NULL COMMENT 'เลขที่ใบร้องการสั่งซื้อสินค้าทดลองรีกายด์เอาไว้แสดง',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานมราเปิดใบร้องการสั่งซื้อสินค้าทดลองรีกายด์',
  `request_regrind_date` varchar(50) NOT NULL COMMENT 'วันที่เปิดใบร้องการสั่งซื้อสินค้าทดลองรีกายด์',
  `request_regrind_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_open` int(11) NOT NULL COMMENT '1 = เปิด PO, 0 = เปิด DN',
  `request_regrind_accept_status` varchar(50) NOT NULL COMMENT 'สถานะการอนุมัติ',
  `request_regrind_accept_by` int(11) NOT NULL COMMENT 'อนุมัติโดยใคร',
  `request_regrind_accept_date` varchar(50) NOT NULL COMMENT 'วันที่อนุมัติ',
  `request_regrind_status` varchar(50) NOT NULL COMMENT 'สถานะใบร้องการสั่งซื้อสินค้าทดลองรีกายด์',
  `request_regrind_cancelled` int(11) NOT NULL COMMENT 'ยอกเลิกใบร้องขอการสั่งซื้อ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_regrind_list`
--

CREATE TABLE `tb_request_regrind_list` (
  `request_regrind_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบร้องขอสั่งซื้อสินค้าทดลองรีกายด์',
  `request_regrind_id` int(11) NOT NULL COMMENT 'รหัสใบร้องขอสั่งซื้อสินค้าทดลองรีกายด์',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'ลูกค้าที่เอาไปเทส',
  `request_regrind_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `request_regrind_list_delivery` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุด',
  `request_regrind_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `tool_test_result` int(11) NOT NULL COMMENT '1 = ผ่าน, 0 = ไม่ผ่าน',
  `request_test_list_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้าทดสอบ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_special`
--

CREATE TABLE `tb_request_special` (
  `request_special_id` int(11) NOT NULL COMMENT 'รหัสใบร้องการสั่งซื้อสินค้าทดลองพิเศษ',
  `request_special_rewrite_id` int(11) NOT NULL COMMENT 'แก้ไขจากใบร้องการสั่งซื้อสินค้าทดลองพิเศษรหัส',
  `request_special_rewrite_no` int(11) NOT NULL COMMENT 'แก้ไขเอกสารครั้งที่',
  `request_special_code` varchar(50) NOT NULL COMMENT 'เลขที่ใบร้องการสั่งซื้อสินค้าทดลองพิเศษเอาไว้แสดง',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานมราเปิดใบร้องการสั่งซื้อสินค้าทดลองพิเศษ',
  `request_special_date` varchar(50) NOT NULL COMMENT 'วันที่เปิดใบร้องการสั่งซื้อสินค้าทดลองพิเศษ',
  `request_special_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_open` int(11) NOT NULL COMMENT '1 = เปิด PO, 0 = เปิด DN',
  `request_special_accept_status` varchar(50) NOT NULL COMMENT 'สถานะการอนุมัติ',
  `request_special_accept_by` int(11) NOT NULL COMMENT 'อนุมัติโดยใคร',
  `request_special_accept_date` varchar(50) NOT NULL COMMENT 'วันที่อนุมัติ',
  `request_special_status` varchar(50) NOT NULL COMMENT 'สถานะใบร้องการสั่งซื้อสินค้าทดลองพิเศษ',
  `request_special_cancelled` int(11) NOT NULL COMMENT 'ยอกเลิกใบร้องขอการสั่งซื้อ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_special_list`
--

CREATE TABLE `tb_request_special_list` (
  `request_special_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบร้องขอสั่งซื้อสินค้าทดลองพิเศษ',
  `request_special_id` int(11) NOT NULL COMMENT 'รหัสใบร้องขอสั่งซื้อสินค้าทดลองพิเศษ',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'ลูกค้าที่เอาไปเทส',
  `request_special_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `request_special_list_delivery` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุด',
  `request_special_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `tool_test_result` int(11) NOT NULL COMMENT '1 = ผ่าน, 0 = ไม่ผ่าน',
  `request_test_list_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้าทดสอบ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_standard`
--

CREATE TABLE `tb_request_standard` (
  `request_standard_id` int(11) NOT NULL COMMENT 'รหัสใบร้องการสั่งซื้อสินค้าทดลอง',
  `request_standard_rewrite_id` int(11) NOT NULL COMMENT 'แก้ไขจากใบร้องการสั่งซื้อสินค้าทดลองรหัส',
  `request_standard_rewrite_no` int(11) NOT NULL COMMENT 'แก้ไขเอกสารครั้งที่',
  `request_standard_code` varchar(50) NOT NULL COMMENT 'เลขที่ใบร้องการสั่งซื้อสินค้าทดลองเอาไว้แสดง',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานมราเปิดใบร้องการสั่งซื้อสินค้าทดลอง',
  `request_standard_date` varchar(50) NOT NULL COMMENT 'วันที่เปิดใบร้องการสั่งซื้อสินค้าทดลอง',
  `request_standard_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_open` int(11) NOT NULL COMMENT '1 = เปิด PO, 0 = เปิด DN',
  `request_standard_accept_status` varchar(50) NOT NULL COMMENT 'สถานะการอนุมัติ',
  `request_standard_accept_by` int(11) NOT NULL COMMENT 'อนุมัติโดยใคร',
  `request_standard_accept_date` varchar(50) NOT NULL COMMENT 'วันที่อนุมัติ',
  `request_standard_status` varchar(50) NOT NULL COMMENT 'สถานะใบร้องการสั่งซื้อสินค้าทดลอง',
  `request_standard_cancelled` int(11) NOT NULL COMMENT 'ยอกเลิกใบร้องขอการสั่งซื้อ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_standard_list`
--

CREATE TABLE `tb_request_standard_list` (
  `request_standard_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบร้องขอสั่งซื้อสินค้าทดลอง',
  `request_standard_id` int(11) NOT NULL COMMENT 'รหัสใบร้องขอสั่งซื้อสินค้าทดลอง',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `customer_id` int(11) NOT NULL COMMENT 'ลูกค้าที่เอาไปเทส',
  `request_standard_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `request_standard_list_delivery` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุด',
  `request_standard_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `tool_test_result` int(11) NOT NULL COMMENT '1 = ผ่าน, 0 = ไม่ผ่าน',
  `request_test_list_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งซื้อสินค้าทดสอบ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งซื้อสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_test`
--

CREATE TABLE `tb_request_test` (
  `request_test_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิง PO',
  `request_test_rewrite_id` int(11) NOT NULL,
  `request_test_rewrite_no` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงาน',
  `request_test_code` varchar(50) NOT NULL COMMENT 'เลขที่ใบสั่งซื้อ',
  `request_test_date` varchar(50) NOT NULL COMMENT 'วันที่สั่งสินค้าทดลอง',
  `request_test_status` varchar(50) NOT NULL,
  `request_test_cancelled` int(11) NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มใบสั่งสินค้าทดลอง',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มใบสั่งสินค้าทดลอง',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขใบสั่งสินค้าทดลอง',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขใบสั่งสินค้าทดลอง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_request_test_list`
--

CREATE TABLE `tb_request_test_list` (
  `request_test_list_id` int(11) NOT NULL COMMENT 'รหัสรายการใบสั่งสินค้าทดลอง',
  `request_test_id` int(11) NOT NULL COMMENT 'รหัสใบสั่งสินค้าทดลอง',
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `request_test_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `request_test_list_delivery` varchar(50) NOT NULL COMMENT 'วันที่ส่งสินค้าไวที่สุด',
  `request_test_list_remark` text NOT NULL COMMENT 'หมายเหตุรายการสินค้า',
  `request_test_list_supplier_qty` int(11) NOT NULL COMMENT 'จำนวนที่ผู้ขายมี',
  `request_test_list_supplier_delivery` varchar(50) NOT NULL COMMENT 'วันที่จัดส่งเร็วที่สุด',
  `request_test_list_supplier_remark` text NOT NULL COMMENT 'หมายเหตุของผู้จัดส่ง',
  `stock_group_id` int(11) NOT NULL COMMENT 'รหัสคลังสินค้า',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มรายการสั่งสินค้าทดลอง',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มรายการสั่งสินค้าทดลอง',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขรายการสั่งสินค้าทดลอง',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขรายการสั่งสินค้าทดลอง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_group`
--

CREATE TABLE `tb_stock_group` (
  `stock_group_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงกลุ่มคลังสินค้า',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงพนักงาน',
  `stock_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทคลังสินค้า',
  `stock_group_code` varchar(50) NOT NULL COMMENT 'หมายเลขคลังสินค้า',
  `stock_group_name` varchar(200) NOT NULL COMMENT 'ชื่อกลุ่มคลังสินค้า',
  `stock_group_detail` varchar(300) NOT NULL COMMENT 'รายละเอียดกลุ่มคลังสินค้า',
  `stock_group_notification` int(11) NOT NULL COMMENT 'มีการแจ้งเตือนกลุ่มคลังสินค้า',
  `stock_group_day` int(11) NOT NULL COMMENT 'สรุปผลทุกวันที่',
  `table_name` varchar(200) NOT NULL COMMENT 'ชื่อตารางเก็บบันทึกเข้า-ออก',
  `stock_group_primary` int(11) NOT NULL COMMENT 'ตั้งเป็นหลัก',
  `addby` int(11) NOT NULL COMMENT 'เพิ่มข้อมูลโดย',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูลโดย',
  `updateby` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูลโดย',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูลโดย'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_issue`
--

CREATE TABLE `tb_stock_issue` (
  `stock_issue_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบตัดคลังสินค้า',
  `invoice_customer_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบกำกับภาษี',
  `stock_issue_code` varchar(100) NOT NULL COMMENT 'หมายเลขใบตัดคลังสินค้า',
  `stock_group_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงคลังสินค้า',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสพนักงานที่ตัดคลังสินค้า',
  `stock_issue_date` varchar(50) NOT NULL COMMENT 'วันที่ตัดคลังสินค้า',
  `stock_issue_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `stock_issue_total` double NOT NULL,
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_issue_list`
--

CREATE TABLE `tb_stock_issue_list` (
  `stock_issue_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการตัดคลังสินค้า',
  `stock_issue_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบตัดคลังสินค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `stock_issue_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `stock_issue_list_price` double NOT NULL COMMENT 'ราคาต่อชิ้น',
  `stock_issue_list_total` double NOT NULL COMMENT 'ราคารวม',
  `stock_issue_list_remark` varchar(200) NOT NULL COMMENT 'หมายเหตุ',
  `purchase_order_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบสั่งซื้อ',
  `sock_issue_list_cost` double NOT NULL COMMENT 'ต้นทุนที่แท้จริง',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_move`
--

CREATE TABLE `tb_stock_move` (
  `stock_move_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงใบย้ายคลังสินค้า',
  `stock_move_code` varchar(100) NOT NULL COMMENT 'รหัสใบย้ายคลังสินค้า',
  `stock_group_id_out` int(11) NOT NULL COMMENT 'รหัสคลังสินค้าย้ายออก',
  `stock_group_id_in` int(11) NOT NULL COMMENT 'รหัสคลังสินค้าย้ายเข้า',
  `stock_move_date` varchar(50) NOT NULL,
  `employee_id` int(11) NOT NULL COMMENT 'พนักงานผู้ย้ายคลังสินค้า',
  `stock_move_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(100) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(100) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_move_list`
--

CREATE TABLE `tb_stock_move_list` (
  `stock_move_list_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายการขายรถ',
  `stock_move_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL COMMENT 'รหัสสินค้า',
  `stock_move_list_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้า',
  `stock_move_list_cost` double NOT NULL COMMENT 'ต้นทุนที่แท้จริง',
  `stock_move_list_remark` text NOT NULL COMMENT 'หมายเหตุ',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_report`
--

CREATE TABLE `tb_stock_report` (
  `stock_report_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงรายงานคลังสินค้า',
  `stock_group_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงคลังสินค้า',
  `product_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงสินค้า',
  `stock_report_qty` int(11) NOT NULL COMMENT 'จำนวนสินค้าในคลังสินค้า',
  `stock_report_minimum_qty` int(11) NOT NULL DEFAULT '0' COMMENT 'จำนวนสินค้าต่ำสุด',
  `stock_report_safty_qty` int(11) NOT NULL DEFAULT '0' COMMENT 'จำนวนสินค้าต่ำสุดอันตราย',
  `stock_report_cost_avg` double NOT NULL COMMENT 'ต้นทุน',
  `addby` int(11) NOT NULL COMMENT 'ผู้เพิ่มข้อมูล',
  `adddate` int(11) NOT NULL COMMENT 'วันที่เพิ่มข้อมูล',
  `updateby` int(11) NOT NULL COMMENT 'ผู้แก้ไขข้อมูล',
  `lastupdate` int(11) NOT NULL COMMENT 'วันที่แก้ไขข้อมูล'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_type`
--

CREATE TABLE `tb_stock_type` (
  `stock_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงประเภทคลังสินค้า',
  `stock_type_name` varchar(100) NOT NULL COMMENT 'ชื่อประเภทคลังสินค้า',
  `stock_type_code` varchar(100) NOT NULL COMMENT 'หมายเลขประเภทคลังสินค้า',
  `stock_type_primary` int(11) NOT NULL COMMENT 'ประเภทคลังสินค้าใหญ่'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_type_user`
--

CREATE TABLE `tb_stock_type_user` (
  `stock_type_user_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้มีสิทธิ์เข้าถึงคลังสินค้า',
  `stock_type_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงคลังสินค้า',
  `employee_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงพนักงาน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_summit_product`
--

CREATE TABLE `tb_summit_product` (
  `summit_product_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `stock_group_id` int(11) NOT NULL,
  `summit_product_qty` int(11) NOT NULL,
  `summit_product_cost` double NOT NULL,
  `summit_product_total` double NOT NULL,
  `addby` int(11) NOT NULL,
  `adddate` varchar(50) NOT NULL,
  `updateby` int(11) NOT NULL,
  `lastupdate` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `supplier_code` varchar(20) NOT NULL COMMENT 'รหัสผู้ขาย (ใช้แสดง)',
  `supplier_name_th` varchar(200) NOT NULL COMMENT 'ชื่อผู้ขายไทย',
  `supplier_name_en` varchar(200) NOT NULL COMMENT 'ชื่อผู้ขายภาษาอังกฤษ',
  `supplier_type` varchar(100) NOT NULL COMMENT 'ประเภทบริษัท',
  `supplier_tax` varchar(100) NOT NULL COMMENT 'เลขผู้เสียภาษี',
  `supplier_address_1` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย 1',
  `supplier_address_2` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย 2',
  `supplier_address_3` text NOT NULL COMMENT 'ที่อยู่ผู้ขาย 3',
  `supplier_zipcode` varchar(10) NOT NULL COMMENT 'เลขไปรษณีย์',
  `supplier_tel` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `supplier_fax` varchar(50) NOT NULL COMMENT 'เบอร์แฟค',
  `supplier_email` varchar(200) NOT NULL COMMENT 'อีเมล',
  `supplier_domestic` varchar(20) NOT NULL COMMENT 'บริษัทของประเทศ',
  `supplier_remark` text NOT NULL COMMENT 'รายละเอียด',
  `supplier_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `supplier_zone` varchar(50) NOT NULL COMMENT 'เขตการขาย',
  `credit_day` varchar(11) NOT NULL COMMENT 'เครดิตการจ่าย',
  `condition_pay` varchar(100) NOT NULL COMMENT 'เงื่อนไขการชำระเงิน',
  `pay_limit` float NOT NULL COMMENT 'วงเงินอนุมัติ',
  `account_id` int(11) NOT NULL COMMENT 'ประเภทบัญชี',
  `vat_type` int(11) NOT NULL COMMENT 'ประเภทภาษีมูลค่าเพิ่ม',
  `vat` float NOT NULL COMMENT 'ภาษีมูลค่าเพิ่ม',
  `currency_id` int(11) NOT NULL COMMENT 'สกุลเงิน',
  `supplier_logo` varchar(200) NOT NULL COMMENT 'รูปผู้ขาย',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข',
  `supplier_accept_by` int(11) NOT NULL,
  `supplier_accept_date` varchar(50) NOT NULL,
  `supplier_accept_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier_account`
--

CREATE TABLE `tb_supplier_account` (
  `supplier_account_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงบัญชีผู้ขาย',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `supplier_account_no` varchar(50) NOT NULL COMMENT 'เลขที่บัญชี',
  `supplier_account_name` varchar(100) NOT NULL COMMENT 'ชื่อบัญชี',
  `supplier_account_bank` varchar(100) NOT NULL COMMENT 'ธนาคาร',
  `supplier_account_branch` varchar(100) NOT NULL COMMENT 'สาขา',
  `supplier_account_detail` text NOT NULL COMMENT 'รายละเอียดบัญชีเพิ่มเติ่ม',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier_contact`
--

CREATE TABLE `tb_supplier_contact` (
  `supplier_contact_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงผู้ติดต่อ',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `supplier_contact_name` varchar(100) NOT NULL COMMENT 'ชื่อผู้ติดต่อ',
  `supplier_contact_position` varchar(100) NOT NULL COMMENT 'ตำแหน่ง',
  `supplier_contact_tel` varchar(100) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `supplier_contact_email` varchar(100) NOT NULL COMMENT 'อีเมล',
  `supplier_contact_detail` text NOT NULL COMMENT 'รายละเอียดเพิ่มเติ่ม',
  `addby` int(11) NOT NULL COMMENT 'รหัสผู้เพิ่ม',
  `adddate` varchar(50) NOT NULL COMMENT 'วันที่เพิ่ม',
  `updateby` int(11) NOT NULL COMMENT 'รหัสผู้แก้ไข',
  `lastupdate` varchar(50) NOT NULL COMMENT 'วันที่แก้ไข'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier_logistic`
--

CREATE TABLE `tb_supplier_logistic` (
  `supplier_logistic_id` int(11) NOT NULL COMMENT 'รหัสอ้างอิงลักษณะการจัดส่ง',
  `supplier_id` int(11) NOT NULL COMMENT 'รหัสผู้ขาย',
  `supplier_logistic_name` varchar(100) NOT NULL COMMENT 'ชื่อการจัดส่ง',
  `supplier_logistic_detail` text NOT NULL COMMENT 'รายละอียดการจัดส่ง',
  `supplier_logistic_lead_time` varchar(50) NOT NULL COMMENT 'ระยะเวลาในการจัดส่ง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `user_id` int(11) NOT NULL,
  `user_code` varchar(15) NOT NULL,
  `user_paper_code` varchar(50) NOT NULL COMMENT 'รหัสที่ใช้แสดงบนหน้าเอกสาร',
  `user_player_id` varchar(200) NOT NULL COMMENT 'ใช้เก็บ Player id ที่ใช้ในการแจ้งเตือนของ Onesignal',
  `user_prefix` varchar(20) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_lastname` varchar(100) NOT NULL,
  `user_name_en` varchar(200) NOT NULL,
  `user_lastname_en` varchar(200) NOT NULL,
  `user_birthday` date NOT NULL,
  `user_age` int(11) NOT NULL,
  `user_nationality` varchar(100) NOT NULL,
  `user_position_id` int(11) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_mobile` varchar(20) NOT NULL,
  `user_username` varchar(100) NOT NULL,
  `user_password` varchar(50) NOT NULL,
  `user_address` varchar(200) NOT NULL,
  `user_district` varchar(100) NOT NULL,
  `user_amphur` varchar(100) NOT NULL,
  `user_province` varchar(100) NOT NULL,
  `user_zipcode` varchar(20) NOT NULL,
  `license_id` int(11) NOT NULL,
  `user_image` varchar(200) NOT NULL,
  `user_signature` text NOT NULL,
  `user_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`user_id`, `user_code`, `user_paper_code`, `user_player_id`, `user_prefix`, `user_name`, `user_lastname`, `user_name_en`, `user_lastname_en`, `user_birthday`, `user_age`, `user_nationality`, `user_position_id`, `user_email`, `user_mobile`, `user_username`, `user_password`, `user_address`, `user_district`, `user_amphur`, `user_province`, `user_zipcode`, `license_id`, `user_image`, `user_signature`, `user_status_id`) VALUES
(1, 'TH00001', '', 'a810ea5f-6f25-4be2-a6ed-abaee4785311', 'นาย', 'Thana', 'Tepchuleepornsil', 'thana', 'tepchuleepornsil', '0000-00-00', 0, '', 1, 'thana.t@revelsoft.co.th', '0987877899', 'thana', 'thana', '271/55 ตรอกวัดท่าตะโก ต.ในเมือง', 'ในเมือง   ', 'เมืองนครราชสีมา   ', 'นครราชสีมา   ', '30000', 10, '', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARgAAAEYCAYAAACHjumMAAAgAElEQVR4Xu2ddfx9SVnH3/SSgtIpLUhIlxJLd6eILLKyICUK0rmACFIiuUtJp4R0LSWggEijdHfDkvr6LGd0dvace0/NnXPufJ6/dn/fyffM+dxzZp555ljYTMAETCATgWNlKtfFmoAJmAAWGE8CEzCBbAQsMNnQumATMAELjOeACZhANgIWmGxoXbAJmIAFxnPABEwgGwELTDa0LtgETMAC4zlgAiaQjYAFJhtaF2wCJmCB8RwwARPIRsACkw2tCzYBE7DAeA6YgAlkI2CByYbWBZuACVhgPAdMwASyEbDAZEPrgk3ABCwwngMmYALZCFhgsqF1wSZgAhYYz4HSBDQHLw5cDTgA+BDwgtKNcv3zELDAzMPRpYwn8FzgFi3Zjw38z/hinXMJBCwwSxiFutpwMeAQ4JbA8Td0/VLAe+pCs3+9tcDs35guqUd6CzkvcHfgVgMbdkrg2wPzOPnCCFhgFjYgK2/OcYALApcE7gL8DDj/wD69FHgq8IaB+Zx8gQQsMAsclBU1SYuy1wBuDVy7pd1vBd4OHAE8ErjIlr49qnnbWRECN3UTAQuM58dQAqcH7teso7TlfQzwSuBdwIWARwOX6VHJT4GzAV/rkdZJVkLAArOSgSrczDMCD28WZtOm/AC4B/B64HPNH28EvLhnm98H3Bd4k3eNehJbUTILzIoGa4dN1cLswcBdO+rU24k+efSmEm8lK99He7bzOc1b0I97pneyFRKwwKxw0DI0WfNAnzNXAG4HnLOlDq2zPB/4ecvfzgd8uEe77ggc1iz+9kjuJGsnYIFZ+wiOa7+2j88DHNQs0uq/U3sJ8DTgjT0+XV7beOJ2tebqwOvGNdW51kzAArPm0evf9uMCesuQ85o+fX4FXDTK/kVAnyzvaATll/2LPiql3l5Ufmpy/9fajK1SAhaY/Rz40wBXBG4CXLeli3ro5SX7GuDjwA8nYrgx8DjgdMDDgEcAWvy1VU7AArP+CXCWxpntTsBVOrrzHeAJzduEdm2GvqGsn5J7UISABaYI9tGVngC4QHM4sGuHR4V/GdAayuE9F19HN8gZTWATAQvMsufHGYBrAg8FdDanyz4LvBx4dhPuYNm9cuuqIWCBWc5QayzkRyKv15sCB25omrxl3wa8Gvj1crrglpjA0QlYYMrNiLBVfFvgWsA5Opqi8zxPaZza5E5vM4HVELDA7GaoTtKcML58c+DvSs3uTXrSWG8mn2x2d7R1bDOBVROwwOQZvpM153bkudrmxKZaw0ljbRl/DPh+nqa4VBMoR8ACM529dnbkwKYobVo72WT3b+KcfAD4xfSqXYIJLJuABWb4+MgrVkGqrww8cEt2HeQ7cZNGhwDbvF2Ht8A5TGAlBCww/QZKEdp02E8HAbfZYxuntq8C8UnhbwGn2pbZfzeBfSJggWkfzZM228R32OAdG3J+F7gz8Lxoy1hBmT4InDoq/kzAl/Zp8rgvJrCNgAXmN4Tkbn/mJuyjAlRvsk8BhzahC7pc7p/RvPHE5Zj1ttnov+8dgRonvRZlb9MEpT53jxGVh6wCUb8C+FGP9EqS3uejA4VyorOZQFUEahCYywKPb6LdbxtcHQTUyWJFaxsbZkC7SU9KKlLg68ttq9x/N4F9I7CPAqNrM3TlRbz+sW3ctHir0AVzrJG03UaomwsVDc5mAlUR2AeB0ZmdZwJaRB1iTwTuliF8Y5vA7APnIWyd1gSOIrDGia8zPPcBHjxiDF/Y+K58YkTevlksMH1JOd3eE1iiwEhALgyconm7ULAk/b8+Yy49YkSeBWi7+Scj8g7NIqe6dCH4ycDthxbk9CawDwSWJDC6dEvbu1qUnWovagJa70JU4rYqfku6jqPrVB1SYeqIOv8qCSxJYJ7bRGobC1JvCbrTuOTDrIONOrgY25IYj2XrfCYwisCSJv+RgHxU+thHmnUY7fwsKb7s9YGXRR24YfL/ffrmNCawNwSWJDA/A47fQVahDO7ZbPUuOayB4rnEsXLVH5+a3pvHxR0ZSmBJAqPrSNNFXO366B6fqddqDOUyNr0izh0QZV4S37F9cj4TGE1gaQ+AIr4p1uxaLd6ilrPfVdfaEbfbBOYgsDSBmaNPpcqQ5/DXo8ofAijAlM0EqiVggZlv6BXN7gVRcTqh/YX5indJJrA+AhaY+cbsc03Yh1CiF3jnY+uSVkrAAjPfwKVHBMx2PrYuaaUE/BDMM3DiGDv4aWu6a8t9nhpdigmsgIAFZp5BOjmg0JnBdBDzAfMU7VJMYL0ELDDzjJ0OYupQYzDd0vjpeYp2KSawXgIWmHnG7ufA8aKifMBxHq4uZeUELDDzDKAXeOfh6FL2jIAFZp4BtcDMw9Gl7BkBC8z0AdXnUHyiW+E7D5perEswgfUTsMBMH0NdR6JrYYPpnur3TC/WJZjA+glYYKaPoYKHxyExFfKzLS7v9JpcggmsjIAFZvqApYGyzHQ6U5ewJwT8MEwfSC/wTmfoEvaUgAVm+sDGAvOg5lqU6aW6BBPYAwIWmOmDGAvMiQBFtbOZgAms9OK1JQ1cGmTKgr2k0XFbihPwAzFtCG4OPK8p4iuA7kWymYAJNAQsMNOmwpOAQ5oibgscPq045zaB/SJggZk2nt8ATtUUoatuvzetOOc2gf0iYIGZNp7xAq9ZTmPp3HtIwA/FtEENAvNB4MLTinJuE9g/AhaY8WN6kuhCuKsAbxxflHOawH4SsMCMH9dzA59osutObQWdspmACUQELDDjp8PNmruy3w1cZnwxzmkC+0vAAjN+bB8G3Au4C/D48cU4pwnsLwELzPix/TJweuCiwPvHF+OcJrC/BCww48Y2jmJ3QkAhG2wmYAIJAQvMuClxWeCIJqsZjmPoXBUQ8MMxbpC/Cpy2CY2pEJk2EzCBFgIWmHHTIjjYXQl487ginMsE9p+ABWb4GMefR15/Gc7POSoiYIEZPti6MeASXn8ZDs456iNggRk+5uHzSEcDdETAZgIm0EHAAjNsaigk5o+bLLoP6ePDsju1CdRFwAIzbLyvDbyyyeLzR8PYOXWFBCwwwwZdtwbcH/gmoHi8NhMwgQ0ELDDDpoeOBCjuy8uBGwzL6tQmUB8BC0z/MRerXzfJXwzcpH9WpzSBOglYYPqPuwNM9WfllCZwFAELTP+JcH3gZU3yMwFf6p/VKU2gTgIWmP7jfihwnya5ufXn5pQVE/CD0n/w/xs4uwWmPzCnNAELTP85EDx4fYNjf2ZOWTkBC0z/CRAE5kXATftnc0oTqJeABabf2J8Y+FGT9GDgsH7ZnMoE6iZggek3/gdGcV8cg7cfM6cyAW9T95wD9wMe3KR1DJie0JzMBPwG028OfB44s3eQ+sFyKhMIBCww/eaCL7nvx8mpTOBoBCww/SaEBaYfJ6cyAQvMiDlggRkBzVlMwG8w/eZAEJj3Apfsl8WpTMAELDDb58BJgR80yR4Q7SZtz+kUJlA5AQvM9glwIeADTbLLAW/fnsUpTMAERMACs30e3AY4vEl2MuCH27M4hQmYgAWm3xx4OnBQk9SC3I+ZU5nAUQT8wGyfCEcCukHAvLazcgoTOBoBC8z2CeEt6u2MnMIEWglYYLZPDAvMdkZOYQIWmJFzIAjMq4DrjCyjbzZdRfunwD8AugPbZgKrJuA3mO3DFwTmj4B3bk8+KUWo67+Ac00qyZlNYAEELDCbB+F0gEJkyuRwF4JO5Ri63wU+GxXssclB2WXulIAn8WbcVwTe1CTJzeoNwJUtMDud/64sM4HcD03m5mcv/qHAvQF9uhw7Y23xm1KoxmOTEbiL3g0BT+LNnP8NUIjMpwK3yzgk/wTcMir/55HvTcZqXbQJ5CVggdnMNyy6Xh44ItNQtL29/AegM1A2E1g1AQtMP4HJucD7Y+BESTN2sSW+6onrxq+DgAWme5ziXZ2cnGJHvtAavb3oLcZmAqsmkPPBWTUY4I+B5wAvBW6UqTNxKIi4Co9LJuAudrcEPJG7eT8EuC9wA+DlmYblX1si5J0T0D3YNhNYPQELTPcQalH3skCuGDDHAX6ZVH914HWrn1XugAk0BCww7VNBXH4NfAE4S6bZom3vJydlezwywXaxZQh4Qrdz/y3gu8AzAUW0y2Hp4u4jgHvmqMhlmkApAhaYdvI60SxxuRrw+gyDo21pbU/HdnzgFxnqGlOk/H7eNiaj85hATMAC0z4fHg/cCZAT3NcyTJnnAzdb2OfRqYGvR23Sie4bAh/O0H8XWQkBC0z7QP+kecM4VaZ5kH4e3b5lPSZT1cco9neAvwVu21FhTi/mXfXR9RQiYIE5Jnitv3wPeApwSIZxOW7Lp1CJcdAn2cuAa27p4+eAs2bg4CIrIFBiYi8dq94mntgs7j4jQ2PvAxxa+PPo94GPDOjbrubJaYE/Ay4MvAs4LLr0bkBznXQpBHY1cZbS3z7teDNwYEb/l/TzSDFn3tKnYTOk0dvTC5q1lbS46wJvBD6avLF8GjjHDHVvK+KEzRqQzn0FezVw7W0Z/fflErDAHHNsggDkYpMKTK560p7dtBGX9N91DELHIYJ9EzhlgTcsXc8bi4ua8NOWg6DLfZrcsmMQ2NXkXgv6cDZIaxPaQZnbTpPsSt25CfA9dz1xeacHPg7oDeF40R/uByigVip47wUuvmOBUSQ/RfRL7RPAeXLCcdl5CVhgjs5Xhxt1yDHX+aODm+BVodacvi/XbxZx4x7+DHgecEdAO2VtpsXtP9+xwLSdKFcTzpbEKc77NLj02QlYYI6ONEz0E294AKcMgpz2dDWJTGL2J1MK68grcZBIpPZJ4ErAl7bUqdsT3r5DgblMx20NWvfR+s/cprfIewB3awrW2S8tvH9g7opcnq+OjeeAJrScy2Q5hFfrC1pnkH0f+L2Znfi08/L+jkmtTx6F/+xj4RxWnFYHM3U2a247oFlnScu9OaC1oFsDH2ze+ua40UHivskzO5eozc1tNeXleJBW0/mkoe8GLgU8rPlFm7sf+izRhWqyOUNi6uF/EqDPr9T0mfTPIzqSfrL8dnM2a0RRG7Noq1xb5rH9FXD+Rlzif5/Dq7rrUyyu52LAv8/d0aS8kwCPA77YjM/eBhezwPz/yIfJJ0e78KYx5zyLJ/dcl7jppLe2lfVJF5uOIbxwQuMfBehBDyZx/McJ5bVlbVvY1RqR3mrahECxcy49sQ1a7Nab4zZT28J1NdvS9v27dub0VtZmjwYeCPywb2FrSWeB+c1IXQtQHFxZDibnbYQgzIs56tAtBLqNIDaFf9DO1NRDk/qk0m5SbHO0OZQnQWz75AlvSl1vGlo7eeSEh0tvDlrEl3gqsNcm+xvg7ybUFWfd9Pka0t2rObIxU5XLKGbOSbOMHo1rRQi8PfaTYlutwXlP6fTJonqmmH7ttEgZ+42cqccC7pA604f8FM0RiiFldKXVWlfqvHc94BVNhk2fMnrD0ZvOXKaAYq8BtNic2jWA106sqE2su4rUp+EQD+uJTcuf3QID+tX8doM6F4/4gZmy9Zru8Oj+JB1nyHFmSrsq8dUpOl3+hBmm5B8C72gpRxfbBU6bBOZBzefEDE35vyIUPkPhKbT+ktpFJu4wdfVFInmClvpyzcE5efUua68607vXR0+otYqbALkCPt0YeFFU5VjmeoXWAnRsORckHwvcJapM29x91i82DUPXOoS2jr8RZdwkMBqvNNTFyKE/WjaNi94e9Dmb2tjT7uFm0Li8uzYLvF2fTbHQztGvomWMnexFGz1j5XJ0C6/b8nQ9csayQ1HxwzLW9+U/m52VuHny0P1qhvaGInVlrh6Q2KbOlzbhaItDvElgFIBdjpA5TP17dnLLZqjnOtE6XZ+6FebirS0JY4Y6F6YjHLFt2wDQyXb5T/0BoIXvZyXi3KdtO0szdcLsrKGZKvrrZtFQ39n63p7b0tAMY7ZaP9biLq9Xep3TyWkKeJ7eZjllvrQ9cO8E9ECltklgdIRDRzlymT5bJAxyWUhNQbm6doLitF3nvtJ1rLbQHVrE1mJ2l6VntuQUeblcMKaWO2XCTK27dP7YoWzMg9+n/drVie+0Hspbnw1x0CtdZ7Jt96NPu/qkuULLKe+h7Y/rUdvPnlSsXZ00dKiSbBKYKW3o02+lUR06yd32o7PtE0bxdZQ3NX1uKlJianoLVZiKYJt8pOSWoPg8sf0KkFAt0nYxWIvseLQ1/XlAtzjmsPhBUUBvrfP0Nf1Kx7tN8tK9aN/MM6TTrk58H9S3ErEbWkUqGrp36v4dhXQJjP5dD/iuLIROjeuTa8CtOhoQbxjESTat4aR9lcNn246WymsLtRoLjDyVNc9UpnbGnp7JQbI3/5oF5j3AJYALAlrjmNviowEqW78ymgx9LJ3YcziZ9ak3TiMfkLtH/9D1C9y33PRB2uTT0iUwuded0r7oc6ltXa7rU0nrIan4bHN6lD9Q7Cgph0Y5NrbZv7S8VSmOzyubIF3pURHtBGoXrJjVKjDBN+EzLa/tcw2GtnT/oilMgZzCIcdt5Ss27tOiRFoI1NmcXVv6kE/1s9HBRW3RB/t7QGtgqWlOfgc4efIHRRkMPHfJQqL25ZYK0x8MtVdX3cTW5yI9fU7FYUu17qX1qjZrE97wDOvNSs6XqeXy7eo1BrUKjAIsaSciV1gGwY8nQ9/rYNOT0KUeqrT9+n/9msvvZqylD0dXrBcFIJcXbWq5btjs059U9JUn3S5/LnCLqLC+n5TyvNa5pNjansurttz6eYfmHJryamG6S5iKPefFKu4zqpnS6HCgFhz1C6Rf5Rym3QL9Cgfrw1kTTWeAQlAoiU38JpOjnV1ltv0a9+nDpjbqNHZchpzttFMVW+ozFP6mvBq3Utb1qRS/1Q1ZY4r7cYYWD2y9/cqxMba2t5fwFtV2DXGcd9vCdDauUydNtoZlLFhrCXIiO6i5XC1HVfGvkj5v9JmzybT4G7YmtU6j/1eMklKW/hqrHVPnSvqAyN9EF9wFU+xdrSV02dT6p7Js+1QKIT3P2JyMjusYsua26dNHZcrBUQc1Y9OCb3hjkpuFLgnssjmPeQziWHrQBjV2hsR6Owiv+XJYSrf8ZqjiqCLiCbMtlooizMVrLI+JgiHN1Z6h5aQTXkcGpoYUSMvUJ4Tc8xVZT349ig28yZYQqyX8OMXtlL+MFuFTG/JsaS5qPTC22PFTf0uvjgl/b3t7kfDp78G04TBHPJ2h82jyr9LgCgtnCFuv6a/nnM3SRWZ6eGSbxEKvremuUghXMGd7hpbVNmGHPCxd9X1qoA+PdkDkTh9sV7cbbOKlh7Yr1GicT28cOloxxLStrEXhYLq+RdvMslScXwLoc1KmXSetxcQmMdYyQDC5YcgdY+c2x8TZeaMnVKgtR31Pny8JnzChyGNk1cJxiNIvJ7kgNmlCBTVKtxCLfStHjdO5qTB59c8Ks6nT4FOtbTFzU5kS6nAINaRbwnzVTlG6wxX3o+/ibtr3dCFZJ87PBcTHWVIOcrOQu0VsOpEucdah0GBTF+hHj/0SBmx04wdmDAGO5ji0t6lqvS5fsnFS6zozoy3aEBM2lLVJjAZ2dVLyNCjTtk+8IZWFqIHb8oQQn+kWrtY62raMt5U3598lenKo67KxflVt6yx6PhW/RmfYgoXgXwq+pcvpUtOPlN58FG40WLHnvFjFc454z7J+2exEaMC07pHDwrWzKlu/SIe3VKLF5fDqG/6c66jCmD4+NQq/qRCfevOYyxTvROsuXQ+o1nn0QxDe+tLAVLmukxnSv03HGCQ+6Z1SfcvW9Sw6dxabnk99DsVX6OhtRGPSFnhLP2wKFKYdqPhYwhxxbfr24xgdGJVxZZnCfUdq9pDV/aHdlKDcpsnUFnqzbbdhCb/KcT81gbXgqvu5FXG/r/fxUFYhvRbe9eut0J9tgcUVIF0+MDJ9DrQdQhxb95h8mwRmytzSae0QcEvt0u0P2gbXdr5i6ATTJ6zCi6QWr8u0zbMiLxNFKh0zqhPzhEkxZxjEtibFky9lq/9PH6BtR/Mndnsvsqenuqc8xHMAUdxcHdJMbepVN18HdAQh2F827hT6DOoTi1hrVrHvlX4g9CMXbI6dwMH8ahAYuaeH+3VyhjnQHdMhULS2v9NtxdRXQWdW0pi6gwewggzprlYf9/ucWNLzRgpSpTeK1E9lSBvimNAhn05Of6HZDUpPocdld3lEi5N2pmLb+fO+8wqHUJ8pbXir2HR6d46qdPWp1g9kmnAvjgp9QBLmUaeI1R5bPwJh90+pS29Xyx1fbvta79ADPCUIeei93jzkDBcsPjXe5gMT0mnDQte+dH3Gpp9zCguhN6Wd2b4LjLaBwx03Oa9p1Vkj+XnI0pAC6aKu1hq0TW7rT0CL4uIYbI55qzLkCRt2aORaoNg96dZ4/1aOT7nJk1cXzyl6XZuloUbTNG3nuuZg17unO62sd6vmSxgGLrd3bHrQLHDVBfMPTrqz65AD89EsV1L6CTGHX0fbFbva2tUn0K5N3uXhDFoqoorv8vCWBik2UNdNniF5mzOnWCrsw05snwVG7vdhOzpnP9ODjSHOSVvISW0h6tXaNoxAug4zND5uWlub85rSKAbxfYc1bZbU8iyP7ymP11W0I6RDsArDqU9FeeQqBEPfNZ82Id3ZQnnOB28W8iMLiXds5DMQrmwdWdzGbBINOYYF05aqXP7b7u7Z9kqbo337Umb8GTH1M1PrJm2xaBRJTs6Auza59muHU17TilOsmwfm/FT7cPJZ3naSPUuf91VgdBWntvlkOdVav6wSkhBKQDF0JSK6WTGNkxqffs0ymHteaHqzwpS5Kx8ThUmITdeV9H0rWBvqtvNlUyMU9mIwZZB6VVAgkbaiQyDpCwBS71yWXt+qrWqt0rfdzrePrHNxbSs3vSFR53R0XmeoyStWTmmxaRwVomKfTetW2q3S8xFM/jVtJ8Fn47CPk173DuubVTDlfJTTtPimRbhg2rVqW3iT+318u0DONu1z2fFnkrymdRxjiOkAYFug8Zw7jEPat4u0Og0eh3IIMX2z1L1vAhO/Cu5ityY9Yi/X+rbAP/vGOctk7FGo1ki0RhEs9V7dVETqixTSHgwc1qPufUmiNUIdv4hNp7Lfl6OD+zbxw5H3TVdLzMlx07mUUI/eqLQmZJuHQMz8s0kg8a4aDuwIOdEWmnKeVi67lLazSjpsqd2rWW2fBCbeejxzSwjDWcE1hfURmH1inIPh0DLTNRTd170pvGjXlvSmC86GtmmN6dvcKGZ/69+nya/LrRSF/xDgKTsa8W0CE47P76g51VSTct/0JiK/Ef3gxFbqKpilDZCu0nl90iitIypg1Sy2LwIThzJsC5MwC6yWQjYFHyp+6VWuTi+g3LZI/ApjkF4k3xZ7R28uCsW57cdhAd3cSRPavM3PHR19mdSIfRGYoMTaqdGOza7sQ4C2wtusyPH4XXV8AfXokF+bO4BuUlTUOzmTtdmSgnstAONRTdDOWhxiU/+mmy3iHdJRbd0HgdF5i7cA8obU4tUureuciNqwD2x3yXJMXYqfMuR0sNzx4/CTY+rc1zxaxzq0pXN6W/zK2E7vw0MQnN1y3tLYxVeC9kBAEeCDyXFJ60F6u7HlJxDf4rCpNvks3Tt/c1Zdg06Xtzkcjj77tXaBCWeOFA9DK+By1bfVR0AR5vRJ1BXWQOeOFGjdtp2Adt20fqhP0NgUgF1vgIqU19vWLjByalOkuHsBin1hq5eAnCy1qBuu29Uxglc1by1tB0/rJdWv5zqKkd7tNNgpcc0CE8e6yBkKs99wOJUJ7CeB+L5wXfWr0BK9bc0CE7x25eYtZbWZgAnkIaBrZrQEoTAZg7b31yow8duLgnrLZdxmAiawMAJrFZiwpabL1NJQgwtD7OaYQL0E1igw8Ynp7PEs6p0a7rkJTCewRoFRpLpwOnmN7Z8+ai7BBFZCYI0PaFhkUjDjsCW5EtxupgnURWBtAnM94OXNEK2t7XXNLPfWBFZ4Xia8veiWAN0WYDMBE1gwgTW9Bch1Wed7tMh78pawfwvG7KaZQJ0E1iQwiuNxweYwlg442kzABBZOYC0CE7srK97HCxfO1c0zARNY0RpM7J68FlH0BDOB6gms4WFVLIpXNCOlwFK63MxmAiawAgJrEJh3AfLYlSkSelcoxBXgdhNNoC4CSxeY9LpQBfc+sq4hcm9NYL0Eli4wnwHOGuGV74t8YGwmYAIrILB0gWmLPbH0Nq9g2N1EE9gNgaU/rBaY3cwD12ICWQgsXWB+ChwQ9fxjLcGIs4BxoSZgAtMJLF1gFAn+blE3bw08a3q3XYIJmMAuCCxdYMTg7sA1gCOaqyd+uAswrsMETGA6gTUIzPReugQTMIEiBCwwRbC7UhOog4AFpo5xdi9NoAgBC0wR7K7UBOogYIGpY5zdSxMoQsACUwS7KzWBOghYYOoYZ/fSBIoQsMAUwe5KTaAOAhaYOsbZvTSBIgQsMEWwu1ITqIOABaaOcXYvTaAIAQtMEeyu1ATqIGCBqWOc3UsTKELAAlMEuys1gToIWGDqGGf30gSKELDAFMHuSk2gDgIWmDrG2b00gSIELDBFsLtSE6iDgAWmjnF2L02gCAELTBHsrtQE6iBggaljnN1LEyhCwAJTBLsrNYE6CFhg6hhn99IEihCwwBTB7kpNoA4CFpg6xtm9NIEiBCwwRbC7UhOog4AFpo5xdi9NoAgBC0wR7K7UBOogYIGpY5zdSxMoQsACUwS7KzWBOghYYOoYZ/fSBIoQsMAUwe5KTaAOAhaYOsbZvTSBIgQsMEWwu1ITqIOABaaOcXYvTaAIAQtMEeyu1ATqIGCBqWOc3UsTKELAAlMEuys1gToIWGDqGGf30gSKELDAFMHuSk2gDgIWmDrG2b00gSIELDBFsLtSE6iDgAWmjnF2L02gCAELTBHsrtQE6iBggaljnN1LEyhCwAJTBLsrNV9AZiQAAAExSURBVIE6CFhg6hhn99IEihCwwBTB7kpNoA4CFpg6xtm9NIEiBCwwRbC7UhOog4AFpo5xdi9NoAgBC0wR7K7UBOogYIGpY5zdSxMoQsACUwS7KzWBOghYYOoYZ/fSBIoQsMAUwe5KTaAOAhaYOsbZvTSBIgQsMEWwu1ITqIOABaaOcXYvTaAIAQtMEeyu1ATqIGCBqWOc3UsTKELAAlMEuys1gToIWGDqGGf30gSKELDAFMHuSk2gDgIWmDrG2b00gSIELDBFsLtSE6iDgAWmjnF2L02gCAELTBHsrtQE6iBggaljnN1LEyhCwAJTBLsrNYE6CFhg6hhn99IEihCwwBTB7kpNoA4CFpg6xtm9NIEiBCwwRbC7UhOog4AFpo5xdi9NoAgBC0wR7K7UBOog8L84z05VGGItUgAAAABJRU5ErkJggg==', 1),
(4, 'PC002', '', 'a810ea5f-6f25-4be2-a6ed-abaee4785311', 'นางสาว', 'BOONSRI', 'SILAPAKRANPRADIT', '', '', '0000-00-00', 0, '', 4, 'boonsri@partnerchips.co.th', '092-764-0990', 'boonsri', 'boonsri2018', 'Bangkok', 'บางนา   ', 'เขตบางนา   ', 'กรุงเทพมหานคร   ', '10260', 7, '', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARgAAAEYCAYAAACHjumMAAAAAXNSR0IArs4c6QAABXBJREFUeAHt0DEBAAAAwqD1T20ND4hAYcCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBgwYMCAAQMGDBj4HBjKVAABZZSAbgAAAABJRU5ErkJggg==', 1),
(26, 'JECK', '', '', 'นาย', 'Santisook', 'Daowdon', '', '', '0000-00-00', 0, '', 1, 'mr.jeck.ryo@gmail.com', '0986959369', 'jeck', 'jeck', '159 ม. 18', 'ในเมือง   ', 'เมืองนครราชสีมา   ', 'นครราชสีมา   ', '30000', 1, '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_position`
--

CREATE TABLE `tb_user_position` (
  `user_position_id` int(11) NOT NULL,
  `user_position_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_user_position`
--

INSERT INTO `tb_user_position` (`user_position_id`, `user_position_name`) VALUES
(1, 'ผู้ดูแลระบบ'),
(2, 'Managing Director'),
(3, 'Sales & Application Manager'),
(4, 'Account'),
(5, 'Sales & Application Engineer'),
(6, 'Sales Representative'),
(7, 'Sales Coordinator'),
(8, 'Project and Application'),
(9, 'Key Account'),
(10, 'Logistic'),
(11, 'Marketing Executive'),
(12, 'Maid');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_status`
--

CREATE TABLE `tb_user_status` (
  `user_status_id` int(11) NOT NULL,
  `user_status_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_user_status`
--

INSERT INTO `tb_user_status` (`user_status_id`, `user_status_name`) VALUES
(1, 'ทำงาน'),
(2, 'ลาออก');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `geography`
--
ALTER TABLE `geography`
  ADD PRIMARY KEY (`GEO_ID`);

--
-- Indexes for table `tb_account`
--
ALTER TABLE `tb_account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `tb_account_group`
--
ALTER TABLE `tb_account_group`
  ADD PRIMARY KEY (`account_group_id`);

--
-- Indexes for table `tb_account_setting`
--
ALTER TABLE `tb_account_setting`
  ADD PRIMARY KEY (`account_setting_id`);

--
-- Indexes for table `tb_amphur`
--
ALTER TABLE `tb_amphur`
  ADD PRIMARY KEY (`AMPHUR_ID`);

--
-- Indexes for table `tb_bank`
--
ALTER TABLE `tb_bank`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `tb_bank_account`
--
ALTER TABLE `tb_bank_account`
  ADD PRIMARY KEY (`bank_account_id`);

--
-- Indexes for table `tb_billing_note`
--
ALTER TABLE `tb_billing_note`
  ADD PRIMARY KEY (`billing_note_id`);

--
-- Indexes for table `tb_billing_note_list`
--
ALTER TABLE `tb_billing_note_list`
  ADD PRIMARY KEY (`billing_note_list_id`);

--
-- Indexes for table `tb_check`
--
ALTER TABLE `tb_check`
  ADD PRIMARY KEY (`check_id`);

--
-- Indexes for table `tb_check_pay`
--
ALTER TABLE `tb_check_pay`
  ADD PRIMARY KEY (`check_pay_id`);

--
-- Indexes for table `tb_company`
--
ALTER TABLE `tb_company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `tb_credit_note`
--
ALTER TABLE `tb_credit_note`
  ADD PRIMARY KEY (`credit_note_id`);

--
-- Indexes for table `tb_credit_note_list`
--
ALTER TABLE `tb_credit_note_list`
  ADD PRIMARY KEY (`credit_note_list_id`);

--
-- Indexes for table `tb_credit_note_type`
--
ALTER TABLE `tb_credit_note_type`
  ADD PRIMARY KEY (`credit_note_type_id`);

--
-- Indexes for table `tb_credit_purchasing`
--
ALTER TABLE `tb_credit_purchasing`
  ADD PRIMARY KEY (`credit_purchasing_id`);

--
-- Indexes for table `tb_credit_purchasing_list`
--
ALTER TABLE `tb_credit_purchasing_list`
  ADD PRIMARY KEY (`credit_purchasing_list_id`);

--
-- Indexes for table `tb_currency`
--
ALTER TABLE `tb_currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indexes for table `tb_customer`
--
ALTER TABLE `tb_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tb_customer_account`
--
ALTER TABLE `tb_customer_account`
  ADD PRIMARY KEY (`customer_account_id`);

--
-- Indexes for table `tb_customer_contact`
--
ALTER TABLE `tb_customer_contact`
  ADD PRIMARY KEY (`customer_contact_id`);

--
-- Indexes for table `tb_customer_holiday`
--
ALTER TABLE `tb_customer_holiday`
  ADD PRIMARY KEY (`customer_holiday_id`);

--
-- Indexes for table `tb_customer_logistic`
--
ALTER TABLE `tb_customer_logistic`
  ADD PRIMARY KEY (`customer_logistic_id`);

--
-- Indexes for table `tb_customer_purchase_order`
--
ALTER TABLE `tb_customer_purchase_order`
  ADD PRIMARY KEY (`customer_purchase_order_id`);

--
-- Indexes for table `tb_customer_purchase_order_list`
--
ALTER TABLE `tb_customer_purchase_order_list`
  ADD PRIMARY KEY (`customer_purchase_order_list_id`);

--
-- Indexes for table `tb_customer_purchase_order_list_detail`
--
ALTER TABLE `tb_customer_purchase_order_list_detail`
  ADD PRIMARY KEY (`customer_purchase_order_list_detail_id`);

--
-- Indexes for table `tb_customer_type`
--
ALTER TABLE `tb_customer_type`
  ADD PRIMARY KEY (`customer_type_id`);

--
-- Indexes for table `tb_debit_note`
--
ALTER TABLE `tb_debit_note`
  ADD PRIMARY KEY (`debit_note_id`);

--
-- Indexes for table `tb_debit_note_list`
--
ALTER TABLE `tb_debit_note_list`
  ADD PRIMARY KEY (`debit_note_list_id`);

--
-- Indexes for table `tb_delivery_note_customer`
--
ALTER TABLE `tb_delivery_note_customer`
  ADD PRIMARY KEY (`delivery_note_customer_id`);

--
-- Indexes for table `tb_delivery_note_customer_list`
--
ALTER TABLE `tb_delivery_note_customer_list`
  ADD PRIMARY KEY (`delivery_note_customer_list_id`);

--
-- Indexes for table `tb_delivery_note_supplier`
--
ALTER TABLE `tb_delivery_note_supplier`
  ADD PRIMARY KEY (`delivery_note_supplier_id`);

--
-- Indexes for table `tb_delivery_note_supplier_list`
--
ALTER TABLE `tb_delivery_note_supplier_list`
  ADD PRIMARY KEY (`delivery_note_supplier_list_id`);

--
-- Indexes for table `tb_district`
--
ALTER TABLE `tb_district`
  ADD PRIMARY KEY (`DISTRICT_ID`);

--
-- Indexes for table `tb_exchange_rate_baht`
--
ALTER TABLE `tb_exchange_rate_baht`
  ADD PRIMARY KEY (`exchange_rate_baht_id`);

--
-- Indexes for table `tb_finance_credit`
--
ALTER TABLE `tb_finance_credit`
  ADD PRIMARY KEY (`finance_credit_id`);

--
-- Indexes for table `tb_finance_credit_account`
--
ALTER TABLE `tb_finance_credit_account`
  ADD PRIMARY KEY (`finance_credit_account_id`);

--
-- Indexes for table `tb_finance_credit_list`
--
ALTER TABLE `tb_finance_credit_list`
  ADD PRIMARY KEY (`finance_credit_list_id`);

--
-- Indexes for table `tb_finance_credit_pay`
--
ALTER TABLE `tb_finance_credit_pay`
  ADD PRIMARY KEY (`finance_credit_pay_id`);

--
-- Indexes for table `tb_finance_debit`
--
ALTER TABLE `tb_finance_debit`
  ADD PRIMARY KEY (`finance_debit_id`);

--
-- Indexes for table `tb_finance_debit_account`
--
ALTER TABLE `tb_finance_debit_account`
  ADD PRIMARY KEY (`finance_debit_account_id`);

--
-- Indexes for table `tb_finance_debit_list`
--
ALTER TABLE `tb_finance_debit_list`
  ADD PRIMARY KEY (`finance_debit_list_id`);

--
-- Indexes for table `tb_finance_debit_pay`
--
ALTER TABLE `tb_finance_debit_pay`
  ADD PRIMARY KEY (`finance_debit_pay_id`);

--
-- Indexes for table `tb_holiday`
--
ALTER TABLE `tb_holiday`
  ADD PRIMARY KEY (`holiday_id`);

--
-- Indexes for table `tb_invoice_customer`
--
ALTER TABLE `tb_invoice_customer`
  ADD PRIMARY KEY (`invoice_customer_id`);

--
-- Indexes for table `tb_invoice_customer_list`
--
ALTER TABLE `tb_invoice_customer_list`
  ADD PRIMARY KEY (`invoice_customer_list_id`);

--
-- Indexes for table `tb_invoice_supplier`
--
ALTER TABLE `tb_invoice_supplier`
  ADD PRIMARY KEY (`invoice_supplier_id`);

--
-- Indexes for table `tb_invoice_supplier_list`
--
ALTER TABLE `tb_invoice_supplier_list`
  ADD PRIMARY KEY (`invoice_supplier_list_id`);

--
-- Indexes for table `tb_job`
--
ALTER TABLE `tb_job`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `tb_job_operation`
--
ALTER TABLE `tb_job_operation`
  ADD PRIMARY KEY (`job_operation_id`);

--
-- Indexes for table `tb_job_operation_process`
--
ALTER TABLE `tb_job_operation_process`
  ADD PRIMARY KEY (`job_operation_process_id`);

--
-- Indexes for table `tb_job_operation_process_tool`
--
ALTER TABLE `tb_job_operation_process_tool`
  ADD PRIMARY KEY (`job_operation_process_tool_id`);

--
-- Indexes for table `tb_journal`
--
ALTER TABLE `tb_journal`
  ADD PRIMARY KEY (`journal_id`);

--
-- Indexes for table `tb_journal_cash_payment`
--
ALTER TABLE `tb_journal_cash_payment`
  ADD PRIMARY KEY (`journal_cash_payment_id`);

--
-- Indexes for table `tb_journal_cash_payment_invoice`
--
ALTER TABLE `tb_journal_cash_payment_invoice`
  ADD PRIMARY KEY (`journal_cash_payment_invoice_id`);

--
-- Indexes for table `tb_journal_cash_payment_list`
--
ALTER TABLE `tb_journal_cash_payment_list`
  ADD PRIMARY KEY (`journal_cash_payment_list_id`);

--
-- Indexes for table `tb_journal_cash_receipt`
--
ALTER TABLE `tb_journal_cash_receipt`
  ADD PRIMARY KEY (`journal_cash_receipt_id`);

--
-- Indexes for table `tb_journal_cash_receipt_list`
--
ALTER TABLE `tb_journal_cash_receipt_list`
  ADD PRIMARY KEY (`journal_cash_receipt_list_id`);

--
-- Indexes for table `tb_journal_general`
--
ALTER TABLE `tb_journal_general`
  ADD PRIMARY KEY (`journal_general_id`);

--
-- Indexes for table `tb_journal_general_list`
--
ALTER TABLE `tb_journal_general_list`
  ADD PRIMARY KEY (`journal_general_list_id`);

--
-- Indexes for table `tb_journal_purchase`
--
ALTER TABLE `tb_journal_purchase`
  ADD PRIMARY KEY (`journal_purchase_id`);

--
-- Indexes for table `tb_journal_purchase_list`
--
ALTER TABLE `tb_journal_purchase_list`
  ADD PRIMARY KEY (`journal_purchase_list_id`);

--
-- Indexes for table `tb_journal_purchase_return`
--
ALTER TABLE `tb_journal_purchase_return`
  ADD PRIMARY KEY (`journal_purchase_return_id`);

--
-- Indexes for table `tb_journal_purchase_return_list`
--
ALTER TABLE `tb_journal_purchase_return_list`
  ADD PRIMARY KEY (`journal_purchase_return_list_id`);

--
-- Indexes for table `tb_journal_sale`
--
ALTER TABLE `tb_journal_sale`
  ADD PRIMARY KEY (`journal_sale_id`);

--
-- Indexes for table `tb_journal_sale_list`
--
ALTER TABLE `tb_journal_sale_list`
  ADD PRIMARY KEY (`journal_sale_list_id`);

--
-- Indexes for table `tb_journal_sale_return`
--
ALTER TABLE `tb_journal_sale_return`
  ADD PRIMARY KEY (`journal_sale_return_id`);

--
-- Indexes for table `tb_journal_sale_return_list`
--
ALTER TABLE `tb_journal_sale_return_list`
  ADD PRIMARY KEY (`journal_sale_return_list_id`);

--
-- Indexes for table `tb_license`
--
ALTER TABLE `tb_license`
  ADD PRIMARY KEY (`license_id`);

--
-- Indexes for table `tb_main_setting`
--
ALTER TABLE `tb_main_setting`
  ADD PRIMARY KEY (`main_setting_id`);

--
-- Indexes for table `tb_notification`
--
ALTER TABLE `tb_notification`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tb_official_receipt`
--
ALTER TABLE `tb_official_receipt`
  ADD PRIMARY KEY (`official_receipt_id`);

--
-- Indexes for table `tb_official_receipt_list`
--
ALTER TABLE `tb_official_receipt_list`
  ADD PRIMARY KEY (`official_receipt_list_id`);

--
-- Indexes for table `tb_other_expense`
--
ALTER TABLE `tb_other_expense`
  ADD PRIMARY KEY (`other_expense_id`);

--
-- Indexes for table `tb_other_expense_list`
--
ALTER TABLE `tb_other_expense_list`
  ADD PRIMARY KEY (`other_expense_list_id`);

--
-- Indexes for table `tb_other_expense_pay`
--
ALTER TABLE `tb_other_expense_pay`
  ADD PRIMARY KEY (`other_expense_pay_id`);

--
-- Indexes for table `tb_paper`
--
ALTER TABLE `tb_paper`
  ADD PRIMARY KEY (`paper_id`);

--
-- Indexes for table `tb_paper_lock`
--
ALTER TABLE `tb_paper_lock`
  ADD PRIMARY KEY (`paper_lock_id`);

--
-- Indexes for table `tb_paper_type`
--
ALTER TABLE `tb_paper_type`
  ADD PRIMARY KEY (`paper_type_id`);

--
-- Indexes for table `tb_product`
--
ALTER TABLE `tb_product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `tb_product_category`
--
ALTER TABLE `tb_product_category`
  ADD PRIMARY KEY (`product_category_id`);

--
-- Indexes for table `tb_product_customer`
--
ALTER TABLE `tb_product_customer`
  ADD PRIMARY KEY (`product_customer_id`);

--
-- Indexes for table `tb_product_customer_price`
--
ALTER TABLE `tb_product_customer_price`
  ADD PRIMARY KEY (`product_id`,`customer_id`);

--
-- Indexes for table `tb_product_group`
--
ALTER TABLE `tb_product_group`
  ADD PRIMARY KEY (`product_group_id`);

--
-- Indexes for table `tb_product_supplier`
--
ALTER TABLE `tb_product_supplier`
  ADD PRIMARY KEY (`product_supplier_id`);

--
-- Indexes for table `tb_product_type`
--
ALTER TABLE `tb_product_type`
  ADD PRIMARY KEY (`product_type_id`);

--
-- Indexes for table `tb_product_unit`
--
ALTER TABLE `tb_product_unit`
  ADD PRIMARY KEY (`product_unit_id`);

--
-- Indexes for table `tb_province`
--
ALTER TABLE `tb_province`
  ADD PRIMARY KEY (`PROVINCE_ID`);

--
-- Indexes for table `tb_purchase_order`
--
ALTER TABLE `tb_purchase_order`
  ADD PRIMARY KEY (`purchase_order_id`);

--
-- Indexes for table `tb_purchase_order_list`
--
ALTER TABLE `tb_purchase_order_list`
  ADD PRIMARY KEY (`purchase_order_list_id`);

--
-- Indexes for table `tb_purchase_order_list_detail`
--
ALTER TABLE `tb_purchase_order_list_detail`
  ADD PRIMARY KEY (`purchase_order_list_detail_id`);

--
-- Indexes for table `tb_purchase_request`
--
ALTER TABLE `tb_purchase_request`
  ADD PRIMARY KEY (`purchase_request_id`);

--
-- Indexes for table `tb_purchase_request_list`
--
ALTER TABLE `tb_purchase_request_list`
  ADD PRIMARY KEY (`purchase_request_list_id`);

--
-- Indexes for table `tb_quotation`
--
ALTER TABLE `tb_quotation`
  ADD PRIMARY KEY (`quotation_id`);

--
-- Indexes for table `tb_quotation_list`
--
ALTER TABLE `tb_quotation_list`
  ADD PRIMARY KEY (`quotation_list_id`);

--
-- Indexes for table `tb_regrind_supplier`
--
ALTER TABLE `tb_regrind_supplier`
  ADD PRIMARY KEY (`regrind_supplier_id`);

--
-- Indexes for table `tb_regrind_supplier_list`
--
ALTER TABLE `tb_regrind_supplier_list`
  ADD PRIMARY KEY (`regrind_supplier_list_id`);

--
-- Indexes for table `tb_regrind_supplier_receive`
--
ALTER TABLE `tb_regrind_supplier_receive`
  ADD PRIMARY KEY (`regrind_supplier_receive_id`);

--
-- Indexes for table `tb_regrind_supplier_receive_list`
--
ALTER TABLE `tb_regrind_supplier_receive_list`
  ADD PRIMARY KEY (`regrind_supplier_receive_list_id`);

--
-- Indexes for table `tb_request_regrind`
--
ALTER TABLE `tb_request_regrind`
  ADD PRIMARY KEY (`request_regrind_id`);

--
-- Indexes for table `tb_request_regrind_list`
--
ALTER TABLE `tb_request_regrind_list`
  ADD PRIMARY KEY (`request_regrind_list_id`);

--
-- Indexes for table `tb_request_special`
--
ALTER TABLE `tb_request_special`
  ADD PRIMARY KEY (`request_special_id`);

--
-- Indexes for table `tb_request_special_list`
--
ALTER TABLE `tb_request_special_list`
  ADD PRIMARY KEY (`request_special_list_id`);

--
-- Indexes for table `tb_request_standard`
--
ALTER TABLE `tb_request_standard`
  ADD PRIMARY KEY (`request_standard_id`);

--
-- Indexes for table `tb_request_standard_list`
--
ALTER TABLE `tb_request_standard_list`
  ADD PRIMARY KEY (`request_standard_list_id`);

--
-- Indexes for table `tb_request_test`
--
ALTER TABLE `tb_request_test`
  ADD PRIMARY KEY (`request_test_id`);

--
-- Indexes for table `tb_request_test_list`
--
ALTER TABLE `tb_request_test_list`
  ADD PRIMARY KEY (`request_test_list_id`);

--
-- Indexes for table `tb_stock_group`
--
ALTER TABLE `tb_stock_group`
  ADD PRIMARY KEY (`stock_group_id`);

--
-- Indexes for table `tb_stock_issue`
--
ALTER TABLE `tb_stock_issue`
  ADD PRIMARY KEY (`stock_issue_id`);

--
-- Indexes for table `tb_stock_issue_list`
--
ALTER TABLE `tb_stock_issue_list`
  ADD PRIMARY KEY (`stock_issue_list_id`);

--
-- Indexes for table `tb_stock_move`
--
ALTER TABLE `tb_stock_move`
  ADD PRIMARY KEY (`stock_move_id`);

--
-- Indexes for table `tb_stock_move_list`
--
ALTER TABLE `tb_stock_move_list`
  ADD PRIMARY KEY (`stock_move_list_id`);

--
-- Indexes for table `tb_stock_report`
--
ALTER TABLE `tb_stock_report`
  ADD PRIMARY KEY (`stock_report_id`);

--
-- Indexes for table `tb_stock_type`
--
ALTER TABLE `tb_stock_type`
  ADD PRIMARY KEY (`stock_type_id`);

--
-- Indexes for table `tb_stock_type_user`
--
ALTER TABLE `tb_stock_type_user`
  ADD PRIMARY KEY (`stock_type_user_id`);

--
-- Indexes for table `tb_summit_product`
--
ALTER TABLE `tb_summit_product`
  ADD PRIMARY KEY (`summit_product_id`);

--
-- Indexes for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `tb_supplier_account`
--
ALTER TABLE `tb_supplier_account`
  ADD PRIMARY KEY (`supplier_account_id`);

--
-- Indexes for table `tb_supplier_contact`
--
ALTER TABLE `tb_supplier_contact`
  ADD PRIMARY KEY (`supplier_contact_id`);

--
-- Indexes for table `tb_supplier_logistic`
--
ALTER TABLE `tb_supplier_logistic`
  ADD PRIMARY KEY (`supplier_logistic_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tb_user_position`
--
ALTER TABLE `tb_user_position`
  ADD PRIMARY KEY (`user_position_id`);

--
-- Indexes for table `tb_user_status`
--
ALTER TABLE `tb_user_status`
  ADD PRIMARY KEY (`user_status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `geography`
--
ALTER TABLE `geography`
  MODIFY `GEO_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_account`
--
ALTER TABLE `tb_account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=254;
--
-- AUTO_INCREMENT for table `tb_account_group`
--
ALTER TABLE `tb_account_group`
  MODIFY `account_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงกลุ่มบัญชี', AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_account_setting`
--
ALTER TABLE `tb_account_setting`
  MODIFY `account_setting_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงการตั้งค่าบัญชี', AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `tb_amphur`
--
ALTER TABLE `tb_amphur`
  MODIFY `AMPHUR_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1007;
--
-- AUTO_INCREMENT for table `tb_bank`
--
ALTER TABLE `tb_bank`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `tb_bank_account`
--
ALTER TABLE `tb_bank_account`
  MODIFY `bank_account_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_billing_note`
--
ALTER TABLE `tb_billing_note`
  MODIFY `billing_note_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบวางบิล';
--
-- AUTO_INCREMENT for table `tb_billing_note_list`
--
ALTER TABLE `tb_billing_note_list`
  MODIFY `billing_note_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบวางบิล';
--
-- AUTO_INCREMENT for table `tb_check`
--
ALTER TABLE `tb_check`
  MODIFY `check_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_check_pay`
--
ALTER TABLE `tb_check_pay`
  MODIFY `check_pay_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_company`
--
ALTER TABLE `tb_company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_credit_note`
--
ALTER TABLE `tb_credit_note`
  MODIFY `credit_note_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบลดหนี้';
--
-- AUTO_INCREMENT for table `tb_credit_note_list`
--
ALTER TABLE `tb_credit_note_list`
  MODIFY `credit_note_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบลดหนี้';
--
-- AUTO_INCREMENT for table `tb_credit_note_type`
--
ALTER TABLE `tb_credit_note_type`
  MODIFY `credit_note_type_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_credit_purchasing`
--
ALTER TABLE `tb_credit_purchasing`
  MODIFY `credit_purchasing_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_credit_purchasing_list`
--
ALTER TABLE `tb_credit_purchasing_list`
  MODIFY `credit_purchasing_list_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_currency`
--
ALTER TABLE `tb_currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;
--
-- AUTO_INCREMENT for table `tb_customer`
--
ALTER TABLE `tb_customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ขาย';
--
-- AUTO_INCREMENT for table `tb_customer_account`
--
ALTER TABLE `tb_customer_account`
  MODIFY `customer_account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงบัญชีลูกค้า';
--
-- AUTO_INCREMENT for table `tb_customer_contact`
--
ALTER TABLE `tb_customer_contact`
  MODIFY `customer_contact_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงผู้ติดต่อ';
--
-- AUTO_INCREMENT for table `tb_customer_holiday`
--
ALTER TABLE `tb_customer_holiday`
  MODIFY `customer_holiday_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_customer_logistic`
--
ALTER TABLE `tb_customer_logistic`
  MODIFY `customer_logistic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงลักษณะการจัดส่ง';
--
-- AUTO_INCREMENT for table `tb_customer_purchase_order`
--
ALTER TABLE `tb_customer_purchase_order`
  MODIFY `customer_purchase_order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิง PO ของลูกค้า';
--
-- AUTO_INCREMENT for table `tb_customer_purchase_order_list`
--
ALTER TABLE `tb_customer_purchase_order_list`
  MODIFY `customer_purchase_order_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบสั่งซื้อสินค้าของลูกค้า';
--
-- AUTO_INCREMENT for table `tb_customer_purchase_order_list_detail`
--
ALTER TABLE `tb_customer_purchase_order_list_detail`
  MODIFY `customer_purchase_order_list_detail_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_customer_type`
--
ALTER TABLE `tb_customer_type`
  MODIFY `customer_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงประเภทลูกค้า', AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_debit_note`
--
ALTER TABLE `tb_debit_note`
  MODIFY `debit_note_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบเพิ่มหนี้';
--
-- AUTO_INCREMENT for table `tb_debit_note_list`
--
ALTER TABLE `tb_debit_note_list`
  MODIFY `debit_note_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบเพิ่มหนี้';
--
-- AUTO_INCREMENT for table `tb_delivery_note_customer`
--
ALTER TABLE `tb_delivery_note_customer`
  MODIFY `delivery_note_customer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบยืมผู้ซื้อสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_delivery_note_customer_list`
--
ALTER TABLE `tb_delivery_note_customer_list`
  MODIFY `delivery_note_customer_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบยืมผู้ซื้อสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_delivery_note_supplier`
--
ALTER TABLE `tb_delivery_note_supplier`
  MODIFY `delivery_note_supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบยืมผู้ซื้อสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_delivery_note_supplier_list`
--
ALTER TABLE `tb_delivery_note_supplier_list`
  MODIFY `delivery_note_supplier_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบยืมผู้ขายสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_district`
--
ALTER TABLE `tb_district`
  MODIFY `DISTRICT_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8861;
--
-- AUTO_INCREMENT for table `tb_exchange_rate_baht`
--
ALTER TABLE `tb_exchange_rate_baht`
  MODIFY `exchange_rate_baht_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการแลกเปลี่ยนเงินบาท';
--
-- AUTO_INCREMENT for table `tb_finance_credit`
--
ALTER TABLE `tb_finance_credit`
  MODIFY `finance_credit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบจ่ายชำระหนี้';
--
-- AUTO_INCREMENT for table `tb_finance_credit_account`
--
ALTER TABLE `tb_finance_credit_account`
  MODIFY `finance_credit_account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงวิธีจ่ายชำระหนี้';
--
-- AUTO_INCREMENT for table `tb_finance_credit_list`
--
ALTER TABLE `tb_finance_credit_list`
  MODIFY `finance_credit_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบจ่ายชำระหนี้';
--
-- AUTO_INCREMENT for table `tb_finance_credit_pay`
--
ALTER TABLE `tb_finance_credit_pay`
  MODIFY `finance_credit_pay_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการจ่ายค่าใช้จ่ายอื่นๆ';
--
-- AUTO_INCREMENT for table `tb_finance_debit`
--
ALTER TABLE `tb_finance_debit`
  MODIFY `finance_debit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบรับชำระหนี้';
--
-- AUTO_INCREMENT for table `tb_finance_debit_account`
--
ALTER TABLE `tb_finance_debit_account`
  MODIFY `finance_debit_account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงวิธีรับชำระหนี้';
--
-- AUTO_INCREMENT for table `tb_finance_debit_list`
--
ALTER TABLE `tb_finance_debit_list`
  MODIFY `finance_debit_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบรับชำระหนี้';
--
-- AUTO_INCREMENT for table `tb_finance_debit_pay`
--
ALTER TABLE `tb_finance_debit_pay`
  MODIFY `finance_debit_pay_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการจ่ายค่าใช้จ่ายอื่นๆ';
--
-- AUTO_INCREMENT for table `tb_holiday`
--
ALTER TABLE `tb_holiday`
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_invoice_customer`
--
ALTER TABLE `tb_invoice_customer`
  MODIFY `invoice_customer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิง Invoice ผู้ซื้อ';
--
-- AUTO_INCREMENT for table `tb_invoice_customer_list`
--
ALTER TABLE `tb_invoice_customer_list`
  MODIFY `invoice_customer_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการ Invoice ผู้ซื้อ';
--
-- AUTO_INCREMENT for table `tb_invoice_supplier`
--
ALTER TABLE `tb_invoice_supplier`
  MODIFY `invoice_supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิง Invoice ผู้ขาย';
--
-- AUTO_INCREMENT for table `tb_invoice_supplier_list`
--
ALTER TABLE `tb_invoice_supplier_list`
  MODIFY `invoice_supplier_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการ Invoice ผู้ขาย';
--
-- AUTO_INCREMENT for table `tb_job`
--
ALTER TABLE `tb_job`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงงาน';
--
-- AUTO_INCREMENT for table `tb_job_operation`
--
ALTER TABLE `tb_job_operation`
  MODIFY `job_operation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงขั้นตอนการผลิต';
--
-- AUTO_INCREMENT for table `tb_job_operation_process`
--
ALTER TABLE `tb_job_operation_process`
  MODIFY `job_operation_process_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงกระบวนการผลิต';
--
-- AUTO_INCREMENT for table `tb_job_operation_process_tool`
--
ALTER TABLE `tb_job_operation_process_tool`
  MODIFY `job_operation_process_tool_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงเครื่องมือในกระบวนการผลิต';
--
-- AUTO_INCREMENT for table `tb_journal_cash_payment`
--
ALTER TABLE `tb_journal_cash_payment`
  MODIFY `journal_cash_payment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันจ่ายเงิน';
--
-- AUTO_INCREMENT for table `tb_journal_cash_payment_invoice`
--
ALTER TABLE `tb_journal_cash_payment_invoice`
  MODIFY `journal_cash_payment_invoice_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_journal_cash_payment_list`
--
ALTER TABLE `tb_journal_cash_payment_list`
  MODIFY `journal_cash_payment_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันจ่ายเงิน';
--
-- AUTO_INCREMENT for table `tb_journal_cash_receipt`
--
ALTER TABLE `tb_journal_cash_receipt`
  MODIFY `journal_cash_receipt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันรับเงิน';
--
-- AUTO_INCREMENT for table `tb_journal_cash_receipt_list`
--
ALTER TABLE `tb_journal_cash_receipt_list`
  MODIFY `journal_cash_receipt_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันรับเงิน';
--
-- AUTO_INCREMENT for table `tb_journal_general`
--
ALTER TABLE `tb_journal_general`
  MODIFY `journal_general_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันทั่วไป';
--
-- AUTO_INCREMENT for table `tb_journal_general_list`
--
ALTER TABLE `tb_journal_general_list`
  MODIFY `journal_general_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันทั่วไป';
--
-- AUTO_INCREMENT for table `tb_journal_purchase`
--
ALTER TABLE `tb_journal_purchase`
  MODIFY `journal_purchase_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันซื้อ';
--
-- AUTO_INCREMENT for table `tb_journal_purchase_list`
--
ALTER TABLE `tb_journal_purchase_list`
  MODIFY `journal_purchase_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันซื้อ';
--
-- AUTO_INCREMENT for table `tb_journal_purchase_return`
--
ALTER TABLE `tb_journal_purchase_return`
  MODIFY `journal_purchase_return_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันส่งคืนสินค้า';
--
-- AUTO_INCREMENT for table `tb_journal_purchase_return_list`
--
ALTER TABLE `tb_journal_purchase_return_list`
  MODIFY `journal_purchase_return_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันส่งคืนสินค้า';
--
-- AUTO_INCREMENT for table `tb_journal_sale`
--
ALTER TABLE `tb_journal_sale`
  MODIFY `journal_sale_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันขาย';
--
-- AUTO_INCREMENT for table `tb_journal_sale_list`
--
ALTER TABLE `tb_journal_sale_list`
  MODIFY `journal_sale_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันขาย';
--
-- AUTO_INCREMENT for table `tb_journal_sale_return`
--
ALTER TABLE `tb_journal_sale_return`
  MODIFY `journal_sale_return_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสมุดรายวันรับคืนสินค้า';
--
-- AUTO_INCREMENT for table `tb_journal_sale_return_list`
--
ALTER TABLE `tb_journal_sale_return_list`
  MODIFY `journal_sale_return_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการสมุดรายวันรับคืนสินค้า';
--
-- AUTO_INCREMENT for table `tb_license`
--
ALTER TABLE `tb_license`
  MODIFY `license_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tb_main_setting`
--
ALTER TABLE `tb_main_setting`
  MODIFY `main_setting_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงองค์กร';
--
-- AUTO_INCREMENT for table `tb_notification`
--
ALTER TABLE `tb_notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสการแจ้งเตือน';
--
-- AUTO_INCREMENT for table `tb_official_receipt`
--
ALTER TABLE `tb_official_receipt`
  MODIFY `official_receipt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบวางบิล';
--
-- AUTO_INCREMENT for table `tb_official_receipt_list`
--
ALTER TABLE `tb_official_receipt_list`
  MODIFY `official_receipt_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบวางบิล';
--
-- AUTO_INCREMENT for table `tb_other_expense`
--
ALTER TABLE `tb_other_expense`
  MODIFY `other_expense_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงบันทึกค่าใช้จ่ายอื่นๆ';
--
-- AUTO_INCREMENT for table `tb_other_expense_list`
--
ALTER TABLE `tb_other_expense_list`
  MODIFY `other_expense_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการค่าใช้จ่ายอื่นๆ';
--
-- AUTO_INCREMENT for table `tb_other_expense_pay`
--
ALTER TABLE `tb_other_expense_pay`
  MODIFY `other_expense_pay_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการจ่ายค่าใช้จ่ายอื่นๆ';
--
-- AUTO_INCREMENT for table `tb_paper_lock`
--
ALTER TABLE `tb_paper_lock`
  MODIFY `paper_lock_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงงวด', AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tb_paper_type`
--
ALTER TABLE `tb_paper_type`
  MODIFY `paper_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงประเภทเอกสาร', AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tb_product`
--
ALTER TABLE `tb_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงสินค้า';
--
-- AUTO_INCREMENT for table `tb_product_category`
--
ALTER TABLE `tb_product_category`
  MODIFY `product_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงประเภทสินค้า (บริการ,สินค้า)	', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_product_customer`
--
ALTER TABLE `tb_product_customer`
  MODIFY `product_customer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงผู้ซื้อสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_product_group`
--
ALTER TABLE `tb_product_group`
  MODIFY `product_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงกลุ่มสินค้า', AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_product_supplier`
--
ALTER TABLE `tb_product_supplier`
  MODIFY `product_supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงผู้ขายสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_product_type`
--
ALTER TABLE `tb_product_type`
  MODIFY `product_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงประเภทสินค้า', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_product_unit`
--
ALTER TABLE `tb_product_unit`
  MODIFY `product_unit_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงหน่วยนับ', AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_province`
--
ALTER TABLE `tb_province`
  MODIFY `PROVINCE_ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `tb_purchase_order`
--
ALTER TABLE `tb_purchase_order`
  MODIFY `purchase_order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิง PO';
--
-- AUTO_INCREMENT for table `tb_purchase_order_list`
--
ALTER TABLE `tb_purchase_order_list`
  MODIFY `purchase_order_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบสั่งซื้อสินค้า';
--
-- AUTO_INCREMENT for table `tb_purchase_order_list_detail`
--
ALTER TABLE `tb_purchase_order_list_detail`
  MODIFY `purchase_order_list_detail_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายละเอียดรายการใบสั่งซื้อสินค้า';
--
-- AUTO_INCREMENT for table `tb_purchase_request`
--
ALTER TABLE `tb_purchase_request`
  MODIFY `purchase_request_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสใบ PR';
--
-- AUTO_INCREMENT for table `tb_purchase_request_list`
--
ALTER TABLE `tb_purchase_request_list`
  MODIFY `purchase_request_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบขายสินค้า';
--
-- AUTO_INCREMENT for table `tb_quotation`
--
ALTER TABLE `tb_quotation`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบเสนอราคา';
--
-- AUTO_INCREMENT for table `tb_quotation_list`
--
ALTER TABLE `tb_quotation_list`
  MODIFY `quotation_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบเสนอราคา';
--
-- AUTO_INCREMENT for table `tb_regrind_supplier`
--
ALTER TABLE `tb_regrind_supplier`
  MODIFY `regrind_supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบรีกายร์ผู้ซื้อสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_regrind_supplier_list`
--
ALTER TABLE `tb_regrind_supplier_list`
  MODIFY `regrind_supplier_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบรีกายร์ผู้ขายสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_regrind_supplier_receive`
--
ALTER TABLE `tb_regrind_supplier_receive`
  MODIFY `regrind_supplier_receive_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบรับรีกายร์ผู้ซื้อสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_regrind_supplier_receive_list`
--
ALTER TABLE `tb_regrind_supplier_receive_list`
  MODIFY `regrind_supplier_receive_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการใบรับรีกายร์ผู้ขายสิ้นค้า';
--
-- AUTO_INCREMENT for table `tb_request_regrind`
--
ALTER TABLE `tb_request_regrind`
  MODIFY `request_regrind_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสใบร้องการสั่งซื้อสินค้าทดลองรีกายด์';
--
-- AUTO_INCREMENT for table `tb_request_regrind_list`
--
ALTER TABLE `tb_request_regrind_list`
  MODIFY `request_regrind_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบร้องขอสั่งซื้อสินค้าทดลองรีกายด์';
--
-- AUTO_INCREMENT for table `tb_request_special`
--
ALTER TABLE `tb_request_special`
  MODIFY `request_special_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสใบร้องการสั่งซื้อสินค้าทดลองพิเศษ';
--
-- AUTO_INCREMENT for table `tb_request_special_list`
--
ALTER TABLE `tb_request_special_list`
  MODIFY `request_special_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบร้องขอสั่งซื้อสินค้าทดลองพิเศษ';
--
-- AUTO_INCREMENT for table `tb_request_standard`
--
ALTER TABLE `tb_request_standard`
  MODIFY `request_standard_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสใบร้องการสั่งซื้อสินค้าทดลอง';
--
-- AUTO_INCREMENT for table `tb_request_standard_list`
--
ALTER TABLE `tb_request_standard_list`
  MODIFY `request_standard_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบร้องขอสั่งซื้อสินค้าทดลอง';
--
-- AUTO_INCREMENT for table `tb_request_test`
--
ALTER TABLE `tb_request_test`
  MODIFY `request_test_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิง PO';
--
-- AUTO_INCREMENT for table `tb_request_test_list`
--
ALTER TABLE `tb_request_test_list`
  MODIFY `request_test_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสรายการใบสั่งสินค้าทดลอง';
--
-- AUTO_INCREMENT for table `tb_stock_group`
--
ALTER TABLE `tb_stock_group`
  MODIFY `stock_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงกลุ่มคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_stock_issue`
--
ALTER TABLE `tb_stock_issue`
  MODIFY `stock_issue_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบตัดคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_stock_issue_list`
--
ALTER TABLE `tb_stock_issue_list`
  MODIFY `stock_issue_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการตัดคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_stock_move`
--
ALTER TABLE `tb_stock_move`
  MODIFY `stock_move_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงใบย้ายคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_stock_move_list`
--
ALTER TABLE `tb_stock_move_list`
  MODIFY `stock_move_list_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายการขายรถ';
--
-- AUTO_INCREMENT for table `tb_stock_report`
--
ALTER TABLE `tb_stock_report`
  MODIFY `stock_report_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงรายงานคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_stock_type`
--
ALTER TABLE `tb_stock_type`
  MODIFY `stock_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงประเภทคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_stock_type_user`
--
ALTER TABLE `tb_stock_type_user`
  MODIFY `stock_type_user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงผู้มีสิทธิ์เข้าถึงคลังสินค้า';
--
-- AUTO_INCREMENT for table `tb_summit_product`
--
ALTER TABLE `tb_summit_product`
  MODIFY `summit_product_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสผู้ขาย';
--
-- AUTO_INCREMENT for table `tb_supplier_account`
--
ALTER TABLE `tb_supplier_account`
  MODIFY `supplier_account_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงบัญชีผู้ขาย';
--
-- AUTO_INCREMENT for table `tb_supplier_contact`
--
ALTER TABLE `tb_supplier_contact`
  MODIFY `supplier_contact_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงผู้ติดต่อ';
--
-- AUTO_INCREMENT for table `tb_supplier_logistic`
--
ALTER TABLE `tb_supplier_logistic`
  MODIFY `supplier_logistic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสอ้างอิงลักษณะการจัดส่ง';
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `tb_user_status`
--
ALTER TABLE `tb_user_status`
  MODIFY `user_status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
