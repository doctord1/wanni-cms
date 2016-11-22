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
	$deactivate = $_POST['deactivate'];
	$uninstall = $_POST['uninstall'];
	
	#echo $addon_name;


if(isset($activate)){
$path = htmlentities($required_file);



$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "CREATE TABLE IF NOT EXISTS `project_manager_project` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `project_manager` varchar(150) NOT NULL,
  `path` varchar(255) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `created` varchar(150) NOT NULL,
  `last_updated` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  `parent` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_name` (`project_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");


$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `project_manager_task` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(150) NOT NULL,
  `parent` varchar(150) NOT NULL,
  `parent_type` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(150) NOT NULL,
  `project_manager` varchar(150) NOT NULL,
  `path` varchar(255) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `assigned_to` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL,
  `priority` varchar(10) NOT NULL,
  `reward` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_name` (`task_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");




# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '{$addon_name}' ,'{$description}' ,'$path' ,'1' ,'$version')") ; 
insert_blockable_function('project_manager','my_assigned_tasks();');

#ADD MENU ITEM
menu_item_create('Projects','primary',ADDONS_PATH.'project_manager','project_manager');

# INSTALL BLOCKS

block_create($block_name='my_task_assignments',$description='Block showing assigned tasks',$function_call='my_assigned_tasks();',$parent_addon='project_manager');


	
if($query) { echo "<br>Successfully Activated ". $addon_name ;
	
	}

}

if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='{$addon_name}'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	
	if($query) { echo "Successfully DeActivated!". $addon_name ; 
		menu_item_delete('Projects','primary');
	}
	
else { echo " Could not deactivate  addon!"; }
}

if(isset($uninstall)){
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_project`");
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_task`");
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_task_submissions`");
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_ticket`");
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `project_manager_suggestion`");	
$q = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `parent`='project_manager'")
 or die ("Failed to delete menu item" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
 
 if($q){ status_message('success','Project manager uninstalled successfully!!'); }
	}

	
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
	echo "<section>" .strtoupper($result['addon_name']) ." is <strong>ACTIVE!</strong> </section>";} 
	else  { echo "<section> This Addon is <strong>DEACTIVATED! </strong> </section>";}


//end of save_config()

save_config();

show_config_form_buttons();

// CONFIG ENDS HERE
?>



</section>

</body>
</html>
