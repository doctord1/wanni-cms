<?php 
#=======================================================================
#						- Template starts -

// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/

$r = dirname(dirname(dirname(__FILE__))); #do not edit
$r = $r .'/'; #do not edit
require_once($r .'money_service/includes/functions.php'); #do not edit
	
//$_SESSION['addon_home'] ='<a href="'.ADDONS_PATH. 'money_service/catalog/"> Catalog </a>';

  start_addons_page();  
#						- Template ends -
#=======================================================================


if(is_admin()){
echo '<section class="top-left-links">
	<ul>
		<li class="float-right-lists">
			<a href="'.ADDONS_PATH .'money_service">Settings </a></li>
		<li class="float-right-lists">
			<a href="'.ADDONS_PATH .'money_service/config">Configure </a></li>
		<li class="float-right-lists">
			<a href="'.ADDONS_PATH .'money_service/catalog">Catalog </a></li>
		<li class="float-right-lists">
			<a href="'.ADDONS_PATH .'money_service/cart.php">Basket </a></li>
	</ul>
</section>';
}

?>
<section class="container">
<?php
 show_session_message();
 show_money_service_catalog(); ?>
</section>

<div class="footer-region">
<?php do_footer(); ?>
</div>
