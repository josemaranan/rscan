<?php
session_start();
class ADPEmployeeClass extends ADPPayroll {
	
	public $adpLeftMenuVar;
	public $bottomLinksDashBoardVar;
	public $usLocationArray;
	public $employmentStatus;
	public $usReportingLocationArray;
	public $usPayGroupLocationArray;
	public $convertArray;
	public $employData;
	public $employADPData;
	public $topLevelEmployeeInfo;
	public $employeeFulltimeParttimeInfo;
	public $employeeTimingTypes;
	public $empHistoricalPartTimeFullTimeInformation;
	public $allLocationArray;
	public $adpClientCodes;
	public $accessPositions = '306,4,315,105,17,313,42,320,163,351,352,427,243,441,371';
	public $locationsWithOutCorporate;
	public $returnN_NumberofPayperiods;
	public $isFirstRecoredInPayrollRates;
	public $usADPLocationArray;
	public $supArray;
	public $empHistoricalSupervisorInformation;
	public $employeeSupervisorInfo;
	
	
	
	public $bottomLinksArray = array(
									 'empDates'=>'Modify Employment Dates',
									 'empSalary'=>'Modify Salary / Wage',
									 'empFullTime'=>'Modify Full-time/Part-time',
									 'empPayrollData'=>'Modify ADP Data',
									 'empPosition'=>'Modify Position',
									 'empClient'=>'Modify Employee’s Clients',
									 'empAddress'=>'Modify Contact Information',
									 'empPersonalInfo'=>'Modify Personal Information',
									 'empEmrContact'=>'Modify Emergency Contact',
									 'empViewPersonalInfo'=>'Modify Personal Information',
									 'empViewSalary'=>'View Salary Wage',
									 'empViewPayrollData'=>'View Payroll Data',
									 'empRemoval'=>'Remove no-show',
									 'empModifyContractor'=>'Modify Contractor',
									 'empSupervisor'=>'Modify Supervisor');
	
	public $bottomLinksHighlighted = array();
	public $setMissingEmployeeData;
	
	function setLeftMenu($accessLimit)
	{
		if($accessLimit=='fullAccess')
		{
			$this->adpLeftMenuVar =  '<div id = "adpLeftMenu">
		   <div class="adpLeftMenuBigFont">HR Dashboard</div>
		   <div class="adpLeftMenuSmallFont" onClick="setPageVariables(\'hr\', \'empManagement\'); return false;">Human Resource Access &nbsp;>></div>';
		   $this->adpLeftMenuVar .= '<div class="adpLeftMenuSmallFont" onClick="setPageVariables(\'emp\', \'empManagement\'); return false;">Employee Self Service &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>></div>';
		   
		   $this->adpLeftMenuVar .= '</div>';
			
		}
		else if($accessLimit=='partialAccess')
		{
			$employeeID = $this->UserDetails->User;
			
			$this->adpLeftMenuVar =  '<div id = "adpLeftMenu">
		   <div class="adpLeftMenuBigFont">US Employee Payroll Dashboard</div>';
		   $this->adpLeftMenuVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empDetails\'); return false;">Employee Self Service &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>></div>
			</div>';
		}
		   /*
		   if(empty($employeeID))
		   {
		   	$this->adpLeftMenuVar .= '<div class="adpLeftMenuSmallFont" onClick="setPageVariables(\'us\', \'empManagement\'); return false;">Employee Self Service &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>></div>
			</div>';
		   }
		   else
		   {
				$this->adpLeftMenuVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'us\', \'empDetails\'); return false;">Employee Self Service &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>></div>
			</div>';   
		   }*/
		   
		   
	}
	
	function getLeftMenu()
	{
			echo $this->adpLeftMenuVar;
	}
	
