<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");

/* Step 2 */
// Load html header content.
$htmlObject->htmlMetaTagsTitle('Agent score card');

//$cssJsArray = array('CSS'=>array('readiNetAll.css', 'adpcss.css' , 'modalwindowzindex.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('dhtmlgoodies_calendar_new.js?random=20060118','table.js','jquery.js','dymicwidthHeightv2.js','Users/site_administration/siteManagement/ajax.js'));
$cssJsArray = array('CSS'=>array('agentScore.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('dhtmlgoodies_calendar.js?random=20060118' , 'table.js','dymicwidthHeightv2.js','agentScoreCard/jquery.min.js', 'agentScoreCard/rnetCharts.js' , 'agentScoreCard/validations.js'));
$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.
$htmlObject->loadBodyTag('leftMenu');

/* Step - 4 Load header part */
// Send object of DB class.
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php', 'Back'=>'agentScoreCard/viewMyScoreCard.php');
$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */
$loggedInemployeeID = $agentScoreObj->UserDetails->User;
$requestedDate = date('m/d/Y');
$todayDate = date('m/d/Y');
$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));

$topLevelHeading = 'Agent score card';
$indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg'));

$notificationIndicator = array('0'=>'includes/images/SmallEmail_blue.jpg', '1'=>'includes/images/SmallEmail_grey.jpg');
$trStryleArray = array('0'=>'<tr style="height:30px; background-color:#D0D8E8;">', '1'=>'<tr style="height:30px; background-color:#E9EDF4;">');

$weektoDate = array('WTD'=>'Week-to-Date', 'MTD'=>'Month-to-Date');
/*echo '<pre>';
print_r($indicatorArray);
echo '</pre>';*/


if(isset($_REQUEST['Date']))
{
	$requestedDate = date('m/d/Y', strtotime($_REQUEST['Date']));	
}

$employeeID = '42909';
$currentDate = '06/29/2013';

/* -- General Section ----------*/
$agentScoreObj->setTopLevelGeneralData($employeeID , $currentDate);
$topLevelGeneralData = $agentScoreObj->getopLevelGeneralData();
/*echo '<pre>';
print_r($topLevelGeneralData);
echo '</pre>';
exit();*/
/* -- End General Section ----------*/


/* -- Inbound Section ----------*/
$agentScoreObj->setInboundData($employeeID , $currentDate);
$inboundData = $agentScoreObj->getInboundData();
/*echo '<pre>';
print_r($inboundData);
echo '</pre>';
exit();*/
/* -- End Inbound Section ----------*/

/* -- Outbound Section ----------*/
$agentScoreObj->setOutboundData($employeeID , $currentDate);
$outboundData = $agentScoreObj->getOutboundData();
/*echo '<pre>';
print_r($outboundData);
echo '</pre>';
exit();*/
/* -- End outbound Section ----------*/

/* -- Fax Section ----------*/
$agentScoreObj->setFaxData($employeeID , $currentDate);
$faxData = $agentScoreObj->getFaxData();
/*echo '<pre>';
print_r($faxData);
echo '</pre>';
exit();*/
/* -- End Fax Section ----------*/


// Get rejection reason

$rejectionReason = $agentScoreObj->getRejectionId($loggedInemployeeID , $requestedDate);

?>
<div id="report_content">
<div class="outer" id="emptyDiv"></div>

<div id="topLevelHeading" class="outer">
<form name="frmTopLvel" id="frmTopLvel" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" style="padding:0px; margin:0px;">
	<div id="headingPanel"><?php echo $topLevelHeading;?></div>
    
    <div id="headingDateControl">
    
        <div style="vertical-align:top; float:left; padding-top:3px;">Date: </div>

        <div style="float:left; padding-left:5px;"><input type="text" name="Date" id="Date" style="width:75px;" value="<?php echo $requestedDate;?>"   />&nbsp;&nbsp;&nbsp;<img id="imgstartDate" alt="Choose Date" onclick=        "javascript:displayCalendar(document.getElementById('Date'),'mm/dd/yyyy',document.getElementById('imgstartDate'));" src="https://<?php echo $_SERVER['HTTP_HOST']?>/Include/images/calendar.gif" /> 
        </div>

        <div style="float:left; padding-left:15px;">
        <input type="hidden" name="hdnCalendarMinEndDate" id="hdnCalendarMinEndDate" value="<?php echo $yesterDayDate;?>" />
        <input type="button" name="btnReport" id="btnReport" value="Generate Score card" onclick="return formSubmit('frmTopLvel'); return false;">
        </div>

    </div> <!-- headingDateControl -->
    
    <div id="wellCareImg">
    	<img src="includes/images/wellcare.png" alt="Wellcare" width="200" height="50">
    </div>
