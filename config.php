<?php ob_start();
require_once('includes/connect.php');
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
 
$r = dirname(__FILE__); #do not edit
$r .= '/'; #do not edit
#echo $r ."<br>";
include_once($r .'includes/functions.php'); #do not edit

show_top_bar();
echo '
	<!DOCTYPE html>
	<html lang="en">
	<head>';
		
	echo '<meta charset="utf-8"/><meta name="description" content="">
	<title>Install Wanni-CMS</title>
	
	<!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  	<link rel="stylesheet" href="'.BASE_PATH.'styles/geniusaid.css" type="text/css" media="screen" />';

#======================================================================
#						TEMPLATE ENDS
#======================================================================

?>

<section class='container'>

<h2> Configure Wanni CMS </h2>



<?php

if($_POST['submit'] ==='Reinstall WanniCMS' ){install_core();}

function save_config(){		
// CATCH ALL THE VARIABLES SENT VIA POST	
	
$application_name = trim(mysql_prep($_POST['application_name']));
$welcome_message = trim(mysql_prep($_POST['welcome_message']));
$base_path = $_POST['base_path'];
if($_POST['subfolder'] !==''){
$sub_folder = trim(mysql_prep($_POST['sub_folder'] .'/'));
}else { $sub_folder = NULL;}

$sub_folder= $sub_folder;

$base_path = htmlentities($base_path .$sub_folder);
if ($_POST['admin_folder'] === '') {
	$admin_folder = 'admin';
} else {
$admin_folder = trim(mysql_prep($_POST['admin_folder']));
}
$url = $_SERVER["PHP_SELF"];
$submitted = $_POST['submitted'];

$r = dirname(__FILE__);//try to locate the base functions file
if($sub_folder !==''){
$r = $r .'includes/functions.php';
} else {
$r = $r . "/includes/functions.php";
}
$default_func = htmlentities($r);
$stylesheet = trim(mysql_prep($_POST['stylesheet']));
//echo $default_func;

// TEST THAT ALL IS WELL TILL HERE
// echo $base_path .'<br>';

if (isset($submitted)) {
	
// IF WE RECIEVED ANY SUBMISSIONS FROM THE FORM,
// WIPE THE CONFIG TABLE BEFORE SAVE 
//SINCE WE ONLY WANT ONE SET OF VALUES


$clear = mysqli_query($GLOBALS["___mysqli_ston"], "TRUNCATE config") or die("Truncate failed!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
#$reset = mysql_query("DELETE * FROM addons") or die("Reset failed!" . mysql_error());
install_required();


// SAVE USER SUBMITTED VALUES TO DATABASE

$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `config`(`application_name`, `welcome_message`, `base_path`, `admin_folder_name`, `sub_folder_name`, `default_functions`, `stylesheet`) 
VALUES ('$application_name' ,'$welcome_message', '$base_path' ,'$admin_folder' ,'$sub_folder', '$default_func', '$stylesheet') 
") or die('Could not save Configuration!' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));



// NOTIFY USER OF SUCCESS OR FAILURE

if(!$query) {
	
	die("Database insert failed!". ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	}
	
else {
	$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `installed` SET `value`='yes' WHERE id=1");
	 echo "<div class='message-notification'> Configuration saved successfully!</div>";
	 }
}
}//end of save_config()

save_config();



function create_admin(){

	if(isset($_POST['create_user'])){
	
	$username = trim(mysql_prep($_POST['username']));
	$password = trim(mysql_prep($_POST['password']));
	$hashed_password = sha1($password);
	
	$create_user_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `user` 
	(`id`, `user_name`, `password`, `created_time`, `last_login`, 
	`login_count`, `phone`, `site_funds_amount`, `role`, `picture`, `picture_thumbnail`) 
	VALUES ('', '{$username}', '{$hashed_password}', CURRENT_TIMESTAMP, '', '2', '', '500', 'admin', '', '')") 
	or die("Errorr creating superadmi!" .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	if($create_user_query){
		status_message('success',"Admin account created successfully<br> You are now logged in ");
		
		$_SESSION['username'] = 'test' ;
		$_SESSION['user_id'] = 1;
		$_SESSION['role'] = 'admin';
		
		// INSTALL DEFAULT PAGES HERE SINCE I NOW KNOW THE BASE PATH
		$time = date('c');
		$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
		INSERT INTO `page` (`id`, `section_name`, `page_name`, `page_type`, `menu_type`, `position`, `visible`, `content`, `created`, `last_updated`, `author`, `editor`, `allow_comments`, `promote_on_homepage`, `destination`) VALUES
		(1, 'none', 'home', 'page', 'primary', 1, 1, '', '', '', 'superadmin', 'test', 'no', 'no', '".BASE_PATH. "?page_name=home}'),
		(2, 'none', 'sections', 'page', 'primary', 2, 1, '', '', '{$time}', 'superadmin', 'test', 'no', 'no', '".BASE_PATH. "?page_name=sections'),
		(3, 'contest', 'sol laptop giveaway', 'contest', 'none', 1, 1, 'Tell+us+why+you+should+get+the+sol+laptop', '{$time}', '{$time}', 'test', 'test', '', '', '".ADDONS_PATH. "contests/?contest_name=sol laptop giveaway')
		");
		
		$destination = $_SERVER['PHP_SELF'];
		echo "<script> window.location.replace('{$destination}') </script>";
		#header("Location: $destination"); exit;
		}
	}
	if(!is_admin()){
	echo '<div class="step2">Step 2</div><h2>Create Super-admin account</h2>
	<form method="post" action="'. $_SERVER["PHP_SELF"] .'">' .'
	<input type="hidden" name="username" value = "superadmin">
	Your username is "superadmin": <br> choose a password below<br>
	<input type="text" name="password" placeholder="password">
	<input type="submit" name="create_user" value="Create account" class="submit"></form><hr><br>';
	}
	
}


 
 $query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from config");
 $config = mysqli_fetch_array($query);
 echo 

'<div class="main-content-region">
<div class="config-center" align="center">';

//<form method="POST" action="'. $_SERVER["PHP_SELF"] . '">' .
//<input type="submit" name="submit" value="Reinstall WanniCMS">
//</button></form>';


if($config['application_name'] !== 'default'){
	echo "<div class='completed'>Step 1 Completed !</div>";
	} else { echo '<div class="step1" align="center">Step 1</div>'; }

echo '<h1>Very important settings</h1>' . 
'<form method="POST" action="'. $_SERVER["PHP_SELF"] . '">' .
'<big>APPLICATION NAME: </big><br><input type="text" name="application_name" value="'.$config['application_name'].'" "placeholder="Name of your Applictaion"><p>' .
'<big>WELCOME MESSAGE: </big><br><input type="text" name="welcome_message"  value="'.$config['welcome_message'].'" placeholder="Optional welcome message to the viewer"><p>' .
'<big>BASE PATH: </big><br>' .
'<em>Path to your server root eg {http://example.com/}</em><br>' .
'<input type="text" name="base_path" value ="http://' . $_SERVER["SERVER_NAME"] .'/" placeholder="http://localhost/"><br>' .
'<p><big>SUB FOLDER/DOMAIN: </big><br>' .
'Is Wanni CMS in a subfolder? eg <em>{http://example.com/subfolder}</em>  If Yes, then type in the name of the "subfolder"' .
'<br><input type="text" name="sub_folder" value="'.$config['sub_folder_name'].'"><br>' .
'<p><big>ADMIN FOLDER: </big><br>' .
'If you changed the name of your admin folder [for security reasons], type in the new name here <em> {defaults to admin}</em><br>' .
'<input type="text" name="admin_folder" value="'.$config['admin_folder_name'].'" placeholder="admin"><br>' .
'<br><big>STYLESHEET:</big><br> Name of theme or style you want to use for your applictaion <em>(leave blank to use default)</em><br>
<input type="text" name="stylesheet" placeholder="stylesheet" value="'.$config['stylesheet'].'"><br>' .
'<input type="submit" name="submitted" value="Save"></form>' .
'</div></div>' ;





echo '<div class="right-sidebar-region" align="center"><br>';
if($config['application_name'] !== 'default'){
$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `username` FROM `users` WHERE `username`='superadmin'");
	$result = mysqli_num_rows($q);
	if($result < 1){
		create_admin();
		} 
}

 if (is_admin()) {
	echo '<div class="completed">Step 2 Completed !</div>';
	echo '<div class="step4">Step 3</div><br><br>
	<a href="'.ADDONS_PATH.'"><button class="submit">
	<h2>Install Addons here</h2></button></a>'.'<br>';
	

 upload_image($folder='default_images', $name='logo', $instruction= "name your logo 'logo.png'") .
 upload_image($folder='default_images', $name='favicon', $instruction= "name your favicon 'favicon.png'");

echo '</div>';

}
#<!--  ENABLE CHANGE OF LOGO PICTURE AND ICON -->


// CONFIG ENDS HERE
	

?>
</section>
</body>
</html>
