<?php 
ob_start();
ini_alter
('date.timezone','Africa/Lagos');


 // Start the output buffer

require_once('includes/functions.php');
$page_path = 'http://'.$_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];	
//~ $referer_string = $_SERVER['HTTP_REFERRER'];
if((!is_logged_in() && $page_path == BASE_PATH) 
|| (!is_logged_in() && string_contains($page_path,'?page_name=home'))
//~ || (!string_contains($referer_string,'facebook.com') 
//~ || !string_contains($referer_string,'addthis.com')) 
&& $_SESSION['free_view_count'] < 2){
	$_SESSION['free_view_count']++;
	redirect_to(BASE_PATH.'user/');
}

if(is_logged_in() && $page_path == BASE_PATH){
	redirect_to(BASE_PATH.'?page_name=home');
	}

//~ }else if(!url_contains('page_name=') && !url_contains('section_name=')){
	  //~ $destination = BASE_PATH . '?page_name=home';
	//~ //echo "<script> window.location.replace('{$destination}') </script>";
	//~ redirect_to($destination);
//~ }
$_SESSION['addon_home'] ='';
//start_page(); 
# 					DO NOT EDIT ABOVE THIS LINE
#----------------------------------------------------------------------
?>
	
<!-- NAVIGATION -->
<section class ="navigation"><?php do_header(); ?>

	<div class="menu-wrapper dropdown"><?php if(addon_is_available(menus)){ get_top_menu_items();}?>	
	</div>
	<?php show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>
 
 
 
<section class="container">	
	
<div class="main-content-region">
<?php  show_session_message();

if(!empty($_SESSION['total_pending_products']) && is_admin()){
	status_message('alert',$_SESSION['total_pending_products'] ." product <a href='".ADDONS_PATH."shop/?show=pending_approval'>awaiting approval</a>");
	}
		if(addon_is_available(users)){ logout_notify();}
		if(addon_is_available(messaging)){ new_message_notification();
			 }
?>	

</div>	

<!-- HIGHTLIGHT REGION -->
<?php include_once('regions/includes/highlight_region.php'); ?>



<?php
// $ismobile = check_user_agent('mobile');
// if($ismobile){} else {
//	if($_GET['page_name'] === 'home'){
//		include_once('regions/includes/3_column_region.php'); 
//		}
//}?>


<!-- MAIN CONTENT -->
<?php include_once('regions/includes/main_content_region.php'); ?>

<!-- LEFT REGION -->	
<?php include_once('regions/includes/left_region.php'); ?>

<!-- RIGhT REGION -->	
<?php include_once('regions/includes/right_region.php'); ?>

</section>
<!-- FOOTER -->
<?php include_once('regions/includes/footer_region.php');?>


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
