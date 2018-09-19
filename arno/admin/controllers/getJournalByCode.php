<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalSpecialModel.php');
$journal_special_code = $_POST['journal_special_code'];

$journal_model = new JournalSpecialModel;

$journal = $journal_model->getJournalSpecialByCode($journal_special_code );

echo json_encode($journal);

?>