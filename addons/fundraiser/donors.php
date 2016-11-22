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


<section class="container">

<?php
# CUSTOM CODE HERE 

if (isset($_SESSION['username'])){
	if($_SESSION['username'] === $_POST['owner'] || $_SESSION['role'] === 'admin' || $_SESSION['role'] === 'manager'){
		$fundraiser_name = $_POST['fundraiser_name'];
		$owner=$_POST['owner'];
		
		show_fundraiser_donors_list($fundraiser_name,$owner);
		} else {deny_access();}
} else {
	 deny_access();
	}
 
?>
</section>



</body>
</html>
