<?php ob_start();
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

# 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
//echo $r;
require_once($r .'includes/functions.php'); #do not edit

#print_r($_SERVER);
#print_r($_POST);
#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function generate_pins($value=''){
	
	if(!empty($_POST['agent']) 
	&& !empty ($_POST['submit_generate']) 
	&& $_POST['control'] == $_SESSION['control']){
		
	$agent  = trim(mysql_prep($_POST['agent']));
	$value = trim(mysql_prep($_POST['value']));
	if($_POST['number'] === ''){
		$limit = 10;
	} else {
		$limit = trim(mysql_prep($_POST['number']));
		
		}
	
		$num = 0;
		while ($num < $limit){
		$code = rand(10000, 100000);
		$code = sha1($code);
		$code = substr($code, 7, 13);
		echo $code ." - <br>";
		
		$date = date('c');

		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `top_up_pin`(`id`, `top_up_pin`, `value`, `agent`, `used_by`, `date_generated`, `date_used`, `status`) 
		VALUES ('0','{$code}','{$value}','{$agent}','','{$date}', '','unused')") 
		or die('Failed to save generated reg_codes ' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		$num++;
		}
	}

}



function show_gen_pin_form(){
		// Show form
	echo '<h2>Generate Top up pins</h2>
	<form method="post" action="'.$_SERVER['PHP_SELF'].'">
	<input type="hidden" name="control" value="'.$_SESSION['control'].'">
	<input type="number" name="number" value="" placeholder="number of codes to generate">
	<input type="number" name="value" value="" placeholder="cash value">
	<input type="text" name="agent" value="" placeholder="agent name">
	<input type="submit" name="submit_generate" value="Generate codes for this Agent">
	</form>';
	
	}
	
	
function show_used_pins($start,$stop){
	
		echo '<h2>Used pins</h2>
	<form method="post" action="'.$_SERVER['PHP_SELF'].'">
	<input type="text" name="agent" value="" placeholder="agent name">
	<input type="submit" name="show_used_pins" value="Show used codes by this agent">
	</form>';
	
	$agent = trim(mysql_prep($_POST['agent']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `top_up_pin` WHERE `agent`='{$agent}' AND `status`='used'") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = 1;
	if(isset($_POST['show_used_pins'])){
		echo "
		<table class='table'><thead><th></th><th>Top up pins</th><th>Value</th><th>Used by</th><th>Date used</th></thead>";
	
		while($result = mysqli_fetch_array($query)){
		echo "<tr>
		<td>{$num}</td><td>{$result['top_up_pin']}</td><td>{$result['value']}</td><td>{$result['used_by']}</td><td>{$result['date_used']}</td>
		</tr><hr>";
		$num++;
		}echo '</table>';
	}
}

function show_unused_pins(){
	echo '<h2>Unused pins</h2>
	<form method="post" action="'.$_SERVER['PHP_SELF'].'">
	<input type="text" name="agent" value="" placeholder="agent name">
	<input type="submit" name="show_unused_pins" value="Show unused codes by this Agent">
	</form>';

	if(isset($_POST['show_unused_pins'])){
	$agent = trim(mysql_prep($_POST['agent']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `top_up_pin` WHERE `agent`='{$agent}' AND `status`='unused'") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = 1;
	echo "
		<table class='table'><thead><th></th><th>Top up pin</th><th>Value</th><th>Used by</th><th>Date</th></thead>";
	while($result = mysqli_fetch_array($query)){
		echo "<tr>
		<td>{$num}</td><td>{$result['top_up_pin']}</td><td>{$result['value']}</td><td>{$result['used_by']}</td><td>{$result['date']}</td>
		</tr>";
		$num++;
		}echo '</table>';
		}
}

function total_generated_pins(){
	
}


function load_top_up_pin(){
	
	echo '<form method="post" action="'.$_SESSION['current_url'].'">
	<h2>Load top up pin </h2>
	<input type="hidden" name="action" value="load_top_up">
	<input type="hidden" name="reason" value="Loaded to up pin">
	<input type="hidden" name="pin_value" value="">
	<input type="text" name="top_up_pin" value="" placeholder="top up pin">
	<input type="text" name="account_to_top_up" value="'.$_SESSION['username'].'" placeholder="User name to Top up">
	<input type="submit" name="submit" value="Load pin" >
	</form>';
	
	if((!empty($_POST['submit']) && !empty($_POST['top_up_pin'])) 
	|| ($_POST['action'] === 'load_top_up')){
		
	if(!empty($_POST['account_to_top_up'])){
	$account_to_top_up = strtolower(trim(mysql_prep($_POST['account_to_top_up'])));
	} else { 
		status_message('error','account name cannot be empty'); 
		die();
		}
	
	$pin = mysql_prep($_POST['top_up_pin']);
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `top_up_pin` WHERE `top_up_pin`='{$pin}' AND `status`='unused'") 
	or die("Pin selection failed " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if(mysqli_num_rows($query)===1){
		$result = mysqli_fetch_array($query);
		$date = date('d-M-Y');
		
		transfer_funds('add',$result['value'],'system',$account_to_top_up,$reason = 'Loaded top up pin');
		//transfer_funds($action='add',$amount='',$giver='',$reciever='',$reason='')
		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `top_up_pin` SET `used_by`='{$account_to_top_up}',`date_used`='{$date}',`status`='used' 
	WHERE `top_up_pin`='{$pin}'") 
	or die('Failed to update top_up_pin information' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 
		}
	
	}
}


function show_agent_progress(){
	
	$agent = trim(mysql_prep($_POST['agent']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `top_up_pin` WHERE `agent`='{$agent}' ORDER BY id DESC") 
	or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = 1;
	
	echo "<h1>Agent Progress </h1>
	<a href='".BASE_PATH."/funds_manager/?action=load_top_up'><button class='inline-block'>Fund an account</button></a>&nbsp;
	
	<form method='post' action='".$_SESSION['current_url']."'>
	<input type='hidden' name='agent' value='".$_SESSION['username']."'>
	<input type='submit' name='submit' value='My unused pins'>
	</form>";
	
	echo "<table><thead><th></th><th>Reg_code</th><th>Value</th><th>Used by</th><th>Date</th></thead>";
	
	while($result = mysqli_fetch_array($query)){
		
	echo "<tr>
	<td>{$num}</td><td>{$result['top_up_pin']}</td><td>{$result['value']}</td><td>{$result['used_by']}</td><td>{$result['date_used']}</td>
	</tr>";
	$num++;
	}echo '</table>';
	
	
}

 // end of top up pin functions file
 // in root/addons/top_up_pin/includes/functions.php
?>
