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
start_addon_config_page();

?>

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
	$deactivate = $_POST['deactivate'];
	$uninstall = $_POST['uninstall'];
	
	#echo $addon_name;


if(isset($activate)){
$path = htmlentities($required_file);

# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '{$addon_name}' ,'{$description}' ,'$path' ,'$status' ,'$version')") ; 

$query = mysqli_query($GLOBALS["___mysqli_ston"], 
"CREATE TABLE IF NOT EXISTS `{$addon_name}` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(150) NOT NULL,
  `contact_name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ");	

block_create($block_name = 'my contacts',$description = 'Show user contacts',$function_call ='view_contacts();',$parent_addon='contacts');

if($query) { status_message("success","Successfully Activated ". $addon_name) ;
	}

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


?>


</section>

</body>
</html>
