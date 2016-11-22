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

start_addons_page();  
#						- Template ends -
#=======================================================================


#DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

#<!-- HEADER REGIONstart_page( START -->

echo '<section class="container"><h1>Sponsored Ads</h1><hr>';
echo '<div class="margin-10">';
show_session_message();
if(is_admin()){


}
view_ads();
if(is_logged_in()){
		 delete_item('ads',BASE_PATH.'ads/');
	echo '<div class="row">
	<div class="one-half column">';
	list_all_ads();
	echo '</div>';
	echo'<div class="one-half column">';
	list_ads_by_owner();
	echo '</div>';
	echo '</div>';
	
	echo '<div class="row">
	<div class="one-half column">';
	create_ads();
	echo '</div>';
	
	echo'<div class="one-half column">';
	ads_prices();
	echo '</div>';
	//crud_do('ads','create');
	echo '</div>';
	} 
	else { log_in_to_continue(); }
	

echo '<div>';
?>
</section>
<?php echo "<div class='footer-region'>";
do_footer();
echo "</div>";
?>
</body>
</html>
