<?php
require_once('constants.php');

include_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .ADDONS_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
$_SESSION['page_context'] = $addon_home;

function set_page_title() {
	$title_left = APPLICATION_NAME;
	
		if(isset($_POST['page_name']) && ($_POST['page_name'] !== 'home')){
			$title_right = $_POST['page_name'];
		}else if(isset($_SESSION['page_context']) && empty($_POST['page_name'])){
			$title_right = $_SESSION['page_context'];
		}
			
	if(isset($title_left)) { 
		$title_tag = $title_left .' - ' . $title_right;
		} 
		else { $title_tag = "Wanni CMS" .' -' .$title_right; }
	return $title_tag; 
}

function set_style_sheet(){
$stylesheet = '<link href="http://'.BASE_PATH .'styles/style.css" rel="stylesheet">';
echo $stylesheet;	
}
?>

