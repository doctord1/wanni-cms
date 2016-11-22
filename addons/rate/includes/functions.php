<?php
#=======================================================================
#                   FUNCTIONS TEMPLATE 
#=======================================================================
# THIS TEMPLATE CONTAINS CODE ALREADY WRITTEN CODE TO HELP YOU QUICKLY 
# AND EASILY  START WRITING ADDONS FOR WANNI CMS.
# 
#				DO NOT EDIT OR TAMPER 
#		[UNLESS YOU ABOLUTELY KNOW WHAT YOU ARE DOING]	 
# ---------------------------------------------------------------------
#					 TEMPLATE STARTS
#----------------------------------------------------------------------

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function add_rate_type(){
	
	$rate_type_name = trim(mysql_prep($_POST['rate_type_name']));
	$rate_value = trim(mysql_prep($_POST['rate_value']));
	$rate_text = trim(mysql_prep($_POST['rate_text']));
	$unrate_text = trim(mysql_prep($_POST['unrate_text']));
	$submit = trim(mysql_prep($_POST['submit']));
	
	if($submit ==='submit'){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `rate_type`(`id`, `rate_type_name`,
	 `rate_value`, `rate_text`, `unrate_text`) 
	VALUES ('','{$rate_type_name}', '{$rate_value}', '{$rate_text}', '{$unrate_text}')") 
	or die("rating type Insert Failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){ session_message("success","rate type saved!"); 
	redirect_to($_SERVER[HTTP_REFERER]);}
	}
	
	echo '<div class="holder"><h2>Add a new rate type</h2>
	<form method="post" action="http://'. $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI'].'">	
	Rate Type name: <br><input type="text" name="rate_type_name" value="" placeholder="">
	<br>Rate text: (<em>eg: rate, like, support, thumbs up</em>)<br>
	<input type="text" name="rate_text" value="" placeholder="rate">
	<br>Unrate text (<em>eg: unrate, dislike, withdraw-support, thumbs down </em>)<br>
	<input type="text" name="unrate_text" value="" placeholder="unrate"><br><br>
	<input type="submit" name="submit" value="submit" class="submit">
	</form></div>';
	
	
}

function list_rate_types(){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `rate_type`") 
	or die ("Error selecting rate types!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	echo "<h2>Rating types</h2><ol>";
	while($result = mysqli_fetch_array($query)){
		echo '<li>'.$result['rate_type_name'].' </li>';
		}
		echo '</ol>';
	}
	
function rate_user($rate_type=''){
	if($rate_type ===''){
	$rate_type = 'efficiency';	
	}
	$ratee = trim(mysql_prep($_GET['user']));
	
	$path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
	$poor_val = get_rate_count($rate_type='poor');
	$good_val = get_rate_count($rate_type='good');
	$excellent_val = get_rate_count($rate_type='excellent');
	echo '<br><hr><h2>Rate me</h2>
	<a href="'.ADDONS_PATH.'rate/process.php?action=do_rate&rate_type='.$rate_type.'&rate_value=poor&path='.$path.'&ratee='.$ratee.'"><div class="rate-poor">poor'.$poor_val.'</div></a>
	<a href="'.ADDONS_PATH.'rate/process.php?action=do_rate&rate_type='.$rate_type.'&rate_value=good&path='.$path.'&ratee='.$ratee.'"><div class="rate-good">good'.$good_val.'</div></a>
	<a href="'.ADDONS_PATH.'rate/process.php?action=do_rate&rate_type='.$rate_type.'&rate_value=excellent&path='.$path.'&ratee='.$ratee.'"><div class="rate-excellent">excellent'.$excellent_val.'</div></a><br>';
}

function get_rate_count($rate_type){
	$count = 0;
	$path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` from `rate` where rate_type='".$rate_type."' AND `path`='".$path."'") 
	or die('Failed to get rate value' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$count = mysqli_num_rows($query);
	$num = '(' .$count .')';
	return $num;
}
 // end of rate functions file
 // in root/rate/includes/functions.php
?>
