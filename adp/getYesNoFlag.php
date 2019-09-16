<?php 
//ini_set('display_errors','1');
error_reporting(0);
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");
$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);

$terminationReasonID = $_REQUEST['terRID'];

if(empty($terminationReasonID))
{
		echo '';
		exit;
}	


unset($sqlQuery);
unset($resultSet);
unset($NCNS);
$NCNS = 'N';
$sqlQuery = " 	SELECT NCNS FROM ctlTerminationReasons (NOLOCK) WHERE terminationReasonID = '".$terminationReasonID."'  ";
$resultSet	= mssql_query($sqlQuery, $db);
$NCNS = mssql_result($resultSet,0,0);
echo $NCNS;
exit;
?>

