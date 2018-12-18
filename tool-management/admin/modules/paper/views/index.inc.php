<?php

require_once('../models/PaperModel.php');
require_once('../models/PaperTypeModel.php');
require_once('../models/JournalModel.php');


$path = "modules/paper/views/";

$paper_model = new PaperModel;
$paper_type_model = new PaperTypeModel;
$journal_model = new JournalModel; 


if(!isset($_GET['action'])){     
        $journals = $journal_model->getJournalBy();
        $paper_types = $paper_type_model->getPaperTypeBy();
        $papers = $paper_model->getPaperBy(); 
        require_once($path.'view.inc.php');
/**/
}else if ($_GET['action'] == 'edit'){
        if(isset($_POST['paper_code'])){ 

                $data = [];
                $data['paper_type_id'] = $_POST['paper_type_id']; 
                $data['paper_code'] = $_POST['paper_code']; 
                $data['paper_name_th'] = $_POST['paper_name_th']; 
                $data['paper_name_en'] = $_POST['paper_name_en']; 
                $data['journal_id'] = $_POST['journal_id']; 
                $data['journal_description'] = $_POST['journal_description']; 
                $data['paper_lock'] = $_POST['paper_lock'];  

                $paper_model->updatePaperByID($_POST['paper_id'],$data); 
               
        }
        ?>
        <script>window.location="index.php?app=paper&action=view"</script>
        <?php 
    
}else{
        $journals = $journal_model->getJournalBy();
        $paper_types = $paper_type_model->getPaperTypeBy();
        $papers = $paper_model->getPaperBy(); 
        require_once($path.'view.inc.php');

}





?>