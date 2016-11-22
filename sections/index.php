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
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'-admin</a>';
start_addons_page();   
$message = $_SESSION['status_message'];
echo $message; $_SESSION['status_message']='';
#						- Template ends -
#=======================================================================
?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->


<section class="container">

<?php
# CUSTOM CODE HERE 
if (!is_logged_in()){
 deny_access();

} else { 
if($_SESSION['role']==='admin' || $_SESSION['role']==='manager'){	
		
	echo '<span class="main-content-region">
		<ul>
			<li id="add_page_form_link" class="float-right-lists">
				<a href="'.BASE_PATH .'sections/add">Add section </a></li>
			<li id="show_blocks_form_link" class="float-right-lists">
				<a href="'.BASE_PATH .'sections/"> List sections</a> </li>
		</ul>
	</span>';
	#SECTIONS
	delete_section();	 
	list_sections();
	list_categories();
	
	#section ITEMS
	edit_section();
	
	
	
	
} else { deny_access();}
 }
?>
</section>



</body>
</html>
