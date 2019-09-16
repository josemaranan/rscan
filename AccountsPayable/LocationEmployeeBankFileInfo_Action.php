<?php 
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);

if($_REQUEST["employeeID"])
{
	$employeeID=$_REQUEST["employeeID"];
}

// variables for maintaining view state
if($_REQUEST["fNameVS"])
{
	$fNameVS = $_REQUEST["fNameVS"];
}
if($_REQUEST["lNameVS"])
{
	$lNameVS = $_REQUEST["lNameVS"];
}
if($_REQUEST["empIDVS"])
{
	$empIDVS = $_REQUEST["empIDVS"];
}
if($_REQUEST["avayaIDVS"])
{
	$avayaIDVS = $_REQUEST["avayaIDVS"];
}
if($_REQUEST["empStatVS"])
{
	$empStatVS = $_REQUEST["empStatVS"];
}
// end of variables for maintaining veiw state

if($_REQUEST["location"])
{
	$location = $_REQUEST["location"];

	$query = " SELECT [location]
					  ,[description]
			FROM  [ctlLocations] (NOLOCK)                   
			Where location='".$location."'"; 
	
	$locRst=mssql_query($query, $db);
	if ($row=mssql_fetch_array($locRst)) 
	{	
		  $locationDes = $row[description];
	}
	
	$currencyQuery = "select * from ctlLocationCurrencies (NOLOCK) where location='".$location."'";
	$currencyRst = mssql_query($currencyQuery,$db);
	if($rowCurrency=mssql_fetch_array($currencyRst)) 
	{	
		  $currency = $rowCurrency[currencyAbbreviation];
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="expires" content="Mon, 26 Jul 1997 05:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
<title>Employee Bank File Information</title>
<link href="../Include/CSS/main.css" rel="stylesheet" type="text/css" />
<link href="../Include/CSS/dhtmlgoodies_calendar.css?random=20051112" media="screen" rel="stylesheet" type="text/css" />
<script language="javascript" src='../Include/javascript/dhtmlgoodies_calendar.js?random=20060118'
        type="text/javascript"></script>
<script src="ajax.js" type="text/javascript"></script>
<script language="JavaScript" src="../Include/javascript/validation.js" type="text/javascript"></script>
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
		
	function Validate()
	{
		var ErrorMessage = "";
		if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form1.txtMonthlyRate.value))
		{
			 alert("Invalid Monthly Rate. Please enter decimal suffixed with 1 or 2 digits.")
			 document.form1.txtMonthlyRate.focus()
			 return false
		}
		else if(!/^([-+]?\d{0,9}(\,\d{9})*|(\d+))(\.\d{1,2})?$/.test(document.form1.txtDailyRate.value))
		{
			 alert("Invalid Daily Rate. Please enter decimal suffixed with 1 or 2 digits.")
			 document.form1.txtDailyRate.focus()
			 return false
		}
		
	}


 </script>
</head>
<body >
<? include($_SERVER['DOCUMENT_ROOT']."/javascript_menu.php");?>
<div id="LOGO">
<div id="userinfo">	
		<?php include("../Include/class/DisplayUserinfo.php");?>
    </div>
	<div id="menu1"> <a href="LocationEmployeeBankFileInfo.php?fNameVS=<?=urlencode($fNameVS)?>&lNameVS=<?=urlencode($lNameVS)?>&empIDVS=<?=$empIDVS?>&avayaIDVS=<?=$avayaIDVS?>&empStatVS=<?=urlencode($empStatVS)?>&location=<?=$location?>">Back</a> </div>
</div>
<div id="content" style="padding-left:10px;">
  <?
