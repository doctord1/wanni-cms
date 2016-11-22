<?php  ob_start();
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(dirname(__FILE__)))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

start_addons_page();  
#						- Template ends -
#=======================================================================

#DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

#<!-- HEADER REGIONstart_page( START -->

echo '<div class="container">';

echo '<h1>Curriculum Vitae </h1>' ;
echo '<div class="main-content-region">';
if(addon_is_active('money_service')){
	
	show_user_money_services();
	}
echo '</div>'; // end main content region

echo '<div class="right-sidebar-region">'; 

echo '</div>';

echo "</div>".//end container
"<p></p><div class='footer-region'>";
do_footer();

echo "</div>";
?>

</body>
</html>