	function setbottomLinksDashBoard($employeeID, $adpMode, $accessLimit)
	{
		//echo '<pre>';
		//print_r($this->bottomLinksHighlighted);	
		
		$titleoftheDiv = '';
		
		if($accessLimit=='fullAccess' && $adpMode=='hr')
		{
			$titleoftheDiv = '5rows';	
		}
		else if($accessLimit=='fullAccess' && $adpMode=='emp')
		{
			$titleoftheDiv = '3rows';
		}
		else
		{
			$titleoftheDiv = '5rows'; 	
		}
		
		$this->bottomLinksDashBoardVar = '<div id = "bottomLinksDashBoard" title="'.$titleoftheDiv.'">
           <div class="adpLeftMenuBigFont">Launch</div>';
		  
		  if($accessLimit=='fullAccess')
		  {
			 
			   if($adpMode=='hr') // If HR
			   {
					if(in_array('empDates',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '		   
						<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empDates\'); return false;">'.$this->bottomLinksArray['empDates'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '		   
						<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empDates\'); return false;">'.$this->bottomLinksArray['empDates'].'</div>';	   
					}
					
					if(in_array('empSalary',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '  <div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empSalary\'); return false;">'.$this->bottomLinksArray['empSalary'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '  <div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empSalary\'); return false;">'.$this->bottomLinksArray['empSalary'].'</div>';
					}
					
					
					if(in_array('empFullTime',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .=  '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empFullTime\'); return false;">'.$this->bottomLinksArray['empFullTime'].'</div>';
					}
					else
					{					   
						$this->bottomLinksDashBoardVar .=  '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empFullTime\'); return false;">'.$this->bottomLinksArray['empFullTime'].'</div>';
					}
					
					if(in_array('empPayrollData',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .=  '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empPayrollData\'); return false;">'.$this->bottomLinksArray['empPayrollData'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .=  '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empPayrollData\'); return false;">'.$this->bottomLinksArray['empPayrollData'].'</div>';
					}
					
					if(in_array('empPosition',$this->bottomLinksHighlighted))
					{
					
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empPosition\'); return false;">'.$this->bottomLinksArray['empPosition'].'</div>';
					}
					else
					{
					
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empPosition\'); return false;">'.$this->bottomLinksArray['empPosition'].'</div>';
					}
					////////////Work Item #38193
					if(in_array('empSupervisor',$this->bottomLinksHighlighted))
					{
					
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empSupervisor\'); return false;">'.$this->bottomLinksArray['empSupervisor'].'</div>';
					}
					else
					{
					
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empSupervisor\'); return false;">'.$this->bottomLinksArray['empSupervisor'].'</div>';
					}
					/////////////////Work Item #38193
					if(in_array('empClient',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empClient\'); return false;">'.$this->bottomLinksArray['empClient'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empClient\'); return false;">'.$this->bottomLinksArray['empClient'].'</div>';
					}
					
					/*if(in_array('empUploadPhoto',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empUploadPhoto\'); return false;">'.$this->bottomLinksArray['empUploadPhoto'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empUploadPhoto\'); return false;">'.$this->bottomLinksArray['empUploadPhoto'].'</div>';
					}
					*/
					
					/////////////START 2013/03/26	   
					if(in_array('empRemoval',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empRemoval\'); return false;">'.$this->bottomLinksArray['empRemoval'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empRemoval\'); return false;">'.$this->bottomLinksArray['empRemoval'].'</div>';
					}
					
					
					/////////////END 2013/03/26	

					/////////////START 2013/11/20   //empModifyContractor
					/* 
					if(in_array('empModifyContractor',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empModifyContractor\'); return false;">'.$this->bottomLinksArray['empModifyContractor'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'hr\', \'empModifyContractor\'); return false;">'.$this->bottomLinksArray['empModifyContractor'].'</div>';
					}
					*/
					
					/////////////END 2013/11/20
				   
			   }
			   else
			   {
					if(in_array('empAddress',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empAddress\'); return false;">'.$this->bottomLinksArray['empAddress'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empAddress\'); return false;">'.$this->bottomLinksArray['empAddress'].'</div>';
					}
					
					if(in_array('empPersonalInfo',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empPersonalInfo\'); return false;">'.$this->bottomLinksArray['empPersonalInfo'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empPersonalInfo\'); return false;">'.$this->bottomLinksArray['empPersonalInfo'].'</div>';
					}
					
					if(in_array('empEmrContact',$this->bottomLinksHighlighted))
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empEmrContact\'); return false;">'.$this->bottomLinksArray['empEmrContact'].'</div>';
					}
					else
					{
						$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empEmrContact\'); return false;">'.$this->bottomLinksArray['empEmrContact'].'</div>';
					}
			   } // end is hr.
		  } // end of is full access
		  else
		  {
				if(in_array('empAddress',$this->bottomLinksHighlighted))
				{
					$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empAddress\'); return false;">'.$this->bottomLinksArray['empAddress'].'</div>';
				}
				else
				{
					$this->bottomLinksDashBoardVar .= '<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empAddress\'); return false;">'.$this->bottomLinksArray['empAddress'].'</div>';
				}
				
				if(in_array('empEmrContact',$this->bottomLinksHighlighted))
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empEmrContact\'); return false;">'.$this->bottomLinksArray['empEmrContact'].'</div>';
				}
				else
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empEmrContact\'); return false;">'.$this->bottomLinksArray['empEmrContact'].'</div>';
				}
				
				if(in_array('empViewPersonalInfo',$this->bottomLinksHighlighted))
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empViewPersonalInfo\'); return false;">'.$this->bottomLinksArray['empViewPersonalInfo'].'</div>';
				}
				else
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empViewPersonalInfo\'); return false;">'.$this->bottomLinksArray['empViewPersonalInfo'].'</div>';
				}
				
				if(in_array('empViewSalary',$this->bottomLinksHighlighted))
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empViewSalary\'); return false;">'.$this->bottomLinksArray['empViewSalary'].'</div>';
				}
				else
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empViewSalary\'); return false;">'.$this->bottomLinksArray['empViewSalary'].'</div>';
				}
				
				if(in_array('empViewPayrollData',$this->bottomLinksHighlighted))
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFontBorder" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empViewPayrollData\'); return false;">'.$this->bottomLinksArray['empViewPayrollData'].'</div>';
				}
				else
				{
					$this->bottomLinksDashBoardVar .=	'<div class="adpLeftMenuSmallFont" onClick="gotoNextPage('.$employeeID.',\'emp\', \'empViewPayrollData\'); return false;">'.$this->bottomLinksArray['empViewPayrollData'].'</div>';
				}
					
		  }
           
    	$this->bottomLinksDashBoardVar .= '</div>';
	
	}
	function getbottomLinksDashBoard()
	{
		echo $this->bottomLinksDashBoardVar;
	
	}
	
	function setUSLocations()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		$sqlQuery = "SELECT 
					[location],[description] 
				FROM  
					[ctlLocations] WITH (NOLOCK) 				
				WHERE
					State IS NOT NULL AND location IN ".$this->UserDetails->Locations."  
				AND 
					active ='Y' AND switch ='N' 
				AND
					country = 'United States of America' 
				ORDER BY 
					description ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->usLocationArray = $this->bindingInToArray($resultsSet);
		}
	}
	
	function getUsLocations()
	{
		return 	$this->usLocationArray;
	}
	
	function setEmploymentStatuses()
	{
			$sqlQuery = " SELECT 
								* 
							FROM 
								[ctlEmploymentStatuses] WITH (NOLOCK) 
							ORDER BY 
								description ";
	
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employmentStatus = $this->bindingInToArray($resultsSet);
		}
	}
	
	function getEmploymentStatuses()
	{
		return 	$this->employmentStatus;
	}
	
	function setUSReportingLocations()
	{
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		$sqlQuery = " SELECT 
							b.location, b.description 
						FROM 
							RNet.dbo.ctlADPPayGroupLocations a WITH (NOLOCK) 
						JOIN
							ctlLocations b WITH (NOLOCK)
						ON
							a.location = b.location
						WHERE 
							a.location  <> 803	
						ORDER BY 
							b.description ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->usReportingLocationArray = $this->bindingInToArray($resultsSet);
		}
		
	}
	
	
	function getUSReportingLocations()
	{
		return 	$this->usReportingLocationArray;
	}
	
	
	function setUSPayGroupLocations($accessLocations = NULL)
	{
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		if($accessLocations=='Yes')
		{
			$sqlQuery = " SELECT	
								DISTINCT [description] + ' (' + paygroupID + ')' [paygroup],
								CASE WHEN location IN ('800','801','803') THEN '800' ELSE location END [location]
							FROM	
								rnet.dbo.ctlADPPaygroupLocations WITH (NOLOCK) 
							WHERE
								location IN ".$this->UserDetails->Locations."  ";
		}
		else
		{
				$sqlQuery = " SELECT	
								DISTINCT [description] + ' (' + paygroupID + ')' [paygroup],
								CASE WHEN location IN ('800','801','803') THEN '800' ELSE location END [location]
							FROM	
								rnet.dbo.ctlADPPaygroupLocations WITH (NOLOCK) ";
		}
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->usPayGroupLocationArray = $this->bindingInToArray($resultsSet);
		}
		
	}
	function getUSPayGroupLocations()
	{
		return 	$this->usPayGroupLocationArray;
	}
	
	function convertArrayKeyValuePair($arrayObject, $arrayKey , $arrayVal)
	{
		unset($this->convertArray);
		
		foreach($arrayObject as $arrayObjectKey=>$arrayObjectVal)
		{
			
			$this->convertArray[$arrayObjectVal[$arrayKey]] = $arrayObjectVal[$arrayVal];
		}
		
		return $this->convertArray;
	}
	
	function setEmployeeInformation($employeeID , $curDate)
	{
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$curDatePlus90Day = date('m/d/Y', strtotime($curDate. ' + 90 day'));
				
		$sqlQuery = " EXEC RNet.dbo.[standard_spEmployeeInformation] '%','$employeeID','$curDate','$curDatePlus90Day' ";
		//echo $sqlQuery;
		//exit;
		
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employData = $this->bindingInToArray($resultsSet);
		}
		
	}
	
	function getEmployeeInformation()
	{
		return 	$this->employData;
	}
	
	
	function setEmployeeADPInformation($employeeID)
	{
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		$sqlQuery = " EXEC RNet.dbo.[report_spEmployeeADPInformation] '$employeeID' ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employADPData = $this->bindingInToArray($resultsSet);
		}
		
	}
	
