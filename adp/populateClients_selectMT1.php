<?php 
session_start();
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class/config.inc.php');
//$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
//mssql_select_db(MSSQL_DB);

  include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');

$headerObj->jsSource = $jsFilesAjax;
$jsFiles = $headerObj->getJsSourceFiles();
echo $jsFiles;

$empID = $_REQUEST['employID'];
$type = $_REQUEST['type'];
$posID = $_REQUEST['posID'];
$posType = $_REQUEST['posType'];

if($type=='Clients')
{
	$sqlQuery = "	SELECT	DISTINCT
						b.[description] + ' - ' + a.lob_id + ' - ' + c.[channel] AS dispName,
						b.clientName,
						a.lob_id,
						c.channelID
					FROM	
						ctlVDNSources a WITH (NOLOCK) 
					JOIN
						ctlClients b WITH (NOLOCK) 
					ON 
						a.clientName = b.clientName
					JOIN
						ctlChannels c WITH (NOLOCK) 
					ON 
						a.channelID = c.channelID
					WHERE
						a.clientName <> 'AT&T' 
					ORDER BY 
						b.[description] + ' - ' + a.lob_id + ' - ' + c.[channel],
						b.clientName,
						a.lob_id,
						c.channelID ";

	//$resQry = mssql_query($sqlQuery) or die(mssql_get_last_message());
	$resQry = $RDSObj->execute($sqlQuery);
	
	while($rows = mssql_fetch_assoc($resQry))
	{
		$reqresultraw[$rows['clientName']][] = $rows;
	}
	/*echo '<pre>';
	print_r($reqresultraw);
	echo '</pre>';*/
	
	// Get Primary Client.
		
		unset($getCltDescemployee);
		unset($rstGetClntDescemployee);
		unset($isPrimaryClient);
		unset($isPrimaryClientAE);
		
		$getCltDescemployee = " SELECT DISTINCT clientName FROM  Rnet.dbo.prmEmployeePositionClients WITH (NOLOCK) WHERE employeeID = '".$empID."' AND isPrimary = 'Y' and positionID = ".$posID." ";		
		//$rstGetClntDescemployee = mssql_query($getCltDescemployee, $db) or die(mssql_get_last_message());
		$rstGetClntDescemployee = $RDSObj->execute($getCltDescemployee);
		$isPrimaryClient = mssql_result($rstGetClntDescemployee,0,0);
		//echo 'xxxxxxxxxxx'.$isPrimaryClient;
		
		
	?>
    
	<table width="100%" border="0" style="border-collapse:collapse;">
	<?php 
	$tdcount = 1;
	foreach($reqresultraw as $reqresultrawKey=>$reqresultrawVal) 
	{ 
		if($tdcount>1)
		{
			$tableStyle02062012 = 'style="margin:0px; padding:0px; display:none; width:100%;"';
			$imgPath = '<img src="../../../SkillChangeRequestPortal/includes/asc.gif" />';
			
		} else {
			$tableStyle02062012 = 'style="margin:0px; padding:0px; display:block; width:100%;"';	
			$imgPath = '<img src="../../../SkillChangeRequestPortal/includes/desc.gif"  />';
			
		}
		$hdnClients .= $reqresultrawKey.',';
		
		$cntQry = " SELECT 
						COUNT(*) AS activeItems 
					FROM 
						RNet.dbo.prmEmployeePositionClients WITH (NOLOCK) 
					WHERE 
						employeeID = '$empID' 
					AND 
						clientName = '".$reqresultrawKey."' 
					AND
						positionID = '$posID'
					AND 
						endDate IS NULL";
		//$rstCntQry = mssql_query($cntQry, $db) or die(mssql_get_last_message());
		$rstCntQry = $RDSObj->execute($cntQry);
		$activeItems = mssql_result($rstCntQry,0,0);
		
		$getCltDesc = " SELECT description FROM  ctlClients WITH (NOLOCK) WHERE clientName = '".$reqresultrawKey."' ";
		//$rstGetClntDesc = mssql_query($getCltDesc, $db) or die(mssql_get_last_message());
		$rstGetClntDesc = $RDSObj->execute($getCltDesc);
		$clntDesc = mssql_result($rstGetClntDesc,0,0);
		
		
		$isPrimAEView = " SELECT DISTINCT clientName FROM  Rnet.dbo.prmEmployeePositionClients WITH (NOLOCK) WHERE employeeID = '".$empID."' AND isPrimaryAE = 'Y' and positionID = ".$posID." AND clientName = '".$reqresultrawKey."'  ";		
		//$rstIsPrimAEView = mssql_query($isPrimAEView, $db) or die(mssql_get_last_message());
		$rstIsPrimAEView = $RDSObj->execute($isPrimAEView);
		$isPrimaryClientAE = mssql_result($rstIsPrimAEView,0,0);
		?>
				<tr>
					<td class="ColumnHeader" style="text-align:center;" colspan="4" onclick="return lobToggleEdit('<?php echo $reqresultrawKey;?>'); return false;">

<div id="img<?php echo $reqresultrawKey;?>" style="float:left; margin-left:5px; vertical-align:middle;">
<?php echo $imgPath; ?>
</div>

<?php if($posType=='AE') { ?>                    
<div style="float:left;margin-left:13px;text-align:center;">
<?php } else {?>
<div style="float:left;margin-left:350px;text-align:center;">
<?php
}
?>
<?php
echo '&nbsp; &nbsp;'.$clntDesc; if(!empty($activeItems)) { echo ' ('.$activeItems.' active items)'; }?>
</div>
<?php
if($posType=='AE') { ?>

<div style="float:right;margin-right:10px;">
<input type="checkbox" name="chkIsPrimaryAE[]" id="chkIsPrimaryAE"  value="<?php echo $reqresultrawKey;?>" <?php if(!empty($isPrimaryClientAE)) {?> checked <?php }?> onclick="return validateIsPrimaryAE(this.value,'edit','<?php echo $posID;?>'); return false;" />&nbsp;IS PRIMARY AE?</div>

<?php } ?>

<div style="float:right;">
<input type="radio" name="chkisPrimary[]" value="<?php echo $reqresultrawKey;?>" onclick="return storedisprimaryValue('<?php echo $reqresultrawKey;?>'); return false;" id="<?php echo 'DivDiv'.$reqresultrawKey;?>" <?php echo ($isPrimaryClient==$reqresultrawKey?'checked="checked"':'');?>/>&nbsp;Is Primary Client?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
</td>
                    
				</tr>
				<tr style="margin:0px; padding:0px;">
				<td>
				<table id="<?php echo $reqresultrawKey;?>" <?php echo $tableStyle02062012; ?>> 
				
				<tr class="ColumnHeader">
					
					<td width="34%"><a href="#" onclick="completeAll('<?php echo $reqresultrawKey;?>');">Complete All</a>
					&nbsp;&nbsp;<a href="#" onclick="selectNone('<?php echo $reqresultrawKey;?>');">Select None</a>
                    </td>
					
					<td width="4%" style="text-align:left;"> 
					<input type="checkbox" name="<?php echo $reqresultrawKey;?>" onclick="checkAll('<?php echo $reqresultrawKey;?>', this.checked);" />
					</td>
					
					 <td width="31%">Effective Date&nbsp;
                     
                     <?php
					 $idName = str_replace(array(' ', '&', '/', '!', '.', ','), '_', $reqresultrawKey);
						 $htmlTextElement->accesskey = 'true';
						$htmlTextElement->type = 'text';
						$htmlTextElement->name = 'effectiveDate_'.$idName;
						$htmlTextElement->id = 'effectiveDate_'.$idName;
						$htmlTextElement->readonly=TRUE;
						$htmlTextElement->style='width:75px;';
						$htmlTextElement->value = '';
						$effDate = $htmlTextElement->renderHtml();
						echo $effDate;
						$htmlTextElement->resetProperties();
					 ?>
                    
				 
				</td>
				
				 <td width="31%">End Date&nbsp;
                 
                 <?php
				$htmlTextElement->accesskey = 'true';
				$htmlTextElement->type = 'text';
				$htmlTextElement->name = 'endDate_'.$idName;
				$htmlTextElement->id = 'endDate_'.$idName;
				$htmlTextElement->readonly=TRUE;	
				$htmlTextElement->value = '';
				$htmlTextElement->style='width:75px;';
				$htmlTextElement->onchange = 'greaterFunc("'.$idName.'", "client")';
				$endDate = $htmlTextElement->renderHtml();
				echo $endDate;
				$htmlTextElement->resetProperties();
				//echo 'End Date'. $endDate;
				?>
                
				</td>  
									  
					</tr>
	
				<?php 
				$innerCount = 0;
				foreach($reqresultrawVal as $finalVal)
				{
					$clintsSelQry = "SELECT 
										clientName,
										lob_id,
										channelID,
										CONVERT(VARCHAR(10),effectiveDate,101) effectiveDate,
										CONVERT(VARCHAR(10),endDate,101) endDate 
									FROM 
										RNet.dbo.prmEmployeePositionClients WITH (NOLOCK) 
									WHERE 
										employeeID = '$empID'  
									AND 
										clientName = '".$reqresultrawKey."' 
									AND
										lob_id = '".$finalVal['lob_id']."'
									AND
										channelID = '".$finalVal['channelID']."' 
									AND
										positionID = '$posID'
									AND 
										endDate IS NULL";

					//$rstClintsSelQry = mssql_query($clintsSelQry, $db) or die(mssql_get_last_message());
					$rstClintsSelQry = $RDSObj->execute($clintsSelQry);
					$viewStCli = mssql_result($rstClintsSelQry,0,0);
					$viewStLobId = mssql_result($rstClintsSelQry,0,1);
					$viewStChnId = mssql_result($rstClintsSelQry,0,2);
					$viewStCliEff = mssql_result($rstClintsSelQry,0,3);
					$viewStCliEnd = mssql_result($rstClintsSelQry,0,4);
					
					if($innerCount % 2) 
					{ //this means if there is a remainder 
						$color = 'bgcolor="#dee7d1"'; 
					} 
					else 
					{ //if there isn't a remainder we will do the else 
						$color = 'bgcolor="#eff3ea"'; 
					}
					?>

				<tr <?php echo $color;?>>
				<td width="34%"><?php echo $finalVal['dispName'];?></td>
				<td width="4%" style="text-align:left;">
				<input type="checkbox" name="<?php echo $reqresultrawKey;?>[]" id="chkDispName_<?php echo $finalVal['dispName'];?>" value="<?php echo $reqresultrawKey.'##'.$finalVal['lob_id'].'##'.$finalVal['channelID'].'****'.$finalVal['dispName'].'$$$'.$innerCount;?>" <?php if($viewStCli==$reqresultrawKey && $viewStLobId==$finalVal['lob_id'] && $viewStChnId==$finalVal['channelID']) {?> checked <?php }?> />
				</td>
				
				<td width="31%">Effective Date&nbsp;
                
                <?php
				
						$htmlTextElement->accesskey = 'true';
						$htmlTextElement->type = 'text';
						$htmlTextElement->name = 'effectiveDate_'.$idName.'[]';
						$htmlTextElement->id = 'effectiveDate_'.$idName.'_'.$innerCount;
						$htmlTextElement->readonly='true';
						$htmlTextElement->style='width:75px;';
						if(!empty($viewStCliEff)){
						$htmlTextElement->value = $viewStCliEff ;
						}else {
							$htmlTextElement->value = '' ;
							}
						$effDate_2 = $htmlTextElement->renderHtml();
						echo  $effDate_2;
						$htmlTextElement->resetProperties();
				?>
                
				</td>
				
				<td width="31%">End Date&nbsp;
                
                <?php
				$htmlTextElement->accesskey = 'true';
				$htmlTextElement->type = 'text';
				$htmlTextElement->name = 'endDate_'.$idName.'[]';
				$htmlTextElement->id = 'endDate_'.$idName.'_'.$innerCount;
				$htmlTextElement->readOnly=TRUE;
				$htmlTextElement->style='width:75px';
				$htmlTextElement->onchange = 'greaterFunc("'.$idName.'_'.$innerCount.'", "client")';
				if(!empty($viewStCliEnd)){
				$htmlTextElement->value = $viewStCliEnd ;
				}
				$endDate_2 = $htmlTextElement->renderHtml();
				echo $endDate_2;
				$htmlTextElement->resetProperties();
				?>
                
                		
            
            &nbsp;&nbsp;&nbsp;
            <a href="#" onclick="clearendDate('endDate_<?php echo $idName.'_'.$innerCount;?>'); return false;">Clear</a>
            
            
            
            
				</td> 
                                       
				</tr>
				<?php 
				$innerCount++;
				} // eof inner for loop
				?>
				</table>
				</td>
				</tr>
	<?php $tdcount++; } // eof of out for loop?>
    <input type="hidden" name="hdnRawClients" id="hdnRawClients" value="<?php echo substr($hdnClients,0,-1);?>">
    <input type="hidden" name="hdnRawLocs" id="hdnRawLocs" value="">	
	</table>
<?php
}
else
{
	$sqlLoc=" SELECT  
				[location],
				[description]
			FROM  
				[ctlLocations] WITH (NOLOCK)			
			WHERE 
				State IS NOT NULL AND location IN ".$RDSObj->UserDetails->Locations." 
			AND 
				active ='Y' AND switch ='N' 
			ORDER BY 
				description
			";
	//echo $sqlLoc;exit;
	//$rstLoc=mssql_query($sqlLoc, $db) or die(mssql_get_last_message());
	$rstLoc = $RDSObj->execute($sqlLoc);
	while($rowsLoc = mssql_fetch_assoc($rstLoc))
	{
		$locArray[] = $rowsLoc;
	}?>
    <table width="100%" border="0" style="border-collapse:collapse;">
    
        <tr class="ColumnHeader">
            <td width="34%">
            <a href="#" onClick="checkORuncheckAll(true);">Select All</a>&nbsp;&nbsp;
            <a href="#" onClick="checkORuncheckAll(false);">Select None</a>&nbsp;&nbsp;
            <a href="#" onclick="completeAllLoc(true);">Complete All</a></td>
           
            <td width="31%">Effective Date&nbsp;
            
            <?php
			$htmlTextElement->accesskey = 'true';
			$htmlTextElement->type = 'text';
			$htmlTextElement->name = 'effectiveDate_Loc';
			$htmlTextElement->id = 'effectiveDateLoc_';
			$htmlTextElement->readonly='true';
			$htmlTextElement->style='width:75px;';			
			$effDate_loc = $htmlTextElement->renderHtml();
			echo $effDate_loc;
			$htmlTextElement->resetProperties();
				?>
            </td>
            
            <td width="31%">End Date&nbsp;
            <?php
			$htmlTextElement->accesskey = 'true';
			$htmlTextElement->type = 'text';
			$htmlTextElement->name = 'endDate_Loc';
			$htmlTextElement->id = 'endDateLoc_';
			$htmlTextElement->readonly='true';
			$htmlTextElement->style='width:75px;';
			$htmlTextElement->onchange = 'greaterFunc("", "Loc")';
			$endDate_Loc = $htmlTextElement->renderHtml();
			echo $endDate_Loc;
			$htmlTextElement->resetProperties();
			//echo 'Effective Date&nbsp;'. $effDate_loc;
			?>
            
            
            </td>  
        
        </tr>
        
        <?php
		$viewStateLoc = '';
		$viewStateEff = '';
		$viewStateEnd = '';
		foreach($locArray as $locArrayKey=>$locArrayVal) 
		{ 
			$hdnLocations .= $locArrayVal['location'].',';

			$locSelQry = "	SELECT 
								location,
								CONVERT(VARCHAR(10),effectiveDate,101) effectiveDate,
								CONVERT(VARCHAR(10),endDate,101) endDate 
							FROM 
								RNet.dbo.prmEmployeePositionLocations WITH (NOLOCK) 
							WHERE 
								employeeID = '$empID'  
							AND 
								location = '".$locArrayVal['location']."'
							AND
								positionID = '$posID'
							AND
								endDate IS NULL ";
			//$rstLocSelQry = mssql_query($locSelQry, $db) or die(mssql_get_last_message());
			$rstLocSelQry = $RDSObj->execute($locSelQry);
			$viewStateLoc = mssql_result($rstLocSelQry,0,0);
			$viewStateEff = mssql_result($rstLocSelQry,0,1);
			$viewStateEnd = mssql_result($rstLocSelQry,0,2);
			
			if($i % 2) 
			{ //this means if there is a remainder 
				$color = 'bgcolor="#dee7d1"'; 
			} 
			else 
			{ //if there isn't a remainder we will do the else 
				$color = 'bgcolor="#eff3ea"'; 
			} 
		?>
        
        <tr <?php echo $color;?> >
        
            <td width="34%">
            <input type="checkbox" name="<?php echo $locArrayVal['location'];?>[]" id="<?php echo $locArrayVal['location'];?>" value="<?php echo $locArrayVal['location'].'****'.$locArrayVal['description'];?>" <?php if(!empty($viewStateLoc)) {  ?> checked="checked" <?php }?> />&nbsp;&nbsp;
            <?php echo $locArrayVal['description'];?>
            </td>
            
            <td width="31%">Effective Date&nbsp;
            <?php
			$htmlTextElement->accesskey = 'true';
			$htmlTextElement->type = 'text';
			$htmlTextElement->name = 'effectiveDate_'.$locArrayVal['location'].'[]';
			$htmlTextElement->id = 'effectiveDateLoc_'.$locArrayVal['location'];
			$htmlTextElement->readonly='true';
			$htmlTextElement->style='width:75px;';
			$htmlTextElement->value=(!empty($viewStateEff)) ? $viewStateEff : '';
			$effDate_loc2 = $htmlTextElement->renderHtml();
			echo $effDate_loc2;
			$htmlTextElement->resetProperties();
			//echo 'Effective Date&nbsp;'. $effDate_loc2;
			?>
            
           
            </td>
            
            <td width="31%">End Date&nbsp;
            <?php
		   	$htmlTextElement->accesskey = 'true';
			$htmlTextElement->type = 'text';
			$htmlTextElement->name = 'endDate_'.$locArrayVal['location'].'[]';
			$htmlTextElement->id = 'endDateLoc_'.$locArrayVal['location'];
			$htmlTextElement->readonly='true';
			$htmlTextElement->style='width:75px;';
			$htmlTextElement->onchange="greaterFunc(\'".$locArrayVal['location']."\','Loc');";
			$htmlTextElement->value=(!empty($viewStateEnd)) ? $viewStateEnd : '';
			$endDate_loc2 = $htmlTextElement->renderHtml();
			echo $endDate_loc2;
			$htmlTextElement->resetProperties();
			//echo 'End Date&nbsp;'. $endDate_loc2;
		   ?>
                      
            </td>  
        
        </tr>
        <?php 
		$i++;
		}?>	
        <input type="hidden" name="hdnRawLocs" id="hdnRawLocs" value="<?php echo substr($hdnLocations,0,-1);?>">	
        <input type="hidden" name="hdnRawClients" id="hdnRawClients" value="">
    <input type="hidden" name="hdnIVal" id="hdnIVal" value="<?php echo $i;?>">
    </table>
<?php
}?>

