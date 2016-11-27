<?php 
#======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#======================================================================

?>

<?php
	
$amount=mysql_prep($_POST['amount']);
get_voguepay_transaction_details($_POST['transaction_id']);
if(! empty($_POST['submit']) && ($_POST['submit']==='Donate')||($_POST['submit']==='Claim this')){

	# Fundraiser processing
	if(isset($_POST['fundraiser_name'])){
$fundraiser_name = trim(mysql_prep(strtolower($_POST['fundraiser_name'])));
} 
 if(isset($_POST['merchant_ref'])){
	}
$amount=mysql_prep($_POST['amount']);
$current_amount = trim(mysql_prep($_POST['current_amount']));
$user_balance = trim(mysql_prep($_POST['user_balance']));
$update_amount = $current_amount + $amount;
$giver = $_POST['giver'];
$reciever = $_POST['reciever'];
$reason = trim(mysql_prep($_POST['reason']));
$time = getdate();
$today = $time['weekday'] .' '. $time['mday'].' '. $time['month'].' '. $time['year'].' '. $time['hours'].':'. $time['minutes'].' :: '. $time['seconds'];

if($user_balance >= $amount){
$update_fundraiser_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `fundraiser` SET `amount_raised`='{$update_amount}' 
WHERE `fundraiser_name`='{$fundraiser_name}'") or die("Fundraiser query error!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$insert_fundraiser_donor_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `fundraiser_donors`
(`id`, `donor`, `amount`, `fundraiser_name`, `recipient`, `date`) 
VALUES ('0', '{$giver}', '{$amount}', '{$fundraiser_name}', '{$reciever}', '{$today}')") 
 or die("Fundraiser_donor insert error!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 
 # Funds manager processing
	//~ transfer_funds($amount,$reciever='system',$giver,$reason=$reason,'subtract');
	set_user_vip_status();
	} else {
	status_message('error','You do not nave sufficent funds to donate that amount<br> Please give what you have.');
	go_back();
	}
}
transfer_funds();
?>