	function getEmployeeADPInformation()
	{
		return 	$this->employADPData;
	}
	
	function setTopLevelEmployeeInfo($employData , $employADPData)
	{
		
		
			$this->topLevelEmployeeInfo = '';
			//$empProfileImage = 'https://'.$_SERVER['HTTP_HOST'].$employData[0]['imageUrl'];
			$checkPathFileName = $_SERVER['DOCUMENT_ROOT'].$employData[0]['imageUrl'];
			//echo 'xxx'.$empProfileImage;
			$this->topLevelEmployeeInfo = '<div id="singlePixelBorder" class="outer">
    										<table id="adpsearchTable" cellspacing="3">
											<tr>
												<th>First Name</th>
												<td>'.$employData[0]['firstName'].'</td>
												
												<th>Last Name</th>
												<td>'.$employData[0]['lastName'].'</td>';
												
			if(is_file($checkPathFileName))
			{
				$this->topLevelEmployeeInfo .= 		'<td rowspan="4"><img src = "https://'.$_SERVER['HTTP_HOST'].
														$employData[0]['imageUrl']													
													.'" width="110px;" height="120px;"></td>' ;
			}
			$this->topLevelEmployeeInfo .=		'</tr>
											
											<tr>
												<th>Location</th>
												<td>'.$employData[0]['locationDescription'].'</td>
											
												<th>Employee ID</th>
												<td>'.$employData[0]['employeeID'].'</td>
											</tr>
												
											<tr>
												<th>Position</th>
												<td>'.$employData[0]['position'].'</td>
												<th>Full-time / Part-time</th>
												<td>'.$employADPData[0]['fullTimePartTime'].'</td>
											</tr>
												
											<tr>
												<th>Employee Type</th>
												<td>'.$employData[0]['payTypeDescription'].'</td>
											</tr>
												
										 </table>
									   
										</div>
											<div class="outer" id="emptyDiv"></div>';
	
		}
	
