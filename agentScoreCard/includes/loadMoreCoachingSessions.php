<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();


// Load html header content.
$htmlObject->htmlMetaTagsTitle('View My Score Card');

//$cssJsArray = array('CSS'=>array('readiNetAll.css', 'agentScoreNew.css'));

$indicatorArray = array('0'=>array('G'=>'includes/images/SmallGreen_blue.jpg', 'Y'=>'includes/images/SmallYellow_blue.jpg', 'R'=>'includes/images/SmallRed_blue.jpg', 'X'=>'includes/images/white_ball_blue.jpg', 'E'=>'includes/images/esc_blue.png'),'1'=>array('G'=>'includes/images/SmallGreen_grey.jpg', 'Y'=>'includes/images/SmallYellow_grey.jpg', 'R'=>'includes/images/SmallRed_grey.jpg', 'X'=>'includes/images/white_ball_grey.jpg','E'=>'includes/images/esc_grey.png'));

$trStryleArray = array('0'=>'<tr style="height:25px; background-color:#D0D8E8;">', '1'=>'<tr style="height:25px; background-color:#E9EDF4;">');


$htmlObject->loadCSSJsFiles($cssJsArray);
$client = $_SESSION[agentScoreClient];
$coachingLob = $_SESSION[agentScoreCardLob_id];


$agentScoreObj->setCoachingSessionData($agentScoreObj->UserDetails->User,$client,$coachingLob);
$coachingSessionData = $agentScoreObj->getCoachingSessionData();
?>

<!-- <div style="margin:0px; padding:0px; height:280px; width:auto; overflow-X:hidden" class="scrollingdatagrid" > -->
<div style="margin:0px; padding:0px; height:180px; width:auto; overflow-X:hidden" class="scrollingdatagrid" >
<table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" id="tdReport">
        <thead>
          <tr>
            
            <th align="center"><strong></strong></th>
            <th align="center"><strong>Date</strong></th>
            <th align="center"><strong>Coach</strong></th>
            <th align="center"><strong>Time <br />Spent</strong></th>
            <th align="center" colspan="2"><strong>Strengths/Opportunities <br />Identified</strong></th>
           
          </tr>
        </thead>
        
        <tbody>
        
        <?php
			if(!empty($coachingSessionData))
			{
				
				$i=0;
				foreach($coachingSessionData as $coachingSessionDataVal)
				{ 
					if($i!=0 && $i%2==0)
					{
						$i=0;	
					}
				?>
                
                <?php echo $trStryleArray[$i]; ?>
                <td style="text-align:left;"><a href="viewCoachingSession.php?from=viewMyscoreCard&coachSessID=<?=$coachingSessionDataVal['coachSessionID'];?>">View</a></td>
                <td style="text-align:left;"><?php echo $coachingSessionDataVal['Date'];?></td>
                <td style="text-align:left;"><?php echo $coachingSessionDataVal['coach'];?></td>
                <td style="text-align:left;"><?php echo $coachingSessionDataVal['splitTimeSpent'];?>&nbsp;Min</td>                       
                <td style="text-align:center;"><img src="<?php echo $indicatorArray[$i][$coachingSessionDataVal['strengthsIndicator']];?>" width="15" height="15" ></td>
                <td style="text-align:left;"><?php echo wordwrap($coachingSessionDataVal['strengthsIdentified'],25,"<br />\n");?></td>
                        
                        </tr>
						
				<?php 
				$i++;}
				}// outer loop
			
			else
			{ ?>
					<tr><td colspan="6" style="text-align:center;">No data found</td></tr>
			<?php }
		?>
        
        </tbody>
        </table>
</div>
<?php $agentScoreObj->closeConn();  ?>