<?php
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
#echo $r;

# IMPORTANT!  ONLY EDIT BELOW THIS LINE 
# -------------------------------------------------------
function show_main_content(){
echo "<div class='main-content-region'>";	
if($_GET['page_name'] != 'talk'){	
get_page_content();
}
echo "</div>";

echo "<div class='main-content-region'>";

do_main_content();	
echo "</div></div>	"; // here to correct a bug 
} 
show_main_content();

?>
<!-- THis view enables easy any individual styling of this region -->
