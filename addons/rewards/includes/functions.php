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
//print_r($_SESSION);

#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

function calculate_goodwill(){
	$goodwill = get_user_goodwill_status();
	// User supports fundraiser
	if($_POST['submit'] === 'Donate' && url_contains('addons/fundraiser')){
		$amount = mysql_prep($_POST['amount']);
		$goodwill += $amount;
		
		}
	//User votes in contest
	return $goodwill;
	}
	
function get_user_goodwill_status($user=''){
	if(empty($user)){
		$user = $_SESSION['username'];
		}
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `goodwill_amount` FROM `reward_goodwill` WHERE `user_name`='{$user}'") 
		or die('Failed to get user goodwill amount '. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		return $result['goodwill_amount']; 
	}
	
function set_user_goodwill($user='',$goodwill){
	if(empty($user)){
	$user = $_SESSION['username'];
	}
	
	$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT id FROM reward_goodwill WHERE user_name='{$user}'");
	$num = mysqli_num_rows($query1);
	if(!empty($num)){
		$query2 = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE reward_goodwill SET goodwill_amount='{$goodwill}' WHERE user_name='{$user}'") 
		or die('Problems setting user goodwill '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		} else { 
			$query2 = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `reward_goodwill`(`id`, `user_name`, `goodwill_amount`) 
			VALUES ('0','{$user}','{$goodwill}')") or die("Failed to save goodwill ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
	if($query2){ 
		status_message('alert', 'Goodwill updated!');
		}
	
		
}

function get_reward_points(){ // deprecated
	
	
		$vip_incrementer = 0;
		$points_incrementer= 0;
		
		while ($amount >= 0){
			$amount_remains = $amount - 50;
			if($amount_remains >= 0){
			$points_incrementer++;
			//echo "points incrementer =".$points_incrementer; - testing
			}
			if($points_incrementer > 10){
			$vip_incrementer++;
			$points_incrementer = 1;
			}
			
			$amount = $amount_remains;
			$amount_remains= '';
			}
	
	$output = array('points'=>$points_incrementer, 'vip_increment' => $vip_incrementer);
		
		//print_r($output);
		return $output;
		
		}
	 
	
function set_user_vip_status(){
	
	//if user donated funds
	if($_POST['action'] == 'donate' && !empty($_POST['amount'])){ 
	$amount = mysql_prep($_POST['amount']);
		
	$user = $_SESSION['username'];
	$vip_points = get_user_vip_status();
	
	$updated_total = $vip_points + $amount;
	if($updated_total > 500){
		$badge = 'Bronze member';
	} else if($updated_total > 1000){
		$badge = 'Silver member';
	} else if($updated_total > 3000){
		$badge = 'Gold member';
	} else if($updated_total > 10000){
		$badge = 'Platinum Sponsor';
	} else if($updated_total > 20000){
		$badge = 'Life changer';
	} else if($updated_total > 30000){
		$badge = 'Angel';
	}
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `reward_vip` SET `amount`='{$updated_total}',`badge`='{$badge}' WHERE `user_name`='{$user}'") 
	or die("Failed to set user vip status ". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
		$_SESSION['user_vip_status'] = $updated_total;
		status_message('alert', 'Vip status updated!');
		}
	}
} 

function get_user_vip_status($user=""){
	if($user==''){
	$user = $_SESSION['username'];
	}
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `amount` FROM `reward_vip` WHERE user_name='{$user}'") 
	or die('Cannot get user vip status points '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	if(empty($num)){
		$insert_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `reward_vip`(`id`, `amount`, `badge`, `user_name`) 
		VALUES ('0','1','','{$user}')") or die("Could not insert record in reward vip status" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		}
	
	if($query){
		$result = mysqli_fetch_array($query);
		$_SESSION['user_vip_status'] = $result['amount'];
		return $result['amount'];
		} else if($insert_query){ 
			return 1;
			}
		
	
	}
	
function get_reward_badge($user=''){
	
	$funds = get_user_funds($user);
	
	if($funds < 45){ $badge = 'broke'; }
	
	else { 
	$funds = get_user_vip_status($user);
	
	if($funds < 499){ $badge = 'regular'; }
	elseif($funds < 1000){ $badge = 'bronze'; }
	elseif($funds < 3000){ $badge = 'silver'; }
	elseif($funds < 10000){ $badge = 'gold'; }
	elseif($funds < 15000){ $badge = 'angel'; }
	}
		if(is_user_page() && $user==''){
			$user = trim(mysql_prep($_GET['user']));
			}
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT badge from `reward_vip` WHERE `user_name`='{$user}'") 
		or die("Badge fetching failed! ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		$result = mysqli_fetch_array($query);
		if(empty($result['badge'])){
			$result['badge'] = 'unverified';
		}
		$_SESSION['user_vip_badge'] = $badge;
		return $badge;
	}
	

function add_reward_freebies(){
	
	if($_POST['submit'] == 'Save freebie'){
		$type = trim(mysql_prep($_POST['type']));
		$description = trim(mysql_prep($_POST['description']));
		$date = date('c');
		
			if(addon_is_active('notifications')){
				$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `reward_freebies`(`id`, `recipient`, `type`, `description`, `date`,`status`)
				 VALUES ('0','','{$type}','{$description}','{$date}','available')") 
				 or die("Failed to save freebie ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				 
				 
				 if($query){
					 status_message('success', 'Freebie saved!');
					 }
					 
				save_notification($addon='reward',$message=$description,'');
			}
		}
	
	if(is_admin()){
	echo '<h3>Add Freebie</h3><form method="post" action="'.$_SESSION['current_url'].'">
	<select name="type">
	<option>Site Funds</option>
	<option>Call credits</option>
	<option>Other</option>
	</select>
	<textarea name="description"></textarea>
	<input type="submit" name="submit" value="Save freebie">
	</form>';
	}
}

function claim_freebie(){
	if($_GET['action'] == 'claim_freebie' && $_SESSION['control']==$_GET['control']){
		echo'idey here';
		$description = mysql_prep($_GET['desc']);
		$recipient = mysql_prep($_GET['recipient']);
		$status = mysql_prep($_GET['status']);
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE reward_freebies SET recipient='{$recipient}', status='claimed'
		 WHERE description='{$description}' AND status='{$status}'") 
		 or die('Could not claim freebie '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			send_message($sender='system',$reciever=$_SESSION['username'],$message=$description,$parent_id="");
			session_message('success','You have claimed this freebie! and details have been sent to your inbox. ');
			redirect_to(ADDONS_PATH.'messaging');
			}
		}
	
	}
	
	
function reward_referrals(){
	
	$recipient = mysql_prep($_POST['ref_id']);
	$referree = mysql_prep($_POST['referree']);
	$description = 'Bonus For referring '.mysql_prep($_POST['referree']);
	$bonus = 40;
	$date = date('c');
	
	
	$destination = $_POST['back_to'];
	if($_POST['submit'] == '40 Cash' && $_POST['action'] == 'reward_referral'){
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `reward_freebies`(`id`, `recipient`, `type`, `description`, `date`, `status`) 
		VALUES ('0','{$recipient}','referral bonus','{$description}','{$date}','claimed')")
		 or die('Error giving referral bonus' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
	if($query){
		session_message('success','You have recieved '.$bonus. 'site funds');
		transfer_funds($action="add",$amount=40,$giver="system",$reciever=$recipient,$reason="Bonus for referring ".$referree);
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE referrals SET rewarded='Yes', rewarded_by='self'
		 WHERE referrer='{$recipient}' AND referree='{$referree}'") 
		 or die('Could not claim freebie '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			if($query){
			redirect_to($destination);
			}
		}
	} else if($_POST['submit'] == '2 Veto' && $_POST['action'] == 'reward_referral'){
		$bonus = 2;
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `reward_freebies`(`id`, `recipient`, `type`, `description`, `date`, `status`) 
		VALUES ('0','{$recipient}','veto power','{$description}','{$date}','claimed')")
		 or die('Error giving referral bonus' .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
	if($query){
		session_message('success','You have recieved '.$bonus. 'Veto power');
		//transfer_funds($action="add",$amount=40,$giver="system",$reciever=$recipient,$reason="Bonus for referring ".$referree);
		update_veto_power('',$amount=2,'');
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE referrals SET rewarded='Yes', rewarded_by='self'
		 WHERE referrer='{$recipient}' AND referree='{$referree}'") 
		 or die('Could not claim freebie '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			if($query){
			redirect_to($destination);
			}
		}
	}
}

function update_veto_power($action='',$amount='', $user=''){
	if($action == ''){
		$action= 'add';
		}
	if($user == ''){
		$user= $_SESSION['username'];
		}
		// fetch current veto value
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT amount FROM reward_veto_power WHERE user_name='{$user}'") 
		or die("Cannot get user veto ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$num = mysqli_num_rows($query);
		
		
		// if not found, create one
		if(empty($num)){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO reward_veto_power(`id`,`amount`,`user_name`) 
			VALUES('0','0','{$user}')") or die("Could not instantiate user veto power ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			$veto_amount = 0;
			}
		
		$veto = mysqli_fetch_array($query);
		$veto_amount = $veto['amount'];
		
	if(!empty($amount)){
		if($action == 'add'){
		$new_amount= $veto_amount + $amount;
		} else if($action == 'subtract'){
			$new_amount= $veto_amount - $amount;
			}
		
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE reward_veto_power SET amount='{$new_amount}' WHERE user_name='{$user}'") 
		or die("Error updating user veto ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($query){
			$_SESSION['user_veto_power_amount'] = $new_amount;
			}
		}
		
	}
	

function get_user_veto_power_status(){
	
	$user = $_SESSION['username'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT amount FROM reward_veto_power WHERE user_name='{$user}'") 
	or die("Cannot get user veto power status ".((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false))); 
	
	if($query){
		$result = mysqli_fetch_array($query);
		$_SESSION['user_veto_power_amount'] = $result['amount'];
		return $result['amount'];
		}
	}
	
function display_user_rewards_status(){
	
	$vip = get_user_vip_status();
	$veto = get_user_veto_power_status();
	$goodwill = get_user_goodwill_status();
	$funds = get_user_funds();
	$badge = get_reward_badge();
	
	echo '<div class=" black center-block">';
	
	echo '<span class="badge darkslategrey margin-3 tiny-text">SF: '.$funds.' </span>';
	echo '<span class="badge darkslategrey margin-3 tiny-text">GW: '.$goodwill.' </span>';
	echo '<span class="badge darkslategrey margin-3 tiny-text">VIP: '.$vip.' </span>';
	echo '<span class="badge darkslategrey rounded margin-3 tiny-text">VETO: '.$veto.' </span>';
	echo '<span class="badge darkslategrey margin-3 tiny-text">LEVEL: '.$badge.' </span>';
	echo '</div>';
	}
	
function get_claimed_reward_freebies(){
	$pager = pagerize();
	if(url_contains('?page_name=')){
		$limit = "LIMIT 0, 4";
		} else { $limit = "LIMIT 0,15"; 
			echo '<h2>Claimed rewards</h2>';}
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `reward_freebies` WHERE `status`='claimed' ORDER BY id DESC {$limit}");
	
	
	echo '<br><table class="table"><thead><th>Freebie</th><th>Claimed by</th></thead><tbody>';
	while($result =mysqli_fetch_array($query)){
		echo '<tr><td class="table-message-plain">'.$result['description'].'</td><td>'.$result['recipient'].'<br><time class="timeago" datetime="'.$result['date'].'"></time></td></tr>';
		}
		echo '</tbody></table>';
		echo $pager;
	}

 // end of rewards functions file
 // in root/addons/rewards/includes/functions.php
?>
