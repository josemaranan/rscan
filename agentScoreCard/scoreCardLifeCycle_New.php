<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();

include_once($_SERVER['DOCUMENT_ROOT']."/Include/HTMLContent.class.inc.php");
$htmlObject = new HTMLClass();

include_once($_SERVER["DOCUMENT_ROOT"]."/Include/tempTableStructure.inc.php");


// Load html header content.
$htmlObject->htmlMetaTagsTitle('View My Score Card');

$cssJsArray = array('CSS'=>array('Tabstyles.css' , 'readiNetAll.css', 'agentScore.css' ), 'JS'=>array('table.js','agentScoreCard/jquery.min.js',  'agentScoreCard/tytabs.jquery.min.js' ,  'agentScoreCard/viewMyScore.js'));


$trStryleArray = array('0'=>'<tr style="height:20px; background-color:#D0D8E8;">', '1'=>'<tr style="height:20px; background-color:#E9EDF4;">');



$htmlObject->loadCSSJsFiles($cssJsArray);

/* Step 3 */
// Load body tag and left menu.
// Don't pass any thing if dont want left menu.
$htmlObject->loadBodyTag('leftMenu','No',array('style'=>'background-color:#1F497D'));
//$htmlObject->loadBodyTag('leftMenu');

/* Step - 4 Load header part */
// Send object of DB class.
$pageHyperlinks = array('Main Menu'=>'Clients/Results/index.php' , 'Back'=>'agentScoreCard/index.php');
$htmlObject->htmlHeadPart($agentScoreObj->UserDetails->User, $pageHyperlinks);

unset($_SESSION['getEmployeeLifeCycleData']);
unset($employeeCycleData);
$agentScoreObj->setEmployeeLifeCycleData($agentScoreObj->UserDetails->User);
$getEmployeeLifeCycleData = $agentScoreObj->getEmployeeLifeCycleData();
$_SESSION['getEmployeeLifeCycleData'] = $getEmployeeLifeCycleData;

/*echo '<pre>';
print_r($_SESSION['getEmployeeLifeCycleData']);
echo '</pre>';*/

foreach($_SESSION['getEmployeeLifeCycleData'] as $key=>$cycleVal)
{
	if($cycleVal['isMintrainingClassID']=='Y')
	{
			$employeeCycleData[] = $_SESSION['getEmployeeLifeCycleData'][$key];
	}
}

/*echo '<pre>';
print_r($employeeCycleData);
echo '</pre>';*/

$agentScoreObj->setEmployeeStructureHistory($agentScoreObj->UserDetails->User);
$getEmployeeStrucrureData = $agentScoreObj->getEmployeeStructureHistory();


$employeeNameAndAvaya = $agentScoreObj->getEmployeeNameAvayaID($agentScoreObj->UserDetails->User);
/*echo '<pre>';
print_r($employeeNameAndAvaya);
echo '</pre>';*/

?>
<style type="text/css">


</style>
<div id="mainCenterDiv">


