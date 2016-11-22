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
//print_r($_POST);
 
 #DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

?>	

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
echo '<div class="container">';


echo '<div class="main-content-region">';
 if(addon_is_active('rewards')){
	echo "<h1>Rewards</h1>";
	get_reward_points('fundraiser',2200);
	echo '<br>';
	
	claim_freebie();
	reward_referrals();
	add_reward_freebies();
	get_claimed_reward_freebies();
	
 }
echo '</div>';


echo '<div class="right-sidebar-region padding-10">';

echo '</div>';


echo "</div>".//end container
"<p></p><div class='footer-region'>";
do_footer();

echo "</div>";
?>

</body>
</html>
