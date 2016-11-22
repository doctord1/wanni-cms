<?php  ob_start();
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .ADDONS_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';

start_addons_page();  
echo '<style type="text/css">
.item{
background-color: darkcyan;
width: 320px;
height: 340px;
}

</style>';
#						- Template ends -
#=======================================================================


#DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

#<!-- HEADER START -->



# CUSTOM CODE HERE 
echo '<div class="container">';
echo "<h1></h1>";

if(is_admin()){
	
	echo "<div class='gainsboro'>";
	
	show_gen_pin_form();
	generate_pins();
	echo "</div><p></p>";
	
	echo "<div class='gainsboro'>";
	show_used_pins();
	echo "</div><p></p>";
	
	echo "<div class='gainsboro'>";
	show_unused_pins();
	echo "</div>";
	}
	
show_agent_progress();
echo '</div>';
do_footer();
?>


