
<?php 
ob_start();
require_once('../includes/functions.php');

route_post_activity_values();
	
start_page(); 
# 					DO NOT EDIT ABOVE THIS LINE
#----------------------------------------------------------------------
?>



	
<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>
		
	</div>
	
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>

<section class="container">
<?php 
echo $_SESSION['status_message'];

		if(addon_is_available(users)){ logout_notify();}
		if(addon_is_available(messaging)){ new_message_notification();}
		show_search_form();
		do_search();
?> 
<!-- HIGHTLIGHT REGION -->
<?php include_once('regions/includes/highlight_region.php'); ?>


<!-- NO LEFT REGION -->



<?php $ismobile = check_user_agent('mobile');
if($ismobile){} else {
	if($_GET['page_name'] === 'home'){
		include_once('regions/includes/3_column_region.php'); 
		}
}?>



<!-- MAIN CONTENT -->
<?php

include_once('regions/includes/main_content_region.php'); ?>

<!-- RIGhT REGION -->	
<?php include_once('regions/includes/right_region.php'); ?>

<!-- ADS REGION  -->	
<?php #include_once('regions/ad_region.php'); ?>

</section>
<!-- FOOTER -->
<?php include_once('regions/includes/footer_region.php') ?>


</body></html>

<!-- STYLE GUIDE

use REGION classes eg <section class=""> or <nav class="">

.header-region
.footer-region
.left-sidebar-region
.right-sidebar-region
.navigation-region
.main-content-region


place functions inside these and then style them.
 -->
