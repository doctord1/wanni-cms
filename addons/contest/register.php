<?php
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit


start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#=======================================================================
//print_r($_SESSION); //for testing

echo '<div class="container">';
	
# GET THE VALUES FROM ENTRY FORM
$posted_comment = trim(mysql_prep($_POST['comments']));
$submitted = $_POST['submit'];
$user = $_SESSION['username'];

#GET THE VALUES FROM URL

$url = $_SERVER['HTTP_REFERER'];
//echo $url;
$id = '';
$contestant_name = $_SESSION['username'];
$contest =  $_SESSION['contest_name'];
$contest_entry = trim(mysql_prep($_POST['comments']));
$delete = $_GET['delete_reg'];
$clear = $_GET['clear_comment'];
$date = date('c');
$contest_id = $_SESSION['contest_id'];
if(isset($_POST['contest_id'])){
	$contest_id = $_POST['contest_id'];
	}
$page = $contest;

# SHOW CONTEST ENTRY FORM
if(!empty($_GET['user']) && !empty($_GET['contest_name'])){
	echo "<div class='main-content-region'>";	
	show_contest_registration_form();
	echo "</div>";
	
	echo "<div class='right-sidebar-region'>";
	upload_image();
	echo "</div>";
	}

# HANDLE CLEARING OF ENTRIES AND UNREGISTER ACTIONS

if(!empty($_GET['action']) && $_GET['action'] ==='delete_reg'){
	// Do delete registration
	$do_delete = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `contest_entries` WHERE `contestant_name`='{$_SESSION['username']}' AND `contest_id`='{$contest_id}'") 
	or die("Could not delete registration" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($do_delete){
		set_total_votes();
		echo "<div class='success'><strong>Registration deleted successfully!</strong></div><br>" .
			"You may now go <a href='"  .ADDONS_PATH.'contest/?contest_name='.$contest ."'> Back to contest page</a>"; 
		$_SESSION["{$page}_{$user}_status"] = '';
		}
	
}

if(!empty($_GET['action']) && $_GET['action'] ==='clear_comment'){
	# Do clear entry comment
	
	$do_clear= mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `contest_entries` SET `contest_entry`='' WHERE `contestant_name`='{$_SESSION['username']}' AND `contest_id`='{$contest_id}'") 
	or die("Could not clear entry comment!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
		if($do_clear){
		echo "<div class='message-notification'><strong>Entry comment cleared successfully!</strong></div><br>" .
			"You may now go <a href='" .ADDONS_PATH.'contest/?contest_name='.$contest . "'> Back to contest page</a>"; 
		}
	
	
}


// Handle new contest entry submission

if(isset($submitted)){
	echo "<h1>Registering for the contest : \"{$page }\"</h1>";
	//echo "Session user status is : ".$_SESSION["{$page}_{$user}_status"]; //testing
	if(!empty($_POST['comments']) && ($_SESSION["{$page}_{$user}_status"] === '0')){
		
		$insert_contest_entry_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `contest_entries`(`id`,`contest_id`,`contestant_name`,`contest_entry`,`date`,`votes`) 
		VALUES ('0','{$contest_id}','{$contestant_name}','{$contest_entry}','{$date}','0')")  
		or die("Could not update ENTRY COMMENTS!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($insert_contest_entry_query){
	
			$new_amount = $_SESSION['site_funds_amount'] - 50;
			echo "<div class='alert'> <span class='huge-text'>50 site funds</span> have been subtracted from your account! <br>
			Your new account balance is <span class='huge-text'>{$new_amount}</span> site funds</div>";
			$save_state = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE user SET site_funds_amount='{$new_amount}' WHERE user_name='{$contestant_name}'") 
			or die("Could not update Site funds!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			
		}
		
		if($save_state){
			
			$get_new_amount = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT site_funds_amount FROM user WHERE `user_name`='{$user}'") 
			or die ("Get new amount failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
		}
		if ($get_new_amount) {
			
			//echo "ALL SYSTEMS ARE GO!";
			
			$get_new_amount_result = mysqli_fetch_array($get_new_amount);
			$_SESSION['site_funds_amount'] = $get_new_amount_result['site_funds_amount'];
			$new_amount = $get_new_amount_result['site_funds_amount'];
			
			$log_funds = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) 
			VALUES ('0','system','{$user}','-50','{$date}','contest registration for {$contest}','{$new_amount}')") or die("log funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			add_to_contest_revenue($amount=50);
			//redirect_to($url);
			}
			echo "<div class='success'><strong>Entry Submitted successfully!</strong></div><br>" .
			"<h3><a href='" .ADDONS_PATH.'contest/?reg_user='.$user.'&contest_name='.$contest ."&action=voting'> continue &raquo&raquo</a></h3>"; 
			$_SESSION["{$page}_{$user}_status"] = '1';
		}
		
}
		 
				 

echo '</div>';
?>
