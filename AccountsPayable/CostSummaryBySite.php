<?
//ini_set('session.cache_limiter', 'private'); 
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);

	//Months Array
	$months[1] = "January";
	$months[2] = "February";
	$months[3] = "March";
	$months[4] = "April";
	$months[5] = "May";
	$months[6] = "June";
	$months[7] = "July";
	$months[8] = "August";
	$months[9] = "September";
	$months[10] = "October";
	$months[11] = "November";
	$months[12] = "December";
	
	
	//Years Array
	$years[1] = "2009";
	$years[2] = "2010";
	$years[3] = "2011";
	$years[4] = "2012";
	$years[5] = "2013";
	$years[6] = "2014";
	$years[7] = "2015";


	if($_REQUEST["ddlLocations"])
	{
		$LOC = $_REQUEST["ddlLocations"];
		
			$query = " SELECT [location]
					  ,[description]
			FROM  [ctlLocations] (NOLOCK)                     
			Where location='".$LOC."'"; 
	
		$locRst=mssql_query($query, $db);
		if ($row=mssql_fetch_array($locRst)) 
		{	
			  $locationDes = $row[description];
		}
	}
	
	if($_REQUEST["ddlYear"])
	{
		$Year = $_REQUEST["ddlYear"];
	}
	
	if($_REQUEST["ddlMonth"])
	{
		$Month = $_REQUEST["ddlMonth"];
		$monthDesc = $months[$Month];
	}
	
	if($_REQUEST["result"])
	{
		$result = $_REQUEST["result"];
	}
	
	
		$Filter .= " WHERE cs.location in".$Me->Locations;
		
		if($LOC != "") 
		{
			$Filter .= " AND cs.location = '".$LOC."' ";
		}
		
		if($Year != "") 
		{
			$Filter .= " AND cs.currentYear = '".$Year."' ";
		}
		
		if($Month != "") 
		{
			$Filter .= " AND cs.currentMonth = '".$Month."' ";
		}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cost Summary By Site</title>

<link href="../Include/CSS/main.css" rel="stylesheet" type="text/css" />
<link href="../Include/CSS/dhtmlgoodies_calendar.css?random=20051112" media="screen" rel="stylesheet" type="text/css" />
<script src="ajax.js" type="text/javascript"></script>
<script language="javascript" src='../Include/javascript/dhtmlgoodies_calendar.js?random=20060118' type="text/javascript"></script>

<script language="JavaScript" type="text/JavaScript">
	function Validate()
	{ 
		var elementRef1 = document.getElementById('ddlLocations').value;
		var elementRef2 = document.getElementById('ddlPayDate').value;
		var ErrorMessage = "";	
		
		if(elementRef1 =="")
		{ 
			alert("Please Select  Location"); 
			document.form_data.ddlLocations.focus();			
			return false;
		}
		if(elementRef2 =="")
		{ 
			alert("Please Select  Pay Date"); 
			document.form_data.ddlPayDate.focus();			
			return false;
		}
	}
</script></head>
<body >

<div id="LOGO">
<div id="userinfo">	
		<?php include("../Include/class/DisplayUserinfo.php");?>
    </div>
	<div id="menu1"> 
	<a href="../Clients/Results/index.php">Main Menu</a><? include($_SERVER['DOCUMENT_ROOT']."/javascript_menu.php");?>
    </div>
