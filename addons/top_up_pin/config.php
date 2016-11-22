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

?>


<!-- PAGE RENDERING STARTS-->

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title> <?php echo $page_title ?> </title>
<link href="../styles/style.css" rel="stylesheet">
</head>
<body>
 
<!-- HEADER REGION START -->
<section class="holder">
	<section class="top_bar"> <?php echo '<a href="' .BASE_PATH . '" class ="home-link">Home - ' .APPLICATION_NAME .'</a>'; ?>
		<div class="back-to-control"><a href="<?php echo ADMIN_PATH; ?>">GO TO ADMIN </a> 
		</div>
	</section>
</section>

 
<!-- Page begins -->
<section class="header">
<br><h1> <?php echo ucfirst($addon_name)." Addon";?></h1>
</section>


<section class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .$addon_name.'">Settings </a>' ;?></li>
		<li class="float-right-lists">
			<?php echo'<a href="'.ADDONS_PATH .'">back to Addons </a>' ;?></li>
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
	


if(isset($activate)){
$path = htmlentities($required_file);

$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "CREATE TABLE IF NOT EXISTS `top_up_pin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `top_up_pin` varchar(50) NOT NULL,
  `value` int(5) NOT NULL,
  `agent` varchar(150) NOT NULL,
  `used_by` varchar(150) NOT NULL,
  `date_generated` varchar(30) NOT NULL,
  `date_used` varchar(30) NOT NULL,
  `status` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;");

# then insert 	
$query = mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `addons`(`id`, `addon_name`, `description`, `required_files`, `status`, `version`) 
VALUES ('0', '{$addon_name}' ,'{$description}' ,'$path' ,'$status' ,'$version')") ; 

# INSTALL BLOCKS
	
if($query) { echo "<br>Successfully Activated ". $addon_name ;}
}

if(isset($deactivate)){
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "DELETE FROM `addons` WHERE `addon_name`='{$addon_name}'") 
	or die(" Could not deactivate  addon!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
	if($query) { echo "Successfully DeActivated!". $addon_name ;}
else { echo " Could not deactivate  addon!";}
}

if(isset($uninstall)){
$q1 = mysqli_query($GLOBALS["___mysqli_ston"], "DROP TABLE IF EXISTS `top_up_pin`");
}

# GET ADDON DESCRIPTION
 
$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `addons` WHERE `addon_name`='$addon_name'") ;
 or die("Cannot get addon description!") . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));

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

echo 
'<form enctype="multipart/form-data" method="POST" action="'. $SERVER["PHP_SELF"] . '">' .
'<input type="hidden" name="status" value="1"><br>' .
'<input type="submit" name="activate" value="ACTIVATE" class="activate-button"></form>';

echo 
'<form method="POST" action="'. $SERVER["PHP_SELF"] . '">' .
'<input type="hidden" name="status" value="0"><br>' .
'<input type="submit" name="deactivate" value="DEACTIVATE" class="deactivate-button"></form>';

echo 
'<form method="POST" action="'. $SERVER["PHP_SELF"] . '">' .
'<input type="submit" name="uninstall" value="UNINSTALL" class="uninstall-button"></form><p><hr></p>';

// CONFIG ENDS HERE

?>

</section>

</body>
</html>
