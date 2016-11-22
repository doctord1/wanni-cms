<?php require_once('../includes/session.php');
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#=======================================================================



?>

<?php
if($_GET['action']==='edit_package'){
go_back();
edit_package();	
}

if($_GET['action']==='delete_package'){
go_back();
delete_package();	
}

	$id = $_POST['id'];
	$package_name = trim(mysql_prep($_POST['package_name']));
	$sender_name = trim(mysql_prep($_POST['sender_name']));
	$reciever_name = trim(mysql_prep($_POST['reciever_name']));
	$sender_location = trim(mysql_prep($_POST['sender_location']));
	$submitted = trim(mysql_prep($_POST['submitted']));
	$reciever_location = trim(mysql_prep($_POST['reciever_location']));
	$description = trim(mysql_prep($_POST['description']));
	$description = trim(mysql_prep($_POST['description']));
	$status = trim(mysql_prep($_POST['status']));
	$tracking_no = trim(mysql_prep($_POST['tracking_number']));	



if (isset($_POST['submitted'])){
			
			$edit_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `courier` SET `package_name`='{$package_name}', `sender_name`='{$sender_name}', `reciever_name`='{$reciever_name}', `sender_location`='{$sender_location}', `reciever_location`='{$reciever_location}', `description`='{$description}', `status`='{$status}', `tracking_no`='{$tracking_no}' 
			WHERE `id`='{$id}'") 
			or die ("Database insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		 if($edit_query) {
			echo "<div class='message-notification'> Package edited and saved !</div>";
			go_back();
			}		
	}
	



?>



