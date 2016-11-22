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
 if(addon_is_active('testimonial')){
	echo "<h1>Testimonials</h1>";
	echo '<br>';
	//echo '<div class="row">
	//<div class="col-md-6">';
	show_testimonials();
	//echo '</div>';
	
	//echo '<div class="row">
	//<div class="col-md-5 offset-by-1">';
	add_testimonial();
	//echo'</div>';
	
	
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
