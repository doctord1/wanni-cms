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

# echo $r; // Testing only

//get local functions file
#$r2 = dirname(__FILE__);
#$r2 = $r2 .'/';
#require_once($r2 .'includes/functions.php'); 
# echo $r2; // For Testing only
$page_title = set_page_title();

# LOADING WANNI CMS --END

start_addon_config_page();
?>



<section class="header">
<br><h1> Configure PAGES [CORE]</h1>
</section>

<!-- TOP LINKS --->
<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'page">Settings </a>' ;?></li>
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
$description = $details['desc'];
//do not edit
$required_file = "" .$r_me ."";  
#echo $required_file ."WANNI";  // TESTING
$status = $_POST['status'];
$version = $details['version'];
$activate = $_POST['activate'];
$deactivate = $_POST['deactivate'];


############################################

if(isset($activate)){
$path = htmlentities($required_file);

# first check if installed

$check = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from addons where addon_name='$addon_name' AND status='0'") 
or die("Checking failed") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
if(!$check){
# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('', '$addon_name' ,'$description' ,'$path' ,'$status' ,'$version')") ; 
} else {$check= mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE addons SET status='1' WHERE addon_name='$addon_name'") 
	or die ("Failed to Update and REactivate" . $addon_name) . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));}

//Install block
block_create($block_name='Front promoted',$description='Pages promoted in frontpage',$function_call='show_front_promoted_posts();',$parent_addon='page');

	
if($query) { echo "Successfully Activated ". $addon_name ;}
}

if (isset($deactivate)) {
	
# clear the entry if available
$query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `addons` SET `status`='0' WHERE `addon_name`='$addon_name'")
 or die('Could not deactivate' .$addon_name .'!') . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
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
if($result['status'] === '1' && isset($result['addon_name'])){
	echo "<section>" .strtoupper($result['addon_name']) ." is <strong>ACTIVE!</strong> </section>";} 
	else if($result['status']=== '0' && isset($result['addon_name'])) { 
		echo "<section> This Addon is <strong>DEACTIVATED! </strong> </section>";}

}
//end of save_config()

save_config();
 
show_config_form_buttons();

?>


</section>

</body>
</html>
