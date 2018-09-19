<?PHP  
header('Access-Control-Allow-Origin: *');  
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('../../models/JournalSpecialModel.php');
$keyword = $_GET['keyword'];
$journal_id = $_GET['journal_id'];
$journal_model = new JournalSpecialModel;

$journal = $journal_model->getJournalSpecialByKeyword($keyword,$journal_id);

echo json_encode($journal);

?>