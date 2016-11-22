<?php
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');

$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
?>

<!-- START PAGE -->

<?php start_addons_page();

#======================================================================

//<!-- TOP RIGHT LINKS --> 
$admin = is_admin();

if($admin){ 

	echo '<section class="container"><h1> Pages List</h1>
	<span class="float-right-lists">
			<a href="'.BASE_PATH .'page/add">Add a new Page </a>
		</span>
		
	<div class="main-content-region"> ';
	show_session_message();
	delete_page_type();
	add_page_type();
	get_page_types();
	echo get_page_lists() .'  <br></div>';
	
	echo '<div class="right-sidebar-region">';
	list_page_types();
	echo '</div>';
	echo '</section>';
	
	} else { deny_access(); }
	
?>

</body>
</html>


<!-- Here we hide the primary menu items by default but allow for toggling them -->


<!-- STYLE GUIDE

use REGION classes eg <section class=""> or <nav class="">

.header-region
.footer-region
.sidebar-left
.sidebar-right
.menu-bar


place functions inside these and then style them in 
 -->
