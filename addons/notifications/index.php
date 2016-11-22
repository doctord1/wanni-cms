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

start_page();  
#						- Template ends -
#=======================================================================
//print_r($_SESSION);
 
 #DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->


?>	


	
<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>	
	</div>
	<?php// show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>
  

<section class='container padding-20'>
	<h2>Notifications</h2>
<?php include_once('regions/includes/left_region.php'); ?>


	
<?php show_session_message();
save_notification();


echo '<div class="col-md-7 col-xs-12">';
show_notifications($addon='all');
echo '</div>';

echo '<div class="col-md-5 col-xs-12 whitesmoke padding-20">';
add_notification();
echo '</div>';	
?>	

<!-- </div> -->

<?php // include_once(BASE_PATH .'regions/includes/right_region.php');?>

</section>

<!-- FOOTER -->
<?php echo "<div class='footer-region'>";
do_footer();
echo "</div>";
?>
</body></html>

