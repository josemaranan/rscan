<?php
//ini_set('display_errors','1');
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");

/* Step 2 */
// Load html header content.
$htmlObject->htmlMetaTagsTitle('Agent scorecard');

//$cssJsArray = array('CSS'=>array('readiNetAll.css', 'adpcss.css' , 'modalwindowzindex.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('dhtmlgoodies_calendar_new.js?random=20060118','table.js','jquery.js','dymicwidthHeightv2.js','Users/site_administration/siteManagement/ajax.js'));

/*$cssJsArray = array('CSS'=>array('readiNetAll.css', 'agentScore.css','dhtmlgoodies_calendar.css?rand=20051112'), 'JS'=>array('dhtmlgoodies_calendar.js?random=20060118' , 'table.js','jquery-1.9.1.min.js','dymicwidthHeightv2.js', 'agentScoreCard/validations.js'));
*/


$client = $_SESSION[agentScoreClient];
$lob_id = $_SESSION[agentScoreCardLob_id];
$acknowlegeFlag = true; 
if($_SESSION['fromMentor'] == 'Yes')
{
	$agentScoreObj->UserDetails->User = $_SESSION['fromMentorEmployeID'];	
	$acknowlegeFlag = false;
}
//echo $lob_id;
//exit;

 

if(strtoupper($client) == 'HELIO')
{
	$client2 = 'sprint';
}
else
{
	$client2 = $client;
}

//echo $client2." :C<br/>";


$cssJsArray = array('CSS'=>array('agentScoreCardNew.css','dhtmlgoodies_calendar.css?random=20051112', 'modalwindowzindex.css'), 'JS'=>array('dhtmlgoodies_calendar.js?random=20060118' , 'table.js','agentScoreCard/jquery.min.js', 'agentScoreCard/rnetCharts.js' ,'agentScoreCard/validations.js','agentScoreCard/dymicwidthHeightv.js'));
$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.
//$htmlObject->loadBodyTag('leftMenu');
$htmlObject->loadBodyTag('leftMenu','',array('style'=>'background-color:#000'));

/* Step - 4 Load header part */
// Send object of DB class.


$unEndCoachings = $agentScoreObj->getUnEndCoachingSessions($agentScoreObj->UserDetails->User);

if($unEndCoachings[0]['Result']=='Yes' && $_SESSION[isCoachingApplicable] == 'Y')
{
	$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php','Back'=>'agentScoreCard/viewMyScoreCard.php','image'=>array('image'=>'agentScoreCard/includes/images/returnToCoaching_black_bevel.png','style'=>'float:right; margin-right:50px; margin-top:-22px; padding:3px; height:25px;', 'id'=>'returnCoaching'));
}
else
{
	$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php','Back'=>'agentScoreCard/viewMyScoreCard.php');	
}

$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */
$employeeID = $agentScoreObj->UserDetails->User;
$requestedDate = date('m/d/Y');
$todayDate = date('m/d/Y');
$yesterDayDate = date ('m/d/Y', strtotime('-1 day', strtotime($todayDate)));
$sectionTitle = '';
unset($scoreCardClientID);

if($_SESSION[agentScoreCardLob_id]!='SXM_IB OEM')
{
	$topLevelHeading = str_replace('OB','',$_SESSION[agentScoreCardLob_id]).'  Agent scorecard';
	$topLevelHeading = str_replace('%','',$topLevelHeading);
}
else
{
	$topLevelHeading = 'SiriusXM Saves Agent scorecard';	
}


$indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg', 'X'=>'includes/images/white_ball_blue.jpg'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg', 'X'=>'includes/images/white_ball_grey.jpg'));

$notificationIndicator = array('0'=>'includes/images/SmallEmail_blue.jpg', '1'=>'includes/images/SmallEmail_grey.jpg');

