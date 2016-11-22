<?php
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
#echo $r;
?>

<!-- IMPORTANT!  ONLY EDIT BELOW THIS LINE -->


<?php

function show_three_column_region(){
	echo "<div class='main-content-region'><div class='three-column-region'>";
	do_three_column_region();
	echo "</div></div>";
}
show_three_column_region();

?>


<!-- THis view enables easy any individual styling of this region -->