</div>
<div id="content" style="padding-left:10px;">
<form method="POST" action="<? echo $_SERVER['PHP_SELF'];?>" name="form_data">

  <table width="770" align="center" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
 <tr>
      <td colspan="4" align="left" bgcolor="#CCCCCC" class="ColumnHeader">Cost Summary By Site</td>
    </tr>
       <tr>
      <td width="46" style="text-align: left;"><strong>Location:</strong></td>
      <td width="711" style="text-align: left;">
        
       <select name="ddlLocations"  id="ddlLocations" style="width:200px;" onchange="htmlData('populatePayDates_select.php', 'Location='+this.value, 'PAYDATE')">	
	   <option value="">Please Choose</option> 
	   <?php


			$SQL="  SELECT [location]
					  ,[description]
					  ,[city]
					  ,[state]
			FROM  [ctlLocations] (NOLOCK)                  
			Where State is not null and location in ".$Me->Locations.
			" and switch ='N' AND productionReportDisplay = 'Y' order by [description]";
			
				
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			print "<option value='$row[location]'";
			if ($row[location] == $LOC) { 
				print " selected";
			}
			print ">$row[description]</option>\n";
		}
	  ?> 	 
      </select></td>
	  </tr>
	  <tr>
	  	<td style="text-align: left;"><strong>Year:</strong></td>
	<td style="text-align: left">

      <select name="ddlYear"  id="ddlYear" style="width:200px;">
          <option value="" selected="selected">Please Choose</option>
		  <!--<option value="2009">2009</option>
		  <option value="2010">2010</option>
		  <option value="2011">2011</option>
		  <option value="2012">2012</option>
		  <option value="2013">2013</option>
		  <option value="2014">2014</option>
		  <option value="2015">2015</option>-->
		  
			<? 
				$j = 1;
				while($j <= sizeof($years))
				{
			
					print "<option value=$years[$j]";
						if ($years[$j] == $Year) 
						{ 
							print " selected";
						}
					print ">$years[$j]</option>\n";
					$j++;
				}
			?>
      </select>	  </td>
	  </tr>
	  
	  	  <tr>
	  	<td style="text-align: left;"><strong>Month:</strong></td>
	<td style="text-align: left">

      <select name="ddlMonth"  id="ddlMonth" style="width:200px;">
          <option value="" selected="selected">Please Choose</option>
		  <? 
				$i = 1;
			while($i <= sizeof($months))
			{
		
				print "<option value=$i";
					if ($i == $Month) 
					{ 
						print " selected";
					}
				print ">$months[$i]</option>\n";
				$i++;
			}
		?>		  
      </select>	  </td>
	  </tr>

	  
	  <tr>
	    <td style="text-align: left;">&nbsp;</td>
	    <td style="text-align: left"> <input class="WSGInputButton" type="submit" name="submit" value="Search" onclick="JavaScript:return Validate()"/></td>
      </tr>
	  <tr>
	    <td colspan="2" style="text-align: left;">
		<? if($LOC!="" && $Year!="" && $Month!="") {?>
		 <div class="blue_button">
    <a href="CreateCostSummaryBySite.php?loc=<?=$LOC; ?>&Year=<?=$Year; ?>&Month=<?=$Month; ?>&monthDesc=<?=$monthDesc?>">Create New Summary for <?=$locationDes;?> <?=$monthDesc;?> <?=$Year?></a>
 </div>
 <? } ?>
	
		</td>
      </tr>
  </table>

 <br />
     <table border="1" width="50%" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#FFFFFF" class="black_small" style=" border-collapse:collapse;">
      <tr>
        <td align="center" class="ColumnHeader"><strong>Location</strong></td>
        <td align="center" class="ColumnHeader">Year</td>
		 <td align="center" class="ColumnHeader"><strong>Month</strong></td>
         <td align="center" class="ColumnHeader"></td>
      </tr>
	  <?
	  	$qry = " SELECT DISTINCT cs.location,cs.currentYear,cs.currentMonth,l.description 
				 FROM 
				 	COMMON.dbo.prm_rnet_CostSummaryBySite (NOLOCK) cs
				 LEFT JOIN
					ctlLocations l
				 ON
					cs.location = l.location".$Filter;
					
		$rst=mssql_query(str_replace("\'","''",$qry), $db);
		
			$i = 0;
			while ($row=mssql_fetch_array($rst)) 
	 		{
			
				$locDec = $row[description];
				$locID = $row[location];
				$currYear = $row[currentYear];
				$currMon = $row[currentMonth];
				
				if($i % 2) 
				{
					echo "<TR bgcolor=\"#EFEFEF\">"; 
				} 
				else 
				{ 
					echo "<TR bgcolor=\"white\">"; 
				}
				?>
				<td><? echo "$locDec"; ?></td>
        		<td><? echo "$currYear"; ?></td>
        		<td><? echo $months[$currMon]; ?></td>
				<td><a href="EditCostSummaryBySite.php?loc=<?=$locID; ?>&Year=<?=$currYear; ?>&Month=<?=$currMon; ?>&monthDesc=<?=$months[$currMon]?>">Edit</a></td>
				</tr> 
	  <?	
			$i++;
			}
	  ?>
    </table>
  
  
  <? 
  if($result=="existed")
  {
  echo "<script type='text/javascript'>alert('$locationDes $monthDesc $Year already existed');</script>";
  }
  
  
   ?>
  
</form>
</div>
</body>
</html>
