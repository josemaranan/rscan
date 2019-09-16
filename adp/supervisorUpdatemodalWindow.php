<?php
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");
unset($sqlQuery);
unset($resultsSet);
unset($defaultHireDate);

$sqlQuery = " 
			DECLARE @CURDATE DATETIME
			SET @CURDATE = getDate() ";
			
$sqlQuery .= $sqlTemEmployeeCareerHistoryStructure;
				
$sqlQuery .= " EXEC RNet.dbo.[standard_spEmployeeCareerHistory] '%','".$employeeID."','Active',@CURDATE
			SELECT CONVERT(VARCHAR(10),hiredate,101) as hiredate from #tempEmployeeCareerHistory";
//echo $sqlQuery;

//$resultsSet=mssql_query($sqlQuery, $db);
$resultsSet = $employeeeMaintenanceObj->execute($sqlQuery);

$defaultHireDate = mssql_result($resultsSet,0,0);
//echo 'ccccccccccccccccccccccccccccc'.$defaultHireDate;

unset($sqlQuery);
unset($resultsSet);

if($defaultHireDate=='')
{	
	$defaultHireDate = date('m/d/Y');
}
?>

<form method="POST" action="supervisorUpdate_Process.php?employeeID=<?=$employeeID; ?>" name="formModal">
<table width="400" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
    <tr>
      <td colspan="4" bgcolor="#CCCCCC" class="ColumnHeader" style="text-align:center;">SUPERVISOR UPDATE</td>
    </tr>
  <tr>
	<td style="text-align: right;">Supervisor<font color="#FF0000">*</font></td>
      <td style="text-align: left;">

      <select name="ddlSupervisors"  id="ddlSupervisors" style="width:220px;">         
          <?php
	  	 
		 $sqlSUPERV = " DECLARE @CURDATE DATETIME
						SET @CURDATE = getDate()
						IF OBJECT_ID('tempdb.dbo.#tempEmployeeSupervisors') IS NOT NULL        
											DROP TABLE #tempEmployeeSupervisors
											SELECT TOP 0 * 
											INTO #tempEmployeeSupervisors 
											FROM RNet.dbo.tempEmployeeSupervisors WITH (NOLOCK)
											EXEC RNet.dbo.[standard_spEmployeeSupervisors] '%','".$employeeID."',@CURDATE
											SELECT supervisorID FROM #tempEmployeeSupervisors WITH (NOLOCK) WHERE isCurrent = 'Y' ";
		
				
		//$rstSUP=mssql_query($sqlSUPERV, $db);
		$rstSUP = $employeeeMaintenanceObj->execute($sqlSUPERV);
		if($row=mssql_fetch_array($rstSUP)) 
		{	
			$supervisorID = $row[supervisorID];
		}
		 $supervisorID;
		mssql_free_result($rstSUP);
		
		
		$sqlLoc = "SELECT location FROM  [ctlEmployees] WITH (NOLOCK) WHERE
					employeeID = " .$employeeID;
					      
		//$rstLoc=mssql_query($sqlLoc, $db);	
		$rstLoc = $employeeeMaintenanceObj->execute($sqlLoc);

		if ($row=mssql_fetch_array($rstLoc)) 
		{	
		$locationID = $row[location];
		}
		mssql_free_result($rstLoc);
		

$sqlPOS = "SELECT corporateAccess FROM ctlEmployees WITH (NOLOCK) WHERE employeeID = '$employeeID' ";
		
		//$rstPOS=mssql_query($sqlPOS, $db);
		$rstPOS = $employeeeMaintenanceObj->execute($sqlPOS);
		
			if ($row=mssql_fetch_array($rstPOS)) 
			{	
				$businessFunction = $row[corporateAccess];
			}
		mssql_free_result($rstPOS);	




			if($businessFunction == "Y")
			{
			 	$SQLbsfn=" EXEC rnet.dbo.report_spPopulateSupervisors $locationID, 'Y'" ; 
			}
			else
			{
				$SQLbsfn=" EXEC rnet.dbo.report_spPopulateSupervisors $locationID, 'N'" ; 		
			}
			

				
				
		

		//$rst=mssql_query($SQLbsfn, $db);
		$rst = $employeeeMaintenanceObj->execute($SQLbsfn);
		
			print "<option value=''";
			print ">Please Choose</option>\n";
		while ($row=mssql_fetch_array($rst)) {
			print "<option value='$row[employeeID]'";
			if ($row[employeeID] == $supervisorID) 
				{ 
				print " selected";
				}
				print ">".ucwords(strtolower($row[lastName]))." , ".ucwords(strtolower($row[firstName]))." </option>\n";
			//print ">$row[lastName] , $row[firstName] </option>\n";
		}
		mssql_free_result($rst);
	  // add all the corporate Possible supervisors
	  /*$peter_query= "SELECT DISTINCT e.firstName,e.lastName, e.employeeID 
                FROM ctlEmployees e 
                JOIN ctlEmployeePositions ep on e.employeeID = ep.employeeID 
                        and ep.endDate IS NULL
                JOIN ctlPositions p on ep.positionID = p.positionID 
                WHERE businessFunction = 'Corporate' 
                and
                (position like '%manager%'
                or
                position like '%director%'
                or
                position like '%vp%'
                or
                position like '%president%'
                or
                position like '%cio%'
                or
                position like '%cio%'
                or
                position like '%ceo%'
                or
                position like '%coo%')
                and e.location in ('801','800')
                and  effectiveDate = (SELECT MAX(effectiveDate) FROM ctlEmployeePositions WHERE employeeID = e.employeeID) AND 

                ISNULL(p.positionID, '') != '' ORDER BY e.lastName, e.firstName ";
				
				$rst_peter=mssql_query($peter_query, $db);

		while ($row_peter=mssql_fetch_array($rst_peter)) {
			print "<option value='$row_peter[employeeID]'";
			if ($row_peter[employeeID] == $supervisorID) 
				{ 
				print " selected";
				}
			print ">$row_peter[lastName] , $row_peter[firstName] </option>\n";
		}*/
	  ?>
      
      </select>
	  
  </td>
	  
  </tr> 
   <tr>
    <td style="text-align: right;">Effective Date</td>	
    <td style="text-align:left;">
	<input name="effectiveDate" type="text" id="effectiveDateModal" readonly="readonly" style="width: 75px" value="<? echo $defaultHireDate;?>" />
<img id="imgTrainingModaldate" alt="Choose Training Start Date" onclick=
"javascript:displayCalendar(document.getElementById('effectiveDateModal'),'mm/dd/yyyy',document.getElementById('imgTrainingModaldate'))" 
src="https://<?=$_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px; 
border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" /> </td>

	
	
	
	</td>
  </tr> 
  <tr>
    <td colspan="2" align="center">
    <input type="hidden" name="hdnReturnPath" value="positionUpdate.php" />
    <input type="hidden" name="returnPositionID" value="<?php echo $_REQUEST['returnPositionID'];?>" />
    <input type="hidden" name="returneffectiveDate" value="<?php echo $_REQUEST['returneffectiveDate'];?>" />
    
    <input name="btnUpdate" type="submit" value="Submit" onclick="return checkcurrentDateValidations(); return false;" />
	<input name="Cancel" id="Cancel" type="button" value="Cancel" onclick="closeWindow()"/>
	
	</td>
  </tr>
  </table>
  </form>