	function getTopLevelEmployeeInfo()
	{
		echo $this->topLevelEmployeeInfo;
	}
	
	
	function setEmployeeFullTimePartTimeInformation($employeeID)
	{
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
			
		$sqlQuery = " SELECT 
							a.employeeID ,
							effectiveDate ,
							b.description as fullPartTimeDescription
						FROM
							Rnet.dbo.prmEmployeePayrollPartTimeFullTime a WITH (NOLOCK)
						JOIN
							results.dbo.ctlTimingTypes b WITH (NOLOCK)
						ON
							a.fullPartTime = b.type
						WHERE
							a.employeeID = '$employeeID' 
						ORDER BY  
							effectiveDate DESC  ";

		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employeeFulltimeParttimeInfo = $this->bindingInToArray($resultsSet);
		}
		
	}
	
	function getEmployeeFullTimePartTimeInformation()
	{
		return $this->employeeFulltimeParttimeInfo;	
	}
	
	
	function getEmployeeTimingTypes()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
			
		$sqlQuery = " SELECT * FROM ctlTimingTypes WITH (NOLOCK) ORDER BY [description]  ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employeeTimingTypes = $this->bindingInToArray($resultsSet);
		}
		
		return $this->employeeTimingTypes;
	}
	
	function getHistoricalPartTimeFullTimeInformation($employeeID)
	{
		$this->empHistoricalPartTimeFullTimeInformation='';
		
		$this->empHistoricalPartTimeFullTimeInformation = '<div id="topHeading">Historical Part Time / Full Time  Information</div>';
		$this->empHistoricalPartTimeFullTimeInformation .= '
		<table border="0" align="left" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report">
		   <thead>   
			<tr>
				<th>Effective Date </th>
				<th>Part Time / Full Time</th>
				
			</tr>
		</thead>';
	
		
	if(!empty($this->employeeFulltimeParttimeInfo))
	{
		$restrict = 1;
		
		foreach($this->employeeFulltimeParttimeInfo as $fullPartTimeK=>$fullPartTimeV)
		{
			$effectiveDate = date('m/d/Y',strtotime($fullPartTimeV[effectiveDate]));
			$fullPartTimeDescription = $fullPartTimeV[fullPartTimeDescription];
			
			$this->empHistoricalPartTimeFullTimeInformation .= '<tr>
				<td style="text-align:center; font-size:11px;">';
				
				if($restrict==1)
				{
					$this->empHistoricalPartTimeFullTimeInformation .= "<a href=\"#\" onclick=\"setFullTimePartTimeVars('".$employeeID."', '".$effectiveDate."'); return false;\">(Edit)<span style=\"text-decoration:none;\">&nbsp;&nbsp;</span>".$effectiveDate."</a>";
					
					
				} 
				else 
				{
					$this->empHistoricalPartTimeFullTimeInformation .= $effectiveDate;
				}
				$this->empHistoricalPartTimeFullTimeInformation .= '</td>
				<td style="font-size:11px;text-align:center;">'.$fullPartTimeDescription.'</td></tr>';

			$restrict++; 
		}
	}
	else 
	{
		$this->empHistoricalPartTimeFullTimeInformation .= "<td colspan = 2  style=\"text-align:left; font-size:12px;\">No records in the history</td>";
	}
	$this->empHistoricalPartTimeFullTimeInformation .= '</table>';
	return $this->empHistoricalPartTimeFullTimeInformation;
	}
	
	function setAllLocations()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		$sqlQuery = "SELECT 
					[location],[description] 
				FROM  
					[ctlLocations] WITH (NOLOCK) 				
				WHERE
					State IS NOT NULL AND location IN ".$this->UserDetails->Locations."  
				AND 
					active ='Y' AND switch ='N' 
				ORDER BY 
					description ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->allLocationArray = $this->bindingInToArray($resultsSet);
		}
	}
	
	function getAllLocations()
	{
		return 	$this->allLocationArray;
	}
	
	function setADPClients()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		$sqlQuery = " SELECT 
							* 
						FROM  
							rnet.dbo.ctlADPClientCodes WITH (NOLOCK) 
						ORDER BY 
							clientDescription ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->adpClientCodes = $this->bindingInToArray($resultsSet);
		}
			
	}
	function getADPClients()
	{
		return 	$this->adpClientCodes;
	}
	
	
	public  function setMissingColumns($employeeID)
	{
		//echo 'xxxxxx';
		
		$getCountryName = $this->getCountryName($employeeID);	
		//$getPayrollLocation = $this->getPayrollLocation($employeeID);	
		
			$restrictPayrollLocations = array('800','801','803');
			
			$getPayrollLocation = 'notRequired';
			
			$emppayrollLocation = $this->getEmployeeCurrentPayrollLocation($employeeID);
			//echo 'EmployeePayrollLocaiton'.$emppayrollLocation;
			//exit;
			
			if(!in_array($emppayrollLocation, $restrictPayrollLocations))
			{
				$getPayrollLocation  = 'Required'	;
			}
		
		//echo 'GEt'.$getPayrollLocation;
		//exit;
		
		unset($requiredData);
		if($getCountryName=='United States of America')
		{
			/*$sqlMissingData = " SELECT 
									a.secureSSN, 
									a.dob,
									b.employeeID
								FROM 
									ctlEmployees a WITH (NOLOCK) 
								LEFT JOIN
									ctlEmployeeEmergencyContactInformation b WITH (NOLOCK)
								ON
									a.employeeID = b.employeeID
								WHERE 
									a.employeeID = $employeeID ";*/
			$sqlMissingData = " 
						IF OBJECT_ID('tempdb.dbo.#tempPayrollRates') IS NOT NULL
						DROP TABLE #tempPayrollRates
						
						CREATE TABLE #tempPayrollRates
						(
							employeeID INT NULL,
							startDate DATETIME NULL
						)
						
						INSERT INTO #tempPayrollRates
						SELECT 
							employeeID ,
							MAX(startDate)
							
							FROM
								ctlEmployeePayrollRates
							WHERE
								employeeID = '".$employeeID."'
							GROUP BY employeeID
													
						
						SELECT 
							 a.payType
							,a.amount
							,b.employeeID addressID
							,e.maritalStatus
							,e.dob
							,e.gender
							,e.ethnicity
							,e.educationLevel
							,e.secureSSN
							,c.employeeID contactID
							,e.location	
							,d.positionID
						FROM
							ctlEmployees e WITH (NOLOCK)
						LEFT JOIN
							#tempPayrollRates tp WITH (NOLOCK)
						ON
							e.employeeID = tp.employeeID
						LEFT JOIN
							ctlEmployeePayrollRates a WITH (NOLOCK)
						ON
							tp.employeeID = a.employeeID
						AND
							tp.startDate = a.startDate
						LEFT JOIN
							ctlEmployeeAddresses b WITH (NOLOCK)
						ON
							e.employeeID = b.employeeID
						AND
							b.addressType = 'home'
						LEFT JOIN
							ctlEmployeeEmergencyContactInformation c WITH (NOLOCK)
						ON
							e.employeeID = c.employeeID
						LEFT JOIN
							ctlEmployeePositions d WITH (NOLOCK)
						ON
							e.employeeID = d.employeeID
						AND
							d.isPrimary = 'Y'
						
						WHERE
							e.employeeID = '".$employeeID."' ";
							
			$rstMissingData = $this->ExecuteQuery($sqlMissingData);
			while($rowMissingData = mssql_fetch_assoc($rstMissingData))
			{
				$requiredData[] = $rowMissingData;
			}
			
			unset($rstMissingData);
			unset($this->bottomLinksHighlighted);
			
			foreach($requiredData as $requiredDataVal)
			{
				
				if($getPayrollLocation=='Required')
				{
					if(empty($requiredDataVal['payType']))
					{
							$missingDataCols .= 'Pay Type, ';
							$this->bottomLinksHighlighted[] = 'empSalary';
					}
					
					/*if(empty($requiredDataVal['fullPartTime']))
					{
							$missingDataCols .= 'Full-time/Part-time, ';
					}*/
					
					if(empty($requiredDataVal['amount']))
					{
							$missingDataCols .= 'Comp Rate, ';
							$this->bottomLinksHighlighted[] = 'empSalary';
					}
				}
				
				if(empty($requiredDataVal['addressID']))
				{
						$missingDataCols .= 'Home Address, ';
						$this->bottomLinksHighlighted[] = 'empAddress';
				}
				
				if(empty($requiredDataVal['maritalStatus']))
				{
						$missingDataCols .= 'Marital Status, ';
						$this->bottomLinksHighlighted[] = 'empPersonalInfo';
				}
				if(empty($requiredDataVal['dob']))
				{
						$missingDataCols .= 'DOB, ';
						$this->bottomLinksHighlighted[] = 'empPersonalInfo';
				}
				
				if(empty($requiredDataVal['gender']))
				{
						$missingDataCols .= 'Gender, ';
						$this->bottomLinksHighlighted[] = 'empPersonalInfo';
				}
				
				if(empty($requiredDataVal['ethnicity']))
				{
						$missingDataCols .= 'Race, ';
						$this->bottomLinksHighlighted[] = 'empPersonalInfo';
				}
				
				if(empty($requiredDataVal['educationLevel']))
				{
						$missingDataCols .= 'Education Level, ';
						$this->bottomLinksHighlighted[] = 'empPersonalInfo';
				}
				
				if(empty($requiredDataVal['secureSSN']))
				{
						$missingDataCols .= 'SSN, ';
						$this->bottomLinksHighlighted[] = 'empPersonalInfo';
				}
				
				if(empty($requiredDataVal['contactID']))
				{
						$missingDataCols .= 'Emergency Contact, ';
						$this->bottomLinksHighlighted[] = 'empEmrContact';
				}
				
				if(empty($requiredDataVal['location']))
				{
						$missingDataCols .= 'Reporting Location, ';
						$this->bottomLinksHighlighted[] = 'empPayrollData';
						
				}
				
				if(empty($emppayrollLocation))
				{
						$missingDataCols .= 'Work Location, ';
						$this->bottomLinksHighlighted[] = 'empPayrollData';
				}
				if(empty($requiredDataVal['positionID']))
				{
						$missingDataCols .= 'Position, ';
						$this->bottomLinksHighlighted[] = 'empPosition';
				}
				if($this->employADPData[0]['hireDateReview']!='Y')
				{
						$missingDataCols .= 'Hire Date not yet reviewed by HR, ';
						$this->bottomLinksHighlighted[] = 'empDates';
				}
	
			}
			
			$missingDataCols = substr($missingDataCols,0,-2);
			//return $missingDataCols;
			if(!empty($missingDataCols))
			{
				$this->setMissingEmployeeData = '<fieldset style="color:#F00;border:3px solid #F00;padding:0px; width:95%; text-align:left;" >
						<legend style="color:#F00;"><strong>Missing Payroll Data</strong></legend>
						<table border="0">
						<tr>
						<td><strong>'.$missingDataCols.'</strong></td>
						</tr>
						</table>
						</fieldset>';
			}
		}
	} // eof
	
	public  function getMissingColumns()
	{
		return $this->setMissingEmployeeData;	
	}
	
	public function setLocationsWithOutCorporate()
	{
		//$locationsWithOutCorporate	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($usLocationString);
		unset($usLocaRows);
			
		$sqlQuery = " SELECT 
							[location]
						FROM  
							[ctlLocations] WITH (NOLOCK) 				
						WHERE
							country = 'United States of America'   
						AND 
							active ='Y' AND switch ='N' 
						AND
							state IS NOT NULL
						AND
							location NOT IN (800,801,803)
						ORDER BY 
							[location] ";

		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			while($usLocaRows = mssql_fetch_assoc($resultsSet))
			{
				$usLocationString .= $usLocaRows['location'].',';
			}
			
			$this->locationsWithOutCorporate = substr($usLocationString,0,-1);
		}
		
	} // eof
	
	public  function getLocationsWithOutCorporate()
	{
		return $this->locationsWithOutCorporate;	
	} // eof
	
	
	public function getN_NumberofPayperiods($topN=4, $hireDate = NULL, $payrollLocation)
	{
		/*
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($returnN_NumberofPayperiods);
		unset($usLocaRows);
			
		$sqlQuery = " IF OBJECT_ID('tempdb.dbo.#tempPayDateStartDates') IS NOT NULL DROP TABLE #tempPayDateStartDates
					CREATE TABLE #tempPayDateStartDates
					(
						paydate DATETIME NULL,
						startDate DATETIME NULL,
						endDate DATETIME NULL,
						location VARCHAR(3) NULL
					)
					
					INSERT INTO #tempPayDateStartDates
					SELECT 
						a.paydate, a.startDate, a.endDate, a.location
					FROM
						ctlLocationPaydateSchedules a WITH (NOLOCK) 
					WHERE 
						GETDATE() BETWEEN a.startDate AND a.endDate AND a.location = '-1'
					
					INSERT INTO #tempPayDateStartDates
					SELECT  
						TOP ".$topN." a.paydate, a.startDate, a.endDate, a.location
					FROM 
						ctlLocationPaydateSchedules a WITH (NOLOCK) 
					JOIN
						#tempPayDateStartDates b WITH (NOLOCK)
					ON
						a.location = b.location
					WHERE 
						a.startDate > b.endDate
					AND
						a.location = '-1'
					ORDER BY 
						Paydate
					
					SELECT 
					CONVERT(VARCHAR(10),startDate,101) startDate , 
					CONVERT(VARCHAR(10),endDate,101) endDate 
				FROM 
					#tempPayDateStartDates WITH (NOLOCK) 
				WHERE
					startDate > '".$hireDate."' ";

		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->returnN_NumberofPayperiods = $this->bindingInToArray($resultsSet);
			
		}	
		*/
		
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($returnN_NumberofPayperiods);
		unset($usLocaRows);

		$sqlQuery = " 
		IF OBJECT_ID('tempdb.dbo.#tempPayDateStartDates') IS NOT NULL DROP TABLE #tempPayDateStartDates
		CREATE TABLE #tempPayDateStartDates
		(
			location VARCHAR(3) NULL,
			startDate DATETIME NULL
		)
			
		INSERT INTO #tempPayDateStartDates
		SELECT
			location,
			MIN(startDate)
		FROM
			results.dbo.ctlLocationPaydateschedules WITH (NOLOCK)
		WHERE
			location = ".$payrollLocation."
		AND
			ISNULL(isFinalized,'N')<>'Y'
		AND
			startDate > '".$hireDate."'
		GROUP BY 
			location
			
		INSERT INTO #tempPayDateStartDates
		SELECT  
			TOP ".$topN." a.location , a.startDate
		FROM 
			ctlLocationPaydateSchedules a WITH (NOLOCK) 
		JOIN
			#tempPayDateStartDates b WITH (NOLOCK)
		ON
			a.location = b.location
		WHERE 
			a.startDate > b.startDate
		AND
			a.location = ".$payrollLocation."
		AND
			ISNULL(isFinalized,'N')<>'Y'		
		ORDER BY 
			Paydate
		
		SELECT 
			CONVERT(VARCHAR(10),startDate,101) startDate 
		FROM 
			#tempPayDateStartDates WITH (NOLOCK) ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->returnN_NumberofPayperiods = $this->bindingInToArray($resultsSet);
			
		}
		return $this->returnN_NumberofPayperiods;
	} // eof
	
	
	public function fnFirstRecoredInPayrollRates($employeeID , $hireDate)
	{
			
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($returnN_NumberofPayperiods);
		
			
		$sqlQuery = " IF OBJECT_ID('tempdb.dbo.#tempdbEmployeeCompRateFunctionality') 
					IS NOT NULL
					DROP TABLE #tempdbEmployeeCompRateFunctionality
					
					CREATE TABLE #tempdbEmployeeCompRateFunctionality 
					(
						employeeID INT NULL,
						isHireDateExist CHAR(1) NULL,
						isFirstRecord CHAR(1) NULL,
						hireDate DATETIME NULL,
						compEffectiveDate DATETIME NULL
					)
					
					INSERT INTO #tempdbEmployeeCompRateFunctionality
					(employeeID, isHIreDateExist , hireDate)
					VALUES
					('".$employeeID."', 'Y', '".$hireDate."')
					
					
					
					IF(SELECT count(*) FROM results.dbo.ctlEmployeePayrollRates WITH (NOLOCK) 
							WHERE employeeID = '".$employeeID."' AND startDate>='".$hireDate."')=0
					BEGIN
					
					UPDATE 
							#tempdbEmployeeCompRateFunctionality 
						SET 	
							isFirstRecord = 'N'
						WHERE
							employeeID = '".$employeeID."' AND hireDate =  '".$hireDate."'
					END	
					
					IF(SELECT count(*) FROM results.dbo.ctlEmployeePayrollRates WITH (NOLOCK) 
							WHERE employeeID = '".$employeeID."' AND startDate>='".$hireDate."' )=1
					BEGIN
					
					UPDATE 
						a
							SET a.isFirstRecord = 'Y',
								a.compEffectiveDate = b.startDate
						FROM
							#tempdbEmployeeCompRateFunctionality a WITH (NOLOCK)
						JOIN
							results.dbo.ctlEmployeePayrollRates b WITH (NOLOCK)
						ON
							a.employeeID = b.employeeID
						AND
							a.hireDate = b.startDate
					END	
					
					SELECT * FROM #tempdbEmployeeCompRateFunctionality (NOLOCK) ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		while($rowGetHireDate = mssql_fetch_assoc($resultsSet))
		{
			$this->isFirstRecoredInPayrollRates = $rowGetHireDate['isFirstRecord'];
		}
		
		return $this->isFirstRecoredInPayrollRates;
		
	}
	
	/////////START 2013/03/26
	public function getEmployeePayrollHours($employeeID, $hireDate)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsQuery);
		unset($expsCount);
		unset($returnFlag);
		unset($output);
		
		$returnFlag = false;
		
		$sqlQuery = " SELECT COUNT(*) AS cntPay FROM RNet.dbo.prmEmployeePayrollExceptions WITH (NOLOCK) WHERE employeeID = ".$employeeID."  ";
		if(!empty($hireDate))
		{
			$sqlQuery .= " AND date >= '".$hireDate."' ";
		}
		//echo $sqlQuery;exit;
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		while($rowsQuery = mssql_fetch_assoc($resultsSet))
		{
			$expsCount = $rowsQuery['cntPay'];
		}
		if($expsCount>0)
		{
			$returnFlag = true;
		}
		mssql_free_result($resultsSet);
		
		if(!$returnFlag)
		{
			unset($sqlQuery);
			unset($resultsSet);
			unset($rowsQuery);
			unset($expsCount);
			
			$sqlQuery = " SELECT COUNT(*) AS cntPay FROM RNet.dbo.prmEmployeePayrollBonuses WITH (NOLOCK) WHERE employeeID = ".$employeeID."  ";
			if(!empty($hireDate))
			{
				$sqlQuery .= " AND payDate >= '".$hireDate."' ";
			}
			//echo $sqlQuery;exit;
			$resultsSet = $this->ExecuteQuery($sqlQuery);
			while($rowsQuery = mssql_fetch_assoc($resultsSet))
			{
				$expsCount = $rowsQuery['cntPay'];
			}
			if($expsCount>0)
			{
				$returnFlag = true;
			}
			mssql_free_result($resultsSet);
		}
		
		/*if(!$returnFlag)
		{
			unset($sqlQuery);
			unset($resultsSet);
			unset($rowsQuery);
			unset($expsCount);
			
			$sqlQuery = " SELECT COUNT(*) AS cntPay FROM RNet.dbo.prmEmployeeTimeClockEntries WITH (NOLOCK) WHERE employeeID = ".$employeeID." AND CONVERT(VARCHAR(10),startTime,101) >= '".$hireDate."' ";
			//echo $sqlQuery;exit;
			$resultsSet = $this->ExecuteQuery($sqlQuery);
			while($rowsQuery = mssql_fetch_assoc($resultsSet))
			{
				$expsCount = $rowsQuery['cntPay'];
			}
			if($expsCount>0)
			{
				$returnFlag = true;
			}
			mssql_free_result($resultsSet);
		}*/
			
		if(!$returnFlag)
		{
			unset($sqlQuery);
			unset($resultsSet);
			unset($rowsQuery);
			unset($opIDArr);
			unset($finalOpIDs);
			
			$sqlQuery = " SELECT userName FROM ctlEmployeeApplications WITH (NOLOCK) WHERE employeeID = ".$employeeID." AND applicationName = 'Avaya Switch' ";
			//echo $sqlQuery;exit;
			$resultsSet = $this->ExecuteQuery($sqlQuery);
			while($rowsQuery = mssql_fetch_array($resultsSet))
			{
				$opIDArr .= "'".$rowsQuery['userName']."',";
			}
			$finalOpIDs = substr($opIDArr,0,-1);
			mssql_free_result($resultsSet);
			//echo 'xx'.$finalOpIDs.'xx';exit;
			
			if(empty($finalOpIDs))
			{
				$finalOpIDs = -1;
			}
			
			unset($sqlQuery);
			unset($resultsSet);
			unset($rowsQuery);
			unset($combSummaryHours);
			
			$sqlQuery = " SELECT COUNT(*) AS cntCom FROM RNet.dbo.prmCombinedSummary WITH (NOLOCK) WHERE opID IN (".$finalOpIDs.") ";
			if(!empty($hireDate))
			{
				$sqlQuery .= " AND date >= '".$hireDate."' ";
			}
			//echo $sqlQuery;exit;
			$resultsSet = $this->ExecuteQuery($sqlQuery);
			while($rowsQuery = mssql_fetch_array($resultsSet))
			{
				$combSummaryHours = $rowsQuery['cntCom'];
			}
			if($combSummaryHours>0)
			{
				$returnFlag = true;
			}
			mssql_free_result($resultsSet);
		}
		
		
		/*if($returnFlag)
		{
			$output = 'Exists';
		}
		else
		{
			$output = 'Nope';
		}
		*/
		return $returnFlag;
	}
		/////////END 2013/03/26
	
	
	function setUSADPPayGroupLocations()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
				
		$sqlQuery = "SELECT 
					a.location,a.description 
				FROM  
					[ctlLocations] a WITH (NOLOCK) 
				JOIN
					RNet.dbo.ctladpPaygroupLocations b WITH (NOLOCK)
				ON
					a.location = b.location		
				WHERE
					a.State IS NOT NULL AND a.location IN ".$this->UserDetails->Locations."  
				AND 
					a.active ='Y' AND a.switch ='N' 
				AND
					a.country = 'United States of America' 
				ORDER BY 
					a.description ";
		//echo $sqlQuery;
		//exit;
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->usADPLocationArray = $this->bindingInToArray($resultsSet);
		}
	}
	
	function getUSADPPayGroupLocations()
	{
		return 	$this->usADPLocationArray;
	}
	
	
	/////////////////Work Item #38193
	function getSupervisors($employeeID)
	{
			
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsQry);
		unset($locationID);
		unset($businessFunction);
		
		$sqlQuery = "SELECT location, corporateAccess FROM  [ctlEmployees] WITH (NOLOCK) WHERE employeeID = ".$employeeID;
		//echo $sqlQuery;exit;
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		while($rowsQry = mssql_fetch_assoc($resultsSet)) 
		{	
			$locationID = $rowsQry['location'];
			$businessFunction = $rowsQry['corporateAccess'];
		}
		mssql_free_result($resultsSet);
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		if($businessFunction=='Y')
		{
			$sqlQuery = " EXEC RNet.dbo.report_spPopulateSupervisors $locationID, 'Y' " ; 
		}
		else
		{
			$sqlQuery = " EXEC RNet.dbo.report_spPopulateSupervisors $locationID, 'N' " ; 		
		}
		//echo $sqlQuery;exit;
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->supArray = $this->bindingInToArray($resultsSet);
		}
		mssql_free_result($resultsSet);
		
		return $this->supArray;
	}
	
	function setEmployeeSupervisorInformation($employeeID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
			
		$sqlQuery = "	SELECT 
							es.effectiveDate, es.supApprove, e.firstName, e.lastName 
						FROM 
							ctlEmployeeSupervisors es WITH (NOLOCK)
						LEFT JOIN
							ctlEmployees e WITH (NOLOCK)
						ON
							es.SupervisorID = e.employeeID
						WHERE
							es.employeeID = ".$employeeID."
						ORDER BY 
							es.effectiveDate DESC";
		//echo $sqlQuery;exit;							
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employeeSupervisorInfo = $this->bindingInToArray($resultsSet);
		}
		
	}
	
	function getEmployeeSupervisorInformation()
	{
		return $this->employeeSupervisorInfo;	
	}
	
	function getHistoricalSupervisorInformation($employeeID)
	{
		$this->empHistoricalSupervisorInformation='';
		
		$this->empHistoricalSupervisorInformation = '<div id="topHeading">Historical Supervisor  Information</div>';
		$this->empHistoricalSupervisorInformation .= '
		<table border="0" align="left" cellpadding="0" bgcolor="#FFFFFF" cellspacing="0" class="report">
		   <thead>   
			<tr>
				<th>Effective Date </th>
				<th>Supervisor</th>
				<th>Is Approved?</th>
			</tr>
		</thead>';
	
		if(!empty($this->employeeSupervisorInfo))
		{
			foreach($this->employeeSupervisorInfo as $employSupsArrayK=>$employSupsArrayV)
			{
				$efDate = date('m/d/Y',strtotime($employSupsArrayV['effectiveDate']));
				$supervisor = $employSupsArrayV['firstName'].' '.$employSupsArrayV['lastName'];
				if($employSupsArrayV['supApprove']!='')
				{
					$isSupApp = 'Yes';
				}
				else
				{
					$isSupApp = 'No';
				}
				
				$this->empHistoricalSupervisorInformation .= '<tr>
				<td style="text-align:center; font-size:11px;">'.$efDate.'</td>';
				$this->empHistoricalSupervisorInformation .=
				'<td style="text-align:center; font-size:11px;">'.$supervisor.'</td>';
				$this->empHistoricalSupervisorInformation .=
				'<td style="text-align:center; font-size:11px;">'.$isSupApp.'</td>';
			}
		}
		else 
		{
			$this->empHistoricalSupervisorInformation .= "<td colspan = 2  style=\"text-align:left; font-size:12px;\">No records in the history</td>";
		}
		$this->empHistoricalSupervisorInformation .= '</table>';
		return $this->empHistoricalSupervisorInformation;
	}
	
	/////////////////END OF Work Item #38193	
}
?>