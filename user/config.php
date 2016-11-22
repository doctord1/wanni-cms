<?php 
/**	===================================================================
*                   addon CONFIG TEMPLATE 
*	===================================================================
* THIS TEMPLATE CONTAINS CODE WRITTEN TO HELP YOU QUICKLY 
* AND EASILY  START WRITING CONFIGURATION FOR WANNI CMS addon.
* 
*	DO NOT EDIT CODE BETWEEN {TEMPLATE STARTS} and {TEMPLATE ENDS}
*
*		[UNLESS YOU ABOLUTELY KNOW WHAT YOU ARE DOING]	 
* ---------------------------------------------------------------------
*					 TEMPLATE STARTS
* --------------------------------------------------------------------
**/
# 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
*   It can be optional if you want your addon to act independently. 
**/

# LOADING WANNI CMS START --

$r = dirname(dirname(__FILE__));
$r = $r .'/';
require_once($r .'includes/functions.php'); 

$page_title = set_page_title();

# LOADING WANNI CMS --END
start_addon_config_page();
?>

<!-- Page begins -->
<section class="header">
<br><h1> Configure "User" [Core]</h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'user">Settings </a>' ;?></li>
	</ul>
</section>


<section class="config-submit"> 

<?php
	
function save_config(){	
require_once('details.php');	
	$r_me = dirname(__FILE__);
	$r_me = $r_me . '/';
	$r_me = $r_me;
	$r_me = $r_me .'includes/functions.php';
#	echo $r_me;	
	
// ADDON DETAILS HERE	
############################################

# Addon name in lowercase	
$addon_name = $details['name'];
#echo $addon_name; //TESTING
$description = $details['desc'];
//do not edit
$required_file = "" .$r_me ."";  
#echo $required_file ."WANNI";  // TESTING
$status = $_POST['status'];
$version = $details['version'];
$activate = $_POST['activate'];
$deactivate = $_POST['deactivate'];
$update = $_POST['update'];
$uninstall = $_POST['uninstall'];

//$upgrade_query = 'ALTER TABLE `user` ADD `agent VARCHAR(3) NOT NULL';
$update_query = 'ALTER TABLE `user` ADD `logged_in` VARCHAR( 3 ) NOT NULL AFTER `login_count`';
############################################

if(isset($activate)){
$path = htmlentities($required_file);

# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`, `core`) 
VALUES ('', '$addon_name' ,'$description' ,'$path' ,'$status' ,'$version','yes')") ; 

# INSTALL BLOCKS
$parent_addon = 'user';
$new_users = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `blocks`(`id`, `block_name`, `region`, `block_title`, `block_description`, `position`, `visible`, `content`, `function_call`, `parent_addon`, `show_title`, `page_visibility`) 
VALUES ('','New users','none','New Users','Block showing new members','1','1','','show_new_users();','user','0','home')")
 or die("Error inserting new users block " .((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
$user_login_block_func = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], 'show_login_form();') : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
$user_login_block_desc = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], 'User Login block') : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	
$user_login_block_query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `blocks`(`id`, `block_name`, `region`, `block_title`, `block_description`, `position`, `visible`, `content`, `function_call`, `parent_addon`, `show_title`) 
VALUES ('','user_login', 'none', 'User login', '{$user_login_block_desc}', '1', '1', '', '{$user_login_block_func}', '{$parent_addon}' ,'0')") or die ("INSERT 'User login' block failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

	
if($query) { echo "<br>Successfully Activated ". $addon_name ;
	}
if($user_login_block_query){ echo "<br>Successfully installed 'User Login' block";}

}


if(isset($update)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "{$update_query}");
	if($query) { status_message("Successfully upgraded!". $addon_name ); }
}

# GET ADDON DESCRIPTION
 
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `addons` WHERE `addon_name`='$addon_name'") ;
# or die("Cannot get addon description!") . mysql_error();

if($query) {
$result = mysqli_fetch_array($query);
# echo "description fetched !!";
}
 
# SHOW DESCRIPTION
if($result){
echo "<section class='holder'> <h2>Description</h2>". $result['description'] ."</section>";
}

# SHOW STATUS
if(isset($result['addon_name'])){
	echo "<section>" .strtoupper($result['addon_name']) ." is <strong class='green-text'>ACTIVE!</strong> </section>";} 
	else  { echo "<section> This Addon is <strong>DEACTIVATED! </strong> </section>";}

}
//end of save_config()

save_config();
show_config_form_buttons();
// CONFIG ENDS HERE

?>
</section>

</body>
</html>