if($employeeID!=="")
{
?>
  <table width="770" border="1" align="left" cellpadding="1" bgcolor="#FFFFFF" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
    <?
	$query="SELECT [employeeID]
      ,[firstName]
      ,[lastName]
 	   FROM  [ctlEmployees] (NOLOCK) where employeeID=".$employeeID; 
	   $rst=mssql_query($query, $db);
	   
	   $emp_array=mssql_fetch_array($rst);
		
?>
    <tr>
      <td colspan="6" class="ColumnHeader">Employee Information</td>
    </tr>
    <tr>
      <td style="text-align: right;"><strong>FirstName: </strong></td>
      <td style="text-align: left;"><?=$emp_array[firstName]?>
      </td>
      <td style="text-align: right;"><strong>Employee ID: </strong></td>
      <td style="text-align: left;"><?=$emp_array[employeeID]?></td>
      <?
 	  $QryCAIWorkID= "select username from ctlemployeeapplications (NOLOCK) WHERE employeeID=".$employeeID." 
	   and applicationName = 'CAI Work ID'";
		$rstsCAIWorkID=mssql_query($QryCAIWorkID, $db);
		while($CAIWork=mssql_fetch_array($rstsCAIWorkID))
		{
			$CAIWorkID = $CAIWork[username];
		}
?>
      <td style="text-align: right;"><strong>CAI Work ID: </strong></td>
      <td style="text-align: left;"><?=$CAIWorkID?></td>
    </tr>
    <tr>
      <td style="text-align: right;"><strong>Last Name: </strong></td>
      <td style="text-align: left;"><?=$emp_array[lastName]?>
      </td>
      <?
  	$QryAva= "select username from ctlemployeeapplications (NOLOCK) WHERE employeeID=".$employeeID." 
		  and applicationName = 'Avaya Switch'";
		$rstsAVA=mssql_query($QryAva, $db);
		while($Avaya=mssql_fetch_array($rstsAVA))
		{
			$AvayaID .= $Avaya[username].",";
		}
	  
	  $AvayaIDSUB = substr($AvayaID, 0, -1);
?>
      <td style="text-align: right;"><strong>Avaya ID: </strong></td>
      <td style="text-align: left;"><?=$AvayaIDSUB ?></td>
      <?
		$pos_query="Select b. position 
					from ctlEmployeePositions (NOLOCK) a
					join ctlPositions (NOLOCK) b on a.positionID=b.positionID 
					where employeeID Like '".$employeeID."' and endDate is null ";
		$pos_res=mssql_query($pos_query, $db) or die(mssql_get_last_message());	
		$num_pos= mssql_num_rows($pos_res);
		if($num_pos==0){print " ";}
		else{
			while($pos_row=mssql_fetch_array($pos_res)){
				$pos_string .= $pos_row[position].", ";
			}
		}	
?>
      <td style="text-align: right;"><strong>Position: </strong></td>
      <td style="text-align: left;"><?=$pos_string?></td>
    </tr>
    <tr>
      <td style="text-align: right;"><strong>Status: </strong></td>
      <td style="text-align: left;"><?php
		$sqlJOBST = "SELECT employmentStatus FROM ctlEmployeeStatuses (NOLOCK) WHERE 
		employeeID = '".$employeeID."' AND effectiveDate = (SELECT MAX(effectiveDate) FROM ctlEmployeeStatuses (NOLOCK) WHERE 
		employeeID = '".$employeeID."') ORDER BY effectiveDate";
		$rstJOBST=mssql_query($sqlJOBST, $db);
		if ($row=mssql_fetch_array($rstJOBST)) 
		{	
			$empStatusID = $row[employmentStatus];
		}
		$SQLJOBSTATUS = "SELECT description FROM ctlEmploymentStatuses (NOLOCK) where employmentStatus = '$empStatusID'";
		$RSTJOBSTATUS=mssql_query($SQLJOBSTATUS , $db);
		if($row=mssql_fetch_array($RSTJOBSTATUS)) {
			echo $row[description];
		}
?>
      </td>
    </tr>
  </table>
  <?
}
?>
  <br/>
  <br />
  <br />
  <br />
  <br />
  <br />
  <?
//$birthDay = date('m/d/Y', strtotime(now)); 

//$CAIWorkID = "PH0012";

	$fillQuery = "SELECT * from ctlEmployeeBankFile_SilverCity (NOLOCK) WHERE employeeID = '".$CAIWorkID."'";
	$fillRst=mssql_query(str_replace("\'","''",$fillQuery), $db);
	if($fillRow=mssql_fetch_array($fillRst)) 
	{
		$status = $fillRow["employmentStatus"];
		$department = $fillRow["departmentCode"];
		$category 	= $fillRow["category"];
		$monthlyRate = $fillRow["monthlyRate"];
		$dailyRate = $fillRow["dailyRate"];
		$sSSNo = $fillRow["SSSNo"];
		$philHealthNo = $fillRow["philHealthNo"];
		$pagIbigNo = $fillRow["pagIbigNo"];
		$taxIDNo = $fillRow["taxIDNo"];
		if(isset($fillRow["birthday"]))
		{
			$birthDay = date('m/d/Y', strtotime($fillRow["birthday"]));
		}	
		$civilStatus = $fillRow["civilStatus"];
		$gender = $fillRow["gender"];
		$taxStatus = $fillRow["taxStatus"];
		$shiftCode = $fillRow["shiftCode"];
		
	}
