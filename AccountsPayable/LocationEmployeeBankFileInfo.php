<?php
/* Added Newly  AND supApprove IS NOT NULL   */
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);

if($_REQUEST["location"])
{
	$location = $_REQUEST["location"];
}

//getting location description

if(isset($location))
{
	$query = " SELECT [location]
					  ,[description]
			FROM  [ctlLocations] (NOLOCK)                   
			Where location='".$location."'"; 
	
	$locRst=mssql_query($query, $db);
	if ($row=mssql_fetch_array($locRst)) 
	{	
		  $locationDes = $row[description];
	}
}
else
{
	
}
// variables for maintaining view state

if($_REQUEST["fNameVS"])
{
	$firstName = $_REQUEST["fNameVS"];
	$firstName1 = str_replace("\'","'",$_REQUEST["fNameVS"]);
}
if($_REQUEST["lNameVS"])
{
	$lastName = $_REQUEST["lNameVS"];
	$lastName1 = str_replace("\'","'",$_REQUEST["lNameVS"]);
}
if($_REQUEST[empIDVS])
{
	$employeeID = $_REQUEST[empIDVS];
	$empID1=str_replace("\'","'",$_REQUEST[empIDVS]);
}
if($_REQUEST["avayaIDVS"])
{
	$avayaID = $_REQUEST["avayaIDVS"];
	$avayaID1=str_replace("\'","'",$_REQUEST["avayaIDVS"]);
}
if($_REQUEST["empStatVS"])
{
	$employmentStatus = $_REQUEST["empStatVS"];
	$employmentStatus1 = str_replace("\'","'",$_REQUEST["empStatVS"]);
} 
// end of variables for maintaining veiw state

//Filter Setup
unset($Filter);

if($_REQUEST["txtFirstName"])
{
	$firstName = $_REQUEST["txtFirstName"];
	$firstName1 = str_replace("\'","'",$_REQUEST["txtFirstName"]);
}
if($_REQUEST["txtLastName"])
{
	$lastName = $_REQUEST["txtLastName"];
	$lastName1 = str_replace("\'","'",$_REQUEST["txtLastName"]);
}
if($_REQUEST["txtEmployeeID"])
{
	$employeeID = $_REQUEST["txtEmployeeID"];
	$empID1=str_replace("\'","'",$_REQUEST["txtEmployeeID"]);
}
if($_REQUEST["txtAvayaID"])
{
	$avayaID = $_REQUEST["txtAvayaID"];
	$avayaID1=str_replace("\'","'",$_REQUEST["txtAvayaID"]);
}
if($_REQUEST["ddlStatus"])
{
	$employmentStatus = $_REQUEST["ddlStatus"];
	$employmentStatus1 = str_replace("\'","'",$_REQUEST["ddlStatus"]);
} 


if($location) 
{
	$Filter .= "  a.location like '".$location."' ";
}
if($firstName) 
{
	$Filter .= " and a.firstName like '".$firstName."%'  ";
}
if($lastName) 
{
	$Filter .= " and a.lastName like '".$lastName."%' ";
}
if($employeeID) 
{
	$Filter .= " and a.employeeID = '".$employeeID."' ";
}
if($avayaID) 
{
	$Filter .= " and eapp.username = '".$avayaID."' ";
}

if($employmentStatus) 
{
	$Filter .= " and es.employmentStatus = '".$employmentStatus."' ";
}
//default filter to NOT show Terminated Agents 
if($employmentStatus!=2) 
{
	$Filter .= "and (es.description != 'Terminated' OR es.employmentStatus IS NULL)     ";
}

$Filter .= " and a.location in".$Me->Locations;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="expires" content="Mon, 26 Jul 1997 05:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
<title>
<?=$locationDes?>
Employee Bank File Information</title>
<link href="../Include/CSS/main.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
 function onlyNumbers(evt)
        {
            var e = event || evt; // for trans-browser compatibility
            var charCode = e.which || e.keyCode;

            if (charCode > 31 && (charCode < 48 || charCode > 57))
            {
                alert("Please enter only numeric values.");
	            return false;
	        }    

            return true;

        }

 </script>
</head>
<body >
<? include($_SERVER['DOCUMENT_ROOT']."/javascript_menu.php");?>
<div id="LOGO">
<div id="userinfo">	
		<?php include("../Include/class/DisplayUserinfo.php");?>
    </div>
	<div id="menu1"> <a href="CustomLocationConfigurationPages.php?location=<?=$location?>">Back</a> </div>
