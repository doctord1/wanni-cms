<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .BASE_PATH . $addon_home .
'" class ="home-link">'.str_ireplace('_', ' ', $addon_home ).'</a>';
start_addons_page();  
#						- Template ends -
#=======================================================================

?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->

<section class='container'>

<?php  
# SHOW ADD FUNDS FORM

if (isset($_SESSION['username'])){
	
	#GET TRANSACTION HISTORY
	
	if(isset($_GET['get_trans_history'])){
	
		$person  = $_GET['get_trans_history'];
		get_site_funds_history($person);
	
	}else{
		
		$person = $_SESSION['username'];
		get_site_funds_history($person);
		
		}
	}
?>
</section>

</body>
</html>
