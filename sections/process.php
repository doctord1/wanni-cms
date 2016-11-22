<?php require_once('../includes/session.php');
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'-admin</a>';

start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#=======================================================================

		
		

if($_POST['edit_section'] ==='Save'){
	
	if($_SESSION['role'] ==='manager' || $_SESSION['role'] ==='admin'){
		
	$id = trim(mysql_prep($_POST['id_holder']));
	$section_name = trim(mysql_prep($_POST['section_name']));
	$position = trim(mysql_prep($_POST['position']));
	$visible = trim(mysql_prep($_POST['visible']));
	$description = trim(mysql_prep($_POST['description']));
	$parent_post_type = strtolower(trim(mysql_prep($_POST['parent_post_type'])));
	$is_category = strtolower($_POST['is_category']);
	$save_edit = trim(mysql_prep($_POST['edit_section']));
	
		$edit_query = mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE sections SET `section_name`='{$section_name}', 
		`position`='{$position}', `description`='{$description}', `visible`='{$visible}', 
		`parent_post_type`='{$parent_post_type}', `is_category`='{$is_category}' 
		WHERE `id`='{$id}'") or die("Edit query failed!" . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
			}
	if($edit_query) { status_message('success', 'Section edited successfully!');
	 
		}
}

delete_section(); 


go_back();
?>



