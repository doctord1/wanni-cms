<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
start_addons_page();  
#=======================================================================

echo '<menu class="dropdown-menu">';
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM menus where parent_id='{$_SESSION['active_menu_id']}'") 
	or die('Error selecting Active menu children '.((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
	
	while($result = mysqli_fetch_array($q)){
		//echo '<li class=""><a href="' .$result['destination']. '">' .strtoupper(str_ireplace('-',' ',$result['menu_item_name'])). '</a></li>';
		}
	echo '</menu>';
