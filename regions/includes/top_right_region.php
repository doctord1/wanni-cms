<?php
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit



function show_top_right_sidebar(){
echo '<section class="top-right-sidebar-region">'; 
	do_top_right_sidebar();
	
  
echo '</section>';	
}
show_top_right_sidebar();
# THis view enables easy any individual styling of this region 

?>


