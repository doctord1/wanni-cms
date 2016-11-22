<?php require_once('details.php');
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

$r1 = dirname(__FILE__);
$r2 = $r1 .'/';
#echo $r2;
$r3 = dirname(dirname(dirname(__FILE__)));
require_once($r2 .'/includes/functions.php'); 
require_once($r3 .'/includes/functions.php'); 

$page_title = set_page_title();
# LOADING WANNI CMS --END

// ADDON DETAILS HERE	
############################################

# Addon name in lowercase	
$addon_name = $details['name'];
#echo $addon_name; //TESTING
$description = $details['desc'];
//do not edit
#echo $required_file ."WANNI";  // TESTING
$version = $details['version'];

############################################
start_addon_config_page();
?>

 
<!-- Page begins -->
<section class="header">
<br><h1> <?php echo ucfirst($addon_name)." Addon";?></h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .$addon_name.'">Settings </a>' ;?></li>
	</ul>
</section>


<section class="config-submit"> 

<?php
	
function save_config(){	

	$r_me = dirname(__FILE__);
	
	$r_me = $r_me . '/';
	$r_me = $r_me;
	$r_me = $r_me .'includes/functions.php';
	#echo $r_me;	
	$required_file = "" .$r_me ."";  
	global $addon_name;
	global $description;
	global $version;
	
	$status = $_POST['status'];
	$activate = $_POST['activate'];
	$upgrade = $_POST['upgrade'];
	$deactivate = $_POST['deactivate'];
	$uninstall = $_POST['uninstall'];
	$upgrade_query = '';
	#echo $addon_name;


if(isset($activate)){
$path = htmlentities($required_file);

# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '{$addon_name}' ,'{$description}' ,'$path' ,'$status' ,'$version')") ; 

$query = mysqli_query($GLOBALS["___mysqli_ston"], 
"CREATE TABLE IF NOT EXISTS `referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer` varchar(150) NOT NULL,
  `referree` varchar(150) NOT NULL,
  `rewarded` varchar(5) NOT NULL,
  `rewarded_by` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");	

$query = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `referral_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");	

if($query) { echo "<br>Successfully Activated ". $addon_name ;
	}

}


if(isset($upgrade)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "{$upgrade_query}");
	if($query) { status_message("Successfully upgraded!". $addon_name ); }
}


if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='{$addon_name}'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
}

if(isset($uninstall)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], 
"DROP TABLE IF EXISTS `{$addon_name}`");
"DROP TABLE IF EXISTS `referral_agents`");
if($query){ status_message('success',"{$addon_name} uninstalled successfully!"); }
	}
else { }	
	
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
	status_message('success', strtoupper($result['addon_name']) ." is <strong>ACTIVE!");
} else  { status_message('alert'," This Addon is <strong>DEACTIVATED! </strong>");
}


//end of save_config()

save_config();

show_config_form_buttons();

// CONFIG ENDS HERE

?>

</section>

</body>
</html>
