<?php
#=======================================================================
#					- Template starts
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
  It can be optional if you want your addon to act independently.*/
 
$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
?>

<!-- START PAGE -->

<?php start_addons_page();#from root/inludes/functions.php

#					- Template Ends -
#=======================================================================


show_session_message();
process_contest_submission();

echo ' <section class="top-left-links">
		<ul>
			<li id="add_page_form_link" class="float-right-lists">
			' .'<a href="'.ADDONS_PATH .'contest"> Back to CONTESTS </a> </li>
		</ul>
		</section>';

?>

