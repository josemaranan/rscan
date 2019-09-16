<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");

if($_SESSION['fromMentor'] == 'Yes')
{
	$agentScoreObj->UserDetails->User = $_SESSION['fromMentorEmployeID'];	
}


if($_REQUEST[client])
{
	$_SESSION[agentScoreClient] = $_REQUEST[client];
}


$client = $_SESSION[agentScoreClient];
$coachingLob = $_SESSION[agentScoreCardLob_id];
if(strtoupper($client) == 'HELIO')
{
	$client2 = 'sprint';
}
else
{
	$client2 = $client;
}

// Load html header content.
$htmlObject->htmlMetaTagsTitle('View My Score Card'.$_SESSION[agentScoreClient]);

$cssJsArray = array('CSS'=>array('readiNetAll.css', 'agentScoreNew.css','dhtmlgoodies_calendar.css?random=20051112'), 'JS'=>array('dhtmlgoodies_calendar.js?random=20060118' , 'table.js','jquery-1.9.1.min.js','agentScoreCard/viewMyScore.js', 'agentScoreCard/validations.js'));

$indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg', 'X'=>'includes/images/white_ball_blue.jpg', 'E'=>'includes/images/esc_blue.png'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg', 'X'=>'includes/images/white_ball_grey.jpg','E'=>'includes/images/esc_grey.png'));

$trStryleArray = array('0'=>'<tr style="height:25px; background-color:#D0D8E8;">', '1'=>'<tr style="height:25px; background-color:#E9EDF4;">');



$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.
$htmlObject->loadBodyTag('leftMenu','',array('style'=>'background-color:#000'));
//$htmlObject->loadBodyTag('leftMenu');

/* Step - 4 Load header part */
// Send object of DB class.
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php');
$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

/* Variable declariations */

$currentDate = date('m/d/Y');
$topLevelHeading = 'View My Score Card';


$agentScoreObj->setCoachingSessionData($agentScoreObj->UserDetails->User, $client, $coachingLob);


$coachingSessionData = $agentScoreObj->getCoachingSessionData();

$isMoreLink = false;
if(count($coachingSessionData)>8)
{
	$isMoreLink = true;
}

$unEndCoachings = $agentScoreObj->getUnEndCoachingSessions($agentScoreObj->UserDetails->User, $client, $coachingLob);

$isSessionExists = 'N';
if($unEndCoachings[0]['Result']=='Yes')
{
	$btnValue = 'Continue Existing Coaching';
	$isSessionExists = 'Y';
}
else
{
	$btnValue = 'Start New Coaching';	
}

if($_SESSION['fromMentor'] == 'Yes')
{
	unset($_SESSION[isCoachingApplicable]);
}
?>

<script language="JavaScript" src="../../../ajax.js" type="text/javascript"></script>
<style type="text/css">
<!--
body{
	background-color:#000;
}

#legend_line_employee {
	float:left;  
	clear:both;
	padding:2px;
	width:250px;
	color:#FFFFFF;
	
}

#legend_color {
	height: 20px;
	width: 25px;
	float:left;
	border:#999999  solid 1px;
	margin-top:3px;
}

#right {
	background-color:#FFFFFF;
	border:#0066CC  solid 1px;	
}

