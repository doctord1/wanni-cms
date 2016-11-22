<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/fundraiser'; #do not edit

require_once($r .'/includes/functions.php'); #do not edit
start_page(); 
#						- Template ends -
#=======================================================================
start_addons_page();
?>
<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

<!-- HEADER REGION START -->


<section class="container">
<?php 
$back_url = $_SERVER['HTTP_REFERER'];
go_back($back_url);
status_message('alert','This addon has no configuration, or is still under construction!');
do_footer();
?>



</section>
</body>
</html>
