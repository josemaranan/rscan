<?php


$ldaphost = "atl-dc-1";
$ldapport = 389;   


$admin="srv-ldap-rnet";
$passwd="ALdPbfLvECwrs2mfKCKO123";


$ds = ldap_connect($ldaphost, $ldapport) or die("Could not connect to $ldaphost");
ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3) or die("Could not set ldap protocol version");
if (!ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0)) 
{ 
    exit('Failed to set opt referrals to 0'); 
}


if ($ds) 
{ 
 
    $ldapbind = ldap_bind($ds, $admin, $passwd);


    if ($ldapbind) 
	{
      
    } 
	else 
	{
        echo "connection to windows active directory failed...";
		exit();
    }
	
	include_once("../Include/Global.inc.php");
	
	unset($requestAccount);
	$requestAccount2 = addslashes($_REQUEST[account]);
	$requestAccount = str_replace("\'","''",$requestAccount2); 
	
	$SQL = "
			EXEC RNet.dbo.[report_spRNetUserAuthentication] '".$requestAccount2."' ";
		
			$db=mssql_connect(MSSQL_HOST, MSSQL_USERNAME, MSSQL_PASSWORD);
			mssql_select_db(MSSQL_DB);
			$rst=mssql_query(str_replace("\'","''",$SQL), $db);
			
			
	while ($row=mssql_fetch_array($rst)) 
	{
		$employeeID = $row[employeeID];
		$firstName = $row[firstName];
		$lastName = $row[lastName];
		$location = $row[location];
		$LDAPhost = $row[LDAPhost];
		$middle = $row[middle];
		$emailAddress = $row[emailAddress];
		$extension = $row[extension];
		$displayName = $row[displayName];
		$applicantId = $row[applicantId];
		$locked = $row[Locked];
		$enabled = $row[Enabled];
		$external = $row[External];
		$internal = $row[Internal];
		$corporateAccess = $row[corporateAccess];
		$positionID = $row[positionID];
		$accessedLocations = $row[accessedLocations];
		$opID = $row[opID];
		
	}			
			
	if(substr($accessedLocations, 0, 1) == ',')
	{
			$accessedLocations =  substr($accessedLocations,1);
	}
			
	$accessedLocations = '('.$accessedLocations.')';


	
	$num_records=mssql_num_rows($rst);
	
	mssql_free_result($rst);
	
	
	if($employeeID=='')
	{
		
		print "Please ask the login admin to add your windows account to your profile";
		exit();
	}
	else if($positionID=='')
	{
		echo "You have no positions assigned as primary position, please ask your supervisor to update your profile";
		exit();
	}
	else 
	{

		$sr=ldap_search($ds, $LDAPhost, "sAMAccountName=".$requestAccount);  
		$ent= ldap_get_entries($ds,$sr);
		$dn=$ent[0]["dn"];
			

			
		if($dn == '')
		{
			$sr=ldap_search($ds, $LDAPhost, "sAMAccountName=".$requestAccount); 
			$ent= ldap_get_entries($ds,$sr);
			$dn=$ent[0]["dn"];
		}
			
		if(ldap_count_entries($ds, $sr)==0)
		{
			$message= "Your user is not in the right active directory location, Please ask the LRP to correct your main location";
			header("Location: login_form2.php"."?message=$message");
			exit;
		}
	}
			
	mssql_free_result($rst);
	

		
	if(!$info = ldap_get_entries($ds, $sr))
	{
			
	};
		
		
	for ($i=0; $i<$info["count"]; $i++) 
	{
		
		 $user_cn=$info[$i]["distinguishedname"][0];
    }

	 $passwd=$_REQUEST[password];    
	 
	error_reporting(0);
   
    if (ldap_bind($ds, $user_cn, $passwd)) 
	{	
		session_start();
		
		$agentID = $_SESSION[empID];
		
        session_register('agentCoachempID'); 
        $_SESSION['agentCoachempID'] = $employeeID;
		
		session_register('agentCoachLocID');
		$_SESSION['agentCoachLocID'] = $location;
		

		
		session_register('agentCoachLocIDPositionID');
		$_SESSION['agentCoachLocIDPositionID'] = $positionID;
		
		
		$startTimer = date("m/d/Y H:i:s");
		$cdate = date("m/d/Y");
	
		if($agentID == $employeeID)
		{
			
			header("Location: coachLogin.php?results=both");	
			exit();
		}




		$coachingClient = $_SESSION[agentScoreClient];
		$coachingLob = $_SESSION[agentScoreCardLob_id];
		$scoreCardEntityID = $_SESSION['scoreCardEntityID'];
		
		$query =" EXEC RNet.dbo.process_spStartEmployeeCoachingSession '$agentID', '$location', '$positionID', '$employeeID', '$cdate', '$startTimer', '$coachingClient', '$coachingLob' , '$scoreCardEntityID' ";
			
		$rst=mssql_query(str_replace("\'","''",$query), $db);
		mssql_free_result($rst);
		mssql_close();
		
		header("Location: agentCoachingNew.php");	
		
		
		
		
		
		
    } 
	else 
	{
       echo "Wrong Username and password, please hit back and try again<br /><hr />";
    }
    mssql_close();


} 
else 
{
    echo "<h4>Unable to connect to LDAP server</h4>";
	exit();
}
?>