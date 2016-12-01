<?php require_once('../includes/session.php');
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">People</a>';
start_addons_page();#from root/inludes/functions.php
#					- Template Ends -
#=======================================================================

//print_r($_POST);

echo "<div class='container'>";
go_back();
delete_user();
//print_r($_POST);

if(isset($_POST['submit']) && $_POST['submit'] ==='register') {
$username = str_ireplace(' ','_',strtolower(trim(mysql_prep($_POST['user_name']))));
$password = trim(mysql_prep($_POST['password']));

$begins_with = substr($_POST['phone'],0,1);
//echo "begins with" .$begins_with;
if($begins_with == '0' || $begins_with == '+'){
$phone = mysql_prep($_POST['phone']);
}else if($begins_with !== '0' && $begins_with !== '+'){
$phone = '0'. mysql_prep($_POST['phone']);
}	
$hashed_password = sha1($password);
$bonus_funds = 20;
$secret_question = trim(mysql_prep($_POST['secret_question']));
$secret_answer = trim(mysql_prep($_POST['secret_answer']));
$post_destination = trim(mysql_prep($_POST['destination']));
//echo "Secret Answer = ". $secret_answer;
$created = date('c');
// testing only

//register / add user
$save_to_db = mysqli_query($GLOBALS["___mysqli_ston"], "insert into `user`(`id`, `user_name`, `password`, `created_time`, `last_login`,`login_count`,`logged_in`, `phone`, `site_funds_amount`, `role`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer`, `status`, `bank_account_no`, `bank_name`, `full_name`)
 VALUES ('0', '{$username}', '{$hashed_password}', '{$created}', '{$created}', '0', 'no', '{$phone}', '{$bonus_funds}','authenticated','','','{$secret_question}','{$secret_answer}','not verified','0','','')") 
or die("<div class='error'>Registration failed!</div>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

if($save_to_db)
	{
		save_referral();
		
		status_message('success', 'Registration Successful!');
		$message = 'Welcome to GeniusAid. Your password is "'.$password .'". We hope to see your active participation.';
		sms_notify($sender='feedback',$reciever=$username,$content=$message,$sms_flag='important');
		echo "<div class='container'><a href='".BASE_PATH."user'><button> Login Now </button></a></div>";
}
}

if(isset($_POST['submit']) && $_POST['submit'] ==='login') {
$begins_with = substr($_POST['phone'],0,1);
//echo "begins with" .$begins_with;
if($begins_with == '0' || $begins_with == '+'){
$phone = mysql_prep($_POST['phone']);
}else if($begins_with !== '0' && $begins_with !== '+'){
$phone = '0'. mysql_prep($_POST['phone']);	
}
//echo $phone;
$password = trim(mysql_prep($_POST['password']));
#print_r($_POST);
$hashed_password = sha1($password);

//login
$login_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `user_name`, `created_time`, `last_login`, `login_count`, `phone`, `site_funds_amount`, `role`, `picture`, `picture_thumbnail`, `secret_question`, `secret_answer` FROM `user` WHERE `phone`='{$phone}' AND `password`='{$hashed_password}'")
or die("<div class='error'>Unsuccessful login attempt!</div>" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

while($login_result = mysqli_fetch_array($login_query)){
	
	$_SESSION['username'] = $login_result['user_name'] ;
	$_SESSION['user_id'] = $login_result['id'];
	$_SESSION['username'] = $login_result['user_name'];
	$_SESSION['phone'] = $login_result['phone'];
	$_SESSION['site_funds_amount'] = $login_result['site_funds_amount'];
	$_SESSION['login_count'] = $login_result['login_count'];
	$_SESSION['LAST_ACTIVITY'] = time();
	$_SESSION['CREATED'] = time();
	
	$control = mt_rand(10000,100000);
	$_SESSION['control'] = $control;
	
	$_SESSION['role'] = $login_result['role'];
	$_SESSION['picture'] = '<a href="'.BASE_PATH .'user/?user='.$login_result['user_name'] .'">'.
	'<img src="'.$login_result['picture'].'"></a>';
	$_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/?user='.$login_result['user_name'] .'">'.
	$_SESSION['secret_question'] = $login_result['secret_question'];
	$_SESSION['secret_answer'] = $login_result['secret_answer'];
	unset($_SESSION['not_logged_in']);
	 #print_r($_SESSION);
	}
	if(isset($_SESSION['username'])){
	$time = time();
	$user = $_SESSION['username'];
	
	//Upate login time
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `last_login`='{$time}' WHERE `user_name`='{$user}'") 
	or die('Failed to update login time' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
	$_SESSION['last_login'] = $time;
		}
	echo "<br><br><div class='success'> Login successful! </div>";
	if(!empty($post_destination)){
		$_SESSION['destination'] = $post_destination;
		} 
	$destination = BASE_PATH;
	//~ 'user/?user='.$_SESSION['username'].'&login=true';
	
	header("Location: {$destination}"); exit;
	echo '<div class="container"><a href="' .BASE_PATH .'user?user='.$user.'&login=true"><button><img src="'.BASE_PATH .'uploads/files/default_images/profile-icon.png"><br> Go to profile </button></a>   '.'&nbsp &nbsp<a href="' .BASE_PATH .'"><button><img src="'.BASE_PATH .'uploads/files/default_images/home-icon.png"><br> Go to Home</button></a>';
		if(is_admin()){
		echo'&nbsp &nbsp<a href="' .ADMIN_PATH .'"><button><img src="'.BASE_PATH .'uploads/files/default_images/admin-icon.png"><br> Go to Admin </button></a></div>';
		} 
	} else {
		sleep(4);
		session_message('error', 'Unsuccessful login attempt');
		redirect_to(BASE_PATH.'user');
		#go_back();
	}
} 



if(isset($_POST['submit']) && $_POST['submit'] ==='save') {
	
$uid = $_POST['uid'];
$password = trim(mysql_prep($_POST['password']));
$confirm_password = trim(mysql_prep($_POST['confirm_password']));

$begins_with = substr($_POST['phone'],0,1);
//echo "begins with" .$begins_with;
if($begins_with == '0' || $begins_with == '+'){
$phone = mysql_prep($_POST['phone']);
}else if($begins_with !== '0' && $begins_with !== '+'){
$phone = '0'. mysql_prep($_POST['phone']);	
}

if($password !== $confirm_password){
	status_message("error", "Passwords do not match!");
	
	exit;
	} else {

$hashed_password = sha1($password);

	if($_POST['role']){
	$role = trim(mysql_prep($_POST['role']));
	} else { $role = 'authenticated'; }

	if($_POST['password']===''){
		
		if(!empty($_POST['secret_answer'])){
			die ("<span class='red-text'>You must enter your password to update your secret answer!</span>" . go_back());
			
			}
		
		$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `phone`='{$phone}', `role`='{$role}' WHERE `id`='{$uid}'") 
		or die("Update user query failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($update_user){
			
			if(addon_is_active('referrals')){ edit_referrer(); }
			status_message("success","Changes saved successfully!");
				}
		} else {
			if(empty($_POST['secret_answer'])){
			$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `password`='{$hashed_password}', `phone`='{$phone}', `role`='{$role}' WHERE `id`='{$uid}'");
			}
			if(!empty($_POST['secret_answer'])){
			$secret_answer = trim(mysql_prep($_POST['secret_answer']));
			$update_user=mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `password`='{$hashed_password}', `phone`='{$phone}', `role`='{$role}', `secret_answer`='{$secret_answer}' WHERE `id`='{$uid}'");	
		
			}
			
		if($update_user){
			status_message("success","Changes saved successfully!");
			
				}
			}
	}
}

if(isset($_POST['submit-account']) && $_POST['submit-account'] ==='save') {
	$username = $_SESSION['username'];
	$bank_account = mysql_prep($_POST['account_no']);
	$bank_name = trim(mysql_prep($_POST['bank']));
	$full_name = trim(mysql_prep($_POST['full_name']));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `bank_account_no`='{$bank_account}', `bank_name`='{$bank_name}', `full_name`='{$full_name}' WHERE `user_name`='{$username}'") 
	or die("Failed to save account details " . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($query){
		status_message('success', 'Account details saved successfully!');
		link_to(BASE_PATH."user/?user={$username}", 'Return to profile');
		} else{ echo "<h2>Nothing happened! why?</h2>"; }
	}

echo "</div>";
?>



