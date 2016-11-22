<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit

 start_page(); if(addon_is_active(contest) === 0){
	 session_message('error', 'addon is not activated!');
	 redirect_to(BASE_PATH);
	 }get_contest_id(); //set_total_votes();
#						- Template ends -
#=====================================================================?>
	
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

<!-- MAIN START -->
<section class="container">
<h2>Contests</h2>
<?php 
	//print_r($_SESSION);

	echo "<div class='main-content-region'>";
	show_session_message(); 
	if(addon_is_available(users)){ logout_notify();}
	if(addon_is_available(messaging)){ new_message_notification();}
		
	
	echo "</div>";


# CUSTOM CODE HERE 
	
	echo get_contest_lists();
	
	
if(isset($_GET['contest_name'])){	

	echo "<div class='main-content-region'>";
	//log_in_to_continue();
	# Allow users to check their status in contests.
	contest_do_vote();
	show_contest_page();
	show_user_contest_entry_page();
	check_user_contest_status();
	show_all_categories();
		
	show_contest_entry_form();
	echo "</div>";
	
	echo "<aside class='right-sidebar-region'>";
	show_num_contestants();	
	show_registered_contest_entries();
	register_for_contest();
	show_contest_stats();
	get_menu_items('user');	
	echo "</aside>";
	
		
}
?>

<!-- RIGhT REGION -->	
<?php include_once('regions/includes/right_region.php'); ?>

</section>
<section class="footer-region">
<?php do_footer(); ?>
</section>
</body>
</html>
