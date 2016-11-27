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
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

//print_r($_POST);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function simplepay_payment(){
	
echo '<form method=post action=https://simplepay4u.com/process.php>
<input type=hidden name=member value="useraccount@simplepay4u.com">
<input type=hidden name=escrow value="N">
<input type=hidden name=action value="payment">
Fund Your Account with SimplePay :<br>
<input type=text name=price value="">
<input type=hidden name=quantity value="1">
<input type=hidden name=ureturn value="'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
<input type=hidden name=unotify value="'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
<input type=hidden name=ucancel value="'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
<input type=hidden name=comments value="Fund Account">
<input type=hidden name=customid value="SP76693">
<input type=hidden name=freeclient value="N">
<input type=hidden name=nocards value="N">
<input type=hidden name=giftcards value="Y">
<input type=hidden name=chargeforcard value="Y">
<input type=hidden name=site_logo value="'.BASE_PATH.'uploads/files/default_images/logo.png"><br>
<input type=image src="'.BASE_PATH.'uploads/files/default_images/Pay-now.png">
</form>';

}




function voguepay_webview(){
		
echo '<iframe width="auto" height="auto" frameborder="0" scrolling="auto" seamless="seamless" src="https://voguepay.com/webview_embed/webview_token/GUYUn5kXxQCRCfRZVPa.13767/width/200" ></iframe>';	
	
}


function request_payout(){
	$user = $_SESSION['username'];
	if($_POST['submit_payout'] ==='Payout' 
	&& $_GET['action'] === 'payment_requested' 
	&& $_GET['control'] == $_SESSION['control']){
		if($_POST['amount'] <= $_SESSION['site_funds_amount']){
		$amount = trim(mysql_prep($_POST['amount']));
		} else { die("You have requested an invalid amount !"); }
		$request_time = date('c');
		
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `payouts`(`id`,`owner`,`amount`,`status`,`request_time`,`payout_time`,`balance`) 
		VALUES('0','{$user}','{$amount}','payment requested','{$request_time}','','{$_SESSION['site_funds_amount']}')") 
		or die('Failed to complete payment request! ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message('alert','Your payout request has been sent!!<br> Expect response within 48 hrs.');
			link_to(BASE_PATH."user/?user=".$_SESSION['username'],'Return to profile');
			}
		}
		
	if($_GET['action'] === 'payment_requested' && is_logged_in()){
		$user = $_SESSION['username'];
		if($_SESSION['site_funds_amount'] > 1000 ){
			$funds = $_SESSION['site_funds_amount'];
			$form = "<form method='post' action='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."'>
			<em>Your balance is {$funds} site funds</em><br>
			Amount : <input type='number' name='amount' value='{$funds}'>
			<input type='submit' name='submit_payout' value='Payout'><br>
			</form>";
			echo $form;
			} else {
				
				status_message('alert', 'You must have minimum of 1000 to request payout!');
				}
	
	
	}
}

function view_payout_report(){
	
	if($_GET['status'] === 'paid' && $_GET['control'] == $_SESSION['control'] && !empty($_GET['id']) && is_admin()){
		$time = date('c');
		$id = trim(mysql_prep($_GET['id']));
		$amount = trim(mysql_prep($_GET['amount']));
		$reciever = trim(mysql_prep($_GET['owner']));
		transfer_funds('subtract',"{$amount}","system", "{$reciever}");
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `payouts` SET `status`='paid', `payout_time`='{$time}' WHERE `id`='{$id}'") 
		or die("Failed to update payment status " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			status_message('alert','Payout successfull!');
			}
		}
	
	if($_GET['action'] === 'view_payout_reports' ){
		$pager = pagerize();
		$limit = $_SESSION['pager_limit'];
		if(is_admin()){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `payment` {$limit}") 
			or die("Failed to get payout report!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		}else if(is_logged_in()){
			$user=$_SESSION['username'];
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `payment` WHERE `owner`='{$user}' {$limit}") 
			or die("Nothing to report!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			
			$output = "<h1 align='center'>Payout Reports</h1>
				<table class='table'><thead><th>id</th><th>Owner/Requester</th><th>Amount</th><th>Status</th>
				</thead>
				<tbody>";
			while($result = mysqli_fetch_array($query)){
				if($result['status'] !== 'paid'){
					$class = 'red-text';
				$pay_link = "<a href='http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&id={$result['id']}&status=paid&amount={$result['amount']}&owner={$result['owner']}&control={$_SESSION['control']}'><button>Mark paid</button></a>";
				} else if ($result['status'] === 'paid'){
				$class = 'green-text';
				$pay_link = '';
				}
				if(empty($result['payout_time'])){ 
					$result['payout_time'] = 'not paid';
					}
				$output .= "
				<tr><td>{$result['id']}</td><td>{$result['owner']}<br>User balance: {$result['balance']}<br>Requested : <time class='timeago' datetime='{$result['request_time']}'>{$result['request_time']}..</time></td>
				<td>{$result['amount']}</td><td><span class='{$class}'>{$result['status']}</span><br><time class='timeago' datetime='".$result['payout_time']."'>".$result['payout_time']."</time>
				<br>";
				
				if(is_admin()){
					$output .= $pay_link ; 
					}
				echo "</td></tr>";
				}
			$output .= "</tbody></table>";
			
			echo $output;
			echo $pager;
			}
}

	
 // end of support functions file
 // in root/support/includes/functions.php
?>
