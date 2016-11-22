<?php
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/messaging'; #do not edit
require_once($r .'/includes/functions.php'); #do not edit
 start_addons_page();  
#						- Template ends -
#=======================================================================
//print_r($_SESSION);
echo $_SESSION['last_message_thread_id'];

#<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->
echo '<div id="new-pings">';
fetch_new_thread_messages();
echo '</div>';
