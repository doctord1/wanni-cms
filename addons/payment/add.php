
<?php
// 		LOAD FILES REQUIRED TO CONNECT WITH Wanni CMS

/** This gives you access too core functions and variables.
 *  It can be optional if you want your addon to act independently. **/
 
$r = dirname(dirname(__FILE__)); #do not edit
$r = $r .'/'; #do not edit

require_once($r .'includes/functions.php'); #do not edit 
require_once($r .'includes/title.php');

start_page();
show_top_bar();

?>

<br>




<!-- BACK LINK -->
<section class="container">
	<h1> Add Menu </h1>
	
	<ul>
		<li id="add_page_form_link" class="float-right-lists">
			<?php echo'<a href="../"> Back to menus </a>' ;?></li>
	</ul>
</section>

<section class="container"><hr><br>
	<div class="add_pages_form_holder"><?php 
	delete_menu();
	add_menu(); 
	?> </div>
</section>

