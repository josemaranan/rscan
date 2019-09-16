<?
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");

$agentScoreObj6 = new agentScoreCard();

$coachLocation = $_REQUEST[coachLocation];
$coachPosition = $_REQUEST[coachPosition];
//echo $coachLocation.' '.$coachPosition;


$query= "EXEC Rnet.dbo.[rnet_spGetCoachesbyPostionAndLocation] '".$coachPosition."', '".$coachLocation."'";


$result2 = $agentScoreObj6->ExecuteQuery($query);

print '<select name="ddlCoaches"  id="ddlCoaches" onchange="return coachValidations();">';
print '<option value="">Please Select</option>';

	while($row=mssql_fetch_assoc($result2)) 
    {
       print "<option value='".$row[employeeID]."' ";
	   
	if($row['employeeID'] == $_SESSION[eSessCoach]){ print " selected";}
	
	
	print ">$row[firstName] $row[lastName]</option>\n";
	}
	print '</select>';

	$agentScoreObj6->closeConn();
?>