<?php ob_start();
#=======================================================================
#                   FUNCTIONS TEMPLATE 
#=======================================================================
# THIS TEMPLATE CONTAINS CODE ALREADY WRITTEN TO HELP YOU QUICKLY 
# AND EASILY  START WRITING ADDONS FOR WANNI CMS.
# 
#				DO NOT EDIT OR TAMPER 
#		[UNLESS YOU ABOLUTELY KNOW WHAT YOU ARE DOING]	 
# ---------------------------------------------------------------------
#					 TEMPLATE STARTS
#----------------------------------------------------------------------

# 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

//print_r($_POST);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function set_gateway(){
	
if($_POST['submit'] === 'Save Gateway'){
	$gateway_name = trim(mysql_prep($_POST['gateway_name']));
	$gateway_path = trim(mysql_prep($_POST['gateway_path']));
	$username = trim(mysql_prep($_POST['username']));
	$password = trim(mysql_prep($_POST['password']));
	$sender_id = trim(mysql_prep($_POST['sender_id']));
	$format = trim(mysql_prep($_POST['format']));
	$route_id = trim(mysql_prep($_POST['route_id']));
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `sms_gateways`(`id`, `gateway_name`, `gateway_path`, `username`, `password`, `sender_id`, `format`, `route_id`, `selected`) 
	VALUES('','{$gateway_name}','{$gateway_path}','{$username}','{$password}','{$sender_id}','{$format}',{$route_id},'')") 
	or die("Failed to save sms gateway" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($query){ 
		status_message('success','Gateway information saved !');
		 }
	}
	
		
	//Select default gateway
	if(isset($_POST['selected_gateway']) && $_POST['save_selected'] == 'Use Selected Gateway'){
		$id = mysql_prep($_POST['selected_gateway']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `sms_gateways` SET `selected`='yes' WHERE `id`='{$id}'") 
		or die("Gateway update error! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			status_message('success','Default Gateway set!');
		}
	}
	//Delete gateway
	if(isset($_GET['remove_gateway']) && $_GET['control'] == $_SESSION['control']){
		$id = mysql_prep($_GET['remove_gateway']);
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `sms_gateways` WHERE `id`='{$id}'") 
		or die("Gateway delete error! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		if($query){
			status_message('alert','Gateway deleted successfully');
			}
	}
echo '<h2>Set Gateway</h2>
<form action="'.ADDONS_PATH .'sms/index.php" method="post">
<input type="text" name="gateway_name" value="" placeholder="Name">
<input type="text" name="gateway_path" value="" placeholder="Gateway">
<input type="text" name="username" value="" placeholder="username">
<input type="password" name="password" value="" placeholder="password">
<input type="text" name="sender_id" value="" placeholder="sender_id">
<input type="text" name="route_id" value="" placeholder="route_id (optional)">
<input type="text" name="format" value="" placeholder="text / json / tab">
<br><input type="submit" name="submit" value="Save Gateway">
</form>';	


 //Get saved gateways
 echo '<h2>Saved Gateways</h2><ol>';
 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`,`gateway_name`,`gateway_path`, `selected` FROM `sms_gateways` LIMIT 10") ;
 
  echo '<form method="post" action="http://'.$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'] .'"><ol>';
 while($result = mysqli_fetch_array($query)){
	 echo '<li> <input type="radio" name="selected_gateway" value="'.$result['id'].'"';
		$selected = '';
		if($result['selected'] === 'yes'){
			echo ' checked';
			$selected = "<strong><em> &nbsp;&nbsp;selected</em></strong>";
			}
		echo '> &nbsp;'.$result['gateway_name'] .' - &nbsp;&nbsp;'.$result['gateway_path'] .$selected .' <aside class="u-pull-right">
	 <a href="'.ADDONS_PATH .'sms?remove_gateway='.$result['id'].'&control='.$_SESSION['control'].'">delete </a></aside></li><hr>';
	 }
	 echo '<input type="submit" name="save_selected" value="Use Selected Gateway">'; echo '</ol></form>';
}

function select_message_template(){
	
	
}


function get_users_numbers(){
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `user_name`, `phone` FROM `user` WHERE `phone` !='' LIMIT 25") 
	or die("User selection failed in get_users_numbers " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	
	
	 echo '<h2>Send Sms </h2>';
			
	 echo '<form method="post" action="http://'.$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'].'">
	 <input type="hidden" name="control" value="'.$_SESSION['control'].'">
	 <input type="checkbox" name="select_all"> Select all <hr><ol>';
	while($result = mysqli_fetch_array($query)){
		echo '<li>'.
		//<input type="hidden" name="users[]" value="'.$result['user_name'].'">
		'<input type="checkbox" name="numbers[]'.$result['id'].'" value="'.$result['phone'].'">&nbsp;' .$result['user_name'] . '&nbsp;| &nbsp;'.$result['phone'] 
			.'<span class="u-pull-right"><a href="'.ADDONS_PATH.'sms/send_message.php?to='.$result['phone'].'&user='.$result['user_name'].'">Sms me </a></span><hr>';
		
		
	} 
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sms_templates`") 
	or die("SMS template selection failed! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	while($template = mysqli_fetch_array($query)){
		echo '</ol><select name="template">
					<option value="'.$template['message'].'">'.$template['subject'].'</option>
			</select>';}
	echo '<input type="submit" name="continue" value="continue to SMS">
			</form>';
	
	
}

function get_gateway_info(){
	
	// Get gateway information	
	 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `sms_gateways` WHERE `selected`='yes' LIMIT 1") 
	 or die("No Gateway selected " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	 $num = mysqli_num_rows($query);
	 if($num < 1){
		 status_message('alert', 'Either you have not saved any gateway or you have NOT SELECTED any for use.<br>Select one in Saved Gateways');
		 }
	$result = mysqli_fetch_array($query);
		
		return $result;
		
}

function sms_notify($sender='',$reciever='',$message='',$sms_flag=''){
	if(string_contains($content,'!mportant')){
		$sms_flag = '!mportant';
		}
	if(!in_array($reciever,$_SESSION['recently_logged_in']) || $sms_flag == '!mportant'){
		$result = get_gateway_info();
		$gateway = $result['gateway_path']; 
		$username = $result['username'];
		$password = $result['password'];
		$sender_id = $result['sender_id'];
		$format = $result['format'];
		$route_id = $result['route_id'];
	
		$user = get_user_details($reciever);
		$to = $user['phone'];
		
		$msg = "{$sender} > {$reciever} : '{$message}'...Log in to www.GeniusAid.org to respond";
		$formatted_message = str_ireplace(' ','+',$msg);
		$send = $gateway."/?username={$username}&password={$password}&sender={$sender_id}&to={$to}&message={$formatted_message}&reqid=#&format={$format}&route_id={$route_id}&unique=1";
		$output = curl_get($send);
		return $output;
	}
}

function send_sms_bulk(){
//print_r($_POST);
if(isset($_POST) && $_POST['control'] == $_SESSION['control']){
	
	// Get numbers
	if(!empty($_POST['numbers'])){
		$to = implode(',', $_POST['numbers']);
		}
		echo 'numbers = '. $to;
		
	//Get users
	if(!empty($_POST['users'])){
		$users = implode(',', $_POST['users']);
		}
		
		$result = get_gateway_info();
		
		$gateway = $result['gateway_path']; 
		$username = $result['username'];
		$password = $result['password'];
		$sender_id = $result['sender_id'];
		$format = $result['format'];
		$route_id = $result['route_id'];
		
$_SESSION['sms_part1'] = $gateway."/?username={$username}&password={$password}&sender={$sender_id}&to={$to}";
$_SESSION['sms_part2'] = "&reqid=#&format={$format}&route_id={$route_id}&unique=1";	
$_SESSION['sms_to'] = $to;		
echo '<form method="post" action="'. ADDONS_PATH .'sms/process.php">
<input type="hidden" name="users" value="'.$users.'">
<input type="hidden" name="control" value="'.$_SESSION['control'].'">
<textarea name="message" placeholder="Your message here"></textarea>
<input type="submit" name="submit" value="Send">
</form>';
	
	
	}
}

function check_balance(){

	echo '<div id="sms_balance">
	<a href="http://smsc.xwireless.net/API/WebSMS/Http/v3.1/index.php?method=credit_check&username=genai&password=blairson123&format=text"><button>Check SMS Balance</button></a>  
	</div>';
	
}	


 // end of sms functions file
 // in root/sms/includes/functions.php
?>
