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


if(isset($_GET['path'])){
	$path = trim(mysql_prep($_GET['path']));
}
if(isset($_GET['ratee'])){
	$ratee = trim(mysql_prep($_GET['ratee']));
} else { die('Rating cannot be saved because you are not rating anyone');
	go_back();
	}
if(isset($_GET['rate_type'])){
	$rate_type = trim(mysql_prep($_GET['rate_type']));
} else {die('What type of rating is this?'); 
	go_back();
	}

if(isset($_GET['rate_value'])){
	$rate_type = trim(mysql_prep($_GET['rate_value']));
}else {die('What type of rating is this? no rate value'); 
	go_back();
	}
	
	if(isset($_SESSION['username'])){
	$rater = $_SESSION['username'];	
	}
	$date = date('c');
	
if(isset($_GET['action']) && $_GET['action'] === 'do_rate'){	
	if($rater !== $ratee || is_admin()){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `rate`(
		`id`, 
		`path`,
		`rate_type`, 
		`rate_value`, 
		`rater`, 
		`ratee`, 
		`date`) 
		VALUES ('0','{$path}','{$rate_type}','{$rate_value}','{$rater}','{$ratee}','{$date}')") 
		or die('Error saving rate' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
		session_message('success','You have just rated me, thanks!');
		redirect_to($path);
		}
	} else { session_message('error','Sorry, you cannot rate yourself!');
		redirect_to($path);
		}
}

?>



