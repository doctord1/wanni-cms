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

 if(addon_is_active('project_manager')){
 style_project_manager();
 }
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
if(is_admin() && empty($_GET['job_title'])){
echo '<div class="top-left-links">
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<a href="'.ADDONS_PATH .'jobs/config">Configure </a></li>
	</ul>
</div>';
}
echo '<h1>Jobs Bids and Contracts </h1>';
echo '<div class="main-content-region">';

show_session_message();
//show_jobs_navigation();
search_jobs();
process_bids_submission();
accept_bid();

if($_GET['action'] == 'add_job'){
add_job();
}else if($_GET['action'] == 'edit_job'){
edit_job();
}
get_job_lists();


	
get_job_content();



show_bids();


echo '</div>'; // end main content region

echo '<div class="right-sidebar-region">'; 
place_bid(); 
submit_job_report();
echo '</div>';

echo '<div class="row padding-20">
<div class="col-md-12 well">';show_jobs_and_bidding_instructions();
echo '</div>
</div>';

echo "</div>".//end container
"<p></p><div class='footer-region'>";
do_footer();

echo "</div>";
?>

</body>
</html>
