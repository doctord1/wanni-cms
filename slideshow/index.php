<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
?>

<?php  start_addons_page();  
#						- Template ends -
#=======================================================================
?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->

<br>
<section class="container">
 <h1>Slideshow settings</h1>



<?php 
show_session_message();
# CUSTOM CODE HERE 
if (!isset($_SESSION['username'])){
 deny_access();

} else {
	 upload_slideshow_pics();
	 show_uploaded_slides();
	 }
?>

</section>


</body>
</html>