$trStryleArray = array('0'=>'<tr style="height:25px; background-color:#D0D8E8;" class="hidden">', '1'=>'<tr style="height:25px; background-color:#E9EDF4;" class="hidden">');

$weektoDate = array('WTD'=>'Week-to-Date', 'MTD'=>'Month-to-Date');
/*echo '<pre>';
print_r($indicatorArray);
echo '</pre>';*/

if (isset($_REQUEST['Date']))
{
	$requestedDate = date('m/d/Y', strtotime($_REQUEST['Date']));
	unset($_SESSION['requestedDate']);
	$_SESSION['requestedDate'] = $requestedDate;
} 
else
{ 
	$requestedDate = $_SESSION['requestedDate'];
}
$scoreCardClientID = $_SESSION['scoreId'];
if($scoreCardClientID=='')
{
	header('Location:../index.php');	
	exit();
}

// Get rejection reason

$rejectionReason = $agentScoreObj->getRejectionId($employeeID , $requestedDate, $client, $lob_id);

				
//$getSections =  $agentScoreObj->getSectionsListByClient($client , $lob_id);
$getSections =  $agentScoreObj->getSectionsListById($scoreCardClientID);
/*echo '<pre>';
print_r($getSections);
echo '</pre>';
exit;*/

$dataSections = array();	
foreach($getSections as $key => $value)
{
	//echo $key." - ".$value['scoreCardSectionName']." - $requestedDate <br/>";
	if(!empty($value['SP']))
	{
		$sp = "EXEC ".$value['SP']." '$employeeID','$requestedDate'";
		$dataSections[$value['scoreCardSectionName']] = $agentScoreObj->getDataOfEachSection($sp , $value['pointingTo']);
	}
	$scoreCardClientID = $value['scoreCardClientID'];
	
	
}

/*echo '<pre>';
print_r($dataSections);
echo '</pre>';
exit;*/

$allSectionsData = $agentScoreObj->setMappingWithSections($scoreCardClientID);

/*echo '<pre>';
print_r($allSectionsData);
echo '</pre>';*/

$allSectionsHeadings = $agentScoreObj->setMappingWithSectionHeadings($scoreCardClientID);

/*echo '<pre>';
print_r($allSectionsHeadings);
echo '</pre>';
exit;*/


				
?>
<div id="report_content">
<div class="outer" id="emptyDiv"></div>
<div style="background-color:#CCC;">

</div>

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
        <input type="hidden" name="exceptValidations" id="exceptValidations" value="startDate" />
        </div>

    </div> <!-- headingDateControl -->
    
    <div id="wellCareImg">
    <?php
			//echo 'Client'.$client;
			switch(strtoupper($client))
			{
				case 'WELLCARE':
					echo '<img src="includes/images/wellcare.png" alt="Wellcare" width="200" height="50">';
				break;
				
				case 'HELIO':
					echo '<img src="includes/images/Sprint.jpg" alt="Sprint" width="100" height="50">';
				break;
				
				case 'COMCAST':
					echo '<img src="includes/images/comcastScoreCard.png" alt="Comcast" width="100" height="50">';
				break;
				
				case 'H&R BLOCK':
					echo '<img src="includes/images/hrbLogo_small.jpg" alt="H&R Block" width="70" height="50">';
				break;
				case 'EHARMONY':
					echo '<img src="includes/images/eharmony_new.png" alt="eharmony">';
				break;
				case 'BREADCRUMB':
					echo '<img src="includes/images/breadcrumb.png" alt="breadcrumb">';
				break;
				case 'XM':
					echo '<img src="includes/images/xm.jpg" alt="xm">';
				break;
				case 'SXM CONNECTED VEHICLE':
					echo '<img src="includes/images/XM_connected.png" alt="SXM Connected Vehicle">';
				break;
				
				
			}
	?>
    	
    </div>
</form>

</div>
<div class="outer" id="emptyDiv"></div>

