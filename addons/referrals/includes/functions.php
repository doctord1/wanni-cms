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

function show_referral_id($user=''){
	$user_being_viewed = trim(mysql_prep($_GET['user']));
	//Generate referral id if not generated	
	if(is_logged_in() && $_SESSION['username'] === $user_being_viewed){
		if($user === '' && empty($_GET['user']) && !url_contains('/user/?user=')){
				$user = $_SESSION['username'];
		} else { 
			$user = trim(mysql_prep($_GET['user'])); 
			}
		$ref_id = $user;
		$ref_link = "<strong>Referral link: </strong> " . BASE_PATH ."user/?action=register&ref_id=". strtolower($user)." ";
	
		$output= array();
		$output['ref_id'] = '<br><strong>Referral id :</strong>'. strtolower($user) .'<span class="tiny-text "><a href="'.ADDONS_PATH."referrals/?referrer=".strtolower($user).'"> check rewards</a></span>';
		$output['ref_link'] = '<br>'. $ref_link;
	return $output;
	}// Dislay referral link in user profile page	
}

function save_referral(){
	// catch referral from user registration and save to database
	
	if(!empty($_POST['ref_id']) && isset($_POST['user_name'])){

		$ref_id = trim(mysql_prep($_POST['ref_id']));
		$referrer = strtolower(str_ireplace(' ','_',$ref_id));
		$referree1 =  trim(mysql_prep($_POST['user_name']));
		$referree = strtolower(str_ireplace(' ','_',$referree1));

			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `referrals`(`id`, `referrer`, `referree`, `rewarded`, `rewarded_by`) 
			VALUES('0','{$referrer}','{$referree}','','')") 
			or die("Failed to save referral" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($query){
				status_message('success','Referral saved successfully !');
				}
	}
	
}

function edit_referrer(){
	// catch referral from user edit and save to database
	
	if(!empty($_POST['ref_id'])){

		$ref_id = trim(mysql_prep($_POST['ref_id']));
		$referrer = strtolower(str_ireplace(' ','_',$ref_id));
		$referree = $_SESSION['username'];

			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT referrer FROM referrals WHERE referree='{$referree}'");
			$num = mysqli_num_rows($query);
			if(!empty($num)){
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `referrals` SET `referrer`='{$referrer}' WHERE `referree`='{$referree}' LIMIT 1")  
			or die("Failed to edit referral" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			} else {
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `referrals`(`id`, `referrer`, `referree`, `rewarded`, `rewarded_by`) 
			VALUES('0','{$referrer}','{$referree}','','')") 
			or die("Failed to save referral" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
				}
			if($query){
				status_message('success','Referral edited successfully !');
				}
	}
	
	}

function track_user_referrals(){
	
	if(is_logged_in() && $_GET['referrer'] == $_SESSION['username']){
	//print_r($_POST);
	// Show referrals by this user from $_GET or $_POST
	// Showing no. of rewards collected and no. of rewards remaining
	
	if(isset($_GET['referrer'])){
	$referrer = trim(mysql_prep($_GET['referrer']));
	}
	if(isset($_POST['referrer'])){
	$referrer = trim(mysql_prep($_POST['referrer']));
	}
	
			echo "<div class='gainsboro padding-20'>
		<h2>Collect Rewards for People referred by {$referrer}</h2>
		<form action='{$_SESSION['current_url']}' method='post'>
		<input type='text' name='referrer' value='{$referrer}' placeholder='referrer name'>
		<input type='submit' name='submit' value='Submit'>
		</form></div><p></p>";
		
	
	
	if(isset($referrer)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `referree`,`rewarded` from `referrals` WHERE `referrer`='{$referrer}' ORDER BY `rewarded` LIMIT 50") 
	or die("Unable to select referrals by {$referrer} " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	
	while($result = mysqli_fetch_array($query)){
		
		$referree =$result["referree"];
		$user = get_user_details($user = $referree);
		
		
		if($result['rewarded'] != 'Yes'){
			$verified = '<span class="green-text">verified</span>';
			} else {$verified = '<span class="red-text">fishy</span>';}
		if($result['rewarded'] == 'yes'){
			$rewarded = 'Yes';
			}else{
				$rewarded = 'No';
				}
	
		$pic = default_pic_fallback($user["picture_thumbnail"],$size='small');
			if($verified =='<span class="green-text">verified</span>' && $rewarded == 'No'){
				
		$give_reward = '<form method="post" action="'.ADDONS_PATH.'rewards/'.'" class="tiny-text">
						<input type="hidden" name="ref_id" value="'.$_GET['referrer'].'">
						<input type="hidden" name="referree" value="'.$user['user_name'].'">
						<input type="hidden" name="back_to" value="'.$_SESSION['current_url'].'">
						<input type="hidden" name="action" value="reward_referral">
						<div class="btn btn-group">
						<input type="submit" name="submit" value="40 Cash" class="tiny-text btn btn-primary btn-xs">
						<input type="submit" name="submit" value="2 Veto" class="tiny-text btn btn-success btn-xs">
						
						</div>
						<br>choose
						</form>';
		
		
			echo '<span class"margin-10">
					<span class="img-thumbnail text-center ">
					<span class="tiny-text">'.$verified.'</span>
					<a href="'.BASE_PATH. 'user/?user='.$result["referree"]. '" ><img src="'.$pic .'" class="img-circular"><br>'.$result["referree"].' </a>
					<br>'.$give_reward .'</span>
				</span><span class=""></span>';
			} else if($verified = '<span class="red-text">unverified</span>'){ // not verified, show person but do not give reward
				$give_reward = '<form method="post" action="'.ADDONS_PATH.'rewards/'.'" class="tiny-text">
						<div class="btn btn-group">
						<div class="tiny-text padding-5">No reward </div>
						
						</div>
						<br> ..
						</form>';
				echo '<span class"margin-10">
						<span class="img-thumbnail text-center ">
							<span class="tiny-text">'.$verified.'</span>
								<a href="'.BASE_PATH. 'user/?user='.$result["referree"]. '" ><img src="'.$pic .'" class="img-circular"><br>'.$result["referree"].' </a>
							<br>'.$give_reward .'</span>
						</span>';
				
				}
		} 
		
	if(empty($num)){
		status_message('error','You have no rewards to collect at this time. <br> Refer More people to earn cash or get veto power');
		}
		
		
	//echo "</div>";
	
	}
	
} else {
	
	}
	echo '<p></p><div class="whitesmoke padding-20"> There are two kinds of referral rewards.<br><b>Cash reward</b> and <b>Veto power</b></div>';
	echo '<p></p><div class="row"><div class="col-md-offset-1 col-md-5 tan padding-20"><b>Cash rewards</b> add site cash to your site funds which you may spend as you like.
	Each referral earns you <b>40</b> site funds, if the users have at least <b>200</b> site funds in their account. You will not earn any money for users
	that do not fund / verify their accounts.So referring 20 users a week, will give you <b>800</b> site funds, to spend on anything</div>';
	echo '<div class=" col-md-5 darkturquoise padding-20"><b>Veto power</b> multiplies your influence when you are voting in a contest.
	for example, if you have <b>20 Veto power</b>, Your single vote will be multiply the total votes by  1 + ((number of veto applied / 2) / 10). 
	If you do the math, you will understand how much power you have.</div>
	<div class="col-md-offset-1"></div></div>';
	
}	

function is_an_agent($user=''){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` FROM referral_agents WHERE user_name='{$user}'");
	$num =mysqli_num_rows($query);
	if(!empty($num)){
		return true;
		} else return false;
	}
	
function add_agent($user=''){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO referral_agents (`id`,`username`) VALUES ('0','{$user}')") 
	or die('Failed to add agent '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($query){
		status_message('success', 'agent added successfully');
		}
	}
	

function show_referrer($user=''){
	if($user == '' && isset($_GET['user'])){
	$user_being_viewed = trim(mysql_prep($_GET["user"]));
	} else {
		$user_being_viewed = $user;
		}
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `referrer` FROM `referrals` WHERE `referree`='{$user_being_viewed}'") 
	or die("Failed to get referrer! " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	$output = array();
	$result = mysqli_fetch_array($query);
	
	if(!empty($result['referrer'])){
	$output['referrer_link'] = "<strong>Referred by : " . '<a href="'.ADDONS_PATH. 'referrals/?referrer=' . $result['referrer'] .'">'.$result['referrer'] ."</a></strong>";
	$output['referrer_name'] = $result['referrer'];
	}
	return $output;
}

 // end of referrals functions file
 // in root/referrals/includes/functions.php
?>
