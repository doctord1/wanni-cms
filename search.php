<?php 
ob_start();
require_once('./includes/functions.php');
	
start_page(); 
# 					DO NOT EDIT ABOVE THIS LINE
#----------------------------------------------------------------------
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

<section class="container">
<div class="main-content-region">
<?php 
		if(addon_is_available(users)){ logout_notify();}
		if(addon_is_available(messaging)){ new_message_notification();}
		
		
?> 
<!-- HIGHTLIGHT REGION -->
<?php include_once('regions/includes/highlight_region.php'); ?>


</div>
<!-- MAIN CONTENT -->
<div class="main-content-region"><h2>Search results</h2>
	
<?php 

if($_GET['section_name']==='fundraiser'){
do_search('fundraiser','fundraiser_name');
} else{
	 do_search(); 
	 }
?>
</div>
<!-- RIGhT REGION -->	
<?php include_once('regions/includes/right_region.php'); ?>

<!-- ADS REGION  -->	
<?php #include_once('regions/ad_region.php'); ?>

</section>
<!-- FOOTER -->
<?php include_once('regions/includes/footer_region.php') ;
	
?>


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