<div id = "midContent" class="outer">

	<div id = "legend" >
    	<div id="legendHeading" style="text-align:left; padding-left:25px; background-image:url(includes/images/minus.gif); background-repeat:no-repeat; background-position:left;" class="locked" onclick="return toggleDivs('legendContent', this.id); return false;" title="collapse"> Legend</div>
        <div id="legendContent" >
            <table cellpadding="3" border="1" style="border: 1px #FFF;">
                <tr>
                    <th style="border: 1px #000;">Excellent</th>
                    <td style="border: 1px #000;"><img src="includes/images/SmallGreen.png" width="20" height="20" style="text-align:center;"></td>
                </tr>
                <tr>
                    <th style="border: 1px #000;">OK</th>
                    <td style="border: 1px #000;"><img src="includes/images/SmallYellow.png" width="20" height="20" style="text-align:center;"></td>
                </tr>
                <tr>
                    <th style="border: 1px #000;">Poor</th>
                    <td style="border: 1px #000;"><img src="includes/images/SmallRed.png"width="20" height="20" style="text-align:center;"></td>
                </tr>
                <tr>
                    <th style="border: 1px #000;">No Data</th>
                    <td style="border: 1px #000;"><img src="includes/images/smallWhite_grey.png"width="20" height="20" style="text-align:center;"></td>
                 </tr>
            </table>
        </div>       
    </div>
	
	<div id = "General">		
        
        	<?php 
				$cntBelowTbls = count($getSections);
				unset($sectionStyle);
				
				$sectionTitle = $getSections['0']["scoreCardSectionName"];
				//$sectionStyle = $getSections['0']["style"];
				
				echo '<div id = "generalHeading" style="text-align:left; padding-left:20px; background-image:url(includes/images/minus.gif); background-repeat:no-repeat; background-position:left;" class="locked" onclick="return toggleDivs(\'generalBodyContent\', this.id); return false;" title="collapse">'.$sectionTitle.'</div>';
				echo '<div id = "generalBodyContent">';
				unset($displayEachSectionData);
				$displayEachSectionData =  $agentScoreObj->getConentBySectionWise($getSections, 
																  $dataSections, 
																  $allSectionsData, 
																  $allSectionsHeadings, 
																  $notificationIndicator, 
																  $trStryleArray, 
																  $weektoDate, 
																  $indicatorArray, 
																  $sectionTitle,
																  $requestedDate,
																  $employeeID,
																  '\'General\''); 
				echo $displayEachSectionData;
				echo '</div>';			
			?>
               
    </div>