a.tooltip {
position:relative;
}
a.tooltip span {
	width:auto;
	position: absolute;  
	clear:both;
	padding:2px;
	z-index:50;
	background-color:#FFFFFF;
	border:#999999  solid 1px;
	display:none;
	overflow:visible;
	background:#0066CC;

}
a.tooltip:hover {
display:inline-block;
}
a.tooltip:hover span {
display:block;

}

 .odd{background-color: white;} 
 .even{background-color: #EFEFEF;} 
 .selected{background-color:#FFCC66;} 


</style>
<?php
if($_SESSION['accountType']=='CX360')
{
?>
<style>
	.leftSide
	{
		margin-left:0px;
	}
</style>
<?php	
}
else
{
?>
<style>
	.rightDataDiv
	{
		float: left;
		position:relative;
	}
	.leftSide1
	{
		height:75%;
	}
	.eurekaDiv
	{
		height:25%;
	}
	.mainMenu
	{
		overflow:auto;
	}
	
</style>
<?php
}
?>
<div class="mainMenu">
<div class="leftSide" id="leftSide">
<input type="hidden" name="accountType" id="accountType" value="<?php echo $_SESSION['accountType']; ?>" />
<?php	
if($_SESSION[isScoreCardApplicable] == 'Y')
{
?>

<div class="leftSideInnerContent" id="leftSideInnerContent">
    <div class="topLevelHeading" id="topLevelHeading" >
        <div class="textHeading"  id="textHeading">Agent Scorecard Calendar</div>
    </div>

	<br clear="all" />
	<div id="leftSideContent" class="outer leftSideContent">

<?php
//********************************************************
$date =time () ; 	

//This puts the day, month, and year in seperate variables 
$day = date('d', $date) ; 

//check is the users wants to go back or forward a month
if($_GET[year_flag])
{
	$year = $_GET[year_flag] ;
} 
else 
{
	$year = date('Y', $date);
}

if(!empty($_GET[move_month]))
{
	$month=$_GET[move_month];
}
else
{
	$month = date('m', $date) ; 		
} 	

//Here we generate the first day of the month 
$first_day = mktime(0,0,0,$month, 1, $year) ; 

//This gets us the month name 
$title = date('F', $first_day) ; 

//Here we find out what day of the week the first day of the month falls on 
$day_of_week = date('D', $first_day) ; 	
    
//Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
switch($day_of_week)
{ 
	case "Sun": $blank = 0; break; 
	case "Mon": $blank = 1; break; 
	case "Tue": $blank = 2; break; 
	case "Wed": $blank = 3; break; 
	case "Thu": $blank = 4; break; 
	case "Fri": $blank = 5; break; 
	case "Sat": $blank = 6; break; 
}
    
//We then determine how many days are in the current month
$days_in_month = cal_days_in_month(0, $month, $year) ; 

$eid = $_SESSION['empID']; 

$qry = " EXEC Rnet.dbo.[report_spCheckEmployeeScoreCardApprovals] '$eid', '$month/01/$year', '$month/$days_in_month/$year', '$client', '$coachingLob' ";

$hours_rst = $agentScoreObj->ExecuteQuery($qry);
$num_rows = mssql_num_rows($hours_rst);
$agentScoreObj->closeConn();
$hours_array = array("empty");
$hours_Approval = array("empty");
while ($hours_row=mssql_fetch_array($hours_rst))
{
	array_push($hours_array, $hours_row['date'] );

	$approveStatus[$hours_row['date']] = $hours_row[isApproved];
}
mssql_free_result($hours_rst);

unset($next_months_year);
unset($next_month);
unset($prev_months_year);
unset($prev_month);

$next_months_year  = $year;
$next_month = $month+1;
$prev_months_year  = $year;
$prev_month = $month-1;

	
if($next_month == 13) 
{
	$next_month = 1;    
	$next_months_year= $year+1;
}
	
if($prev_month == 0) 
{
	$prev_month = 12;
	$prev_months_year = $year-1;
}


$currentDate = date('m/d/Y');
?>
        <table border=1  style="border-collapse:collapse; vertical-align:top">
        <tr>
        
        <th class="ColumnHeader">
            <a style="color:White;" href="#" onclick="return moveCalendar('<?php echo $prev_month;?>', '<?php echo $prev_months_year;?>'); return false;">&lt;</a>
        </th>
        
        <th colspan=5 class="ColumnHeader"><?php echo $title.' '.$year;?></th>
        
        <th class="ColumnHeader">
            <a style="color:White;" href="#" onclick="return moveCalendar('<?php echo $next_month;?>', '<?php echo $next_months_year;?>'); return false;">&gt;</a>
        </th>
        
        </tr>
        
        <tr  class="ColumnHeader">
        
            <td style="width:45px;">S</td>
            <td style="width:45px;">M</td>
            <td style="width:45px;">T</td>
            <td style="width:45px;">W</td>
            <td style="width:45px;">T</td>
            <td style="width:45px;">F</td>
            <td style="width:45px;">S</td>
        
        </tr>
        <?php
        //This counts the days in the week, up to 7
        $day_count = 1;
        
        echo "<tr>\r\n";
        //first we take care of those blank days
        while ( $blank > 0 ) 
        { 
            echo "<td></td>\r\n"; 
            $blank = $blank-1; 
            $day_count++;
        } 
        //sets the first day of the month to 1 
        $day_num = 1;
        
        //count up the days, untill we've done all of them in the month
        while ( $day_num <= $days_in_month ) 
        { 
            echo "<td  style=\"vertical-align:top\">\r\n";
            echo "<table>\r\n";
            echo "<tr>\r\n";
            
            $loopdate = $month.'/'.$day_num.'/'.$year;
            $loopdate1 = date('m/d/Y', strtotime($loopdate));
            
            
            if(strlen($day_num)==1)
            {
                $fullday = "0".$day_num;
            } 
            else 
            {
                $fullday = $day_num;
            }
            
            
            if(strlen($month)==1)
            {
                $month1 = "0".$month;
            } 
            else 
            {
                $month1 = $month;
            }
            
            
            
            $datetosearch = $fullday."/".$month1."/".$year;
            
            
            
            
            
            if(strtotime($loopdate1) < strtotime($currentDate))
            {
                echo "<td><a href='index.php?Date=$month/$day_num/$year'>".$day_num."</a>";
                
                unset($sts);
                if (array_search($datetosearch , $hours_array, true) != FALSE)
                {
                    $key = array_search($datetosearch , $hours_array); 
                    $sts = $approveStatus[$datetosearch];
                }
                
                unset($col);
                $myIDs = 'legend_color_white';
                if($sts == 'Y')
                {
                    $col = '#66CC00';
                    $myIDs = 'legend_color';
                }
                else if($sts == 'N')
                {
                    $col = '#F00';
                    $myIDs = 'legend_color';
                }
    
                echo '<div id="'.$myIDs.'" style="background-color:'.$col.';"></div>';
            }
            else
            {
                echo "<td>".$day_num."<br>";
                echo "<br><br>\r\n";
            
            }
            
            
            
            
            echo " <td></td>\r\n"; 
            echo " </tr>\r\n<tr valign=\"bottom\" style=\"vertical-align:bottom\">\r\n"; 
            echo " <td valign=\"bottom\" align=\"left\" style=\"vertical-align:bottom\">";
            
        
            unset($pendingApprovals);
            unset($Approved);
        
        
            echo " \r\n</td>\r\n";  
            echo " <td>\r\n";
            if(strlen($day_num)==1){
                $fullday = "0".$day_num;
            } else {
                $fullday = $day_num;
            }
            if(strlen($month)==1){
                $fullmonth = "0".$month;
            } else {
                $fullmonth = $month;
            }
            $datetosearch = "(".$fullday."/".$fullmonth."/".$year.")";
    
             unset($key);
    
    
            print "</td>\r\n";
            echo " </tr>\r\n"; 
            echo " </table>\r\n"; 
        echo " </td>\r\n"; 
        $day_num++; 
        $day_count++;
        
        //Make sure we start a new row every week
        if ($day_count > 7)
        {
        echo "</tr><tr>\r\n";
        $day_count = 1;
        }
        } 
        //Finaly we finish out the table with some blank details if needed
        while ( $day_count >1 && $day_count <=7 ) 
        { 
        echo "<td> </td>\r\n"; 
        $day_count++; 
        } 
        
        echo "</tr>\r\n</table>\r\n"; 
        
        
    
        
        ?>
    </div>
	
    <div id="legendView" class="legendView" style="border:none;" >
        <div id="legend_line" class="legend_line"><div id="legend_color2" style="background-color: #66CC00;"></div> &nbsp;Approved</div>
        <div id="legend_line" class="legend_line"><div id="legend_color2" style="background-color:#F00;"></div> &nbsp;Rejected</div> 
        <div id="legend_line" class="legend_line"><div id="legend_color2" style="background-color:#FFF;"></div> &nbsp;Not Decided</div>
    </div> 

</div>
<?php } ?>
</div>

<div class="rightDataDiv" style="float:left;">
<div id="rightSideDiv" class="leftSide1">
<?php
if($_SESSION['isCoachingApplicable'] == 'Y')
{
	?>

    <div id ="rightSideInnerContent" class="leftSideInnerContent">
    
        <div id="topLevelHeadingRight" class="topLevelHeading" >
            <div class="textHeading">Coaching Sessions</div>
        </div>
    
        <div id="rightSideContent" class="leftSideContent">
        
        
            <?php
            if(!empty($coachingSessionData))
            {?>
                <div style="margin:0px; padding:0px;" id="jadxDiv" >
                    <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" id="tdReport">
                    <thead>
                      <tr>
                        <th align="center"><strong>&nbsp;</strong></th>
                        <th align="center"><strong>Date</strong></th>
                        <th align="center"><strong>Coach</strong></th>
                        <th align="center"><strong>Time <br />Spent</strong></th>
                        <th align="center" colspan="2"><strong>Strengths/Opportunities <br />Identified</strong></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                        
						$i=0;
						$count = 0;
						foreach($coachingSessionData as $coachingSessionDataVal)
						{ 
						
							//if($count>7)
							if($count>4)
							{
								break;	
							}
							
							if($i!=0 && $i%2==0)
							{
								$i=0;	
							}
							 echo $trStryleArray[$i]; 
							 ?>
							<td style="text-align:left;"><a href="viewCoachingSession.php?from=viewMyscoreCard&coachSessID=<?=$coachingSessionDataVal['coachSessionID'];?>">View</a></td>
							<td style="text-align:left;"><?php echo $coachingSessionDataVal['Date'];?></td>
							<td style="text-align:left;"><?php echo $coachingSessionDataVal['coach'];?></td>
							<td style="text-align:left;"><?php echo $coachingSessionDataVal['splitTimeSpent'];?>&nbsp;Min</td>                       
							<td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$coachingSessionDataVal['strengthsIndicator']];?>" width="15" height="15" ></td>
							<td style="text-align:left;"><?php echo wordwrap($coachingSessionDataVal['strengthsIdentified'],30,"<br />\n");?></td>
								
						</tr>
								
						<?php 
						$i++;
						$count++;
						} 
					?>
                        
                    </tbody>
                    </table>
                </div>
                <div style="text-align:center;" id="moreLink">
					<?php 
                    if($isMoreLink)
                    { 
                    ?>
                    	<a href="#" style="text-decoration:none;" onclick="return loadMoreCoachingSessions(); return false;">more...</a>
                    <?php 
					} 
					?>
                </div>
            <?php 
			} 
			else 
			{ 			
			?>
                <div style="margin:0px; padding:0px;" id="jadxDiv" >
                    <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" style="width:50%;">
                    <thead>
                      <tr>
                        
                        <th align="center"><strong>Date</strong></th>
                        <th align="center"><strong>Coach</strong></th>
                        <th align="center"><strong>Time <br />Spent</strong></th>
                        <th align="center" colspan="2"><strong>Strengths/Opportunities <br />Identified</strong></th>
                       
                      </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="5" style="text-align:center;">No data found</tr>
                    </tbody>
                    </table>
                </div>
            
            	<div style="text-align:center;" id="moreLink">&nbsp;</div>
            <?php 
			} 
			?>
        </div>
        <br clear="all" />
        <? if($isSessionExists == 'Y')
        {
        ?>
    
        <div style="text-align:center; margin-top:25px;" id="divBtnButton" onclick="JavaScript:window.location='agentCoachingNew.php'; return false;">
        <?
        }
        else
        {
        ?>
            <div style="text-align:center; margin-top:25px;" id="divBtnButton" onclick="JavaScript:window.location='coachLogin.php'; return false;">
                <?
                }
                ?>
                
                <input type="button" name="btnNew" value="<?php echo $btnValue;?>" />
            </div>
        <?php } ?>
        

        
        <?php
    	if($_SESSION['isMyEurekaApplicable'] == 'Y')
    	{
        ?>
        <div style="height:25px;"></div>
        <div id="topLevelHeadingRight" class="topLevelHeading">
            <div class="textHeading">MyEureka</div>
        </div>
		
        <div class="leftSideContent">
            <div style="text-align:center;" id="moreLink">
                <a href="<?php echo $_SESSION['myEurekaLink']; ?>" style="text-decoration:none;" target="_self" >MyEureka</a>
            </div>
            <div style="text-align:center;" id="moreLink">&nbsp;</div>
        </div>
        <?php } ?>
        </div>
    </div>

</div>
</div>
<script type="text/javascript">
resizeRequiredContent();



function loadMoreCoachingSessions()
{
			document.getElementById('jadxDiv').innerHTML = '<img src="../Include/images/progress.gif">' + ' Please Wait...';
			$('#moreLink').hide();
			
	$.post('includes/loadMoreCoachingSessions.php',   
	{ 
	
	},   
	
		function(data)
		{		
			if(data!='error')
			{
				document.getElementById('jadxDiv').innerHTML = '';
				$('#jadxDiv').html(data);	
				
				var hiWidR = document.getElementById('rightSideDiv').clientWidth;
				hiWidRMore = hiWidR+10;
				
				document.getElementById('tdReport').style.width = hiWidR +"px";	
				document.getElementById('jadxDiv').style.width = hiWidRMore-20 +"px";
				document.getElementById('jadxDiv').style.height = '180px';
							
				$('#divBtnButton').css("marginTop", 5);
				
				makeItDynamic();
			
			}
		
		} 
	
	
	
	); 
	
	return false;		
}

function moveCalendar(month, year)
{
	document.getElementById('leftSideContent').innerHTML = '<img src="../Include/images/progress.gif">' + ' Please Wait...';
		$.get("populateCalendar.php",   
		{ 
			move_month:month,
			year_flag:year
		},   
		function(data)
		{
			document.getElementById('leftSideContent').innerHTML = '';
			$('#leftSideContent').html(data);
		} 

	);	
}
</script>
