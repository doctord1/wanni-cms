<?php 
#======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#======================================================================

?>

<?php

transfer_funds();
?>



