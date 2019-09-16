<?php
/*
   Description :  This class is used for Agent Scrore Card.
 */

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/DB.class.inc.php");

class agentScoreCard extends ClassQuery 
{
	public $topLevelGenralData;
	
	public $inboundData;
	
	public $comCastInboundData;
	public $sprintInboundData;
	
	public $outboundData;
	public $faxData;
	public $coachingSessionData;
	public $employeeLifeCycleData;
	public $employeeStructureData;
	public $parameters;
	
	public $inboundCallesHandled;
	public $employeeNameAndAvaya;
	public $unEndCoachings;
	
	public $trStryleArray = array('0'=>'<tr style="height:20px; background-color:#D0D8E8;">', '1'=>'<tr style="height:20px; background-color:#E9EDF4;">', '3'=>'<tr style="height:20px; background-color:#999999;">');
	
	public $indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg', 'X'=>'includes/images/white_ball_blue.jpg'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg', 'X'=>'includes/images/white_ball_grey.jpg'),'2'=>array('G'=>'includes/images/SmallGreen_greyBall.jpg', 'Y'=>'includes/images/SmallYellow_greyBall.jpg', 'R'=>'includes/images/SmallRed_greyBall.jpg', 'X'=>'includes/images/white_ball_greyBall.jpg'));
	
		
	function setTopLevelGeneralData($employeeID, $date, $client)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		
		
		if($client == 'sprint')
		{
			
			
		$sqlQuery = " EXEC ".$client."Common.dbo.report_spScorecardTopLevelGeneral_Version1 '".$employeeID."', '".$date."'";
			$resultsSet = $this->ExecuteQuery($sqlQuery, '', 'LIVE');
		}
		else
		{
			
			$sqlQuery = " EXEC ".$client."Common.dbo.report_spScorecardTopLevelGeneral'".$employeeID."', '".$date."'";
			$resultsSet = $this->ExecuteQuery($sqlQuery);
		}
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->topLevelGenralData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	function getopLevelGeneralData()
	{
		return $this->topLevelGenralData;	
	} 
	
	
	function setInboundData($employeeID, $date)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC WellcareCommon.dbo.report_spScorecardTopLevelInbound '".$employeeID."', '".$date."'";
		

		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->inboundData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	
	
	function setComCastInboundData($employeeID, $date)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC ComcastCommon.dbo.report_spScorecardTopLevelInbound '".$employeeID."', '".$date."'";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->comCastInboundData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	
	
	function getComCastInboundData()
	{
		return $this->comCastInboundData;	
	} 
	
	function setSprintInboundData($employeeID, $date)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC SprintCommon.dbo.report_spScorecardTopLevelDirectorsCup_Version2 '".$employeeID."', '".$date."'";
				
		$resultsSet = $this->ExecuteQuery($sqlQuery, '','LIVE');
		
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->sprintInboundData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	
	
	function getSprintInboundData()
	{
		return $this->sprintInboundData;	
	} 
	

	function getInboundData()
	{
		return $this->inboundData;	
	} 
	
	
	function setOutboundData($employeeID, $date)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC WellcareCommon.dbo.report_spScorecardTopLevelOutbound '".$employeeID."', '".$date."'";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->outboundData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	function getOutboundData()
	{
		return $this->outboundData;	
	} 
	
	
	function setFaxData($employeeID, $date)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC WellcareCommon.dbo.report_spScorecardTopLevelFaxProductivity '".$employeeID."', '".$date."'";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->faxData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	function getFaxData()
	{
		return $this->faxData;	
	} 
	
	
	function isRecordsExists($employeeID , $requestedDate, $client, $lob_id)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		
