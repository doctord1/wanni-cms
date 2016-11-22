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

?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->



<?php 
# CUSTOM CODE HERE 
echo "<section class='container'>";
echo show_session_message();

if (isset($_SESSION['username']) && addon_is_active('messaging')){
	
		echo "<ul class='popout'>" .
				'<li id="add_page_form_link" class="float-right-lists">
				<a href="' .ADDONS_PATH .'messaging/">All Messages</a></li>' .
				'</ul>';
				
	if($_GET['show_sent_messages'] =='yes'){
					
					display_sent_messages();
					}
				
	if(isset($_GET['delete'])){
					
					delete_message();
					}

	
	if(!isset($_GET['reply_to']) && empty($_GET['show_sent_messages'])){
	
				
			if(!isset($_GET['send_message'])){
				
				if(!isset($_GET['mid'])){
					
			$unread = display_unread_messages();
			
			
			echo "<section class='padding-10 popout'><hr>" .$unread['display'] .'</section>';
			
			display_read_messages();
			
			} else {
					display_message_thread();
					
					}
			}
				if(isset($_GET['send_message'])){
					
					show_message_form();
					}
				
	} else { reply_message();}
	
} else { deny_access();}
echo "</section>";
do_footer();

