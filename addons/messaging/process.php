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

$sender = $_SESSION['username'];

echo "<ul class='popout'>" .
		'<li id="add_page_form_link" class="float-right-lists">'	.
		'<a href="'.ADDONS_PATH .'messaging"> All</a>' .'</li>' .
		'<li id="add_page_form_link" class="float-right-lists">' .
		"<a href='" .ADDONS_PATH .'messaging/?send_message=yes' ."'>Write</a></li>" .
		'<li id="add_page_form_link" class="float-right-lists">' .
		"<a href='" .ADDONS_PATH .'messaging/?show_sent_messages=yes' ."'>Sent</a></li>" .
		'</ul><br>' ;

send_message($sender);