$sqlQuery = "EXEC RNet.dbo.[report_spCheckEmployeeScoreCardApprovalsExisted] '$employeeID', '$requestedDate', '$client'				, '$lob_id' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$recordsNum = mssql_result($resultsSet,0,0);
		if($recordsNum>=1)
		{
			return 'exist';
		}	
		else
		{
			return 'not exist';	
		}
	}  
	
	function insertRecord($hdnEmployeeID , $hdnRequestedDate, $hdnIsSuccess, $ddlReason, $client, $lob_id)
	{
			$modifiedBy = $this->UserDetails->User;
			$modifiedDate = date('m/d/Y h:i:s');
			unset($sqlQuery);
			unset($resultsSet);
						  
			$sqlQuery =  "EXEC RNet.dbo.process_spEmployeeScoreCardApprovals '$hdnEmployeeID', '$hdnRequestedDate', '$client' , '$lob_id', '$hdnIsSuccess', '$ddlReason', 'Insert' ";
			
			$resultsSet = $this->ExecuteQuery($sqlQuery);
			
			return $resultsSet;
	} 
	
	function updateRecord($hdnEmployeeID , $hdnRequestedDate, $hdnIsSuccess, $ddlReason, $client, $lob_id)
	{
			$modifiedBy = $this->UserDetails->User;
			$modifiedDate = date('m/d/Y h:i:s');
			unset($sqlQuery);
			unset($resultsSet);
			
			$sqlQuery =  "EXEC RNet.dbo.process_spEmployeeScoreCardApprovals '$hdnEmployeeID', '$hdnRequestedDate', '$client' , '$lob_id', '$hdnIsSuccess', '$ddlReason', 'Update' ";
			
			
			$resultsSet = $this->ExecuteQuery($sqlQuery);
			
			return $resultsSet;
	} 
	
	function getRejectionId($hdnEmployeeID , $hdnRequestedDate, $client, $lob_id)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
							
		$sqlQuery = "EXEC RNet.dbo.[report_spGetEmployeeScoreCardRejectionReason] '$hdnEmployeeID','$hdnRequestedDate','$client', '$lob_id' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rejectionReason = mssql_result($resultsSet,0,0);
		return $rejectionReason;
	} 
	
	function JsonConvertion ($Array, $type)
	{
		unset($str);
		$str = "[";
		foreach($Array as $val)
		{
			if($type=='string')
			{
				$str .= '"'.$val.'",';
			}
			else
			{
				$str .= $val.',';	
			}
		}
		$str = substr($str, 0,-1);
		$str .= "]";
		return $str;
		
	} 
	
	function JsonKeyValueConvertion($Array, $type)
	{
		 
		foreach($Array as $key=>$val)
		{
			$str .= '["'.$key.'",';
			if($type=='string')
			{
				$str .= '"'.$val.'"';
			}
			else
			{
				$str .= $val.' ';	
			}
		
		}
		$str = substr($str, 0,-1);
		$str .= "]";
		return $str;
	}
	
	function JsonKeyValueConvertionDimentions($Array, $type)
	{
		unset($str);
		
		
		foreach($Array as $key=>$val)
		{
			$str .= '["'.$key.'",';
			if($type=='string')
			{
				$str .= '"'.$val.'"';
			}
			else
			{
				$str .= $val.' ';	
			}
			$str .= "] , ";
		
		}
		$str = substr($str, 0,-1);
		return $str;
	}
	function startEndDates($startDate, $endDate , $type , $measure , $period , $employeID, $defaultUrl)
	{
	$todayDate = date('m/d/Y');
	$daysBefore90 = date ('m/d/Y', strtotime('-3 months', strtotime($todayDate)));
	
   $dateString = '<div style="float:left;">Start Date &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="startDate" id="startDate" value="'.$startDate.'"  style="width:75px;" readonly="readonly"/><img id="imgStartDateClass" alt="Choose Production Date" onclick="javascript:displayCalendar(document.getElementById(\'startDate\'),\'mm/dd/yyyy\',document.getElementById(\'imgStartDateClass\'))" src="https://'.$_SERVER['HTTP_HOST'].'/Include/images/calendar.gif" style="border-top-width: 0px; border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" /></div>';
   
   $dateString .= '<div style="float:left; margin-left:10px;">End Date &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="endDate" id="endDate" value="'.$endDate.'"  style="width:75px;" readonly="readonly"/><img id="imgEndDateClass" alt="Choose Production Date" onclick="javascript:displayCalendar(document.getElementById(\'endDate\'),\'mm/dd/yyyy\',document.getElementById(\'imgEndDateClass\'))" src="https://'.$_SERVER['HTTP_HOST'].'/Include/images/calendar.gif" style="border-top-width: 0px; border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" /></div>';
   
   $dateString .= '<div style="float:left; margin-left:10px;">
   					<input type="button" name="search" id="search" value="Search" onClick="return dateValidation(\'startDate\' , \'endDate\' , \''.$daysBefore90.'\' , \''.$type.'\' , \''.$measure.'\' , \''.$period.'\' , \''.$employeID.'\'  , \''.$defaultUrl.'\' ); return false;" />
   				   </div>';

	return $dateString;
                
	 }
	 
	 
	 function getLocations($restrictedLocations)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[rnet_spGetLocations] '".$restrictedLocations."' ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 
	
	 
	 
	function getPositions()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);

		$sqlQuery = " EXEC Rnet.dbo.[rnet_spGetPositions] '%' ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 
	
	
	
	function getCoaches()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = "EXEC Rnet.dbo.[rnet_spGetEmployeesLocationWise] '801'";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 
	
	
	function getKPIs($coachingClient)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = "EXEC RNet.dbo.[report_spGetCoachingKPIs] '$coachingClient' "; 

		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 
	
	
	function getKPIsByEntity($entityID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($rowsLocNum);
		
		$sqlQuery = "EXEC RNet.dbo.[report_spGetCoachingKPIsByEntity] '$entityID' "; 
		$resultsSet = $this->ExecuteQuery($sqlQuery);

		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			return $this->bindingInToArray($resultsSet);
		}
	} 
	
	
	function getBehaviors($coachingClient,$type)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachingBehaviors] '$coachingClient','$type' ";

		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 


	function getBehaviorsByEntity($entityID,$type)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachingBehaviorsByEntity] '$entityID','$type' ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			return $this->bindingInToArray($resultsSet);
		}
	} 

	function getMethods($coachingClient)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachingMethods] '$coachingClient' ";
		
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 
	
	function getMethodsByEntity($entityID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachingMethodsByEntity] '$entityID' ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			return $this->bindingInToArray($resultsSet);
		}
	} 
	
	
	function getActionPlans($methodID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetActionPlans] '$methodID' ";
				
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 

	
	
	
	function getCoachForms()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachForms]  ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	} 
	
	
	function getEvaluationMethods()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachingEvaluationMethods]  ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	}

	function getMainBehaviorCoachs()
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetCoachingMainBehaviorCoachs] ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		return $this->bindingInToArray($resultsSet);
	}

	
	function setCoachingSessionData($loggedEmployeeID, $client, $coachingLob)
	{	
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spViewEmployeeCoachingSessions]  '$loggedEmployeeID', '$client', '$coachingLob' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->coachingSessionData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	function getCoachingSessionData()
	{
		return $this->coachingSessionData;	
	} 
	 
	 
	function setEmployeeLifeCycleData($employeeID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spEmployeeLifecycle] '".$employeeID."' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery, '', 'LIVE');
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employeeLifeCycleData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	function getEmployeeLifeCycleData()
	{
			return $this->employeeLifeCycleData;
	}
	
	function setEmployeeStructureHistory($employeeID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spEmployeeStructureHistory] '".$employeeID."' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery, '', 'LIVE');
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employeeStructureData = $this->bindingInToArray($resultsSet);
		}
	} 
	
	function getEmployeeStructureHistory()
	{
			return $this->employeeStructureData;
	}
	 
	function numberFormat($string , $decimals = NULL)
	{
		if(empty($decimals))
		{
			$decimals = 2;	
		}
		
		echo $string;
	}
	
	function numberFormatReturn($string , $decimals = NULL)
	{
		if(empty($decimals))
		{
			$decimals = 2;	
		}
		
		return number_format($string,$decimals, '.', '');
	}
	
	function getParameterTitle($period, $measure , $type, $lobID = NULL)
	{
		$title = 	$type.' : Date wise '.$measure.' details';
		$periodParameter = 	'D';
		
		if($period=='WTD')
		{
			$title = $type.' : Week-to-Date '.$measure.' details';	
			$periodParameter = 'W';
			$subTotal = $lobID.' '.$measure.' Trend';
		}
		
		if($period=='MTD')
		{
			$title = $type.' : Month-to-Date '.$measure.' details';	
			$periodParameter = 'M';
			$subTotal = $lobID.' '.$measure.' Trend';
		}
		$this->parameters[0] = $title;
		$this->parameters[1] = $periodParameter;
		$this->parameters[2] = $subTotal;
		return $this->parameters;
		
	}

	function getData($employeID, $startDate, $periodParameter, $currentSP, $clientName = NULL , $pointingTo)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		
		$sqlQuery = " EXEC ".$currentSP." ".$employeID.", '".$startDate."', '".$periodParameter."' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery, '', 'LIVE');
		if($pointingTo=='LIVE')
		{
			$resultsSet = $this->ExecuteQuery($sqlQuery, '', 'LIVE');	
		}
		else
		{
			$resultsSet = $this->ExecuteQuery($sqlQuery);
		}

		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->inboundCallesHandled = $this->bindingInToArray($resultsSet);
		}
		
		return $this->inboundCallesHandled;
	}
	
	function getEmployeeNameAvayaID($employeID)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spGetEmployeeNameandAvayaID]  '$employeID' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery, '', 'LIVE');
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->employeeNameAndAvaya = $this->bindingInToArray($resultsSet);
		}
		
		return $this->employeeNameAndAvaya;
	}
	
	function getUnEndCoachingSessions($employeeID, $client, $lob)
	{
		
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		
		
		$sqlQuery = " EXEC Rnet.dbo.[report_spCheckEmployeeActiveCoachingSession] '$employeeID', '$client', '$lob' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->unEndCoachings = $this->bindingInToArray($resultsSet);
		}
		
		return $this->unEndCoachings;
	}
	
	// added by BhanuPrakash
	function getSectionsListByClient($clientName, $lob_id = '%')
	{
		unset($mainSections);
		$query = " EXEC Rnet.dbo.[rnet_spAgentScoreCardSectionsByClient] '".$clientName."' , '".$lob_id."' ";
		$resultsSet = $this->ExecuteQuery($query);
		while($spQrs = mssql_fetch_assoc($resultsSet))
		{
			$mainSections[] = $spQrs;	
		}
		
		return $mainSections;
		
		
	}
	
	function getSectionsListById($scoreCardClientID)
	{
		unset($mainSections);
		$query = " EXEC Rnet.dbo.[rnet_spAgentScoreCardSectionsByID] '".$scoreCardClientID."' ";
		$resultsSet = $this->ExecuteQuery($query);
		while($spQrs = mssql_fetch_assoc($resultsSet))
		{
			$mainSections[] = $spQrs;	
		}
		return $mainSections;
	}
	
	
	function getDataOfEachSection($sp , $pointingTo)
	{
		if($pointingTo=='LIVE')
		{
			$resultsSet = $this->ExecuteQuery($sp, '', 'LIVE');
		}
		else
		{
			$resultsSet = $this->ExecuteQuery($sp);
		}
		
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			return $this->bindingInToArray($resultsSet);
		}
	
	}
	
	function setMappingWithSections($scoreCardClientID)
	{
		unset($requiredResultsSet);
		$query = " EXEC Rnet.dbo.[rnet_spAgentScoreCardMappingWithSections] '".$scoreCardClientID."' ";
		$resultsSet = $this->ExecuteQuery($query);
		
		while($rSRow = mssql_fetch_assoc($resultsSet))
		{
			$requiredResultsSet[$rSRow['scoreCardSectionName']][] = $rSRow;
		}
		
		return $requiredResultsSet;
	}
	
	
	public function setMappingWithSectionHeadings($scoreCardClientID)
	{
		unset($requiredResultsSet);	
		$query = " EXEC Rnet.dbo.[rnet_spAgentScoreCardMappingWithSectionHeadings] '".$scoreCardClientID."' ";
		$resultsSet = $this->ExecuteQuery($query);
		
		while($rSRow = mssql_fetch_assoc($resultsSet))
		{
			$requiredResultsSet[$rSRow['scoreCardSectionName']][] = $rSRow;

		}
		return $requiredResultsSet;
	}
	
	
	function getConentBySectionWise($getSections, $dataSections, $allSectionsData, $allSectionsHeadings, $notificationIndicator , $trStryleArray  , $weektoDate , $indicatorArray , $sectionName, $requestedDate, $employeeID, $divID)
	{
		
		unset($returnString);
		$returnString = '<table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" width="99%;">
						<thead>
						<tr>';
          
				if(!empty($allSectionsHeadings[$sectionName]))
				{
					$columnNumber = 0;
					foreach($allSectionsHeadings[$sectionName] as $allSectionsHeadingsVal)
					{
						$returnString .= '<th align="center" colspan="'.$allSectionsHeadingsVal['colSpan'].'"><strong>'.$allSectionsHeadingsVal['columnHeading'].'</strong></th>';
						$columnNumber +=  $allSectionsHeadingsVal['colSpan'];
					}
				}
                
           $returnString .=   '</tr></thead>';
		   $returnString .= '<tbody>';

				$countOf = count($dataSections[$sectionName]);
				if($countOf>0)
				{
					$countOf = count($dataSections[$sectionName]);
					$countOfBelow = count($allSectionsData[$sectionName]);
					$g=0;
					$rows = 1;
					for($i=0; $i<$countOf; $i++)
					{
						if($g!=0 && $g%2==0)
						{
							$g=0;	
						}

						if($rows==1)
						{
							$returnString .= "<tr style=\"height:20px; background-color:#D0D8E8;\" class=".$divID.">";
						}
						else
						{
							$returnString .=  $trStryleArray[$g];
						}
						
						
						for($k=0; $k<$countOfBelow; $k++)
						{
							
							$sectionDetailID = $allSectionsData[$sectionName][$k]['sectionDetailID'];
							$height = $allSectionsData[$sectionName][$k]['height'];
							$width = $allSectionsData[$sectionName][$k]['width'];
							$isHavingBar = $allSectionsData[$sectionName][$k]['isHavingBarGraph'];
							$isHavingPie = $allSectionsData[$sectionName][$k]['isHavingPieGraph'];
							
							if($rows==1 && $k==0 && $countOf>1)
							{
								unset($randowmTableID);
								unset($sectionId);
								$randowmTableID = date('ymdhis');
								$sectionId = str_replace(' ','',$sectionName);
								$randowmTableID .= $randowmTableID.$sectionId;
								$returnString .= '<td style="text-align:'.$allSectionsData[$sectionName][$k]['align'].'; padding-left:20px; background-image:url(includes/images/plus.gif); background-repeat:no-repeat; background-position:left;" class="locked" id="'.$randowmTableID.'" onclick="return toggleThisUp(this.id, '.$divID.'); return false;" title="expand">';
							}
							else
							{
								$returnString .= '<td style="text-align:'.$allSectionsData[$sectionName][$k]['align'].';">';
							}
							
							switch($allSectionsData[$sectionName][$k]['resultsSetMapping'])
							{
								case 'NotificationImage':
								
									if($dataSections[$sectionName][$i]['Notifications']>0)
									{
										$returnString .= '<a href="https://'.$_SERVER['HTTP_HOST'].'/Clients/Results/viewReviewMessagesPage.php"><img src="'.$notificationIndicator[$g].'" width="30" height="20" border="0" /></a>';
									}
									else
									{
										$returnString .= '&nbsp;';
									}	
									
								break;
								
								case 'scoreCardLifeCycle.php':
								$returnString .= '<a href="scoreCardLifeCycle.php">View</a>';
								break;
								
								
								case 'Period':
								$period = $dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']];
								if(array_key_exists($dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']], $weektoDate))
								{
									/* This block is for week and month */
									if($allSectionsData[$sectionName][$k]['isDisplayInModalWindowReport']!='Y')
									{
										$returnString .= $weektoDate[$dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']]];
									}
									else
									{
										$returnString .= '<a href=# onclick="return populateDetails(\''.$period.'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$sectionDetailID.'\', \''.$height.'\', \''.$width.'\', \'report\',\'\' , \''.$isHavingBar.'\' , \''.$isHavingPie.'\'); return false;">';
										$returnString .= $weektoDate[$dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']]];
										$returnString .= '</a>';
									} // 
									
								}
								else
								{
									// This block is for only Date.
									if($allSectionsData[$sectionName][$k]['isDisplayInModalWindowReport']!='Y')
									{
										$returnString .= $dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']];
									}
									else
									{
										$returnString .= '<a href=# onclick="return populateDetails(\''.$period.'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$sectionDetailID.'\', \''.$height.'\', \''.$width.'\', \'report\',\'\' , \''.$isHavingBar.'\' , \''.$isHavingPie.'\'); return false;">';
										$returnString .= $dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']];
										$returnString .= '</a>';
										
									}
								}
								
								
								break;
								
								default:
								if($allSectionsData[$sectionName][$k]['isIndicator']=='Y')
								{
									
									$returnString .= '<img src="'.$indicatorArray[$g][$dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']]].'" width="20" height="20" />';
								}
								else
								{
									if($allSectionsData[$sectionName][$k]['isDisplayInModalWindowReport']!='Y')
									{
										$returnString .= $dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']];
									}
									else
									{
										$returnString .= '<a href=# onclick="return populateDetails(\''.$period.'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$sectionDetailID.'\', \''.$height.'\', \''.$width.'\', \'report\',\'\' , \''.$isHavingBar.'\' , \''.$isHavingPie.'\'); return false;">';
										$returnString .= $dataSections[$sectionName][$i][$allSectionsData[$sectionName][$k]['resultsSetMapping']];
										$returnString .= '</a>';	
									}
								}
								break;
							} // eof of switch
							
							$returnString .= '</td>';
						} // end Inner for
						
						
						$returnString .= '</tr>';
					$g++; 
					$rows++; } // end Outer for.
					
					
				}
				else
				{
					$returnString .= '<tr><td style="text-align:center;" colspan = "'.$columnNumber.'">No data found</td></tr>';	
				}
			
            
        $returnString .= '</tbody></table>';
		 return $returnString;
		
	}

	public function getStoreProcedureDetails($sectionDetailID)
	{
		
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		unset($detailsArray);
		
		
		$sqlQuery = " 	EXEC Rnet.dbo.[rnet_spAgentScoreStoreProcedureDetails] '".$sectionDetailID."'  ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->detailsArray = $this->bindingInToArray($resultsSet);
			
			
		}
		
		return $this->detailsArray ;
		
	}
	
	public function getdrillDownHeadersForReport($sectionDetailID , $reportType = NULL)
	{
		
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		unset($detailsArray);
		unset($filter);
		
		
		$sqlQuery = " EXEC Rnet.dbo.[rnet_spAgentScoreCardDrillDownHeadersForReport] '".$sectionDetailID."' , '".$reportType."' ";
		
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->detailsArray = $this->bindingInToArray($resultsSet);
			
			
		}
		
		return $this->detailsArray ;
	}
	
	public function generateColumnHeadings($drilldownHeaders , $periodParameter)
	{
		unset($returnSTring);
		unset($countArray);
		$class = 'ColumnHeader1';
		
		$countArray = count($drilldownHeaders);
		$returnSTring = '<tr>';
		for($kk=0; $kk<$countArray; $kk++)
		{
			if($kk==0)
			{
				$class = 'ColumnHeader1 locked';	
			}
			if($periodParameter=='D' && trim($drilldownHeaders[$kk]['ColumnHeading'])=='&nbsp')
			{
				$drilldownHeaders[$kk]['ColumnHeading'] = 'NotDisplay';	
			}
			
			if($drilldownHeaders[$kk]['ColumnHeading']!='NotDisplay')
			{
				$returnSTring .= '<th class="'.$class.'">'.$drilldownHeaders[$kk]['ColumnHeading'].'</th>';
			}
		}
		$returnSTring .=  '</tr>';
		
		return $returnSTring;
	} // eof
	
	
	function getdrillDownMappingForReport($sectionDetailID , $reportType = NULL)
	{
		unset($sqlQuery);
		unset($resultsSet);
		unset($rowsLocNum);
		unset($filter);
		unset($orderBy);
		unset($detailsArray);
		
		
		unset($filter);

		
		$sqlQuery = " EXEC Rnet.dbo.[rnet_spAgentScoreCardDrillDownDetailsForReport] '".$sectionDetailID."' , '".$reportType."' ";
		$resultsSet = $this->ExecuteQuery($sqlQuery);
		$rowsLocNum = mssql_num_rows($resultsSet);
		if($rowsLocNum>=1)
		{
			$this->detailsArray = $this->bindingInToArray($resultsSet);
			
			
		}
		
		return $this->detailsArray ;
			
	} // eof
	
	public function generateColumnConent($drilldownMappings,  $periodParameter, $requiredData, $trStryleArray,$period, $requestedDate,  $employeeID,  $sectionDetailID,  $height, $width,  $reportType, $iconf, $isBar , $isPie)
	{
		
		unset($drilldownMappingsCalculatedFiled);
		unset($drilldownMappingsCount);
		unset($requiredDataCount);
		$drilldownMappingsCount = count($drilldownMappings); // inner
		$requiredDataCount = count($requiredData); // outer 
		
		for($z=0; $z<$drilldownMappingsCount; $z++)
		{
			if($drilldownMappings[$z]['isCalculatedField']=='Y')
			{
				$drilldownMappingsCalculatedFiled[$z] = $drilldownMappings[$z];
			}
		}
		
		unset($returnString);
		$g = 0;
		$s =0;
		for($i=0; $i<$requiredDataCount; $i++)
		{
			if($s!=0 && $s%2==0)
			{
				$s=0;	
			}
			
								
			for($k=0; $k<$drilldownMappingsCount;$k++)
			{
				
				if(trim($requiredData[$i][$drilldownMappings[$k]['ResultsSetMapping']])!='%')
				{
					unset($indicatorType);
					$indicatorType = $drilldownMappings[$k]['dataType'];
					if($indicatorType=='indicator')
					{
						if($requiredData[$i]['initialDisplay']==0)
						{
							$finalValue = '<img src="'.$this->indicatorArray[$s][$requiredData[$i][$drilldownMappings[$k]['ResultsSetMapping']]].'" width="20" height="20" />';
						}
						else
						{
							$finalValue = '<img src="'.$this->indicatorArray[2][$requiredData[$i][$drilldownMappings[$k]['ResultsSetMapping']]].'" width="20" height="20" />';
						}
						
					}
					else
					{
						$finalValue = $requiredData[$i][$drilldownMappings[$k]['ResultsSetMapping']];				 
					}
					
									 
				}
				else
				{
					$finalValue = '&nbsp;';	
				}
				
				unset($indVar);
				$indVar = $requiredData[$i][$drilldownMappingsCalculatedFiled[$k]['ResultsSetMapping']];
							
				switch(@$drilldownMappingsCalculatedFiled[$k]['ResultsSetMapping'])
				{
					case 'LOB':
					case 'Script Name':
					$lobName = $requiredData[$i][$drilldownMappingsCalculatedFiled[$k]['ResultsSetMapping']];
						switch($requiredData[$i]['initialDisplay'])
						{
							case '1':
							
								if($indVar=='Totals')
								{
									$returnString .= $trStryleArray['4'];	
									$firstColmn =  $requiredData[$i][$drilldownMappings[$k]['ResultsSetMapping']];
									$returnString .= '<td style="text-align:left; padding-left:20px;" class="locked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';
								}
								else if($indVar!='Totals')
								{

									$firstColmn =  $requiredData[$i][$drilldownMappingsCalculatedFiled[$k]['ResultsSetMapping']].' - Subtotals';
									$returnString .=  $trStryleArray['3'];
									unset($tableId);
									$tableId = str_replace(' ', '',$requiredData[$i][$drilldownMappingsCalculatedFiled[$k]['ResultsSetMapping']]);
									 $returnString .= '<td style="text-align:left; padding-left:20px; background-image:url(includes/images/plus.gif); background-repeat:no-repeat; background-position:left;" class="locked" id="'.$tableId.'" onclick="return toggleThis(this.id); return false;" title="expand">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';
								}
							
							break;
							default:
							
								$firstColmn =  $requiredData[$i][$drilldownMappingsCalculatedFiled[$k]['ResultsSetMapping']];
								if($g!=0 && $g%2==0)
								{
									$g=0;	
								}
					
								$returnString .= $trStryleArray[$g];
								$g++;
								$returnString .= '<td style="text-align:left; padding-left:20px;" class="locked">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$firstColmn.'</td>';
							break;
							
						} // eof inner switch 
					break;
					
										
					case 'image':
					
					if($periodParameter!='D')
					{
							$returnString .= '<td style="text-align:center;">';
							if($requiredData[$i]['LOB']=='Totals' && $isPie=='Y')
							{
								switch($sectionDetailID)
								{
									case '29': // Wellcare -> Quality Measure.
									$returnString .= '<a href=# onclick="return populateDetails(\''.$period.'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$sectionDetailID.'\', \''.$height.'\', \''.$width.'\', \'bar\', \''.$lobName.'\' , \''.$isBar.'\' , \''.$isPie.'\'); return false;"><img src="includes/images/bar_graph.png" border="0" style="width:20px; height:20px; cursor:hand;" title="Click here to view chart" /></a>';
									break;
									
									default:
									$returnString .= '<a href=# onclick="return populateDetails(\''.$period.'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$sectionDetailID.'\', \''.$height.'\', \''.$width.'\', \'pie\', \''.$lobName.'\' , \''.$isBar.'\' , \''.$isPie.'\'); return false;"><img src="includes/images/'.$iconf.'" border="0" style="width:30px; height:30px; cursor:hand;" title="Click here to view chart" /></a>';
									break;
								}
							}
							else if($requiredData[$i]['Date']=='Subtotals' && $isBar=='Y')
							{
								$returnString .= '<a href=# onclick="return populateDetails(\''.$period.'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$sectionDetailID.'\', \''.$height.'\', \''.$width.'\', \'bar\', \''.$lobName.'\' , \''.$isBar.'\' , \''.$isPie.'\'); return false;"><img src="includes/images/bar_graph.png" border="0" style="width:20px; height:20px; cursor:hand;" title="Click here to view chart" /></a>';
							}
							
							else
							{
								$returnString .= '&nbsp;';	
							}
							
							
						$returnString .= '</td>';
					}
					break;
					
					default:
					if(empty($finalValue))
					{
						$finalValue = '&nbsp;';	
					}
					$returnString .= '<td style="text-align:center">'.$finalValue.'</td>';
									
					
					break;
					
				} // eof outer swithch 
					
				} // inner
				
			$returnString .= '</tr>';
			if($requiredData[$i]['initialDisplay']==0) // means each row with out subTotals
			{
				$s++;
			}
		} // outer
		
		return $returnString;
		
	}// eof
}
?>