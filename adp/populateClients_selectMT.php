<?php 
//session_start();
//include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
//include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");
//$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
//mssql_select_db(MSSQL_DB);
//echo 'test';exit;
//include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/lib/RDSData/DbConfig.php');
//$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');
include_once($_SERVER['DOCUMENT_ROOT'] . '/RNetIncludes/class1/common.config.inc.php');
$RDSObj = new RDSData(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD, MSSQL_DB, MSSQL_TYPE, MSSQL_PORT, '');


//$headerObj->jsSource = $jsFilesAjax;
//$jsFiles = $headerObj->getJsSourceFiles();
//echo $jsFiles;

$empID = $_REQUEST['employID'];
$type = $_REQUEST['type'];
$posID = $_REQUEST['posID'];
$posType = $_REQUEST['posType'];

$returnCountry = '';
if(!empty($_REQUEST['selectedLoc']))
{
	$selLocation = $_REQUEST['selectedLoc'];
	
	unset($sqlCountry);
	$sqlCountry = " SELECT country FROM ctlLocations WITH (NOLOCK) WHERE location = '$selLocation'  ";
	$rstCountry = $RDSObj->execute($sqlCountry); // or die(mssql_get_last_message());
	while($rowCountry = mssql_fetch_assoc($rstCountry))
	{
		$returnCountry = $rowCountry['country'];
	}
	mssql_free_result($rstCountry);
}

if($type=='Clients') 
{
	if(!empty($returnCountry) && $returnCountry=='United States of America')
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
							RNet.dbo.ctlADPClientCodes d WITH (NOLOCK)
						ON
							b.clientName = d.clientID
						JOIN
							ctlChannels c WITH (NOLOCK) 
						ON 
							a.channelID = c.channelID
						ORDER BY 
							b.[description] + ' - ' + a.lob_id + ' - ' + c.[channel],
							b.clientName,
							a.lob_id,
							c.channelID ";
	}
	else
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
						ORDER BY 
							b.[description] + ' - ' + a.lob_id + ' - ' + c.[channel],
							b.clientName,
							a.lob_id,
							c.channelID ";
	}
	$resQry = $RDSObj->execute($sqlQuery); // or die(mssql_get_last_message());
	
	while($rows = mssql_fetch_assoc($resQry))
	{
		$reqresultraw[$rows['clientName']][] = $rows;
	}
	/*echo '<pre>';
	print_r($reqresultraw);
	echo '</pre>';*/
	
	
	// Get Primary Client.
		if(!empty($empID))
		{
			$getCltDescemployee = " SELECT DISTINCT clientName FROM  Rnet.dbo.prmEmployeePositionClients WITH (NOLOCK) WHERE employeeID = '".$empID."' AND isPrimary = 'Y' and positionID = ".$posID." ";		
			$rstGetClntDescemployee = $RDSObj->execute($getCltDescemployee); //, $db) or die(mssql_get_last_message());
			$isPrimaryClient = mssql_result($rstGetClntDescemployee,0,0);
			//echo 'xxxxxxxxxxx'.$isPrimaryClient;
		}
		
		
	?>
	<table width="95%" border="0">
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
		if(!empty($empID))
		{
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
			$rstCntQry = $RDSObj->execute($cntQry); //, $db) or die(mssql_get_last_message());
			$activeItems = mssql_result($rstCntQry,0,0);
		}
		
		$getCltDesc = " SELECT description FROM  ctlClients WITH (NOLOCK) WHERE clientName = '".$reqresultrawKey."' ";
		$rstGetClntDesc = $RDSObj->execute($getCltDesc); //, $db) or die(mssql_get_last_message());
		$clntDesc = mssql_result($rstGetClntDesc,0,0);
		?>
				<tr style="cursor:pointer;">
                
					<td class="ColumnHeader" style="text-align:center;" colspan="4" onclick="return lobToggle('<?php echo $reqresultrawKey;?>'); return false;">