?>
  <form method="POST" action="LocationEmployeeBankFileInfo_Action_process.php" name="form1">
    <table width="770" border="1" align="left" cellpadding="1" bgcolor="#FFFFFF" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
      <tr>
        <td colspan="2" class="ColumnHeader">Enter
          <?=$locationDes?>
          Employee Bank File Information
          <input type="hidden" name="hdnEmployeeID" id="hdnEmployeeID" value="<?=$employeeID?>"/>
          <input type = "hidden" name = "hdnFNameVS" id="hdnFNameVS" value="<?=$fNameVS?>"/>
          <input type = "hidden" name = "hdnLNameVS" id="hdnLNameVS" value="<?=$lNameVS?>"/>
          <input type = "hidden" name = "hdnEmpIDVS" id="hdnEmpIDVS" value="<?=$empIDVS?>"/>
          <input type = "hidden" name = "hdnAvayaIDVS" id="hdnAvayaIDVS" value="<?=$avayaIDVS?>"/>
          <input type = "hidden" name = "hdnEmpStatVS" id="hdnEmpStatVS" value="<?=$empStatVS?>"/>
          <input type = "hidden" name = "hdnLocation" id="hdnLocation" value="<?=$location?>"/>
        </td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Status: </strong></td>
        <td style="text-align: left;"><select name="ddlStatus"  id="ddlStatus" style="width:200px;">
            <option value="">Please Choose</option>
            <option value="0" <? if($status == "0"){ print ' selected';}?>>Regular</option>
            <option value="1" <? if($status == "1"){ print ' selected';}?>>Probationary</option>
            <option value="2" <? if($status == "2"){ print ' selected';}?>>Contractual</option>
            <option value="3" <? if($status == "3"){ print ' selected';}?>>Finished Contract</option>
            <option value="4" <? if($status == "4"){ print ' selected';}?>>Resigned</option>
            <option value="5" <? if($status == "5"){ print ' selected';}?>>Temporary</option>
            <option value="6" <? if($status == "6"){ print ' selected';}?>>Terminated</option>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Department: </strong></td>
        <td style="text-align: left;"><select name="ddlDepartments"  id="ddlDepartments" style="width:200px;" onchange="htmlData('populateShiftCode.php', 'departmentCode='+this.value, 'SHIFTCODE')">
            <option value="">Please Choose</option>
            <?php
			$SQL="	SELECT * FROM  [ctlEmployeeBankFile_departments] (NOLOCK) ORDER BY deptDescription";
			$rst=mssql_query($SQL, $db);		
			
			while ($row=mssql_fetch_array($rst)) {
				print "<option value='$row[deptCode]'";
				if ($row[deptCode] == $department) { 
					print " selected";
				}
				print ">$row[deptDescription]</option>\n";
			}
