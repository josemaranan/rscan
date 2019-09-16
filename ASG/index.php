<?php
/*
$db = mysql_connect('localhost', 'RNetMySqlServer', 'results123');
$rv = mysql_select_db('RNet', $db);
$userId = '';


if (!$db) 
{
    die('Could not connect: ' . mysql_error());
}
*/
//echo 'Connected successfully';
//mysql_close($link);
$UNIQUE_ID = '';
$FIRST_NAME = '';
$AGENCY_NAME = '';
$RENEWAL_MONTH = '';
$INSURANCE_COMPANY = '';
$ADDRESS1 = '';
$ADDRESS2 ='';
$HOME_INSURANCE = '';
$PERSONAL_PROPERTY_INSURANCE = '';
$LIABILITY_INSURANCE = '';
$THUMBNAIL = '';
$LANDING_PAGE_URL ='';
$PHONE = '';
$LAST_NAME = '';
$EMAIL = '';
$FILENAME = '';
$BLAST_ID = '';
$STATUS = '';
$COMPANY_LOGO = '';
$SENT_COUNT = '';
$LAST_SENT_DATE = '';
$PROCESSEDDATE = '';


if(isset($_REQUEST['userID']))
{
	$UNIQUE_ID = $_REQUEST['userID'];
}

/*
$SQL = "SELECT * FROM prmASGCustomerPURLsLoad WHERE UNIQUE_ID = ".$UNIQUE_ID." ";

$rst=mysql_query(str_replace("\'","''",$SQL), $db);
	
while ($row=mysql_fetch_array($rst)) 
{
	*/
	$FIRST_NAME = 'Diane';//$row['FIRST_NAME'];
	$AGENCY_NAME = 'Starkweather & Shepley Insurance Brokerage, Inc.';//$row['AGENCY_NAME'];
	$RENEWAL_MONTH = $row['RENEWAL_MONTH'];
	$INSURANCE_COMPANY = $row['INSURANCE_COMPANY'];
	$ADDRESS1 = $row['ADDRESS1'];
	$ADDRESS2 = $row['ADDRESS2'];
	$HOME_INSURANCE = $row['HOME_INSURANCE'];
	$PERSONAL_PROPERTY_INSURANCE = $row['PERSONAL_PROPERTY_INSURANCE'];
	$LIABILITY_INSURANCE = $row['LIABILITY_INSURANCE'];
	$THUMBNAIL = $row['THUMBNAIL'];
	$LANDING_PAGE_URL = '181/598/6b86b273ff34fce19d6b804eff5a3f57';//$row['LANDING_PAGE_URL'];
	$PHONE = $row['PHONE'];
	$LAST_NAME = $row['LAST_NAME'];
	$EMAIL = $row['EMAIL'];
	$FILENAME = $row['FILENAME'];
	$BLAST_ID = $row['BLAST_ID'];
	$STATUS = $row['STATUS'];
	$COMPANY_LOGO = $row['COMPANY_LOGO'];
	$SENT_COUNT = $row['SENT_COUNT'];
	$LAST_SENT_DATE = $row['LAST_SENT_DATE'];
	$PROCESSEDDATE = $row['PROCESSEDDATE'];

/*
}
*/
?>
<head>
</head>
<body>
<form action="ASG/index.php" method="post" name="asgForm" id="asgForm">
<input type="hidden" name="UNIQUE_ID" id="UNIQUE_ID" value="<?php echo $UNIQUE_ID;?>" />
<input type="hidden" name="FIRST_NAME" id="FIRST_NAME" value="<?php echo $FIRST_NAME;?>" />
<input type="hidden" name="AGENCY_NAME" id="AGENCY_NAME" value="<?php echo $AGENCY_NAME;?>" />
<input type="hidden" name="RENEWAL_MONTH" id="RENEWAL_MONTH" value="<?php echo $RENEWAL_MONTH;?>" />
<input type="hidden" name="INSURANCE_COMPANY" id="INSURANCE_COMPANY" value="<?php echo $INSURANCE_COMPANY;?>" />
<input type="hidden" name="ADDRESS1" id="ADDRESS1" value="<?php echo $ADDRESS1;?>" />
<input type="hidden" name="ADDRESS2" id="ADDRESS2" value="<?php echo $ADDRESS2;?>" />
<input type="hidden" name="HOME_INSURANCE" id="HOME_INSURANCE" value="<?php echo $HOME_INSURANCE;?>" />
<input type="hidden" name="PERSONAL_PROPERTY_INSURANCE" id="PERSONAL_PROPERTY_INSURANCE" value="<?php echo $PERSONAL_PROPERTY_INSURANCE;?>" />
<input type="hidden" name="LIABILITY_INSURANCE" id="LIABILITY_INSURANCE" value="<?php echo $LIABILITY_INSURANCE;?>" />
<input type="hidden" name="THUMBNAIL" id="THUMBNAIL" value="<?php echo $THUMBNAIL;?>" />
<input type="hidden" name="LANDING_PAGE_URL" id="LANDING_PAGE_URL" value="<?php echo $LANDING_PAGE_URL;?>" />
<input type="hidden" name="PHONE" id="PHONE" value="<?php echo $PHONE;?>" />
<input type="hidden" name="LAST_NAME" id="LAST_NAME" value="<?php echo $LAST_NAME;?>" />
<input type="hidden" name="EMAIL" id="EMAIL" value="<?php echo $EMAIL;?>" />
<input type="hidden" name="FILENAME" id="FILENAME" value="<?php echo $FILENAME;?>" />
<input type="hidden" name="BLAST_ID" id="BLAST_ID" value="<?php echo $BLAST_ID;?>" />
<input type="hidden" name="STATUS" id="STATUS" value="<?php echo $STATUS;?>" />
<input type="hidden" name="COMPANY_LOGO" id="COMPANY_LOGO" value="<?php echo $COMPANY_LOGO;?>" />
<input type="hidden" name="SENT_COUNT" id="SENT_COUNT" value="<?php echo $SENT_COUNT;?>" />
<input type="hidden" name="LAST_SENT_DATE" id="LAST_SENT_DATE" value="<?php echo $LAST_SENT_DATE;?>" />
<input type="hidden" name="PROCESSEDDATE" id="PROCESSEDDATE" value="<?php echo $PROCESSEDDATE;?>" />
<input type="hidden" name="utm_source" id="utm_source" value="streamsend" />
<input type="hidden" name="utm_medium" id="utm_medium" value="email" />
<input type="hidden" name="utm_content" id="utm_content" value="<?php echo $UNIQUE_ID;?>" />
<input type="hidden" name="utm_campaign" id="utm_campaign" value="Rnet" />
</form>
</body>
<script type="text/javascript">
function formSubmit()
{
	document.forms['asgForm'].submit();
}
formSubmit();
</script>