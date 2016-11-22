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
style_project_manager();  

#						- Template ends -
#=======================================================================


#DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->
?>
<!-- HEADER REGION START -->
	
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
	echo '<div class="container padding-20">';
	echo '<div class="row">';
	echo '<div class="main-content-region">';
	show_session_message();
	pm_color_codes();
	echo '<br>';

	search_projects();
	
	# Projects
	add_project();
	edit_project();
	show_projects_list();
	show_project_page();
	
	# Tasks
	show_task_page();
	add_task();
	edit_task();
	show_task_list();
	
	
	//
	if(!isset($_SESSION['role'])){	
	log_in_to_continue(); 
		
} else { }

	echo '</div>';
	
	
	
	echo '<div class="right-sidebar-region">';
	show_add_project_link();
	project_manager_menu();
	project_instructions();
	show_activity();
	echo '</div>';

//do_footer();
echo '</div>';//row	
echo '</div>';//container
?>
<!-- FOOTER -->
<?php include_once('../../regions/includes/footer_region.php');?>


</body></html>
