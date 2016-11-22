<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__));
$r = $r .'/rate'; #do not edit
require_once($r .'/includes/functions.php'); #do not edit
start_page();
show_top_bar(); //print_r($_SESSION);
#						- Template ends -
#=======================================================================
?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->


<section class="container">
 <h1>Rate</h1>
 
<?php  show_session_message();
echo "<ul>" .
		'<li id="add_page_form_link" class="float-right-lists">'	.
		'<a href="'.ADDONS_PATH .'rate?action=add_rate_type"> Add Rate type </a>' .'</li>' .
		'</ul><br>' ;

# CUSTOM CODE HERE 
if (isset($_SESSION['username'])){	
	
	if(is_admin()){
		list_rate_types();
		if($_GET['action']==='add_rate_type'){ add_rate_type();}
		 
	}
		if(isset($_GET['reg_user'])){
			get_page_content();
			contest_rate_user();
		} else if(isset($_GET['rating_for'])){				
			if($_GET['do_rate'] ==='yes'){					
			rate_user();
			}			
		}

} else { 
	
	deny_access();}
?>

</section>




</body>
</html>
