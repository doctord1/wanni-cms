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

echo '<section class="container"><h1>Payment</h1>';
echo "<span>"; 
link_to(ADDONS_PATH."payment/?action=view_payout_reports",' View Payout Reports ','button','button'); echo "</span>";

echo "<span>";
link_to(ADDONS_PATH."payment/?action=payment_requested&control=".$_SESSION['control'],' Request payout ','button','text');
echo "</span><hr>";

view_payout_report();
request_payout();

?>
</section>
<?php echo "<div class='footer-region'>";
do_footer();
echo "</div>";
?>
</body>
</html>
