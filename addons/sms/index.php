<?php  ob_start();
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
#echo $r;
require_once($r .'includes/functions.php'); #do not edit

start_addons_page();  
#						- Template ends -
#=======================================================================


#DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

#<!-- HEADER REGIONstart_page( START -->

echo '<section class="container"><h1>SMS</h1><hr>';
echo '<div class="margin-10">';
if(is_admin()){
check_balance();
set_gateway();
get_users_numbers();
send_sms_bulk();

}
echo '<div>';
?>
</section>
<?php echo "<div class='footer-region'>";
do_footer();
echo "</div>";
?>
</body>
</html>
