<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'includes/functions.php'); #do not edit
require_once('details.php');
$addon_home = $my_addon_name;
$_SESSION['addon_home'] = '<a href="' .ADDONS_PATH . $addon_home .
'" class ="home-link">'.$addon_home .'</a>';
?>

<?php  start_addons_page();
#						- Template ends -
#=======================================================================

#print_r($_POST);

#<!--  DO NOT EDIT ABOVE THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING -->

	echo '<section class="top-left-links">
			<ul>
				<li class="float-right-lists">
					<a href="'.ADDONS_PATH .'money_service/catalog">Catalog </a></li>
				<li class="float-right-lists">
					<a href="'.ADDONS_PATH .'money_service/cart.php">Basket </a></li>
			</ul>
		</section>';
?>
<section class="container">
<?php show_session_message();
checkout();
show_order_form();
 ?>
</section>
