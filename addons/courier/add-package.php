<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit

?>

<?php  start_addons_page();  
#						- Template ends -
#=======================================================================

?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->
<section class='container'>
	
	
<?php go_back(); add_package(); 


?>
</section>
