<?php
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

echo '<section class="container">';
echo '<div class="padding-20">';
go_back();

if(is_admin()){	
if(isset($_POST) && $_POST['control'] == $_SESSION['control']){	
	
		$users = $_POST['users']; 
		$to = $_SESSION['sms_to'];
		$part1 = $_SESSION['sms_part1'];
		$part2 = $_SESSION['sms_part2'];
		$message = $_POST['message'];
	
	echo 'You are sending the message :<br>"'.
	$_POST['message']
	.'"<br><strong>To : </strong>'.$to
	.'<br> Do You wish to CONTINUE? if Yes then clik on the \'OK SEND\' Button or go back to make changes';
	
	$send = $part1.'&message='.$message.$part2;

	echo '<p><a href="javascript:void(0)" onclick="location.href='."'" . $send ."'".'"'."><button>OK SEND</button></a></p>";
}

//print_r($_POST);
	
}



echo '</div></section>';
?>



