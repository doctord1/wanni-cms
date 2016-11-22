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

$r = dirname(dirname(dirname(__FILE__)));
$r = $r .'/';
require_once($r .'includes/functions.php'); 

$page_title = set_page_title();

# LOADING WANNI CMS --END

start_addon_config_page();
?>

 
<!-- Page begins -->
<section>
<br><br><h1> Configure "FUNDRAISER" Addon</h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'shop">Settings </a>' ;?></li>
		<li class="float-right-lists">
			<?php echo'<a href="'.BASE_PATH .'addons">back to Addons </a>' ;?></li>
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
# Drop table if exists



#Create table

$create_fundraiser = mysqli_query($GLOBALS["___mysqli_ston"], "

	CREATE TABLE IF NOT EXISTS `fundraiser` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `fundraiser_name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `perks` text NOT NULL,
  `target_amount` int(6) NOT NULL,
  `amount_raised` int(6) NOT NULL,
  `author` varchar(150) NOT NULL,
  `editor` varchar(150) NOT NULL,
  `status` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  `last_updated` varchar(50) NOT NULL,
  `start_date` varchar(50) NOT NULL,
  `end_date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`fundraiser_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") or die("Create fundraiser failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$create_perks = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `fundraiser_perks` (
  `id` int(11) DEFAULT NULL,
  `fundraiser_id` int(11) NOT NULL,
  `donation_amount` int(11) NOT NULL,
  `reward` text NOT NULL,
  `amount_available` int(11) NOT NULL,
  `amount_claimed` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1") or die("Create fundraiser perks failed" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$create_fundraiser_donors = mysqli_query($GLOBALS["___mysqli_ston"], "

CREATE TABLE IF NOT EXISTS `fundraiser_donors` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `donor` varchar(150) NOT NULL,
  `amount` int(6) NOT NULL,
  `fundraiser_name` varchar(255) NOT NULL,
  `recipient` varchar(150) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;") or die("Create fundraiser_donors failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

$q= mysqli_query($GLOBALS["___mysqli_ston"], "
INSERT INTO `fundraiser` (`id`, `fundraiser_name`, `reason`, `target_amount`, `amount_raised`, `author`, `editor`, `status`, `created`, `last_updated`, `start_date`, `end_date`) VALUES
(1, 'test', 'zilch brand', 500, 100, 'test', '', 'pending', '2015-06-06T14:03:53+01:00', '2015-06-06T14:03:53+01:00', '0000-00-00', '0000-00-00')");



# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '$addon_name' ,'$description' ,'$path' ,'$status' ,'$version')") ; 

//~ $fundraiser_section_query = mysql_query("INSERT INTO `sections`(`id`, `section_name`, `position`, `description`, `visible`) 
//~ VALUES ('0', 'fundraiser', '3', 'Raise funds for that plan, business or idea', '1')") 
//~ or die ("Failed to insert fundraiser section " . mysql_error());


# INSTALL BLOCKS
block_create('Fundraisers','Block showing recent fundraisers','get_fundraiser_grid();','fundraiser');
}

if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='{$addon_name}'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `sections` WHERE `section_name`='$addon_name'") ;
	if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
}

if(isset($uninstall)){
	
	$clear = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `$addon_name`") or die("Failed to Drop table {$addon_name}" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	$clear = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `fundraiser_donors`");
	
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

show_config_form_buttons();

// CONFIG ENDS HERE

?>


</section>

</body>
</html>
