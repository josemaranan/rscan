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
$employeeID = $_REQUEST['empID'];

if(empty($terminationReasonID))
{
		echo '';
		exit;
}	


unset($sqlQuery);
unset($resultSet);

$sqlQuery = " 	
				IF OBJECT_ID('tempdb.dbo.#tmpEmployeeClient') IS NOT NULL
				DROP TABLE #tmpEmployeeClient
				
				CREATE TABLE #tmpEmployeeClient
				(
					clientName	varchar(50) NULL,
					skillScript	varchar(50) NULL
				)
				
				DECLARE @clientName varchar(50)
				DECLARE @employeeID int
				DECLARE @terminationReasonID varchar(10)
				
				SET @employeeID = ".$employeeID."
				SET @terminationReasonID = '".$terminationReasonID."'
				
				
				
				-- Retrieve last day's skills/scripts
				-- or new hire class client
				
				INSERT INTO #tmpEmployeeClient
				SELECT
					a.clientName,
					b.scriptName
				FROM
					results.dbo.ctlDatabases (NOLOCK) a
				JOIN
					results.dbo.ctlScripts (NOLOCK) b
						ON a.databaseName = b.databaseName
				JOIN
					results.dbo.ctlEmployees (NOLOCK) c
						ON c.lastDayScriptName = b.scriptName
				WHERE
					c.employeeID = @employeeID
				
				INSERT INTO #tmpEmployeeClient
				SELECT
					a.clientName,
					b.skill
				FROM
					results.dbo.ctlDatabases (NOLOCK) a
				JOIN
					results.dbo.ctlSkills (NOLOCK) b
						ON a.databaseName = b.databaseName
				JOIN
					results.dbo.ctlEmployees (NOLOCK) c
						ON c.lastDaySkill = b.skill
				WHERE
					c.employeeID = @employeeID
				
				INSERT INTO #tmpEmployeeClient
				SELECT
					newHireClient,
					NULL
				FROM
					results.dbo.ctlEmployees
				WHERE
					employeeID = @employeeID
				
				 -- need to make sure only 1 client is identified:
				-- try for OB script first
					SELECT
						@clientName = clientName
					FROM
						#tmpEmployeeClient
					WHERE
						ISNULL(skillScript,'-1') != '-1'
				-- then try for IB skill
				IF @clientName IS NULL
					BEGIN
						SELECT
							@clientName = clientName
						FROM
							#tmpEmployeeClient
						WHERE
							SkillScript IS NOT NULL
					END
				-- then try new hire class client
				IF @clientName IS NULL
					BEGIN
						SELECT
							@clientName = clientName
						FROM
							#tmpEmployeeClient
						WHERE
							SkillScript IS NULL
					END
				--print @clientName
				
				SELECT
					CASE clientVolumeReductionAction 
						WHEN 'Automatically No' THEN 'N'
						WHEN 'Automatically Yes' THEN 'Y'
						WHEN 'Y/N Option' THEN 'Prompt Y/N'
					ELSE
						'Prompt Y/N'
					END [dueToClientReduction],
					clientName,
					clientCategory
				FROM
					results.dbo.ctlClientTerminationReasons (NOLOCK) a
				JOIN
					results.dbo.ctlEmployees (NOLOCK) b
						ON a.clientName = @clientName
						AND b.employeeID = @employeeID
				WHERE	a.terminationReasonID = @terminationReasonID  ";
				


$resultSet	= $RDSObj->execute($sqlQuery); //, $db);


$resString = '';


if(!empty($resultSet))
{
	
	while($rRows = mssql_fetch_assoc($resultSet))
	{
		$desiredResult = trim($rRows['dueToClientReduction']);
		$desiredClient = trim($rRows['clientName']);
		$desiredCategory = trim($rRows['clientCategory']);
	}
}

switch($desiredResult)
{
	case 'Y':
	 $resString = '<select name="ddldisabledvolumeReduction" id="ddldisabledvolumeReduction" disabled="disabled">
	  <option value="Y">Y</option></select><input type="hidden" name="ddlvolumeReduction" id="ddlvolumeReduction" value="Y" />
	  <input type="hidden" name="txtClientName" id="txtClientName" value="'.$desiredClient.'" /><input type="hidden" name="txtCategory" id="txtCategory" value="'.$desiredCategory.'" />';
	break;
	
	case 'N':
	$resString = '<select name="ddldisabledvolumeReduction" id="ddldisabledvolumeReduction" disabled="disabled">
	  <option value="N">N</option></select><input type="hidden" name="ddlvolumeReduction" id="ddlvolumeReduction" value="N" />
	  <input type="hidden" name="txtClientName" id="txtClientName" value="'.$desiredClient.'" /><input type="hidden" name="txtCategory" id="txtCategory" value="'.$desiredCategory.'" />';
	break;
	
	case 'Prompt Y/N':
	$resString = '<select name="ddlvolumeReduction" id="ddlvolumeReduction" >
			<option value="Y">Y</option><option value="N">N</option></select>
			<input type="hidden" name="txtClientName" id="txtClientName" value="'.$desiredClient.'" /><input type="hidden" name="txtCategory" id="txtCategory" value="'.$desiredCategory.'" />';
	break;
	
	default:
	$resString = '';
	break;
}


echo $resString;
exit;
?>

