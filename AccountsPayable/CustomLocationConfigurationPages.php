<?
session_start();
include($_SERVER['DOCUMENT_ROOT']."/Include/authenticate.inc.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/Include/Global.inc.php");

$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
mssql_select_db(MSSQL_DB);

if($_REQUEST['location'])
{
	$ddlLocations = $_REQUEST['location'];
}

//limited access to locations 

$Filter .= " WHERE a.location in".$Me->Locations;

if($_REQUEST[ddlLocations]){
$Filter .= " AND a.location LIKE ".$_REQUEST[ddlLocations];
}

$query = " SELECT [location]
				  ,[description]
		FROM  [ctlLocations]  (NOLOCK)                   
		Where location='".$ddlLocations."'"; 

	$locRst=mssql_query($query, $db);
	if ($row=mssql_fetch_array($locRst)) 
	{	
		  $locationDes = $row[description];
	}


	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Custom Location Configuration Pages</title>
<link href="../Include/CSS/main.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
	function Validate()
	{ 
		var elementRef1 = document.getElementById('ddlLocations').value;
		if(elementRef1 =="")
		{ 
			alert("Please Select  Location"); 
			document.form_data.ddlLocations.focus();			
			return false;
		}
	}
</script>
</head>
<body >
<div id="LOGO">
<div id="userinfo">	
		<?php include("../Include/class/DisplayUserinfo.php");?>
    </div>
	<div id="menu1">  <a href="../Clients/Results/index.php">Main Menu</a>
    <? include($_SERVER['DOCUMENT_ROOT']."/javascript_menu.php");?>
  </div>
</div>
<div id="content" style="padding-left:10px;">
  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form_data" id="form_data">
    <table width="770" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#CCCCCC" class="black_small" style="border-collapse:collapse;">
      <tr>
        <td colspan="7" align="left" bgcolor="#CCCCCC" class="ColumnHeader">Custom Location Configuration Pages</td>
      </tr>
      <tr>
        <td width="128" style="text-align: right; width: 112px;">Location</td>
        <td width="629" style="text-align: left;"><select name="ddlLocations"  id="ddlLocations" style="width:200px;">
            <option value="">Please Choose</option>
            <?php
			$SQL=" SELECT [location]
					  ,[description]
					  ,[city]
					  ,[state]
			FROM  [ctlLocations] (NOLOCK)                    
			Where State is not null 
			and location in ".$Me->Locations." 
			and switch ='N' AND productionReportDisplay = 'Y'
			and location = '384'
			order by [description]";

				
		$rst=mssql_query($SQL, $db);		
		
		while ($row=mssql_fetch_array($rst)) {
			print "<option value='$row[location]'";
			if ($row[location] == $ddlLocations) { 
				print " selected";
			}
			print ">$row[description]</option>\n";
		}
	  ?>
          </select></td>
      </tr>
    </table>
    <input class="WSGInputButton" type="submit" name="submit" value="Search" onclick="JavaScript:return Validate()" />
  </form>
  <?php 
  
  if(isset($_REQUEST['submit']) || isset($_REQUEST['location']))
  {	 
  $sql="SELECT * from ctlLocationWebPages (NOLOCK) a  
  join ctlLocations (NOLOCK) b on a.location = b.location
  $Filter
  AND a.rnetModule = 'Accounts Payable' AND a.active = 'Y'
  order by a.pageDescription ";

  $rst=mssql_query($sql, $db);
  
  
  print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" bordercolor=\"#CCCCCC\" class=\"black_small\" style=\"border-collapse:collapse;\">
		<tr>
		  <td align=\"left\" bgcolor=\"#CCCCCC\" class=\"ColumnHeader\">".$locationDes."  Pages</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		";
	
  while ($row=mssql_fetch_array($rst)) {
  
			print "<tr class=\"blue_large\">\r\n";
			print "<td><a href=\"$row[url]?location=$row[location]\">$row[pageDescription]</a></td>\r\n";
			print "</tr>\r\n";
		}
	}	
  ?>
</div>
</body>
</html>
