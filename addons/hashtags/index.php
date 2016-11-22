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
	<?php show_search_form();?>
</section>
	
<!-- SECONDARY MENU -->
<section class="secondary">
	
	<?php if(addon_is_available(menus)){ get_secondary_menu_items();}?>  
</section>
 
 
 
<?php
echo '<div class="container">';

echo '<div class="top-right-sidebar-region">';

administer_hashtag();
delete_hashtag_participants();
echo '</div>';

echo '<div class="main-content-region padding-10">';
 if(addon_is_active('hashtags')){
 get_hashtag_details();
 }
show_session_message();
show_hashtags_page_title();

if($_POST['submit'] != 'Find Hashtag'){

start_a_discussion();
} else{
	search_hashtags();
	}



get_hashtag_posts();
echo '</div>';


echo '<div class="right-sidebar-region">';

add_hashtag('','',$show_form='yes','');
add_hashtag_participants();
show_participants();

if($_POST['submit'] != 'Find Hashtag'){
search_hashtags();
}
echo '</div>';

//add_hashtag($hashtag='', $show_form='yes');

echo "</div>".//end container
"<p></p><div class='footer-region'>";
do_footer();

echo "</div>";
?>

</body>
</html>