</div>
<div id="content" style="padding-left:10px;">
  <form method="GET" action="<? echo $_SERVER['PHP_SELF'];?>" name="form_data">
    <table width="770" border="1" align="left" cellpadding="1" bgcolor="#FFFFFF" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
      <tr>
        <td colspan="2" align="left" bgcolor="#CCCCCC" class="ColumnHeader"><?=$locationDes?>
          Employee Bank File Information
		  <input type="hidden" name="location" id="location" value="<?=$location?>" />
		  </td>
      </tr>
      <tr>
        <td width="112" style="text-align: right; ">First Name</td>
        <td width="645" style="text-align: left;"><input type="text" id="txtFirstName" name="txtFirstName" value="<? echo $firstName1; ?>" /></td>
      </tr>
      <tr>
        <td width="112" style="text-align: right; ">Last Name</td>
        <td  width="645" style="text-align: left;"><input type="text" id="txtLastName" name="txtLastName" value="<? echo $lastName1; ?>" /></td>
      </tr>
      <tr>
        <td  width="112" style="text-align: right;">Employee ID</td>
        <td width="645" style="text-align: left;"><input type="text" id="txtEmployeeID" name="txtEmployeeID" value="<? echo $empID1; ?>" onkeypress="return onlyNumbers();"  /></td>
      </tr>
      <tr>
        <td  width="112" style="text-align: right;">Avaya ID</td>
        <td width="645" style="text-align: left;"><input type="text" id="txtAvayaID" name="txtAvayaID" value="<? echo $avayaID1; ?>" /></td>
      </tr>
      <tr>
        <td width="112" style="text-align: right;">Employment Status</td>
        <td width="645" style="text-align: left;"><select name="ddlStatus"  id="ddlStatus" style="width:200px;">
            <option value="">Please Choose</option>
            <?php

		$SQL="	SELECT * FROM  .[ctlEmploymentStatuses] (NOLOCK) ORDER BY description";
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			print "<option value='$row[employmentStatus]'";
			if ($row[employmentStatus] == $employmentStatus1) { 
				print " selected";
			}
			print ">$row[description]</option>\n";
		}
?>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: left;" colspan="2"><input class="WSGInputButton" type="submit" name="submit" value="Search" />
        </td>
      </tr>
    </table>
  </form>
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <table border="1" width="770" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF" class="black_small" style=" border-collapse:collapse;" align="left">
    <tr>
      <td align="center" class="ColumnHeader"><strong>Employee ID</strong></td>
      <td align="center" class="ColumnHeader"><strong>FirstName </strong></td>
      <td align="center" class="ColumnHeader"><strong>LastName </strong></td>
      <td align="center" class="ColumnHeader"><strong>AvayaID</strong></td>
      <td align="center" class="ColumnHeader"><strong>CAI Work ID</strong></td>
      <td align="center" class="ColumnHeader"><strong>Position</strong></td>
      <td align="center" class="ColumnHeader"><strong>Supervisor</strong></td>
      <td align="center" class="ColumnHeader">&nbsp;</td>
    </tr>
    <?
//if(isset($_GET['submit']))
//{

unset($row);
$query="
SELECT 
DISTINCT a.employeeID
		, a.firstName
		, a.lastName
		, b.description
		, a.location
		, CONVERT(CHAR(10), a.productionDate,101)as productionDate
		, ep.phone
		, es.description as employmentStatus
		, a.emailAddress 
		, eapp.username from ctlEmployees as a with(NOLOCK)
			LEFT OUTER JOIN (
				SELECT 
				eh1.* FROM ctlEmployeeCareerHistory eh1 
				JOIN ( SELECT	employeeID, MAX(hireDate) [maxDate] FROM ctlEmployeeCareerHistory  GROUP By employeeID) maxeh 
				ON eh1.employeeID = maxeh.employeeID	
				and eh1.hireDate = maxeh.maxDate) eh 
				ON a.employeeID = eh.employeeID  
				LEFT OUTER JOIN	ctlemploymentStatuses es 
				ON es.employmentStatus = a.employmentStatus
				LEFT OUTER JOIN prmEmployeeApplications eapp 
				ON a.employeeID = eapp.employeeID and eapp.applicationName = 'Avaya Switch'
				LEFT OUTER JOIN	(SELECT employeeID, phoneType, phone FROM ctlEmployeePhones WHERE (phoneType = 'home')) ep 
				ON ep.employeeId = a.employeeId  
				JOIN ctlLocations b ON b.location=a.location 
				LEFT OUTER JOIN ctlEmployeePositions empPos ON empPos.employeeID = a.employeeID
				WHERE ".$Filter." ORDER BY a.lastName,a.firstName,location";
//echo $query;	
				
$rst=mssql_query(str_replace("\'","''",$query), $db);
$num =mssql_num_rows($rst);

