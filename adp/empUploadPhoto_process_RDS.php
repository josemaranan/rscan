<?php
//include_once($_SERVER["DOCUMENT_ROOT"].'/Include/logADPPayrollChangesClass.inc.php');
include_once($_SERVER['DOCUMENT_ROOT']."/RNetIncludes/class1/logADPPayrollChangesClass.inc.php");
$employeeeMaintenanceObj = new ADPPayroll();
$employeeID = $_REQUEST['hdnEmployeeID'];
$effectiveDate = date('m/d/Y');
define ("MAX_SIZE","400"); 
$errors=0;
 
if(isset($_POST['Submit'])) 
 {
 	$image=$_FILES['empPhoto']['name'];
 	if ($image) 
 	{
 		$filename = stripslashes($_FILES['empPhoto']['name']);
  		$extension = $employeeeMaintenanceObj->getExtension($filename);
 		$extension = strtolower($extension);
 		if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
 		{
			$errors=1;
 			//$err = "Unknown extension!";
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empUploadPhoto&error=ErrUpload1&activeLink=");
			exit;
 			
 		}
 		else
 		{
			 $size=filesize($_FILES['empPhoto']['tmp_name']);
			
			if ($size > MAX_SIZE*1024)
			{
				//$err = "You have exceeded the size limit!";
				$errors=1;
				header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empUploadPhoto&error=ErrUpload2&activeLink=");
				exit;
				
			}

		$image_name='Emp'.$employeeID.'.'.$extension;
		//$upload_dir=$_SERVER['DOCUMENT_ROOT']."/Users/Recruitment/SoundFiles/";
		$upload_dir=$_SERVER['DOCUMENT_ROOT']."/Users/ApplicantsPhotos/";
		
		$newname= $upload_dir.$image_name;
		
		$imageUrl = "/Users/ApplicantsPhotos/".$image_name;
		$copied = copy($_FILES['empPhoto']['tmp_name'], $newname);
		if (!$copied) 
		{
			//$err = "File Not Uploaded Successfully! Try again!";
			$errors=1;
			header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empUploadPhoto&error=ErrUpload3&activeLink=");
			exit;
		}

	}  // elkse

}	// if image

}

if(isset($_POST['Submit']) && !$errors) 
{
	$query = " 	UPDATE 
					[ctlEmployees] 
				SET 
					imageUrl = '$imageUrl'
				WHERE 
					employeeID = $employeeID ";
	$result = $employeeeMaintenanceObj->execute($query);
	
	$err = "File Uploaded Successfully";
}

if($err == "File Uploaded Successfully")
{
	header("Location: index_test.php?hdnEmployeeID=".$employeeID."&adpMode=hr&adpTask=empUploadPhoto&error=DataIUpdate&activeLink=");
	exit;
}
?>