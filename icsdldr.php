<?
require_once('../../../wp-config.php');
require_once('sem_export.php');
global $wpdb;
(isset($_GET['semIcal']) && !empty($_GET['semIcal']) && ctype_digit($_GET['semIcal']))?$semid = trim(stripslashes(strip_tags($_GET['semIcal']))):$semid=0;
$table_name = $wpdb->prefix . "semanager";
if (!$row = $wpdb->get_row("SELECT * FROM $table_name WHERE sem_id=$semid")) {
	return "<center><span style='border: 1px solid black; padding: 3px; font-weight: bold;'>SEManager: <span style='color: maroon;'>[SEManager_sem_id='$semid']</span> is not a valid event call tag.</span></center>";
}
$exports->eventData = $row;
$fileContent = $exports->genIcs(trim(stripslashes(strip_tags($_GET['semCsOffset']))));
// Set headers
//header("Cache-Control: public");
//header("Content-Description: File Transfer");
//header("Content-Disposition: attachment; filename=SEManager_Event.ics");
//header("Content-Type: text/calendar");
//header("Content-Transfer-Encoding: binary");
//echo $fileContent;
?>