?>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Category: </strong></td>
        <td style="text-align: left;"><select name="ddlCategories"  id="ddlCategories" style="width:200px;">
            <option value="">Please Choose</option>
            <option value="0" <? if($category == "0"){ print ' selected';}?>>Monthly</option>
            <option value="1" <? if($category == "1"){ print ' selected';}?>>Daily</option>
            <option value="2" <? if($category == "2"){ print ' selected';}?>>Piece-rate</option>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Monthly Rate: (<?=$currency?>) </strong></td>
        <td style="text-align: left;"><input type="text" id="txtMonthlyRate" name="txtMonthlyRate" value="<?=$monthlyRate?>"/></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Daily Rate: (<?=$currency?>) </strong></td>
        <td style="text-align: left;"><input type="text" id="txtDailyRate" name="txtDailyRate" value="<?=$dailyRate?>" /></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>SSS No. </strong></td>
        <td style="text-align: left;"><input type="text" id="txtSSSNo" name="txtSSSNo" value="<?=$sSSNo?>" maxlength="50"/></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>PhilHealth No. </strong></td>
        <td style="text-align: left;"><input type="text" id="txtPhilHealthNo" name="txtPhilHealthNo" value="<?=$philHealthNo?>" maxlength="20"/></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Pag Ibig No. </strong></td>
        <td style="text-align: left;"><input type="text" id="txtPagIbigNo" name="txtPagIbigNo" value="<?=$pagIbigNo?>" maxlength="20"/></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Tax ID No. </strong></td>
        <td style="text-align: left;"><input type="text" id="txtTaxIDNo" name="txtTaxIDNo" value="<?=$taxIDNo?>" maxlength="9"/></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>BirthDay:</strong></td>
        <td style="text-align: left"><input name="txtBirthDay" type="text" id="txtBirthDay" readonly="readonly" style="width: 75px" value="<?=$birthDay?>" />
          <img id="imgDate" alt="Choose BirthDay" onclick="javascript:displayCalendar(document.getElementById('txtBirthDay'),'mm/dd/yyyy',document.getElementById('imgDate'))"
src="../Include/images/calendar.gif" style="border-top-width: 0px;border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" /></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Civil Status: </strong></td>
        <td style="text-align: left;"><select name="ddlCivilStatus"  id="ddlCivilStatus" style="width:200px;">
            <option value="">Please Choose</option>
            <option value="0" <? if($civilStatus == "0"){ print ' selected';}?>>Single</option>
            <option value="1" <? if($civilStatus == "1"){ print ' selected';}?>>Married</option>
            <option value="2" <? if($civilStatus == "2"){ print ' selected';}?>>Widow/er</option>
            <option value="3" <? if($civilStatus == "3"){ print ' selected';}?>>Separated</option>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Gender: </strong></td>
        <td style="text-align: left;"><select name="ddlGender"  id="ddlGender" style="width:200px;">
            <option value="">Please Choose</option>
            <option value="0" <? if($gender == "0"){ print ' selected';}?>>Male</option>
            <option value="1" <? if($gender == "1"){ print ' selected';}?>>Female</option>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Tax Status: </strong></td>
        <td style="text-align: left;"><select name="ddlTaxStatus"  id="ddlTaxStatus" style="width:200px;">
            <option value="">Please Choose</option>
            <option value="S" <? if($taxStatus == "S"){ print ' selected';}?>>S</option>
            <option value="S1" <? if($taxStatus == "S1"){ print ' selected';}?>>S1</option>
            <option value="S2" <? if($taxStatus == "S2"){ print ' selected';}?>>S2</option>
            <option value="S3" <? if($taxStatus == "S3"){ print ' selected';}?>>S3</option>
            <option value="S4" <? if($taxStatus == "S4"){ print ' selected';}?>>S4</option>
            <option value="ME" <? if($taxStatus == "ME"){ print ' selected';}?>>ME</option>
            <option value="ME1" <? if($taxStatus == "ME1"){ print ' selected';}?>>ME1</option>
            <option value="ME2" <? if($taxStatus == "ME2"){ print ' selected';}?>>ME2</option>
            <option value="ME3" <? if($taxStatus == "ME3"){ print ' selected';}?>>ME3</option>
            <option value="ME4" <? if($taxStatus == "ME4"){ print ' selected';}?>>ME4</option>
            <option value="Z" <? if($taxStatus == "Z"){ print ' selected';}?>>Z</option>
          </select></td>
      </tr>
      <tr>
        <td style="text-align: right;"><strong>Shift Code: </strong></td>
        <td style="text-align: left;">
		<? if(!empty($ddlDepartments)){echo "<script type='text/javascript'>htmlData('populateShiftCode.php', 'departmentCode=$ddlDepartments', 'SHIFTCODE');</script>";}?>
		<div id="SHIFTCODE">
			<input type="text" id="txtShiftCode" name="txtShiftCode" value="<?=$shiftCode?>" readonly="readonly"/>
		</div>	
		</td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: left"><input type="submit" name="button" id="button" value="Save" onclick="return Validate()"/>
        </td>
      </tr>
    </table>
  </form>
</div>
</body>
</html>
