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
require_once($r .'/includes/resize_class.php'); 

//print_r($_SERVER);
#======================================================================
#						TEMPLATE ENDS
#======================================================================


#				 ADD YOUR CUSTOM ADDON CODE BELOW

# CREATE USER FORM
function create_user(){
	if(isset($_GET['ref_id'])){
	$ref_id = trim(mysql_prep($_GET['ref_id']));
	}
	 echo '<section class="pull-left"><ul><li id="add_page_form_link" class="float-right-lists"><a href="'.BASE_PATH .'user/?action=login">Login </a>' .
	 '</li><li id="add_page_form_link" class="float-right-lists"><a href="' .BASE_PATH .'user/?action=register">Register </a></li></ul></section>' ;

	  	echo "<br><div class='login-form' align='center'>" . 
"<br><h1>Signup</h1>" .
'<form action="process.php" method="post">' .
'Username:<br>	<input type="text" name="user_name" id="user_name" placeholder="username" required><br>' .
'Password:<br> <input type="password" name="password" id="password" placeholder="password" required><br>' .
'Confirm Password:<br> <input type="password" name="password" id="password" placeholder="password" required><br>' .
'Phone No.:<br> <input type="tel" name="phone" placeholder="phone number" required><br>' .
'Write a Secret Question.:<br>'.
'<span class="tiny-text">This is the question you will be asked, if you forget your password and cannot log in ... </span><br>'.
'<input type="text" name="secret_question" placeholder="secret question" required><br>' .
'Answer your Secret Question.:<br>'.
'<span class="tiny-text" align="center">This is the answer you MUST provide to reset your password!</span><br>'.
'<input type="text" name="secret_answer" placeholder="answer" required><br>' .
'<input type="hidden" name="funds" value="0">' .
'<input type="hidden" name="logged_in" value="no">' .
'Referred by : <br><input type="text" name="ref_id" value="'.$ref_id.'"><br>' .
'<input type="submit" name="submit" value="register" class="submit"><br>' .
'</form>' .
'</div>' ;
}



