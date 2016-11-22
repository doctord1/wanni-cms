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
* --------------------------------------------------------------------<?php 
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
<section class="header">
<br><h1> Configure "Messaging" Addon </h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .'messaging">Settings </a>' ;?></li>
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
$deactivate = $_GET['deactivate'];
}

if(isset($_POST['uninstall'])){
$uninstall = $_POST['uninstall'];
} else if(!empty($_GET['uninstall']) && $_SESSION['role']==='admin'){
$uninstall = $_GET['uninstall'];
}


############################################

if(isset($activate)){
$path = htmlentities($required_file);

# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '$addon_name' ,'$description' ,'$path' ,'$status' ,'$version')") ; 

$query = mysqli_query($GLOBALS["___mysqli_ston"], "
CREATE TABLE IF NOT EXISTS `messaging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reciever` varchar(60) NOT NULL,
  `sender` varchar(60) NOT NULL,
  `subject` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `parent_id` varchar(60) NOT NULL,
  `unread_sender` varchar(3) NOT NULL,
  `unread_reciever` varchar(3) NOT NULL DEFAULT 'yes',
  `reply` varchar(3) NOT NULL,
  `participants` varchar(150) NOT NULL,
  `created` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

			
block_create($block_name = 'site_contact_details',
			$description = 'Email, Phone and physical address of company / website',
			$function_call = '',
			$parent_addon = 'messaging');
}


	
if($query) { echo "Successfully Activated ". $addon_name ;
	}


if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='$addon_name'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
}


if(isset($uninstall)){
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `{$addon_name}`");
delete_block('contact_form');
delete_block('site_contact_details');
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

}
//end of save_config()

save_config();

show_config_form_buttons();

// CONFIG ENDS HERE

?>

</section>

</body>
</html>

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
<section class="header">
<br><h1> Configure "Messaging" Addon </h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .'messaging">Settings </a>' ;?></li>
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
$deactivate = $_GET['deactivate'];
}

if(isset($_POST['uninstall'])){
$uninstall = $_POST['uninstall'];
} else if(!empty($_GET['uninstall']) && $_SESSION['role']==='admin'){
$uninstall = $_GET['uninstall'];
}


############################################

if(isset($activate)){
$path = htmlentities($required_file);

# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('', '$addon_name' ,'$description' ,'$path' ,'$status' ,'$version')") ; 

block_create($block_name = 'contact_form',
			$description = 'Enables anonymous users to send feedback or contact admin',
			$function_call = 'show_contact_form();',
			$parent_addon = 'messaging');
			
block_create($block_name = 'site_contact_details',
			$description = 'Email, Phone and physical address of company / website',
			$function_call = '',
			$parent_addon = 'messaging');
}


	
if($query) { echo "Successfully Activated ". $addon_name ;
	}


if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='$addon_name'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
}


if(isset($uninstall)){
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `{$addon_name}`");
delete_block('contact_form');
delete_block('site_contact_details');
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

}
//end of save_config()

save_config();

?>

<?php 

show_config_form_buttons();

// CONFIG ENDS HERE

?>

</section>

</body>
</html>
