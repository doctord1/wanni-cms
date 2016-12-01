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
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit

//print_r($_SERVER);
#======================================================================
#						TEMPLATE ENDS
#======================================================================




#				 ADD YOUR CUSTOM ADDON CODE BELOW



function add_funds_form_block(){
	if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
		
		$form = '<div class=""><h2>Add or subtract funds</h2> <form action="' .BASE_PATH .
		'funds_manager/process.php" method="POST" id="add-funds">
		ADD or subtract:<br><select name="action" value="" size="1">
		<option value="add">Add</option>
		<option value="subtract">Subtract</option></select>
		<br><br>Username of Target individual:<br><input type="text" name="reciever" value="" placeholder="user\'s name here">
		<br><br>Add a minus sign to subtract:<br>
		<input type="number" name="amount" value="" size="3" placeholder="amount to add/subtract"><br>
		<br> Reason: <br><textarea size="3" name="reason" value""></textarea><br><br>
		<input type="submit" name="add_funds" class="submit" value="ADD funds" class="submit">
		</form></div><br>';
		
		echo $form;
	}
}


function get_user_funds($user=''){
	if($user == ''){
	$user =  $_SESSION['username'];
	}
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `site_funds_amount` FROM `user` WHERE `user_name`='{$user}'") 
or die("SELECT site funds amount failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
if($query){
	$result = mysqli_fetch_array($query);
	$_SESSION['site_funds_amount'] = $result['site_funds_amount'];
	}
	return $result['site_funds_amount'];
}get_user_funds();

function get_site_funds_history(){
	
		if(isset($_GET['user'])){
			$user = trim(mysql_prep($_GET['user']));
		}else if(isset($_POST['user'])){
		$user = trim(mysql_prep($_POST['user']));
		}
		
		if($user === $_SESSION['username'] || is_admin()){
			
			if(isset($_GET['direction'])){
			$direction = $_GET['direction'];	
			}else if (isset($_POST['direction'])){
			$direction = $_POST['direction'];	
			}
			if($direction === 'incoming' || empty($direction)){
			$fetch_history=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `funds_manager` WHERE `reciever`='{$user}' ORDER BY id DESC LIMIT 15") 
			or die("Failed to fetch ".$user ."'s Incoming transaction history!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			else if($direction === 'outgoing'){
			$fetch_history = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `funds_manager` WHERE `giver`='{$user}' ORDER BY id DESC LIMIT 15") 
			or die("Failed to fetch ".$user ."'s Outgoing transaction history!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			
			if($fetch_history){
				
				if(isset($user)){
				$output = "<h1><p align='center'>";
				$output = $output .ucfirst($user)."'s {$_POST['direction']} transaction history</p></h1>";
				
				$output = $output ."<table class=''><thead><th>&nbsp&nbspID:&nbsp&nbsp</th><th>&nbsp&nbspTransaction Details&nbsp&nbsp</th><th>&nbsp&nbspBalance&nbsp&nbsp</th></thead>";
					
				
					while ($transaction_history = mysqli_fetch_array($fetch_history)){
							
				$output = $output ."<tr><td>" .$transaction_history['id'] ."</td>" .
					"<td class='table-message-plain'><big>" .$transaction_history['amount'] ."</big> site funds <br>" .
					"<strong>Giver:  </strong>" .$transaction_history['giver'] .			
					" | <strong> Reason:  </strong>" .$transaction_history['reason'] .
					" | <strong>Time:</strong> " .$transaction_history['time'] .
					
					"</big></strong>"."</td>";
					
					if($direction === 'outgoing'){
						$output = $output . "<td>" .$transaction_history['balance'] ."</td></tr>";
						} else { $output = $output . "<td><em> not available </em></td>"; }
				
				}$output = $output ."</table><br><br>"; 
				echo '<h2>Transaction History</h2>';
			
			} echo $output;
		}
		# FORM
		echo "<form method='post' action='" .$_SERVER["PHP_SELF"]."'>"
		."Get transaction details for : <br>
		<input type='text' name='user' placeholder='Username here'>
		<select name='direction'>
		<option value='incoming'>Incoming</option>
		<option value='outgoing'>Outgoing</option>
		</select><br>
		<input type='submit' name='submit' value='Submit' class='submit'></form><hr>";
	}
}



function get_voguepay_transaction_details($transaction_id=''){
	
	$response = curl_get("https://voguepay.com/?v_transaction_id={$transaction_id}&type=json");
	//echo $response;
	$voguepay = json_decode($response,true);
	//print_r($vouepay);
	return $voguepay;
}

function record_payment_transaction(){
	$voguepay = get_voguepay_transaction_details($transaction_id);
	
	if(string_contains('addons/fundraiser_name=',$voguepay['referer'])){
		$action = 'Support';
		
		}
	if($voguepay['merchant_id'] == '13302-13767' && isset($voguepay['memo'])){
		$target = mysql_prep($voguepay['merchant_ref']);
		$channel = 'voguepay';
		$email = mysql_prep($voguepay['email']);
		$status = mysql_prep($voguepay['status']);
		$transaction_id = mysql_prep($voguepay['transaction_id']);
		$amount = mysql_prep($voguepay['total']);
		$time = getdate();
		$today = $time['weekday'] .' '. $time['mday'].' '. $time['month'].' '. $time['year'].' '. $time['hours'].':'. $time['minutes'].' :: '. $time['seconds'];

		//save to db
		$query = mysqli_query("INSERT INTO `payment_transactions`(`id`, `transaction_id`, `actor`, `action`, `target`, `target_type`, `channel`, `amount`, `status`) 
		VALUES ('0','{$transaction_id}','{$target}','fundraiser','voguepay','{$amount}','{$status}')") or die('Error recording payment transaction '.mysqli_error());
		if($query){
			status_message('success','payment recorded');
			}

		$query = mysqli_query("SELECT amount_raised FROM fundraiser WHERE id='{$target}'") or die('Error fetching target fundraiser '. mysql_error());
		$result = mysqli_fetch_array($query);
		
		$update_amount = $result['amount'] + $amount;
		$update_fundraiser_query = mysqli_query("UPDATE `fundraiser` SET `amount_raised`='{$update_amount}' 
		WHERE `id`='{$target}'") or die("Fundraiser amount-raised update error! ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

		//~ $insert_fundraiser_donor_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `fundraiser_donors`
		//~ (`id`, `donor`, `amount`, `fundraiser_name`, `recipient`, `date`) 
		//~ VALUES ('0', '{$giver}', '{$amount}', '{$fundraiser_name}', '{$reciever}', '{$today}')") 
		//~ or die("Fundraiser_donor insert error!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	}
	
}
	

function transfer_funds($action,$amount,$giver,$reciever,$reason,$auto_switch='false',$add_to_system=''){
	
	if(isset($_SESSION['username'])){
	
#Get POST variables
$id = '';
if(isset($_POST['amount'])){
$amount = trim(mysql_prep($_POST['amount']));
}

if($reciever == '' && isset($_POST['reciever'])){
$reciever = trim(mysql_prep($_POST['reciever']));
} else if(isset($_POST['account_to_top_up'])){
$reciever = trim(mysql_prep($_POST['account_to_top_up']));
} else if(!isset($reciever)){
	$reciever = $_SESSION['username'];}


if($giver===''){
$giver = $_SESSION['username'];
}

if(isset($_POST['reason'])){
$reason = mysql_prep($_POST['reason']);
} 

if(isset($_GET['contest_name'])){
	$contest = trim(mysql_prep($_GET['contest_name']));
	$reason = "Support from contest : <strong>{$contest}</strong>";
	}

if(isset($_POST['action'])){
$action = trim(mysql_prep($_POST['action']));
}

$time = getdate();
$today = $time['weekday'] .' '. $time['mday'].' '. $time['month'].' '. $time['year'].' '. $time['hours'].':'. $time['minutes'].' :: '. $time['seconds'];

#echo "<br><br>" .$reciever ." " .$today; // Testing purposes

# Try to resolve current reciever balance

$reciever_balance = get_user_funds();

# ADD OR SUBTRACT BASED ON USER CHOICE
if($action =='add'){
			$amount = $amount;
			} else if ($action === 'subtract'){
				$amount = -0 - $amount;
				}//echo 'Amount = '.$amount; //Testing purposes
			

$new_reciever_balance = $reciever_balance + ($amount);
//~ echo $new_reciever_balance;

//echo "<br><br>" .$new_reciever_balance;// testing purposes
$giver_balance = get_user_funds();
$new_giver_balance = $giver_balance - $amount;

//die('I enter 2');
# PROCEED

	if(!empty($_POST['add_funds']) || !empty($amount) || isset($_POST['transaction_id']) || $_POST['action'] == 'load_top_up' ){
	
		
# do add funds 
		if($_POST['action'] == 'donate' || $_POST['intent'] == 'support' || $auto_switch=='true'){
		
		# reciever
		$fm_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) VALUES 
		('0', '{$giver}', '{$reciever}', '{$amount}', '{$today}', '{$reason}', '{$new_reciever_balance}')") 
		or die("Add funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		
		# Updating reciever balance 
		$update_reciever_amount = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`='{$new_reciever_balance}' WHERE `user_name`='{$reciever}'") 
		or die("Could not update new Amount!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		if($update_reciever_amount){
			$string = 'addons/draws';
				if(!string_contains($_SESSION['current_url'], $string) || !string_contains($_SESSION['current_url'], 'page_name=home')){
				status_message('alert','Your balance has been updated!');
				}		
			}
		//echo 'New reciever balance is: '. $new_reciever_balance;
		
		}
		
# do vote in contest 
		if($_POST['action'] == 'vote-contest'){
		$user_funds = get_user_funds();	
		$balance = $user_funds - 5;
		$reason = mysql_prep($_POST['reason']);
		#echo "Doing add funds manager!" //testing purposes
		# reciever
		$vote_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) VALUES 
		('0', '{$_SESSION['username']}', 'system', '5', '{$today}', '{$reason}', '{$balance}')") 
		or die("Add funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		# Updating reciever balance 
		$update_reciever_amount = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`='{$new_reciever_balance}' WHERE `user_name`='{$reciever}'") 
		or die("Could not update new Amount!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			
		}
		
	//Get referral funds
		if($_POST['submit'] == 'claim reward'){
	//	echo 'i dey here';
		$user_funds = get_user_funds();	
		$balance = $user_funds + 40;
		
		$reward_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) VALUES 
		('0', 'system', '{$_SESSION['username']}', '40', '{$today}', '{$reason}', '{$balance}')") 
		or die("Add funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		# Updating reciever balance 
			$update_reciever_amount = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`='{$new_reciever_balance}' WHERE `user_name`='{$reciever}'") 
			or die("Could not update new Amount!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		
		
		if($update_reciever_amount){	

			status_message('success',"Funds UPDATED successfully!<br>"
			.$reciever ." has recieved " .$amount ." extra site funds.");
			}
		}	
		
		
//Load top up pin
		if($_POST['action'] == 'load_top_up' || isset($_POST['transaction_id'])){		
		
		$giver = 'system';
		//$amount= mysql_prep($_POST['pin_value']);
		$check_initial_deposit_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `amount` FROM funds_manager WHERE reciever='{$reciever}' AND `reason`='initial deposit'") 
		or die('Could not get initial deposit '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$num = mysqli_num_rows($check_initial_deposit_query);
		if(!empty($num)){
			//is not initial deposit
			$fm_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) VALUES 
			('0', '{$giver}', '{$reciever}', '{$amount}', '{$today}', '{$reason}', '{$new_reciever_balance}')") 
			or die("Add funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			$referrer = show_referrer();
			
		} else {
			// this is the initial deposit
			$fm_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) VALUES 
			('0', '{$giver}', '{$reciever}', '{$amount}', '{$today}', 'initial deposit', '{$new_reciever_balance}')") 
			or die("Add funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
		# Updating reciever balance 
			$update_reciever_amount = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`='{$new_reciever_balance}' WHERE `user_name`='{$reciever}'") 
			or die("Could not update new Amount!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		$referrer = show_referrer($reciever); // returns an array;
		if(is_an_agent($referrer['referrer_name'])){
			$bonus_amount = (1/10)*$amount;
			$agent_balance = get_user_funds($referrer['referrer_name']);
			$new_agent_balance = $agent_balance + $bonus_amount;
			$agent_bonus_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `funds_manager`(`id`, `giver`, `reciever`, `amount`, `time`, `reason`, `balance`) VALUES 
			('0', '{$giver}', '{$referrer['referrer_name']}', '{$bonus_amount}', '{$today}', 'bonus on initial deposit for {$reciever}', '{$new_agent_balance}')") 
			or die("Add funds failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
		
		if($update_reciever_amount){	

			status_message('success',"Funds UPDATED successfully!<br>"
			.$reciever ." has recieved " .$amount ." extra site funds.");
			}
		
		}
	
		if($fm_query){
			
	
			#SET NEW GIVER BALANCE
			if($giver != 'system'){
			$update_giver = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`='{$new_giver_balance}' WHERE `user_name`='{$giver}'") 
			or die('Failed to update giver balance' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
			
			if($update_giver){
			 $_SESSION['site_funds_amount'] = $new_giver_balance;
			 $updated_balance = get_user_funds();
			status_message('alert','Your balance is now '.$updated_balance);	
			}
			
			# SHOW LINK TO RETURN TO ADD FUNDS PAGE
			$string = 'addons/draws';
			if(!string_contains($_SESSION['current_url'], $string) || !string_contains($_SESSION['current_url'], 'page_name=home')){
			go_back();
			}
			
			
			}
		}
	}
}

function fund_account_voguepay(){
	
	$merchant_id = '13302-13767';
	$merchant_demo = 'demo';
	$username = $_SESSION['username'];
	
echo "
<form method='POST' action='https://voguepay.com/pay/'>

<input type='hidden' name='v_merchant_id' value='".$merchant_id."' />
<input type='text' name='merchant_ref' value='".$username."' placeholder='Username to be funded' />
<input type='hidden' name='notify_url' value='".BASE_PATH."funds_manager/process.php' />
<input type='hidden' name='success_url' value='".BASE_PATH."funds_manager/success.php' />
<input type='hidden' name='memo' value='Fund Your GeniusAid Account' />
Fund your account: <br>
NGN <input type='number' name='total' value='' placeholder='500'/> .00<br>

<input type='submit' name='submit' value='Fund your Account' class='button-primary'/>

</form>";
	
	
}

function do_payout(){
	
	
	}
	
function fund_account_link(){
	if(addon_is_available('funds_manager')){
	echo '<a href="'.BASE_PATH.'funds_manager/?action=fund_account"><aside class="call-to-action"> Fund your account</aside></a>';
	}
}

 // end of funds_manager functions file
 // in root/funds_manager/includes/functions.php
?>