function delete_user(){
	
	if(!empty($_GET['delete']) && !empty($_GET['id']) && !empty($_GET['control'])){
		if(($_GET['user'] === $_SESSION['username'] || is_admin()) 
		&& $_SESSION['control'] == $_GET['control'] 
		&& $_GET['delete'] === 'delete_user'){
			
			$referree = trim(mysql_prep($_GET['user']));
			
			$id = trim(mysql_prep($_GET['id']));
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `user` WHERE `id`={$id}") 
			or die('Failed to delete the User account. ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `referral` WHERE `referree`={$referree}") 
			or die('Failed to delete the referree . ' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($query){
				session_destroy(); 
				redirect_to(BASE_PATH);
				}
			}
		
		}
	
}


#  Show the log in form 
 function show_login_form($action=''){ // LARGELY INCOMPLETE
	 
	 $query_string = $_SERVER['QUERY_STRING'];
	 if($action===''){
		 $action = "user/process.php";
		 }
	 
	 if(!isset($_SESSION['username']) && $query_string !== 'forgot_password'){
	
	echo '<div class="row">
	<div class="col-md-7 col-xs-12">'; 
	  echo '<div class="pull-left"><ul><li id="add_page_form_link" class="float-right-lists"><a href="'.BASE_PATH .'user/?action=login">Login </a>' .
	 '</li><li id="add_page_form_link" class="float-right-lists"><a href="' .BASE_PATH .'user/?action=register">Signup </a></li></ul></div>' ;


  	echo "<br><div class='login-form' align='center'>" .
"<br><h1 align='center'> Login </h1>" .
'<form action="'.BASE_PATH ."{$action}".'" method="post">' .
'Phone: <br><input class="login" type="number" name="phone" id="user_name" placeholder="Phone number" width="40px"><br>' .
'Password: <br><input class="login" type="password" name="password" id="password" placeholder="password"><br>' ;
	 if(url_contains('redirect_to')){
		 $destination = mysql_prep($_GET['redirect_to']);
		 //echo '<input type="hidden" name="destination" value="'.$destination.'">';
		 $_SESSION['destination'] = $destination;
		 }

echo '<input type="submit" name="submit" value="login" class="submit">
<small><a href="'.BASE_PATH.'user/?forgot_password">Forgot password?</a></small><br>' .
'</form>' .
'</div>' ;

echo '</div>';
echo '<div class="col-md-5 col-xs-12 well aliceblue">
Support What we do on GeniusAid: 
<br>(Any amount is welcome)<br><em>All contributions will be acknowledged on the GeniusAid supporters page.</em>
<form method="post" action="https://voguepay.com/pay/">
Enter Amount<br />
<input type="text" name="total" style="width:120px" /><br />
Choose Currency<br />
<select name="cur" style="width:120px">
<option value="NGN">NGN - Nigerian Naira</option>
<option value="USD">USD - US Dollar</option>
</select><br />
<input type="hidden" name="v_merchant_id" value="13302-13767" />
<input type="hidden" name="memo" value="Donation to GeniusAid Company" />
<input type="image" src="http://voguepay.com/images/buttons/donate_blue.png" alt="PAY" />
</form>
</div>';
echo '</div>';
		}

}

function login_successful(){
	
	if (isset($_SESSION['username']) && $_GET['login'] =='true'){
		$id = $_SESSION['id'];
		$login_count = $_SESSION['login_count'];
		$login_time = time();
				
			if(isset($login_count)){
			#echo "<br><br><br>"  .$login_count ."<br><hr>";  // Testing purposes
			
			if(!isset($_SESSION['do_not_update_login_count'])){
				$login_count_plus = $login_count + 1;
								
				$update_login_count = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `last_login`='{$login_time}', `login_count`={$login_count_plus}, `logged_in`='yes' WHERE `id`={$_SESSION['user_id']}") 
				or die("Could not update Login count!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
				
				if($update_login_count){
					$_SESSION['do_not_update_login_count'] = 'true'; 
					echo "<div class='success'> You are now Logged in! </div>";
				}
			
				if($_SESSION['login_count'] < '1'){
			status_message('success', 'Congrats!! You have just beeen awarded 20 site funds!! ');
			$amt = $_SESSION['site_funds_amount'];
			$bonus = $amt + 20;
			
			$give_bonus =  mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `site_funds_amount`={$bonus} WHERE `id`={$_SESSION['user_id']}") 
			or die("Could not update Site funds!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			
				if($give_bonus){
					#echo "Bonus given"; //testing
					$update_amount = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT site_funds_amount FROM user WHERE id={$_SESSION['user_id']}") 
					or die ("Could not fetch updated Site funds!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
				}
				if($update_amount){ 
					#echo "amount updated";//testing
					$fetched = mysqli_fetch_array($update_amount);
					$_SESSION['site_funds_amount'] = $fetched['site_funds_amount'];
					
					}
				}
			}
		}
	} 
	if(!empty($_SESSION['destination'])){
		$destination = $_SESSION['destination'];
		unset($_SESSION['destination']);
		$_SESSION['status_message']= '<div class="success">You are now Logged in as <b>'.$_SESSION['username'].'</b></div>';
		redirect_to($destination);
		}
}


function logout_notify(){
	
		if(isset($_GET['logout']) && $_GET['logout'] ==='true'){ # If logged out, Notify of logout success
		
		status_message('success', 'You have now been successfully logged out!');
		
		}
}

function greet_user(){
	if (isset($_SESSION['username'])){
		$output = "Hello " .ucfirst($_SESSION['username']) ."!";
	return $output;	
}
	}


function is_user_page(){	
	if(url_contains('user/?user=') && !empty($_GET['user'])){
	return true;	
	}else{
	return false;	
	}

}

function online_users($type=''){
	if(isset($_SESSION['username'])){
	$now = time();
	$time_limit = ((time()/60)+15) - ($now/60);
	$time_limit = $time_limit * 60;

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `user_name`,`last_login` FROM `user` WHERE `last_login`<='{$time_limit}' LIMIT 0, 30") 
	or die("Failed to get last seen users" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	echo "<h2>Recently logged in persons</h2>";
	$last_seen = array();
	while ($result = mysqli_fetch_array($query)){
		$name = $result['user_name'];
		
		$person = show_user_pic($name) ;
		$login_timeago = (( time() - $result['last_login']) / 60 );
		if($login_timeago - $time_limit < 360){ 
			$last_seen[]= $name;
			if($type === 'pics'){
			$pics = "<span class=''>" . $person['thumbnail']." &nbsp</span>";
			$text ='';
			echo '<span title="'. $name .'">' . $pics .'</abbr>';
			}else {
			$text = "<span><a href='". BASE_PATH ."user/?user={$name}'>{$name}</a></span> &nbsp&nbsp<br><hr>";	
			echo $text;
				}
		}
	} echo '<br><hr>';
	}
	$_SESSION['recently_logged_in'] = $last_seen;
	//print_r($last_seen);
}


function masquerade_as(){
	
	$control = $_SESSION['control'] ;	
	
	if(!empty($_POST['username_to_morph_as']) && $_POST['morph_string'] == $_SESSION['control']){
		$morph_target = trim(mysql_prep($_POST['username_to_morph_as']));
		$person = get_user_details($morph_target);	
		
		if(!empty($person)){
			
		$_SESSION['user_before_morph'] = $_SESSION['username'];
		$_SESSION['username'] = trim(mysql_prep($_POST['username_to_morph_as']));
		$_SESSION['role'] =	$person['role'];
		
		}
		
	session_message('alert', 'Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!');
	redirect_to(BASE_PATH.'user/?user='.$morph_target);
	
	} 
	 
	if(isset($_GET['morph_target'])){ 
		$morph_target = trim(mysql_prep($_GET['morph_target']));
		$person = get_user_details($morph_target);	
	
		if($_GET['morph_string'] == $_SESSION['control']){

		unset($_SESSION['user_before_morph']);
		$_SESSION['username'] = trim(mysql_prep($morph_target));
		$_SESSION['role'] =	$person['role'];
		
		session_message('alert', 'Your are now viewing '.APPLICATION_NAME .' as <big>'.$morph_target .'</big>!');
		redirect_to(BASE_PATH.'?page_name=home');
		
		}
	}
	
	if(is_admin()){
		$action = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if(isset($_GET['user']) && $_GET['user'] !== $_SESSION['username']){
			
			$user= trim(mysql_prep($_GET['user']));
			}
		echo "<h2>Masquerade</h2><form method='post' action='{$action}'>
		View site as (person):<input type='text' name='username_to morph_as' value='{$user}' placeholder='user to masquerade as'>
		<input type='hidden' name='morph_string' value='{$control}'>
		<input type='submit' name='submit' value='Go'>
		</form>";
	}
	if(isset($_SESSION['user_before_morph'])){
		echo "<br>You are now masquerading as {$_SESSION['username']} . 
		<em><a href='".BASE_PATH."user/?user={$_SESSION['user_before_morph']}&morph_string={$_SESSION['control']}&morph_target={$_SESSION['user_before_morph']}'>switch back to {$_SESSION['user_before_morph']}</a></em>";
	}

}


function get_user_details($user){
	
	$user = trim(mysql_prep($user));
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `user` WHERE `user_name`='{$user}' LIMIT 1") 
	or die('Failed to "get user details "'. ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$num = mysqli_num_rows($query);
	
	if($num <= 0){ 
		status_message('error','There is no account in that name!');
		die();
	}else if($num === 1){
	$result = mysqli_fetch_array($query);
	unset($result['2']);
	unset($result['password']);
	return $result;
	}
	
}



function upload_user_pic(){
if (isset($_SESSION['username'])){
global $r;	
$r2 = str_ireplace('/regions/','',$r);
		$r = $r2;
$submit =  $_POST['submit'];
$uploaddir = $r.'/uploads/files/user/';
$uploadfile = $uploaddir . $_SESSION['username'].'.jpg';
$path = BASE_PATH .'uploads/files/user/'. $_SESSION['username'].'.jpg';
$rpath = $r.'/uploads/files/user/'. $_SESSION['username'].'.jpg';
$user = $_GET['user'];

# ONSUBMIT
if (isset($submit)){
   $type = $_FILES['image_field']['type'];
   $name = $_SESSION['username'];
   
   $path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

   if (isset($_SESSION['username']))
   { $parent = $_SESSION['username'];
	 $move = move_uploaded_file($_FILES['image_field']['tmp_name'], $uploadfile);
	//echo '$move = '.$move;
	//echo ' $uploadfile = '.$uploadfile;
		if($move ==1)
			{ 
			$small_path = resize_userpic_small($pic=$rpath);
			$small_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $small_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			
			$medium_path = resize_userpic_medium($pic=$rpath);
			$medium_path = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $medium_path) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			
				
			echo "<div class='success'>File is valid, and was successfully uploaded.\n</div>";
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `picture`='{$medium_path}', `picture_thumbnail`='{$small_path}' 
		WHERE `user_name`='{$user}'")
		or die("Could not save Picture!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		//if($query) { echo "Succesfully saved to DB!";} testing
	} else {
		echo "<div class='error'>Error : No file uploaded!\n</div>";
	}
	
	}
	
}
//echo 'Here is some more debugging info:' .$_FILES['image_field']['error']; //testing

	
	

	
	# UPLOAD FORM
	if($_SESSION['username']){
	
		if($user === $_SESSION['username'] || $_SESSION['role']==='admin' || $_SESSION['role']==='manager'){
		
		echo '<h2> Add / Change Picture </h2>
		<form action="'.htmlentities($SERVER["PHP_SELF"]) .'" method="post" enctype="multipart/form-data" class="form form-vertcal">
		<!-- MAX_FILE_SIZE must precede the file input field -->
		<p align="center"><input type="hidden" name="MAX_FILE_SIZE" value="5000000" /></p>
		<!-- Name of input element determines name in $_FILES array -->
		<input type="file" size="500" name="image_field"  value="">
		<input type="submit" name="submit" value="upload" class="submit">
		</form>';
		}
		
		$p = show_user_pic($user);
		echo $p['thumbnail'] .'<br>Current pic';
	}
}
}


function show_user_pic($user='',$pic_class='',$length=''){

	// if reward addon is active, Get reward badge
	if(addon_is_active('rewards')){
		$badge = get_reward_badge($user);
		}

	if($user !== ''){
		$user = trim(mysql_prep($user));
	}else{ $user = $_SESSION['username']; }

	if($length != ''){
		$dimensions = "width='{$length}' height='{$length}'";
		} else {$dimensions = '';}

$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `picture`, `picture_thumbnail` FROM `user` WHERE `user_name`='{$user}'")
or die("Unable to Select user pic!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$result=mysqli_fetch_array($query);
$time = time();
$pic_small = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
$pic_medium =  default_pic_fallback($pic=$result['picture'], $size='medium');

if(is_user_page() && ($_SESSION['user_being_viewed'] == $user || is_admin())){
$picture = '<span class="clear"><a href="'.BASE_PATH .'uploads/files/user/'.$user .'.jpg"  rel="prettyPhoto[\"'.$user.'_gal\"]">'.
'<img src="'.$pic_medium.'?str='.$time.'" alt="user picture" id="" class="thumbnail '.$pic_class.'" '.$dimensions.'></a>
</span><span class="badge padding-10">'.$badge.'</span>
<span class="inline-block"><a href="'.BASE_PATH.'user/?user='.$_GET['user'].'&edit=true"><button><i class="glyphicon glyphicon-camera"> </i></button> </a></span>';

} else { 	
$picture = '<span class="clear"><a href="'.BASE_PATH .'user/?user='.$user .'">'.
'<img src="'.$pic_medium.'?str='.$time.'" alt="user picture" id="" class="thumbnail '.$pic_class.'" '.$dimensions.'></a></span><span class="badge padding-5">'.$badge.'</span>';	
	}
$thumbnail = '<span class="thumbnail small-pic inline-block"><a href="'.BASE_PATH .'user/?user='.$user .'">'.
'<img src="'.$pic_small.'?str='.$time.'" alt="user picture" id="profile-thumbnail" class="'.$pic_class.' img-rounded"'.$dimensions.'></a><span class="badge padding-5 margin-3 ">'.$badge.'</span></span>';

$title = "<h1>" .ucfirst($_GET['user']) ."'s Profile </h1><hr> " ;	

if($user === $_SESSION['username']){
	
	$_SESSION['picture'] = '<a class="tooltip" href="'.BASE_PATH .'user/?user='.$result['user_name'] .'">'.
	'<img src="'.$pic_medium.'"></a>';
	$_SESSION['picture_thumbnail'] = '<a href="'.BASE_PATH .'user/?user='.$result['user_name'] .'" title="'.$result['user_name'] .'">'.
	'<img src="'.$pic_small.'"></a>';
	}
	
$output = array('picture'=>$picture, 'thumbnail'=>$thumbnail, 'title'=>$title);
return $output;
}


function show_user_edit_link(){
if (is_logged_in()){	
	if($_GET['user'] === $_SESSION['username'] || is_admin()){
		echo '<a href="' .BASE_PATH .'user/?user='.$_GET['user'].'&edit=true"> Edit my account </a>';
		}
	}
}

function show_user_delete_link(){
if (is_logged_in()){
	if($_GET['user'] === $_SESSION['username'] || is_admin()){
		$url_user = trim(mysql_prep($_GET['user']));
		$user = get_user_details($url_user);
		echo '<a href="' .BASE_PATH .'user/process.php?user='.$_GET['user'].'&delete=delete_user&id='.$user['id'].'&control='.$_SESSION['control'].'" onclick="return confirm(\'Are you sure you want to delete this user?\');"> Delete account </a>';
		}
	}
}

function show_user_profile($user=''){
	
	if($user===''){$user = $_GET['user'];}
	
	
	if (isset($_SESSION['username'])){
		
		$user_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `user` WHERE `user_name`='{$user}'") 
		or die("USER Selection failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		
		if($user_query){
		$row = mysqli_fetch_array($user_query);
		$_SESSION['user_being_viewed'] = $row['user_name'];
		}
		
					
			$check_funds_manager=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT status FROM addons WHERE addon_name='funds_manager'") 
			or die("funds manager checking failed!") .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
			
			if($check_funds_manager){
				
				$trans_link ="<a href ='" .BASE_PATH ."funds_manager/transaction_history.php?user={$user}" ."'>Transaction History</a>";
				}
				
		// LAST sEEN
		
			$last_seen = round(((time() - $row['last_login']) / 60 ),0);
			if($last_seen < 1 ){
				$suffix = " secs ago!";
			}else if($last_seen > 1 && $last_seen < 59 ){
				$suffix = " mins ago!";
			}else if($last_seen > 59 && $last_seen < 1439){
				$suffix = " hours ago!";
				$last_seen = round((($last_seen / 60)),0);
			}else if($last_seen > 1439 && $last_seen < 43169){
				$suffix = " days ago!";
				$last_seen = round((($last_seen / 24) / 60),0);
			}else if($last_seen >= 43169){
				$suffix = " months ago!";
				$last_seen = round(((($last_seen / 30) / 24) / 60),0);
				}
				
			echo "<br><em>Last seen <span class='green-text'>". $last_seen . $suffix ."</span></em><br>";

		# Profile fields  	
		if($_SESSION['username'] === $user){
		$profile = '<br>
		 <strong>Phone: </strong>'. $row['phone'] .'<br>'.
		'<img src="'.BASE_PATH.'uploads/files/default_images/coins-24.png"> <div class="green-text"><big>'. $row['site_funds_amount'] .'.00  </big></div>' ;
		if(addon_is_active('top_up_pin') && $_SESSION['user_being_viewed'] === $_SESSION['username']){ 
			echo '<a href="'.BASE_PATH.'funds_manager?action=load_top_up"'.'><button class="button button-primary"><i class="glyphicon glyphicon-usd"></i> Add funds </button></a>';
			}echo '<a href="'.BASE_PATH.'user/?user='.$_SESSION['username'].'&edit=true&action=set_bank_details"'.'><button class="button "><i class="glyphicon glyphicon-piggy-bank"></i> Bank details</button></a>';
		if(addon_is_active('payment')){
			echo '<a href="'.ADDONS_PATH.'payment/?action=payment_requested&control='.$_SESSION['control'].'"><button class="btn btn-warning "> Request payout <i class="glyphicon glyphicon-arrow-right"></i></button></a>';
			}
	
		echo $profile .$trans_link;
		}
			if(addon_is_available('messaging')){
				
			
			$unread = display_unread_messages();
			
				if($unread['count'] !== 0  && $_SESSION['username'] === $_SESSION['user_being_viewed']){
				$_SESSION['new_message_count'] = $unread['count'];
						
				
				echo '<strong> Messages :</strong><div class="green-text"><big> <a href="'.
				 ADDONS_PATH .'messaging">'. $unread['count'] .'</big></div>  unread </a>';
					}
				}
				
		} 
			
	}


	

function list_users(){
if (isset($_SESSION['username'])){	
	if(empty($_GET['user']) && !empty ($_SESSION['username'])){	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM  `user` ORDER BY  `id` DESC 
  LIMIT 0 , 30") or die('Could not get data:' . ((is_object( )) ? mysqli_error( ) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
  #echo "Data fetched succesfully"; //testing
  $user_list = "<h2> Users List</h2>";
  
  
  while($result = mysqli_fetch_array($query)){
	$picture = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
  	$user_list = $user_list 
  . "<table class=''>"
  . '<tr><td colspan="3" rowspan="6" width="auto" height="100%"'
  . ' align="left" valign="bottom">'
  .'<a id="pagelist" href="' .BASE_PATH .'user/?user=' .$result['user_name'] 
  .'"><img src="'.$picture
  .'" width="50" height="50 alt="user pic"> &nbsp<strong><big> ' 
  . $result['user_name'] 
  . '</big></strong></a>';
  if(is_admin()){   $user_list = $user_list. ''
  .'&nbsp &nbsp<a href="' 
  . BASE_PATH ."user/?user=" 
  . $result['user_name']
  . '&edit=true'
  . '" '
  . '>edit </a>'
  . "</td>"
  . '<td colspan="3" rowspan="6" width="auto" height="100%"'
  . ' align="left" valign="bottom">'
  . '&nbsp| &nbsp<a href="'
  . BASE_PATH ."user/process.php?" 
  . 'action='
  . 'delete_page&'
  . 'user_name='
  . $result['user_name']
  . '" '
  . '>delete </a>';
  }
  $user_list = $user_list. ''
  . "</td><hr></tr></table>";
	} echo $user_list;
  }
}
}

function edit_user($user=''){
	if (isset($_SESSION['username'])){	
		if(isset($_GET['user'])){
			$user = $_GET['user'];
		if($_GET['action'] !== 'set_bank_details'){
			echo "<h1>Edit <a href='".BASE_PATH.'user/?user='.$user."'>".strtoupper($_GET['user'])."</a>'s account </h1><hr>";
			
			upload_user_pic(); # Allows users, admins, or managers to upload pics to user profiles.
			
			# FETCH USER DETAILS
			$fetch_user=mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `user` WHERE `user_name`='{$user}'") 
			or die("No such user" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			$referrer = show_referrer();
			while( $row = mysqli_fetch_array($fetch_user)){
				
					$form = "<form method='post' action='process.php'>
							<input type='hidden' name='uid' value='{$row['id']}'>";
							
					if($_GET['field'] !== 'profile_pic'){		
						$form .= "<br>Password :<br><input type='password' name='password' placeholder='' >
							<br>Confirm password :<br><input type='password' name='confirm_password' placeholder=''>
							<br>Phone :<br><input type='number' name='phone' value='{$row['phone']}' placeholder='Phone number'>
							<br>Secret Question :<br> <span class='green-text'>\"".$_SESSION['secret_question']."\"</span>
							<br> Answer:<br><input type='text' name='secret_answer' value='' placeholder='answer'>
							<br> Referrer:<br><input type='text' name='ref_id' value='{$referrer['referrer_name']}' placeholder='refferrer'>";
							if($_SESSION['role']==='admin'){
							
							$form = $form ."<br>Role or level :<br><input type='text' name='role' placeholder='role or permissions level' value='".$row['role']."'>";
							}
						
					$form = $form ."<br><input type='submit' class='submit' name='submit' value='save'>	";	
					}		
					$form .= "</form>";
					echo $form;
				}
			
		}
				
		set_bank_details($user);
		}

	}
}


function get_bank_details($user){
	$result = get_user_details($user);
	$output = array();
	$output['id'] = $result['id'];
	$output['account_number'] = $result['bank_account_no'] ;
	$output['bank_name'] = $result['bank_name'];
	$output['full_name'] = $result['full_name'];
	return $output;
	}

function set_bank_details($user){
	
	$result = get_bank_details($user); //bank details only
	if(empty($result['account_number']) && $_GET['action'] == 'set_bank_details'){ // but if empty
		$result = get_user_details($user); // get all details this time
	
	$form = "<h1>Enter your bank details</h1><form method='post' action='process.php'>
				<input type='hidden' name='uid' value='{$result['id']}'>
				Full name :<input type='text' name='full_name' value=''><br>
				<em>Cannot be changed and must be the same name on your account!</em><br>
				Account no :<input type='number' name='account_no' value=''><br>
				Bank : <select name='bank'>";
				$banks_list = "Access Bank Plc,
						Citibank Nigeria Limited,
						Diamond Bank Plc,
						Ecobank Nigeria Plc,
						Enterprise Bank,
						Fidelity Bank Plc,
						First Bank of Nigeria Plc,
						First City Monument Bank Plc,
						Guaranty Trust Bank Plc,
						Heritage Banking Company Ltd.,
						Key Stone Bank,
						MainStreet Bank,
						Skye Bank Plc,
						Stanbic IBTC Bank Ltd.,
						Standard Chartered Bank Nigeria Ltd.,
						Sterling Bank Plc,
						Union Bank of Nigeria Plc,
						United Bank For Africa Plc,
						Unity Bank Plc,
						Wema Bank Plc,
						Zenith Bank Plc";
						
		$banks = explode(',',$banks_list);
		foreach($banks as $bank){
			$form .= "<option value='{$bank}'>{$bank}</option>";
			}
			$form .= "</select>";
		$form = $form ."<br><input type='submit' class='submit' name='submit-account' value='save'>		
				</form>";
		echo $form;
	} else {
			if(!empty($result['account_number']) && !empty($result['bank_name'])){
				link_to(BASE_PATH."user/?user={$user}", 'Return to profile');
				echo "<br><hr><h1>Bank Account Details</h1>";
				echo "You have already saved your account details and you cannot change it (except you contact support).";
				echo "<br>Your Bank details are: <hr><br>".
				"<strong>Account number: </strong>{$result['account_number']} <br>
				<strong>Full name: </strong>{$result['full_name']} <br>
				<strong>Bank: </strong>{$result['bank_name']} <br><br>";
				
				//link_to(BASE_PATH."user/?user={$user}", 'Return to profile');
			}
		}
}

function forgot_password(){
	
	//print_r($_POST);
	$query_string = $_SERVER['QUERY_STRING'];
	$username = trim(mysql_prep($_POST['username']));

	
	if($query_string === 'forgot_password'){
	echo '<div class="main-content-region light-blue">';	
	echo "<h2>You forgot your password?</h2>";
		
		if(isset($_POST['phone'])){
			$phone = mysql_prep($_POST['phone']);
			$question_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `secret_question` FROM `user` WHERE `phone`='{$phone}'") 
			or die (((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($question_query){
			$question_result = mysqli_fetch_array($question_query);
			
			echo "<br><big><p align='center'><strong>{$username} </strong>Your secret question is : ". $question_result['secret_question'].
		" ";	
			$fetched_question = true;
			}
			
		} else if(!isset($_POST['secret_question'])) {
			echo "<form action='".BASE_PATH."user/?forgot_password' method='POST'>
			<input type='number' name='phone' value='' placeholder='What is your phone number?'>
			<input type='submit' name='submit' class='button-submit' value='Submit'>
			</form>";	
			}

		if($fetched_question){
			echo "<form action='".BASE_PATH."user/?forgot_password' method='POST'>
			What is the answer to your secret Question?<br>
			<input type=text' name='secret_answer' value=''>
			<input type='hidden' name='secret_question' value='".$question_result['secret_question']."'>
			<input type='hidden' name='user' value='".$phone."'>
			<input type='submit' name='submit' class='button-submit' value='Submit'>
			</form>";
		} 

		 if(isset($_POST['secret_answer'])){
			$secret_question = trim(mysql_prep($_POST['secret_question']));
			$secret_answer = trim(mysql_prep($_POST['secret_answer']));
			$phone = trim(mysql_prep($_POST['phone']));	
			
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `secret_answer` FROM `user` WHERE `secret_question`='{$secret_question}'") 
			or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));	
			
			$answer_result = mysqli_fetch_array($query);
			//compare values
			if($answer_result['secret_answer'] === $secret_answer){
				$wrong_or_right = "<span class='green-text'> Correct! </span>";
				} else {$wrong_or_right = "<span class='red-text'> incorrect! </span>";}
				
				
			echo "<br><big><p align='center'><strong>{$username} </strong>Your secret question is : \"". $question_result['secret_question']."
		\" <strong>".$_POST['secret_answer']."</strong> and your secret answer is {$wrong_or_right} \"</p></big>";	
			
			//RESET PASSWORD
			$password = random_password();
			$new_password = sha1($password);
			$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `user` SET `password`='{$new_password}' WHERE `phone`='{$phone}' AND `secret_answer`='{$secret_answer}'") 
			or die("Failed to generate new password!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			
			if($query){
				echo "Your new password is : <big><strong>{$password}</strong></big> <br>";
				echo "Ensure that you change it to something you can remember or WRITE IT DOWN NOW!";
				sms_notify();
				}
			
		}
	echo '</div>';				
	}
}

function random_password( $length = 8 ) { 
// random password by http://hughlashbrooke.com/2012/04/23/simple-way-to-generate-a-random-password-in-php/

$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
$password = substr ( str_shuffle ( str_repeat ( $chars ,$length ) ), 0, $length );
return $password;
}

function login_register_switcher(){
	if (!isset($_SESSION['username'])){
	 $action = $_GET['action'];
		
		 // check that selected page is login page
	   if($action === 'login') {
		// show login form if login is clicked in menu
		show_login_form();  
	}elseif($action === 'register') {
		// show register form if requested page is register
		create_user(); 
	}else {show_login_form();}
	}
}

function default_pic_fallback($pic,$size=''){
	
	if(empty($pic) && $size ===''){
		$picture = BASE_PATH.'uploads/files/default_images/default-pic.png';
		return $picture;
	} else if(empty($pic) && $size === 'small'){
		$picture = BASE_PATH.'uploads/files/default_images/default-pic-small.png';
		return $picture;
	} else if(empty($pic) && $size === 'medium'){
		$picture = BASE_PATH.'uploads/files/default_images/default-pic.png';
		return $picture;	
	} else {
		$picture = $pic;
		return $picture; 
		}
					 
}


function show_new_users(){
	 $query_string = $_SERVER['QUERY_STRING'];
	 
	 if($query_string !== 'forgot_password'){	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `user_name`, `picture_thumbnail` FROM `user` ORDER BY `id` DESC LIMIT 6")
	 or die("Error selecting new users" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		echo '<div class="new-users-carousel center-block">';
	while($result = mysqli_fetch_array($query)){
			echo '<div class="inline-block"><a href="'.BASE_PATH.'user/?user='.$result['user_name'].'">';
			
			$picture = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');
			echo '<img src="'.$picture.'" title="'.$result['user_name'].'"></a></div>';		
		}
		echo '
            </div><br>';
			
		}
	
}

function user_being_viewed(){
	if(url_contains('user/?user=')){
		if(!empty($_GET['user'])){
			$user = $_GET['user'];
			}
		} return $user;
	}


function user_search(){

	$query_string = $_GET;
	if (!empty($_SESSION['username']) && empty($query_string)){	
	// Show search form
		echo '<form method="post" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
		<strong>Find user: </strong> <input type="text" name="search_name" value="" placeholder="username">
		<input type="submit" name="submit" value="Search" placeholder="user_name" class="button-primary">
		</form>';
		
	$show_more_pager = pagerize($start='',$show_more=20);
		$limit = $_SESSION['pager_limit'];
	if(isset($_POST['search_name'])){ 
		
		$string = trim(mysql_prep($_POST['search_name']));
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM user WHERE `user_name` LIKE '%{$string}%'") 
		or die("Search failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$info = '<h2>Searching for <span class="green-text">'.$string.'</span></h2><h3>Results are :</h3>';
	}	else { 
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM user ORDER BY id DESC {$limit} ") 
		or die("Cannot fetch users !" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
		$info = '<h2 align="center">Members</h2>';
		}
	$num = mysqli_num_rows($query);

		
		echo $info .'<section>' ;
		while ($result = mysqli_fetch_array($query)){
		if($num){
		$picture = default_pic_fallback($pic=$result['picture_thumbnail'], $size='small');	
		echo "<div class='thumbnail inline-block margin-10'><div class=''>"
				//.'<a href="'.BASE_PATH.'user/?user='.$result['user_name'].'">&nbsp;&nbsp;'.$result['user_name'].'</a>'	
				.'<a href="'.BASE_PATH.'user/?user='.$result['user_name'].'" title="'.$result['user_name'].'" alt="'.$result['user_name'].'">'
				."".'<img class="img-rounded" src="'.$picture.'">'.''
			."</div>".
			substr($result['user_name'],0,8) ;
			if(addon_is_active('rewards')){
		$badge = get_reward_badge($result['user_name']);
		}
			echo '<br><span class="badge padding-10">'.$badge ."</span></div></a>";
		} 
		
		}echo '</section>';	
		echo $show_more_pager;
	} 
}


function resize_userpic_small($pic='',$option='exact'){
	global $r;
	$width=50; 
	$height=50;
	$dest_folder= $r.'/uploads/files/user/small-size/'. $_SESSION['username'].'.jpg';
	//echo 'Destination folder is '.$dest_folder;
	$output = BASE_PATH.'uploads/files/user/small-size/'. $_SESSION['username'].'.jpg';
	/**$folder is the folder name, eg thumbnail, medium etc
	 * $option is one of : exact, portrait, landscape, auto, crop
	 * */
	
	
	// USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 100);

return $output;
	
} 

function resize_userpic_medium($pic='',$option='auto'){
	global $r;
	$width=240; 
	$height=240;
	$dest_folder= $r.'/uploads/files/user/medium-size/'. $_SESSION['username'].'.jpg';
	$output = BASE_PATH.'uploads/files/user/medium-size/'. $_SESSION['username'].'.jpg';
	/**$folder is the folder name, eg thumbnail, medium etc
	 * $option is one of : exact, portrait, landscape, auto, crop
	 * */
	
	
	// USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 100);

return $output;
}

function resize_userpic_large($pic='',$option='auto'){
	
	global $r;
	$width=600; 
	$height=600;
	$dest_folder= $r.'uploads/files/user/large-size/'. $_SESSION['username'].'.jpg';
	$output = BASE_PATH.'uploads/files/user/large-size/'. $_SESSION['username'].'.jpg';
	/**$folder is the folder name, eg thumbnail, medium etc
	 * $option is one of : exact, portrait, landscape, auto, crop
	 * */
	
	// USING THE RESIZE CLASS

// *** 1) Initialise / load image
$resizeObj = new resize($pic);

// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
$resizeObj -> resizeImage($width, $height, $option);

// *** 3) Save image ('image-name', 'quality [int]')

$resizeObj -> saveImage($dest_folder, 80);

return $output;
	
}

 // end of user functions file
 // in root/user/includes/functions.php
?>
