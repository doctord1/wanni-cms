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

$r = dirname(dirname(dirname(__FILE__)));;
$r = $r .'/';
require_once($r .'includes/functions.php'); 

$page_title = set_page_title();

# LOADING WANNI CMS --END

start_addon_config_page();
?>


<!-- PAGE RENDERING STARTS-->


 
<!-- Page begins -->
<section class="header">
<br><h1> Configure "money_service" Addon [Core]</h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .'money_service">Settings </a>' ;?></li>
			<li class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .'">back to Addons </a>' ;?></li>
		</ul>
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
$status = 1;
$version = $details['version'];

if(isset($_POST['activate'])){
$activate = $_POST['activate'];
} else if(!empty($_GET['activate']) && $_SESSION['role']==='admin'){
$activate =	$_GET['activate'];
}

if(isset($_POST['deactivate'])){
$deactivate = $_POST['deactivate'];
} else if(!empty($_GET['deactivate']) && $_SESSION['role']==='admin'){
$deactivate =	$_GET['deactivate'];
}

if(isset($_POST['uninstall'])){
$uninstall = $_POST['uninstall'];
} else if(!empty($_GET['uninstall']) && $_SESSION['role']==='admin'){
$uninstall =	$_GET['uninstall'];
}

############################################

if(isset($activate)){
$path = htmlentities($required_file);
	
#Create tables

$create_basket_table = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `money_service_basket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `money_service_code` varchar(150) NOT NULL,
  `buyer` varchar(150) NOT NULL,
  `seller` varchar(150) NOT NULL,
  `quantity` int(6) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1") or die("Create basket table failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


$create_money_service_orders_table = mysqli_query($GLOBALS["___mysqli_ston"], "
  CREATE TABLE IF NOT EXISTS `money_service_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `money_service_code` varchar(150) NOT NULL,
  `buyer` varchar(100) NOT NULL,
  `seller` varchar(150) NOT NULL,
  `quantity` int(5) NOT NULL,
  `price` int(6) NOT NULL,
  `order_details` text NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1") or die("money_service Orders Create failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$create_money_service_table = mysqli_query($GLOBALS["___mysqli_ston"], "
  CREATE TABLE IF NOT EXISTS `money_service` (
  `money_service_id` int(11) NOT NULL AUTO_INCREMENT,
  `money_service_code` varchar(150) NOT NULL,
  `money_service_type` varchar(150) NOT NULL,
  `price` int(6) NOT NULL,
  `stock` int(4) NOT NULL,
  `description` varchar(255) NOT NULL,
  `seller` varchar(150) NOT NULL,
  `currency` varchar(11) NOT NULL,
  `author` varchar(15) NOT NULL,
  `status` varchar(11) NOT NULL,
  PRIMARY KEY (`money_service_id`),
  UNIQUE KEY `money_service_id` (`money_service_id`),
  KEY `money_service_code` (`money_service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ") or die("Failed to create money_service buyable item table" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$create_money_service_types_table = mysqli_query($GLOBALS["___mysqli_ston"], " 
  CREATE TABLE IF NOT EXISTS `money_service_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `money_service_type_name` varchar(150) NOT NULL,
  `money_service_type_description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1") or die("Failed to create money_service money_service type table" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `money_service_type` (`id`, `money_service_type_name`, `money_service_type_description`) VALUES
(1, 'default', 'default buyable item type')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

# then insert into addons table	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '{$addon_name}' ,'{$description}' ,'{$path}' ,'{$status}' ,'{$version}')") ; 

# INSTALL BLOCKS
	
block_create('money_service Catalog block', 'System block showing buyable items','get_grid_money_service();','money_service');

$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `sections`(`id`, `section_name`, `position`, `description`, `visible`) 
VALUES ('','{$addon_name}','4','{$description}','1')") or die(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$money_service_url = ADDONS_PATH.'money_service/catalog';
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `menus`(`id`, `menu_item_name`, `menu_type`, `position`, `visible`, `destination`, `parent`) 
VALUES ('','{$addon_name}','secondary','1','1','{$money_service_url}','{$addon_name}')") or die("Menu insert error!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));


	
if($query) { echo "<br>Successfully Activated ". $addon_name ;
	}
if($money_service_block_query){ echo "<br>Successfully installed {$block_name}";}

}

if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='$addon_name'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
}

if(isset($uninstall)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `sections` WHERE `section_name`='{$addon_name}'");
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `menus` WHERE `menu_item_name`='{$addon_name}'");
	$clear_money_service = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `money_service`,`money_service_basket`") or die("Failed to Drop table money_service" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$clear_money_service = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `money_service`") or die("Failed to Drop table money_service money_service" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$clear_money_service_orders = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `money_service_orders`") or die("Failed to Drop table money_service Orders" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$clear_money_service_type = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `money_service_type`") or die("Failed to Drop table money_service buyable type" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$uninstall_blocks_query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `blocks` WHERE `parent_addon`='{$addon_name}'") 
	or die(" Could not delete primary menu block!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	if($uninstall_blocks_query) { echo "Successfully uninstalled!". $addon_name .'';}
else { echo " Could not uninstall {$addon_name} block";}	
	
}


# GET ADDON DESCRIPTION
 
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `addons` WHERE `addon_name`='{$addon_name}'") ;
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

}
//end of save_config()

save_config();

?>

<?php 
 
 

show_config_form_buttons();

// CONFIG ENDS HERE


?>

<?php

?>


</section>

</body>
</html>