$i=0;
while ($row=mssql_fetch_array($rst)) 
{	
	  $employeeID1=$row[employeeID];
	  
	  	  $QryAva= "select username from prmEmployeeApplications WHERE employeeID=".$employeeID1." 
		  and applicationName = 'Avaya Switch'";
		$rstsAVA=mssql_query($QryAva, $db);
		while($Avaya=mssql_fetch_array($rstsAVA))
		{
			$AvayaID .= $Avaya[username].",";
		}
	  
	  $AvayaIDSUB = substr($AvayaID, 0, -1);
	  
	   $QryCAIWorkID= "select username from prmEmployeeApplications WHERE employeeID=".$employeeID1." 
	   and applicationName = 'CAI Work ID'";
		$rstsCAIWorkID=mssql_query($QryCAIWorkID, $db);
		while($CAIWork=mssql_fetch_array($rstsCAIWorkID))
		{
			$CAIWorkID .= $CAIWork[username].",";
			$linkCAIWorkID = $CAIWork[username];
		}
	  
	  $CAIWorkIDSUB = substr($CAIWorkID, 0, -1);
	  
	  $firstName2=$row[firstName];
	  $lastName2=$row[lastName];
	  $description=$row[description]; 
	  $location1=$row[location]; 
	  $productionDate=$row[productionDate];
	  $phone = $row[phone];
	  $empStatus = $row[employmentStatus];
	  $email = $row[emailAddress]; 
	if($i % 2) { //this means if there is a remainder 
	echo "<TR bgcolor=\"#EFEFEF\">"; 
	} else { //if there isn't a remainder we will do the else 
	echo "<TR bgcolor=\"white\">"; 
	} 
	?>
    <td><? echo "$employeeID1"; ?></td>
      <td><? echo "$firstName2 "; ?></td>
      <td><? echo "$lastName2"; ?></td>
      <td><? echo "$AvayaIDSUB"; ?></td>
      <td><? echo "$CAIWorkIDSUB"; ?></td>
      <td><?
				 $pos_query="Select b. position 
							from ctlEmployeePositions a
							join ctlPositions b on a.positionID=b.positionID 
							where employeeID Like '".$row[employeeID]."' and endDate is null ";
				$pos_res=mssql_query($pos_query, $db) or die(mssql_get_last_message());	
				$num_pos= mssql_num_rows($pos_res);
				if($num_pos==0){print " ";}
				else{
				while($pos_row=mssql_fetch_array($pos_res)){
					$pos_string .= $pos_row[position].", ";
				}	
				echo substr($pos_string, 0, -2);
				mssql_free_result($pos_res);
				unset($pos_string);
			} ?>
      </td>
      <td><?
				 $sup_query="select    distinct  e.firstName + ' ' + e.lastName as supName
							from  ctlEmployees (NOLOCK) e
										join
										(
											  SELECT   employeeID, 
														  SupervisorID
											  FROM  ctlEmployeeSupervisors (NOLOCK)
											  WHERE effectiveDate =
													(
														  SELECT      MAX(effectiveDate)
														  FROM  ctlEmployeeSupervisors
														  WHERE employeeID = '".$row[employeeID]."'
													)
											 AND 
											 		supApprove IS NOT NULL 
										) eh
											  on e.employeeID = eh.SupervisorID
							where eh.employeeid = '".$row[employeeID]."'";
				$sup_res=mssql_query($sup_query, $db) or die(mssql_get_last_message());	
				$num_sup= mssql_num_rows($sup_res);
				if($num_sup==0){print " ";}
				else {
					while($sup_row=mssql_fetch_array($sup_res)){
						 $sup_string .= $sup_row[supName].", ";
					}
					$x =substr($sup_string, 0, -2);
					print " $x";
					mssql_free_result($sup_res);
					unset($sup_string);
				}
	  
	  ?>
      </td>
      <td><? if(isset($linkCAIWorkID)){?>
        <a href="LocationEmployeeBankFileInfo_Action.php?employeeID=<?=$employeeID1; ?>&location=<?=$location1?>&fNameVS=<?=urlencode($firstName)?>&lNameVS=<?=urlencode($lastName)?>&empIDVS=<?=$employeeID?>&avayaIDVS=<?=$avayaID?>&empStatVS=<?=urlencode($employmentStatus)?>">Edit</a>
        <? }else{print "Edit";}?></td>
    </tr>
    <tr>
      <?
//finish the while loop
unset($AvayaID);
unset($CAIWorkID);
unset($linkCAIWorkID);
$i++;
} 
mssql_close();
?>
      <td colspan="12"><span class="style1"><br />
        Total =
        <?=$num; ?>
        </span>
        <? //} ?></td>
    </tr>
  </table>
</div>
<?  
 if($_GET[res])
{
	$type=$_GET[res];
}
if($type=="recordAdded")
{
	echo "<script type='text/javascript'>alert('New Employee Bank file information added');</script>";
}
if($type=="recordEdited")
{
	echo "<script type='text/javascript'>alert('Employee Bank file information details Updated');</script>";
}
?>
</body>
</html>
