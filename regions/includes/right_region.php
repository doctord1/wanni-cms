<?php
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



function show_right_sidebar(){
echo '<section class="right-sidebar-region">'; 
	do_right_sidebar();
	
  
echo '</section>';	
}
show_right_sidebar();
# THis view enables easy any individual styling of this region 

?>