</div>
<!-- end of mid content -->
<div id="inboundCountContent" style="padding:0px; padding:0px;">
	<div class="scrollingdatagridNew" id="scrollingdatagrid" visible="true" style="background-color:#FFF;">
 
        <?php 
		
		for($idx=1; $idx< $cntBelowTbls; $idx++)
		{
			unset($sectionTitle);
			unset($countOf);
			unset($sectionStyle);
			unset($sectionLevelSP);
			unset($scoreCardSectionID);
			$sectionTitle = $getSections[$idx]["scoreCardSectionName"];
			$sectionStyle = $getSections[$idx]["style"];
			$sectionLevelSP = $getSections[$idx]["sectionLevelSP"];
			$scoreCardSectionID = $getSections[$idx]["scoreCardSectionID"];
			
			if($sectionTitle=='Other Sections')
			{
					goto OtherSections;
			}
			$countOf = count($dataSections[$sectionTitle]);
			if($countOf>0)
			{
				echo '<div class="section" id="seciton'.$idx.'" style="'.$sectionStyle.'">';
				
				if($sectionLevelSP=='N')
				{
					echo '<div class="sectionHeading" id="div'.$idx.'" style="text-align:left; padding-left:20px; background-image:url(includes/images/minus.gif); background-repeat:no-repeat; background-position:left;" class="locked" onclick="return toggleDivs(\'exPandDiv'.$idx.'\', this.id); return false;" title="collapse">'.$sectionTitle.'</div>';
				}
				else
				{
					$height = '500';
					$width = '1000';
					
					$returnString .= '<a href=# onclick="return populateDetails(\'\',\''.$requestedDate.'\', \''.$employeeID.'\', \''.$scoreCardSectionID.'\', \''.$height.'\', \''.$width.'\', \'tscCustom\',\'\' , \''.$isHavingBar.'\' , \''.$isHavingPie.'\'); return false;">';
					
					echo '<div class="sectionHeading" id="div'.$idx.'" style="text-align:left; padding-left:20px; background-image:url(includes/images/minus.gif); background-repeat:no-repeat; background-position:left;" class="locked" onclick="return toggleDivs(\'exPandDiv'.$idx.'\', this.id); return false;" title="collapse">'.$returnString.$sectionTitle.'</a></div>';	
				}
				
				
				echo '<div class="sectionContent" id="exPandDiv'.$idx.'">';
				unset($displayEachSectionData);
				$displayEachSectionData =  $agentScoreObj->getConentBySectionWise($getSections, 
																		  $dataSections, 
																		  $allSectionsData, 
																		  $allSectionsHeadings, 
																		  $notificationIndicator, 
																		  $trStryleArray, 
																		  $weektoDate, 
																		  $indicatorArray, 
																		  $sectionTitle,
																		  $requestedDate,
																		  $employeeID,
																		  '\'seciton'.$idx.'\''); 
				echo $displayEachSectionData;
				echo '</div>';
				echo '</div>';
			} 
		}
		
		OtherSections:
		include_once('includes/staticSections.php');
		?>   
        

		<!-- Outbound Section -->
        <!-- div class="section">
        	<div class="sectionHeading">Outbound</div>
            <div class="sectionContent"></div>
        </div>
		<!-- Fax -->	
		<!-- div class="section">
			<div class="sectionHeading">Fax</div>
        	<div class="sectionContent"></div>        
    	</div -->
            
	</div>
	<div class="outer" id="emptyDiv"></div>
	<div class="outer" id="emptyDiv"></div>
</div>

<!-- Footer -->
<?php
if($acknowlegeFlag)
{ ?>

<div class="outer" id="footer" style="height:35px;">
<form name="frmbottomLvel" id="frmbottomLvel" method="post" action="includes/approveReject_process.php" style="padding:0px; margin:0px;">
<div style="float:left; margin-left:10px;"><input type="button" name="btnAgreed" value="I agree with the score card" onclick="formSubmit('frmbottomLvel'); return false;"></div>

<div style="float:left; margin-left:10px;">
<input type="button" name="btnDisAgreed" value="I do not agree with the score card" onClick="return validateReason(); return false;">
<input type="hidden" name="hdnEmployeeID" id="hdnEmployeeID" value="<?php echo $employeeID;?>" />
<input type="hidden" name="hdnRequestedDate" id="hdnRequestedDate" value="<?php echo $requestedDate;?>" />
<input type="hidden" name="hdnIsSuccess" id="hdnIsSuccess" value="" />
</div>
<?php
if(!empty($rejectionReason))
{
	$style = "display:block";	
}
else
{
	$style = 	"display:none";
}
?>
<div style="float:left; margin-left:10px; <?php echo $style;?>;" id="divTxtReason">
<input type="text" name="texReason" id="texReason" size="35" value="<?php echo $rejectionReason;?>" />
<input type="hidden" id="howMany" value="1" />
</div>

</form>
</div>

<?php } ?>
</div> <!-- report content -->


<div id="dialog" class="window" style="margin:0px; padding:0px;">
<div id="replace_main">
</div>
</div>
<!-- Mask to cover the whole screen -->
<div id="mask"></div>
<script type="text/javascript">
makeItDynamic();
</script>

<?php $agentScoreObj->closeConn(); ?>

