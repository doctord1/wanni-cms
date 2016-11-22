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

# This gives you access too core functions and variables.
#  It can be optional if you want your addon to act independently. 
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
include_once($r .'includes/functions.php'); #do not edit
#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW
//print_r($_POST);

$parent = $_POST['parent'];
$child_name = $_POST['child_name'];
$owner = $_SESSION['username'];
$path = $_POST['path'];
$follow_list_name = $_POST['follow_list_name'];


function follow( $child_name =''){ 
	// this is the processing code to follow,
	//not the button
	// button code is in show_user_folloe_button();
	
	if(isset($_POST['follow'])){
	$parent = $_POST['parent'];
	if($child_name === '' && !empty($child_name)){
		$child_name = trim(mysql_prep($_POST['child_name']));
	}
	$owner = $_SESSION['username'];
	$path = $_POST['path'];
	$follow_list_name = $_POST['follow_list_name'];

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `follow` (`id`, `parent`, `child_name`, `owner`, `path`, `follow_list_name`) 
	VALUES('0','{$parent}','{$child_name}','{$owner}','{$path}','{$follow_list_name}')") or die 
	('Failed to Log follow intent!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 

	if($query){
	 $destination= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	 header("Location: $destination"); exit;
	}
	
	}
	
}

function promote($child_name=''){
	
	if(!empty($_GET['action']) && $_GET['action']==='promote'){
		if(isset($_GET['parent'])){
			$parent = trim(mysql_prep($_GET['parent']));
			} else {$parent = '';}
		$destination = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$path = $destination;
		
		if($_SESSION['role']==='manager' || $_SESSION['role']==='admin'){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `follow`(`id`, `parent`, `child_name`, `owner`, `path`, `follow_list_name`) 
			VALUES ('', '{$parent}', '{$child_name}', 'site', '{$path}', 'promote')");
			if($query){ status_message('success', 'Post is now promoted!');
				 header("Location: $destination"); exit;
			}
			
		}
	}
	
}

function unfollow($parent='',$child_name=''){
	
	$child_name = trim(mysql_prep($child_name));
	$owner= $_SESSION['username'];
	$path = $_POST['path'];
	$return_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	if(isset($_POST['unfollow'])){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `follow` WHERE `child_name`='{$child_name}' AND `owner`='{$owner}' AND `parent`='{$parent}'");
	
	if($query){
		header("Location: $return_url"); exit;
		}
	}

}

 
function show_user_follow_button($child_name='',$parent=''){
	
	//This function is responsible for showing the buttons
	
	if(isset($_SESSION['username'])){
		$owner = $_SESSION['username'];
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `follow` WHERE `parent`='{$parent}' AND `child_name`='{$child_name}' 
		AND `owner`='{$owner}' AND `follow_list_name`='follow' ") or die ("Failed to get follow state" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$count = mysqli_num_rows($query);	
	#echo $count;
	if($count < 1){
		echo '<form class="inline" method="post" action="http://'.$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'].'">
		<input type="hidden" name="parent" value="'.$parent.'">
		<input type="hidden" name="child_name" value="'.$child_name.'">
		<input type="hidden" name="path" value="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
		<input type="hidden" name="follow_list_name" value="follow">
		<input type="submit" name="follow" value="follow" class="button-primary">
		</form>';
	} else if($count > 0) {
		
		echo '<form class="inline" method="post" action="http://'.$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'].'">
		<input type="hidden" name="parent" value="'.$parent.'">
		<input type="hidden" name="child_name" value="'.$child_name.'">
		<input type="hidden" name="path" value="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
		<input type="hidden" name="follow_list_name" value="follow">
		<input type="submit" name="unfollow" value="unfollow" class="button">
		</form>';
		}
		
	}
	
}

	
function user_follow_list($follow_list_name=''){
if(!empty($_SESSION['username']) && !empty($_GET['user'])){
$user = trim(mysql_prep($_GET['user']));
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `parent`, `child_name`, `path` FROM `follow` 
WHERE `owner`='{$user}' AND `follow_list_name`='{$follow_list_name}'") or die
("failed to get follow list!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

echo '<div class="container">
		<div class="sweet_title">Posts I am Following </div>';

while($result=mysqli_fetch_array($query)){
	echo '<a href="'.$result['path'].'">'
	.str_ireplace('-',' ',$result['child_name']).'</a>'
	.'<div class="tiny-text">'
	.$result['parent'].'</div><hr>';
}	
 echo '<div>';
}
}

function site_follow_list($follow_list_name=''){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `parent`, `child_name`, `path` FROM `follow` 
	WHERE `owner`='{$user}' AND `follow_list_name`='{$follow_list_name}'") or die
	("failed to get follow list!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	echo '<br><hr><br><h2 class="sweet_title">'
	.ucfirst($result['follow_list_name'])
	.'</h2><div class="page-content">';

	while($result=mysqli_fetch_array($query)){
	echo '<a href="'.$result['path'].'">'
	.$result['child_name'].'</a>'
	.'<div class="tiny-text">'
	.$result['parent'].'</div><br><hr>';
	}	
	
	echo '</div>';
	
}
	
function promoted_posts(){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `parent`, `child_name`, `path` FROM `follow` 
	WHERE `owner`='{$user}' AND `follow_list_name`='promoted'") or die
	("failed to get follow list!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	echo '<br><hr><br><h2 class="sweet_title">'
	.ucfirst($result['follow_list_name'])
	.'</h2><div class="page-content">';

	while($result=mysqli_fetch_array($query)){
	echo '<a href="'.$result['path'].'">'
	.str_ireplace('-',' ',$result['child_name']).'</a>'
	.'<div class="tiny-text">'
	.$result['parent'].'</div><br><hr>';
	}	
	
	echo '</div>';
	
}



?>