<div id="img<?php echo $reqresultrawKey;?>" style="float:left; margin-left:5px; vertical-align:middle;">
<?php echo $imgPath; ?>
</div>
                    
<?php if($posType=='AE') { ?>                    
<div style="float:left;margin-left:13px;text-align:center;">
<?php } else {?>
<!--<div style="float:left;margin-left:350px;text-align:center;">-->
<div style="float:left;margin-left:13px;text-align:center;">
<?php
}
?>
<?php
echo '&nbsp; &nbsp;'.$clntDesc; if(!empty($activeItems)) { echo ' ('.$activeItems.' active items)'; }?>
</div>
<?php
if($posType=='AE') { ?>

<div style="float:right;margin-right:10px;"><input type="checkbox" name="chkIsPrimaryAE[]" id="chkIsPrimaryAE"  value="<?php echo $reqresultrawKey;?>" onclick="return validateIsPrimaryAE(this.value,'add','<?php echo $posID;?>'); return false;" />&nbsp;IS PRIMARY AE?</div>

<?php } ?>

<div style="float:right;">
<input type="radio" name="chkisPrimary[]" value="<?php echo $reqresultrawKey;?>" onclick="return storedisprimaryValue('<?php echo $reqresultrawKey;?>'); return false;" id="<?php echo 'DivDiv'.$reqresultrawKey;?>" <?php echo ($isPrimaryClient==$reqresultrawKey?'checked="checked"':'');?>/>&nbsp;Is Primary Client?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
</td>
				</tr>
                
              
                
				<tr style="margin:0px; padding:0px;">
				<td>
				<table id="<?php echo $reqresultrawKey;?>" <?php echo $tableStyle02062012; ?> > 
				
				<tr style="background-color:#7ac143;">
					
					<td width="34%"><a href="javascript:;" onclick="completeAll('<?php echo $reqresultrawKey;?>');" style="text-decoration:none;">Complete All</a>
					&nbsp;&nbsp;<a href="javascript:;" onclick="selectNone('<?php echo $reqresultrawKey;?>');"  style="text-decoration:none;">Select None</a>
                    </td>
					
					<td width="4%" style="text-align:left;"> 
					<input type="checkbox" name="<?php echo $reqresultrawKey;?>" onclick="checkAll('<?php echo $reqresultrawKey;?>', this.checked);" />
					</td>
					
					 <td width="31%">Effective Date&nbsp;
				 <input name="effectiveDate_<?php echo $reqresultrawKey;?>" type="text" id="effectiveDate_<?php echo $reqresultrawKey;?>"  style="width: 75px" value=""  readonly="readonly" accesskey="true" />
				 			
				</td>
				
				 <td>End Date&nbsp;
				<input name="endDate_<?php echo $reqresultrawKey;?>" type="text" id="endDate_<?php echo $reqresultrawKey;?>"  style="width: 75px" value=""  readonly="readonly" onChange="greaterFunc('<?php echo $reqresultrawKey;?>','client');" accesskey="true" />
				
			
				</td>  
									  
					</tr>
	
				<?php 
				$innerCount = 0;
				foreach($reqresultrawVal as $finalVal)
				{
					if(!empty($empID))
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
						$rstClintsSelQry = $RDSObj->execute($clintsSelQry); //, $db) or die(mssql_get_last_message());
						$viewStCli = mssql_result($rstClintsSelQry,0,0);
						$viewStLobId = mssql_result($rstClintsSelQry,0,1);
						$viewStChnId = mssql_result($rstClintsSelQry,0,2);
						$viewStCliEff = mssql_result($rstClintsSelQry,0,3);
						$viewStCliEnd = mssql_result($rstClintsSelQry,0,4);
					}
					if($innerCount%2 == 0)
					{
						$color = '#dee7d1';
					}
					else
					{
						$color	= '#eff3ea';
					}
					?>

				<tr style="background-color:<?=$color?>">
				<td width="34%"><?php echo $finalVal['dispName'];?></td>
				<td width="4%" style="text-align:left;">
				<input type="checkbox" name="<?php echo $reqresultrawKey;?>[]" id="chkDispName_<?php echo $finalVal['dispName'];?>" value="<?php echo $reqresultrawKey.'##'.$finalVal['lob_id'].'##'.$finalVal['channelID'].'****'.$finalVal['dispName'].'$$$'.$innerCount;?>" <?php if($viewStCli==$reqresultrawKey && $viewStLobId==$finalVal['lob_id'] && $viewStChnId==$finalVal['channelID']) {?> checked <?php }?> />
				</td>
				
				<td width="31%">Effective Date&nbsp;
				 <input name="effectiveDate_<?php echo $reqresultrawKey;?>[]" type="text" id="effectiveDate_<?php echo $finalVal['dispName'];?>"  style="width: 75px" readonly="readonly" <?php if(!empty($viewStCliEff)) {?> value="<?php echo $viewStCliEff;?>" <?php }?> accesskey="true" />				 			
				</td>
				
				<td >End Date&nbsp;
				<input name="endDate_<?php echo $reqresultrawKey;?>[]" type="text" id="endDate_<?php echo $finalVal['dispName'];?>"  style="width: 75px" readonly="readonly" onchange="greaterFunc('<?php echo $finalVal['dispName'];?>','client');" <?php if(!empty($viewStCliEnd)) {?> value="<?php echo $viewStCliEnd;?>" <?php }?> accesskey="true" />							
            
            &nbsp;&nbsp;&nbsp;
            <a href="javascript:;" onclick="clearendDate('endDate_<?php echo $finalVal['dispName'];?>'); return false;">Clear</a>
            
            
				</td> 
                                       
				</tr>
				<?php 
				$innerCount++;
				} // eof inner for loop
				?>
				</table>
				</td>
				</tr>
	<?php $tdcount++; } // eof of out for loop ?>
    
    <input type="hidden" name="hdnRawClients" id="hdnRawClients" value="<?php echo substr($hdnClients,0,-1);?>">
    <input type="hidden" name="hdnIsPrimary" id="hdnIsPrimary" value="" />
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
				State IS NOT NULL AND location IN ".$Me->Locations." 
			AND 
				active ='Y' AND switch ='N' 
			ORDER BY 
				description
			";
	//echo $sqlLoc;exit;
	$rstLoc=$RDSObj->execute($sqlLoc); //, $db) or die(mssql_get_last_message());
	while($rowsLoc = mssql_fetch_assoc($rstLoc))
	{
		$locArray[] = $rowsLoc;
	}?>
    <table width="95%" border="0" style="border-collapse:collapse;" class="report">
    
        <tr style="background-color:#7ac143;">
            <td width="34%">
            <a href="javascript:;" onClick="checkORuncheckAll(true);">Select All</a>&nbsp;&nbsp;
            <a href="javascript:;" onClick="checkORuncheckAll(false);">Select None</a>&nbsp;&nbsp;
            <a href="javascript:;" onclick="completeAllLoc(true);">Complete All</a></td>
           
            <td width="31%">Effective Date&nbsp;
            <input name="effectiveDate_Loc" type="text" id="effectiveDateLoc_"  style="width: 75px" value=""  readonly="readonly" accesskey="true" />
            
            <!--<img id="imgEffectiveDateLoc" alt="Choose Start Date"
            onclick="javascript:displayCalendar(document.getElementById('effectiveDateLoc_'),'mm/dd/yyyy',document.getElementById('imgEffectiveDateLoc'))"
            src="https://<?=$_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px;
            border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" />-->
            </td>
            
            <td width="31%">End Date&nbsp;
            <input name="endDate_Loc" type="text" id="endDateLoc_"  style="width: 75px" value=""  readonly="readonly" onChange="greaterFunc('','Loc');" accesskey="true" />
            
            <!--<img id="imgEndDateLoc" alt="Choose Start Date"
            onclick="javascript:displayCalendar(document.getElementById('endDateLoc_'),'mm/dd/yyyy',document.getElementById('imgEndDateLoc'))"
            src="https://<?=$_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px;
            border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" />-->
            </td>  
        
        </tr>
        
        <?php
		$viewStateLoc = '';
		$viewStateEff = '';
		$viewStateEnd = '';
		foreach($locArray as $locArrayKey=>$locArrayVal) 
		{ 
			$hdnLocations .= $locArrayVal['location'].',';
			if(!empty($empID))
			{
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
				$rstLocSelQry = $RDSObj->execute($locSelQry); //, $db) or die(mssql_get_last_message());
				$viewStateLoc = mssql_result($rstLocSelQry,0,0);
				$viewStateEff = mssql_result($rstLocSelQry,0,1);
				$viewStateEnd = mssql_result($rstLocSelQry,0,2);
			}
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
            <input name="effectiveDate_<?php echo $locArrayVal['location'];?>[]" type="text" id="effectiveDateLoc_<?php echo $locArrayVal['location'];?>"  style="width: 75px" readonly="readonly" <?php if(!empty($viewStateEff)) {?> value="<?php echo $viewStateEff;?>" <?php }?> accesskey="true" />
            
            <!--<img id="imgEffectiveDateLoc_<?php echo $locArrayVal['location'];?>" alt="Choose Start Date"
            onclick="javascript:displayCalendar(document.getElementById('effectiveDateLoc_<?php echo $locArrayVal['location'];?>'),'mm/dd/yyyy',document.getElementById('imgEffectiveDateLoc_<?php echo $locArrayVal['location'];?>'));  chkLocCheckBox('<?php echo $locArrayVal['location'];?>');"
            src="https://<?=$_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px;
            border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" />-->
            </td>
            
            <td width="31%">End Date&nbsp;
            <input name="endDate_<?php echo $locArrayVal['location'];?>[]" type="text" id="endDateLoc_<?php echo $locArrayVal['location'];?>"  style="width: 75px" readonly="readonly" onchange="greaterFunc('<?php echo $locArrayVal['location'];?>','Loc');" <?php if(!empty($viewStateEnd)) {?> value="<?php echo $viewStateEnd;?>" <?php }?> accesskey="true"/>
            
            <!--<img id="imgEndDateLoc_<?php echo $locArrayVal['location'];?>" alt="Choose Start Date"
            onclick="javascript:displayCalendar(document.getElementById('endDateLoc_<?php echo $locArrayVal['location'];?>'),'mm/dd/yyyy',document.getElementById('imgEndDateLoc_<?php echo $locArrayVal['location'];?>'));  chkLocCheckBox('<?php echo $locArrayVal['location'];?>');"
            src="https://<?=$_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" style="border-top-width: 0px;
            border-left-width: 0px; border-bottom-width: 0px; border-right-width: 0px" />-->
            </td>  
        
        </tr>
        <?php 
		$i++;
		}?>	
        <input type="hidden" name="hdnRawLocs" id="hdnRawLocs" value="<?php echo substr($hdnLocations,0,-1);?>">
            <input type="hidden" name="hdnIVal" id="hdnIVal" value="<?php echo $i;?>">
    </table>
<?php
}?>
<input type="hidden" name="hdnValidate" id="hdnValidate" value="1">
<script type="text/javascript">
//$(document).ready(function(){
	if($('input[accesskey=true]').size()>0)
	{	
		$('input[accesskey=true]').each(function() {
												 //alert($(this).id+'---'+this.name);
		$( "input[name='"+this.name+"']" ).datepicker({
			  showOn			: "button",
			  buttonImage		: "/Include/images/calendar.gif",
			  buttonText		: 'Calendar',
			  buttonImageOnly	: true,
			  showWeek			: true,
			  changeMonth		: true,
			  changeYear		: true,
			  showButtonPanel	: true,
			  closeText			: "Close",
			  showAnim			: "slideDown"
			});
		 
		 });
	}
//});
</script>