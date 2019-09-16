<?php
include_once($_SERVER['DOCUMENT_ROOT']."/agentScoreCard/includes/agentScoreCard.class.inc.php");
$agentScoreObj = new agentScoreCard();
?>
<?php
$client = $_SESSION[agentScoreClient];
$coachingLob = $_SESSION[agentScoreCardLob_id];

if(strtoupper($client) == 'HELIO')
{
	$client2 = 'Wellcare';
}
else
{
	$client2 = $client;
}

$date =time () ; 	

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

/*  New Logic based on stored procedure. */
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