</form>

</div>
<div class="outer" id="emptyDiv"></div>

<div id = "midContent" class="outer">

	<div id = "legend">
    	<div id="legendHeading">Legend</div>
        <div id="legendContent">
        <table cellpadding="4" border="1" style="border: 1px #FFF;">
        	<tr><th style="border: 1px #000;">Excellent</th><td style="border: 1px #000;"><img src="includes/images/SmallGreen.png" width="30" height="30" style="text-align:center;"></td></tr>
            <tr><th style="border: 1px #000;">OK</th><td style="border: 1px #000;"><img src="includes/images/SmallYellow.png" width="30" height="30" style="text-align:center;"></td></tr>
            <tr><th style="border: 1px #000;">Poor</th><td style="border: 1px #000;"><img src="includes/images/SmallRed.png"width="30" height="30" style="text-align:center;"></td></tr>
            

        </table>
        </div>
        
    </div>

	<div id = "General">
		<div id = "generalHeading">General</div>
        <div id = "generalBodyContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
            <th align="center"><strong>&nbsp;</strong></th>
            <th align="center"><strong>Production <br> Hours</strong></th>
            <th align="center"><strong>Break / Lunch <br> Hours</strong></th>
            <th align="center" colspan="2"><strong>Schedule Adherence</strong></th>
            <th align="center" colspan="2"><strong>PIP Level</strong></th>
            <th align="center" colspan="2"><strong>Unread Notifications</strong></th>
            <th align="center"><strong>Training Hours</strong></th>
            
          </tr>
        </thead>
        
        <tbody>
        <?php
		if(!empty($topLevelGeneralData))
		{
			$g = 0;
			
			foreach($topLevelGeneralData as $topLevelGeneralDataVal)
			{
					if($g!=0 && $g%2==0)
					{
						$g=0;	
					}
					
		?>
			<?php echo $trStryleArray[$g]; ?>
                <td style="text-align:left;"><?php 
						if(array_key_exists($topLevelGeneralDataVal['Period'], $weektoDate))
						{
							echo $weektoDate[$topLevelGeneralDataVal['Period']];
						}
						else
						{
							echo $topLevelGeneralDataVal['Period']; 
						}
					?></td>
                <td><?php echo $topLevelGeneralDataVal['Production Hours'];?></td>
                <td><?php echo $topLevelGeneralDataVal['Break/Lunch Aux'];?></td>
                <td><?php echo number_format( ($topLevelGeneralDataVal['Schedule Adherence']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$g][$topLevelGeneralDataVal['Schedule Adherence Indicator']];?>" width="20" height="20" ></td>
                <td><?php echo number_format( ($topLevelGeneralDataVal['PIP Level']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$g][$topLevelGeneralDataVal['PIP Level Indicator']];?>" width="20" height="20" ></td>
                <td><?php echo $topLevelGeneralDataVal['Notifications'];?></td>
                <td style="text-align:center;"><?php 
						if(!empty($topLevelGeneralDataVal['NotificationsLink']))
						{?>
							<a href="<?php echo $topLevelGeneralDataVal['NotificationsLink'];?>"><img src="<?php echo $notificationIndicator[$g];?>"	 width="30" height="20" border="0" /></a>
						<?php } else {
							echo '&nbsp;';	
						}
				?></td>
                <td><?php echo $topLevelGeneralDataVal['Training Hours and History'];?></td>
        	</tr>
        
        <?php 
			$g++;
			} // for
		} else { ?>
        
        	<tr><td colspan="10">No data found.</td></tr>
        <?php } ?>
        </tbody>
        </table>
        </div>
        
    </div>
	
    <div id = "goals">
    	<div id="goalsHeading">Goals</div>
        <div id="goalsContent">
        <table cellpadding="3" border="1" style="border: 1px #FFF; width:90%;">
        	<tr>
            	<th style="border: 1px #000;">Schedule Adherence</th><td style="border: 1px #000; text-align:center;">90%</td>
            </tr>
            
            <tr>
            	<th style="border: 1px #000;">PIP Level</th><td style="border: 1px #000; text-align:center;">82%</td>
            </tr>
            
            <tr>
            	<th style="border: 1px #000;">Calls Handled</th><td style="border: 1px #000; text-align:center;">50</td>
            </tr>
            
            <tr>
            	<th style="border: 1px #000;">AHT</th><td style="border: 1px #000; text-align:center;">3</td>
            </tr>
            
            <tr>
            	<th style="border: 1px #000;">CTM</th><td style="border: 1px #000; text-align:center;">45</td>
            </tr>
            
            <tr>
            	<th style="border: 1px #000;">CSAT</th><td style="border: 1px #000; text-align:center;">32</td>
            </tr>
            
            

        </table>
        </div>
        
    </div>
    
</div>
<!-- end of mid content -->
<div id="inboundCountContent" style="padding:0px; padding:0px;">
<div class="scrollingdatagridNew" id="scrollingdatagrid" visible="true">
<!-- Inbound Section -->
<div class="section">
		<div class="sectionHeading">Inbound</div>
        <div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
            <th align="center"><strong>&nbsp;</strong></th>
            <th align="center" colspan="2"><strong>Stack <br> Rank</strong></th>
            <th align="center" colspan="2"><strong>Calls <br>Handled</strong></th>
            <th align="center" colspan="2"><strong>AHT</strong></th>
            <th align="center" colspan="2"><strong>CTM</strong></th>
            <th align="center" colspan="2"><strong>CSAT</strong></th>
            <th align="center" colspan="2"><strong>FCR</strong></th>
            <th align="center" colspan="2"><strong>Grievance</strong></th>
            <th align="center" colspan="2"><strong>Retention</strong></th>
            <th align="center" colspan="2"><strong>Care Gaps</strong></th>
            <th align="center" colspan="2"><strong>Quality</strong></th>
            
            
          </tr>
        </thead>
        
        <tbody>
        
        <?php 
			if(!empty($inboundData))
			{
				$i=0;
				
				foreach($inboundData as $inboundDataVal)
				{ 
					if($i!=0 && $i%2==0)
					{
						$i=0;	
					}
				?>
                <?php echo $trStryleArray[$i]; ?>
                <td style="text-align:left;"><?php 
						if(array_key_exists($inboundDataVal['Period'], $weektoDate))
						{
							echo $weektoDate[$inboundDataVal['Period']];
						}
						else
						{
							echo $inboundDataVal['Period']; 
						}
						?></td>
                   
                <td><a href="#" onclick="return populateDetails('Inbound','Stack Rank','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>'); return false;"><?php echo $inboundDataVal['Stack Rack'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['Stack Rack Indicator']];?>" width="20" height="20" ></td>
				
				
				<td><a href="#" onclick="return populateDetails('Inbound','Calls Handled','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>'); return false;"><?php echo $inboundDataVal['Calls Handled'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['Calls Handled Indicator']];?>" width="20" height="20" ></td>
                
                <td><a href="#" onclick="return populateDetails('Inbound','AHT','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>'); return false;"><?php echo $inboundDataVal['AHT'];?></a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['AHT Indicator']];?>" width="20" height="20" ></td>
				
                <td><a href="#" onclick="return populateDetails('Inbound','CTM','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>'); return false;"><?php echo number_format( ($inboundDataVal['CTM']*100),2,'.','');?>%</a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['CTM Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format($inboundDataVal['CSAT'],2,'.','');?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['CSAT Indicator']];?>" width="20" height="20" ></td>
                
				<td><?php echo number_format($inboundDataVal['FCR'],2,'.','');?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['FCR Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format( ($inboundDataVal['Grievance']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['Grievance Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format( ($inboundDataVal['Retention']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['Retention Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format( ($inboundDataVal['Care Gaps']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['Care Gaps Indicator']];?>" width="20" height="20" ></td>
                
                 <td><?php echo number_format( ($inboundDataVal['Quality']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$inboundDataVal['Quality Indicator']];?>" width="20" height="20" ></td>
                
				<?php 
				$i++;
				} // for
				
					
			}else {  ?>
			
            	<tr><td colspan="11">No data found</td></tr>
			
			<?php }
		?>
        
        
        </tbody>
        </table>
        </div>
        
    </div>

<!-- Outbound Section -->
    
<div class="section">
		<div class="sectionHeading">Outbound</div>
        <div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
            <th align="center"><strong>&nbsp;</strong></th>
            <th align="center" colspan="2"><strong>Stack <br> Rank</strong></th>
            <th align="center" colspan="2"><strong>Contacts</strong></th>
            <th align="center" colspan="2"><strong>Contacts <br>per Hour</strong></th>
            <th align="center" colspan="2"><strong>Completes</strong></th>
            <th align="center" colspan="2"><strong>Completes <br>per Hour</strong></th>
            <th align="center" colspan="2"><strong>AHT</strong></th>
            <th align="center" colspan="2"><strong>Sales</strong></th>
            <th align="center" colspan="2"><strong>Refusals</strong></th>
            <th align="center" colspan="2"><strong>Eligible <br>Conversion</strong></th>
            <th align="center" colspan="2"><strong>Conversion</strong></th>
            <th align="center" colspan="2"><strong>Sales <br>per Hour</strong></th>
            <th align="center" colspan="2"><strong>Quality</strong></th>
            
            
          </tr>
        </thead>
                
        <tbody>
        	<?php 
				if(!empty($outboundData))
				{
					$o=0;
					
					foreach($outboundData as $outboundDataVal)
					{
						if($o!=0 && $o%2==0)
						{
							$o=0;	
						}
				?>
                <?php echo $trStryleArray[$o]; ?>
						
                        <td style="text-align:left;"><?php 
						if(array_key_exists($outboundDataVal['Period'], $weektoDate))
						{
							echo $weektoDate[$outboundDataVal['Period']];
						}
						else
						{
							echo $outboundDataVal['Period']; 
						}
						?></td>
                   
                <td><?php echo $outboundDataVal['Stack Rack'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Stack Rack Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $outboundDataVal['Contacts'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Contacts Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format($outboundDataVal['Contacts per Hour'],2,'.','');?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Contacts per Hour Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $outboundDataVal['Completes'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Completes Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format($outboundDataVal['Completes per Hour'],2,'.','');?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Completes per Hour Indicator']];?>" width="20" height="20" ></td>
                
                
                <td><?php echo $outboundDataVal['AHT'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['AHT Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $outboundDataVal['Sales'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Sales Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $outboundDataVal['Refusals'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Refusals Indicator']];?>" width="20" height="20" ></td>
                
                 <td><?php echo number_format( ($outboundDataVal['Eligible Conversion']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Eligible Conversion Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format( ($outboundDataVal['Conversion']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Conversion Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format( ($outboundDataVal['Sales per Hour']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Sales per Hour Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format( ($outboundDataVal['Quality']*100),2,'.','');?>%</td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$o][$outboundDataVal['Quality Indicator']];?>" width="20" height="20" ></td>
                
                
                
                <?php
						$o++;
					}
				}
				else
				{ ?>
						<tr><td colspan="13">No data found</td></tr>
				<?php }
			?>
        
        </tbody>
        </table>
        </div>
        
    </div>
    
<!-- Fax -->
    
<div class="section">
		<div class="sectionHeading">Fax</div>
        <div class="sectionContent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report">
        <thead>
          <tr>
            
            <th align="center"><strong>&nbsp;</strong></th>
            <th align="center" colspan="2"><strong>Stack <br> Rank</strong></th>
            <th align="center" colspan="2"><strong>Fax Hours</strong></th>
            <th align="center" colspan="2"><strong># of Faxes <br>Worked</strong></th>
            <th align="center" colspan="2"><strong>Faxes <br> per Hour</strong></th>
            <th align="center" colspan="2"><strong>Quality</strong></th>
            
            
          </tr>
        </thead>
        
        <tbody>
        	<?php
			
				if(!empty($faxData))
				{
						$f=0;
						foreach($faxData as $faxDataVal)
						{
							
							if($f!=0 && $f%2==0)
							{
								$f=0;	
							}
				?>
                <?php echo $trStryleArray[$f]; ?>
						
                        <td style="text-align:left;"><?php 
						if(array_key_exists($faxDataVal['Period'], $weektoDate))
						{
							echo $weektoDate[$faxDataVal['Period']];
						}
						else
						{
							echo $faxDataVal['Period']; 
						}
						?></td>
						
                        <td><?php echo $faxDataVal['Stack Rack'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$f][$faxDataVal['Stack Rack Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format($faxDataVal['Fax Hours'],2,'.','');?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$f][$faxDataVal['Fax Hours Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo $faxDataVal['Number of Faxes Worked'];?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$f][$faxDataVal['Number of Faxes Worked Indicator']];?>" width="20" height="20" ></td>
                
                <td><?php echo number_format($faxDataVal['Faxes per Hour'],2,'.','');?></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$f][$faxDataVal['Faxes per Hour Indicator']];?>" width="20" height="20" ></td>
                 
                 
                 <td><a href="#" onclick="return populateDetails('Fax','Quality','<?php echo $inboundDataVal['Period'];?>','<?php echo $employeeID;?>'); return false;"><?php echo number_format( ($faxDataVal['Quality']*100),2,'.','');?>%</a></td>
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$f][$faxDataVal['Quality Indicator']];?>" width="20" height="20" ></td>
                
                
                
						<?php	
							$f++;
						}
				}
				else
				{ ?>
						<tr><td colspan="11">No data found</td></tr>
				<?php }
			?>
        </tbody>
        </table>
        </div>
        
    </div>
    
</div>
</div>

<!-- Footer -->

<div class="outer" id="footer">
<form name="frmbottomLvel" id="frmbottomLvel" method="post" action="includes/approveReject_process.php" style="padding:0px; margin:0px;">
<div style="float:left; margin-left:10px;"><input type="button" name="btnAgreed" value="I agree with the score card" onclick="formSubmit('frmbottomLvel'); return false;"></div>

<div style="float:left; margin-left:10px;">
<input type="button" name="btnDisAgreed" value="I do not agree with the score card" onClick="return validateReason(); return false;">
<input type="hidden" name="hdnEmployeeID" id="hdnEmployeeID" value="<?php echo $loggedInemployeeID;?>" />
<input type="hidden" name="hdnRequestedDate" id="hdnRequestedDate" value="<?php echo $requestedDate;?>" />
<input type="hidden" name="hdnIsSuccess" id="hdnIsSuccess" value="" />
</div>

<div style="float:left; margin-left:10px;">
<select name="ddlReason" id="ddlReason">
<option value="">Please choose</option>
<option value="1">Incorrect agent score</option>
</select>
<script type="text/javascript">
document.getElementById('ddlReason').value = '<?php echo $rejectionReason;?>';
</script>
</div>
</form>
</div>

</div> <!-- report content -->


<div id="dialog" class="window" style="margin:0px; padding:0px;">

<a href="#"class="close"  style="float:right; border:0px;"  /> Close <img src="../Include/images/round_red_close_button.jpg"  border="0" width="20px" height="20px" /></a>
<br />
<div id="replace_main">
</div>
</div>
<!-- Mask to cover the whole screen -->
<div id="mask"></div>
<script type="text/javascript">
makeItDynamic();
</script>