<?php 
//ini_set('display_errors','1');
error_reporting(0);
session_start();
/*
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");
$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);
*/

include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

$terminationReasonID = $_REQUEST['terRID'];


if(empty($terminationReasonID))
{
		echo '';
		exit;
}	


unset($sqlQuery);
unset($resultSet);
$defaultTerminationFlag = 'false';
unset($numRows);

$sqlQuery = " SELECT
				CASE WHEN (terminationCategory='Voluntary') THEN 'true' ELSE 'false' END terminationFlag
			FROM
				Results.dbo.ctlTerminationReasons (NOLOCK)
			WHERE
			terminationReasonID = '".$terminationReasonID."'";

$resultSet	= $RDSObj->execute($sqlQuery); //, $db);
$numRows = $RDSObj->getNumRows($resultSet);
if($numRows>0)
{
	$defaultTerminationFlag = mssql_result($resultSet,0,0);	
}

echo $defaultTerminationFlag;
exit;
?>
