<?php
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
#echo $r;
?>

<!-- IMPORTANT!  ONLY EDIT BELOW THIS LINE -->


<?php

function show_highlight_content(){
	echo '<div class="main-content-region">';
	do_highlight(); 
	
	echo '</div>';
}
show_highlight_content();


?>


<!-- THis view enables easy any individual styling of this region -->
