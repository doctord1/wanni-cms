<?php  ob_start();
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you wa
nt your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .ADDONS_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';

start_page(); style_prettyPhoto();
#						- Template ends -
#=======================================================================

#DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->
?>
<!-- HEADER START -->
<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>	
	</div>
	<?php show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>
 
 


<?php
# CUSTOM CODE HERE 
echo '<div class="container">';
if(empty($_GET['owner'])){
	$_GET['owner'] = $_SESSION['username'];
	}
echo "<h1><a href='".BASE_PATH."user/?user=".$_GET['owner']."'>".ucfirst($_GET['owner'])."</a>'s Gallery</h1>";

echo '<div class="whitesmoke padding-10 ">';
upload_to_gallery(); 	
echo'</div>';

//echo '<div class="main-content-region">';
echo $_SESSION['status_message'];
show_user_gallery(0,20); 
//echo '</div>';






echo '</div>';//container end

?>


<!-- FOOTER -->
<?php include_once('../../regions/includes/footer_region.php');?>


</body></html>