<div id="mainSubContent">

	
	<div id="tabsholder">
	
    <div id="empNames">Name: <?php echo $employeeNameAndAvaya[0]['employeeName'];?> &nbsp;&nbsp;&nbsp;&nbsp; Avaya ID(s): <?php echo $employeeNameAndAvaya[0]['avayaIDs'];?></div>
    
        <ul class="tabs">
            <li id="tab1">Lifecycle</li>
            <li id="tab2">Employee Training History</li>
            <li id="tab3">Reporting Structure History</li>
        </ul>
        <div class="contents">

        <div id="content1" class="tabscontent">
        <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" id="tLifeCycle">
        <thead>
          <tr>
            
            
            <th align="center"><strong>Hire Date</strong></th>
            <th align="center"><strong>Grad School Date</strong></th>
            <th align="center"><strong>Production Date</strong></th>
            <th align="center"><strong>Assigned Client</strong></th>
            <th align="center"><strong>Assigned Division</strong></th>
           
          </tr>
        </thead>
        
        <tbody>
        
        <?php
			if(!empty($employeeCycleData))
			{
				$i=0;
				foreach($employeeCycleData as $employeeCycleDataVal)
				{ 
					if($i!=0 && $i%2==0)
					{
						$i=0;	
					}
				?>
                
                <?php echo $trStryleArray[$i]; ?>
                
                <td style="text-align:center;"><?php echo $employeeCycleDataVal['hireDate'];?></td>
                <td style="text-align:center;"><?php echo $employeeCycleDataVal['gradSchoolDate'];?></td> 
                <td style="text-align:center;"><?php echo $employeeCycleDataVal['productionDate'];?></td>
                <td style="text-align:left;"><?php echo $employeeCycleDataVal['assignedClient'];?></td>
                <td style="text-align:left;"><?php echo $employeeCycleDataVal['assignedDivision'];?></td>
                        
                        </tr>
						
				<?php 
				$i++;}
			}
			else
			{ ?>
					<tr><td colspan="7" style="text-align:center;">No data found</td></tr>
			<?php }
		?>
        
        </tbody>
        </table>
            </div>
            <div id="content2" class="tabscontent" style="overflow-Y:auto; overflow-X:hidden;">
           
           <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" id="tTraining">
            <thead>
              <tr>
                
                <th align="center" class="locked"><strong>LOB</strong></th>
                <th align="center"><strong>Training Type</strong></th>
                <th align="center"><strong>Training Hours</strong></th>
                <th align="center"><strong>Training Start Date</strong></th>
                <th align="center"><strong>Training End Date</strong></th>
                <th align="center"><strong>Training Professional</strong></th>

               
              </tr>
            </thead>
            <tbody>
            <?php
			if(!empty($getEmployeeLifeCycleData))
			{
				$i2=0;
				foreach($getEmployeeLifeCycleData as $getEmployeeLifeCycleDataVal)
				{ 
					if($i2!=0 && $i2%2==0)
					{
						$i2=0;	
					}
				?>
                
                <?php echo $trStryleArray[$i2]; ?>
                <td style="text-align:left;"><?php echo $getEmployeeLifeCycleDataVal['lobDescription'];?></td>
                <td style="text-align:left;"><?php echo $getEmployeeLifeCycleDataVal['trainingType'];?></td>
                <td style="text-align:center;"><?php echo $getEmployeeLifeCycleDataVal['trainingHours'];?></td>
                <td style="text-align:center;"><?php echo $getEmployeeLifeCycleDataVal['trainingStartDate'];?></td> 
                <td style="text-align:center;"><?php echo $getEmployeeLifeCycleDataVal['trainingEndDate'];?></td>
                <td style="text-align:left;"><?php echo $getEmployeeLifeCycleDataVal['trainingProfessional'];?></td>
                
                        
                        </tr>
						
				<?php 
				$i2++;}
				
				$totalRecords = $i2*20;
			}
			else
			{ ?>
					<tr><td colspan="6" style="text-align:center;">No data found</td></tr>
			<?php }
		?>
        
        </tbody>
        </table>
        	</div>
           
            <div id="content3" class="tabscontent">
             <table border="0"  bgcolor="#FFFFFF" cellspacing="0" class="report" id="tReporting">
            <thead>
              <tr>
                
                <th align="center"><strong>Supervisor</strong></th>
                <th align="center"><strong>Employee Tenure</strong></th>
                <th align="center"><strong>Dates Reported</strong></th>
                <th align="center"><strong>Performance Stack Rank</strong></th>
                               
              </tr>
            </thead>    
            
            <tbody>
            	<tbody>
        
        <?php
			if(!empty($getEmployeeStrucrureData))
			{
				$i=0;
				foreach($getEmployeeStrucrureData as $getEmployeeStrucrureDataVal)
				{ 
					if($i!=0 && $i%2==0)
					{
						$i=0;	
					}
				?>
                
                <?php echo $trStryleArray[$i]; ?>
                <td style="text-align:left;"><?php echo $getEmployeeStrucrureDataVal['supervisorName'];?></td>
                <td style="text-align:center;"><?php echo $getEmployeeStrucrureDataVal['employeeTenure'];?>&nbsp;days</td>
                <td style="text-align:left;"><?php echo $getEmployeeStrucrureDataVal['datesReported'];?></td>
                <td style="text-align:center;"><?php echo $getEmployeeStrucrureDataVal['performanceStackRank'];?></td> 
                 </tr>
						
				<?php 
				$i++;}
			}
			else
			{ ?>
					<tr><td colspan="4" style="text-align:center;">No data found</td></tr>
			<?php }
		?>
        
        </tbody>
            </tbody>
            </table>    
			</div>
            
        </div>
    </div>
    
 </div> <!-- sub content -->   
</div> <!-- main content -->

<script type="text/javascript">
$(document).ready(function(){
$("#tabsholder").tytabs({
	tabinit:"1",
	fadespeed:"fast"
	});
});

//makeItDynamicTabSystem();

var dynamicHeight = document.documentElement.clientHeight;
var dynamicHeightR = dynamicHeight-225;
var x = <?php echo $totalRecords;?>;
var s = x+10;

if (s > dynamicHeightR)
{
	document.getElementById('content2').style.height = dynamicHeightR;
}
else
{
	
	document.getElementById("content2").style.height = "70%";
}
document.getElementsByTagName("html")[0].style.overflow = "hidden";

</script>

<?php $agentScoreObj->closeConn();?>